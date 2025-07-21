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
                <form method="GET" action="{{ route('general-report-get') }}">
                    <div class="row">
                        <div class="col-md-3 mb-4">
                            <label for="size_id" class="col-form-label  ">Plot Size </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select name="size_id" id="size_id" class="form-control select-search-all " data-fouc>
                                    <option selected value="">All</option>
                                    @foreach($size as $row)
                                        <option value="{{$row->id}}" {{ request('size_id') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label  ">Sector </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select name="sector_id" id="sector_id" class="form-control select-search-all " data-fouc>
                                    <option selected value="">All</option>
                                    @foreach($sector as $row)
                                        <option value="{{$row->id}}" {{ request('sector_id') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
{{--                        <div class="col-md-3 mb-4">--}}
{{--                            <label class="col-form-label  ">Select Head </label>--}}
{{--                            <div class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                <select data-placeholder="Select Head" name="charge_id" id="charge_id" class="form-control select-search-all" data-fouc>--}}
{{--                                    <option selected value="">All</option>--}}
{{--                                    @foreach($charges as $key =>  $row)--}}
{{--                                        <option value="{{$row->id}}" {{ request('charge_id') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-3 mb-4">--}}
{{--                            <label class="col-form-label  ">Select Bank </label>--}}
{{--                            <div class="form-group form-group-feedback form-group-feedback-right">--}}
{{--                                <select data-placeholder="Select Bank" name="bank_id" id="bank_id" class="form-control select-search-all" data-fouc>--}}
{{--                                    <option selected value="">All</option>--}}
{{--                                    @foreach($bank as $key =>  $row)--}}
{{--                                        <option value="{{$row->id}}" {{ request('bank_id') == $row->id ? 'selected' : '' }}>{{$row->name .' '. $row->branch.' ' . $row->account_no}}</option>--}}
{{--                                    @endforeach--}}
{{--                                </select>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label  ">Financial Year </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select data-placeholder="Select Financial Year" name="year" id="year" class="form-control select-search-all mb-3 " data-fouc>
                                    <option selected value="">All</option>
                                    @php
                                        $currentYear = date('Y');
                                        $startYear = $currentYear - 29;
                                    @endphp
                                    @for ($year = $currentYear; $year >= $startYear; $year--)
                                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label  ">From Month </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select data-placeholder="Select Month" name="from_month" id="from_month" class="form-control select-search-all mb-3 month" data-fouc>
                                    <option selected value="">All</option>
                                    @foreach($months as $key =>  $row)
                                        <option value="{{$row->id}}" {{ request('from_month') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label  ">To Month </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select data-placeholder="Select Month" name="to_month" id="to_month" class="form-control select-search-all mb-3 month" data-fouc>
                                    <option selected value="">All</option>
                                    @foreach($months as $key =>  $row)
                                        <option value="{{$row->id}}" {{ request('to_month') == $row->id ? 'selected' : '' }}>{{$row->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label  ">Status </label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select name="status" id="status" class="form-control select-search-all mb-3" data-fouc>
                                    <option selected value="">All</option>
                                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Paid</option>
                                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Unpaid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label">Amount</label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <input type="number" name="amount" class="form-control" value="{{ request('amount') }}" placeholder="Enter amount">
                            </div>
                        </div>
                        <div class="col-md-3 mb-4">
                            <label class="col-form-label">Amount Filter</label>
                            <div class="form-group form-group-feedback form-group-feedback-right">
                                <select name="amount_filter" class="form-control select-search-all" data-fouc>
                                    <option value="" {{ request('amount_filter') == '' ? 'selected' : '' }}>Select Operator</option>
                                    <option value="=" {{ request('amount_filter') == '=' ? 'selected' : '' }}>Equal</option>
                                    <option value="<" {{ request('amount_filter') == '<' ? 'selected' : '' }}>Less than</option>
                                    <option value=">" {{ request('amount_filter') == '>' ? 'selected' : '' }}>Greater than</option>
                                    <option value="<=" {{ request('amount_filter') == '<=' ? 'selected' : '' }}>Less than or Equal</option>
                                    <option value=">=" {{ request('amount_filter') == '>=' ? 'selected' : '' }}>Greater than or Equal</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12 mb-4">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </div>
                </form>
                @if(isset($bills))
                <div class="table-responsive mt-4">
                    <table class="table table-bordered" id="generalReport">
                        <thead>
                        <tr>
                            <th>Bill No</th>
                            <th>Allotee Name</th>
                            <th>Plot No</th>  
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
{{--                            <th>Generated By</th>--}}
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($bills as $row)
                            <tr>
                                <td><a target="_blank" title="View" href="{{ route('view-bill', $row->id) }}" class="text-info mr-1">{{ $row->bill_number }}</a></td>
                                <td>{{ $row->allotee->name ?? '' }}</td>
                                 <td>{{ $row->allotee->plot_no ?? '' }}</td> 
                                <td>{{ $row->sector->name ?? '' }}</td>
                                <td>{{ $row->size->name ?? '' }}</td>
                                <td>{{ $row->year }}</td>
                                <td>{{ $row->fromMonth->short ?? '' }}</td>
                                <td>{{ $row->toMonth->short ?? '' }}</td>
                                <td>{{ isset($row->issue_date) ? showDate($row->issue_date) : '' }}</td>
                                <td>{{ isset($row->due_date) ? showDate($row->due_date) : '' }}</td>
                                <td>{{ $row->total }}</td>
                                <td>{{ $row->sub_charges }}</td>
                                <td>{{ $row->sub_total }}</td>
                                <td>{{ $row->is_paid == 1 ? 'Paid' : 'Un Paid' }}</td>
{{--                                <td>{{ $row->generatedBy->name ?? '' }}</td>--}}
                            </tr>
                        @empty
                            <tr><td colspan="15" class="text-center">No records found.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

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
                serverSide: false,
                searching: true,
                responsive: true,
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
                lengthMenu: [[50 ,100, 500, 1000, 5000, -1], [50 ,100, 500, 1000, 5000, "All"]],
                pageLength: 50 // This will set the default number of rows to display per page to 500

            });

        });
    </script>

@endpush
