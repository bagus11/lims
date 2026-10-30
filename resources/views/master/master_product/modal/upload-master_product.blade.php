


<div class="modal fade" id="uploadMasterProduct">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Upload Master Product</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form_serialize" enctype="multipart/form-data">
                <div class="modal-body p-0">
                    <div class="row mx-1">
                        <div class="col-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">General Information</legend>
                                <div class="row">
                                    <div class="col-12">
                                        <p>Here's upload Master Product Format :
                                            <a href="{{URL::asset('master_product.xlsx')}}" style="color: green">
                                            <i class="fa-solid fa-file-excel"></i> .xlsx (original)
                                            </a>
                                            &nbsp;|&nbsp;
                                            <a href="{{ route('downloadFormatMasterProduct') }}" style="color: green">
                                            <i class="fa-solid fa-file-excel"></i> .xlsx (new)
                                            </a>
                                            &nbsp;|&nbsp;
                                            <a href="{{ route('downloadFormatCsvMasterProduct') }}" style="color: #2c7be5">
                                            <i class="fa-solid fa-file-csv"></i> .csv
                                            </a>
                                        </p>
                                        <small class="text-muted">
                                            Format support: <b>.xlsx, .xls, .csv</b>
                                        </small>
                                    </div>
                                    <div class="col-3 mt-2">
                                        <p>Upload File</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="file" class="form-control" id="upload_file" accept=".xlsx,.xls,.csv">
                                        <span class="upload_file_error text-danger"></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button id="btn_upload_master_product" type="submit" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
