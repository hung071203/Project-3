{{--<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">--}}
{{--    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">--}}
{{--        <a class="navbar-brand brand-logo" href="{{ url('/') }}">--}}
{{--            <img src="{{ url('assets/images/download.png') }}" alt="logo" /> </a>--}}
{{--    </div>--}}
{{--    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">--}}

{{--        <ul class="navbar-nav navbar-nav-left header-links">--}}

{{--            <li class="nav-item d-none d-xl-flex">--}}
{{--                <a href="{{ route('division.index') }}" class="nav-link"> Teacher Schedule <span class="badge badge-primary ml-1">New</span>--}}
{{--                </a>--}}
{{--            </li>--}}
{{--            <li class="nav-item active d-none d-lg-flex">--}}
{{--                <a href="{{route('report.receive')}}" class="nav-link">--}}
{{--                    <i class="mdi mdi-account-badge"></i>Student Reports</a>--}}
{{--            </li>--}}
{{--            <li class="nav-item d-none d-md-flex">--}}
{{--                <a href="{{ route('admin.logout') }}" class="nav-link">--}}
{{--                    <i class="mdi mdi-logout"></i>Logout</a>--}}
{{--            </li>--}}

{{--        </ul>--}}
{{--        <ul class="navbar-nav navbar-nav-right">--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">--}}
{{--                    <i class="mdi mdi-file-outline"></i>--}}
{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">--}}
{{--                    <a class="dropdown-item py-3">--}}
{{--                        <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>--}}
{{--                        <span class="badge badge-pill badge-primary float-right">View all</span>--}}
{{--                    </a>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="{{ url('assets/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic"> </div>--}}
{{--                        <div class="preview-item-content flex-grow py-2">--}}
{{--                            <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>--}}
{{--                            <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="{{ url('assets/images/faces/face12.jpg') }}" alt="image" class="img-sm profile-pic"> </div>--}}
{{--                        <div class="preview-item-content flex-grow py-2">--}}
{{--                            <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>--}}
{{--                            <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <img src="{{ url('assets/images/faces/face3.jpg') }}" alt="image" class="img-sm profile-pic"> </div>--}}
{{--                        <div class="preview-item-content flex-grow py-2">--}}
{{--                            <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>--}}
{{--                            <p class="font-weight-light small-text"> The meeting is cancelled </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="nav-item dropdown">--}}
{{--                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">--}}
{{--                    <i class="mdi mdi-bell-outline"></i>--}}

{{--                </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">--}}
{{--                    <a class="dropdown-item py-3 border-bottom">--}}
{{--                        <p class="mb-0 font-weight-medium float-left">4 new notifications </p>--}}
{{--                        <span class="badge badge-pill badge-primary float-right">View all</span>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item py-3">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <i class="mdi mdi-alert m-auto text-primary"></i>--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content">--}}
{{--                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>--}}
{{--                            <p class="font-weight-light small-text mb-0"> Just now </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item py-3">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <i class="mdi mdi-settings m-auto text-primary"></i>--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content">--}}
{{--                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>--}}
{{--                            <p class="font-weight-light small-text mb-0"> Private message </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item preview-item py-3">--}}
{{--                        <div class="preview-thumbnail">--}}
{{--                            <i class="mdi mdi-airballoon m-auto text-primary"></i>--}}
{{--                        </div>--}}
{{--                        <div class="preview-item-content">--}}
{{--                            <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>--}}
{{--                            <p class="font-weight-light small-text mb-0"> 2 days ago </p>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--            <li class="nav-item dropdown d-none d-xl-inline-block">--}}
{{--                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">--}}
{{--                    <span class="profile-text d-none d-md-inline-flex">--}}
{{--                        @if(Auth::guard('admin')->check())--}}
{{--                            <p class="username">{{ Auth::guard('admin')->user()->username }}</p>--}}
{{--                        @endif--}}
{{--                    </span>--}}
{{--                    <img class="img-xs rounded-circle" src="{{ url('assets/images/faces/avt.jpg') }}" alt="Profile image"> </a>--}}
{{--                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">--}}
{{--                    <a class="dropdown-item p-0">--}}
{{--                        <div class="d-flex border-bottom w-100 justify-content-center">--}}
{{--                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">--}}
{{--                                <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>--}}
{{--                            </div>--}}
{{--                            <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">--}}
{{--                                <i class="mdi mdi-account-outline mr-0 text-gray"></i>--}}
{{--                            </div>--}}
{{--                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">--}}
{{--                                <i class="mdi mdi-alarm-check mr-0 text-gray"></i>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </a>--}}
{{--                    <a class="dropdown-item mt-2"> Manage Accounts </a>--}}
{{--                    <a class="dropdown-item"> Change Password </a>--}}
{{--                    <a class="dropdown-item"> Check Inbox </a>--}}
{{--                    <a class="dropdown-item"> Sign Out </a>--}}
{{--                </div>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">--}}
{{--            <span class="mdi mdi-menu icon-menu"></span>--}}
{{--        </button>--}}
{{--    </div>--}}
{{--</nav>--}}
<nav class="navbar default-layout col-lg-12 col-12 p-0 fixed-top d-flex flex-row">

    <div class="text-center navbar-brand-wrapper d-flex align-items-top justify-content-center">
        <a class="navbar-brand brand-logo" href="{{ url('/') }}">
            <img src="{{ url('assets/images/download.png') }}" alt="logo" /> </a>
    </div>
    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">

        <ul class="navbar-nav navbar-nav-left header-links">

{{--            <li class="nav-item active d-none d-lg-flex">--}}
{{--                <a href="{{route('report.receive')}}" class="nav-link">--}}
{{--                    <i class="mdi mdi-elevation-rise"></i>Student Reports</a>--}}
{{--            </li>--}}
{{--            <li class="nav-item d-none d-md-flex">--}}
{{--                <a href="{{ route('admin.logout') }}" class="nav-link">--}}
{{--                    <i class="mdi mdi-logout"></i>Logout</a>--}}
{{--            </li>--}}

        </ul>

        <ul class="navbar-nav navbar-nav-right">
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="messageDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <i class="mdi mdi-file-outline"></i>
                    <span class="count">7</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="messageDropdown">
                    <a class="dropdown-item py-3">
                        <p class="mb-0 font-weight-medium float-left">You have 7 unread mails </p>
                        <span class="badge badge-pill badge-primary float-right">View all</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ url('assets/images/faces/face10.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Marian Garner </p>
                            <p class="font-weight-light small-text"> The meeting is cancelled </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ url('assets/images/faces/face12.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">David Grey </p>
                            <p class="font-weight-light small-text"> The meeting is cancelled </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <img src="{{ url('assets/images/faces/face3.jpg') }}" alt="image" class="img-sm profile-pic"> </div>
                        <div class="preview-item-content flex-grow py-2">
                            <p class="preview-subject ellipsis font-weight-medium text-dark">Travis Jenkins </p>
                            <p class="font-weight-light small-text"> The meeting is cancelled </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link count-indicator dropdown-toggle" id="notificationDropdown" href="#" data-toggle="dropdown">
                    <i class="mdi mdi-bell-outline"></i>
                    <span class="count bg-success">4</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list pb-0" aria-labelledby="notificationDropdown">
                    <a class="dropdown-item py-3 border-bottom">
                        <p class="mb-0 font-weight-medium float-left">4 new notifications </p>
                        <span class="badge badge-pill badge-primary float-right">View all</span>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-alert m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Application Error</h6>
                            <p class="font-weight-light small-text mb-0"> Just now </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-settings m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">Settings</h6>
                            <p class="font-weight-light small-text mb-0"> Private message </p>
                        </div>
                    </a>
                    <a class="dropdown-item preview-item py-3">
                        <div class="preview-thumbnail">
                            <i class="mdi mdi-airballoon m-auto text-primary"></i>
                        </div>
                        <div class="preview-item-content">
                            <h6 class="preview-subject font-weight-normal text-dark mb-1">New user registration</h6>
                            <p class="font-weight-light small-text mb-0"> 2 days ago </p>
                        </div>
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a href="#" id="modeToggle" class="nav-link">
                    <i id="modeIcon" class="mdi mdi-weather-sunset"></i> <!-- Biểu tượng chế độ ban đêm -->
                </a>
            </li>
            <li class="nav-item dropdown d-none d-xl-inline-block">
                <a class="nav-link dropdown-toggle" id="UserDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
                    <span class="profile-text d-none d-md-inline-flex">
                        @if(Auth::guard('admin')->check())
                            <p class="username">{{ Auth::guard('admin')->user()->username }}</p>
                        @elseif(Auth::guard('student')->check())
                            <p class="student_name">{{ Auth::guard('student')->user()->student_name }}</p>
                        @elseif(Auth::guard('teacher')->check())
                            <p class="teacher_name">{{ Auth::guard('teacher')->user()->teacher_name }}</p>
                        @endif
                    </span>
                    <img class="img-xs rounded-circle" src="{{ url('assets/images/faces/avt.jpg') }}" alt="Profile image"> </a>
                <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="UserDropdown">
                    <a class="dropdown-item p-0">
                        <div class="d-flex border-bottom w-100 justify-content-center">
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-bookmark-plus-outline mr-0 text-gray"></i>
                            </div>
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center border-left border-right">
                                <i class="mdi mdi-account-outline mr-0 text-gray"></i>
                            </div>
                            <div class="py-3 px-4 d-flex align-items-center justify-content-center">
                                <i class="mdi mdi-alarm-check mr-0 text-gray"></i>
                            </div>
                        </div>
                    </a>
                    <a class="dropdown-item mt-2" href="{{ route('profile') }}"> Quản lý tài khoản </a>
                    <a class="dropdown-item" href="{{ route('changePass') }}"> Đổi mật khẩu </a>

                    @if(Auth::guard('admin')->check())
                        <a href="{{ route('admin.logout') }}" class="dropdown-item nav-link; mdi mdi-logout mr-2">
                            Đăng xuất
                        </a>
                    @elseif(Auth::guard('student')->check())
                        <a href="{{ route('student.logout') }}" class="dropdown-item nav-link; mdi mdi-logout mr-2">
                            Đăng xuất
                        </a>
                    @elseif(Auth::guard('teacher')->check())
                        <a href="{{ route('teacher.logout') }}" class="dropdown-item nav-link; mdi mdi-logout mr-2">
                            Đăng xuất
                        </a>
                    @endif
                </div>
            </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-menu icon-menu"></span>
        </button>
    </div>
</nav>


