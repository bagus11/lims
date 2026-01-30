

<div class="modal fade" id="logApprovalModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Log Approval</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0 mx-2">
               
                 <fieldset class="scheduler-border">
                    <legend class="scheduler-border">General Information</legend>
                    <div class="row">
                        <div class="col-4">
                            <p>Location</p>
                        </div>
                        <div class="col-8">
                            <p id="approval_location"></p>
                        </div>
                        <div class="col-4">
                            <p>Shift</p>
                        </div>
                        <div class="col-8">
                            <p id="approval_shift"></p>
                        </div>
                    </div>
                 </fieldset>
                 <fieldset class="scheduler-border p-0">
                    <legend class="scheduler-border">Approval List</legend>
                    <div class="dropdown-menu-xl p-0" id="history_approval_container">  
                        
                    </div>
                 </fieldset>
               
            </div>
         
        </div>
    </div>
</div>