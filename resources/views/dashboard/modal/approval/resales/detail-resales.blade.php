<div class="modal fade qc-inspection-modal" id="dashboardResalesApprovalModal" tabindex="-1" role="dialog" aria-labelledby="dashboardResalesApprovalTitle" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content qc-inspection-modal__content">
            <div class="modal-header qc-inspection-modal__header">
                <div>
                    <b id="dashboardResalesApprovalTitle" style="font-size:13px">Resales Goods Approval</b>
                    <p class="qc-inspection-modal__subtitle">Ringkasan transaksi resales goods, hasil pengukuran sample, dan keputusan approval dalam satu modal.</p>
                </div>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body qc-inspection-modal__body">
                <input type="hidden" id="dashboard_resales_code">

                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                    <legend class="scheduler-border" style="font-size: 12px">Transaction Information</legend>
                    <div class="row qc-inspection-modal__grid">
                        <div class="col-2"><p>Product</p></div>
                        <div class="col-4"><p id="dashboard_resales_product"></p></div>
                        <div class="col-2"><p>Location</p></div>
                        <div class="col-4"><p id="dashboard_resales_location"></p></div>
                        <div class="col-2"><p>Supplier / Produsen</p></div>
                        <div class="col-4"><p id="dashboard_resales_supplier"></p></div>
                        <div class="col-2"><p>Inspector</p></div>
                        <div class="col-4"><p id="dashboard_resales_inspector"></p></div>
                        <div class="col-2"><p>Quantity</p></div>
                        <div class="col-4"><p id="dashboard_resales_qty"></p></div>
                        <div class="col-2"><p>Type</p></div>
                        <div class="col-4"><p id="dashboard_resales_type"></p></div>
                        <div class="col-2"><p>No. PF</p></div>
                        <div class="col-4"><p id="dashboard_resales_no_pf"></p></div>
                        <div class="col-2"><p>Certificate / Standard</p></div>
                        <div class="col-4"><p id="dashboard_resales_cert"></p></div>
                        <div class="col-2"><p>Keterangan</p></div>
                        <div class="col-4"><p id="dashboard_resales_keterangan"></p></div>
                    </div>
                </fieldset>

                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                    <legend class="scheduler-border" style="font-size: 12px">Sample Measurements</legend>
                    <div id="dashboard_resales_details"></div>
                </fieldset>

                <fieldset class="scheduler-border p-0 qc-inspection-modal__section">
                    <legend class="scheduler-border" style="font-size: 12px">Decision</legend>
                    <div class="row">
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
                            <textarea id="dashboard_resales_remark" class="form-control" rows="1"></textarea>
                            <span class="remark_error text-danger"></span>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer qc-inspection-modal__footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="btn_resales_approval" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check mr-1"></i> Submit Decision
                </button>
            </div>
        </div>
    </div>
</div>
