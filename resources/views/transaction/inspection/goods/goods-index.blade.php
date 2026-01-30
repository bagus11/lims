@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Inspection Goods Record</strong>
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
                                    <div class="mt-2">
                                       <div class="row">
                                        <div class="col-3 mt-2">
                                            <p for="">Office</p>
                                        </div>
                                        <div class="col-9">
                                            <select name="officeFilter" id="officeFilter" class="select2">
                                                <option value=""> * - All Office</option>
                                            </select>
                                        </div>
                                       </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-3 mt-2">
                                            <p for=""> Status</p>
                                        </div>
                                        <div class="col-9">
                                            <select class="select2" id="statusFilter">
                                                <option value="">* - All Progress</option>
                                                <option value="0">NEW</option>
                                                <option value="1">In Progress</option>
                                                <option value="2">Approval</option>
                                                <option value="3">Done</option>
                                            </select>
                                        </div>
                                    </div>
                        
                                    <div class="mt-2 mb-2 d-flex justify-content-end">
                                        <button class="btn btn-info btn-sm mr-1" id="btnReportWO">
                                            <i class="fas fa-print"></i>  Print
                                        </button>
                                      
                                        <button class="btn btn-warning btn-sm" id="btnFilter">
                                            <i class="fa-solid fa-filter"></i> Filter
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button id="btn_add_header" class="btn btn-sm btn-success mr-2" style="float: right" data-toggle="modal" data-target="#addInspectionHeader">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                        
                    </div>
                   
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="inspection_header_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" style="text-align: center" data-sort=""></th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="date">Created at</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="inspection">Inspection Code</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="location">Location</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="machine">Status</th>
                            {{-- <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th> --}}
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
    @include('transaction.inspection.goods.modal.update-wall_thickness')
    @include('transaction.inspection.goods.modal.report-progress')
    @include('transaction.inspection.goods.modal.log-apporval_header')
    @include('transaction.inspection.goods.modal.log-apporval')
    @include('transaction.inspection.goods.modal.history-progress')
    @include('transaction.inspection.goods.modal.add-wall_thickness')
    @include('transaction.inspection.goods.modal.add-inspection_sub_detail')
    @include('transaction.inspection.goods.modal.edit-inspection_sub_detail')
    @include('transaction.inspection.goods.modal.add-inspection_header')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/transaction/inspection/goods.js"></script>
@endpush