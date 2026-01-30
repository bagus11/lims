@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    
    <div class="row mx-2 mt-4 d-flex justify-content-center">
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <strong style="text-align:left" class="cardTitle">Menu List</strong>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-success" style="float: right" data-toggle="modal" data-target="#addMenusModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                </div>
                <div class="card-body p-0">
                    <table class="datatable-stepper" id="menus_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="type">Type</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="status">Status</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
                <div class="card-footer"></div>
            </div>
        </div>
        <div class="col-md-6 mb-1">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <strong style="text-align:left" class="cardTitle">Sub List</strong>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-sm btn-success btn-radius" id="btn_add_subemnus" style="float: right" data-toggle="modal" data-target="#addSubmenusModal">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="datatable-stepper" id="submenus_table">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="type">Parent</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="status">Status</th>
                                <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                              
                            </tr>
                        </thead>
                    </table>
                </div>
                
                <div class="card-footer"></div>
            </div>
        </div>
    </div>
@endsection
@include('setting.menus.modal.add-menus')
@include('setting.menus.modal.add-submenus')
@include('setting.menus.modal.edit-menus')
@include('setting.menus.modal.edit-submenus')
@push('js')
    <script src="{{ asset('lims') }}/setting/menus/menus.js"></script>
@endpush