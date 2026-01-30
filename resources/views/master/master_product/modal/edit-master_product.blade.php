

<div class="modal fade" id="editMasterProduct">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <b style="font-size: 12"> Detail Product</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <input type="hidden" id="edit_id">
                <div class="modal-body p-0">
                    <div class="row mx-1">
                        <div class="col-12">
                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
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
                                    
                                    <fieldset class="scheduler-border mt-4">
                                        <legend class="scheduler-border">General Information</legend>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Seri</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_seri"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Join</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_join"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Kode Ts</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_kode_ts"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Kode BE</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_kode_be"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Berat (btg)</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_berat_pcs"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Berat (m)</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_berat"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Formula</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_formula"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Marking 2</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_2"></p>
                                            </div>
                                        </div>
                                    </fieldset>
                                    
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">OD Standard</legend>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Min</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_min_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Tlrs</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_toleransi_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Max</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_max_od"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Avg</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_avg_od"></p>
                                            </div>
                                        </div>
                                    </fieldset>
                                    
                                    <fieldset class="scheduler-border">
                                        <legend class="scheduler-border">WT Standard</legend>
                                        <div class="row">
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Min</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_min_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Tlrs</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_toleransi_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Marking</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_marking_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
                                                <p>Max</p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-4">
                                                <p id="label_max_wt"></p>
                                            </div>
                                            <div class="col-12 col-sm-6 col-md-2">
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
                                        <div class="card shadow">
                                            <div class="card-body p-0 mt-2 mx-4">
                                                        <div class="nav-wrapper">
                                                            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text_2" role="tablist">
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
                                                                <div class="row">
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
                                                                <div class="row">
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
                                                                <div class="row">
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
                                            <div class="card-footer justify-content-end p-0 mx-4 my-2">
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
                                                <div class="table-container">
                                                    <table class="datatable-stepper p-0" style="width: 200%" id="log_master_product_table">
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
                <div class="modal-footer justify-content-end">

                </div>
            
        </div>
    </div>
</div>

<style>
    /* Change the color of the active tab link to blue */
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
  </style>