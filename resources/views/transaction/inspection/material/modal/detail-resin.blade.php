

<div class="modal fade qc-inspection-modal qc-inspection-modal--resin" id="detailResinModal">
     <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
         <div class="modal-content qc-inspection-modal__content">
             <div class="modal-header qc-inspection-modal__header">
                 <div>
                     <b style="font-size: 12px">Detail Resin Inspection</b>
                     <p class="qc-inspection-modal__subtitle">Ringkasan inspeksi material resin, properti density, dan approval dalam satu modal.</p>
                 </div>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body qc-inspection-modal__body">
                <div class="nav-wrapper">
                     <ul class="nav nav-pills nav-fill flex-column flex-md-row qc-inspection-modal__tabs" id="tabs-icons-text" role="tablist">
                         <li class="nav-item">
                             <a class="nav-link mb-sm-3 mb-md-0 active" id="detail_resin_general_information-tab" data-toggle="tab" href="#detail_resin_general_information" role="tab" aria-controls="detail_resin_general_information" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>General Information</a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link mb-sm-3 mb-md-0" id="detail_resin_bulk_density_tab-tab" data-toggle="tab" href="#detail_resin_bulk_density_tab" role="tab" aria-controls="detail_resin_bulk_density_tab" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Bulk Density</a>
                         </li>
                         <li class="nav-item">
                             <a class="nav-link mb-sm-3 mb-md-0" id="detail_resin_sample_tab-tab" data-toggle="tab" href="#detail_resin_sample_tab" role="tab" aria-controls="detail_resin_sample_tab" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Density Properties</a>
                         </li>
                     </ul>
                 </div>
                 <div class="card shadow qc-inspection-modal__card">
                     <div class="card-body">
                         <div class="tab-content" id="resin_tab_content">
                             <div class="tab-pane fade show active" id="detail_resin_general_information" role="tabpanel" aria-labelledby="detail_resin_general_information-tab">
                               <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Information</legend>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Insp Code</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_inspection_code"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Type</p>
                                         </div>
                                         <div class="col-4">
                                              <p>: Resin</p>
                                         </div>
                                         <div class="col-2">
                                              <p>Location</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_location"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Inspector</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_inspector"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Supplier</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_supplier"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Brand</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_brand"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Standard</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_standard"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Certificate</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_certificate"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Weight</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_weight"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Amount</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_amount"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>No Lot</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_no_lot"></p>
                                         </div>
                                    </div>
                               </fieldset>
 
                             </div>
                             <div class="tab-pane fade" id="detail_resin_bulk_density_tab" role="tabpanel" aria-labelledby="detail_resin_bulk_density_tab-tab">
                               <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Bulk Density</legend>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Material</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_product_id"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Spec</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_spec"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Step</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_step"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Average</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_avg"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Min</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_min"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Max</p>
                                         </div>
                                         <div class="col-4">
                                              <p id="label_resin_max"></p>
                                         </div>
                                    </div>
                                    <div class="row">
                                         <div class="col-md-6">
                                              <div id="label_resin_graphic_container" class="qc-inspection-modal__chart">
                                                   <canvas id="label_resin_graphic"></canvas>
                                               </div>
                                             
                                         </div>
                                         <div class="col-md-6">
                                                  <div id="label_step_container" class="row qc-inspection-modal__steps"></div>
                                         </div>
                                    </div>
                               </fieldset>
                             </div>
                          <div class="tab-pane fade" id="detail_resin_sample_tab" role="tabpanel" aria-labelledby="detail_resin_sample_tab-tab">
                               <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Properties</legend>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Visible Properties</p>
                                         </div>
                                         <div class="col-4">
                                             <p id="label_vivsible_properties"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Packaging</p>
                                         </div>
                                         <div class="col-4">
                                             <p id="label_resin_package"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Color</p>
                                         </div>
                                         <div class="col-4">
                                           <p id="label_resin_color"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Particle Size</p>
                                         </div>
                                         <div class="col-4">
                                             <p id="label_resin_particle_size"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Volatile Matter</p>
                                         </div>
                                         <div class="col-4">
                                             <p id="resin_volatile_matter"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Amount / sak</p>
                                         </div>
                                         <div class="col-4">
                                            <p id="label_resin_amount_per_sak"></p>
                                         </div>
                                         <div class="col-2">
                                              <p>Weight / sak</p>
                                         </div>
                                         <div class="col-4">
                                             <p id="label_resin_weight_per_sak"></p>
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
                                             <p id="label_resin_pof_number"></p>
                                         </div>
                                    </div>
                                    <div class="row qc-inspection-modal__grid">
                                         <div class="col-2">
                                              <p>Remark</p>
                                         </div>
                                         <div class="col-10">
                                             <p id="label_resin_remark"></p>
                                         </div>
                                    </div>
                               </fieldset>
                             </div>
                         </div>
                         <fieldset class="scheduler-border approval_resin_container qc-inspection-modal__section" hidden>
                              <legend class="scheduler-border">Approval</legend>
                              <div class="row qc-inspection-modal__grid">
                                  <div class="col-2 mt-2">
                                      <p>Approval</p>
                                  </div>
                                  <div class="col-4">
                                      <select name="select_approval_resin" class="select2" id="select_approval_resin">
                                        <option value="">Choose Approval</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                      </select>
                                      <input type="hidden" id="approval_resin">
                                      <span  style="color:red;" class="message_error text-red block approval_resin_error"></span>
                                  </div>
                              </div>
                              <div class="row qc-inspection-modal__grid">
                                   <div class="col-2 mt-2">
                                        <p>Remark</p>
                                   </div>
                                   <div class="col-10">
                                        <textarea class="form-control" id="imr_remark" rows="3"></textarea>
                                        <span  style="color:red;" class="message_error text-red block imr_remark_error"></span>
                                   </div>
                              </div>
                         </fieldset>
                     </div>
                 </div>
             </div>
             <div class="modal-footer justify-content-end approval_resin_container qc-inspection-modal__footer" hidden>
                    <button class="btn btn-success btn-sm" id="btn_imr_approval" >
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
    .qc-inspection-modal__chart,
    .qc-inspection-modal__steps {
        border-radius: 18px;
        border: 1px solid rgba(148, 163, 184, .14);
        background: #f8fbfd;
        padding: .85rem;
        min-height: 100%;
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
