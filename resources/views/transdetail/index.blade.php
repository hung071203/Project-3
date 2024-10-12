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

    <div class="col-lg-15 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Bảng điểm chi tiết</h1>

                @if($transcript_details->isNotEmpty())
                    <input type="hidden" name="transcript_id" value="{{ $transcript_details->first()->transcript->id }}">
                    <div class="row mb-4">
                        <div class="col-md-3 offset-md-9">
                            <label class="mdi mdi-account-badge-alert">Lớp: </label>
                            <span>{{ $transcript_details->first()->transcript->division->class->class_name }}_{{ $transcript_details->first()->transcript->division->class->school_year->sy_name }}</span>
                        </div>
                        <div class="col-md-3 offset-md-9">
                            <a href="{{ route('transdetail.export', ['transcriptId' => $transcript_details->first()->transcript->id]) }}" class="btn btn-primary">Export to Excel</a>
                        </div>
                    </div>
                    <table class="table table-dark">
                        <tr>
                            <th>Tên Bảng Điểm</th>
                            <th>Môn Học</th>
                            <th>Hình Thức Kiểm Tra</th>
                            <th>Kỳ Học</th>
                        </tr>
                        <tr>
                            <td>{{ $transcript_details->first()->transcript->transcript_name }}</td>
                            <td>{{ $transcript_details->first()->transcript->division->subject->subject_name }}</td>
                            <td>
                                @if($transcript_details->first()->transcript->division->exam_type == 1)
                                    <div class="badge badge-success">Miệng, Thường Xuyên</div>
                                @elseif($transcript_details->first()->transcript->division->exam_type == 2)
                                    <div class="badge badge-info">15 Phút</div>
                                @elseif($transcript_details->first()->transcript->division->exam_type == 3)
                                    <div class="badge badge-warning">1 Tiết</div>
                                @elseif($transcript_details->first()->transcript->division->exam_type == 4)
                                    <div class="badge badge-danger">Cuối Kỳ</div>
                                @endif
                            </td>
                            <td>
                                @if($transcript_details->first()->transcript->division->subject->semester == 1)
                                    <div class="badge badge-primary">Kỳ 1</div>
                                @elseif($transcript_details->first()->transcript->division->subject->semester == 2)
                                    <div class="badge badge-info">Kỳ 2</div>
                                @endif
                            </td>
                        </tr>
                    </table>
                @endif

                <table class="table table-striped">
                    <tr>
                        <th>Tên Học Sinh</th>
                        <th>Khối</th>
                        <th>Ghi Chú</th>
                        <th>Điểm</th>
                        <th>Hành động</th>
                    </tr>
                    @foreach($transcript_details as $transcript_detail)
                        <tr>
                            <td>{{ $transcript_detail->student->student_name }}</td>
                            <td>{{ $transcript_detail->grade_name }}</td>
                            <td>
                                @if($transcript_detail->note == 1)
                                    <div class="badge badge-info">Làm Bài</div>
                                @elseif($transcript_detail->note == 2)
                                    <div class="badge badge-danger">Bị Cấm</div>
                                @elseif($transcript_detail->note == 3)
                                    <div class="badge badge-warning">Vắng Mặt</div>
                                @endif
                            </td>
                            <td @if($transcript_detail->score <= 5) class="text-danger" @elseif($transcript_detail->score >= 5.01) class="text-success" @endif>
                                {{ $transcript_detail->score ?? 'None' }}
                            </td>
                            <td>
                                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                    <a href="{{ route('transdetail.edit', $transcript_detail->id) }}" class="btn btn-inverse-primary">Chỉnh sửa</a>
                                </div>
                                <form method="post" action="{{ route('transdetail.destroy', $transcript_detail->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-inverse-danger" type="submit" onclick="return confirmDelete();">Xóa bản ghi</button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </table>

                <script>
                    function confirmDelete() {
                        return confirm('Do you want to delete?');
                    }
                </script>
            </div>
        </div>
    </div>
@endsection
