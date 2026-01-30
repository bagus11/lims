<div class="modal fade" id="addRoleUserModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                Add Role User
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
               <div class="row">
                    <div class="col-md-2 mt-2">
                        <p>User</p>
                    </div>
                    <div class="col-md-10">
                        <select name="selectUser" id="selectUser" class="select2">
                        </select>
                        <input type="hidden" id="userId" class="form-control">
                        <span  style="color:red;" class="message_error text-red block userId_error"></span>
                    </div>
               </div>
               <div class="row mt-2">
                    <div class="col-md-2 mt-2">
                        <p>Role</p>
                    </div>
                    <div class="col-md-10">
                        <select name="selectRole" id="selectRole" class="select2">
                        </select>
                        <input type="hidden" id="roleId" class="form-control">
                        <span  style="color:red;" class="message_error text-red block roleId_error"></span>
                    </div>
               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btnAddRoleUser" type="button" class="btn btn-success btn-sm">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>