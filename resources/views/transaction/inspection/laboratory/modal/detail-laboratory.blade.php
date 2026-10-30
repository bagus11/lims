<div class="modal fade" id="detailLaboratoryModal" tabindex="-1" role="dialog" aria-labelledby="detailLaboratoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lab-detail" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore align-items-center">
                <div class="d-flex align-items-center">
                    <b id="detailLaboratoryModalTitle" style="font-size:12px">Laboratory Test Detail</b>
                    <span id="laboratory_detail_status" class="ml-3"></span>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="detail_laboratory_code">
                <fieldset class="scheduler-border laboratory-summary">
                    <legend class="scheduler-border">Laboratory Information</legend>
                    <div class="row">
                        <div class="col-2"><p>Product</p></div>
                        <div class="col-4"><p id="label_laboratory_product"></p></div>
                        <div class="col-2"><p>Method</p></div>
                        <div class="col-4"><p id="label_laboratory_method"></p></div>
                        <div class="col-2"><p>Test Date</p></div>
                        <div class="col-4"><p id="label_laboratory_test_date"></p></div>
                        <div class="col-2"><p>Location / Shift</p></div>
                        <div class="col-4"><p id="label_laboratory_location"></p></div>
                        <div class="col-2"><p>Production Code</p></div>
                        <div class="col-4"><p id="label_laboratory_production_code"></p></div>
                        <div class="col-2"><p>Material Lot</p></div>
                        <div class="col-4"><p id="label_laboratory_material_lot"></p></div>
                        <div class="col-2"><p>Report No.</p></div>
                        <div class="col-4"><p id="label_laboratory_report_no"></p></div>
                    </div>
                </fieldset>

                <div class="laboratory-detail-split">
                    <div class="laboratory-param-nav">
                        <div class="laboratory-param-progress" id="laboratory_param_progress"></div>
                        <div id="laboratory_param_nav"></div>
                    </div>
                    <div class="laboratory-param-panel">
                        <div id="laboratory_result_container"></div>
                        <div class="laboratory-param-pane" data-pane="comment" style="display:none">
                            <p for="">Comment</p>
                            <textarea id="laboratory_comment" class="form-control" rows="4" placeholder="Catatan QC untuk bagian IV pada form report"></textarea>
                            <div class="text-right mt-3">
                                <button type="button" id="btn_save_laboratory_comment" class="btn btn-sm btn-success">
                                    <i class="fa-solid fa-check mr-1"></i> Save Comment
                                </button>
                            </div>
                        </div>
                        <div class="laboratory-param-pane" data-pane="history" style="display:none">
                            <div id="laboratory_history_container"><div class="text-muted text-center py-4">No approval history.</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted" id="laboratory_edit_state"></small>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-danger laboratory-export-pdf" style="display:none">
                        <i class="fa-solid fa-file-pdf mr-1"></i> PDF
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success laboratory-export-excel mr-2" style="display:none">
                        <i class="fa-solid fa-file-excel mr-1"></i> Excel
                    </button>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_lock_laboratory" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-lock mr-1"></i> Submit for Approval
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
