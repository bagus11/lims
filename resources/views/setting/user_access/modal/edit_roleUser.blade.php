<div class="modal fade" id="editRoleUser">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                Edit Role User
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="row">
                    <div class="col-md-2 mt-2">
                        <p>User</p>
                    </div>
                    <div class="col-md-10">
                        <select name="selectUserEdit" id="selectUserEdit" class="select2" disabled readOnly>
                        </select>
                        <input type="hidden" id="userIdEdit" class="form-control">
                        <span  style="color:red;" class="message_error text-red block userIdEdit_error"></span>
                    </div>
               </div>
               <div class="row mt-2">
                    <div class="col-md-2 mt-2">
                        <p>Role</p>
                    </div>
                    <div class="col-md-10">
                        <select name="selectRoleEdit" id="selectRoleEdit" class="select2">
                        </select>
                        <input type="hidden" id="roleIdEdit" class="form-control">
                        <span  style="color:red;" class="message_error text-red block roleIdEdit_error"></span>
                    </div>
               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btnUpdateRoleUser" type="button" class="btn btn-success btn-sm">
                    <i class="fas fa-save"></i>
                </button>
            </div>
        </div>
    </div>
</div>