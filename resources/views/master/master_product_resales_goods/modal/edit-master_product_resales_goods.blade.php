<div class="modal fade" id="editMasterProductResalesGoods" tabindex="-1" role="dialog" aria-labelledby="editMasterProductResalesGoodsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form_edit_master_product_resales_goods" method="POST" action="{{ route('updateMasterProductResalesGoods') }}">
                @csrf
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editMasterProductResalesGoodsLabel">Edit Master Product Resales Goods</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <p for="">Name <span class="text-danger">*</span></p>
                            <input type="text" id="edit_name" name="name" class="form-control" placeholder="Product Name">
                            <span class="name_error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <p for="">Size</p>
                            <input type="text" id="edit_size" name="size" class="form-control" placeholder="Contoh: DN 200, 1 Inch, dll">
                            <span class="size_error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"><strong>Specification (Symbol &amp; Standard Value)</strong></p>
                                <button type="button" class="btn btn-sm btn-primary" id="btn_edit_add_spec">
                                    <i class="fas fa-plus"></i> Add Specification
                                </button>
                            </div>
                            <small class="text-muted">
                                <b>Symbol</b> = nama kolom/header (contoh: <code>D, d, L, Z, H, T</code>).<br>
                                <b>Value</b> = nilai standard/target untuk simbol tersebut (contoh: <code>201.0, 160.3, 405, 91, 250</code>).
                            </small>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div id="edit_spec_container">
                            </div>
                            <span class="spesification_error text-danger"></span>
                        </div>
                    </div>
                    <input type="hidden" name="spesification" id="edit_spesification">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btn_submit_edit">
                        <i class="fas fa-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
