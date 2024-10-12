@extends('layout.masterTeacher')
@section('content')
    <form method="get" action="{{ route('teacher.transcriptCheck') }}">
        <!-- Your filter form inputs -->

        <button type="submit" name="export" value="excel" class="btn btn-success">Xuất Excel</button>
    </form>
    <div class="col-lg-15 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <form method="get" action="{{ route('teacher.transcriptCheck') }}">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="label">Niên Khóa:</label>
                            <select name="school_year_id" class="form-select">
                                <option value="all">Toàn bộ</option>
                                @foreach($schoolYears as $schoolYear)
                                    <option value="{{ $schoolYear->id }}" {{ $schoolYear->id == $schoolYearId ? 'selected' : '' }}>{{ $schoolYear->sy_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label">Lớp:</label>
                            <select name="class_id" class="form-select">
                                <option value="all">Toàn bộ</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ $class->id == $classId ? 'selected' : '' }}>{{ $class->class_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="label">Môn Học:</label>
                            <select name="subject_name" id="subject_name" class="form-select">
                                <option value="all">Toàn bộ</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->subject_name }}" {{ $subject->subject_name == $subjectName ? 'selected' : '' }}>{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>


                        <div class="col-md-3">
                            <label class="label">Học Kỳ:</label>
                            <select name="semester" id="semester" class="form-select">
{{--                                <option value="all">Toàn bộ</option>--}}
                                <option value="1" {{ $semester == '1' ? 'selected' : '' }}>Kỳ 1</option>
                                <option value="2" {{ $semester == '2' ? 'selected' : '' }}>Kỳ 2</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Lọc</button>
                </form>

                @if($transcriptDetails->isNotEmpty())
                    <!-- Detailed Transcript Table -->
                    <table id="detailedTranscriptTable" class="table table-striped mt-4">
                        <thead>
                        <tr>
                            <th>Tên học sinh</th>
                            <th class="text-center" colspan="2">Thường xuyên</th>
                            <th class="text-center" colspan="2">15 phút</th>
                            <th class="text-center">1 tiết</th>
                            <th class="text-center">Cuối kỳ</th>
                            <th class="text-center">Trung bình môn</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th class="text-center">Điểm 1</th>
                            <th class="text-center">Điểm 2</th>
                            <th class="text-center">Điểm 1</th>
                            <th class="text-center">Điểm 2</th>
                            <th class="text-center">Điểm kiểm tra</th>
                            <th class="text-center">Điểm thi</th>
                            <th class="text-center">Điểm trung bình</th>
                        </tr>
                        </thead>
                        <tbody id="transcriptDetailsBody">
                        @foreach($transcriptDetails->groupBy('student_name') as $student_name => $student_details)
                            @php
                                $thuong_xuyen_scores = ['', ''];
                                $phut_15_scores = ['', ''];
                                $giua_ky_score = null;
                                $cuoi_ky_score = null;

                                foreach ($student_details as $detail) {
                                    if ($detail->exam_type == 1) {
                                        if ($thuong_xuyen_scores[0] === '') {
                                            $thuong_xuyen_scores[0] = $detail->score;
                                        } else {
                                            $thuong_xuyen_scores[1] = $detail->score;
                                        }
                                    }
                                    if ($detail->exam_type == 2) {
                                        if ($phut_15_scores[0] === '') {
                                            $phut_15_scores[0] = $detail->score;
                                        } else {
                                            $phut_15_scores[1] = $detail->score;
                                        }
                                    }
                                    if ($detail->exam_type == 3) {
                                        $giua_ky_score = $detail->score;
                                    }
                                    if ($detail->exam_type == 4) {
                                        $cuoi_ky_score = $detail->score;
                                    }
                                }

                                $average_score = null;
                                if ($thuong_xuyen_scores[0] !== '' && $thuong_xuyen_scores[1] !== '' && $phut_15_scores[0] !== '' && $phut_15_scores[1] !== '' && $giua_ky_score !== null && $cuoi_ky_score !== null) {
                                    $average_score = round(
                                        ($thuong_xuyen_scores[0] + $thuong_xuyen_scores[1] + $phut_15_scores[0] + $phut_15_scores[1] + 2 * $giua_ky_score + 3 * $cuoi_ky_score) / 9,
                                        2
                                    );
                                }
                            @endphp
                            <tr>
                                <td>{{ $student_name }}</td>
                                <td class="text-center">{{ $thuong_xuyen_scores[0] !== '' ? $thuong_xuyen_scores[0] : 'None' }}</td>
                                <td class="text-center">{{ $thuong_xuyen_scores[1] !== '' ? $thuong_xuyen_scores[1] : 'None' }}</td>
                                <td class="text-center">{{ $phut_15_scores[0] !== '' ? $phut_15_scores[0] : 'None' }}</td>
                                <td class="text-center">{{ $phut_15_scores[1] !== '' ? $phut_15_scores[1] : 'None' }}</td>
                                <td class="text-center">{{ $giua_ky_score !== null ? $giua_ky_score : 'None' }}</td>
                                <td class="text-center">{{ $cuoi_ky_score !== null ? $cuoi_ky_score : 'None' }}</td>
                                <td class="text-center">{{ $average_score !== null ? $average_score : 'None' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                @if($transcriptDetails->isEmpty())
                    <div class="alert alert-info mt-4">
                        Không có kết quả tìm kiếm
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý sự kiện khi người dùng thay đổi lựa chọn kỳ học, môn học, lớp và niên khóa
        var filters = ['semester', 'subject_id', 'class_id', 'school_year_id'];
        filters.forEach(function(filter) {
            document.getElementById(filter).addEventListener('change', function () {
                this.form.submit();
            });
        });
    });
</script>
