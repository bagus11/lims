

<div class="modal fade qc-inspection-modal qc-inspection-modal--pellet" id="detailPelletModal">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content qc-inspection-modal__content">
            <div class="modal-header qc-inspection-modal__header">
                <div>
                    <b style="font-size: 12px">Detail Pellet Inspection</b>
                    <p class="qc-inspection-modal__subtitle">Ringkasan inspeksi pellet, hasil properti material, dan approval dalam satu tampilan.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body qc-inspection-modal__body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row qc-inspection-modal__tabs" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="detail_pellet_general_information-tab" data-toggle="tab" href="#detail_pellet_general_information" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>General Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="detail_pellet_detail_inspection-tab" data-toggle="tab" href="#detail_pellet_detail_inspection" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Inspection Properties</a>
                        </li>

                    </ul>
                </div>
                <div class="card shadow qc-inspection-modal__card">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="detail_pellet_general_information" role="tabpanel" aria-labelledby="detail_pellet_general_information-tab">
                                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Information</legend>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Insp Code</p>
                                         </div>
                                         <div class="col-4">
                                             <input type="hidden" class="form-control" id="approval_material_id">
                                              <p id="label_pellet_inspection_code"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Type</p>
                                         </div>
                                         <div class="col-4">
                                              <p>: Pelet</p>
                                         </div>
                                         <div class="col-2">
                                              <p>Location</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_location"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Inspector</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_inspector"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Supplier</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_supplier"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Produsen</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_produsen"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Brand</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_brand"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Standard</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_standard"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Certificate</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_certificate"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Weight</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_weight"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Amount</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_amount"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>No Lot</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_pellet_no_lot"></p>
                                         </div>
                                    </div>
                               </fieldset>
                            </div>
                            <div class="tab-pane fade" id="detail_pellet_detail_inspection" role="tabpanel" aria-labelledby="detail_pellet_detail_inspection-tab">
                                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Result</legend>
                                    <div class="row qc-inspection-modal__grid">
                                        <div class="col-2">
                                            <p>Vis Properties</p>
                                        </div>
                                        <div class="col-4">
                                          <p id="label_pellet_visible_properties"></p>
                                        </div>
                                        <div class="col-2">
                                            <p>Packaging</p>
                                        </div>
                                        <div class="col-4">
                                            <p id="label_pellet_packaging"></p>
                                        </div>
                                    </div>
                                    <div class="row qc-inspection-modal__grid">
                                        <div class="col-2">
                                            <p>Amount / sak</p>
                                        </div>
                                        <div class="col-4">
                                           <p id="label_pellet_amount_per_sak"></p>
                                        </div>
                                        <div class="col-2">
                                            <p>Weight / sak</p>
                                        </div>
                                        <div class="col-4">
                                            <p id="label_pellet_weight_per_sak"></p>
                                        </div>
                                        <div class="col-2">
                                            <p>MFR </p>
                                        </div>
                                        <div class="col-4">
                                           <p id="label_pelet_mfr"></p>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Remark</legend>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>POF Number</p>
                                         </div>
                                         <div class="col-4">
                                                <p id="label_pellet_pof_number"></p>
                                         </div>
                                    </div>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Remark</p>
                                         </div>
                                         <div class="col-10">
                                           <p id="label_pellet_remark"></p>
                                         </div>
                                    </div>
                               </fieldset>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="card shadow approval_pellet_container mt-2 qc-inspection-modal__card" hidden>
                    <div class="card-body">
                         <fieldset class="scheduler-border approval_pellet_container qc-inspection-modal__section" hidden>
                              <legend class="scheduler-border">Approval</legend>
                              <div class="row qc-inspection-modal__grid">
                                  <div class="col-2 mt-2">
                                      <p>Approval</p>
                                  </div>
                                  <div class="col-4">
                                      <select name="select_approval_pellet" class="select2" id="select_approval_pellet">
                                        <option value="">Choose Approval</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                      </select>
                                      <input type="hidden" id="approval_pellet">
                                      <span  style="color:red;" class="message_error text-red block approval_pellet_error"></span>
                                  </div>
                              </div>
                              <div class="row qc-inspection-modal__grid">
                                   <div class="col-2 mt-2">
                                        <p>Remark</p>
                                   </div>
                                   <div class="col-10">
                                        <textarea class="form-control" id="imr_pellet_remark" rows="3"></textarea>
                                        <span  style="color:red;" class="message_error text-red block imr_pellet_remark_error"></span>
                                   </div>
                              </div>
                         </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end qc-inspection-modal__footer">
                <button class="btn btn-sm btn-success" id="btn_approval_pellet" hidden>
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .qc-inspection-modal .modal-dialog {
        max-width: 1120px;
    }
    .qc-inspection-modal__content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(180deg, #f8fbfd 0%, #ffffff 100%);
        box-shadow: 0 28px 70px rgba(15, 23, 42, .18);
    }
    .qc-inspection-modal__header {
        padding: 1.3rem 1.5rem 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background:
            radial-gradient(circle at top right, rgba(23, 155, 174, .16), rgba(23, 155, 174, 0) 30%),
            linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
    }
    .qc-inspection-modal__subtitle {
        margin: .35rem 0 0;
        color: #64748b;
        font-size: 11px !important;
    }
    .qc-inspection-modal__body {
        padding: 1rem 1.25rem 1.25rem;
    }
    .qc-inspection-modal__tabs {
        gap: .85rem;
        margin-bottom: 1rem;
    }
    .qc-inspection-modal__tabs .nav-item {
        margin-bottom: 0 !important;
    }
    .qc-inspection-modal__tabs .nav-link {
        border-radius: 16px !important;
        border: 1px solid rgba(148, 163, 184, .16);
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .05);
        padding: 1rem 1.25rem;
        color: #179BAE !important;
    }
    .qc-inspection-modal__tabs .nav-link.active {
        background-color: #179BAE !important;
        color: white !important;
        border-color: #179BAE !important;
    }
    .qc-inspection-modal__card {
        border: 1px solid rgba(148, 163, 184, .16);
        box-shadow: 0 14px 32px rgba(15, 23, 42, .06) !important;
        border-radius: 22px !important;
    }
    .qc-inspection-modal__section {
        border: 1px solid rgba(148, 163, 184, .16) !important;
        border-radius: 20px !important;
        padding: 1.25rem 1.25rem 1rem 1.25rem !important;
        margin-bottom: 1.1rem !important;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }
    .qc-inspection-modal__section legend {
        padding: 0 .55rem;
        color: #0f172a;
    }
    .qc-inspection-modal__grid > div {
        margin-bottom: .2rem;
    }
    .qc-inspection-modal__grid p[id] {
        display: block;
        width: 100%;
        padding: .55rem .75rem;
        border-radius: 12px;
        background: #f8fbfd;
        border: 1px solid rgba(148, 163, 184, .14);
        min-height: 34px;
    }
    .qc-inspection-modal__footer {
        border-top: 0;
        padding: 0 1.25rem 1.25rem;
    }
    .qc-inspection-modal__footer .btn-success {
        min-width: 42px;
        min-height: 42px;
        border-radius: 12px;
        box-shadow: 0 12px 22px rgba(34, 197, 94, .22);
    }
    @media (max-width: 767.98px) {
        .qc-inspection-modal__body {
            padding: .9rem .85rem 1rem;
        }
        .qc-inspection-modal__tabs {
            gap: .55rem;
        }
        .qc-inspection-modal__tabs .nav-link {
            padding: .85rem 1rem;
        }
        .qc-inspection-modal__section {
            padding: 1rem .85rem .75rem .85rem !important;
        }
        .qc-inspection-modal__grid p[id] {
            margin-bottom: .55rem;
        }
    }
</style>
