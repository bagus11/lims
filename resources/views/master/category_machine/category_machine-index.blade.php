@extends('layouts.app')

@section('content')
    @include('layouts.headers.cards')
    <div class="row mx-2 mt-4 d-flex justify-content-center">
      <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <strong class="cardTitle">Category List</strong>
                    </div>
                    <div class="col-6">
                        <button class="btn btn-sm btn-success mr-2" style="float: right" data-toggle="modal" data-target="#addCategoryModal">
                            <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="datatable-stepper" id="category_table">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col" class="sort" style="text-align: center" data-sort="name">Name</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="description">Description</th>
                            <th scope="col" class="sort" style="text-align: center" data-sort="action">Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="card-footer"></div>
        </div>
      </div>
    </div>
    @include('master.category_machine.modal.add-category_machine')
    @include('master.category_machine.modal.edit-category_machine')
@endsection
@push('js')
    <script src="{{ asset('lims') }}/master/category_machine/category_machine.js"></script>
@endpush