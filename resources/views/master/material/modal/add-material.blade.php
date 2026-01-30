

<div class="modal fade" id="addMaterialModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-mainCore">
                <b style="font-size: 12px"> Add Material</b>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form class="form" id="form_serialize" enctype="multipart/form-data">
                <div class="modal-body p-0">
                    <div class="row mx-2">
                        <div class="col-4 mt-2">
                            <p>Name</p>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="name">
                            <span  style="color:red;" class="message_error text-red block name_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Spec</p>
                        </div>
                        <div class="col-8">
                            <input type="text" class="form-control" id="spec">
                            <span  style="color:red;" class="message_error text-red block spec_error"></span>
                        </div>
                        <div class="col-4 mt-2">
                            <p>Description</p>
                        </div>
                        <div class="col-8">
                            <textarea class="form-control" id="description" rows="3"></textarea>
                            <span  style="color:red;" class="message_error text-red block description_error"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button id="btn_save_material" type="button" class="btn btn-sm btn-success">
                        <i class="fa-solid fa-check"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>