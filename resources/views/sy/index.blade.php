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
                <h1 class="card-title">Niên khóa</h1>
                <form method="GET" action="{{ route('sy.index') }}">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tìm kiếm theo tên, số..." name="search">
                        <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                    </div>
                </form>
                <a class="btn btn-success btn-fw menu-icon mdi mdi-creation" href="{{ route('sy.create') }}">Thêm mới</a>
                <table class="table table-striped">
                    <tr>
                        <th>ID</th>
                        <th>Niên khóa</th>
                        <th>Hành động</th>
                    </tr>
                    @foreach($school_years as $school_year)
                        <tr>
                            <td>{{ $school_year->sy_name }}</td>
                            <td class="text-danger">{{ $school_year->sy_start }}-{{ $school_year->sy_end }}</td>
                            <td>
                                <a class="btn btn-inverse-primary" href="{{ route('sy.edit', $school_year->id) }}">Chỉnh sửa</a>
                            </td>
{{--<td>--}}
{{--                                <form method="post" action="{{ route('sy.destroy', $school_year->id) }}">--}}
{{--                                    @method('DELETE')--}}
{{--                                    @csrf--}}
{{--                                    <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa</button>--}}
{{--                                </form>--}}
{{--                                <script>--}}
{{--                                    function confirmDelete() {--}}
{{--                                        return confirm('Bạn có muốn xóa không?');--}}
{{--                                    }--}}
{{--                                </script>--}}
{{--                            </td>--}}
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
