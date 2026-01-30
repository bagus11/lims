@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <style>
        .table-container {
            display: block;
            overflow-x: auto; /* Enable horizontal scrolling */
            overflow-x: hidden;
            white-space: nowrap; /* Prevent wrapping of table content */
        }

        /* Optional: You can also add some padding to improve the appearance */
        .table-container table {
            width: 1400px !important;
        }
    </style>
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Master Product</strong>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-success" style="float: right">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-info mr-2" style="float: right" data-toggle="modal" data-target="#uploadMasterProduct">
                            <i class="fa-solid fa-upload"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-container">
                    <table class="datatable-stepper" id="master_product_table" style="width: 200%">
                        <thead class="thead-light">
                            <tr>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="seri">Seri</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="join">Join</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="ukuran">Ukuran</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center" data-sort="marking">Marking</th>
                                <th colspan="2" scope="col" class="sort" style="text-align: center">Berat</th>
                                <th colspan="2" scope="col" class="sort" style="text-align: center">Kode</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center">Marking 2</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center">Formula</th>
                                <th colspan="5" scope="col" class="sort" style="text-align: center">OD Standart</th>
                                <th colspan="5" scope="col" class="sort" style="text-align: center">WT Standart</th>
                                <th rowspan="2" scope="col" class="sort" style="text-align: center">Action</th>
                            </tr>
                            <tr style="border-top: solid white 1px !important">
                                <th>pcs</th>
                                <th>meter</th>
                                <th>TD</th>
                                <th>BS</th>
                                <th>Min</th>
                                <th>Tlrs</th>
                                <th>Marking</th>
                                <th>Avg</th>
                                <th>Max</th>
                                <th>Min</th>
                                <th>Tlrs</th>
                                <th>Marking</th>
                                <th>Avg</th>
                                <th>Max</th>
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
@include('master.master_product.modal.upload-master_product')
@include('master.master_product.modal.edit-master_product')
@push('js')
    <script src="{{ asset('lims') }}/master/master_product/master_product.js"></script>
@endpush