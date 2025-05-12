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
                    <a href="{{route('show-bill-non-period')}}" class="breadcrumb-item">Bills</a>
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
            <form action="{{route('store-bill-non-period')}}" method="post"
                  name="bill_registration" class="flex-fill form-validate-jquery">
                @csrf
                @include('backend.message')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Bill Generation Setup</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
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
                                                    <option value="{{$row->id}}">{{$row->name . ' ' . $row->plot_no .' ' . $row->size->name . ' ' . $row->sector->name}}</option>

                                                @endforeach
                                            </select>
                                            @if ($errors->has('allotee_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('allotee_id') }}</span>
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
                                                      $currentMonth = date('m');
                                                         if ($currentMonth > 6) {
                                                             $currentYear++;
                                                             $startYear++;
                                                         }
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
                                </div>

                                <div class="mb-3 mt-3">
                                    <h1 class="mb-0">Generate Charges to Allotee</h1>
                                </div>

                                <div class="row">

                                    <div class="col-md-4">
                                        <div class="form-group pt-2" id="checkboxContainer">

                                        </div>
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

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" id="submitBtn"
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

            $('#year').on('select2:select', function (e) {
                e.preventDefault();
                const selectedData = e.params.data;
                const year = selectedData.text;
                const allotee = $('#allotee_id').val();


                if (allotee.length == 0) {
                    alert('Please Select Allotee for plot charges  ');
                    return false;
                }
                $.ajax({
                    url: '{{ route('get-plotCharges-non-period') }}',
                    type: 'POST',
                    cache: false,
                    dataType: 'json',
                    data: {'year': year, 'allotee': allotee},
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
                                class: 'form-check-input-styled charge-checkbox',
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

            $('body').on('change', '.charge-checkbox', function (e) {
                e.preventDefault();
                if (this.checked) {
                    const checkedCheckboxes = $(this).val();
                        const year = $('#year').val();
                        const allotee = $('#allotee_id').val();
                    $.ajax({
                        url: '{{ route('check-duplicate-bill') }}',
                        type: 'POST',
                        cache: false,
                        dataType: 'json',
                        data: {
                            'year': year,
                            'allotee': allotee,
                            'checkedCheckboxes': checkedCheckboxes
                        },
                        success: function (data, status, xhr) {

                            if (data.status == false) {
                                Swal.fire({
                                    title: data.message,
                                    showConfirmButton: true,
                                    onClose: () => {

                                        // $(this).unchecked();
                                    }

                                });


                            }


                        },
                        error: function (jqXhr, textStatus, errorMessage) {
                        }
                    });
                }
            })

        });
    </script>


@endpush
