@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Resales Goods Inspection</strong>
                    </div>
                    <div class="col-6">
                        <div class="btn-group" style="float:right">
                            <button type="button" class="btn btn-sm btn-tool dropdown-toggle" style="margin-top:3px" data-toggle="dropdown">
                               <i class="fas fa-filter"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-right" id="filter" role="menu" style="width:250px !important;">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-6 mt-2">
                                            <p for="">From</p>
                                            <input type="date" id="from" class="form-control" value="{{date("Y-m-d", strtotime( date( "Y-m-d", strtotime( date("Y-m-d") ) ) . "-1 month" ) )}}">
                                        </div>
                                        <div class="col-md-6 mt-2">
                                            <p for="">To</p>
                                            <input type="date" class="form-control" id="to" value="{{date('Y-m-d')}}">
                                        </div>
                                    </div>
                                    <div class="mt-2 mb-2 d-flex justify-content-end">
                                        <button class="btn btn-warning btn-sm" id="btnFilter">
                                            <i class="fa-solid fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn_add_header" class="btn btn-sm btn-success mr-2" style="float: right" data-toggle="modal" data-target="#addResalesGoodHeader">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="resales_goods_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" style="text-align: center">No</th>
                            <th scope="col" style="text-align: center">Created at</th>
                            <th scope="col" style="text-align: center">Transaction Code</th>
                            <th scope="col" style="text-align: center">Location</th>
                            <th scope="col" style="text-align: center">No. PF</th>
                            <th scope="col" style="text-align: center">Keterangan</th>
                            <th scope="col" style="text-align: center">Created By</th>
                            <th scope="col" style="text-align: center">Status</th>
                            <th scope="col" style="text-align: center">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="detailResalesGoodModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-mainCore">
                    <b style="font-size: 12px" id="detailTitle">Resales Goods Detail</b>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3">
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card shadow-sm border-light">
                                <div class="card-header" style="background: #C96868 !important; color: white;">
                                    <b style="font-size:12px"><i class="fa-solid fa-receipt mr-1"></i> TRANSACTION INFORMATION</b>
                                </div>
                                <div class="card-body p-3" id="detailSummaryContainer" style="font-size: 13px;">
                                    <div class="text-center text-muted small py-2">Loading...</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-6">
                            <b id="detailHeaderInfo" style="font-size: 12px;"></b>
                        </div>
                        <div class="col-6">
                            <button id="btn_add_detail_row" class="btn btn-sm btn-success mr-2" style="float:right" data-toggle="modal" data-target="#addResalesGoodDetail">
                                <i class="fa-solid fa-plus"></i> Add Detail
                            </button>
                        </div>
                    </div>
                    <div class="card shadow-sm">
                        <div class="card-body p-0">
                            <table class="datatable-stepper" id="resales_goods_detail_table" style="width:100%">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="text-align:center">No</th>
                                        <th style="text-align:center">Product</th>
                                        <th style="text-align:center">Specification</th>
                                        <th style="text-align:center">Created By</th>
                                        <th style="text-align:center">Created At</th>
                                        <th style="text-align:center; width:110px;">Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="infoResalesGoodModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-mainCore">
                    <b style="font-size: 12px" id="infoTitle"><i class="fa-solid fa-circle-info mr-1"></i> Transaction Information</b>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-3" id="infoModalBody" style="font-size: 13px;">
                    <div class="text-center text-muted small py-4">Loading...</div>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @include('transaction.inspection.resales-goods.modal.add-header')
    @include('transaction.inspection.resales-goods.modal.add-detail')
    @include('transaction.inspection.resales-goods.modal.lock-transaction')
    @include('transaction.inspection.resales-goods.modal.print-report')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/transaction/inspection/resales_goods.js"></script>
@endpush
