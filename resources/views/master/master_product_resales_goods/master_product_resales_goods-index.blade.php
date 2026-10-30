@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <style>
        .table-container {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
        }
        .table-container table {
            min-width: 900px;
            width: 100% !important;
            white-space: nowrap;
        }
        .spec-row {
            margin-bottom: 8px;
        }
        .badge-spec {
            font-size: 10px;
            padding: 4px 8px;
            margin: 2px;
            display: inline-block;
        }
    </style>
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Master Product Resales Goods</strong>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-success" style="float: right" data-toggle="modal" data-target="#addMasterProductResalesGoods">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-info mr-2" style="float: right" data-toggle="modal" data-target="#uploadMasterProductResalesGoods">
                            <i class="fa-solid fa-upload"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="datatable-stepper" id="master_product_resales_goods_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" style="text-align: center" data-sort="id">ID</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="size">Size</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="spesification">Specification</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="created_at">Created At</th>
                                <th scope="col" class="sort" style="text-align: center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
@endsection

@include('master.master_product_resales_goods.modal.add-master_product_resales_goods')
@include('master.master_product_resales_goods.modal.edit-master_product_resales_goods')
@include('master.master_product_resales_goods.modal.upload-master_product_resales_goods')

@push('js')
    <script src="{{ asset('lims') }}/master/master_product_resales_goods/master_product_resales_goods.js"></script>
@endpush
