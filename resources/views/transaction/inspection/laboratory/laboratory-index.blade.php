@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <style>
        .laboratory-status {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: 10px;
            font-weight: 700;
            border-radius: 999px;
            padding: .35rem .65rem;
        }
        .laboratory-result-item {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            overflow: hidden;
        }
        .laboratory-result-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            background: #f8fafc;
            padding: .9rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }
        .laboratory-result-body { padding: 1rem; }
        .laboratory-result-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(140px, 1fr));
            gap: .8rem;
        }
        .laboratory-measurement-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
            gap: .6rem;
        }
        .laboratory-result-grid p,
        .laboratory-measurement-wrap p {
            color: #475569;
            font-size: 10px !important;
            font-weight: 700;
            margin-bottom: .35rem;
        }
        .laboratory-standard {
            color: #0f766e;
            font-size: 10px;
            font-weight: 700;
            text-align: right;
        }
        .laboratory-summary { margin-bottom: 1rem; }
        .laboratory-summary .row > div > p {
            margin-bottom: .3rem;
            font-size: 11px;
            color: #475569;
        }
        .laboratory-summary .row > div > p[id^="label_laboratory_"] {
            color: #0f172a;
            font-weight: 700;
        }
        .laboratory-history-item {
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 0;
            font-size: 11px;
        }

        /* --- Detail modal: wide shell + per-parameter tab layout --- */
        .modal-lab-detail {
            max-width: none;
            width: 95vw;
            margin: 1.75rem auto;
        }
        .modal-lab-detail .modal-content {
            height: 92vh;
        }
        .modal-lab-detail .modal-body {
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow: hidden;
        }
        .laboratory-detail-split {
            display: flex;
            gap: 1rem;
            flex: 1 1 auto;
            min-height: 0;
        }
        .laboratory-param-nav {
            flex: 0 0 260px;
            width: 260px;
            overflow-y: auto;
            padding-right: .5rem;
            border-right: 1px solid #e2e8f0;
        }
        .laboratory-param-panel {
            flex: 1 1 auto;
            min-width: 0;
            overflow-y: auto;
            padding-right: .25rem;
        }
        .laboratory-param-progress {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .04em;
            color: #64748b;
            padding: 0 .6rem .5rem;
        }
        .laboratory-param-link {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: .5rem .6rem;
            margin-bottom: .2rem;
            border: 1px solid transparent;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            color: #334155;
            cursor: pointer;
        }
        .laboratory-param-link:hover { background: #f1f5f9; }
        .laboratory-param-link.active {
            background: #e6fffa;
            border-color: #14b8a6;
            color: #0f766e;
        }
        .laboratory-param-dot {
            flex: 0 0 8px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
        }
        .laboratory-param-dot.is-good { background: #22c55e; }
        .laboratory-param-dot.is-nogood { background: #ef4444; }
        .laboratory-param-seq {
            flex: 0 0 auto;
            min-width: 18px;
            color: #94a3b8;
            font-size: 10px;
            text-align: right;
        }
        .laboratory-param-link.active .laboratory-param-seq { color: #0f766e; }
        .laboratory-param-name {
            flex: 1 1 auto;
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .laboratory-param-divider {
            height: 1px;
            background: #e2e8f0;
            margin: .6rem .6rem;
        }

        @media (max-width: 991.98px) {
            .modal-lab-detail { width: 100vw; margin: 0; }
            .modal-lab-detail .modal-content { height: 100vh; border-radius: 0; }
            .laboratory-detail-split { flex-direction: column; gap: .5rem; }
            .laboratory-param-nav {
                flex: 0 0 auto;
                width: 100%;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 0 0 .5rem;
                border-right: 0;
                border-bottom: 1px solid #e2e8f0;
            }
            #laboratory_param_nav { display: flex; gap: .4rem; }
            .laboratory-param-panel { flex: 1 1 auto; min-height: 0; }
            .laboratory-param-progress { display: none; }
            .laboratory-param-link { flex: 0 0 auto; margin-bottom: 0; white-space: nowrap; }
            .laboratory-param-name { max-width: 140px; }
            .laboratory-param-divider {
                flex: 0 0 1px;
                width: 1px;
                height: auto;
                margin: .2rem .3rem;
            }
        }
        @media (max-width: 767.98px) {
            .laboratory-result-grid { grid-template-columns: 1fr 1fr; }
            .laboratory-result-head { flex-direction: column; }
            .laboratory-standard { text-align: left; }
        }
    </style>

    <div class="row mx-2 mt-4 d-flex justify-content-center">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <strong class="cardTitle">Product Laboratory</strong>
                            <p class="mb-0 mt-1 text-muted">Dynamic laboratory testing by product and method.</p>
                        </div>
                        <div class="col-md-7 text-right">
                            <div class="d-inline-flex align-items-center flex-wrap justify-content-end">
                                <input type="date" id="laboratory_filter_from" class="form-control form-control-sm mr-2 mb-1" style="width:145px" value="{{ date('Y-m-d', strtotime('-1 month')) }}">
                                <input type="date" id="laboratory_filter_to" class="form-control form-control-sm mr-2 mb-1" style="width:145px" value="{{ date('Y-m-d') }}">
                                <span class="d-inline-block mr-2 mb-1" style="width:145px">
                                <select id="laboratory_filter_status" class="form-control form-control-sm" style="width:145px">
                                    <option value="">All Status</option>
                                    <option value="0">Draft</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Approval</option>
                                    <option value="3">Done</option>
                                    <option value="4">Revision</option>
                                </select>
                                </span>
                                <button type="button" id="btn_filter_laboratory" class="btn btn-sm btn-warning mb-1"><i class="fa-solid fa-filter"></i></button>
                                <button type="button" id="btn_add_laboratory" class="btn btn-sm btn-success ml-1 mb-1" data-toggle="modal" data-target="#addLaboratoryModal">
                                    <i class="fa-solid fa-plus mr-1"></i> New Test
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="datatable-stepper" id="laboratory_table" style="width:100%">
                        <thead class="thead-light">
                            <tr>
                                <th>Test Date</th>
                                <th>Laboratory Code</th>
                                <th>Product</th>
                                <th>Method</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Current Approver</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
    </div>

    @include('transaction.inspection.laboratory.modal.add-laboratory')
    @include('transaction.inspection.laboratory.modal.detail-laboratory')
@endsection

@push('js')
    <script src="{{ asset('lims') }}/transaction/inspection/laboratory.js"></script>
@endpush
