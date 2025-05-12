@extends('layouts.app')

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
                    <a href="{{route('show-bill-stamp-duty')}}" class="breadcrumb-item">Bills</a>
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
            <form action="{{route('update-bill-stamp-duty', $bill->id)}}" method="post"
                  name="bill_registration" class="flex-fill form-validate-jquery">
                @csrf
                {{--                @method('PUT')--}}
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Bill Generation Setup</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="col-form-label  ">Select Allotee <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Allotee" required
                                                    name="allotee_id" id="allotee_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($allotees as $key =>  $row)
                                                    <option
                                                        {{($bill->allotee_id == $row->id) ? 'selected' : ''}}
                                                        value="{{$row->id}}">
                                                        {{ $row->name ?? '' }}
                                                        {{ $row->plot_no ?? '' }}
                                                        {{ $row->size->name ?? '' }}
                                                        {{ $row->sector->name ?? '' }}

                                                    </option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('allotee_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('allotee_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="col-form-label  ">Select Bank <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Bank" required
                                                    name="bank_id" id="bank_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($banks as $key =>  $row)
                                                    <option
                                                        {{($bill->bank_id == $row->id) ? 'selected' : ''}}

                                                        value="{{$row->id}}">{{$row->name .' '. $row->branch.' ' . $row->account_no}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bank_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('bank_id') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                </div>


                                <div class="" id="charges">
                                    <div class="mb-3 mt-3">
                                        <h1 class="mb-0">Generate Charges to Allotee</h1>
                                    </div>
                                    <div id="row-container">
                                        @foreach($bill->billCharges as $charges)
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="from_month[]"
                                                                class="form-control select mb-3 month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->from_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('from_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">From Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select From Year" required
                                                                name="from_year[]"
                                                                class="form-control select mb-3 "
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->from_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('from_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('from_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Month<span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select Month" required
                                                                name="to_month[]"
                                                                class="form-control select mb-3 month"
                                                                data-fouc>
                                                            <option></option>
                                                            @foreach($months as $key =>  $row)
                                                                <option
                                                                    {{($charges->to_month == $row->id) ? 'selected' : ''}}
                                                                    value="{{$row->id}}">{{$row->name}}</option>
                                                            @endforeach
                                                        </select>
                                                        @if ($errors->has('to_month'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_month') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">To Year <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <select data-placeholder="Select To Year" required
                                                                name="to_year[]"
                                                                class="form-control select mb-3 "
                                                                data-fouc>
                                                            <option></option>

                                                            @foreach ($years  as $row)
                                                                <option
                                                                    {{($charges->to_year == $row) ? 'selected' : ''}} value="{{ $row }}">{{ $row }}</option>
                                                            @endforeach

                                                        </select>
                                                        @if ($errors->has('to_year'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('to_year') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">No of Transfer <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <input type="text" class="form-control" name="no_of_transfer[]"
                                                               value="{{$charges->no_of_transfer}}">
                                                        @if ($errors->has('no_of_transfer'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('no_of_transfer') }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="col-form-label  ">Rate Per Marla <span
                                                            class="text-danger">*</span> </label>
                                                    <div
                                                        class="form-group form-group-feedback form-group-feedback-right">
                                                        <input type="text" class="form-control" name="charge_amount[]"
                                                               value="{{$charges->amount}}">
                                                        @if ($errors->has('charge_amount'))
                                                            <span
                                                                class="text-danger">{{ $errors->first('charge_amount') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="col-md-12  ">
                                        <button type="button" id="add-more"
                                                title="Add More" class="btn btn-primary float-right"><i
                                                class="fas fa-plus"></i></button>
                                    </div>

                                </div>
                                <div class="row">

                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Issue Date </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
                                                <input type="text" name="issue_date"
                                                       class="form-control pickadate-year"
                                                       value="{{showDatePicker($bill->issue_date)}}">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Due Date </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
                                                <input type="text" name="due_date"
                                                       class="form-control pickadate-year-future"
                                                       value="{{showDatePicker($bill->due_date)}}">
                                            </div>
                                        </div>
                                    </div>


                                </div>


                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="submitBtn"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-database-edit2"></i></b> Update Bill
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
    <script src="{{asset('assets/global_assets/js/plugins/pickers/pickadate/legacy.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/picker_date.js')}}"></script>

    <script src="{{asset('assets/global_assets/js/demo_pages/form_checkboxes_radios.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            function loadSelect() {
                $('.select').select2();
            }

            // Click event for the "Add More" button
            $('#add-more').on('click', function () {
                var monthOptions = @json($months);
                var yearOptions = @json($years);

                var fromMonthDropdown = generateMonthDropdown("from_month[]", monthOptions);
                var toMonthDropdown = generateMonthDropdown("to_month[]", monthOptions);
                var fromYearDropdown = generateYearDropdown("from_year[]", yearOptions);
                var toYearDropdown = generateYearDropdown("to_year[]", yearOptions);

                function generateMonthDropdown(inputName, monthOptions) {
                    var monthDropdownHtml = '<select data-placeholder="Select Month" required name="' + inputName + '" class="form-control select mb-3 month" data-fouc>' +
                        '<option></option>';
                    for (var i = 0; i < monthOptions.length; i++) {
                        monthDropdownHtml += '<option value="' + monthOptions[i].id + '">' + monthOptions[i].name + '</option>';
                    }
                    monthDropdownHtml += '</select>';
                    return monthDropdownHtml;
                }

                function generateYearDropdown(inputName, yearOptions) {

                    var yearDropdownHtml = '<select data-placeholder="Select Year" required name="' + inputName + '" class="form-control select mb-3 year" data-fouc>' +
                        '<option></option>';

                    for (var i = 0; i < yearOptions.length; i++) {
                        yearDropdownHtml += '<option value="' + yearOptions[i] + '">' + yearOptions[i] + '</option>';
                    }

                    yearDropdownHtml += '</select>';

                    return yearDropdownHtml;
                }

                var newHtml = '<div class="row">' +
                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">From Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    fromYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label">To Month<span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toMonthDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">To Year <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    toYearDropdown +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">No of Transfer <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    '<input type="text" class="form-control" name="no_of_transfer[]">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-2">' +
                    '<label class="col-form-label  ">Rate Per Marla <span class="text-danger">*</span> </label>' +
                    '<div class="form-group form-group-feedback form-group-feedback-right">' +
                    '<input type="text" class="form-control" name="charge_amount[]">' +
                    '</div>' +
                    '</div>' +

                    '<div class="col-md-12">' +
                    '<label class="col-form-label  "></label>' +
                    '<button type="button"   title="Delete Record" class="btn btn-danger remove-row float-right mb-2" style="margin-top: 36px"><i class="fas fa-trash"></i></button>' +
                    '</div>' +

                    '</div>';
                $('#row-container').append(newHtml);
                loadSelect();
            });


            // Click event for the "Remove" button
            $('#row-container').on('click', '.remove-row', function () {
                if ($('#row-container .row').length > 1) {
                    $(this).parent().parent().remove();
                }
            });


        });
    </script>
@endpush
