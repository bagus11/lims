

<div class="modal fade" id="detailInspectionGoodsModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Detail Inspection Goods</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 mx-2">
                <fieldset class="scheduler-border">
                    <legend class="scheduler-border"> General Inspection</legend>
                    <div class="row">
                        <div class="col-3 mt-2">
                            <p>Approval</p>
                        </div>
                        <div class="col-9">
                            <select name="select_approval" class="select2" id="select_approval_insepection_goods_shift">
                                <option value="">Choose Approval</option>
                                <option value="1">Approve</option>
                                <option value="2">Reject</option>
                            </select>
                            <input type="hidden" id="approval_insepection_goods_shift_id">
                        </div>
                    </div>
                </fieldset>
            </div>
            
        </div>
    </div>
</div>