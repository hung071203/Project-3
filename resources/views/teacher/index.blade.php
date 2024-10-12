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

    <div class="col-lg-12 grid-margin stretch-card">

        <div class="card">

            <div class="card-body">

                <div class="mb-3">
                    <h1 class="card-title">Giáo viên</h1>
                </div>

                <div class="mb-3">
                    <form method="GET" action="{{ route('teacher.index') }}">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm theo tên, email, lớp ..." name="search">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>



                <div class="d-flex justify-content-lg-end">
                    {{ $teachers->links() }}
                </div>

                <a class="btn btn-success btn-fw menu-icon mdi mdi-creation" href="{{ route('teacher.create') }}" >Thêm mới</a>

                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID Giáo viên</th>
                        <th>Tên giáo viên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($teachers as $teacher)
                        <tr>
                            <td>
                                {{$teacher->id}}
                            </td>
                            <td>
                                {{$teacher->teacher_name}}
                            </td>
                            <td>
                                {{$teacher->email}}
                            </td>
                            <td>
                                {{$teacher->phone}}
                            </td>
                            <td>
                                <a href="{{route('teacher.edit', $teacher -> id )}}" class="btn btn-inverse-primary">Chỉnh sửa</a>
                            </td>
{{--                            <td>--}}
{{--                                <form method="post" action="{{route('teacher.destroy', $teacher -> id )}}">--}}
{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}
{{--                                    <button class="btn btn-danger btn-fw"  type="submit" onclick="return confirmDelete();">Xóa</button>--}}
{{--                                </form>--}}
{{--                                <script>--}}
{{--                                    function confirmDelete() {--}}
{{--                                        // Sử dụng hộp thoại xác nhận--}}
{{--                                        return confirm('Bạn có chắc chắn muốn xóa?');--}}
{{--                                    }--}}
{{--                                </script>--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
@endsection
