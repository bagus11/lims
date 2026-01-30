

<div class="modal fade" id="editSubmenusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Edit Submenus</b>
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
                        <input type="text" class="form-control" id="submenus_name_edit">
                        <span class="message_error submenus_name_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Parent</p>
                    </div>
                    <div class="col-md-8">
                        <select name="select_parent_edit" class="select2" id="select_parent_edit">
                        </select>
                        <input type="hidden" class="form-control" id="parent_id_edit">
                        <span class="message_error parent_id_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Icon</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="submenus_icon_edit">
                        <span class="message_error submenus_icon_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Link</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" disabled id="submenus_link_edit">
                        <span class="message_error submenus_link_edit_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Status</p>
                    </div>
                    <div class="col-md-8">
                        <div id="status_container_submenus"></div>
                    </div>

               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btn_update_submenus" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>