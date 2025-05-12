@extends('layouts.login')

@section('content')

    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="MainDiv content-wrapper">

            <div class="HeaderofES">
                <img src="{{asset('assets/front_end/img/logo.png')}}" alt="...">

            </div>
            <div class="BINSImageDiv">
                <img src="{{asset('assets/front_end/img/BINSImage.png')}}" alt="...">
                <p class="MANAGMENTPORTALText">MANAGEMENT PORTAL</p>
            </div>

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">
                <!-- Login form -->

                    <div class="card mb-0  ">
                        <div class="card-body  ">
                            <div class="text-center mb-3">
                                <i class="icon-info22 icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                                <h5 class="mb-0">Please Verify Your Account</h5>
                                <span class="d-block text-muted">Before proceeding, please check your email for a verification link.</span>
                            </div>


                            @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                            @endif

                            {{ __('If you did not receive the email') }},
                            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                @csrf
                                <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                            </form>

                        </div>
                    </div>
                 <!-- /login form -->

            </div>
            <!-- /content area -->

            <div class="FooterofES">
                <img src="{{asset('assets/front_end/img/NITB_Logo.png')}}" alt="...">
                <p class="DAndDByNITBText">© 2023 | Designed and Developed by NITB</p>

            </div>
        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->

@endsection
