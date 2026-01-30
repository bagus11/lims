<div class="modal fade" id="addApprovalModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Add Approval</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form_serialize" enctype="multipart/form-data">
                <div class="modal-body p-0">
                    <div class="row mx-2">
                        <div class="col-4 mt-2">
                            <p>Location</p>
                        </div>
                        <div class="col-8">
                            <select name="select_location" class="select2" id="select_location"></select>
                            <input type="hidden" class="form-control" id="location">
                            <span style="color:red;" class="message_error text-red block location_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Inspection Type</p>
                        </div>
                        <div class="col-8">
                            <select name="select_inspection_type" class="select2" id="select_inspection_type">
                                <option value="">Choose Inspection Type</option>
                                <option value="1">Laboratory</option>
                                <option value="2">Inspection Material</option>
                                <option value="3">Inspection Goods</option>
                                <option value="4">Inspection Resales Goods</option>
                            </select>
                            <input type="hidden" class="form-control" id="inspection_type">
                            <span style="color:red;" class="message_error text-red block inspection_type_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Shift</p>
                        </div>
                        <div class="col-8">
                            <select name="select_shift" class="select2" id="select_shift">
                                <option value="">Choose Type</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                            </select>
                            <input type="hidden" class="form-control" id="shift">
                            <span style="color:red;" class="message_error text-red block shift_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Type</p>
                        </div>
                        <div class="col-8">
                            <select name="select_type" class="select2" id="select_type">
                                <option value="">Choose Type</option>
                                <option value="1">Inspection Type 1</option>
                                <option value="2">Inspection Type 2</option>
                            </select>
                            <input type="hidden" class="form-control" id="type">
                            <span style="color:red;" class="message_error text-red block type_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Step</p>
                        </div>
                        <div class="col-6">
                            <input type="number" id="step" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button id="btn_save_approval_header" type="button" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
