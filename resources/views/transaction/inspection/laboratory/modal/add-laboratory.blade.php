<div class="modal fade" id="addLaboratoryModal" tabindex="-1" role="dialog" aria-labelledby="addLaboratoryModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b id="addLaboratoryModalTitle" style="font-size:12px">Create Product Laboratory Test</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <p for="">Master Product</p>
                        <select id="laboratory_add_product_id" class="select2" style="width:100%"></select>
                        <span class="product_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p for="">Test Method</p>
                        <select id="laboratory_add_product_method_id" class="select2" style="width:100%"></select>
                        <span class="product_method_id_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p for="">Test Date</p>
                        <input type="date" id="laboratory_add_test_date" class="form-control" value="{{ date('Y-m-d') }}">
                        <span class="test_date_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p for="">Report No.</p>
                        <input type="text" id="laboratory_add_report_no" class="form-control">
                        <span class="report_no_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mb-3">
                        <p for="">Shift</p>
                        <select id="laboratory_add_shift" class="form-control">
                            <option value="1">Shift 1</option>
                            <option value="2">Shift 2</option>
                            <option value="3">Shift 3</option>
                        </select>
                        <span class="shift_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p for="">Production Code Number</p>
                        <input type="text" id="laboratory_add_production_code" class="form-control">
                        <span class="production_code_number_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p for="">Material Lot Number</p>
                        <input type="text" id="laboratory_add_material_lot" class="form-control">
                        <span class="material_lot_number_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p for="">Location</p>
                        <select id="laboratory_add_location_id" class="select2" style="width:100%"></select>
                        <span class="location_id_error text-danger"></span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <p for="">Machine</p>
                        <select id="laboratory_add_machine_id" class="select2" style="width:100%"></select>
                        <span class="machine_id_error text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_save_laboratory_header" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check mr-1"></i> Create Test
                </button>
            </div>
        </div>
    </div>
</div>
