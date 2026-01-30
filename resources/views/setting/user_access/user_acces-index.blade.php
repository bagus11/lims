@extends('layouts.app')
@section('content')
@include('layouts.headers.cards')
<div class="row justify-content-center mx-2 mt-2">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-3">
                  <p class="title-head">Role</p>
                </div>
                <div class="col-9">
                  <button class="btn btn-sm btn-success" id="btn_add_role_user" style="float: right"   data-toggle="modal" data-target="#addRoleUserModal">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="role_user_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="role">Role</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
          </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header pb-0">
              <div class="row">
                <div class="col-3">
                  <p class="title-head">Permission</p>
                </div>
                <div class="col-9">
                </div>
              </div>
            </div>
              <div class="card-body p-0">
                <table class="datatable-stepper" id="role_permission_table">
                    <thead>
                        <tr>
                            <th style="text-align: center">Name</th>
                            <th style="text-align: center">Action</th>
                        </tr>
                    </thead>
                </table>
              </div>
            </div>
          </div>
    </div>
</div>


@include('setting.user_access.modal.add_roleUser')
@include('setting.user_access.modal.edit_roleUser')
@include('setting.user_access.modal.add_rolePermission')
@include('setting.user_access.modal.edit_rolePermission')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/setting/user_access/user_access.js"></script>
@endpush