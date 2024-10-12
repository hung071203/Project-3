<!DOCTYPE html>
<html>
<head>
    <title>Trang giáo viên</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="_token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('assets/favicon.ico') }}">

    <!-- plugin css -->
    <link rel="stylesheet" href="{{asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css')}}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- end plugin css -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    {{--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    {{--    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>--}}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{asset('assets/plugins/@mdi/font/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.css')}}">
    <!-- end plugin css -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>



    {{--    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">--}}
    <!-- common css -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <!-- end common css -->
    {{--    <link rel="stylesheet" href="{{asset('assets/css/app.css')}}">--}}
    <!-- common css -->
    <link rel="stylesheet" href="{{asset('css/app.css')}}">
    <!-- end common css -->

</head>
<body data-base-url="{{url('/')}}">

<div class="container-scroller" id="app">
    @include('layout.header')
    <div class="container-fluid page-body-wrapper">
                @include('layout.sidebarteacher')
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>
                    @include('layout.footer')
        </div>
    </div>
</div>

<!-- base js -->
<script src="{{asset('assets/js/app.js')}}" ></script>
{{--  <script src="{{asset('assets/js/todolist.js')}}"></script>--}}
<script src="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
<!-- end base js -->

<!-- plugin js -->

<!-- end plugin js -->

<!-- common js -->
<script src="{{asset('assets/js/off-canvas.js')}}"></script>
<script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('assets/js/misc.js')}}"></script>
<script src="{{asset('assets/js/settings.js')}}"></script>
<script src="{{asset('assets/js/todolist.js')}}"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>

<script>
    // Hàm để kiểm tra xem có thời gian ảo đã được lưu trong localStorage chưa
    function checkStoredVirtualTime() {
        const storedVirtualTime = localStorage.getItem('virtualTime');
        const storedUseVirtualTime = localStorage.getItem('useVirtualTime');

        if (storedVirtualTime && storedUseVirtualTime) {
            virtualTime = new Date(storedVirtualTime);
            useVirtualTime = JSON.parse(storedUseVirtualTime);
        }
    }

    let virtualTime = null;
    let useVirtualTime = false;

    // Khởi tạo đồng hồ từ localStorage khi trang được tải
    checkStoredVirtualTime();
    updateClock();

    function updateClock() {
        const now = useVirtualTime ? virtualTime : new Date();
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

    function setVirtualTime() {
        const inputDate = document.getElementById('inputDate').value;
        const inputTime = document.getElementById('inputTime').value;

        if (inputDate && inputTime) {
            virtualTime = new Date(`${inputDate}T${inputTime}`);
            useVirtualTime = true;
            updateClock();
            saveVirtualTimeToLocalStorage();
            $('#setTimeModal').modal('hide');
        } else {
            alert('Please enter both date and time.');
        }
    }

    function resetToRealTime() {
        useVirtualTime = false;
        updateClock();
        saveVirtualTimeToLocalStorage();
    }

    function saveVirtualTimeToLocalStorage() {
        localStorage.setItem('virtualTime', useVirtualTime ? virtualTime.toISOString() : '');
        localStorage.setItem('useVirtualTime', JSON.stringify(useVirtualTime));
    }

    // Liên tục cập nhật đồng hồ mỗi giây
    setInterval(() => {
        updateClock();
        // Nếu sử dụng thời gian ảo, thì cập nhật thời gian ảo và lưu vào localStorage
        if (useVirtualTime) {
            virtualTime.setSeconds(virtualTime.getSeconds() + 1);
            saveVirtualTimeToLocalStorage();
        }
    }, 1000);
</script>

<script>
    var myTextElement = document.getElementById('myText');
    setTimeout(function () {
        myTextElement.style.display = 'none';
    }, 3000);
</script>
<script>
    // Lấy tất cả các nút hoặc liên kết có class "delete-button"
    var deleteButtons = document.querySelectorAll('.delete-button');

    // Lặp qua từng nút hoặc liên kết và thêm sự kiện click
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            // Ngăn chặn hành động mặc định của liên kết (không chuyển hướng trang)
            event.preventDefault();

            // Sử dụng hộp thoại xác nhận
            var confirmation = confirm('Bạn có muốn xóa không?');

            // Nếu người dùng đồng ý xóa, chuyển hướng đến đường dẫn xóa
            if (confirmation) {
                window.location = button.getAttribute('href');
            }
        });
    });
</script>
<script>
    // Lấy tất cả các nút hoặc liên kết có class "delete-button"
    var editButtons = document.querySelectorAll('.edit-button');

    // Lặp qua từng nút hoặc liên kết và thêm sự kiện click
    editButtons.forEach(function(button) {
        button.addEventListener('click', function(event) {
            // Ngăn chặn hành động mặc định của liên kết (không chuyển hướng trang)
            event.preventDefault();

            // Sử dụng hộp thoại xác nhận
            var confirmation = confirm('Warning!!!This Action will change a status and it wont be change anymore. Are you sure with this?');

            // Nếu người dùng đồng ý xóa, chuyển hướng đến đường dẫn xóa
            if (confirmation) {
                window.location = button.getAttribute('href');
            }
        });
    });
</script>




<!-- end common js -->


</body>
</html>
