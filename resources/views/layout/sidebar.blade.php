<script src="jquery.min.js"></script>
<script src="bootstrap.min.js"></script>
{{--@if(session('teacher'))--}}
{{--@endif--}}
<nav class="sidebar sidebar-offcanvas dynamic-active-class-disabled" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile not-navigation-link">
            <div class="nav-link">
                <div class="user-wrapper">
                    <div class="profile-image">
                        <img src="{{ url('assets/images/faces/avt.jpg') }}" alt="profile image">
                    </div>
                    <div class="text-wrapper">
                        @if(Auth::guard('admin')->check())
                            <p class="username">{{ Auth::guard('admin')->user()->username }}</p>
                        @endif
                        <div class="dropdown" data-display="static">
                            <a href="#" class="nav-link d-flex user-switch-dropdown-toggler" id="UsersettingsDropdown" data-toggle="dropdown" aria-expanded="false">
                                <small class="designation text-muted">Giáo vụ</small>
                                <span class="status-indicator online"></span>
                            </a>

                        </div>
                    </div>
                </div>
                <div id="clock">
                    <center>
                        <span id="date"></span><br>
                        <span id="time"></span>
                    </center>
                </div>
            </div>
        </li>
        <li class="nav-item ">
            <a class="nav-link" href="{{url('/')}}">
                <i class="menu-icon mdi mdi-television"></i>
                <span class="menu-title">Trang chủ</span>
            </a>

        <li class="nav-item ">
            <a class="nav-link" href="{{route('division.index')}}">
                <i class="menu-icon mdi mdi-division-box"></i>
                <span class="menu-title">Phân Công Chấm Điểm</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#school-collapse" aria-expanded="false" aria-controls="school-collapse">
                <i class="menu-icon mdi mdi-school"></i>
                <span class="menu-title">Trường Học</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="school-collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('sy.index')}}">Quản Lý Niên Khóa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('grade.index')}}">Quản Lý Khối</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('class.index')}}">Quản lý Lớp Học</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('subject.index')}}">Quản lý Môn Học</a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#account-collapse" aria-expanded="false" aria-controls="account-collapse">
                <i class="menu-icon mdi mdi-account"></i>
                <span class="menu-title">Tài Khoản</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="account-collapse">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('teacher.index')}}">Quản Lý Giáo Viên</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('student.index')}}">Quản Lý Học Sinh</a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</nav>
<script>
    function updateClock() {
        const now = new Date();
        const daysOfWeek = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const dayOfWeek = daysOfWeek[now.getDay()];
        const day = now.getDate().toString().padStart(2, '0');
        const month = (now.getMonth() + 1).toString().padStart(2, '0');
        const year = now.getFullYear();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        const seconds = now.getSeconds().toString().padStart(2, '0');
        const dateString = `${dayOfWeek}, ${day}/${month}/${year}`;
        const timeString = `${hours}:${minutes}:${seconds}`;

        document.getElementById('date').textContent = dateString;
        document.getElementById('time').textContent = timeString;
    }

    // Cập nhật đồng hồ mỗi giây
    setInterval(updateClock, 1000);

    // Khởi động đồng hồ ban đầu
    updateClock();

    // Save the state of the collapsible menu items
    document.querySelectorAll('.nav-link[data-toggle="collapse"]').forEach(function(element) {
        element.addEventListener('click', function() {
            const targetId = this.getAttribute('href').substring(1);
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            localStorage.setItem(targetId, isExpanded ? 'collapsed' : 'expanded');
        });
    });

    // Restore the state of the collapsible menu items
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.collapse').forEach(function(element) {
            const targetId = element.getAttribute('id');
            const state = localStorage.getItem(targetId);
            if (state === 'expanded') {
                element.classList.add('show');
                document.querySelector(`a[href="#${targetId}"]`).setAttribute('aria-expanded', 'true');
            }
        });
    });
</script>
<style>
    .menu-title {
        font-weight: bold;
    }
    .nav-item a.nav-link {
        color: #333;
        transition: all 0.3s ease;
    }
    .nav-item a.nav-link:hover {
        background-color: #f8f9fa;
        border-radius: 5px;
        color: #007bff;
    }
    .nav-item a.nav-link .menu-icon {
        font-size: 1.2em;
        margin-right: 10px;
    }
    /*.nav-item.nav-profile .nav-link {*/
    /*    background-color: #007bff;*/
    /*    color: white;*/
    /*    border-radius: 10px;*/
    /*    padding: 15px;*/
    /*    margin-bottom: 10px;*/
    /*    text-align: center;*/
    /*}*/
    /*.nav-item.nav-profile .nav-link .profile-image img {*/
    /*    border-radius: 50%;*/
    /*    width: 50px;*/
    /*    height: 50px;*/
    /*}*/
    .nav-item.nav-profile .text-wrapper {
        text-align: center;
    }
    .collapse.show {
        background-color: #e9ecef;
        border-radius: 5px;
    }
    #clock {
        margin-top: 15px;
    }
</style>
