

<div class="modal fade" id="detailSubDetailInspection">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-0 my-2 mx-2">
                <div class="nav-wrapper p-0">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tab_progress" role="tablist">
                    </ul>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="ig_id">
            <div class="modal-body p-0 mx-2">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabs-wall-1" role="tabpanel" aria-labelledby="tabs-wall-1-tab"> 
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">General Inspection</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Created By</p>
                                </div>
                                <div class="col-4 mt-2">
                                    <p id="label_user_id"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Inspector</p>
                                </div>
                                <div class="col-4 mt-2">
                                    <p id="label_inspector"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Time</p>
                                </div>
                                <div class="col-4">
                                    <input type="time" id="label_time" class="form-control"value="{{date('H:i:s')}}">
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Machine</p>
                                </div>
                                <div class="col-4 mt-2">
                                    <p id="label_machine"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Product</p>
                                </div>
                                <div class="col-4 mt-2">
                                 <p id="label_product"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Manufacturing Order</p>
                               </div>
                               <div class="col-4 mt-2">
                                <p id="label_manufacturing_code"></p>
                               </div>
                            </div>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Length of Pipe</p>    
                                </div>  
                                <div class="col-4 mt-2">
                                   <p id="label_length_of_pipe"></p>
                                </div>
                            
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border p-0 mx-2">
                            <legend class="scheduler-border">Detail Inspection</legend>
                           
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Appearance of pipes</p>
                                </div>
                                <div class="col-4 mt-2">
                                     <p id="label_appearance_of_pipe"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Marking</p>
                                </div>
                                <div class="col-4 mt-2">
                                     <p id="label_marking"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Condition pipe of cut</p>
                                </div>
                                <div class="col-4 mt-2">
                                     <p id="label_condition_pipe_of_cut"></p>
                                      
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Angle pipe for spigot</p>
                                </div>
                                <div class="col-4 mt-2">
                                    <p id="label_angle_pipe_of_spigot"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Color of pipes</p>
                                </div>
                                <div class="col-4 mt-2">
                                   <p id="label_color_of_pipes"></p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Material Inspection</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Material</p>
                                </div>
                                <div class="col-4 mt-2">
                                  <p id="label_material"></p>
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Tape Diameter</p>
                                </div>
                                <div class="col-4 mt-2">
                                 <p id="label_tape_diameter"></p>
                                </div>

                            </div>

                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Wall Thickness Dimension</legend>
                            <table class="datatable-stepper" id="label_wt">
                                <thead class="thead-light">
                                    <tr>
                                        <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="">Step</th>
                                        <th colspan="8" scope="col" class="sort" style="text-align: center" data-sort="1">Wal Thickness</th>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2</td>
                                        <td>3</td>
                                        <td>4</td>
                                        <td>5</td>
                                        <td>6</td>
                                        <td>7</td>
                                        <td>8</td>
                                    </tr>
                                </thead>
                            </table>
                        </fieldset>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Evaluation Spec</legend>
                            <div class="row">
                                <div class="col-2 mt-2">
                                    <p>Evaluation Spec</p>
                                </div>
                                <div class="col-4 mt-2">
                                     <p id="label_evaluation_spec"></p>
                                </div>
                                
                            </div>
                            <div class="row" id="ig_approval_container" hidden>
                                <div class="col-2 mt-2">
                                    <p>Approval</p>
                                </div>
                                <div class="col-4">
                                    <select name="select_approval" class="select2" id="select_approval_insepection_goods_shift">
                                        <option value="">Choose Approval</option>
                                        <option value="1">Approve</option>
                                        <option value="2">Reject</option>
                                    </select>
                                    <input type="hidden" id="approval_insepection_goods_shift_id">
                                    <span class="message_error approval_insepection_goods_shift_id_error"></span>
                                </div>
                                <div class="col-6">
                                    
                                </div>
                                <div class="col-2 mt-2">
                                    <p>Remark</p>
                                </div>
                                <div class="col-10">
                                    <textarea class="form-control" id="ig_remark" rows="3"></textarea>
                                    <span  style="color:red;" class="message_error text-red block ig_remark_error"></span>
                                </div>
                            </div>
                        </fieldset>
                     </div>
                   
                </div>
               
            </div>
            <div class="modal-footer justify-content-end" id="ig_footer" hidden>
                <button id="btn_ig_approve" class="btn btn-success btn-sm ">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>