@extends('layouts.login')

@section('content')
    <!-- Page content -->
    <div class="page-content">

        <!-- Main content -->
        <div class="MainDiv content-wrapper">

{{--            <div class="HeaderofES">--}}
{{--                <img src="{{asset('assets/front_end/img/logo.png')}}" alt="...">--}}

{{--            </div>--}}
{{--            <div class="BINSImageDiv">--}}
{{--                <img src="{{asset('assets/front_end/img/BINSImage.png')}}" alt="...">--}}
{{--                <p class="MANAGMENTPORTALText">MANAGEMENT PORTAL</p>--}}
{{--            </div>--}}

            <!-- Content area -->
            <div class="content d-flex justify-content-center align-items-center">
                <!-- Login form -->
                <form class="LoginBox  login-form form-validate" method="post" action="{{route('login')}}">
                    @csrf

                    <div class="card mb-0 LoginBoxCard">

                        <div class="card-body LoginBoxCardBody">
                            <div class="text-center mb-3">
                                <i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
                                <h5 class="mb-0">Login to your account</h5>
                                <span class="d-block text-muted">Enter your credentials below</span>

                            </div>
                          @php
                          $subdomain = explode('.', request()->getHost())[0];

                           @endphp
                            @if(request()->getHost() == env('APP_DOMAIN'))
                            <p class="text-danger"><b>Note : </b> If You have already registered your account and you can't receive confirmation email.
                                <a href="{{route('show-verification-form')}}">click here </a> to update your email
                            </p>
                            @endif
                            @include('backend.message')

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="email" required name="email" class="form-control" placeholder="Email">
                                <div class="form-control-feedback">
                                    <i class="icon-user text-muted"></i>
                                </div>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>

                            <div class="form-group form-group-feedback form-group-feedback-left">
                                <input type="password" required name="password" class="form-control"
                                       placeholder="***********">
                                <div class="form-control-feedback">
                                    <i class="icon-lock2 text-muted"></i>
                                </div>
                                @if ($errors->has('password'))
                                    <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Sign in <i
                                        class="icon-circle-right2 ml-2"></i></button>
                            </div>
                            {{--                    <div class="form-group">--}}
                            {{--                        <a href="{{url('sign-up')}}" class="btn btn-success btn-block">Sign Up <i class="icon-server ml-2"></i></a>--}}
                            {{--                    </div>--}}

{{--                            <div class="text-center">--}}
{{--                                <a href="{{route('show-sign-up')}}">Don't have an account yet?  Sign Up</a>--}}
{{--                            </div>--}}
{{--                            <div class="text-center">--}}
{{--                                <a href="{{route('ForgetPasswordGet')}}">Forgot Password?</a>--}}
{{--                            </div>--}}

                        </div>
                    </div>
                </form>
                <!-- /login form -->

            </div>
            <!-- /content area -->

            <div class="FooterofES">
{{--                <img src="{{asset('assets/front_end/img/NITB_Logo.png')}}" alt="...">--}}
                <p class="DAndDByNITBText">© 2023 | Designed and Developed by AppFlex Technology</p>

            </div>
        </div>
        <!-- /main content -->

    </div>
    <!-- /page content -->
@endsection
