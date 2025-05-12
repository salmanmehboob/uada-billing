@extends('layouts.app')

@section('content')

    <!--**********************************
            Content body start
        ***********************************-->

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4>  {{$title}} </h4>
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

        <!-- Form validation -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"></h5>
                <div class="header-elements">
                    <div class="list-icons">
                        <a class="list-icons-item" data-action="collapse"></a>
                        <a class="list-icons-item" data-action="reload"></a>
                    </div>
                </div>
            </div>
            <form class="flex-fill form-validate-jquery" action="{{route('change-password', $userID)}}" method="post">
                {{ csrf_field() }}
                @method('PUT')
                @include('backend.message ')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="oldpassword"> Current  Password
                                        <span class="text-danger">*</span>
                                    </label>

                                    <div class="col-lg-6">
                                        <input type="password" required class="form-control" id="oldpassword" name="oldpassword" placeholder="Old Password">
                                    </div>
                                    @if ($errors->has('email'))
                                        <span class="text-danger">{{ $errors->first('email') }}</span>
                                    @endif
                                </div>
                                <div class="form-group row">
                                    <label class="col-lg-4 col-form-label" for="password"> New Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="password" required class="form-control" id="password" name="password" placeholder="New Password">
                                    </div>
                                </div>
                                <div class=" form-group row">
                                    <label class="col-lg-4 col-form-label" for="repeat_password"> Confirm Password
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="col-lg-6">
                                        <input type="password" required class="form-control" id="repeat_password" name="repeat_password" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i
                                                    class="icon-plus3"></i></b> Change
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </div>
        <!-- /form validation -->

    </div>
    <!-- /content area -->

    <!--**********************************
        Content body end
    ***********************************-->


@endsection

@push('script')
    <script src="{{asset('assets/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
<script src="{{ asset('assets/global_assets/js/demo_pages/form_validation.js') }}" type="text/javascript"></script>
@endpush
