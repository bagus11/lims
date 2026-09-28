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
            margin-bottom: 1rem;
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
            grid-template-columns: repeat(8, minmax(90px, 1fr));
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
        .laboratory-summary {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: .75rem;
        }
        .laboratory-summary-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: .75rem;
        }
        .laboratory-summary-item small { display: block; color: #64748b; }
        .laboratory-summary-item strong { color: #0f172a; font-size: 12px; }
        .laboratory-history-item {
            border-bottom: 1px solid #e2e8f0;
            padding: .75rem 0;
            font-size: 11px;
        }
        @media (max-width: 1199.98px) {
            .laboratory-measurement-grid { grid-template-columns: repeat(4, minmax(90px, 1fr)); }
        }
        @media (max-width: 767.98px) {
            .laboratory-result-grid,
            .laboratory-summary { grid-template-columns: 1fr 1fr; }
            .laboratory-measurement-grid { grid-template-columns: repeat(2, minmax(90px, 1fr)); }
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
                                <select id="laboratory_filter_status" class="form-control form-control-sm mr-2 mb-1" style="width:145px">
                                    <option value="">All Status</option>
                                    <option value="0">Draft</option>
                                    <option value="1">In Progress</option>
                                    <option value="2">Approval</option>
                                    <option value="3">Done</option>
                                    <option value="4">Revision</option>
                                </select>
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
