

<div class="modal fade" id="detailPelletModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px">Detail Pellet Inspection</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="detail_pellet_general_information-tab" data-toggle="tab" href="#detail_pellet_general_information" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>General Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="detail_pellet_detail_inspection-tab" data-toggle="tab" href="#detail_pellet_detail_inspection" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Inspection Properties</a>
                        </li>
                     
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="detail_pellet_general_information" role="tabpanel" aria-labelledby="detail_pellet_general_information-tab">
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Information</legend>
                                    <div class="row">
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
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Result</legend>
                                    <div class="row">
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
                                    <div class="row">
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
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Remark</legend>
                                    <div class="row">
                                         <div class="col-2">
                                              <p>POF Number</p>
                                         </div>
                                         <div class="col-4">
                                                <p id="label_pellet_pof_number"></p>
                                         </div>
                                    </div>
                                    <div class="row">
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
            </div>
            <div class="modal-footer justify-content-end">
              
            </div>
        </div>
    </div>
</div>