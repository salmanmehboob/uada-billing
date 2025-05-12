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
                    <a href="{{route('receipt-bill')}}"
                       class="btn bg-primary btn-labeled btn-labeled-left rounded-round mr-2"><b><i
                                class="fas fa-plus"></i></b> Generate {{$title}} </a>

                </div>
            </div>
            @include('backend.message')

            <table class="table bill-transaction-table">
                <thead>
                <tr>
{{--                    <th>Type</th>--}}
                    <th>Bill Number</th>
                    <th>Name</th>
                    <th>Total</th>
                    <th>Paid Amount</th>
                    <th>Dues</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
        <!-- /basic datatable -->

    </div>
    <!-- /content area -->
@endsection

@push('script')
    <script src=" {{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>
    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function loadProcessingBar() {
                $('.bill-transaction-table').block({
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
                    $('.bill-transaction-table').unblock();
                }, 1000);
            }
            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '10%',
                    targets: [ 2, 3, 4]
                }],
                dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Search :</span> _INPUT_',
                    searchPlaceholder: 'Sector/Plot No/Size',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: {
                        'first': 'First',
                        'last': 'Last',
                        'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;',
                        'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;'
                    }
                }
            });

            var table = $('.bill-transaction-table').DataTable({
                processing: true,
                // processingDelay: 1000,
                serverSide: true,
                searching: true,
                responsive: true,
                render: true,
                pageLength: 100, // Set the default number of records per page to 100
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
                    url: "{{ route('all-receipt-bill') }}"
                },
                columns: [
                    // {data: 'billType', name: 'billType', orderable: false, searchable: false},
                    {data: 'bill_number', name: 'bill_number'},
                    {data: 'name', name: 'name'},
                      {data: 'total', name: 'total'},
                    {data: 'paid_amount', name: 'paid_amount'},
                    {data: 'due_amount', name: 'due_amount'},
                    {data: 'payment_date', name: 'payment_date'},
                 ],

            });
        });
    </script>
@endpush
