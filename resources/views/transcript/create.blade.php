@extends('layout.masterteacher')

@section('content')
    <form method="post" action="{{ route('transcript.store') }}">
        <div class="card">
            <div class="card-body">
                @csrf
                <input type="hidden" name="division_id" value="{{ $division->id }}">

                <div class="form-group row mb-4">
                    <div class="col-md-3 offset-md-9">
                        <label class="mdi mdi-account-badge-alert">Class:</label>
                        <span>
                            {{ $division->class->class_name }}_{{ $division->class->school_year->sy_name }}
                        </span>
                    </div>
                </div>

                <table class="table table-dark">
                    <tr>
                        <th>Môn học</th>
                        <th>Kỳ học</th>
                        <th>Hình thức kiểm tra</th>
                    </tr>
                    <tr>
                        <td>
                            @isset($division->subject)
                                {{ $division->subject->subject_name }}
                            @else
                                Subject not available
                            @endisset
                        </td>
                        <td>
                            @if(isset($division->subject->semester))
                                @if($division->subject->semester == 1)
                                    <div class="badge badge-primary">Kỳ 1</div>
                                @elseif($division->subject->semester == 2)
                                    <div class="badge badge-info">Kỳ 2</div>
                                @else
                                    Invalid Semester
                                @endif
                            @else
                                Semester not available
                            @endisset
                        </td>
                        <td>
                            @isset($division->exam_type)
                                @if ($division->exam_type == 1)
                                    Kiểm tra miệng, thường xuyên
                                    <input type="hidden" name="exam_type" value="{{$division->exam_type}}">
                                @elseif ($division->exam_type == 2)
                                    15 phút
                                    <input type="hidden" name="exam_type" value="{{$division->exam_type}}">
                                @elseif ($division->exam_type == 3)
                                    Giữa kỳ
                                    <input type="hidden" name="exam_type" value="{{$division->exam_type}}">
                                @elseif ($division->exam_type == 4)
                                    Cuối kỳ
                                    <input type="hidden" name="exam_type" value="{{$division->exam_type}}">
                                @else
                                    Invalid Exam Type
                                @endif
                            @else
                                Exam Type not available
                            @endisset
                        </td>
                    </tr>
                </table>

                <div class="form-group">
                    <label class="label">Tên bảng điểm</label>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Tên bảng điểm" name="transcript_name">
                    </div>
                </div>

                <button class="btn btn-success">Thêm mới</button>
            </div>
        </div>
    </form>
@endsection


<!-- Add these in your master layout -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.form-check-input').change(function() {
            if ($(this).is(':checked')) {
                $(this).closest('.form-check').addClass('active');
            } else {
                $(this).closest('.form-check').removeClass('active');
            }
        });
    });
</script>

<style>
    /* Ẩn màu nền xanh lá khi checkbox được kiểm tra */
    .form-check-input:checked+.form-check-label::before {
        background-color: transparent !important;
    }
</style>
