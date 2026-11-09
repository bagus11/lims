@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <style>
        .signature-pad-wrap {
            border: 1px dashed #cbd5e1;
            border-radius: 10px;
            background: #fff;
            position: relative;
        }
        #signature_pad {
            width: 100%;
            height: 220px;
            touch-action: none;
            display: block;
            border-radius: 10px;
            cursor: crosshair;
        }
        .signature-hint {
            position: absolute;
            top: 8px;
            left: 12px;
            font-size: 11px;
            color: #94a3b8;
            pointer-events: none;
        }
        .signature-preview {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            padding: 10px;
            text-align: center;
        }
        .signature-preview img { max-height: 110px; max-width: 100%; }
    </style>

    <div class="row mx-2 mt-4 d-flex justify-content-center">
        <div class="col-lg-5 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <strong class="cardTitle">Ubah Kata Sandi</strong>
                    <p class="mb-0 mt-1 text-muted">Kata sandi baru minimal 8 karakter.</p>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <p class="mb-1">Kata Sandi Saat Ini</p>
                        <input type="password" id="current_password" class="form-control" autocomplete="current-password">
                        <span class="current_password_error text-danger message_error"></span>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1">Kata Sandi Baru</p>
                        <input type="password" id="new_password" class="form-control" autocomplete="new-password">
                        <span class="password_error text-danger message_error"></span>
                    </div>
                    <div class="mb-3">
                        <p class="mb-1">Konfirmasi Kata Sandi Baru</p>
                        <input type="password" id="new_password_confirmation" class="form-control" autocomplete="new-password">
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" id="btn_update_password" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-key mr-1"></i> Simpan Kata Sandi
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <strong class="cardTitle">Tanda Tangan</strong>
                    <p class="mb-0 mt-1 text-muted">Dipakai sebagai tanda tangan Anda pada report PDF. Gambar di kotak berikut memakai mouse atau layar sentuh.</p>
                </div>
                <div class="card-body">
                    <div class="signature-pad-wrap mb-3">
                        <span class="signature-hint">Gambar tanda tangan di sini</span>
                        <canvas id="signature_pad"></canvas>
                    </div>
                    <span class="signature_error text-danger message_error"></span>
                    <div class="signature-preview">
                        <p class="mb-1 text-muted" style="font-size:11px">Tanda tangan tersimpan saat ini</p>
                        @if ($signature)
                            <img id="signature_current" src="{{ $signature }}" alt="Tanda tangan tersimpan">
                        @else
                            <p id="signature_current_empty" class="text-muted mb-0" style="font-size:12px">Belum ada tanda tangan tersimpan.</p>
                            <img id="signature_current" src="" alt="" style="display:none">
                        @endif
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="button" id="btn_clear_signature" class="btn btn-sm btn-secondary">
                        <i class="fa-solid fa-eraser mr-1"></i> Bersihkan
                    </button>
                    <button type="button" id="btn_save_signature" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-signature mr-1"></i> Simpan Tanda Tangan
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ asset('lims') }}/profile-setting.js"></script>
@endpush
