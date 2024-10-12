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
                    <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý môn học</h1>
                    <form method="GET" action="{{ route('subject.index') }}">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Tìm kiếm" name="search">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
                <a href="{{ route('subject.create') }}" class="btn btn-success btn-fw menu-icon mdi mdi-creation">Thêm mới</a>
                <form method="post" action="{{ route('subject.deleteMultiple') }}" id="deleteForm">
                    @csrf
                    @method('DELETE')
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID môn học</th>
                            <th>Tên môn</th>
                            <th>Học kì</th>
                            <th>Khối</th>
                            <th>Hành động</th>
{{--                            <th><input type="checkbox" id="select-all"> Chọn tất cả</th>--}}
                            <th><form method="post" action="{{ route('subject.deleteMultiple') }}" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <!-- Bảng và các trường dữ liệu của môn học -->
                                    <button type="submit" class="btn btn-danger" onclick="return confirmDeleteMultiple();">Xóa nhiều môn học</button>
                                </form>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <td>{{ $subject->id }}</td>
                                <td>{{ $subject->subject_name }}</td>
                                <td>
                                    @if($subject->semester == 1)
                                        Kì 1
                                    @elseif($subject->semester == 2)
                                        Kì 2
                                    @else
                                        {{ $subject->semester }}
                                    @endif
                                </td>
                                <td>{{ $subject->grade_name }}</td>
                                <td>
                                    <a href="{{ route('subject.edit', $subject->id) }}" class="btn btn-inverse-primary">Chỉnh sửa</a>
                                </td>
                                <td>
                                    <form method="post" action="{{ route('subject.destroy', $subject->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa</button>
                                    </form>
                                </td>
                                <td><input type="checkbox" class="delete-checkbox" name="delete_ids[]" value="{{ $subject->id }}"></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
{{--                    <button type="submit" class="btn btn-danger" onclick="return confirmDeleteMultiple();">Xóa nhiều môn học</button>--}}
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    function confirmDelete() {
        return confirm('Bạn có chắc muốn xóa?');
    }

    function confirmDeleteMultiple() {
        return confirm('Bạn có chắc muốn xóa các môn học đã chọn?');
    }
</script>
<script>
    // Xử lý chọn tất cả
    document.getElementById('select-all').addEventListener('change', function() {
        var checkboxes = document.getElementsByClassName('delete-checkbox');
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = this.checked;
        }
    });
</script>
