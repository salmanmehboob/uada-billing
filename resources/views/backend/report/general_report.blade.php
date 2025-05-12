@extends('layouts.app')

@section('content')

    <!-- Page header -->
    <div class="page-header page-header-light">
        <div class="page-header-content header-elements-md-inline">
            <div class="page-title d-flex">
                <h4><span class="font-weight-semibold">{{$title}}</span>
                </h4>
                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>

            </div>


        </div>

        <div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
            <div class="d-flex">
                <div class="breadcrumb">
                    <a href="{{route('dashboard')}}" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home </a>
                    <span class="breadcrumb-item active">{{$title}}</span>
                </div>

                <a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
            </div>


        </div>
    </div>
    <!-- /page header -->


    <!-- Content area -->
    <div class="content">

        <!-- Basic datatable -->
        <div class="card">
            <div class="card-header header-elements-inline">
                <h5 class="card-title"></h5>
                <div class="header-elements">

                </div>
            </div>
            @include('backend.message')
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-4">
                        <label for="size_id" class="col-form-label  ">Plot Size </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select name="size_id" id="size_id"
                                    class="form-control select-search-all "
                                    data-fouc>
                                <option selected value="">All</option>
                                @foreach($size as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">Sector </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select name="sector_id" id="sector_id"
                                    class="form-control select-search-all "
                                    data-fouc>
                                <option selected value="">All</option>
                                @foreach($sector as $row)
                                    <option value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">Select Head </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select data-placeholder="Select Head" required
                                    name="charge_id" id="charge_id"
                                    class="form-control select-search-all"
                                    data-fouc>
                                <option selected value="">All</option>
                                @foreach($charges as $key =>  $row)
                                    <option
                                        value="{{$row->id}}">{{$row->name}}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('charge_id'))
                                <span
                                    class="text-danger">{{ $errors->first('charge_id') }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">Select Bank </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select data-placeholder="Select Bank" required
                                    name="bank_id" id="bank_id"
                                    class="form-control select-search-all"
                                    data-fouc>
                                <option selected value="">All</option>
                                @foreach($bank as $key =>  $row)
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
                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">Financial Year </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select data-placeholder="Select Financial Year" required
                                    name="year" id="year"
                                    class="form-control select-search-all mb-3 "
                                    data-fouc>
                                <option selected value="">All</option>
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

                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">From Month </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select data-placeholder="Select Month" required
                                    name="from_month" id="from_month"
                                    class="form-control select-search-all mb-3 month"
                                    data-fouc>
                                <option selected value="">All</option>
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
                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">To Month </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select data-placeholder="Select Month" required
                                    name="to_month" id="to_month"
                                    class="form-control select-search-all mb-3 month"
                                    data-fouc>
                                <option selected value="">All</option>
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
                    <div class="col-md-3 mb-4">
                        <label class="col-form-label  ">Status </label>
                        <div
                            class="form-group form-group-feedback form-group-feedback-right">
                            <select
                                name="status" id="status"
                                class="form-control select-search-all mb-3"
                                data-fouc>
                                <option selected value="">All</option>
                                <option value="1">Paid</option>
                                <option value="0">Un Paid</option>

                            </select>
                            @if ($errors->has('status'))
                                <span
                                    class="text-danger">{{ $errors->first('status') }}</span>
                            @endif
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table general-report-table" id="generalReport">
                        <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Allotee Name</th>
                            <th>Bank</th>
                            <th>Sector</th>
                            <th>Plot Size</th>
                            <th>Year</th>
                            <th>From Month</th>
                            <th>To Month</th>
                            <th>Issue Date</th>
                            <th>Due Date</th>
                            <th>Total</th>
                            <th>Sub Charges</th>
                            <th>Sub Total</th>
                            <th>Status</th>
                            <th>Generated By</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /basic datatable -->

    </div>
    <!-- /content area -->
@endsection

@push('script')
    <script src="{{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    {{--    <script src="{{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>--}}

    <script
        src="{{asset('assets/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js')}}"></script>
    <script
        src="{{asset('assets/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js')}}"></script>
    <script
        src="{{asset('assets/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js')}}"></script>
    <script
        src="{{asset('assets/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js')}}"></script>
    <script
        src="{{asset('assets/global_assets/js/demo_pages/datatables_extension_buttons_html5.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/loaders/progressbar.min.js')}}"></script>

    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>


    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/touchspin.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_select2.js')}}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            // Call the loader function on page load
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadProcessingBar() {
                $('.table-responsive').block({
                    message: '<i class="icon-spinner9 spinner"></i>',
                    overlayCSS: {
                        backgroundColor: '#fff',
                        opacity: 0.8,
                        cursor: 'wait'
                    },
                    css: {
                        border: 0,
                        padding: 0,
                        backgroundColor: 'none'
                    }
                });
            }

            function hideProcessingBar() {
                window.setTimeout(function () {
                    $('.table-responsive').unblock();
                }, 1000);
            }

            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Search:</span> _INPUT_',
                    searchPlaceholder: 'Search...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                    }
                }
            });

            var table = $('#generalReport').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                responsive: true,
                render: true,
                initComplete: function (settings, json) {
                    hideProcessingBar();
                },
                drawCallback: function (settings) {
                    loadProcessingBar();
                    if (settings.aiDisplay.length > 0) {
                        hideProcessingBar();
                    } else {
                        hideProcessingBar();
                        // loadProcessingBar();
                    }
                    if (settings._iDisplayLength !== settings.oFeatures.bPaginate) {
                        loadProcessingBar();
                    }
                },
                ajax: {
                    url: "{{ route('general-report') }}",
                    // type: "POST", // Set the HTTP method to POST
                    data: function (d) {
                        d.search = $('input[type="search"]').val()
                        d.size_id = $('#size_id').val()
                        d.sector_id = $('#sector_id').val()
                         d.bank_id = $('#bank_id').val()
                        d.year = $('#year').val()
                        d.from_month = $('#from_month').val()
                        d.to_month = $('#to_month').val()
                        d.status = $('#status').val()
                        d.charge_id = $('#charge_id').val()
                    }
                },
                columns: [
                    {data: 'bill_number' },
                    {data: 'allotee_name' },
                    {data: 'bank' },
                    {data: 'sector' },
                    {data: 'plot_size'},
                    {data: 'year'},
                    {data: 'from_month'},
                    {data: 'to_month'},
                    {data: 'issue_date'},
                    {data: 'due_date'},
                    {data: 'total'},
                    {data: 'sub_charges'},
                    {data: 'sub_total'},
                    {data: 'status'},
                    {data: 'generated_by'},
                ],
                buttons: {
                    dom: {
                        button: {
                            className: 'btn btn-light'
                        }
                    },
                    buttons: [
                        'excelHtml5',
                        'csvHtml5',
                    ]
                },
                order: [[0, 'asc']],
                lengthMenu: [[100, 500, 1000, 5000, -1], [100, 500, 1000, 5000, "All"]],
                pageLength: 100 // This will set the default number of rows to display per page to 500

            });


            $('#size_id ,#sector_id ,#type_id, #bank_id ,#year , #from_month ,#to_month,#status,#charge_id').change(function () {
                loadProcessingBar();
                table.draw();

            });
        });
    </script>

@endpush
