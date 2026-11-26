<?php

namespace App\Models\Transaction\Inspection;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResalesAttachment extends Model
{
    use HasFactory;

    protected $table = 'resales_attachments';

    protected $guarded = [];

    const OWNER_PRODUCT     = 'product';
    const OWNER_TRANSACTION = 'transaction';

    const KIND_DRAWING     = 'drawing';
    const KIND_CERTIFICATE = 'certificate';

    /**
     * Kolom content memuat base64 berkas yang bisa megabyte-an. Daftar lampiran
     * tidak pernah memerlukannya — hanya endpoint unduh yang membacanya.
     */
    public function scopeWithoutContent($query)
    {
        return $query->select(
            'id',
            'owner_type',
            'owner_key',
            'kind',
            'file_name',
            'mime_type',
            'file_size',
            'created_by',
            'created_at',
            'updated_at'
        );
    }
}
