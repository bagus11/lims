

<div class="modal fade qc-product-modal" id="editMasterProduct">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content qc-product-modal__content">
            <div class="modal-header qc-product-modal__header">
                <div>
                    <b style="font-size: 12"> Detail Product</b>
                    <p class="qc-product-modal__subtitle">Informasi produk, form update, dan riwayat perubahan dalam satu tampilan.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="edit_id">
                <div class="modal-body qc-product-modal__body">
                    <div class="row mx-1">
                        <div class="col-12">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row qc-product-modal__tabs" id="tabs-icons-text" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" style="color:#179BAE;font-family: Poppins !important; font-size:10 !important" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="fa-solid fa-circle-info"></i> General Information</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" style="color:#179BAE;font-family: Poppins !important; font-size:10 !important" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="fa-solid fa-pen-to-square"></i> Form Edit</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab" data-toggle="tab" style="color:#179BAE;font-family: Poppins !important; font-size:10 !important" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="fa-solid fa-clock-rotate-left"></i> Log History Product</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
                                    <br>

                                    <fieldset class="scheduler-border mt-4 qc-product-modal__section">
                                        <legend class="scheduler-border">General Information</legend>
                                        <div class="row qc-product-modal__grid">
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Seri</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_seri"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Join</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_join"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Kode Ts</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_kode_ts"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Kode BE</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_kode_be"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Berat (btg)</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_berat_pcs"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Berat (m)</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_berat"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Formula</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_formula"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Marking 2</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_2"></p>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border qc-product-modal__section">
                                        <legend class="scheduler-border">OD Standard</legend>
                                        <div class="row qc-product-modal__grid">
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Min</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_min_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Tlrs</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_toleransi_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Max</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_max_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Avg</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_avg_od"></p>
                                            </div>
                                        </div>
                                    </fieldset>

                                    <fieldset class="scheduler-border qc-product-modal__section">
                                        <legend class="scheduler-border">WT Standard</legend>
                                        <div class="row qc-product-modal__grid">
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Min</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_min_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Tlrs</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_toleransi_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Max</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_max_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2 mt-2">
                                                <p>Avg</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_avg_wt"></p>
                                            </div>
                                        </div>
                                    </fieldset>

                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
                                    <div class="nav-wrapper">
                                        <div class="card shadow qc-product-modal__edit-card">
                                            <div class="card-body p-0 mt-2 mx-4">
                                                        <div class="nav-wrapper">
                                                            <ul class="nav nav-pills nav-fill flex-column flex-md-row qc-product-modal__tabs qc-product-modal__tabs--inner" id="tabs-icons-text_2" role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab_2" data-toggle="tab"  style="color:#179BAE;font-family: Poppins !important; font-size:9px !important" href="#tabs-icons-text-1_2" role="tab" aria-controls="tabs-icons-text-1_2" aria-selected="true">General</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab_2" data-toggle="tab"  style="color:#179BAE;font-family: Poppins !important; font-size:9px !important" href="#tabs-icons-text-2_2" role="tab" aria-controls="tabs-icons-text-2_2" aria-selected="false">OD Standard</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-3-tab_2" data-toggle="tab"  style="color:#179BAE;font-family: Poppins !important; font-size:9px !important" href="#tabs-icons-text-3_2" role="tab" aria-controls="tabs-icons-text-3_2" aria-selected="false">WT Standard</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="tab-content" id="myTabContent_2">
                                                            <div class="tab-pane fade show active" id="tabs-icons-text-1_2" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab_2">
                                                                <div class="row qc-product-modal__form-grid">
                                                                    <div class="col-3 mt-2">
                                                                        <p>Berat (pcs)</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_berat_pcs">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Berat (m)</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_berat">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Formula</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_formula">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-icons-text-2_2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab_2">
                                                                <div class="row qc-product-modal__form-grid">
                                                                    <div class="col-3 mt-2">
                                                                        <p>Min</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_min_od">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Tlrs</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_toleransi_od">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Average</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_average_od">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Max</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_max_od">
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="tab-pane fade" id="tabs-icons-text-3_2" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab_2">
                                                                <div class="row qc-product-modal__form-grid">
                                                                    <div class="col-3 mt-2">
                                                                        <p>Min</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_min_wt">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Tlrs</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_toleransi_wt">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Average</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_average_wt">
                                                                    </div>
                                                                    <div class="col-3 mt-2">
                                                                        <p>Max</p>
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <input type="text" class="form-control" id="detail_max_wt">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                            </div>
                                            <div class="card-footer justify-content-end p-0 mx-4 my-2 qc-product-modal__footer">
                                                <button id="btn_update_product" type="button" style="float: right" class="btn btn-sm btn-success mt-2">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
                                    <br>
                                        <div class="row p-0">
                                            <div class="col-12 p-0">
                                                <div class="table-container qc-product-modal__log-wrap">
                                                    <table class="datatable-stepper p-0" id="log_master_product_table">
                                                        <thead class="thead-light">
                                                            <tr>
                                                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="seri">Created</th>
                                                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="join">PIC</th>
                                                                <th colspan="2" scope="col" class="sort" style="text-align: center">Berat</th>
                                                                <th rowspan="2" scope="col" class="sort" style="text-align: center">Formula</th>
                                                                <th colspan="5" scope="col" class="sort" style="text-align: center">OD Standart</th>
                                                                <th colspan="5" scope="col" class="sort" style="text-align: center">WT Standart</th>
                                                            </tr>
                                                            <tr style="border-top: solid white 1px !important">
                                                                <th>pcs</th>
                                                                <th>meter</th>

                                                                <th>Min</th>
                                                                <th>Tlrs</th>
                                                                <th>Marking</th>
                                                                <th>Avg</th>
                                                                <th>Max</th>

                                                                <th>Min</th>
                                                                <th>Tlrs</th>
                                                                <th>Marking</th>
                                                                <th>Avg</th>
                                                                <th>Max</th>
                                                            </tr>

                                                        </thead>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end qc-product-modal__footer">

                </div>

        </div>
    </div>
