<!-- Main sidebar -->
<div class="sidebar sidebar-light sidebar-main sidebar-expand-md test-restriction">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>

        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->


    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user">
            <div class="card-body">
                <div class="media">
                    <div class="mr-3">

                        <img src="{{showImage(Auth()->user()->image,'profile')}}"
                             width="38" height="38" class="rounded-circle" alt="">
                    </div>

                    <div class="media-body">
                        <div class="media-title font-weight-semibold">{{ Auth()->user()->name }}</div>

                    </div>

                    <div class="ml-3 align-self-center">
                        <a href="#" class="text-white"><i class="icon-cog3"></i></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /user menu -->


        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">Main</div>
                    <i class="icon-menu" title="Main"></i></li>

                {{--                @role('Super Admin|Account|Water')--}}
                <li class="nav-item">
                    <a href="{{route('dashboard')}}" class="nav-link ">
                        <i class="icon-home7"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-coin-dollar"></i> <span>Manage Data</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">

                        @can('view-sector')
                            <li class="nav-item">
                                <a href="{{route('show-sector')}}" class="nav-link ">
                                    <i class="icon-user-check"></i>
                                    <span>Sector</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-charges')
                            <li class="nav-item">
                                <a href="{{route('show-charge')}}" class="nav-link ">
                                    <i class="icon-user-check"></i>
                                    <span>Charges</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-size')
                            <li class="nav-item">
                                <a href="{{route('show-size')}}" class="nav-link ">
                                    <i class="icon-user-check"></i>
                                    <span>Plot Size</span>
                                </a>
                            </li>
                        @endcan
                        @can('view-type')
                            <li class="nav-item">
                                <a href="{{route('show-type')}}" class="nav-link ">
                                    <i class="icon-user-check"></i>
                                    <span>Plot Type</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-plotcharges')
                            <li class="nav-item">
                                <a href="{{route('show-plotCharges')}}" class="nav-link ">
                                    <i class="icon-user-check"></i>
                                    <span>Apply Charges</span>
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>

                @can('view-allotee')
                    <li class="nav-item">
                        <a href="{{route('show-allotee')}}" class="nav-link ">
                            <i class="icon-users4"></i>
                            <span>Allotee</span>
                        </a>
                    </li>
                @endcan

                @can('view-bill')
                    <li class="nav-item">
                        <a href="{{route('show-bill')}}" class="nav-link ">
                            <i class="icon-package"></i>
                            <span>Bills</span>
                        </a>
                    </li>
                @endcan
                @can('view-bill')
                    <li class="nav-item">
                        <a href="{{route('add-combine-bill')}}" class="nav-link ">
                            <i class="icon-opt"></i>
                            <span>Combine BIll</span>
                        </a>
                    </li>

