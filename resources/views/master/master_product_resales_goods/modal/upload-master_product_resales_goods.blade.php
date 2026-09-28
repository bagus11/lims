
<div class="modal fade" id="uploadMasterProductResalesGoods" tabindex="-1" role="dialog" aria-labelledby="uploadMasterProductResalesGoodsLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px">Upload Master Product Resales Goods</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" enctype="multipart/form-data" id="form_upload_resales_goods">
                <div class="modal-body p-0">
                    <div class="row mx-1">
                        <div class="col-12">
                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">General Information</legend>
                                <div class="row">
                                    <div class="col-12">
                                        <p>Download format:
                                            <a href="{{ route('downloadFormatResalesGoods') }}" style="color: green">
                                                <i class="fa-solid fa-file-excel"></i> .xlsx
                                            </a>
                                            &nbsp;|&nbsp;
                                            <a href="{{ route('downloadFormatCsvResalesGoods') }}" style="color: #2c7be5">
                                                <i class="fa-solid fa-file-csv"></i> .csv
                                            </a>
                                        </p>
                                        <small class="text-muted">
                                            Kolom <b>name</b> wajib diisi.<br>
                                            Untuk masing-masing spesifikasi: isi <b>spec_N_symbol</b> (nama simbol, mis. <code>D, d, L, Z, H</code>) dan <b>spec_N_value</b> (nilai standard/target, mis. <code>201.0, 160.3, 405, 91, 250</code>).<br>
                                            Total ada 10 pasang kolom spec (1-10). Kosongkan jika tidak dipakai.<br>
                                            Format support: <b>.xlsx, .xls, .csv</b>
                                        </small>
                                    </div>
                                    <div class="col-3 mt-2">
                                        <p>Upload File</p>
                                    </div>
                                    <div class="col-9">
                                        <input type="file" class="form-control" id="resales_goods_upload_file" accept=".xlsx,.xls,.csv">
                                        <span class="upload_file_error text-danger"></span>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-sm btn-success" id="btn_upload_resales_goods">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
