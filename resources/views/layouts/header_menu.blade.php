<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light test-restriction">

{{--    <img class="header-custom-logo" src="{{asset('assets/front_end/img/nitb-green.png')}}" alt="" >--}}
    <div class="navbar-brand">
        <a href="" class="d-inline-block">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="badge bg-success d-none ml-md-3 mr-md-auto">Online</span>

        <ul class="navbar-nav navbar-expand-sm ml-auto">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    <img src="{{showImage(Auth()->user()->image,'profile')}}"
                         class="rounded-circle mr-2" height="34" alt="">
                    <span>{{Auth()->user()->name}}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                     <a href="{{route('update-password')}}" class="dropdown-item"><i class="icon-user-plus"></i> Change Password</a>

                    <a href="{{ route('logout') }}"  onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();"
                       class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
<!-- /main navbar -->