{{--                    <li class="nav-item">--}}
{{--                        <a href="{{route('receipt-bill')}}" class="nav-link ">--}}
{{--                            <i class="icon-git-merge"></i>--}}
{{--                            <span>Receipt BIll</span>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a href="{{route('all-receipt-bill')}}" class="nav-link ">
                            <i class="icon-git-merge"></i>
                            <span>Receipt BIll</span>
                        </a>
                    </li>
                @endcan

{{--                <li class="nav-item nav-item-submenu">--}}
{{--                    <a href="#" class="nav-link"><i class="icon-coin-dollar"></i> <span>Billing</span></a>--}}

{{--                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">--}}

{{--                        <li class="nav-item nav-item-submenu">--}}
{{--                            <a href="#" class="nav-link"><i class="icon-vimeo"></i> Single BIll</a>--}}
{{--                            <ul class="nav nav-group-sub" style="display: none;">--}}
{{--                                @can('view-bill')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill')}}" class="nav-link"><i--}}
{{--                                                class="icon-html5"></i> Time Period Bills</a></li>--}}
{{--                                @endcan--}}
{{--                                @can('view-bill-non-period')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill-non-period')}}" class="nav-link"><i--}}
{{--                                                class="icon-css3"></i> Non Time Period--}}

{{--                                        </a></li>--}}
{{--                                @endcan--}}
{{--                                @can('view-bill-violation')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill-violation')}}" class="nav-link"><i--}}
{{--                                                class="icon-css3"></i> Violation Bill--}}

{{--                                        </a></li>--}}
{{--                                @endcan--}}
{{--                                @can('view-bill-non-user')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill-non-user')}}" class="nav-link"><i--}}
{{--                                                class="icon-css3"></i> Non User Bill--}}

{{--                                        </a></li>--}}
{{--                                @endcan--}}
{{--                                @can('view-bill-stamp-duty')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill-stamp-duty')}}" class="nav-link"><i--}}
{{--                                                class="icon-css3"></i> Stamp Duty Bill--}}

{{--                                        </a></li>--}}
{{--                                @endcan--}}
{{--                                @can('view-bill-yearly')--}}
{{--                                    <li class="nav-item"><a href="{{route('show-bill-yearly')}}" class="nav-link"><i--}}
{{--                                                class="icon-css3"></i> Yearly Bill--}}

{{--                                        </a></li>--}}
{{--                                @endcan--}}
{{--                            </ul>--}}
{{--                        </li>--}}
{{--                        @can('view-bill')--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('add-combine-bill')}}" class="nav-link ">--}}
{{--                                    <i class="icon-opt"></i>--}}
{{--                                    <span>Combine BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('receipt-bill')}}" class="nav-link ">--}}
{{--                                    <i class="icon-git-merge"></i>--}}
{{--                                    <span>Receipt BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
{{--                        @can('view-bill-violation')--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('receipt-bill-violation')}}" class="nav-link ">--}}
{{--                                    <i class="icon-git-merge"></i>--}}
{{--                                    <span>Receipt Violation BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}
{{--                        @can('view-bill-non-user')--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('receipt-bill-non-user')}}" class="nav-link ">--}}
{{--                                    <i class="icon-git-merge"></i>--}}
{{--                                    <span>Receipt Non User BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}

{{--                        @can('view-bill-stamp-duty')--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('receipt-bill-stamp-duty')}}" class="nav-link ">--}}
{{--                                    <i class="icon-git-merge"></i>--}}
{{--                                    <span>Receipt Stamp Duty BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}

{{--                        @can('view-bill-yearly')--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{route('receipt-bill-yearly')}}" class="nav-link ">--}}
{{--                                    <i class="icon-git-merge"></i>--}}
{{--                                    <span>Receipt Yearly BIll</span>--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        @endcan--}}

{{--                    </ul>--}}

{{--                </li>--}}

                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-gear"></i> <span>Reports</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        @can('view-report')
                            <li class="nav-item">
                                <a href="{{route('general-report')}}" class="nav-link ">
                                    <i class="icon-loop3"></i>
                                    <span>General Report</span>
                                </a>
                            </li>
                        @endcan


                    </ul>

                </li>
                <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-gear"></i> <span>Administrator</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Layouts">
                        @can('view-roles')
                            <li class="nav-item">
                                <a href="{{route('show-role')}}" class="nav-link ">
                                    <i class="icon-loop3"></i>
                                    <span>Role</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-users')
                            <li class="nav-item">
                                <a href="{{route('show-user')}}" class="nav-link ">
                                    <i class="icon-user"></i>
                                    <span>User</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-bank')
                            <li class="nav-item">
                                <a href="{{route('show-bank')}}" class="nav-link ">
                                    <i class="icon-coin-dollar"></i>
                                    <span>Bank Detail</span>
                                </a>
                            </li>
                        @endcan

                        @can('view-setting')
                            <li class="nav-item">
                                <a href="{{route('settings')}}" class="nav-link ">
                                    <i class="icon-gear"></i>
                                    <span>Setup</span>
                                </a>
                            </li>
                        @endcan

                    </ul>

                </li>

                {{--                @endrole--}}
            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->

</div>
<!-- /main sidebar -->
