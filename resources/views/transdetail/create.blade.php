@extends('layout.masterteacher')
@section('content')
    <form method="post" action="{{ route('transdetail.store') }}">
        <div class="card">
            <div class="card-body">
                @csrf
                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">
                            <div class="row mb-4">
                                <div class="col-md-3 offset-md-9">
                                    <input type="file" id="fileInput" name="file" class="form-control">
                                </div>
                            </div>

                            @foreach($transcripts as $transcript)
                                <div class="row mb-4">
                                    <div class="col-md-3 offset-md-9">
                                        <a href="{{ route('transcripts.export', ['transcriptId' => $transcript->id]) }}" class="btn btn-primary">Tải xuống file nhập điểm</a>
                                    </div>
                                </div>
                                <input type="hidden" name="transcript_id" value="{{ $transcript->id }}">
                                <div class="row mb-4">
                                    <div class="col-md-3 offset-md-9">
                                        <label class="mdi mdi-account-badge-alert">Lớp: </label>
                                        <span>{{ $transcript->division->class->class_name }}_{{ $transcript->division->class->school_year->sy_name }}</span>
                                    </div>
                                </div>
                                <table class="table table-dark">
                                    <tr>
                                        <th>Tên Bảng Điểm</th>
                                        <th>Môn Học</th>
                                        <th>Hình thức kiểm tra</th>
                                        <th>Kỳ học</th>
                                    </tr>
                                    <tr>
                                        <td>{{ $transcript->transcript_name }}</td>
                                        <td>{{ $transcript->division->subject->subject_name }}</td>
                                        <td>
                                            @if($transcript->division->exam_type == 1)
                                                <div class="badge badge-success">Miệng, thường xuyên</div>
                                            @elseif($transcript->division->exam_type == 2)
                                                <div class="badge badge-info">15 phút</div>
                                            @elseif($transcript->division->exam_type == 3)
                                                <div class="badge badge-warning">1 Tiết</div>
                                            @elseif($transcript->division->exam_type == 4)
                                                <div class="badge badge-danger">Cuối kỳ</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($transcript->division->subject->semester == 1)
                                                <div class="badge badge-primary">Kỳ 1</div>
                                            @elseif($transcript->division->subject->semester == 2)
                                                <div class="badge badge-info">Kỳ 2</div>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            @endforeach

                            <table class="table table-striped">
                                <tr>
                                    <th>Tên Học Sinh</th>
                                    <th>Làm Bài</th>
                                    <th>Bị Cấm</th>
                                    <th>Vắng Mặt</th>
                                    <th>Điểm</th>
                                </tr>
                                @foreach($students as $student)
                                    <tr>
                                        <td>{{ $student->student_name }}</td>
                                        <td><input type="checkbox" value="1" name="note_{{ $student->id }}"></td>
                                        <td><input type="checkbox" value="2" name="note_{{ $student->id }}"></td>
                                        <td><input type="checkbox" value="3" name="note_{{ $student->id }}"></td>
                                        <td class="input-group">
                                            <input type="text" name="score_{{ $student->id }}" class="form-control score-input" placeholder="In range 10 (float true)">
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-right">
                                <button type="submit" class="btn btn-outline-primary">Thêm</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var fileInput = document.getElementById('fileInput');

        fileInput.addEventListener('change', function() {
            var file = fileInput.files[0];
            var reader = new FileReader();
            reader.onload = function(e) {
                var data = new Uint8Array(e.target.result);
                var workbook = XLSX.read(data, { type: 'array' });
                var firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                var rows = XLSX.utils.sheet_to_json(firstSheet, { header: 1 });

                var students = @json($students);

                rows.forEach(function(row, index) {
                    if (index === 0) return; // Skip the header row

                    var studentName = row[0];
                    var note = row[1];
                    var score = row[2];

                    var found = false;
                    students.forEach(function(student) {
                        if (student.student_name === studentName) {
                            var noteInput = document.querySelector(`input[name="note_${student.id}"][value="${note}"]`);
                            var scoreInput = document.querySelector(`input[name="score_${student.id}"]`);

                            if (noteInput) {
                                noteInput.checked = true;
                            }
                            if (scoreInput) {
                                scoreInput.value = score;
                                validateScore(scoreInput); // Validate score immediately
                            }
                            found = true;
                        }
                    });

                    if (!found) {
                        alert(`Học sinh "${studentName}" không tồn tại hoặc không có quyền nhập điểm.`);
                    }
                });
            };
            reader.readAsArrayBuffer(file);
        });

        // Handle checkboxes and score input logic
        var rows = document.querySelectorAll('tr');

        rows.forEach(function(row) {
            var checkboxes = row.querySelectorAll('input[type="checkbox"]');
            var scoreInput = row.querySelector('.score-input');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    checkboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });

                    if (checkbox.checked && (checkbox.value === '2' || checkbox.value === '3')) {
                        scoreInput.disabled = true;
                        scoreInput.value = '';
                    } else {
                        scoreInput.disabled = false;
                    }
                });
            });

            scoreInput.addEventListener('input', function() {
                validateScore(scoreInput);
            });
        });

        function validateScore(scoreInput) {
            var value = parseFloat(scoreInput.value);
            if (isNaN(value) || value < 0 || value > 10 || ![0, 0.25, 0.5, 0.75].includes(value)) {
                scoreInput.setCustomValidity('Điểm phải là số từ 0 đến 10 hoặc .25, .5, .75');
            } else {
                scoreInput.setCustomValidity('');
            }
        }
    });
</script>

