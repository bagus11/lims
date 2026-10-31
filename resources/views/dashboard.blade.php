@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <style>
        .qc-dashboard {
            margin-top: 1.75rem;
        }
        .qc-panel {
            border: 1px solid rgba(23, 155, 174, .10);
            box-shadow: 0 18px 42px rgba(15, 23, 42, .06);
        }
        .qc-hero {
            position: relative;
            overflow: hidden;
            border-radius: 24px;
            padding: 2rem;
            min-height: 100%;
            background: linear-gradient(135deg, #0f766e 0%, #179bae 45%, #1e3a8a 100%);
            color: #fff;
        }
        .qc-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at top right, rgba(255, 255, 255, .20), rgba(255, 255, 255, 0) 30%),
                radial-gradient(circle at bottom left, rgba(255, 255, 255, .12), rgba(255, 255, 255, 0) 35%);
        }
        .qc-hero > * {
            position: relative;
            z-index: 1;
        }
        .qc-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            border-radius: 999px;
            padding: .35rem .75rem;
            background: rgba(255, 255, 255, .14);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
        .qc-hero__title {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.2;
            margin: 1rem 0 .65rem;
        }
        .qc-hero__desc {
            max-width: 620px;
            font-size: 12px;
            color: rgba(255, 255, 255, .84);
            line-height: 1.75;
            margin-bottom: 1.5rem;
        }
        .qc-hero__stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .85rem;
        }
        .qc-hero__stat {
            padding: 1rem;
            border-radius: 18px;
            background: rgba(255, 255, 255, .12);
            border: 1px solid rgba(255, 255, 255, .14);
        }
        .qc-hero__stat-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: rgba(255, 255, 255, .72);
            margin-bottom: .45rem;
        }
        .qc-hero__stat-value {
            font-size: 21px;
            font-weight: 800;
            line-height: 1;
        }
        .qc-hero__stat-note {
            margin-top: .35rem;
            font-size: 10px;
            color: rgba(255, 255, 255, .72);
        }
        .qc-mini-card {
            border-radius: 24px;
            padding: 1.4rem;
            background: #fff;
            height: 100%;
        }
        .qc-mini-card__header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .qc-mini-card__title {
            margin: 0;
            color: #0f172a;
            font-size: 16px;
            font-weight: 800;
        }
        .qc-mini-card__subtitle {
            margin: .3rem 0 0;
            color: #64748b;
            font-size: 11px;
            line-height: 1.5;
        }
        .qc-chip {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .3rem .65rem;
            font-size: 10px;
            font-weight: 700;
            background: rgba(23, 155, 174, .10);
            color: #0f766e;
            white-space: nowrap;
        }
        .qc-progress-list,
        .qc-activity-list {
            display: grid;
            gap: .9rem;
        }
        .qc-progress-item,
        .qc-activity-item {
            border-radius: 18px;
            padding: 1rem;
            background: #f8fbfd;
            border: 1px solid rgba(148, 163, 184, .16);
        }
        .qc-progress-top,
        .qc-activity-top {
            display: flex;
            justify-content: space-between;
            gap: .75rem;
        }
        .qc-progress-name,
        .qc-activity-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .qc-progress-meta,
        .qc-activity-meta {
            font-size: 10px;
            color: #64748b;
            margin-top: .2rem;
        }
        .qc-progress-bar {
            margin-top: .85rem;
            height: 8px;
            border-radius: 999px;
            background: rgba(148, 163, 184, .18);
            overflow: hidden;
        }
        .qc-progress-bar span {
            display: block;
            height: 100%;
            border-radius: 999px;
            background: linear-gradient(90deg, #14b8a6, #179bae);
        }
        .qc-status {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            border-radius: 999px;
            padding: .32rem .65rem;
            font-size: 10px;
            font-weight: 700;
        }
        .qc-status--ok {
            background: rgba(34, 197, 94, .12);
            color: #15803d;
        }
        .qc-status--warn {
            background: rgba(245, 158, 11, .12);
            color: #b45309;
        }
        .qc-status--info {
            background: rgba(37, 99, 235, .10);
            color: #1d4ed8;
        }
        .qc-section-title {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 1rem;
        }
        .qc-table-wrap {
            overflow-x: auto;
            border-radius: 22px;
            background: #fff;
            padding: 1rem;
        }
        .qc-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 .75rem;
        }
        .qc-table thead th {
            border: 0;
            font-size: 10px;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: #64748b;
            padding: 0 .9rem;
        }
        .qc-table tbody tr {
            box-shadow: 0 8px 22px rgba(15, 23, 42, .04);
        }
        .qc-table tbody td {
            background: #fff;
            padding: .95rem .9rem;
            font-size: 11px !important;
            color: #0f172a;
            vertical-align: middle;
            border-top: 1px solid rgba(148, 163, 184, .16);
            border-bottom: 1px solid rgba(148, 163, 184, .16);
        }
        .qc-table tbody td:first-child {
            border-left: 1px solid rgba(148, 163, 184, .16);
            border-top-left-radius: 14px;
            border-bottom-left-radius: 14px;
        }
        .qc-table tbody td:last-child {
            border-right: 1px solid rgba(148, 163, 184, .16);
            border-top-right-radius: 14px;
            border-bottom-right-radius: 14px;
        }
        .qc-tag {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: .28rem .6rem;
            font-size: 10px;
            font-weight: 700;
            background: #ecfeff;
            color: #0f766e;
        }
        #apporval_container {
            display: grid;
            gap: .85rem;
            padding: 0;
            margin: 0;
        }
        #apporval_container .list-group-item {
            margin: 0 !important;
            padding: 1rem !important;
            max-height: none !important;
            border-radius: 18px;
            border: 1px solid rgba(148, 163, 184, .16);
            background: #f8fbfd;
        }
        #apporval_container label {
            display: block;
            margin-bottom: .25rem;
            font-size: 12px !important;
            font-weight: 800 !important;
            color: #0f172a;
        }
        #apporval_container p {
            margin: 0;
            color: #64748b;
            font-size: 10px !important;
            line-height: 1.55;
        }
        #apporval_container .btn {
            min-width: 34px;
            min-height: 34px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            box-shadow: 0 10px 18px rgba(23, 155, 174, .16);
        }
        #apporval_container .btn-info {
            background: linear-gradient(135deg, #179bae, #1e88c9);
            border-color: transparent;
        }
        .qc-lab-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 1rem;
        }
        .qc-lab-box {
            border-radius: 18px;
            padding: 1.1rem;
            background: linear-gradient(180deg, #f8fbfd 0%, #ffffff 100%);
            border: 1px solid rgba(148, 163, 184, .16);
        }
        .qc-lab-box h6 {
            margin: 0 0 .35rem;
            color: #0f172a;
            font-size: 13px;
            font-weight: 700;
        }
        .qc-lab-box p {
            margin: 0;
            color: #64748b;
            font-size: 11px !important;
        }
        @media (max-width: 1199.98px) {
            .qc-hero__stats {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (max-width: 991.98px) {
            .qc-dashboard {
                margin-top: 1rem;
            }
        }
        @media (max-width: 767.98px) {
            .qc-hero {
                padding: 1.35rem;
            }
            .qc-hero__title {
                font-size: 22px;
            }
            .qc-hero__stats,
            .qc-lab-grid {
                grid-template-columns: 1fr;
            }
            .qc-mini-card {
                padding: 1rem;
            }
            .qc-table-wrap {
                padding: .75rem;
            }
        }
    </style>

    <div class="container-fluid qc-dashboard">
        <div class="row">
            <div class="col-xl-8 mb-4">
                @php
                    $authUser = auth()->user();
                    $roleName = $authUser->getRoleNames()->implode(', ') ?: 'Tanpa Role';
                @endphp
                <div class="qc-hero shadow">
                    <span class="qc-eyebrow">
                        <i class="ni ni-atom"></i>
                        Dashboard QC Lab
                    </span>
                    <h1 class="qc-hero__title">Halo, {{ $authUser->name }}</h1>
                    <p class="qc-hero__desc">
                        Anda masuk sebagai <strong>{{ $roleName }}</strong>. Berikut ringkasan transaksi
                        dan approval sesuai hak akses Anda.
                    </p>
                    <span class="qc-eyebrow">
                        <i class="ni ni-badge"></i>
                        {{ $roleName }}
                    </span>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card qc-panel qc-mini-card">
                    <div class="qc-mini-card__header">
                        <div>
                            <p class="qc-mini-card__title">Approval Inbox</p>
                            {{-- <p class="qc-mini-card__subtitle"></p> --}}
                        </div>
                        {{-- <span class="qc-chip">Dynamic</span> --}}
                    </div>

                    <ul class="list-group list-group-flush list my--3" id="apporval_container"></ul>
                </div>
            </div>
        </div>

        @include('dashboard.charts')

        @include('layouts.footers.auth')
    </div>
    @include('transaction.inspection.goods.modal.add-wall_thickness')
    @include('transaction.inspection.material.modal.detail-pellet')
    @include('transaction.inspection.material.modal.detail-resin')
    @include('dashboard.modal.approval.laboratory.detail-laboratory')
    @include('dashboard.modal.approval.resales.detail-resales')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/dashboard/dashboard.js"></script>
@endpush
