<div class="modal fade" id="printResalesGoodModal" tabindex="-1" role="dialog" aria-labelledby="printResalesGoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content bg-default shadow-lg">
            <div class="modal-header" style="background-color:#C96868; color:#fff;">
                <h5 class="modal-title font-weight-bold" id="printResalesGoodModalLabel"><i class="fas fa-print mr-1"></i> Print Inspection Report</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <input type="hidden" id="print_transaction_code">
                <div class="alert alert-light border-info mb-4" style="border-left: 4px solid #C96868;">
                    <i class="fas fa-file-invoice mr-2 text-mainCore"></i>
                    Transaksi: <b id="print_trx_name" class="text-primary">-</b>
                    <br>
                    <small class="text-muted" id="print_trx_sub">Pilih format export dibawah ini:</small>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-lg btn-block btn-danger" id="btn_print_pdf" style="border-radius: 10px; padding: 20px 10px; box-shadow: 0 4px 12px rgba(220,53,69,.18);">
                            <i class="fas fa-file-pdf" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            <div style="font-size:18px; font-weight:700;">Download PDF</div>
                            <small class="opacity-80 mt-1 d-block">Format cetak standar report</small>
                        </button>
                    </div>
                    <div class="col-md-6 mb-2">
                        <button type="button" class="btn btn-lg btn-block btn-success" id="btn_print_excel" style="border-radius:10px; padding: 20px 10px; box-shadow:0 4px 12px rgba(25,135,84,.18);">
                            <i class="fas fa-file-excel" style="font-size:40px; display:block; margin-bottom:8px;"></i>
                            <div style="font-size:18px; font-weight:700;">Export Excel</div>
                            <small class="opacity-80 mt-1 d-block">Format spreadsheet (.xlsx)</small>
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal"><i class="fas fa-times mr-1"></i> Cancel</button>
            </div>
        </div>
    </div>
</div>
