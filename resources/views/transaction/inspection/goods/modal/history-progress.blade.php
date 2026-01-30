<style>
    #historyProgressModal .modal-xl {
        max-width: 100vw; /* Set the modal width to 100% of viewport width */
    }
    #history_table_wrapper {
        width: 100%;
        overflow-x: auto;
    }
    #historyProgressModal .modal-body {
        overflow-x: auto; /* Allow horizontal scrolling in modal body */
    }
    #history_table {
        width: 100%;
        table-layout: fixed; /* Ensures columns expand to fill width */
    }
    #history_table thead{
        width: 100%;
        table-layout: fixed; /* Ensures columns expand to fill width */
    }
    .thead-light{
        width: 100%;
        table-layout: fixed; /* Ensures columns expand to fill width */
    }
</style>

<div class="modal fade" id="historyProgressModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> History Progress</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <div class="row mx-2">
                    <div class="col-md-2 mt-2">
                        <p>Sub Detail Code</p>
                    </div>
                    <div class="col-md-2">
                        <select name="select_history_subdetail" class="select2" id="select_history_subdetail"></select>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div id="history_table_wrapper">
                                <table class="datatable-stepper" id="history_table">
                                    <thead class="thead-light">
                                        <tr>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="date">Created at</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Created By</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Inspector</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Machine</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Product</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Manufacturing Code</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Length Of Pipe</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Appearance of pipes</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Marking</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Condition pipe of cut</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Angle pipe for spigot</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Color of pipes</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Material</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Tape Diameter</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Evaluation Spec</th>
                                            <th scope="col" class="sort" style="text-align: center" data-sort="">Remark</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-end">
            </div>
        </div>
    </div>
</div>
