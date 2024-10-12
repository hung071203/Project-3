@extends('layout.master')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger" id="myText">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="myText">
            {{ session('success') }}
        </div>
    @endif
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý học sinh</h1>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('student.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name, email, class ..." name="search">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
            <a class="btn btn-success btn-fw menu-icon mdi mdi-creation" href="{{ route('student.create') }}">Thêm mới</a>

            {{-- <div class="mb-3">
                <label for="per_page" class="form-label">Records per page:</label>
                <select class="form-select" id="per_page" name="per_page">
                    <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ $perPage == 20 ? 'selected' : '' }}>20</option>
                    <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                </select>
            </div> --}}

            <!-- Danh sách sinh viên -->
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Mã học sinh</th>
                    <th>Student Name</th>
                    <th>Lớp học</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    {{-- <th>Password</th> --}}
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($students as $student)
                    <tr>
                        <td>{{ $student->id }}</td>
                        <td>{{ $student->student_name }}</td>
                        <td>{{ $student->class_name }}{{ $student->sy_name }}</td>
                        <td>{{ $student->email }}</td>
                        <td>{{ $student->phone }}</td>
                        {{-- <td>{{ $student->password }}</td> --}}
                        <td>
                            <a href="{{ route('student.edit', $student->id) }}" class="btn btn-inverse-primary">Chỉnh sửa</a>
                        </td>
{{--                        <td>--}}
{{--                            <form method="post" action="{{ route('student.destroy', $student->id) }}">--}}
{{--                                @csrf--}}
{{--                                @method('DELETE')--}}
{{--                                <button class="btn btn-danger btn-fw" type="submit" onclick="return confirmDelete();">Xóa</button>--}}
{{--                            </form>--}}
{{--                        </td>--}}
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-center">
                {{ $students->links() }}
            </div>

        </div>
    </div>

    <script>
        function confirmDelete() {
            // Sử dụng hộp thoại xác nhận
            return confirm('Do you want to delete?');
        }
    </script>
@endsection
