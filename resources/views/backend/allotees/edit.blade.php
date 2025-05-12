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
                <h4> <span class="font-weight-semibold"></span>{{$title}}
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
            <form action="{{route('update-allotee', $allotee->id)}}" method="post"
                  name="allotee_registration" class="flex-fill form-validate-jquery">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Personal Information</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Name <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" required class="form-control"
                                                   name="name"
                                                   value="{{old('name') ?? $allotee->name}}"
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
                                                   value="{{old('guardian_name') ?? $allotee->guardian_name}}"
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


                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Email </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="email" name="email"
                                                   class="form-control"
                                                   placeholder="Your email"
                                                   value="{{old('email')  ?? $allotee->email}}">
                                            <div class="form-control-feedback">
                                                <i class="icon-mention text-muted"></i>
                                            </div>
                                            @if ($errors->has('email'))
                                                <span
                                                    class="text-danger">{{ $errors->first('email') }}</span>
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
{{--                                                   value="{{old('phone_no')  ?? $allotee->phone_no}}"--}}
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
                                                   value="{{old('plot_no')  ?? $allotee->plot_no}}">
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
                                                    <option {{($row->id == $allotee->sector_id) ? 'selected' : ''}}
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
                                        <label class="col-form-label  ">Select Plot Size <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Plot Size" required
                                                    name="size_id" id="size_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($sizes as $key =>  $row)
                                                    <option {{($row->id == $allotee->size_id) ? 'selected' : ''}}
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
                                        <label class="col-form-label  ">Select Plot Type <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Type Size" required
                                                    name="type_id" id="type_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($types as $key =>  $row)
                                                    <option {{($row->id == $allotee->type_id) ? 'selected' : ''}}
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
{{--                                                   value="{{ old('account_no')  ?? $allotee->account_no}}">--}}

{{--                                            @if ($errors->has('account_no'))--}}
{{--                                                <span--}}
{{--                                                    class="text-danger">{{ $errors->first('account_no') }}</span>--}}
{{--                                            @endif--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Contact Person Name</label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="contact_person_name"
                                                   class="form-control"
                                                   placeholder="Contact Person Name"
                                                   value="{{old('contact_person_name')  ?? $allotee->contact_person_name}}">

                                            @if ($errors->has('contact_person_name'))
                                                <span
                                                    class="text-danger">{{ $errors->first('contact_person_name') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Arrears</label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input type="text" name="arrears"
                                                   class="form-control"
                                                   placeholder="Arrears"
                                                   value="{{old('arrears')  ?? $allotee->arrears}}">

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
                                                            <textarea name="address" rows="10"
                                                                      required
                                                                      placeholder="Address"
                                                                      class="form-control">{{old('address')  ?? $allotee->address}}</textarea>

                                            @if ($errors->has('address'))
                                                <span
                                                    class="text-danger">{{ $errors->first('address') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-plus3"></i></b> Update
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
    <script src="{{asset('assets/global_assets/js/plugins/pickers/daterangepicker.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/anytime.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.date.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/picker.time.js')}}"></script>

    <script src="{{asset('assets/global_assets/js/demo_pages/picker_date.js')}}"></script>

@endpush
