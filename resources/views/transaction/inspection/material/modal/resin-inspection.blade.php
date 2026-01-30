

<div class="modal fade" id="resinInspectionModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px">Resin Inspection</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 mx-2 mb-2">
               <div class="nav-wrapper">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="resin_general_information-tab" data-toggle="tab" href="#resin_general_information" role="tab" aria-controls="resin_general_information" aria-selected="true"><i class="ni ni-cloud-upload-96 mr-2"></i>General Information</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="resin_bulk_density_tab-tab" data-toggle="tab" href="#resin_bulk_density_tab" role="tab" aria-controls="resin_bulk_density_tab" aria-selected="false"><i class="ni ni-bell-55 mr-2"></i>Bulk Density</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="resin_sample_tab-tab" data-toggle="tab" href="#resin_sample_tab" role="tab" aria-controls="resin_sample_tab" aria-selected="false"><i class="ni ni-calendar-grid-58 mr-2"></i>Density Properties</a>
                        </li>
                    </ul>
                </div>
                <div class="card shadow">
                    <div class="card-body">
                        <div class="tab-content" id="resin_tab_content">
                            <div class="tab-pane fade show active" id="resin_general_information" role="tabpanel" aria-labelledby="resin_general_information-tab">
                              <fieldset class="scheduler-border p-0">
                                   <legend class="scheduler-border" style="font-size: 12px">Inspection Material Information</legend>
                                   <div class="row">
                                        <div class="col-2 mt-2">
                                             <p>Insp Code</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <input type="hidden" name="inspection_id" id="inspection_id">
                                             <p id="resin_inspection_code"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Type</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p>: Resin</p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Location</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_location"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Inspector</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_inspector"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Supplier</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_supplier"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Brand</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_brand"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Standard</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_standard"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Certificate</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_certificate"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Weight</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_weight"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Amount</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_amount"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>No Lot</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_no_lot"></p>
                                        </div>
                                   </div>
                              </fieldset>

                            </div>
                            <div class="tab-pane fade" id="resin_bulk_density_tab" role="tabpanel" aria-labelledby="resin_bulk_density_tab-tab">
                              <fieldset class="scheduler-border p-0">
                                   <legend class="scheduler-border" style="font-size: 12px">Bulk Density</legend>
                                   <div class="row">
                                        <div class="col-2 mt-2">
                                             <p>Material</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_product_id"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Spec</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_spec"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Step</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_step"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Average</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_avg"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Min</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_min"></p>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Max</p>
                                        </div>
                                        <div class="col-4 mt-2">
                                             <p id="resin_max"></p>
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-md-6">
                                             <div id="resin_graphic_container">
                                                  <canvas id="resin_graphic"></canvas>
                                              </div>
                                            
                                        </div>
                                        <div class="col-md-6">
                                                  <div id="step_container" class="row"></div>
                                        </div>
                                   </div>
                              </fieldset>
                            </div>
                         <div class="tab-pane fade" id="resin_sample_tab" role="tabpanel" aria-labelledby="resin_sample_tab-tab">
                              <fieldset class="scheduler-border p-0">
                                   <legend class="scheduler-border" style="font-size: 12px">Inspection Properties</legend>
                                   <div class="row">
                                        <div class="col-2 mt-2">
                                             <p>Visible Properties</p>
                                        </div>
                                        <div class="col-4">
                                             <select name="select_vivsible_properties" class="select2" id="select_vivsible_properties">
                                                  <option value="">Choose Result</option>
                                                  <option value="Good">Good</option>
                                                  <option value="Not Good">Not Good</option>
                                             </select>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Packaging</p>
                                        </div>
                                        <div class="col-4">
                                             <select name="select_resin_package" class="select2" id="select_resin_package">
                                                  <option value="">Choose Result</option>
                                                  <option value="Good">Good</option>
                                                  <option value="Not Good">Not Good</option>
                                             </select>
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Color</p>
                                        </div>
                                        <div class="col-4">
                                             <input type="text" class="form-control" id="resin_color">
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Particle Size</p>
                                        </div>
                                        <div class="col-4">
                                             <input type="number" class="form-control" id="resin_particle_size">
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Volatile Matter</p>
                                        </div>
                                        <div class="col-4">
                                             <input type="number" class="form-control" id="resin_volatile_matter">
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Amount / sak</p>
                                        </div>
                                        <div class="col-4">
                                             <input type="number" class="form-control" id="resin_amount_per_sak">
                                        </div>
                                        <div class="col-2 mt-2">
                                             <p>Weight / sak</p>
                                        </div>
                                        <div class="col-4">
                                             <input type="number" class="form-control" id="resin_weight_per_sak">
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
                                             <input type="text" class="form-control" id="resin_pof_number">
                                        </div>
                                   </div>
                                   <div class="row">
                                        <div class="col-2 mt-2">
                                             <p>Remark</p>
                                        </div>
                                        <div class="col-10">
                                             <textarea class="form-control" id="resin_remark" rows="3"></textarea>
                                        </div>
                                   </div>
                              </fieldset>
                              <div class="row d-flex justify-content-end mr-2">
                                   <button class="btn btn-sm btn-success" id="btn_update_density_properties">
                                        <i class="fas fa-check"></i>
                                   </button>
                              </div>
                            </div>
                             
                        </div>
                    </div>
                </div>
           
            
            </div>
        </div>
    </div>
</div>