@extends('layout.masterStudentUser')
@section('content')

    <div class="col-lg-15 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                @if($transcriptDetails->isNotEmpty())
                    <input type="hidden" name="transcript_id" value="{{ $transcriptDetails->first()->transcript->id }}">
                    <div class="row mb-4">
                        <div class="col-md-3 offset-md-9">
                            <label class="mdi mdi-account-badge-alert">Lớp: </label>
                            {{ $transcriptDetails->first()->transcript->division->class->class_name }}_{{ $transcriptDetails->first()->transcript->division->class->school_year->sy_name }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div id="academicEvaluation" class="card">
                            <div class="card-body">
                                <h4 class="card-title">Đánh giá học lực</h4>
                                <p id="academicEvaluationResult">Chưa có đánh giá</p>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-3 offset-md-9">
                            <label class="label">Học Kỳ:</label>
                            <select id="semesterFilter" class="form-select">
                                <option value="all">Toàn bộ</option>
                                <option value="1">Kỳ 1</option>
                                <option value="2">Kỳ 2</option>
                            </select>
                        </div>
                    </div>

                    <!-- Detailed Transcript Table -->
                    <table id="detailedTranscriptTable" class="table table-striped">
                        <thead>
                        <tr>
                            <th rowspan="2">Môn học</th>
                            <th class="text-center" colspan="2">Thường xuyên</th>
                            <th class="text-center" colspan="2">15 phút</th>
                            <th class="text-center">1 tiết</th>
                            <th class="text-center">Cuối kỳ</th>
                            <th class="text-center">Trung bình môn</th>
                        </tr>
                        <tr>
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
                        @foreach($transcriptDetails->groupBy('subject_name') as $subject_name => $subject_details)
                            @foreach($subject_details->groupBy('semester') as $semester => $semester_details)
                                @php
                                    $thuong_xuyen_scores = ['', ''];
                                    $phut_15_scores = ['', ''];
                                    $giua_ky_score = null;
                                    $cuoi_ky_score = null;

                                    foreach ($semester_details as $detail) {
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
                                        $total_scores = $thuong_xuyen_scores[0] + $thuong_xuyen_scores[1] + $phut_15_scores[0] + $phut_15_scores[1] + (2 * $giua_ky_score) + (3 * $cuoi_ky_score);
                                        $average_score = $total_scores / 9;
                                    }

                                    $average_display = $average_score !== null ? number_format($average_score, 2) : 'Không thể tính toán do thiếu số liệu';
                                    $cuoi_ky_display = $cuoi_ky_score !== null ? $cuoi_ky_score : 'Chưa có điểm';
                                @endphp
                                <tr class="semester{{ $semester }}">
                                    <td>{{ $subject_name }}</td>
                                    <td class="text-center">{{ $thuong_xuyen_scores[0] !== '' ? $thuong_xuyen_scores[0] : 'None' }}</td>
                                    <td class="text-center">{{ $thuong_xuyen_scores[1] !== '' ? $thuong_xuyen_scores[1] : 'None' }}</td>
                                    <td class="text-center">{{ $phut_15_scores[0] !== '' ? $phut_15_scores[0] : 'None' }}</td>
                                    <td class="text-center">{{ $phut_15_scores[1] !== '' ? $phut_15_scores[1] : 'None' }}</td>
                                    <td class="text-center">{{ $giua_ky_score !== null ? $giua_ky_score : 'None' }}</td>
                                    <td class="text-center">{{ $cuoi_ky_display }}</td>
                                    <td class="text-center">{{ $average_display }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>

                    <!-- Summary Table -->
                    <table id="summaryTable" class="table table-striped" style="display: none;">
                        <thead>
                        <tr>
                            <th>Môn học</th>
                            <th>Điểm trung bình kỳ 1</th>
                            <th>Điểm trung bình kỳ 2</th>
                            <th>Điểm tổng kết</th>
                        </tr>
                        </thead>
                        <tbody id="summaryTableBody">
                        <!-- Summary rows will be dynamically added here -->
                        </tbody>
                    </table>

                @endif

                @if(count($transcriptDetails) === 0)
                    <div class="alert alert-info">
                        Không có kết quả tìm kiếm
                    </div>
                @endif
            </div>
        </div>
    </div>

@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Lựa chọn mặc định khi trang được tải là 'all' (Toàn bộ)
        var selectedSemester = 'all';
        var detailedTable = document.getElementById('detailedTranscriptTable');
        var summaryTable = document.getElementById('summaryTable');

        // Hiển thị bảng summaryTable và ẩn bảng detailedTranscriptTable
        detailedTable.style.display = 'none';
        summaryTable.style.display = 'table';

        // Clear previous rows in summary table
        var summaryTableBody = document.getElementById('summaryTableBody');
        summaryTableBody.innerHTML = '';

        // Iterate over subjects to populate summary table
        var subjects = {};
        var rows = document.getElementById('transcriptDetailsBody').getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var subject = row.getElementsByTagName('td')[0].textContent.trim();
            var average = parseFloat(row.getElementsByTagName('td')[7].textContent.trim());
            var semesterClass = row.classList.contains('semester1') || row.classList.contains('semester2');

            if (semesterClass) {
                if (!subjects.hasOwnProperty(subject)) {
                    subjects[subject] = { 'average1': null, 'average2': null };
                }

                if (row.classList.contains('semester1')) {
                    subjects[subject]['average1'] = average;
                }

                if (row.classList.contains('semester2')) {
                    subjects[subject]['average2'] = average;
                }
            }
        }

        // Add rows to summary table
        for (var subject in subjects) {
            if (subjects.hasOwnProperty(subject)) {
                var average1 = subjects[subject]['average1'];
                var average2 = subjects[subject]['average2'];
                var finalAverage = 'Không thể tính toán';

                if (average1 !== null && average2 !== null) {
                    finalAverage = ((average1 + (2 * average2)) / 3).toFixed(1); // Round to 1 decimal place
                }

                var newRow = document.createElement('tr');
                newRow.innerHTML = '<td>' + subject + '</td>' +
                    '<td>' + (average1 !== null ? average1.toFixed(1) : 'N/A') + '</td>' +
                    '<td>' + (average2 !== null ? average2.toFixed(1) : 'N/A') + '</td>' +
                    '<td>' + finalAverage + '</td>';

                summaryTableBody.appendChild(newRow);
            }
        }

        // Xử lý sự kiện khi người dùng thay đổi lựa chọn kỳ học
        document.getElementById('semesterFilter').addEventListener('change', function () {
            selectedSemester = this.value;

            if (selectedSemester === 'all') {
                detailedTable.style.display = 'none';
                summaryTable.style.display = 'table';

                // Clear previous rows in summary table
                summaryTableBody.innerHTML = '';

                // Iterate over subjects to populate summary table
                var subjects = {};
                var rows = document.getElementById('transcriptDetailsBody').getElementsByTagName('tr');
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var subject = row.getElementsByTagName('td')[0].textContent.trim();
                    var average = parseFloat(row.getElementsByTagName('td')[7].textContent.trim());
                    var semesterClass = row.classList.contains('semester1') || row.classList.contains('semester2');

                    if (semesterClass) {
                        if (!subjects.hasOwnProperty(subject)) {
                            subjects[subject] = { 'average1': null, 'average2': null };
                        }

                        if (row.classList.contains('semester1')) {
                            subjects[subject]['average1'] = average;
                        }

                        if (row.classList.contains('semester2')) {
                            subjects[subject]['average2'] = average;
                        }
                    }
                }

                // Add rows to summary table
                for (var subject in subjects) {
                    if (subjects.hasOwnProperty(subject)) {
                        var average1 = subjects[subject]['average1'];
                        var average2 = subjects[subject]['average2'];
                        var finalAverage = 'Không thể tính toán';

                        if (average1 !== null && average2 !== null) {
                            finalAverage = ((average1 + (2 * average2)) / 3).toFixed(1); // Round to 1 decimal place
                        }

                        var newRow = document.createElement('tr');
                        newRow.innerHTML = '<td>' + subject + '</td>' +
                            '<td>' + (average1 !== null ? average1.toFixed(1) : 'N/A') + '</td>' +
                            '<td>' + (average2 !== null ? average2.toFixed(1) : 'N/A') + '</td>' +
                            '<td>' + finalAverage + '</td>';

                        summaryTableBody.appendChild(newRow);
                    }
                }
            } else {
                detailedTable.style.display = 'table';
                summaryTable.style.display = 'none';

                // Show only relevant semester rows
                var rows = document.getElementById('transcriptDetailsBody').getElementsByTagName('tr');
                for (var i = 0; i < rows.length; i++) {
                    var row = rows[i];
                    var semesterClass = row.classList.contains('semester' + selectedSemester) || selectedSemester === 'all';

                    if (semesterClass) {
                        row.style.display = '';

                        // Round average score for semester 1 and semester 2 to 1 decimal place
                        var averageColumns = row.getElementsByTagName('td');
                        var semesterClass1 = row.classList.contains('semester1');
                        var semesterClass2 = row.classList.contains('semester2');

                        if (semesterClass1 || semesterClass2) {
                            var averageIndex = 7; // Index of average_display column
                            var averageValue = parseFloat(averageColumns[averageIndex].textContent.trim());
                            if (!isNaN(averageValue)) {
                                averageColumns[averageIndex].textContent = averageValue.toFixed(1);
                            }
                        }
                    } else {
                        row.style.display = 'none';
                    }
                }
            }
        });
    });

    // Cập nhật logic để tính toán và hiển thị đánh giá học lực
    function updateAcademicEvaluation() {
        var totalAverage = 0;
        var count = 0;

        var rows = document.getElementById('transcriptDetailsBody').getElementsByTagName('tr');
        for (var i = 0; i < rows.length; i++) {
            var row = rows[i];
            var semesterClass = row.classList.contains('semester1') || row.classList.contains('semester2');
            if (semesterClass) {
                var average = parseFloat(row.getElementsByTagName('td')[7].textContent.trim());
                if (!isNaN(average)) {
                    totalAverage += average;
                    count++;
                }
            }
        }

        var overallAverage = count > 0 ? totalAverage / count : null;
        var academicEvaluationResult = document.getElementById('academicEvaluationResult');
        if (overallAverage !== null) {
            // Dựa vào điểm tổng kết, bạn có thể đưa ra các đánh giá khác nhau tại đây
            if (overallAverage >= 8.0 && !overallAverage < 6.5) {
                academicEvaluationResult.textContent = 'Giỏi';
            } else if (overallAverage >= 6.5 && !overallAverage < 5) {
                academicEvaluationResult.textContent = 'Khá';
            } else if (overallAverage >= 5.0 && !overallAverage < 3.5) {
                academicEvaluationResult.textContent = 'Trung bình';
            } else if (overallAverage >= 3.5 && !overallAverage < 2.0) {
                academicEvaluationResult.textContent = 'Yếu';
            } else {
                academicEvaluationResult.textContent = 'Kém';
            }

        } else {
            academicEvaluationResult.textContent = 'Chưa có đánh giá';
        }
    }

    // Gọi hàm updateAcademicEvaluation khi trang được tải và khi người dùng thay đổi lựa chọn học kỳ
    document.addEventListener('DOMContentLoaded', function () {
        updateAcademicEvaluation();

        document.getElementById('semesterFilter').addEventListener('change', function () {
            // Xử lý khi người dùng thay đổi học kỳ
            // Cập nhật lại đánh giá học lực sau khi lựa chọn học kỳ thay đổi
            updateAcademicEvaluation();
        });
    });


