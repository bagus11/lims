<div class="modal fade" id="laboratoryMethodModal" tabindex="-1" role="dialog" aria-labelledby="laboratoryMethodModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b id="laboratoryMethodModalTitle" style="font-size:12px">Add Test Method</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="laboratory_method_edit_id">
                <input type="hidden" id="laboratory_method_mode" value="add">
                <div class="row">
                    <div class="col-md-4 mt-2"><p for="">Code</p></div>
                    <div class="col-md-8">
                        <input type="text" id="laboratory_method_code" class="form-control" maxlength="100">
                        <span class="code_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mt-2"><p for="">Name</p></div>
                    <div class="col-md-8">
                        <input type="text" id="laboratory_method_name" class="form-control" maxlength="255">
                        <span class="name_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mt-2"><p for="">Description</p></div>
                    <div class="col-md-8">
                        <textarea id="laboratory_method_description" class="form-control" rows="3"></textarea>
                        <span class="description_error text-danger"></span>
                    </div>
                    <div class="col-md-4 mt-2 laboratory-method-status"><p for="">Status</p></div>
                    <div class="col-md-8 laboratory-method-status">
                        <select id="laboratory_method_status" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="status_error text-danger"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" id="btn_save_laboratory_method" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check mr-1"></i> Save Method
                </button>
            </div>
        </div>
    </div>
</div>
