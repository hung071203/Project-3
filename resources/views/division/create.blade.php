@extends('layout.master')
@section('content')
    <form method="post" action="{{ route('division.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div>
                    <h2 class="text-left mb-4">Phân công mới</h2>
                </div>
                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">
                            <!-- Class selection -->
                            <div class="form-group">
                                <label class="label">Lớp Học</label>
                                <div>
                                    <select class="form-select" name="class_id"  aria-label="Default select example">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">
                                                {{ $class->class_name }}_{{ $class->sy_name }}___({{ $class->grade_name }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Teacher selection -->
                            <div class="form-group">
                                <label class="label">Giáo Viên Phụ Trách</label>
                                <div>
                                    <select class="form-select" name="teacher_id" aria-label="Default select example">
                                        @foreach($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">
                                                {{ $teacher->teacher_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Subject selection -->
                            <div class="form-group">
                                <label class="label">Môn Học</label>
                                <div>
                                    <select class="form-select" name="subject_id" aria-label="Default select example">
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}">
                                                {{ $subject->subject_name }}___({{ $subject->grade_name }})___
                                                @if($subject-> semester == 1)
                                                    Kỳ 1
                                                @elseif($subject-> semester == 2)
                                                    Kỳ 2
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Exam type selection -->
                            <label class="label">Loại bài kiểm tra</label>
                            <div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="exam_type[]" id="exam_type_1" value="1">

                                    <label class="" for="exam_type_1">Kiểm tra miệng, thường xuyên</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="exam_type[]" id="exam_type_2" value="2">
                                    <label class="" for="exam_type_2">15 phút</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="exam_type[]" id="exam_type_3" value="3">
                                    <label class="" for="exam_type_3">1 Tiết</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="exam_type[]" id="exam_type_4" value="4">
                                    <label class="" for="exam_type_4">Cuối kỳ</label>
                                </div>
                            </div>



                            <!-- Submit button -->
{{--                            <button class="btn btn-outline-success">Add</button>--}}
                            <button class="btn btn-success">Add new</button>
                        </div>
                    </div>
                </div>
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
