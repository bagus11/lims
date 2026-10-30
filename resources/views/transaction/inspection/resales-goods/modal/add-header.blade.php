<div class="modal fade" id="addResalesGoodHeader">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Add Resales Goods Transaction</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
               <div class="row mx-2">
                    <div class="col-md-6 mt-2">
                        <p>Location <span class="text-danger">*</span></p>
                        <select name="select_location" class="select2 form-control" id="select_location" style="width:100%"></select>
                        <input type="hidden" id="location_id">
                        <span class="location_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Product <span class="text-danger">*</span></p>
                        <select class="select2 form-control" id="select_product" style="width:100%"></select>
                        <input type="hidden" id="product_id">
                        <span class="product_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Inspector <span class="text-danger">*</span></p>
                        <select class="select2 form-control" id="select_inspector" style="width:100%"></select>
                        <input type="hidden" id="inspector_id">
                        <span class="inspector_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Supplier <span class="text-danger">*</span></p>
                        <select class="select2 form-control" id="select_supplier" style="width:100%"></select>
                        <input type="hidden" id="supplier_id">
                        <span class="supplier_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Produsen <span class="text-danger">*</span></p>
                        <select class="select2 form-control" id="select_produsen" style="width:100%"></select>
                        <input type="hidden" id="produsen_id">
                        <span class="produsen_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Type <span class="text-danger">*</span></p>
                        <select class="select2 form-control" id="select_type" style="width:100%">
                            <option value="">- Select Type -</option>
                            <option value="fitting">Fitting</option>
                            <option value="non fitting">Non Fitting</option>
                        </select>
                        <span class="type_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Quantity <span class="text-danger">*</span></p>
                        <input type="number" id="quantity" class="form-control" value="1" min="1">
                        <span class="quantity_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Standard</p>
                        <input type="text" id="standard" class="form-control" placeholder="ex: ISO">
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Certificate</p>
                        <input type="text" id="certificate" class="form-control" placeholder="No. Certificate">
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Brand</p>
                        <input type="text" id="brand" class="form-control" placeholder="Brand">
                    </div>
                    <div class="col-md-6 mt-2">
                        <p>Packaging</p>
                        <input type="text" id="packaging" class="form-control" placeholder="Packaging">
                    </div>
               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_save_header" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>
