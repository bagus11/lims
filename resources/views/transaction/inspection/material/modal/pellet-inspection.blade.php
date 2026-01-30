

<div class="modal fade" id="pelletInspectionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px">Pellet Inspection</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="pellet_general_information-tab" data-toggle="tab" href="#pellet_general_information" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>General Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="pellet_detail_inspection-tab" data-toggle="tab" href="#pellet_detail_inspection" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Inspection Properties</a>
                        </li>
                     
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="pellet_general_information" role="tabpanel" aria-labelledby="pellet_general_information-tab">
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Information</legend>
                                    <div class="row">
                                         <div class="col-2 mt-2">
                                              <p>Insp Code</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_inspection_code"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Type</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p>: Pelet</p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Location</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_location"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Inspector</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_inspector"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Supplier</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_supplier"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Brand</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_brand"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Standard</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_standard"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Certificate</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_certificate"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Weight</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_weight"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>Amount</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_amount"></p>
                                         </div>
                                         <div class="col-2 mt-2">
                                              <p>No Lot</p>
                                         </div>
                                         <div class="col-4 mt-2">
                                              <p id="pellet_no_lot"></p>
                                         </div>
                                    </div>
                               </fieldset>
                            </div>
                            <div class="tab-pane fade" id="pellet_detail_inspection" role="tabpanel" aria-labelledby="pellet_detail_inspection-tab">
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Material Result</legend>
                                    <div class="row">
                                        <div class="col-2 mt-2">
                                            <p>Visible Properties</p>
                                        </div>
                                        <div class="col-4">
                                            <select class="select2" name="select_pellet_visible_properties" id="select_pellet_visible_properties">
                                                <option value="">Choose Result</option>
                                                <option value="Good">Good</option>
                                                <option value="Not Good">Not Good</option>
                                            </select>
                                        </div>
                                        <div class="col-2 mt-2">
                                            <p>Packaging</p>
                                        </div>
                                        <div class="col-4">
                                            <select class="select2" name="select_pellet_packaging" id="select_pellet_packaging">
                                                <option value="">Choose Result</option>
                                                <option value="Good">Good</option>
                                                <option value="Not Good">Not Good</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-2 mt-2">
                                            <p>Amount / sak</p>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control" id="pellet_amount_per_sak">
                                        </div>
                                        <div class="col-2 mt-2">
                                            <p>Weight / sak</p>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" class="form-control" id="pellet_weight_per_sak">
                                        </div>
                                        <div class="col-2 mt-2">
                                            <p>MFR </p>
                                        </div>
                                        <div class="col-4">
                                            <input type="number" id="pelet_mfr" class="form-control">
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="scheduler-border p-0">
                                    <legend class="scheduler-border" style="font-size: 12px">Inspection Remark</legend>
                                    <div class="row">
                                         <div class="col-2 mt-2">
                                              <p>POF Number</p>
                                         </div>
                                         <div class="col-4">
                                              <input type="text" class="form-control" id="pellet_pof_number">
                                         </div>
                                    </div>
                                    <div class="row">
                                         <div class="col-2 mt-2">
                                              <p>Remark</p>
                                         </div>
                                         <div class="col-10">
                                              <textarea class="form-control" id="pellet_remark" rows="3"></textarea>
                                         </div>
                                    </div>
                               </fieldset>
                               <div class="row d-flex justify-content-end mr-2">
                                <button id="btn_save_pellet" type="button" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                               </div>
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