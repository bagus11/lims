

<div class="modal fade" id="editMasterMachineModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Edit Machine</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
          
                <div class="modal-body p-0">
                    <div class="row mx-2">
                        <div class="col-4 mt-2">
                            <p>Category</p>
                        </div>
                        <div class="col-8">
                            <select name="select_category_edit" class="select2" id="select_category_edit"></select>
                            <input type="hidden" class="form-control" id="edit_id">
                            <input type="hidden" class="form-control" id="category_id_edit">
                            <span  style="color:red;" class="message_error text-red block category_id_edit_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Name</p>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="name_edit">
                            <span  style="color:red;" class="message_error text-red block name_edit_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>No Machine</p>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="no_machine_edit">
                            <span  style="color:red;" class="message_error text-red block no_machine_edit_error"></span>
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
                    <button id="btn_update_machine" type="button" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
           
        </div>
    </div>
</div>