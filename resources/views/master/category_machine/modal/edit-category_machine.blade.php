

<div class="modal fade" id="editCategoryModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Edit Category</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <div class="modal-body p-0">
                    <div class="row mx-2">
                        <div class="col-4 mt-2">
                            <p>Name</p>
                        </div>
                        <div class="col-8">
                            <input type="hidden" class="form-control" id="edit_id">
                            <input type="text" class="form-control" id="name_edit">
                            <span  style="color:red;" class="message_error text-red block name_edit_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Description</p>
                        </div>
                        <div class="col-8">
                            <textarea class="form-control" id="description_edit" rows="3"></textarea>
                            <span  style="color:red;" class="message_error text-red block description_edit_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button id="btn_update_category" type="button" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
        </div>
    </div>
</div>