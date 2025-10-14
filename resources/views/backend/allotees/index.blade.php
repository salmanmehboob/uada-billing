@extends('layouts.app')

@section('content')

    {{-- DataTables Buttons CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">


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
                    @can('create-allotee')
                        <a href="{{route('add-allotee-transfer')}}"
                           class="btn bg-success-400 btn-labeled btn-labeled-left rounded-round mr-2"><b><i
                                        class="fas fa-plus"></i></b> Add Transfer {{$title}} </a>

                        @role('Super Admin|Account|Water')
                        <a href="{{route('add-allotee')}}"
                           class="btn bg-primary-400 btn-labeled btn-labeled-left rounded-round"><b><i
                                        class="fas fa-plus"></i></b> Add {{$title}} </a>
                        @endrole
                    @endcan
                </div>
            </div>
            @include('backend.message')

            {{-- New Filters Section --}}
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="sector_filter">Filter by Sector:</label>
                            <select id="sector_filter" class="form-control select-search">
                                <option value="">All Sectors</option>
                                @foreach($sectors as $sector)
                                    <option value="{{ $sector->id }}">{{ $sector->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="plot_size_filter">Filter by Plot Size:</label>
                            <select id="plot_size_filter" class="form-control select-search">
                                <option value="">All Plot Sizes</option>
                                @foreach($plotSizes as $size)
                                    <option value="{{ $size->id }}">{{ $size->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            {{-- End New Filters Section --}}

            <table class="table allotee-table">
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Name</th>
                    <th>Plot No</th>
                    <th>Sector</th>
                    <th>Plot Size</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <!-- /basic datatable -->

    </div>
    <!-- /content area -->
@endsection

@push('script')
    <script src=" {{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    {{-- DataTables Buttons JS --}}
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.colVis.min.js"></script>


    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            // Initialize select2 for better dropdowns
            $('.select-search').select2();

            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '10%',
                    targets: [ 2, 3, 4, 7 ] // Added 'Actions' column index (7) to disable sorting
                }],
                dom: '<"datatable-header"flB><"datatable-scroll"t><"datatable-footer"ip>', // Added B for buttons
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
                },
                buttons: { // Define the buttons
                    dom: {
                        button: {
                            className: 'btn btn-light' // Style your buttons
                        }
                    },
                    buttons: [
                        {
                            extend: 'copy',
                            text: 'Copy',
                            className: 'btn btn-outline-secondary'
                        },
                        {
                            extend: 'csv',
                            text: 'CSV',
                            className: 'btn btn-outline-secondary'
                        },
                        {
                            extend: 'excel',
                            text: 'Excel',
                            className: 'btn btn-outline-secondary'
                        },
                        {
                            extend: 'pdf',
                            text: 'PDF',
                            className: 'btn btn-outline-secondary'
                        },
                        {
                            extend: 'print',
                            text: 'Print',
                            className: 'btn btn-outline-secondary'
                        },
                        {
                            extend: 'colvis',
                            text: 'Column visibility',
                            className: 'btn btn-outline-secondary'
                        }
                    ]
                }
            });

            var table = $('.allotee-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('show-allotee') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val();
                        d.sector_id = $('#sector_filter').val();
                        d.plot_size_id = $('#plot_size_filter').val();
                    }
                },
                columns: [
                    {data: 'code', name: 'code', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'plot_no', name: 'plot_no'},
                    {data: 'sector', name: 'sector.name'},
                    {data: 'plot_size', name: 'size.name'},
                    {data: 'type', name: 'type.name'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                // Add these for export to exclude 'Actions' column
                buttons: [

                    {
                        extend: 'csvHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'pdfHtml5',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                        }
                    },
                  
                ]
            });

            // Re-draw the table when a filter changes
            $('#sector_filter, #plot_size_filter').on('change', function() {
                table.draw();
            });
        });
    </script>

@endpush