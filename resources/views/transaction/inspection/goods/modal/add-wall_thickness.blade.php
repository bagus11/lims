

<div class="modal fade qc-goods-modal" id="detailSubDetailInspection">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-dialog-scrollable">
        <div class="modal-content qc-goods-modal__content">
            <div class="modal-header qc-goods-modal__header">
                <div class="w-100">
                    <div>
                        <b style="font-size: 12px">Detail Inspection Goods</b>
                        <p class="qc-goods-modal__subtitle">Ringkasan general inspection, material inspection, dimensi, dan approval.</p>
                    </div>
                    <div class="nav-wrapper p-0 mt-3">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tab_progress" role="tablist">
                    </ul>
                    </div>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="ig_id">
            <div class="modal-body qc-goods-modal__body">
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="tabs-wall-1" role="tabpanel" aria-labelledby="tabs-wall-1-tab"> 
                        <fieldset class="scheduler-border qc-goods-modal__section">
                            <legend class="scheduler-border">General Inspection</legend>
                            <div class="row qc-goods-modal__grid">
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
                        <fieldset class="scheduler-border p-0 mx-2 qc-goods-modal__section">
                            <legend class="scheduler-border">Detail Inspection</legend>
                           
                            <div class="row qc-goods-modal__grid">
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
                        <fieldset class="scheduler-border qc-goods-modal__section">
                            <legend class="scheduler-border">Material Inspection</legend>
                            <div class="row qc-goods-modal__grid">
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
                        <fieldset class="scheduler-border qc-goods-modal__section">
                            <legend class="scheduler-border">Wall Thickness Dimension</legend>
                            <div class="qc-goods-modal__table-wrap">
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
                            </div>
                        </fieldset>
                        <fieldset class="scheduler-border qc-goods-modal__section">
                            <legend class="scheduler-border">Evaluation Spec</legend>
                            <div class="row qc-goods-modal__grid">
                                <div class="col-2 mt-2">
                                    <p>Evaluation Spec</p>
                                </div>
                                <div class="col-4 mt-2">
                                     <p id="label_evaluation_spec"></p>
                                </div>
                                
                            </div>
                            <div class="row qc-goods-modal__grid" id="ig_approval_container" hidden>
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
            <div class="modal-footer justify-content-end qc-goods-modal__footer" id="ig_footer" hidden>
                <button id="btn_ig_approve" class="btn btn-success btn-sm ">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .qc-goods-modal .modal-dialog {
        max-width: 1160px;
    }
    .qc-goods-modal__content {
        border: 0;
        border-radius: 24px;
        overflow: hidden;
        background: linear-gradient(180deg, #f8fbfd 0%, #ffffff 100%);
        box-shadow: 0 28px 70px rgba(15, 23, 42, .18);
    }
    .qc-goods-modal__header {
        padding: 1.3rem 1.5rem 1rem;
        border-bottom: 1px solid rgba(148, 163, 184, .18);
        background:
            radial-gradient(circle at top right, rgba(23, 155, 174, .16), rgba(23, 155, 174, 0) 30%),
            linear-gradient(180deg, #ffffff 0%, #f8fbfd 100%);
        align-items: flex-start;
    }
    .qc-goods-modal__subtitle {
        margin: .35rem 0 0;
        color: #64748b;
        font-size: 11px !important;
    }
    .qc-goods-modal__header #tab_progress {
        gap: .75rem;
    }
    .qc-goods-modal__header #tab_progress .nav-item {
        margin-bottom: 0 !important;
    }
    .qc-goods-modal__header #tab_progress .nav-link {
        border-radius: 16px !important;
        border: 1px solid rgba(148, 163, 184, .16);
        background: #ffffff;
        box-shadow: 0 8px 22px rgba(15, 23, 42, .05);
        padding: .9rem 1rem;
        color: #179BAE !important;
    }
    .qc-goods-modal__header #tab_progress .nav-link.active {
        background-color: #179BAE !important;
        color: white !important;
        border-color: #179BAE !important;
    }
    .qc-goods-modal__body {
        padding: 1rem 1.25rem 1.25rem;
    }
    .qc-goods-modal__section {
        border: 1px solid rgba(148, 163, 184, .16) !important;
        border-radius: 20px !important;
        padding: 1.25rem 1.25rem 1rem 1.25rem !important;
        margin-bottom: 1.1rem !important;
        background: #ffffff;
        box-shadow: 0 10px 24px rgba(15, 23, 42, .04);
    }
    .qc-goods-modal__section legend {
        padding: 0 .55rem;
        color: #0f172a;
    }
    .qc-goods-modal__grid > div {
        margin-bottom: .2rem;
    }
    .qc-goods-modal__grid p[id] {
        display: block;
        width: 100%;
        padding: .55rem .75rem;
        border-radius: 12px;
        background: #f8fbfd;
        border: 1px solid rgba(148, 163, 184, .14);
        min-height: 34px;
    }
    .qc-goods-modal__table-wrap {
        overflow-x: auto;
        border-radius: 16px;
        border: 1px solid rgba(148, 163, 184, .16);
        background: #ffffff;
    }
    .qc-goods-modal__table-wrap table {
        min-width: 760px;
    }
    .qc-goods-modal__footer {
        border-top: 0;
        padding: 0 1.25rem 1.25rem;
    }
    .qc-goods-modal__footer .btn-success {
        min-width: 42px;
        min-height: 42px;
        border-radius: 12px;
        box-shadow: 0 12px 22px rgba(34, 197, 94, .22);
    }
    @media (max-width: 767.98px) {
        .qc-goods-modal__body {
            padding: .9rem .85rem 1rem;
        }
        .qc-goods-modal__header {
            padding: 1rem .9rem;
        }
        .qc-goods-modal__header #tab_progress {
            gap: .55rem;
        }
        .qc-goods-modal__header #tab_progress .nav-link {
            padding: .85rem 1rem;
        }
        .qc-goods-modal__section {
            padding: 1rem .85rem .75rem .85rem !important;
        }
        .qc-goods-modal__grid p[id] {
            margin-bottom: .55rem;
        }
    }
</style>
