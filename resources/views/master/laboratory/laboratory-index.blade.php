@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')

    <style>
        .laboratory-tabs .nav-link {
            border-radius: 12px 12px 0 0;
            color: #64748b;
            font-size: 12px;
            font-weight: 700;
            padding: .8rem 1rem;
        }
        .laboratory-tabs .nav-link.active {
            color: #0f766e;
            background: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
        }
        .laboratory-config-toolbar {
            display: grid;
            grid-template-columns: minmax(220px, 1.4fr) minmax(260px, 2fr) auto;
            gap: 1rem;
            align-items: end;
        }
        .laboratory-config-row {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 1rem;
            margin-bottom: .85rem;
            background: #fff;
        }
        .laboratory-config-row.is-disabled {
            background: #f8fafc;
            opacity: .7;
        }
        .laboratory-config-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: .9rem;
        }
        .laboratory-config-grid {
            display: grid;
            /* auto-fit follows the panel width, not the viewport: the old fixed
               6-column track could not shrink and pushed the whole page sideways */
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: .75rem;
        }
        .laboratory-config-grid > div { min-width: 0; }
        /* a parameter that is switched off collapses to its header */
        .laboratory-config-row.is-disabled .laboratory-config-grid { display: none; }
        .laboratory-config-row.is-disabled { padding: .65rem 1rem; }
        .laboratory-config-row.is-disabled .laboratory-config-head { margin-bottom: 0; }
        .laboratory-config-grid p,
        .laboratory-config-toolbar p {
            font-size: 10px !important;
            font-weight: 700;
            color: #475569;
            margin-bottom: .35rem;
        }
        /* the method list lives in a narrow column; let it wrap instead of
           growing its own horizontal scrollbar */
        #configured_method_table {
            table-layout: fixed;
            width: 100%;
        }
        #configured_method_table th,
        #configured_method_table td {
            white-space: normal;
            word-break: break-word;
        }
        .laboratory-empty {
            color: #64748b;
            font-size: 12px;
            padding: 2rem;
            text-align: center;
        }
        .laboratory-code {
            color: #0f766e;
            font-weight: 800;
        }
        @media (max-width: 767.98px) {
            .laboratory-config-toolbar { grid-template-columns: 1fr; }
            .laboratory-config-head { align-items: flex-start; flex-direction: column; }
        }
    </style>

    <div class="row mx-2 mt-4 d-flex justify-content-center">
        <div class="col-12">
            <ul class="nav nav-tabs laboratory-tabs" id="laboratoryMasterTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#test-method-panel" role="tab">
                        <i class="fa-solid fa-book-open mr-1"></i> Test Method
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#test-parameter-panel" role="tab">
                        <i class="fa-solid fa-list-check mr-1"></i> Test Parameter
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#product-configuration-panel" role="tab">
                        <i class="fa-solid fa-sliders mr-1"></i> Product Configuration
                    </a>
                </li>
            </ul>

            <div class="tab-content">
                <div class="tab-pane fade show active" id="test-method-panel" role="tabpanel">
                    <div class="card border-top-0">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <strong class="cardTitle">Laboratory Test Methods</strong>
                                    <p class="mb-0 mt-1 text-muted">Standar pengujian yang dapat dipetakan ke produk.</p>
                                </div>
                                <div class="col-4 text-right">
                                    <button id="btn_add_laboratory_method" class="btn btn-sm btn-success" data-toggle="modal" data-target="#laboratoryMethodModal">
                                        <i class="fa-solid fa-plus mr-1"></i> Add Method
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="datatable-stepper" id="laboratory_method_table" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="test-parameter-panel" role="tabpanel">
                    <div class="card border-top-0">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-8">
                                    <strong class="cardTitle">Laboratory Test Parameters</strong>
                                    <p class="mb-0 mt-1 text-muted">Parameter numeric, text, atau boolean yang dipakai metode uji.</p>
                                </div>
                                <div class="col-4 text-right">
                                    <button id="btn_add_laboratory_parameter" class="btn btn-sm btn-success" data-toggle="modal" data-target="#laboratoryParameterModal">
                                        <i class="fa-solid fa-plus mr-1"></i> Add Parameter
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <table class="datatable-stepper" id="laboratory_parameter_table" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Result Type</th>
                                        <th>Default Unit</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="card-footer"></div>
                    </div>
                </div>

                <div class="tab-pane fade" id="product-configuration-panel" role="tabpanel">
                    <div class="card border-top-0">
                        <div class="card-header">
                            <strong class="cardTitle">Product Laboratory Configuration</strong>
                            <p class="mb-0 mt-1 text-muted">Pilih produk, tetapkan metode, lalu susun parameter dan aturan evaluasinya.</p>
                        </div>
                        <div class="card-body">
                            <div class="laboratory-config-toolbar">
                                <div>
                                    <p for="">Master Product</p>
                                    <select id="laboratory_product_id" class="select2" style="width:100%"></select>
                                </div>
                                <div>
                                    <p for="">Assigned Test Methods</p>
                                    <select id="laboratory_method_ids" class="select2" multiple style="width:100%"></select>
                                </div>
                                <div>
                                    <button type="button" id="btn_save_product_methods" class="btn btn-sm btn-success">
                                        <i class="fa-solid fa-check mr-1"></i> Save Assignment
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-lg-3 mb-3 mb-lg-0">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <strong style="font-size:12px">Configured Methods</strong>
                                        <span class="badge badge-light" id="configured_method_count">0</span>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-sm" id="configured_method_table">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Method</th>
                                                    <th class="text-center">Configure</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr><td colspan="2" class="laboratory-empty">Select a product first.</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <strong style="font-size:12px" id="parameter_configuration_title">Parameter Configuration</strong>
                                            <p class="mb-0 text-muted" id="parameter_configuration_hint">Choose a configured method.</p>
                                        </div>
                                        <button type="button" id="btn_save_method_parameters" class="btn btn-sm btn-success" disabled>
                                            <i class="fa-solid fa-floppy-disk mr-1"></i> Save Parameters
                                        </button>
                                    </div>
                                    <input type="hidden" id="laboratory_product_method_id">
                                    <div id="laboratory_parameter_configuration">
                                        <div class="laboratory-empty">Parameter rules will appear here.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('master.laboratory.modal.laboratory-method')
    @include('master.laboratory.modal.laboratory-parameter')
@endsection

@push('js')
    <script src="{{ asset('lims') }}/master/laboratory/laboratory.js"></script>
@endpush
