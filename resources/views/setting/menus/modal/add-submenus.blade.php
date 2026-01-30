

<div class="modal fade" id="addSubmenusModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Add Submenus</b>
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
                        <input type="text" class="form-control" id="submenus_name">
                        <span class="message_error submenus_name_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Parent</p>
                    </div>
                    <div class="col-md-8">
                        <select name="select_parent" class="select2" id="select_parent">
                        </select>
                        <input type="hidden" class="form-control" id="parent_id">
                        <span class="message_error parent_id_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Icon</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="submenus_icon">
                        <span class="message_error icon_error text-red block "></span>
                    </div>
                    <div class="col-md-4 mt-2">
                        <p>Link</p>
                    </div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" id="submenus_link">
                        <span class="message_error link_error text-red block "></span>
                    </div>

               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btn_save_submenus" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>