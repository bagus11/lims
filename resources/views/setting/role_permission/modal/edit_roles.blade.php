<div class="modal fade" id="editRolesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <b>Edit Role</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-3 mt-2">
                    <p>Name</p>
                </div>
                <div class="col-9">
                    <input type="text" class="form-control" id="roles_name_edit">
                    <input type="hidden" class="form-control" id="roleId">
                    <span  style="color:red;" class="message_error text-red block roles_name_edit_error"></span>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button id="btn_update_role" type="button" class="btn btn-sm btn-success">
                <i class="fas fa-check"></i>
            </button>
        </div>
      </div>
    </div>
  </div>