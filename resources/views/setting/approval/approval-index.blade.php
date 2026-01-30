@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Approval List</strong>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-success mr-2" id="btn_add_approval" style="float: right" id="c" data-toggle="modal" data-target="#addApprovalModal">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="approval_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" style="text-align: center" data-sort=""></th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="status">Status</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="material">Inspection Code</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="material">Location</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="material">Inspection Type</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="material">Type</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="material">Shift</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
@include('setting.approval.modal.step-approval')
@include('setting.approval.modal.add-approval')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/setting/approval/approval.js"></script>
@endpush