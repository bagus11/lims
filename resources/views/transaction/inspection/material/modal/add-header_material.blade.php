

<div class="modal fade" id="addInspectionHeader">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Add Inspection Header</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <fieldset class="scheduler-border p-0">
                    <legend class="scheduler-border" style="font-size: 12px">Form Add Inspection Material</legend>
                    <div class="row">
                        <div class="col-2 mt-2">
                             <p>Type</p>
                        </div>
                        <div class="col-4">
                             <select name="select_type" class="select2" id="select_type">
                                 <option value="">Choose Type</option>
                                 <option value="1">Resin</option>
                                 <option value="2">Pellet</option>
                             </select>
                             <input type="hidden" class="form-control" id="type">
                             <span class="message_error type_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Location</p>
                        </div>
                        <div class="col-4">
                             <select name="select_location" class="select2" id="select_location"></select>
                             <input type="hidden" class="form-control" id="location">
                             <span class="message_error location_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Material</p>
                        </div>
                        <div class="col-4">
                             <select name="select_material" class="select2" id="select_material"></select>
                             <input type="hidden" class="form-control" id="material">
                             <span class="message_error material_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Inspector</p>
                        </div>
                        <div class="col-4">
                             <select name="select_inspector" class="select2" id="select_inspector"></select>
                             <input type="hidden" class="form-control" id="inspector">
                             <span class="message_error inspector_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Supplier</p>
                        </div>
                        <div class="col-4">
                             <select name="select_supplier" class="select2" id="select_supplier"></select>
                             <input type="hidden" class="form-control" id="supplier">
                             <span class="message_error supplier_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Brand</p>
                        </div>
                        <div class="col-4">
                             <input type="text" class="form-control" id="brand">
                             <span class="message_error brand_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Amount</p>
                        </div>
                        <div class="col-4">
                             <input type="number" class="form-control" id="amount">
                             <span class="message_error amount_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Weight</p>
                        </div>
                        <div class="col-4">
                             <input type="number" class="form-control" id="weight">
                             <span class="message_error weight_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Certificate</p>
                        </div>
                        <div class="col-4">
                             <input type="text" class="form-control" id="certificate">
                             <span class="message_error certificate_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Standard</p>
                        </div>
                        <div class="col-4">
                             <input type="text" class="form-control" id="standard">
                             <span class="message_error standard_error text-red block "></span>
                        </div>
                        <div class="col-2 mt-2">
                             <p>Lot Number</p>
                        </div>
                        <div class="col-4">
                             <input type="text" class="form-control" id="no_lot">
                             <span class="message_error no_lot_error text-red block "></span>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btn_save_header" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>