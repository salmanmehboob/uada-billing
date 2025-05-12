@extends('layouts.app')

@section('content')


    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">{{$title}}</span>
                </h4>
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

                    <a href="{{route('add-bill-violation')}}"
                       class="btn bg-primary-400 btn-labeled btn-labeled-left rounded-round"><b><i
                                class="fas fa-plus"></i></b> Generate {{$title}} </a>
                </div>
            </div>
            @include('backend.message')

            <table class="table datatable-basic">
                <thead>
                <tr>
                    <th>Bill Number</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Total</th>
                    <th>Sub Charges</th>
                    <th>Sub Total</th>
                    <th>Paid</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($bills as $bill)
                    <tr>
                        <td>{{$bill->bill_number}}</td>
                        <td>{{$bill->allotee->name .' ' . $bill->allotee->plot_no .$bill->sector->name}}</td>
                        <td>{{$bill->year}}</td>
                        <td>{{$bill->total}}</td>
                        <td>{{$bill->sub_charges}}</td>
                        <td>{{$bill->sub_total}}</td>
                        <td>{{showBoolean($bill->is_paid)}}</td>
                        <td>
                            <div class="d-flex">

                                <a title="View" href="{{ route('view-bill-violation', $bill->id) }}"
                                   class="text-success mr-1"><i
                                        class="fas fa-eye"></i></a>
                                @if($bill->is_paid == 0 && $bill->is_active == 1)

                                    <a title="Edit" href="{{ route('edit-bill-violation', $bill->id) }}"
                                       class="text-primary mr-1"><i
                                            class="fas fa-edit"></i></a>
                                @endif


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
@endsection

@push('script')
    <script src=" {{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>
    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>

@endpush
