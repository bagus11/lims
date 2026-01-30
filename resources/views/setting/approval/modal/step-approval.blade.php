<div class="modal fade" id="stepApprovalModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
            <b>Update Approval</b>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-1">
          <input type="hidden" name="step_approval_code" id="step_approval_code">
          <table class="datatable-stepper nowrap display" id="approver_step_table">
              <thead>
                  <tr>
                      <th  style="text-align: center;width:10% !important">No</th>
                      <th  style="text-align: center;width:90% !important">Name</th>
                  </tr>
              </thead>
          </table> 
          
        </div>
        <div class="modal-footer">
            <button id="btn_set_approver" type="button" class="btn btn-sm btn-success">
                <i class="fas fa-check"></i>
            </button>
        </div>
      </div>
    </div>
  </div>