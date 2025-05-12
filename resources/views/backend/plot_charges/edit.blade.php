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
                    <a href="{{route('show-plotCharges')}}" class="breadcrumb-item">Plot Charges</a>
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
            <form action="{{route('update-plotCharges', $plotCharges->id)}}" method="post"
                  name="allotee_registration" class="flex-fill form-validate-jquery">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="col-form-label  ">Select Plot Size <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select required class="form-control select-search mb-3" name="size_id" id="size_id">
                                                <option value="">Select Plot Size</option>
                                                @if($sizes)
                                                    @foreach($sizes as $row)
                                                        <option {{($plotCharges->size_id == $row->id) ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('size_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('size_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label  ">Select Charges </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select required class="form-control select-search mb-3" name="charge_id"
                                                    id="charge_id">
                                                <option value="">Select Charges</option>
                                                @if($charges)
                                                    @foreach($charges as $row)
                                                        <option {{($plotCharges->charge_id == $row->id) ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            @if ($errors->has('charge_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('charge_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label  ">Time Period <span
                                                class="text-danger">*</span> </label>
                                        <div class="form-group mb-3 mb-md-2">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input"
                                                           name="is_period" id="custom_radio_inline_yes" value="1"
                                                        {{($plotCharges->is_period == 1 ? 'checked' : '')}}>
                                                    Yes
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input"
                                                           name="is_period" id="custom_radio_inline_no" value="0"
                                                        {{($plotCharges->is_period == 0 ? 'checked' : '')}}>
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                        @if ($errors->has('is_period'))
                                            <span
                                                class="text-danger">{{ $errors->first('is_period') }}</span>
                                        @endif
                                    </div>
                                    <div class="col-md-3">
                                        <label class="col-form-label  ">Is Open <span
                                                class="text-danger">*</span> </label>
                                        <div class="form-group mb-3 mb-md-2">
                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input"
                                                           name="is_open" id="custom_is_open_yes" value="1"
                                                        {{($plotCharges->is_open == 1 ? 'checked' : '')}}>
                                                    Yes
                                                </label>
                                            </div>

                                            <div class="form-check form-check-inline">
                                                <label class="form-check-label">
                                                    <input type="radio" class="form-check-input"
                                                           name="is_open" id="custom_is_open_no" value="0"
                                                        {{($plotCharges->is_open == 0 ? 'checked' : '')}}>
                                                    No
                                                </label>
                                            </div>
                                        </div>
                                        @if ($errors->has('is_open'))
                                            <span
                                                class="text-danger">{{ $errors->first('is_open') }}</span>
                                        @endif
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Charges Type<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select required class="form-control select-search mb-3"
                                                    name="charge_type_id"
                                                    id="charge_type_id">
                                                <option value="">Select Charges Type</option>
                                                @foreach($chargeType as $row)
                                                    <option  {{($plotCharges->charge_type_id == $row->id) ? 'selected' : ''}} value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('charge_type_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('charge_type_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Amount<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input required type="text" class="form-control" name="amount" id="amount"
                                                   aria-describedby="emailHelp"
                                                   value="{{old('amount') ?? $plotCharges->amount}}"
                                                   placeholder="Enter {{$title}} amount ">
                                            @if ($errors->has('amount'))
                                                <span
                                                    class="text-danger">{{ $errors->first('amount') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="col-form-label ">Year<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <input required type="number" class="form-control" name="year" id="year"
                                                   aria-describedby="emailHelp"
                                                   value="{{old('year') ?? $plotCharges->year}}"
                                                   placeholder="Enter {{$title}} year ">
                                        </div>
                                        @if ($errors->has('year'))
                                            <span
                                                class="text-danger">{{ $errors->first('year') }}</span>
                                        @endif
                                    </div>
                                </div>


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


    <script src="{{asset('assets/global_assets/js/demo_pages/picker_date.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_checkboxes_radios.js')}}"></script>


@endpush
