@extends('layouts.app')
@section('content')
    @include('layouts.headers.cards')
    
    <div class="row justify-content-center mx-2">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header pb-0">
                  <div class="row">
                    <div class="col-3">
                      <p class="title-head">Role</p>
                    </div>
                    <div class="col-9">
                      <button class="btn btn-sm btn-success" id="btn_add_role" style="float: right"   data-toggle="modal" data-target="#addRoleModal">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-0">
                  <table class="datatable-stepper" id="roles_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" data-sort="name">Name</th>
                            <th scope="col" class="sort" data-sort="action">Action</th>
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
                      <button class="btn btn-sm btn-success" id="btn_add_permission" style="float: right"   data-toggle="modal" data-target="#addPermissionModal">
                        <i class="fas fa-plus"></i>
                      </button>
                    </div>
                  </div>
                </div>
                  <div class="card-body p-0">
                    <table class="datatable-stepper" id="permission_table">
                      <thead class="thead-light">
                          <tr>
                              <th scope="col" class="sort" data-sort="name">Name</th>
                              <th scope="col" class="sort" data-sort="action">Action</th>
                          </tr>
                      </thead>
                  </table>
                  </div>
                </div>
              </div>
        </div>
    </div>
@endsection
@include('setting.role_permission.modal.add_roles')
@include('setting.role_permission.modal.edit_roles')
@include('setting.role_permission.modal.add_permission')
@push('js')
    <script src="{{ asset('lims') }}/setting/role_permission/role_permission.js"></script>
@endpush