@extends('layouts.app')

@section('content')


    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">{{$title}}</span></h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
                    <span class="breadcrumb-item active">{{$title}}</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content">

        <!-- Basic datatable -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"></h5>
                <div class="header-elements">
                    @can('create-charges')
                        <a data-target="addModal" data-action="{{route('store-charge')}}" id="addBtn" href="#"
                           class="btn bg-primary-400 btn-labeled btn-labeled-left rounded-round"><b><i
                                    class="fas fa-plus"></i></b> Add {{$title}} </a>
                    @endcan
                </div>
            </div>
            @include('backend.message')

            <table class="table datatable-basic">
                <thead>
                <tr>
                      <th>Name</th>
                      <th>Possession</th>
                      <th>Transfer</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($charges as $charge)
                    <tr>
                          <td>{{$charge->name}}</td>
                        <td>{{ showBoolean($charge->is_possession)}}</td>
                        <td>{{ showBoolean($charge->is_transfer)}}</td>
                        <td>{{ showStatus($charge->is_active)}}</td>
                        <td>
                            <div class="d-flex">
                                @can('edit-charges')
                                    <a id="editBtn" href="" data-target="editModal"
                                       data-action="{{route('update-charge')}}"
                                       data-id="{{$charge->id}}" data-url="{{route('get-charge')}}"
                                       class="text-primary mr-1 mr-1 edit-btn "><i
                                            class="far fa-edit"></i></a>
                                @endcan

                                @can('delete-charges')
                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-charge')}}"
                                       data-status='0'
                                       data-label="inactive"
                                       data-id="{{$charge->id}}"
                                       class=" text-danger mr-1 change-status-record {{($charge->is_active == '1') ? '' : 'd-none'}}"
                                       title="Suspend Record"><i class="fas fa-exchange-alt"></i></a>

                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-charge')}}"
                                       data-status='1'
                                       data-label="active"
                                       data-id="{{$charge->id}}"
                                       class="text-success mr-1 change-status-record {{($charge->is_active == '0') ? '' : 'd-none'}}"
                                       title="Active Record"><i class="fas fa-exchange-alt"></i></a>


                                    <a href="javascript:void(0)" data-url="{{route('delete-charge')}}"
                                       data-id="{{$charge->id}}"
                                       class=" text-danger mr-1 delete-record  "
                                       title="Delete Record"><i class="fas fa-trash"></i></a>

                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <!-- /basic datatable -->
    </div>
    <!-- /content area -->

    @include('backend.includes.modal')

@endsection

@push('script')
    <script src=" {{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>
    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>

@endpush
