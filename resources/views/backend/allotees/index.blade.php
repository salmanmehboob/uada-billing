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
                <tbody>
{{--                @foreach($allotees as $allotee)--}}
{{--                    <tr>--}}
{{--                        <td>{{$allotee->id}}</td>--}}
{{--                        <td>{{$allotee->name}}</td>--}}
{{--                         <td>{{$allotee->plot_no}}</td>--}}
{{--                        <td>{{$allotee->sector->name ?? ''}}</td>--}}
{{--                        <td>{{$allotee->size->name  ?? ''}}</td>--}}
{{--                        <td>{{$allotee->type->name  ?? ''}}</td>--}}
{{--                        <td>{{showStatus($allotee->is_active)}}</td>--}}
{{--                        <td>--}}
{{--                            <div class="d-flex">--}}
{{--                                @can('edit-allotee')--}}
{{--                                    <a title="Edit" href="{{ route('edit-allotee', $allotee->id) }}"--}}
{{--                                       class="text-primary mr-1"><i--}}
{{--                                            class="fas fa-edit"></i></a>--}}
{{--                                @endcan--}}

{{--                                @can('delete-allotee')--}}
{{--                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-allotee')}}"--}}
{{--                                       data-status='0' data-label="inactive"--}}
{{--                                       data-id="{{$allotee->id}}"--}}
{{--                                       class=" text-danger mr-1 change-status-record {{($allotee->is_active == '1') ? '' : 'd-none'}}"--}}
{{--                                       title="Suspend Record"><i class="fas fa-exchange-alt"></i></a>--}}

{{--                                    <a href="javascript:void(0)" data-url="{{route('changeStatus-allotee')}}"--}}
{{--                                       data-status='1' data-label="active"--}}
{{--                                       data-id="{{$allotee->id}}"--}}
{{--                                       class="text-success mr-1 change-status-record {{($allotee->is_active == '0') ? '' : 'd-none'}}"--}}
{{--                                       title="Active Record"><i class="fas fa-exchange-alt"></i></a>--}}
{{--                                @endcan--}}
{{--                            </div>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                @endforeach--}}
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
{{--    <script src=" {{asset('assets/global_assets/js/demo_pages/datatables_basic.js')}}"></script>--}}
    <script src=" {{asset('assets/custom/js/ajax_form.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            $.extend($.fn.dataTable.defaults, {
                autoWidth: false,
                columnDefs: [{
                    orderable: false,
                    width: '10%',
                    targets: [ 2, 3, 4]
                }],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
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

            var table = $('.allotee-table').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                ajax: {
                    url: "{{ route('show-allotee') }}",
                    data: function (d) {
                            d.search = $('input[type="search"]').val()
                    }
                },
                columns: [
                    {data: 'code', name: 'code', orderable: false, searchable: false},
                    {data: 'name', name: 'name'},
                    {data: 'plot_no', name: 'plot_no'},
                    {data: 'sector', name: 'sector'},
                    {data: 'plot_size', name: 'plot_size'},
                    {data: 'type', name: 'type'},
                    {data: 'status', name: 'status'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

            });
        });
    </script>

@endpush
