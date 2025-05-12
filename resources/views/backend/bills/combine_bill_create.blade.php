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
                    <a href="{{route('show-bill')}}" class="breadcrumb-item">Bills</a>
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
            <form action="{{route('store-combine-bill')}}" method="post"
                  name="bill_registration" class="flex-fill form-validate-jquery">
                @csrf
                @include('backend.message')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Bill Generation Setup For All </h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Sector <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Allotee" required
                                                    name="sector_id" id="sector_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option value="0">All</option>
                                                @foreach($sector as $key =>  $row)
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
                                        <label class="col-form-label  ">Select Plot Size <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Allotee" required
                                                    name="size_id" id="size_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option value="0">All</option>
                                                @foreach($size as $key =>  $row)
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

                                <div class="row">

                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Financial Year <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Financial Year" required
                                                    name="year" id="year"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @php
                                                    $currentYear = date('Y');
                                                    $startYear = $currentYear - 29;
                                                @endphp
                                                @for ($year = $currentYear; $year >= $startYear; $year--)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor

                                            </select>
                                            @if ($errors->has('year'))
                                                <span
                                                    class="text-danger">{{ $errors->first('year') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">From Month<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Month" required
                                                    name="from_month" id="from_month"
                                                    class="form-control select-search mb-3 month"
                                                    data-fouc>
                                                <option></option>
                                                @foreach($months as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('from_month'))
                                                <span
                                                    class="text-danger">{{ $errors->first('from_month') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">To Month<span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Month" required
                                                    name="to_month" id="to_month"
                                                    class="form-control select-search mb-3 month"
                                                    data-fouc>
                                                <option></option>
                                                @foreach($months as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->name}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('to_month'))
                                                <span
                                                    class="text-danger">{{ $errors->first('to_month') }}</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Issue Date </label>
                                        <div class="col-md-12">
                                            <div class="input-group">
										<span class="input-group-prepend">
											<span class="input-group-text"><i class="icon-calendar22"></i></span>
										</span>
                                                <input type="text" name="issue_date"
                                                       class="form-control pickadate-year"
                                                       value="{{currentDatePicker()}}">
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
                                                       value="{{currentDatePicker()}}">
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="" id="charges">
                                    <div class="mb-3 mt-3">
                                        <h1 class="mb-0">Generate Charges to Allotee</h1>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group pt-2" id="checkboxContainer">

                                                {{--                                                @foreach($charges as $row)--}}
                                                {{--                                                                                                    <div class="form-check form-check-right">--}}
                                                {{--                                                                                                        <label class="form-check-label">--}}
                                                {{--                                                                                                            {{$row->name}}--}}
                                                {{--                                                                                                            <input type="checkbox" name="charges[]" class="form-check-input-styled" value=" {{$row->id}}"--}}
                                                {{--                                                                                                                   data-fouc>--}}
                                                {{--                                                                                                        </label>--}}
                                                {{--                                                                                                    </div>--}}

                                                {{--                                                @endforeach--}}


                                            </div>
                                        </div>

                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-plus3"></i></b> Generate Bill
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

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#year,#size_id').on('select2:select', function (e) {
                e.preventDefault();
                const selectedData = e.params.data;
                // const year = selectedData.text;
                const year = $('#year').val();
                const size_id = $('#size_id').val();

                $.ajax({
                    url: '{{ route('getPlotChargesBySize') }}',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {'year': year, 'size_id': size_id},
                    success: function (data, status, xhr) {
                        const checkboxesData = data;

                        // Clear any existing checkboxes
                        $('#checkboxContainer').empty();

                        // Loop through the checkbox data and create/append checkboxes
                        checkboxesData.forEach(function (checkboxData) {
                            const label = $('<label>').addClass('form-check-label').text(checkboxData.name);

                            const checkbox = $('<input>').attr({
                                type: 'checkbox',
                                name: 'charges[]',
                                class: 'form-check-input-styled',
                                value: checkboxData.id,
                            });

                            const formCheck = $('<div>').addClass('form-check form-check-right')
                                .append(label)
                                .append(checkbox);

                            // Append the checkbox to the container
                            $('#checkboxContainer').append(formCheck);
                            $('.form-check-input-styled').uniform();
                        });

                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                    }
                });
            });


        });
    </script>


@endpush
