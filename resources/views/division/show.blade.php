@extends('layout.masterteacher')

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

    <h1>Thông tin phân công</h1>

    <div class="mb-3">
        <form method="GET" action="{{ route('division.show') }}">
            <div class="input-group">
                <select name="status" class="form-select">
                    <option value="">Chọn trạng thái</option>
                    <option value="Working" {{ session('status') == 'Working' ? 'selected' : '' }}>Đang tiến hành</option>
                    <option value="Not Working" {{ session('status') == 'Not Working' ? 'selected' : '' }}>Chưa làm việc</option>
                    <option value="Job Done" {{ session('status') == 'Job Done' ? 'selected' : '' }}>Hoàn thành</option>
                </select>
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('division.show') }}" class="btn btn-secondary">Xóa bộ lọc</a>
            </div>
        </form>
    </div>

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Kì học</th>
            <th>Lớp</th>
            <th>Giáo viên chấm</th>
            <th>Khối</th>
            <th>Môn</th>
            <th>Hình thức kiểm tra</th>
            <th>Người phân công</th>
            <th>Ngày phân công</th>
            <th>Trạng thái</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @php $count = 0 @endphp
        @foreach($divisions as $division)
            <tr>
                <td>
                    @if($division->subject->semester == 1)
                        <div class="badge badge-primary">Kì 1</div>
                    @elseif($division->subject->semester == 2)
                        <div class="badge badge-info">Kì 2</div>
                    @endif
                </td>
                <td>{{ $division->class_name }}_{{ $division->class->school_year->sy_name }}</td>
                <td>{{ $division->teacher_name }}</td>
                <td>{{ $division->grade_name }}</td>
                <td>{{ $division->subject_name }}</td>
                <td>
                    @if($division->exam_type == 1)
                        <div class="badge badge-success">Miệng, thường xuyên</div>
                    @elseif($division->exam_type == 2)
                        <div class="badge badge-info">15 phút</div>
                    @elseif($division->exam_type == 3)
                        <div class="badge badge-warning">1 Tiết</div>
                    @elseif($division->exam_type == 4)
                        <div class="badge badge-danger">Cuối kỳ</div>
                    @endif
                </td>
                <td>{{ $division->username }}</td>
                <td>{{ $division->datetime }}</td>
                <td>
                    @if($division->getStatus() == 'Job Done')
                        <div class="badge badge-success">Hoàn thành</div>
                    @elseif($division->getStatus() == 'Working')
                        <div class="badge badge-warning">Đang tiến hành</div>
                    @else
                        <div class="badge badge-danger">Chưa làm việc</div>
                    @endif
                </td>
                <td>
                    @if($division->getStatus() == 'Job Done')
                        {{-- No action needed for Job Done --}}
                    @elseif($division->getStatus() == 'Working' && in_array($division->exam_type, [3, 4]) )

                    @elseif(in_array($division->exam_type, [1, 2]) && $division->transcriptCount < 2)
                        <a class="btn btn-outline-success" href="{{ route('transcript.create', ['division_id' => $division->id]) }}">Tạo bảng điểm</a>

                    @else
                        @if($division->getStatus() == 'Not Working')
                            <a class="btn btn-outline-success" href="{{ route('transcript.create', ['division_id' => $division->id]) }}">Tạo bảng điểm</a>
                        @endif

                    @endif
                </td>
            </tr>
        @endforeach
        @if($divisions->count() == 0)
            <tr>
                <td colspan="9" style="text-align: center" class="blink-red">Không tìm thấy kết quả</td>
            </tr>
        @endif
        </tbody>
    </table>
@endsection

<style>
    @keyframes blink {
        0% {
            background-color: rgba(255, 0, 0, 0.1);
        }
        50% {
            background-color: rgba(255, 0, 0, 0.5);
        }
        100% {
            background-color: rgba(255, 0, 0, 0.1);
        }
    }

    .blink-red {
        animation: blink 1.5s infinite;
    }
</style>
