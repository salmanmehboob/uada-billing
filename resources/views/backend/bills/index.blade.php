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
                    <a href="{{route('add-bill')}}"
                       class="btn bg-primary btn-labeled btn-labeled-left rounded-round mr-2"><b><i
                                class="fas fa-plus"></i></b> Generate {{$title}} </a>

                    <a href="#" id="billDeleteBtn"
                       data-url="{{ route('delete-bill-bulk') }}"
                       class="btn bg-danger-400 btn-labeled btn-labeled-left rounded-round">
                        <b><i class="fas fa-trash-alt"></i></b> Delete
                    </a>
                </div>
            </div>
            <div class="card-body">
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
                </div>
            </div>
            @include('backend.message')

            <table class="table bill-table">
                <thead>
                <tr>
                    <th><input type="checkbox" id="checkAll"></th>
                    <th>Type</th>
                    <th>Consumer ID</th>
                    <th>Bill Number</th>
                    <th>Name</th>
                    <th>Year</th>
                    <th>Bill Duration</th>
                    <th>Total</th>
                    <th>Sub Charges</th>
                    <th>Sub Total</th>
                    <th>Dues</th>
                    <th>Paid</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
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
    <script src="{{asset('assets/global_assets/js/plugins/forms/validation/validate.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/touchspin.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switch.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/switchery.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/styling/uniform.min.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_validation.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/demo_pages/form_select2.js')}}"></script>
    <script src="{{asset('assets/global_assets/js/plugins/forms/inputs/inputmask.js')}}"></script>

    <script src=" {{asset('assets/global_assets/js/plugins/tables/datatables/datatables.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/plugins/forms/selects/select2.min.js')}}"></script>
    <script src=" {{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>
    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            // When the "checkAll" checkbox is clicked
            $("#checkAll").on("click", function () {
                // Check all checkboxes with the "approved" class
                $(".checkBill").prop("checked", this.checked);
            });


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function loadProcessingBar() {
                $('.bill-table').block({
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
                    $('.bill-table').unblock();
                }, 1000);
            }

            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '10%',
                    targets: [2, 3, 4]
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

            var table = $('.bill-table').DataTable({
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
                    url: "{{ route('show-bill') }}",
                    data: function (d) {
                        d.search = $('input[type="search"]').val(),
                            d.sector_id = $('#sector_id').val(),
                            d.size_id = $('#size_id').val()


                    }
                },
                columns: [
                    {data: 'checkBill', orderable: false, searchable: false},
                    {data: 'billType', name: 'billType', orderable: false, searchable: false},
                    {data: 'consumer_id', name: 'consumer_id'},
                    {data: 'bill_number', name: 'bill_number'},
                    {data: 'name', name: 'name'},
                    {data: 'year', name: 'year'},
                    {data: 'duration', name: 'duration'},
                    {data: 'total', name: 'total'},
                    {data: 'sub_charges', name: 'sub_charges'},
                    {data: 'sub_total', name: 'sub_total'},
                    {data: 'due_amount', name: 'due_amount'},
                    {data: 'status', name: 'status'},
                    {data: 'is_active', name: 'is_active'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

            });

            $('#sector_id ,#size_id').change(function () {
                table.draw();
            });

            $('#billDeleteBtn').click(function () {

                var selectedIds = $('input[name^="checkBill"]:checked').map(function () {
                    return $(this).attr('name').match(/\[(\d+)\]/)[1];
                }).get();

                if (selectedIds.length === 0) {
                    // No checkboxes selected, show an alert
                    Swal.fire({
                        icon: 'error',
                        text: 'No checkboxes are selected. Please select at least one to delete a record.',

                        timer: 2000,

                        showConfirmButton: false,

                        onClose: () => {

                            location.reload();

                        }

                    });
                } else {
                    // Confirm deletion
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won\'t be able to revert this!",
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // If confirmed, proceed with deletion
                            $.ajax({
                                type: 'POST',
                                url: $(this).data('url'),
                                data: {
                                    selectedIds: selectedIds
                                },
                                success: function (data) {
                                    Swal.fire({
                                        title: 'Success',
                                        text: data.message,
                                        timer: 2000,
                                        showConfirmButton: false,
                                        onClose: () => {
                                            location.reload();
                                        }
                                    });
                                },
                                error: function (xhr, status, error) {
                                    // Handle any errors that occur during the AJAX request
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error!',
                                        text: 'An error occurred while processing your request. Please try again later.'
                                    });
                                }
                            });
                        }
                    });
                }
            });

        });
    </script>
@endpush
