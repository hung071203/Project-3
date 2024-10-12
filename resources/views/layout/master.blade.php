<!DOCTYPE html>
<html>
<head>
  <title>Trang quản trị</title>
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

    <link rel="stylesheet" href="bootstrap.min.css">
    <script src="jquery.min.js"></script>
    <script src="bootstrap.min.js"></script>

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
      @include('layout.sidebar')
      <div class="main-panel">
        <div class="content-wrapper">
          @yield('content')
        </div>
        @include('layout.footer')
      </div>
    </div>
  </div>

  <div class="modal fade" id="setTimeModal" tabindex="-1" role="dialog" aria-labelledby="setTimeModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="setTimeModalLabel">Set Time</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <!-- Trường nhập liệu cho ngày, tháng, năm, giờ, phút, giây -->
                  <div class="form-group">
                      <label for="inputDate">Date:</label>
                      <input type="date" class="form-control" id="inputDate">
                  </div>
                  <div class="form-group">
                      <label for="inputTime">Time:</label>
                      <input type="time" class="form-control" id="inputTime">
                  </div>
              </div>
              <div class="modal-footer">
                  <!-- Nút lưu thay đổi và đóng modal -->
                  <button type="button" class="btn btn-primary" onclick="setVirtualTime()">Set Virtual Time</button>
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>

  <script>
      // Hàm để kiểm tra xem có thời gian ảo đã được lưu trong sessionStorage chưa
      function checkStoredVirtualTime() {
          const storedVirtualTime = sessionStorage.getItem('virtualTime');
          const storedUseVirtualTime = sessionStorage.getItem('useVirtualTime');

          if (storedVirtualTime && storedUseVirtualTime) {
              virtualTime = new Date(storedVirtualTime);
              useVirtualTime = JSON.parse(storedUseVirtualTime);
          } else {
              useVirtualTime = false; // Nếu không có dữ liệu, set về thời gian thật
          }
      }

      let virtualTime = null;
      let useVirtualTime = false;

      // Khởi tạo đồng hồ từ sessionStorage khi trang được tải
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
              saveVirtualTimeToSessionStorage();
              $('#setTimeModal').modal('hide');
          } else {
              alert('Please enter both date and time.');
          }
      }

      function resetToRealTime() {
          useVirtualTime = false;
          virtualTime = new Date(); // Set về thời gian thật
          updateClock();
          saveVirtualTimeToSessionStorage();
      }

      function saveVirtualTimeToSessionStorage() {
          sessionStorage.setItem('virtualTime', useVirtualTime ? virtualTime.toISOString() : '');
          sessionStorage.setItem('useVirtualTime', JSON.stringify(useVirtualTime));
      }

      // Liên tục cập nhật đồng hồ mỗi giây
      setInterval(() => {
          updateClock();
          // Nếu sử dụng thời gian ảo, thì cập nhật thời gian ảo và lưu vào sessionStorage
          if (useVirtualTime) {
              virtualTime.setSeconds(virtualTime.getSeconds() + 1);
              saveVirtualTimeToSessionStorage();
          }
      }, 1000);
  </script>


  <!-- base js -->
  <script src="{{asset('assets/js/app.js')}}" ></script>
{{--  <script src="{{asset('assets/js/todolist.js')}}"></script>--}}
  <script src="{{asset('assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
  <!-- end base js -->

  <!-- plugin js -->

  <!-- end plugin js -->

  <!-- common js -->
  <script src="{{asset('assets/js/chart.js')}}"></script>
  <script src="{{asset('assets/js/off-canvas.js')}}"></script>
  <script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('assets/js/misc.js')}}"></script>
  <script src="{{asset('assets/js/settings.js')}}"></script>
  <script src="{{asset('assets/js/todolist.js')}}"></script>
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{--  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>--}}
{{--  <script src="{{ asset('js/custom.js') }}"></script>--}}


  <script>
      $(document).ready(function() {
          $('#classSelect').change(function() {
              var classId = $(this).val();
              var url = '/get-students-by-class/' + classId;

              $.ajax({
                  url: url,
                  type: 'GET',
                  success: function(data) {
                      var studentList = $('#studentList');
                      studentList.empty();

                      if (data.length > 0) {
                          $.each(data, function(index, student) {
                              studentList.append('<div>' + student.name + '</div>');
                          });
                      } else {
                          studentList.append('<div>No students found.</div>');
                      }
                  },
                  error: function(error) {
                      console.error(error);
                  }
              });
          });
      });
  </script>
  <script>
      $(document).ready(function(){
          $('#per_page').on('change', function(){
              var perPage = $(this).val();
              var currentUrl = window.location.href;
              // Kiểm tra xem URL đã chứa tham số per_page hay chưa
              if (currentUrl.includes('per_page=')) {
                  // Nếu đã chứa, thay thế giá trị cũ bằng giá trị mới
                  currentUrl = currentUrl.replace(/per_page=\d+/, 'per_page=' + perPage);
              } else {
                  // Nếu chưa chứa, thêm tham số vào URL
                  currentUrl += (currentUrl.includes('?') ? '&' : '?') + 'per_page=' + perPage;
              }
              // Chuyển hướng đến URL mới
              window.location.href = currentUrl;
          });
      });
  </script>
  <script>
      var myTextElement = document.getElementById('myText');
      setTimeout(function () {
          myTextElement.style.display = 'none';
      }, 5000);
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
  <!-- end common js -->

  <script>
      // Lấy các phần tử cần thiết
      const modeToggle = document.getElementById('modeToggle');
      const modeIcon = document.getElementById('modeIcon');
      const body = document.body;

      // Định nghĩa biến để theo dõi trạng thái chế độ
      let isDarkMode = false;

      // Xử lý sự kiện khi nút chuyển đổi được nhấp vào
      modeToggle.addEventListener('click', function() {
          isDarkMode = !isDarkMode; // Đảo ngược trạng thái chế độ
          if (isDarkMode) {
              // Nếu là chế độ ban đêm
              body.classList.add('dark-mode'); // Thêm lớp CSS cho chế độ ban đêm
              modeIcon.className = 'mdi mdi-weather-sunset'; // Thay đổi biểu tượng
          } else {
              // Nếu là chế độ Light
              body.classList.remove('dark-mode'); // Loại bỏ lớp CSS chế độ ban đêm
              modeIcon.className = 'mdi mdi-weather-sunny'; // Thay đổi biểu tượng
          }
      });
  </script>
</body>
</html>
<style>

</style>
