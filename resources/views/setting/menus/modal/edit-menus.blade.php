
<div class="modal fade" id="editMenusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Edit Menus</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
               <div class="row mx-2">
                    <div class="col-md-4 mt-2">
                        <p>Name</p>
                    </div>
                    <div class="col-md-8">
                        <input type="hidden" class="form-control" id="menus_id">
                        <input type="text" class="form-control" id="name_edit">
                        <span class="message_error name_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Type</p>
                    </div>
                    <div class="col-md-8">
                        <select name="select_type_edit" class="select2" id="select_type_edit">
                            <option value="">Choose Type</option>
                            <option value="1">Menus</option>
                            <option value="2">Submenus</option>
                        </select>
                        <input type="hidden" class="form-control" id="type_edit">
                        <span class="message_error type_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Icon</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="icon_edit">
                        <span class="message_error icon_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Link</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="link_edit" disabled>
                        <span class="message_error link_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Status</p>
                    </div>
                    <div class="col-8 mt-2">
                        <div id="status_container"></div>
                    </div>

               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btn_update_menus" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>