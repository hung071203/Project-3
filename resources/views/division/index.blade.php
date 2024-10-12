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
            <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý phân công</h1>
            <div class="card-body">
                <div class="mb-3">
                    <form method="GET" action="{{ route('division.index') }}">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search by ..." name="search" value="{{ session('search') }}">
                            <button type="submit" class="btn btn-primary">Tìm kiếm</button>
                        </div>
                    </form>
                </div>
                <a class="btn btn-success btn-fw menu-icon mdi mdi-adjust" href="{{ route('division.create') }}" class="btn btn-light">Thêm mới</a>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>Lớp</th>
                            <th>Giáo viên phụ trách</th>
                            <th>Khối</th>
                            <th>Môn học</th>
                            <th>Kỳ học</th>
                            <th>Người phân công</th>
                            <th>Trạng thái</th>
                            <th>Thời điểm</th>
                            <th>Hình thức kiểm tra</th>
                            <th>Hành động</th>
                            <th>Chi tiết</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($divisions as $division)
                            <tr>
                                <td>{{ optional($division->class)->class_name }}_{{ optional($division->class->school_year)->sy_name }}</td>
                                <td>{{ optional($division->teacher)->teacher_name }}</td>
                                <td>{{ optional($division->subject->grade)->grade_name }}</td>
                                <td>{{ optional($division->subject)->subject_name }}</td>
                                <td>
                                    @if($division->subject->semester == 1)
                                        <div class="badge badge-primary">Semester 1</div>
                                    @elseif($division->subject->semester == 2)
                                        <div class="badge badge-info">Semester 2</div>
                                    @endif
                                </td>
                                <td>{{ optional($division->admin)->username }}</td>
                                <td>
                                    @if($division->getStatus() == 'Job Done')
                                        <div class="badge badge-success">Hoàn thành</div>
                                    @elseif($division->getStatus() == 'Working')
                                        <div class="badge badge-warning">Đang tiến hành</div>
                                    @else
                                        <div class="badge badge-danger">Chưa làm việc</div>
                                    @endif
                                </td>
                                <td>{{ $division->datetime }}</td>
                                <td>
                                    @if ($division->exam_type == 1)
                                        <small class="alert alert-primary" role="alert">Thường xuyên</small>
                                    @elseif ($division->exam_type == 2)
                                        <small class="alert alert-info" role="alert">15 phút</small>
                                    @elseif ($division->exam_type == 3)
                                        <small class="alert alert-success" role="alert">1 Tiết</small>
                                    @else
                                        <small class="alert alert-warning" role="alert">Cuối kì</small>
                                    @endif
                                </td>
                                <td>
{{--                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">--}}
{{--                                        <a href="{{ route('division.edit', $division->id) }}" class="btn btn-inverse-primary">Chỉnh sửa</a>--}}
{{--                                    </div>--}}
                                    <form method="post" action="{{ route('division.destroy', $division->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa bản ghi</button>
                                    </form>
                                </td>
                                <td>
                                    @if($division->getStatus() == 'Job Done')
                                        <a href="{{ route('division.checkTranscript', $division->id) }}" class="mdi-account">Kiểm tra</a>
                                    @else
                                        <div class="badge badge-dark blinking">Không khả dụng</div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <label>Điểm trung bình môn = (Điểm kiểm tra thường xuyên + điểm kiểm tra 15p + điểm kiểm tra 1 tiết x 2 + điểm kiểm tra học kỳ x 3)/ 7 </label>
            </div>
        </div>
    </div>
@endsection

<style>
    .blinking {
        animation: blinkingText 1.2s infinite;
    }

    @keyframes blinkingText {
        0% { color: red; }
        50% { color: transparent; }
        100% { color: red; }
    }
</style>

<script>
    function confirmDelete() {
        return confirm('Bạn có chắc muốn xóa?');
    }
</script>
