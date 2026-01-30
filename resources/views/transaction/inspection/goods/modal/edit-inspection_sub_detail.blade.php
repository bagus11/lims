<div class="modal fade" id="editSubDetailInspection">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore p-0 my-2 mx-2">
                <div class="nav-wrapper p-0">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        <li class="nav-item">
                            <a style="font-size: 12px !important" class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1-edit" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-sound-wave mr-2"></i>Inspection Item
                            </a>
                        </li>
                        <li class="nav-item">
                            <a style="font-size: 12px !important" class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2-edit" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-app mr-2"></i>Dimension</a>
                        </li>
                      
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 mx-2">
                <input type="hidden" id="detail_inspection_code_id">
                <div class="tab-content" id="myTabContentEditInspectionSubDetail">
                    <div class="tab-pane fade show active" id="tabs-icons-text-1-edit" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab-edit"> 
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">General Inspection</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Sub Detail Code</p>
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control" id="inspection_detail_edit" disabled>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Sub Detail Code</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_inspection_sub_detail_edit" class="select2" id="select_inspection_sub_detail_edit"></select>
                                    <input type="hidden" class="form-control" id="inspection_sub_detail_edit">
                                    <span class="message_error inspection_sub_detail_edit_error text-red block "></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Time</p>
                                </div>
                                <div class="col-4">
                                    <input type="time" id="time_edit" class="form-control"value="" disabled>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Machine</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_machine_edit" class="select2" id="select_machine_edit"></select>
                                    <input type="hidden" class="form-control" id="machine_id_edit">
                                    <span class="message_error machine_id_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Product</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_product_edit" class="select2" id="select_product_edit"></select>
                                    <input type="hidden" class="form-control" id="product_edit">
                                    <span class="message_error product_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Manufacturing Order</p>
                               </div>
                               <div class="col-4">
                                <input type="text" class="form-control" id="manufacturing_code_edit">
                                <span class="message_error manufacturing_code_edit_error text-red block "></span>
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Length of Pipe</p>    
                                </div>  
                                <div class="col-4">
                                    <input type="number" id="length_of_pipe_edit" class="form-control">
                                    <span class="message_error length_of_pipe_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Inspector</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_inspector_edit" class="select2" id="select_inspector_edit"></select>
                                    <input type="hidden" class="form-control" id="inspector_edit">
                                    <span class="message_error inspector_edit_error text-red block "></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Reason</p>
                                </div>
                                <div class="col-10">
                                    <textarea class="form-control" id="reason" rows="3"></textarea>
                                    <span  style="color:red;" class="message_error text-red block reason_error"></span>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border p-0 mx-2">
                            <legend class="scheduler-border">Detail Inspection</legend>
                           
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Appearance of pipes</p>
                                </div>
                                <div class="col-4">
                                      <select id="select_appearance_of_pipe_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="Ο">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="appearance_of_pipe_edit">
                                      <span class="message_error appearance_of_pipe_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Marking</p>
                                </div>
                                <div class="col-4">
                                      <select id="select_marking_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="Ο">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="marking_edit">
                                      <span class="message_error marking_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Condition pipe of cut</p>
                                </div>
                                <div class="col-4">
                                      <select id="select_condition_pipe_of_cut_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="Ο">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="condition_pipe_of_cut_edit">
                                      <span class="message_error condition_pipe_of_cut_edit_error text-red block "></span>
                                      
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Angle pipe for spigot</p>
                                </div>
                                <div class="col-4">
                                      <select id="select_angle_pipe_of_spigot_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="Ο">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="angle_pipe_of_spigot_edit">
                                      <span class="message_error angle_pipe_of_spigot_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Color of pipes</p>
                                </div>
                                <div class="col-4">
                                      <select id="select_color_of_pipes_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="Ο">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="color_of_pipes_edit">
                                      <span class="message_error color_of_pipes_edit_error text-red block "></span>
                                </div>
                            </div>
                        </fieldset>
                     </div>
                    <div class="tab-pane fade" id="tabs-icons-text-2-edit" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab-edit"> 
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Material Inspection</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Material</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_material_edit" class="select2" id="select_material_edit"></select>
                                    <input type="hidden" id="material_edit" class="form-control">
                                    <span class="message_error material_edit_error text-red block "></span>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Tape Diameter</p>
                                </div>
                                <div class="col-4">
                                    <input type="number" id="tape_diameter_edit" class="form-control">
                                    <span class="message_error tape_diameter_edit_error text-red block "></span>
                                </div>

                            </div>

                        </fieldset>
                        {{-- <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Wall Thickness Dimension</legend>
                           
                            <div class="row">
                                <div class="col-1 mt-2">
                                    <p>1</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_1_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>2</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_2_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>3</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_3_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>4</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_4_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>5</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_5_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>6</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_6_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>7</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_7_edit">
                                </div>
                                <div class="col-1 mt-2">
                                    <p>8</p>
                                </div>
                                <div class="col-2">
                                    <input type="number" class="form-control" id="wt_8_edit">
                                </div>
                            </div>
                        </fieldset> --}}
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Evaluation Spec</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Evaluation Spec</p>
                                </div>
                                <div class="col-4">
                                      <select name="select_evaluation_spec_edit" id="select_evaluation_spec_edit" class="select2">
                                        <option value="">Choose Condition</option>
                                        <option value="O">Ο</option>
                                        <option value="Δ">Δ</option>
                                        <option value="X">X</option>
                                      </select>
                                      <input type="hidden" class="form-control" id="evaluation_spec_edit">
                                      <span class="message_error evaluation_spec_edit_error text-red block "></span>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btn_update_sub_detail" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    /* Change the color of the active tab link to blue */

    #tabs-icons-text .nav-link.active {
        background-color: #179BAE !important;
        color: white !important;
        border-color: #179BAE !important;
    }
    #tabs-icons-text .nav-link {
        color: #179BAE !important;
    }

  </style>