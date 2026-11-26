<div class="modal fade" id="addMasterProductResalesGoods" tabindex="-1" role="dialog" aria-labelledby="addMasterProductResalesGoodsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="form_add_master_product_resales_goods" method="POST" action="{{ route('addMasterProductResalesGoods') }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addMasterProductResalesGoodsLabel">Add Master Product Resales Goods</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mt-2">
                            <p for="">Name <span class="text-danger">*</span></p>
                            <input type="text" id="add_name" name="name" class="form-control" placeholder="Product Name">
                            <span class="name_error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <p for="">Size</p>
                            <input type="text" id="add_size" name="size" class="form-control" placeholder="Contoh: DN 200, 1 Inch, dll">
                            <span class="size_error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <p for="">UoM (Satuan Pengukuran)</p>
                            <input type="text" id="add_uom" name="uom" class="form-control" placeholder="Contoh: mm, inch, pcs">
                            <span class="uom_error text-danger"></span>
                        </div>
                        <div class="col-md-12 mt-2">
                            <p for="">Drawing Produk</p>
                            <input type="file" id="add_drawing_file" class="form-control-file" accept="image/*">
                            <small class="text-muted">
                                Gambar (PNG/JPG) yang akan dicetak di dalam report. Maksimal 2 MB.
                                Untuk melampirkan drawing berupa PDF, simpan produk ini dahulu lalu buka Edit.
                            </small>
                            <span class="drawing_error text-danger d-block"></span>
                            <div id="add_drawing_preview" class="mt-2"></div>
                            <input type="hidden" id="add_drawing">
                        </div>
                        <div class="col-md-12 mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-0"><strong>Specification (Symbol, Nilai &amp; Toleransi)</strong></p>
                                <button type="button" class="btn btn-sm btn-primary" id="btn_add_spec">
                                    <i class="fas fa-plus"></i> Add Specification
                                </button>
                            </div>
                            <small class="text-muted">
                                Satu baris = satu simbol. Isi <b>Nilai</b> dan <b>± Toleransi</b>, lalu Min &amp; Max terisi
                                otomatis. Untuk spesifikasi bergaya rentang, kosongkan Nilai &amp; Toleransi lalu isi
                                <b>Min</b> dan <b>Max</b> langsung.<br>
                                Contoh: <code>A</code> &rarr; <code>238 ± 1.4</code> &nbsp;|&nbsp; <code>K</code> &rarr; <code>55 s/d 58</code>.
                            </small>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="form-row text-muted mb-1" style="font-size:11px;">
                                <div class="col">Symbol</div>
                                <div class="col">Nilai</div>
                                <div class="col">± Toleransi</div>
                                <div class="col">Min</div>
                                <div class="col">Max</div>
                                <div class="col-auto" style="visibility:hidden;">
                                    <button type="button" class="btn btn-sm"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                            <div id="add_spec_container">
                            </div>
                            <span class="spesification_error text-danger"></span>
                        </div>
                    </div>
                    <input type="hidden" name="spesification" id="add_spesification">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-success" id="btn_submit_add">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
