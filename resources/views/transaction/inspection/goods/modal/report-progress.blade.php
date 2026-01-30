

<div class="modal fade" id="reportProgressModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Report Progress </b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
               <input type="hidden" id="export_code">
                <div class="row mx-2">
                   <div class="col-4 mt-2">
                        <p>Export to</p>
                   </div>
                   <div class="col-8">
                        <select name="select_report" class="select2" id="select_report">
                            <option value="">Choose File type</option>
                            <option value="1">PDF</option>
                            <option value="2">Excell</option>
                        </select>
                       
                   </div>
               </div>
               <div class="row mx-2 mb-2 d-flex justify-content-end">
                <button id="btn_export_progress" type="button" class="btn btn-sm btn-success">
                    <i class="fa-solid fa-check"></i>
                </button>
               </div>
            </div>
           
        </div>
    </div>
</div>