@extends('layouts.app')
@section('content')

    <!-- Main content -->
    <div class="content-wrapper">

        <!-- Page header -->
        <div class="page-header page-header-light">
            <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
                <div class="d-flex">
                    <div class="breadcrumb">
                        <a href="{{route('our-dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> <span
                                class="breadcrumb-item active">Dashboard</span></a>

                    </div>

                    <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
                </div>
            </div>
        </div>
        <!-- /page header -->

        <!-- Content area -->
        <div class="content">

            <div class="row">
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="mr-3 align-self-center">
                                <i class="icon-users icon-3x text-success-400"></i>
                            </div>
                            <div class="media-body text-right">
                                <h5 class="font-weight-semibold mb-0">{{$alloteeCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Allotee</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="mr-3 align-self-center">
                                <i class="icon-enter6 icon-3x text-indigo-400"></i>
                            </div>

                            <div class="media-body text-right">
                                <h5 class="font-weight-semibold mb-0">{{$sectorCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Sectors</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$chargeCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Charges</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-opt icon-3x text-blue-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$sizeCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Plot Sizes</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-safe icon-3x text-brown"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$billCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Bill Generated</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-reply icon-3x text-green"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$paidBillCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Paid Bills</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-stack-plus icon-3x text-grey-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$unpaidBillCount}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Unpaid Bills</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-cross3 icon-3x text-danger"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="card card-body">
                        <div class="media">
                            <div class="media-body">
                                <h5 class="font-weight-semibold mb-0">{{$totalArrears}}</h5>
                                <span class="text-uppercase font-size-sm"> Total Arrears</span>
                            </div>

                            <div class="ml-3 align-self-center">
                                <i class="icon-user-cancel icon-3x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
        <!-- /content area -->
    </div>
@endsection
