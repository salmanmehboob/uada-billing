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
                <form class="LoginBox login-form form-validate" method="post" action="{{route('ResetPasswordPost')}}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <input type="hidden" name="email" value="{{ session('email') }}">

                    <div class="LoginBoxCard card mb-0">
                        <div class="LoginBoxCardBody card-body">
                            <div class="text-center mb-3">
                                <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                                <h5 class="mb-0">Reset Password</h5>
                                <span class="d-block text-muted">Enter your new password</span>
                            </div>
                            @include('backend.message')

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="password" required name="password"  id="password" class="form-control"
                                       placeholder="***********">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="password" required name="password_confirmation" class="form-control"
                                       placeholder="***********">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                                @if ($errors->has('repeat_password'))
                                    <span class="text-danger">{{ $errors->first('repeat_password') }}</span>
                                @endif
                            </div>



                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Reset Password <i
                                        class="icon-circle-right2 ml-2"></i></button>
                            </div>
                            <div class="text-center">
                                <a href="{{url('/login')}}">Login?</a>
                            </div>
                        </div>
                    </div>
                </form>
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
