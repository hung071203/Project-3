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
            <h1 class="dropdown-item-title; d-flex justify-content-center" >Quản lý lớp </h1>
            <div class="card-body">
                <div class="mb-3">

                    <div class="card-body">
                        <div class="mb-3">
                            <form method="GET" action="{{ route('class.index') }}">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Tìm kiếm" name="search">
                                    <button type="submit" class="btn btn-primary">Tìm kiếm </button>
                                </div>
                            </form>
                        </div>
                        <a class="btn btn-success btn-fw menu-icon mdi mdi-creation" href="{{ route('class.create') }}" class="btn btn-light">Thêm mới</a>
                        <table class="table table-striped">
                            <tr>
                                <th>ID lớp</th>
                                <th>Tên lớp</th>
                                <th>Số học sinh</th>
                                <th>Khối</th>
                                <th>Niên khóa</th>
                                <th>Hành động</th>
                            </tr>
                            @foreach($classes as $class)
                                <tr>
                                    <td>{{ $class->id }}</td>
                                    <td>{{ $class->class_name }}_{{ $class->sy_name }}</td>
                                    <td>{{ $class->getNumberOfStudents() }}</td>
                                    <td>{{ $class->grade_name }}</td>
                                    <td>{{ $class->sy_start }}-{{ $class->sy_end }}</td>

                                    <td>
                                        <a class="btn btn-inverse-primary" href="{{ route('class.edit', $class->id) }}" class="btn btn-app">Chỉnh sửa</a>
{{--                                        <form method="post" action="{{ route('class.destroy', $class->id) }}">--}}
{{--                                            @csrf--}}
{{--                                            @method('DELETE')--}}
{{--                                            <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa</button>--}}
{{--                                        </form>--}}
{{--                                        <script>--}}
{{--                                            function confirmDelete() {--}}
{{--                                                // Sử dụng hộp thoại xác nhận--}}
{{--                                                return confirm('Do you want to delete?');--}}
{{--                                            }--}}
{{--                                        </script>--}}
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
