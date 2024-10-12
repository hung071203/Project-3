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
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý khối</h1>
        <div class="card-body">
            <div class="mb-3">
                <form method="GET" action="{{ route('grade.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Nhập thông tin cần tìm..." name="search">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
            </div>
            <a class="btn btn-success btn-fw menu-icon mdi mdi-creation" href="{{ route('grade.create') }}">Thêm mới</a>
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên khối</th>
                    {{-- <th>Teacher in charge</th> --}}
                    {{-- <th>Subject</th> --}}
                    <th>Tổng số lớp</th>
                    <th>Tổng học sinh</th>
                    <th>Chi tiết</th>
                    <th>Hành động</th>
                </tr>
                </thead>
                <tbody>
                @foreach($grades as $grade)
                    <tr>
                        <td>{{ $grade->id }}</td>
                        <td>{{ $grade->grade_name }}</td>
                        {{-- <td>{{ $grade->getNumberOfteacher() }}</td> --}}
                        {{-- <td>{{ $grade->getNumberOfSubject() }}</td> --}}
                        <td>{{ $grade->getNumberOfClass() }}</td>
                        <td>{{ $grade->getNumberOfStudent() }}</td>
                        <td>
                            <a class="btn btn-info" href="{{ route('grade.checkClass', $grade->id) }}">Chi tiết</a>
                        </td>
                        <td>
                            <a class="btn btn-inverse-primary" href="{{ route('grade.edit', $grade->id) }}">Chỉnh sửa</a>
                            {{-- <form method="post" action="{{ route('grade.destroy', $grade->id) }}">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa</button>
                            </form> --}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div> <!-- .card-body -->
    </div> <!-- .card -->
@endsection
