<div class="modal fade" id="addResalesGoodDetail">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px" id="addDetailTitle">Add Resales Goods Detail</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
               <div class="row mx-2">
                    <input type="hidden" id="detail_transaction_code">
                    <input type="hidden" id="detail_product_id">
                    <input type="hidden" id="detail_id">
                    <div class="col-md-12 mt-2">
                        <p>Product</p>
                        <div class="alert alert-info py-2 mb-1" style="font-size: 13px;">
                            <i class="fas fa-box"></i> <b id="detail_product_display">Loading product...</b>
                        </div>

                    </div>
               </div>
               <hr>
               <div class="row mx-2">
                    <div class="col-10 mt-2 mb-2">
                        <b style="font-size:12px">Specification (Standard dari master + bisa tambah custom simbol sendiri):</b>
                    </div>
                    <div class="col-2 mt-2 mb-2 text-right">
                        <button id="btn_add_custom_spec" type="button" class="btn btn-sm btn-outline-secondary">
                            <i class="fa-solid fa-plus"></i> Add Custom
                        </button>
                    </div>
                    <div class="col-12 mb-2" id="detail_spec_container" style="background:#f8f9fa; padding:10px; border-radius:6px;">
                        <div class="text-center text-muted small" id="detail_spec_empty_placeholder">
                            Loading specification...
                        </div>
                    </div>
               </div>
               <span class="detail_spesification_error text-danger"></span>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_save_detail" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i> Save Detail
                </button>
            </div>
        </div>
    </div>
</div>