</script>

{{--@extends('layout.masterStudentUser')--}}
{{--@section('content')--}}

{{--    <div class="col-lg-15 grid-margin stretch-card">--}}
{{--        <div class="card">--}}
{{--            <div class="card-body">--}}
{{--                @if($transcriptDetails->isNotEmpty())--}}
{{--                    <input type="hidden" name="transcript_id" value="{{ $transcriptDetails->first()->transcript->id }}">--}}
{{--                    <div class="row mb-4">--}}
{{--                        <div class="col-md-3 offset-md-9">--}}
{{--                            <label class="mdi mdi-account-badge-alert">Lớp: </label>--}}
{{--                            {{ $transcriptDetails->first()->transcript->division->class->class_name }}_{{ $transcriptDetails->first()->transcript->division->class->school_year->sy_name }}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row mb-4">--}}
{{--                        <div class="col-md-4 offset-md-7">--}}
{{--                            <label class="label">Học Kỳ:</label>--}}
{{--                            <select id="semesterFilter" class="form-select">--}}
{{--                                <option value="all">Toàn bộ</option>--}}
{{--                                <option value="1">Kỳ 1</option>--}}
{{--                                <option value="2">Kỳ 2</option>--}}
{{--                            </select>--}}
{{--                        </div>--}}
{{--                        <div class="col-md-5">--}}
{{--                            <label class="label">Đánh giá học lực tổng quát:</label>--}}
{{--                            <span id="overallAssessment">Chưa có đánh giá</span>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <!-- Detailed Transcript Table -->--}}
{{--                    <table id="detailedTranscriptTable" class="table table-striped">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th rowspan="2">Môn học</th>--}}
{{--                            <th class="text-center" colspan="2">Thường xuyên</th>--}}
{{--                            <th class="text-center" colspan="2">15 phút</th>--}}
{{--                            <th class="text-center">1 tiết</th>--}}
{{--                            <th class="text-center">Cuối kỳ</th>--}}
{{--                            <th class="text-center">Trung bình môn</th>--}}
{{--                            <th class="text-center">Đánh giá</th> <!-- New column for assessment -->--}}
{{--                        </tr>--}}
{{--                        <tr>--}}
{{--                            <th class="text-center">Điểm 1</th>--}}
{{--                            <th class="text-center">Điểm 2</th>--}}
{{--                            <th class="text-center">Điểm 1</th>--}}
{{--                            <th class="text-center">Điểm 2</th>--}}
{{--                            <th class="text-center">Điểm kiểm tra</th>--}}
{{--                            <th class="text-center">Điểm thi</th>--}}
{{--                            <th class="text-center">Điểm trung bình</th>--}}
{{--                            --}}{{--                            <th class="text-center">Đánh giá học lực</th> <!-- New column for assessment -->--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody id="transcriptDetailsBody">--}}
{{--                        @foreach($transcriptDetails->groupBy('subject_name') as $subject_name => $subject_details)--}}
{{--                            @foreach($subject_details->groupBy('semester') as $semester => $semester_details)--}}
{{--                                @php--}}
{{--                                    $thuong_xuyen_scores = ['', ''];--}}
{{--                                    $phut_15_scores = ['', ''];--}}
{{--                                    $giua_ky_score = null;--}}
{{--                                    $cuoi_ky_score = null;--}}

{{--                                    foreach ($semester_details as $detail) {--}}
{{--                                        if ($detail->exam_type == 1) {--}}
{{--                                            if ($thuong_xuyen_scores[0] === '') {--}}
{{--                                                $thuong_xuyen_scores[0] = $detail->score;--}}
{{--                                            } else {--}}
{{--                                                $thuong_xuyen_scores[1] = $detail->score;--}}
{{--                                            }--}}
{{--                                        }--}}
{{--                                        if ($detail->exam_type == 2) {--}}
{{--                                            if ($phut_15_scores[0] === '') {--}}
{{--                                                $phut_15_scores[0] = $detail->score;--}}
{{--                                            } else {--}}
{{--                                                $phut_15_scores[1] = $detail->score;--}}
{{--                                            }--}}
{{--                                        }--}}
{{--                                        if ($detail->exam_type == 3) {--}}
{{--                                            $giua_ky_score = $detail->score;--}}
{{--                                        }--}}
{{--                                        if ($detail->exam_type == 4) {--}}
{{--                                            $cuoi_ky_score = $detail->score;--}}
{{--                                        }--}}
{{--                                    }--}}

{{--                                    $average_score = null;--}}
{{--                                    if ($thuong_xuyen_scores[0] !== '' && $thuong_xuyen_scores[1] !== '' && $phut_15_scores[0] !== '' && $phut_15_scores[1] !== '' && $giua_ky_score !== null && $cuoi_ky_score !== null) {--}}
{{--                                        $total_scores = $thuong_xuyen_scores[0] + $thuong_xuyen_scores[1] + $phut_15_scores[0] + $phut_15_scores[1] + (2 * $giua_ky_score) + (3 * $cuoi_ky_score);--}}
{{--                                        $average_score = $total_scores / 9;--}}
{{--                                    }--}}

{{--                                    $average_display = $average_score !== null ? number_format($average_score, 1) : 'Không thể tính toán do thiếu số liệu';--}}
{{--                                    $cuoi_ky_display = $cuoi_ky_score !== null ? $cuoi_ky_score : 'Chưa có điểm';--}}

{{--                                    $assessment = 'Không thể tính toán';--}}
{{--                                    if ($average_score !== null) {--}}
{{--                                        if ($average_score >= 8) {--}}
{{--                                            $assessment = 'Tốt';--}}
{{--                                        } elseif ($average_score >= 6.5) {--}}
{{--                                            $assessment = 'Khá';--}}
{{--                                        } elseif ($average_score >= 5) {--}}
{{--                                            $assessment = 'Đạt';--}}
{{--                                        } else {--}}
{{--                                            $assessment = 'Chưa đạt';--}}
{{--                                        }--}}
{{--                                    }--}}
{{--                                @endphp--}}
{{--                                <tr class="semester{{ $semester }}">--}}
{{--                                    <td>{{ $subject_name }}</td>--}}
{{--                                    <td class="text-center">{{ $thuong_xuyen_scores[0] !== '' ? $thuong_xuyen_scores[0] : 'None' }}</td>--}}
{{--                                    <td class="text-center">{{ $thuong_xuyen_scores[1] !== '' ? $thuong_xuyen_scores[1] : 'None' }}</td>--}}
{{--                                    <td class="text-center">{{ $phut_15_scores[0] !== '' ? $phut_15_scores[0] : 'None' }}</td>--}}
{{--                                    <td class="text-center">{{ $phut_15_scores[1] !== '' ? $phut_15_scores[1] : 'None' }}</td>--}}
{{--                                    <td class="text-center">{{ $giua_ky_score !== null ? $giua_ky_score : 'None' }}</td>--}}
{{--                                    <td class="text-center">{{ $cuoi_ky_display }}</td>--}}
{{--                                    <td class="text-center">{{ $average_display }}</td>--}}
{{--                                    --}}{{--                                    <td class="text-center">{{ $assessment }}</td> <!-- New column for assessment -->--}}
{{--                                </tr>--}}
{{--                            @endforeach--}}
{{--                        @endforeach--}}
{{--                        </tbody>--}}
{{--                    </table>--}}

{{--                    <!-- Summary Table -->--}}
{{--                    <table id="summaryTable" class="table table-striped" style="display: none;">--}}
{{--                        <thead>--}}
{{--                        <tr>--}}
{{--                            <th>Môn học</th>--}}
{{--                            <th>Điểm trung bình kỳ 1</th>--}}
{{--                            <th>Điểm trung bình kỳ 2</th>--}}
{{--                            <th>Điểm tổng kết</th>--}}
{{--                            <th>Đánh giá</th> <!-- New column for assessment -->--}}
{{--                        </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody id="summaryTableBody">--}}
{{--                        <!-- Summary rows will be dynamically added here -->--}}
{{--                        </tbody>--}}
{{--                    </table>--}}

{{--                @endif--}}

{{--                @if(count($transcriptDetails) === 0)--}}
{{--                    <div class="alert alert-info">--}}
{{--                        Không có kết quả tìm kiếm--}}
{{--                    </div>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--@endsection--}}

{{--<script>--}}
{{--    document.addEventListener('DOMContentLoaded', function () {--}}
{{--        // Default selection when the page loads is 'all' (Toàn bộ)--}}
{{--        var selectedSemester = 'all';--}}
{{--        var detailedTable = document.getElementById('detailedTranscriptTable');--}}
{{--        var summaryTable = document.getElementById('summaryTable');--}}

{{--        // Show summaryTable and hide detailedTranscriptTable initially--}}
{{--        detailedTable.style.display = 'none';--}}
{{--        summaryTable.style.display = 'table';--}}

{{--        // Clear previous rows in summary table--}}
{{--        var summaryTableBody = document.getElementById('summaryTableBody');--}}
{{--        summaryTableBody.innerHTML = '';--}}

{{--        // Iterate over subjects to populate summary table--}}
{{--        var subjects = {};--}}
{{--        var rows = document.getElementById('transcriptDetailsBody').getElementsByTagName('tr');--}}
{{--        for (var i = 0; i < rows.length; i++) {--}}
{{--            var row = rows[i];--}}
{{--            var subject = row.getElementsByTagName('td')[0].textContent.trim();--}}
{{--            var average = parseFloat(row.getElementsByTagName('td')[7].textContent.trim());--}}
{{--            // var assessment = row.getElementsByTagName('td')[8].textContent.trim(); // New column for assessment--}}
{{--            var semesterClass = row.className.match(/semester(\d+)/);--}}
{{--            var semester = semesterClass ? semesterClass[1] : 'unknown';--}}
{{--            if (!subjects[subject]) {--}}
{{--                subjects[subject] = { semester1: null, semester2: null };--}}
{{--            }--}}
{{--            // if (semester === '1') {--}}
{{--            //     subjects[subject].semester1 = { average: average, assessment: assessment }; // Update assessment--}}
{{--            // } else if (semester === '2') {--}}
{{--            //     subjects[subject].semester2 = { average: average, assessment: assessment }; // Update assessment--}}
{{--            // }--}}
{{--        }--}}

{{--        // Add rows to summary table--}}
{{--        for (var subject in subjects) {--}}
{{--            if (subjects.hasOwnProperty(subject)) {--}}
{{--                var semester1 = subjects[subject].semester1 ? subjects[subject].semester1.average : 'Chưa có điểm';--}}
{{--                var semester2 = subjects[subject].semester2 ? subjects[subject].semester2.average : 'Chưa có điểm';--}}
{{--                var totalAverage = subjects[subject].semester1 && subjects[subject].semester2 ? (subjects[subject].semester1.average + subjects[subject].semester2.average) / 2 : 'Không thể tính toán';--}}
{{--                var assessment = 'Không thể tính toán'; // Initialize assessment--}}
{{--                if (totalAverage !== 'Không thể tính toán') {--}}
{{--                    if (totalAverage >= 8) {--}}
{{--                        assessment = 'Tốt';--}}
{{--                    } else if (totalAverage >= 6.5) {--}}
{{--                        assessment = 'Khá';--}}
{{--                    } else if (totalAverage >= 5) {--}}
{{--                        assessment = 'Đạt';--}}
{{--                    } else {--}}
{{--                        assessment = 'Chưa đạt';--}}
{{--                    }--}}
{{--                }--}}

{{--                var row = document.createElement('tr');--}}
{{--                row.innerHTML = '<td>' + subject + '</td>' +--}}
{{--                    '<td>' + semester1 + '</td>' +--}}
{{--                    '<td>' + semester2 + '</td>' +--}}
{{--                    '<td>' + (typeof totalAverage === 'number' ? totalAverage.toFixed(1) : totalAverage) + '</td>' +--}}
{{--                    '<td class="assessment-column">' + assessment + '</td>'; // New column for assessment--}}
{{--                summaryTableBody.appendChild(row);--}}
{{--            }--}}
{{--        }--}}

{{--        // Calculate overall assessment--}}
{{--        var overallAssessment = 'Chưa có đánh giá';--}}
{{--        var totalSubjects = Object.keys(subjects).length;--}}
{{--        var goodCount = 0, fairCount = 0, passCount = 0, failCount = 0;--}}

{{--        for (var subject in subjects) {--}}
{{--            if (subjects.hasOwnProperty(subject)) {--}}
{{--                var subjectAssessment = subjects[subject].semester2 ? subjects[subject].semester2.assessment : 'Không thể tính toán';--}}
{{--                if (subjectAssessment === 'Tốt') {--}}
{{--                    goodCount++;--}}
{{--                } else if (subjectAssessment === 'Khá') {--}}
{{--                    fairCount++;--}}
{{--                } else if (subjectAssessment === 'Đạt') {--}}
{{--                    passCount++;--}}
{{--                } else if (subjectAssessment === 'Chưa đạt') {--}}
{{--                    failCount++;--}}
{{--                }--}}
{{--            }--}}
{{--        }--}}

{{--        // Determine overall assessment based on the provided criteria--}}
{{--        if (goodCount > 0 && fairCount >= totalSubjects - 1 && failCount === 0) {--}}
{{--            overallAssessment = 'Tốt';--}}
{{--        } else if (fairCount >= totalSubjects && failCount === 0) {--}}
{{--            overallAssessment = 'Khá';--}}
{{--        } else if (passCount >= totalSubjects - 1 && failCount <= 1) {--}}
{{--            overallAssessment = 'Đạt';--}}
{{--        } else if (failCount > 1) {--}}
{{--            overallAssessment = 'Chưa đạt';--}}
{{--        }--}}

{{--        document.getElementById('overallAssessment').textContent = overallAssessment;--}}

{{--        document.getElementById('semesterFilter').addEventListener('change', function () {--}}
{{--            selectedSemester = this.value;--}}

{{--            if (selectedSemester === 'all') {--}}
{{--                summaryTable.style.display = 'table';--}}
{{--                detailedTable.style.display = 'none';--}}

{{--                // Hide assessment column--}}
{{--                var assessmentColumns = document.getElementsByClassName('assessment-column');--}}
{{--                for (var i = 0; i < assessmentColumns.length; i++) {--}}
{{--                    assessmentColumns[i].style.display = 'none';--}}
{{--                }--}}
{{--            } else {--}}
{{--                summaryTable.style.display = 'none';--}}
{{--                detailedTable.style.display = 'table';--}}
{{--                var rows = detailedTable.getElementsByTagName('tbody')[0].getElementsByTagName('tr');--}}
{{--                for (var i = 0; i < rows.length; i++) {--}}
{{--                    if (rows[i].classList.contains('semester' + selectedSemester)) {--}}
{{--                        rows[i].style.display = '';--}}
{{--                    } else {--}}
{{--                        rows[i].style.display = 'none';--}}
{{--                    }--}}
{{--                }--}}

{{--                // Show assessment column--}}
{{--                // var assessmentColumns = document.getElementsByClassName('assessment-column');--}}
{{--                // for (var i = 0; i < assessmentColumns.length; i++) {--}}
{{--                //     assessmentColumns[i].style.display = '';--}}
{{--                // }--}}
{{--            }--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}
