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
                    <a href="{{route('show-bill-violation')}}" class="breadcrumb-item">Bills</a>
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
            <form action="{{route('store-receipt-bill-violation')}}" method="post"
                  name="bill_registration" class="flex-fill form-validate-jquery">
                @csrf
                @include('backend.message')
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="mb-3">
                                    <h1 class="mb-0">Receipt Entry</h1>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="col-form-label  ">Select Bill <span
                                                class="text-danger">*</span> </label>
                                        <div
                                            class="form-group form-group-feedback form-group-feedback-right">
                                            <select data-placeholder="Select Bill" required
                                                    name="bill_id" id="bill_id"
                                                    class="form-control select-search mb-3 "
                                                    data-fouc>
                                                <option></option>
                                                @foreach($bill as $key =>  $row)
                                                    <option
                                                        value="{{$row->id}}">{{$row->bill_number}}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('bill_id'))
                                                <span
                                                    class="text-danger">{{ $errors->first('bill_id') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Amount <span
                                                class="text-danger">*</span></label>
                                        <div class="col-md-12">
                                            <div class="input-group">
                                                <input type="text" required name="total" id="total"
                                                       class="form-control"
                                                       placeholder="Enter Paid Amount"
                                                >
                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-4  ">
                                        <label class="col-form-label col-md-4">Payment Date <span
                                                class="text-danger">*</span></label>
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

                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit"
                                                class="btn bg-teal-400 btn-labeled btn-labeled-right float-right">
                                            <b><i class="icon-database-edit2"></i></b> Update Receipt Entry
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


    <script type="text/javascript">
        $(document).ready(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#bill_id').on('select2:select', function (e) {
                e.preventDefault();
                const selectedData = e.params.data;
                const bill_id = selectedData.id;


                $.ajax({
                    url: '{{ route('get-bill') }}',
                    type: 'GET',
                    cache: false,
                    dataType: 'json',
                    data: {'bill_id': bill_id },
                    success: function (data, status, xhr) {
                        $('#total').val(data.responseData.transaction.total);
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                    }
                });
            });

        });
    </script>


@endpush
