<div class="modal fade" id="lockResalesGoodModal" tabindex="-1" role="dialog" aria-labelledby="lockResalesGoodModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px;"> Lock Resales Goods Transaction</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-2">
                <input type="hidden" id="lock_transaction_code">
               <div class="row mx-2">
                    <div class="col-md-12 mt-2">
                        <p style="font-size:12px;" id="lock_header_info">
                            <i class="fas fa-lock mr-1 text-mainCore"></i>
                            Anda akan melakukan <b>Lock Transaksi</b>. Setelah di-Lock, status transaksi berubah menjadi <b class="text-info">Approval Progress</b> dan data tidak dapat di-edit lagi. Silakan lengkapi informasi dibawah:
                        </p>
                    </div>
                    <div class="col-md-12 mt-3">
                        <p>No. PF <span class="text-danger">*</span></p>
                        <input type="text" class="form-control" id="lock_no_pf" placeholder="Masukkan Nomor PF">
                        <span class="lock_no_pf_error text-danger"></span>
                    </div>
                    <div class="col-md-12 mt-2">
                        <p>Keterangan</p>
                        <textarea class="form-control" id="lock_keterangan" rows="3" placeholder="(Opsional) Catatan tambahan..."></textarea>
                        <span class="lock_keterangan_error text-danger"></span>
                    </div>
               </div>
            </div>
            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                <button id="btn_submit_lock" type="button" class="btn btn-sm btn-warning">
                    <i class="fa-solid fa-lock"></i> Confirm Lock
                </button>
            </div>
        </div>
    </div>
</div>
