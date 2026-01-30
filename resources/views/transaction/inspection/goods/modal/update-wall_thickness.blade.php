

<div class="modal fade" id="updateWallThicknessModal">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header p-0 my-2 mx-2">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="ig_id">
            <div class="modal-body p-0 mx-2">
                <div class="row mx-2">
                    <div class="col-2 mt-2">
                        <p>Progress</p>
                     </div>
                     <div class="col-4">
                         <select name="select_update_detail_inspection_code" class="select2" id="select_update_detail_inspection_code"></select>
                     </div>
                </div>
                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Add Wall Thickness Dimension</legend>
                            <input type="hidden" id="update_detail_inspection_code">
                          
                            <div class="row">
                                <div class="col-1 mt-2">
                                    <p>1</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_1"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>2</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_2"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>3</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_3"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>4</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_4"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>5</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_5"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>6</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_6"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>7</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_7"/>
                                </div>
                                <div class="col-1 mt-2">
                                    <p>8</p>
                                </div>
                                <div class="col-2">
                                    <input type="text" class="form-control" id="update_wt_8"/>
                                </div>
                            </div>
                            <div class="row mt-2 mx-2 d-flex justify-content-end">
                                <button class="btn btn-sm btn-success" id="btn_add_wt">
                                    <i class="fas fa-check"></i>
                                </button>
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border p-0">
                            <legend class="scheduler-border">Wall Thickness Step</legend>
                                <div class="row p-0">
                                    <div class="col-12 p-0">
                                        <table class="datatable-stepper" id="wt_step">
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
                                    </div>
                                </div>
                        </fieldset>
            </div>
           
        </div>
    </div>
</div>