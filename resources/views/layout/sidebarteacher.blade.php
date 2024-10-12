<script src="jquery.min.js"></script>
<script src="bootstrap.min.js"></script>
@if(session('teacher'))

@endif
<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{ url('assets/images/faces/avt.jpg') }}" alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        @if(Auth::guard('teacher')->check())
                            <p class="teacher_name">{{ Auth::guard('teacher')->user()->teacher_name }}</p>
                        @endif
                        <div class="dropdown" data-display="static">
                            <a href="#" class="nav-link d-flex user-switch-dropdown-toggler" id="" href="#" data-toggle="dropdown" aria-expanded="">
                                <small class="designation text-muted">Giáo viên</small>
                                <span class="status-indicator online"></span>
                            </a>

                        </div>
                    </div>
                </div>
{{--                <button class="btn btn-success btn-block">New Project <i class="mdi mdi-plus"></i>--}}
{{--                </button>--}}
            </div>
        </li>
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{ route('teacher.transcriptCheck') }}">--}}
{{--                <i class="menu-icon mdi mdi-glass-cocktail"></i>--}}
{{--                <span class="menu-title">Check</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('sy.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-dna"></i>--}}
{{--                <span class="menu-title">School Year Management</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('grade.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-chart-line"></i>--}}
{{--                <span class="menu-title">grade Management</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('teacher.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-emoticon"></i>--}}
{{--                <span class="menu-title">Teacher List</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('subject.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-file-outline"></i>--}}
{{--                <span class="menu-title">Subject Management</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('student.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-table-large"></i>--}}
{{--                <span class="menu-title">Student Management</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('class.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-lock-outline"></i>--}}
{{--                <span class="menu-title">Class Management</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item active ">
            <a class="nav-link" href="{{route('division.show')}}">
                <i class="menu-icon mdi mdi-worker"></i>
                <span class="menu-title">Khối lượng công việc</span>
            </a>
        </li>
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('teacher.profile')}}">--}}
{{--                <i class="menu-icon mdi mdi-account"></i>--}}
{{--                <span class="menu-title">My Profile</span>--}}
{{--            </a>--}}
{{--        </li>--}}
        <li class="nav-item active ">
            <a class="nav-link" href="{{route('transcript.index')}}">
                <i class="menu-icon mdi mdi-calendar-account"></i>
                <span class="menu-title">Quản lý bảng điểm</span>
            </a>
        </li>
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('transdetail.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-account-alert"></i>--}}
{{--                <span class="menu-title">Transcript Detail</span>--}}
{{--            </a>--}}
{{--        </li>--}}
{{--        <li class="nav-item ">--}}
{{--            <a class="nav-link" href="{{route('reexamine.index')}}">--}}
{{--                <i class="menu-icon mdi mdi-alert-box"></i>--}}
{{--                <span class="menu-title">Reexamine</span>--}}
{{--            </a>--}}
{{--        </li>--}}

        {{--    <li class="nav-item">--}}
        {{--      <a class="nav-link" data-toggle="collapse" href="" aria-expanded="" aria-controls="basic-ui">--}}
        {{--        <i class="menu-icon mdi mdi-dna"></i>--}}
        {{--        <span class="menu-title">Basic UI Elements</span>--}}
        {{--        <i class="menu-arrow"></i>--}}
        {{--      </a>--}}
        {{--      <div class="collapse " id="">--}}
        {{--        <ul class="nav flex-column sub-menu">--}}
        {{--          <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('sy.index')}}">School Year Management</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item }">--}}
        {{--            <a class="nav-link" href="{{route('grade.index')}}">grade Management</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item ">--}}
        {{--            <a class="nav-link" href="{{route('teacher.index')}}">Teacher List</a>--}}
        {{--          </li>--}}
        {{--        </ul>--}}
        {{--      </div>--}}
        {{--    </li>--}}

        {{--    <li class="nav-item {{ active_class(['charts/chartjs']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/charts/chartjs') }}">--}}
        {{--        <i class="menu-icon mdi mdi-chart-line"></i>--}}
        {{--        <span class="menu-title">Charts</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['tables/basic-table']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/tables/basic-table') }}">--}}
        {{--        <i class="menu-icon mdi mdi-table-large"></i>--}}
        {{--        <span class="menu-title">Tables</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['icons/material']) }}">--}}
        {{--      <a class="nav-link" href="{{ url('/icons/material') }}">--}}
        {{--        <i class="menu-icon mdi mdi-emoticon"></i>--}}
        {{--        <span class="menu-title">Icons</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item {{ active_class(['user-pages/*']) }}">--}}
        {{--      <a class="nav-link" data-toggle="collapse" href="#user-pages" aria-expanded="{{ is_active_route(['user-pages/*']) }}" aria-controls="user-pages">--}}
        {{--        <i class="menu-icon mdi mdi-lock-outline"></i>--}}
        {{--        <span class="menu-title">User Pages</span>--}}
        {{--        <i class="menu-arrow"></i>--}}
        {{--      </a>--}}
        {{--      <div class="collapse {{ show_class(['user-pages/*']) }}" id="user-pages">--}}
        {{--        <ul class="nav flex-column sub-menu">--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/login']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/login') }}">Login</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/register']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/register') }}">Register</a>--}}
        {{--          </li>--}}
        {{--          <li class="nav-item {{ active_class(['user-pages/lock-screen']) }}">--}}
        {{--            <a class="nav-link" href="{{ url('/user-pages/lock-screen') }}">Lock Screen</a>--}}
        {{--          </li>--}}
        {{--        </ul>--}}
        {{--      </div>--}}
        {{--    </li>--}}
        {{--    <li class="nav-item">--}}
        {{--      <a class="nav-link" href="https://www.bootstrapdash.com/demo/star-laravel-free/documentation/documentation.html" target="_blank">--}}
        {{--        <i class="menu-icon mdi mdi-file-outline"></i>--}}
        {{--        <span class="menu-title">Documentation</span>--}}
        {{--      </a>--}}
        {{--    </li>--}}
    </ul>
</nav>
