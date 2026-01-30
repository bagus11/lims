@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Machine List</strong>
                    </div>
                    <div class="col-6">
                    
                        <button id="btn_add_machine" class="btn btn-sm btn-success mr-2" style="float: right" data-toggle="modal" data-target="#addMasterMachineModal">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="machine_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" style="text-align: center" data-sort=""></th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="status">Status</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="no">No Machine</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="description">Category</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
    @include('master.master_machine.modal.edit-master_machine')
    @include('master.master_machine.modal.add-master_machine')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/master/master_machine/master-machine.js"></script>
@endpush