</div>

<style>
    .qc-product-modal .modal-dialog {
        max-width: 1120px;
    }
    .qc-product-modal__content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(180deg, #f8fbfd 0%, #ffffff 100%);
        box-shadow: 0 28px 70px rgba(15, 23, 42, .18);
    }
    .qc-product-modal__header {
        padding: 1.3rem 1.5rem 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background:
            radial-gradient(circle at top right, rgba(23, 155, 174, .16), rgba(23, 155, 174, 0) 30%),
            linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
    }
    .qc-product-modal__subtitle {
        margin: .35rem 0 0;
        color: #64748b;
        font-size: 11px !important;
    }
    .qc-product-modal__body {
        padding: 1rem 1.25rem 1.25rem;
    }
    .qc-product-modal__tabs {
        gap: .85rem;
    }
    .qc-product-modal__tabs .nav-item {
        margin-bottom: 0 !important;
    }
    .qc-product-modal__tabs .nav-link {
        border-radius: 16px !important;
        border: 1px solid rgba(148, 163, 184, .16);
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .05);
        padding: 1rem 1.25rem;
    }
    .qc-product-modal__tabs--inner {
        gap: .65rem;
        margin-bottom: .5rem;
    }
    .qc-product-modal__tabs--inner .nav-link {
        padding: .85rem 1rem;
    }
    .qc-product-modal__section {
        border: 1px solid rgba(148, 163, 184, .16) !important;
        border-radius: 20px !important;
        padding: 1.25rem 1.25rem 1rem 1.25rem !important;
        margin-bottom: 1.1rem !important;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }
    .qc-product-modal__section legend {
        padding: 0 .55rem;
        color: #0f172a;
    }
    .qc-product-modal__grid > div,
    .qc-product-modal__form-grid > div {
        margin-bottom: .2rem;
    }
    .qc-product-modal__grid p[id],
    .qc-product-modal__form-grid input {
        display: block;
        width: 100%;
    }
    .qc-product-modal__grid p[id] {
        padding: .55rem .75rem;
        border-radius: 12px;
        background: #f8fbfd;
        border: 1px solid rgba(148, 163, 184, .14);
        min-height: 34px;
    }
    .qc-product-modal__edit-card {
        border: 1px solid rgba(148, 163, 184, .16);
        box-shadow: 0 14px 32px rgba(15, 23, 42, .06) !important;
        border-radius: 22px !important;
    }
    .qc-product-modal__edit-card .card-body {
        padding: 1.1rem 0 0 !important;
    }
    .qc-product-modal__edit-card .card-footer {
        background: transparent;
        border-top: 1px solid rgba(148, 163, 184, .14);
        min-height: 56px;
    }
    .qc-product-modal__footer {
        border-top: 0;
    }
    .qc-product-modal__footer .btn-success {
        min-width: 42px;
        min-height: 42px;
        border-radius: 12px;
        box-shadow: 0 12px 22px rgba(34, 197, 94, .22);
    }
    .qc-product-modal__log-wrap {
        background: #ffffff;
        border: 1px solid rgba(148, 163, 184, .16);
        border-radius: 18px;
        padding: .85rem;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }
    #tabs-icons-text_2 .nav-link.active {
        background-color: #179BAE !important;
        color: white !important;
        border-color: #179BAE !important;
    }
    #tabs-icons-text .nav-link.active {
        background-color: #179BAE !important;
        color: white !important;
        border-color: #179BAE !important;
    }
    @media (max-width: 767.98px) {
        .qc-product-modal__body {
            padding: .9rem .85rem 1rem;
        }
        .qc-product-modal__tabs {
            gap: .55rem;
        }
        .qc-product-modal__tabs .nav-link {
            padding: .85rem 1rem;
        }
        .qc-product-modal__section {
            padding: 1rem .85rem .75rem .85rem !important;
        }
        .qc-product-modal__grid p[id] {
            margin-bottom: .55rem;
        }
    }
  </style>
