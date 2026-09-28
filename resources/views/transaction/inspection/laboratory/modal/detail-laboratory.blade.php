<div class="modal fade" id="detailLaboratoryModal" tabindex="-1" role="dialog" aria-labelledby="detailLaboratoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b id="detailLaboratoryModalTitle" style="font-size:12px">Laboratory Test Detail</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="detail_laboratory_code">
                <div class="laboratory-summary mb-4" id="laboratory_detail_summary"></div>

                <ul class="nav nav-tabs mb-3" role="tablist">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#laboratory-result-tab" role="tab">Test Results</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#laboratory-history-tab" role="tab">Approval History</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="laboratory-result-tab" role="tabpanel">
                        <div id="laboratory_result_container"></div>
                    </div>
                    <div class="tab-pane fade" id="laboratory-history-tab" role="tabpanel">
                        <div id="laboratory_history_container"><div class="text-muted text-center py-4">No approval history.</div></div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <small class="text-muted" id="laboratory_edit_state"></small>
                <div>
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="btn_lock_laboratory" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-lock mr-1"></i> Submit for Approval
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
