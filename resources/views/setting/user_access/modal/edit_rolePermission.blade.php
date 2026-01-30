<div class="modal fade" id="deletePermission">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                Add Role Permission
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
               <div class="row">
              <div class="col-md-12">
                <input type="hidden" id="roleIdPermissionDelete">
                <table class="datatable-stepper" id="rolePermissionActiveTable">
                    <thead>
                        <tr>
                            <th style="text-align: center"><input type="checkbox" id="checkAllPermissionActiveTable" name="checkAllPermissionActiveTable" class="checkAllPermissionActiveTable" style="border-radius: 5px !important;"></th>
                            <th style="text-align: center">Name</th>
                          
                        </tr>
                    </thead>
                </table>
              </div>
               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button id="btnDeleteRolePermission" type="button" class="btn btn-success btn-sm">
                    <i class="fas fa-check"></i>
                </button>
            </div>
        </div>
    </div>
</div>