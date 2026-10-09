<div class="modal fade" id="dashboardResalesApprovalModal" tabindex="-1" role="dialog" aria-labelledby="dashboardResalesApprovalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b id="dashboardResalesApprovalTitle" style="font-size:12px">Resales Goods Approval</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="dashboard_resales_code">
                <div class="row mb-3" id="dashboard_resales_summary"></div>
                <div id="dashboard_resales_details"></div>
                <div id="dashboard_resales_history" class="mt-3"></div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <p for="">Decision</p>
                        <select id="dashboard_resales_decision" class="form-control">
                            <option value="">Choose Decision</option>
                            <option value="1">Approve</option>
                            <option value="4">Return for Revision</option>
                        </select>
                        <span class="decision_error text-danger"></span>
                    </div>
                    <div class="col-md-8">
                        <p for="">Approval Remark</p>
                        <textarea id="dashboard_resales_remark" class="form-control" rows="2"></textarea>
                        <span class="remark_error text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn_resales_approval" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check mr-1"></i> Submit Decision
                </button>
            </div>
        </div>
    </div>
</div>
