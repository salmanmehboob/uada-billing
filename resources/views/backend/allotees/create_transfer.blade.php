@extends('layouts.app')
@push('style')
    <link href="{{asset('backend/vendor/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{asset('backend/vendor/bootstrap-select/dist/css/bootstrap-select.min.css')}}" rel="stylesheet">

@endpush
@section('content')
    <!--**********************************
            Content body start
        ***********************************-->

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold"></span>{{$title}}
                </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
                    <a href="/show-allotee" class="breadcrumb-item">Allotees</a>
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

            <!-- Registration form -->
            <form action="{{route('store-allotee-transfer')}}" method="post"
                  name="allotee_registration" class="flex-fill form-validate-jquery">
                @csrf
                @include('backend.message')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Old Allotee Information </h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Name of old Allotee <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" required class="form-control"
                                                   name="name"
                                                   value="{{old('name')}}"
                                                   placeholder="Allotee Name">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('name'))
                                                <span
                                                    class="text-danger">{{ $errors->first('name') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label  ">S/D/W/O <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" required class="form-control"
                                                   name="guardian_name"
                                                   value="{{old('guardian_name')}}"
                                                   placeholder="Guardian Name">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('guardian_name'))
                                                <span
                                                    class="text-danger">{{ $errors->first('guardian_name') }}</span>
                                            @endif
                                        </div>
                                    </div>


{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label  ">Phone No<span--}}
{{--                                                class="text-danger">*</span> </label>--}}
{{--                                        <div--}}
{{--                                            class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                            <input type="text" name="phone_no"--}}
{{--                                                   required data-mask="0399-9999999"--}}
{{--                                                   class="form-control"--}}
{{--                                                   value="{{old('phone_no')}}"--}}
{{--                                                   placeholder="0399-9999999">--}}
{{--                                            @if ($errors->has('phone_no'))--}}
{{--                                                <span--}}
{{--                                                    class="text-danger">{{ $errors->first('phone_no') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-md-4">
                                        <label class="col-form-label ">Plot No<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input required type="text" name="plot_no"
                                                   class="form-control" placeholder="Plot No"
                                                   value="{{old('plot_no')}}">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-lock text-muted"></i>
                                            </div>
                                            @if ($errors->has('plot_no'))
                                                <span
                                                    class="text-danger">{{ $errors->first('plot_no') }}</span>
                                            @endif
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Sector <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Sector" required
                                                    name="sector_id" id="sector_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($sectors as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('sector_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('sector_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label  "> Select Plot Size <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Plot Size" required
                                                    name="size_id" id="size_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($sizes as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('size_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('size_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  "> Select Plot Type <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Plot Type" required
                                                    name="type_id" id="type_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($types as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('type_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label  ">Account No </label>--}}
{{--                                        <div--}}
{{--                                            class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                            <input type="text" name="account_no"--}}
{{--                                                   class="form-control"--}}
{{--                                                   placeholder="Account No"--}}
{{--                                                   value="{{session('account_no') ?? old('account_no')}}">--}}

{{--                                            @if ($errors->has('account_no'))--}}
{{--                                                <span--}}
{{--                                                    class="text-danger">{{ $errors->first('account_no') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label  ">Address<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                                            <textarea name="address" rows="10"
                                                                      required
                                                                      placeholder="Address"
                                                                      class="form-control">{{old('address')}}</textarea>

                                            @if ($errors->has('address'))
                                                <span
                                                    class="text-danger">{{ $errors->first('address') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="mb-3">
                                    <h1 class="mb-0">New Allotee Information </h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Name of New Allotee <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" required class="form-control"
                                                   name="new_name"
                                                   value="{{old('new_name')}}"
                                                   placeholder="New Allotee Name">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('new_name'))
                                                <span
                                                    class="text-danger">{{ $errors->first('new_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">S/D/W/O <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" required class="form-control"
                                                   name="new_guardian_name"
                                                   value="{{old('new_guardian_name')}}"
                                                   placeholder="New Guardian Name">
                                            <div class="form-control-feedback">
                                                <i class="icon-user-check text-muted"></i>
                                            </div>
                                            @if ($errors->has('new_guardian_name'))
                                                <span
                                                    class="text-danger">{{ $errors->first('new_guardian_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label  ">Phone No<span--}}
{{--                                                class="text-danger">*</span> </label>--}}
{{--                                        <div--}}
{{--                                            class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                            <input type="text" name="new_phone_no"--}}
{{--                                                   required data-mask="0399-9999999"--}}
{{--                                                   class="form-control"--}}
{{--                                                   value="{{old('new_phone_no')}}"--}}
{{--                                                   placeholder="0399-9999999">--}}
{{--                                            @if ($errors->has('new_phone_no'))--}}
{{--                                                <span--}}
{{--                                                    class="text-danger">{{ $errors->first('new_phone_no') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-4">--}}
{{--                                        <label class="col-form-label  ">Account No </label>--}}
{{--                                        <div--}}
{{--                                            class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                            <input type="text" name="new_account_no"--}}
{{--                                                   class="form-control"--}}
{{--                                                   placeholder="Account No"--}}
{{--                                                   value="{{session('new_account_no') ?? old('new_account_no')}}">--}}

{{--                                            @if ($errors->has('new_account_no'))--}}
{{--                                                <span--}}
{{--                                                    class="text-danger">{{ $errors->first('new_account_no') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Arrears</label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="arrears"
                                                   class="form-control"
                                                   placeholder="Arrears"
                                                   value="{{session('arrears') ?? old('arrears')}}">

                                            @if ($errors->has('arrears'))
                                                <span
                                                    class="text-danger">{{ $errors->first('arrears') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <label class="col-form-label  ">Address<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                                            <textarea name="new_address" rows="10"
                                                                      required
                                                                      placeholder="Address"
                                                                      class="form-control">{{old('new_address')}}</textarea>

                                            @if ($errors->has('new_address'))
                                                <span
                                                    class="text-danger">{{ $errors->first('new_address') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-plus3"></i></b> Register
                                        </button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <!-- /registration form -->
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
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/touchspin.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_validation.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/inputmask.js')}}"></script>


    <script src="{{asset('assets/global_assets/js/plugins/ui/moment/moment.min.js')}}"></script>

@endpush
