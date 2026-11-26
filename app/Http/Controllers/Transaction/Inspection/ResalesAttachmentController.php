<?php

namespace App\Http\Controllers\Transaction\Inspection;

use App\Helper\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Master\MasterProductResalesGood;
use App\Models\Setting\Approval\ApprovalHistory;
use App\Models\Transaction\Inspection\ResalesAttachment;
use App\Models\Transaction\Inspection\ResalesGood;
use Illuminate\Http\Request;

/**
 * Lampiran berkas Resales Goods: sertifikat vendor per transaksi dan drawing
 * PDF per produk master. Berkas disimpan base64 di tabel resales_attachments,
 * terpisah dari tabel induk supaya tidak ikut tertarik di query daftar.
 */
class ResalesAttachmentController extends Controller
{
    const MAX_BYTES = 5 * 1024 * 1024;

    private function allowedMimes(): array
    {
        return [
            'application/pdf',
            'image/png',
            'image/jpeg',
            'image/gif',
            'image/webp',
        ];
    }

    private function canAccessLocation($locationId): bool
    {
        $scope = auth()->user()->transactionScope();
        if ($scope === 'all') {
            return true;
        }
        if ($scope === 'location') {
            return (string) $locationId === (string) auth()->user()->kode_kantor;
        }

        return false;
    }

    /** Boleh melihat: sesuai lokasi, pembuat transaksi, atau approver terkait. */
    private function canViewTransaction(ResalesGood $header): bool
    {
        if ($this->canAccessLocation($header->location_id)
            || (int) $header->created_by === (int) auth()->user()->id) {
            return true;
        }

        return ApprovalHistory::where('transaction_code', $header->transaction_code)
            ->where('inspection_type', 4)
            ->where('user_id', auth()->user()->id)
            ->exists();
    }

    /**
     * Validasi kepemilikan sekaligus hak akses. Mengembalikan pesan error bila
     * ditolak, atau null bila boleh.
     */
    private function guardOwner(string $ownerType, string $ownerKey, bool $forWrite): ?string
    {
        if ($ownerType === ResalesAttachment::OWNER_PRODUCT) {
            if (!MasterProductResalesGood::where('id', $ownerKey)->exists()) {
                return 'Master product not found';
            }
            if ($forWrite && !auth()->user()->canManageModule('view-master_product_resales_goods')) {
                return 'You are not allowed to manage this master data';
            }
            return null;
        }

        $header = ResalesGood::where('transaction_code', $ownerKey)->first();
        if (!$header) {
            return 'Transaction not found';
        }
        if (!$this->canViewTransaction($header)) {
            return 'You are not allowed to access this transaction';
        }
        // Setelah lock transaksi tidak boleh diubah lagi, termasuk lampirannya.
        if ($forWrite && (int) $header->status >= 2) {
            return 'Locked transaction can no longer be modified';
        }

        return null;
    }

    private function normalizeOwnerType($value): ?string
    {
        $value = (string) $value;

        return in_array($value, [ResalesAttachment::OWNER_PRODUCT, ResalesAttachment::OWNER_TRANSACTION], true)
            ? $value
            : null;
    }

    private function normalizeKind($value): ?string
    {
        $value = (string) $value;

        return in_array($value, [ResalesAttachment::KIND_DRAWING, ResalesAttachment::KIND_CERTIFICATE], true)
            ? $value
            : null;
    }

    public function getResalesAttachments(Request $request)
    {
        $ownerType = $this->normalizeOwnerType($request->owner_type);
        $kind      = $this->normalizeKind($request->kind);
        $ownerKey  = trim((string) $request->owner_key);

        if (!$ownerType || !$kind || $ownerKey === '') {
            return ResponseFormatter::error(null, 'Invalid attachment request', 400);
        }

        if ($message = $this->guardOwner($ownerType, $ownerKey, false)) {
            return ResponseFormatter::error(null, $message, 403);
        }

        $data = ResalesAttachment::withoutContent()
            ->where('owner_type', $ownerType)
            ->where('owner_key', $ownerKey)
            ->where('kind', $kind)
            ->orderBy('id', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function uploadResalesAttachment(Request $request)
    {
        $ownerType = $this->normalizeOwnerType($request->owner_type);
        $kind      = $this->normalizeKind($request->kind);
        $ownerKey  = trim((string) $request->owner_key);

        if (!$ownerType || !$kind || $ownerKey === '') {
            return ResponseFormatter::error(null, 'Invalid attachment request', 400);
        }

        if ($message = $this->guardOwner($ownerType, $ownerKey, true)) {
            return ResponseFormatter::error(null, $message, 403);
        }

        $request->validate([
            'attachment' => 'required|file|mimes:pdf,png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $file = $request->file('attachment');

        // mimes: menilai dari ekstensi; cek tipe asli supaya berkas yang
        // ekstensinya disamarkan tidak lolos.
        if (!in_array($file->getMimeType(), $this->allowedMimes(), true)) {
            return ResponseFormatter::error(null, 'Only PDF or image files are allowed', 422);
        }
        if ($file->getSize() > self::MAX_BYTES) {
            return ResponseFormatter::error(null, 'Maximum file size is 5 MB', 422);
        }

        $attachment = ResalesAttachment::create([
            'owner_type' => $ownerType,
            'owner_key'  => $ownerKey,
            'kind'       => $kind,
            'file_name'  => $file->getClientOriginalName(),
            'mime_type'  => $file->getMimeType(),
            'file_size'  => $file->getSize(),
            'content'    => base64_encode(file_get_contents($file->getRealPath())),
            'created_by' => auth()->user()->id,
        ]);

        return ResponseFormatter::success(
            ['id' => $attachment->id, 'file_name' => $attachment->file_name],
            'Attachment uploaded'
        );
    }

    public function downloadResalesAttachment(Request $request)
    {
        $attachment = ResalesAttachment::find($request->id);
        if (!$attachment) {
            abort(404, 'Attachment not found');
        }

        if ($this->guardOwner($attachment->owner_type, $attachment->owner_key, false)) {
            abort(403, 'You are not allowed to access this attachment');
        }

        $binary = base64_decode($attachment->content, true);
        if ($binary === false) {
            abort(500, 'Attachment is corrupted');
        }

        // PDF & gambar dibuka inline supaya bisa langsung dilihat/dicetak.
        return response($binary, 200, [
            'Content-Type'        => $attachment->mime_type,
            'Content-Disposition' => 'inline; filename="' . addslashes($attachment->file_name) . '"',
            'Content-Length'      => strlen($binary),
        ]);
    }

    public function deleteResalesAttachment(Request $request)
    {
        $attachment = ResalesAttachment::find($request->id);
        if (!$attachment) {
            return ResponseFormatter::error(null, 'Attachment not found', 404);
        }

        if ($message = $this->guardOwner($attachment->owner_type, $attachment->owner_key, true)) {
            return ResponseFormatter::error(null, $message, 403);
        }

        $attachment->delete();

        return ResponseFormatter::success(null, 'Attachment deleted');
    }
}
