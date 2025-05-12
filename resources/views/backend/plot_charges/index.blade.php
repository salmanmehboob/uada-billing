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

    @include('backend.message')
    <!-- Basic datatable -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"></h5>
                <div class="header-elements">
                    @can('create-plotcharges')
                        <a href="{{route('add-plotCharges')}}"
                           class="btn bg-primary-400 btn-labeled btn-labeled-left rounded-round"><b><i
                                    class="fas fa-plus"></i></b> Add {{$title}} </a>
                    @endcan
                </div>
            </div>

            <table class="table datatable-basic">
                <thead>
                <tr>
                    <th>Size</th>
                    <th>Charges</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Year</th>
                    <th>Time Period</th>
                    <th>Is Open</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($plotCharges as $plotCharge)
                    <tr>
                        <td>{{$plotCharge->size->name}}</td>
                        <td>{{$plotCharge->charge->name}}</td>
                        <td>{{$plotCharge->type->name}}</td>
                        <td>{{$plotCharge->amount}}</td>
                        <td>{{$plotCharge->year}}</td>
                        <td>{{showBoolean($plotCharge->is_period)}}</td>
                        <td>{{showBoolean($plotCharge->is_open)}}</td>
                        <td>
                            <div class="d-flex">
                                @can('edit-plotcharges')
                                    <a title="Edit" href="{{ route('edit-plotCharges', $plotCharge->id) }}"
                                       class="text-primary mr-1"><i
                                            class="fas fa-edit"></i></a>
                                @endcan
                                @can('delete-plotcharges')
                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-plotCharges')}}"
                                       data-status='0' data-label="inactive"
                                       data-id="{{$plotCharge->id}}"
                                       class="text-danger mr-1 change-status-record {{($plotCharge->is_active == '1') ? '' : 'd-none'}}"
                                       title="Suspend Record"><i class="fas fa-exchange-alt"></i></a>

                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-plotCharges')}}"
                                       data-status='1' data-label="active"
                                       data-id="{{$plotCharge->id}}"
                                       class="text-success mr-1 change-status-record {{($plotCharge->is_active == '0') ? '' : 'd-none'}}"
                                       title="Active Record"><i class="fas fa-exchange-alt"></i></a>

                                    <a href="javascript:void(0)" data-url="{{route('delete-plotCharges')}}"
                                       data-id="{{$plotCharge->id}}"
                                       class="text-danger delete-record "
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
