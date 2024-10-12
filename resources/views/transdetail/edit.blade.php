{{--@extends('layout.masterteacher')--}}

{{--@section('content')--}}
{{--    <form method="post" action="{{ route('transdetail.update', $transcript_id) }}">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}
{{--                                @foreach($transcripts as $transcript)--}}
{{--                                    <input type="hidden" name="transcript_id" value="{{ $transcript->id }}">--}}
{{--                                    <tr>--}}
{{--                                        <div class="row mb-4">--}}
{{--                                            <div class="col-md-3 offset-md-9">--}}
{{--                                                <tr>--}}
{{--                                                    <label class="mdi mdi-account-badge-alert">Class: </label>--}}
{{--                                                </tr>--}}
{{--                                                <td>--}}
{{--                                                    {{ $transcript->division->class->class_name }}_{{ $transcript->division->class->school_year->sy_name }}--}}
{{--                                                </td>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </tr>--}}
{{--                                    <table class="table table-dark" >--}}
{{--                                        <tr>--}}
{{--                                            <th>Transcript Name</th>--}}
{{--                                            <th>Subject</th>--}}
{{--                                            <th>Semester</th>--}}
{{--                                            <th>Test Times</th>--}}
{{--                                        </tr>--}}

{{--                                        <div class="row mb-4">--}}
{{--                                                                                    <label class="label">Transcript Name</label>--}}
{{--                                            <td>--}}
{{--                                                {{ $transcript->transcript_name }}--}}
{{--                                            </td>--}}

{{--                                                                                    <label class="label">Subject</label>--}}
{{--                                            <td>--}}
{{--                                                {{ $transcript->division->subject->subject_name }}--}}
{{--                                            </td>--}}

{{--                                                                                    <label class="label">Semester</label>--}}
{{--                                            <td>--}}
{{--                                                @if($transcript->division -> semester == 0)--}}
{{--                                                    <div class="badge badge-primary">Semester 1</div>--}}
{{--                                                @elseif($transcript->division -> semester == 1)--}}
{{--                                                    <div class="badge badge-info">Semester 2</div>--}}
{{--                                                @elseif($transcript->division -> semester == 2)--}}
{{--                                                    <div class="badge badge-warning">Extra Semester</div>--}}
{{--                                                @endif--}}
{{--                                            </td>--}}


{{--                                                                                    <label class="label">Test Times</label>--}}
{{--                                            <td>--}}
{{--                                                @if($transcript -> exam_type == 0)--}}
{{--                                                    <div class="badge badge-success">1 Times</div>--}}
{{--                                                @elseif($transcript -> exam_type == 1)--}}
{{--                                                    <div class="badge badge-primary">2 Times</div>--}}
{{--                                                @elseif($transcript -> exam_type == 2)--}}
{{--                                                    <div class="badge badge-danger">Relearn</div>--}}
{{--                                                                                                @elseif($transcript -> exam_type == 3)--}}
{{--                                                                                                    <div class="badge badge-warning">Exam Skipped</div>--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
{{--                                        </div>--}}
{{--                                    </table>--}}

{{--                                <table class="table table-striped">--}}
{{--                                    <tr>--}}
{{--                                        <th>Student Name</th>--}}
{{--                                        <th>Tested</th>--}}
{{--                                        <th>Banned</th>--}}
{{--                                        <th>Skipped</th>--}}
{{--                                        <th>Score</th>--}}
{{--                                    </tr>--}}
{{--                                    @foreach($students as $student)--}}
{{--                                        <tr>--}}

{{--                                            <td>{{ $student->student_name }}</td>--}}
{{--                                            <td>--}}
{{--                                                <input type="checkbox" value="1" name="note_{{ $student->id }}"></td>--}}
{{--                                            <td><input type="checkbox" value="2" name="note_{{ $student->id }}"></td>--}}
{{--                                            <td><input type="checkbox" value="3" name="note_{{ $student->id }}">--}}
{{--                                            </td>--}}
{{--                                            <td class="input-group">--}}
{{--                                                <input type="text" name="score_{{ $student->id }}"  class="form-control" placeholder="In range 10 (float true)">                                            </td>--}}
{{--                                             <input type="hidden" value=" {{ $student->id }}">--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                </table>--}}
{{--                                <div class="text-right">--}}
{{--                                    <button class="btn btn-outline-info">Update</button>--}}
{{--                                </div>--}}
{{--            @endforeach--}}
{{--    </form>--}}
{{--@endsection--}}
{{--<script>--}}
{{--    document.addEventListener('DOMContentLoaded', function() {--}}
{{--        var rows = document.querySelectorAll('tr');--}}

{{--        rows.forEach(function(row) {--}}
{{--            var checkboxes = row.querySelectorAll('input[type="checkbox"]');--}}
{{--            var scoreInput = row.querySelector('input[name^="score"]');--}}

{{--            checkboxes.forEach(function(checkbox) {--}}
{{--                checkbox.addEventListener('change', function() {--}}
{{--                    // Uncheck checkboxes in the same row--}}
{{--                    checkboxes.forEach(function(otherCheckbox) {--}}
{{--                        if (otherCheckbox !== checkbox) {--}}
{{--                            otherCheckbox.checked = false;--}}
{{--                        }--}}
{{--                    });--}}

{{--                    // Disable score input if checkbox with value 2 or 3 is checked in the same row--}}
{{--                    if (checkbox.checked && (checkbox.value === '2' || checkbox.value === '3')) {--}}
{{--                        scoreInput.disabled = true;--}}
{{--                        // Additional actions when checkbox with value 2 or 3 is checked in this row--}}
{{--                        // Add handling code here--}}
{{--                    } else {--}}
{{--                        scoreInput.disabled = false;--}}
{{--                        // Additional actions when checkbox with value 2 or 3 is not checked in this row--}}
{{--                        // Add handling code here--}}
{{--                    }--}}
{{--                });--}}
{{--            });--}}
{{--        });--}}
{{--    });--}}
{{--</script>--}}


{{--        <div class="card">--}}
{{--            <div class="card-body">--}}
{{--                <div class=""><h2 class="text-left mb-4">Edit Point for Student</h2></div>--}}
{{--                <table class="table table-striped">--}}
{{--                    <tr>--}}
{{--                        <td>Transcript:</td>--}}
{{--                        <td>--}}
{{--                            <select name="transcript_id" disabled class="input-group">--}}
{{--                                @foreach($transcripts as $transcript)--}}
{{--                                    <option value="{{ $transcript->id }}"--}}
{{--                                    @if($transcript->id == $transcript_detail->transcript_id)--}}
{{--                                        {{ 'selected' }}--}}
{{--                                        @endif--}}
{{--                                    >--}}
{{--                                        ({{ $transcript->division->subject->subject_name }})___({{ $transcript->class->class_name }}_{{ $transcript->class->school_year->sy_name }})--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Student:</td>--}}
{{--                        <td>--}}
{{--                            <select name="student_id" disabled  class="input-group">--}}
{{--                                @foreach($students as $student)--}}
{{--                                    <option value="{{ $student->id }}"--}}
{{--                                    @if($student->id == $transcript_detail->student_id)--}}
{{--                                        {{ 'selected' }}--}}
{{--                                        @endif--}}
{{--                                    >--}}
{{--                                        {{ $student->student_name }}___({{ $student->class->class_name }}_{{ $student->class->school_year->sy_name }})--}}
{{--                                    </option>--}}
{{--                                @endforeach--}}
{{--                            </select>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Test times:</td>--}}
{{--                        <td>--}}
{{--                            <select name="exam_type" class="input-group">--}}
{{--                                <option value="0" @if($transcript_detail->exam_type == 0) {{ 'selected' }} @endif>1 times</option>--}}
{{--                                <option value="1" @if($transcript_detail->exam_type == 1) {{ 'selected' }} @endif>2 times</option>--}}
{{--                                <option value="2" @if($transcript_detail->exam_type == 2) {{ 'selected' }} @endif>Banned</option>--}}
{{--                                <option value="3" @if($transcript_detail->exam_type == 3) {{ 'selected' }} @endif>Exam Skipped</option>--}}
{{--                            </select>--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                    <tr>--}}
{{--                        <td>Score:</td>--}}
{{--                        <td>--}}
{{--                            <input type="text" name="score" placeholder="In range 10(float true)" class="form-control" value="{{ $transcript_detail->score }}">--}}
{{--                        </td>--}}
{{--                    </tr>--}}
{{--                </table>--}}
{{--                <button type="submit" class="btn btn-outline-primary">Update</button>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        @endforeach--}}
{{--    </form>--}}
{{--@endsection--}}
@extends('layout.masterteacher')

@section('content')
    <form method="post" action="{{ route('transdetail.update', $id) }}">
        @csrf
        @method('PUT')

        @foreach($transcript_details as $transcript_detail)
{{--                                @foreach($transcripts as $transcript)--}}
{{--                                    <input type="hidden" name="transcript_id" value="{{ $transcript->id }}">--}}
{{--                                    <tr>--}}
{{--                                        <div class="row mb-4">--}}
{{--                                            <div class="col-md-3 offset-md-9">--}}
{{--                                                <tr>--}}
{{--                                                    <label class="mdi mdi-account-badge-alert">Class: </label>--}}
{{--                                                </tr>--}}
{{--                                                <td>--}}
{{--                                                    {{ $transcript->division->class->class_name }}_{{ $transcript->division->class->school_year->sy_name }}--}}
{{--                                                </td>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </tr>--}}
{{--                                    <table class="table table-dark" >--}}
{{--                                        <tr>--}}
{{--                                            <th>Transcript Name</th>--}}
{{--                                            <th>Subject</th>--}}
{{--                                            <th>Semester</th>--}}
{{--                                            <th>Test Times</th>--}}
{{--                                        </tr>--}}

{{--                                        <div class="row mb-4">--}}

{{--                                            <td>--}}
{{--                                                {{ $transcript->transcript_name }}--}}
{{--                                            </td>--}}


{{--                                            <td>--}}
{{--                                                {{ $transcript->division->subject->subject_name }}--}}
{{--                                            </td>--}}


{{--                                            <td>--}}
{{--                                                @if($transcript->division -> semester == 0)--}}
{{--                                                    <div class="badge badge-primary">Semester 1</div>--}}
{{--                                                @elseif($transcript->division -> semester == 1)--}}
{{--                                                    <div class="badge badge-info">Semester 2</div>--}}
{{--                                                @elseif($transcript->division -> semester == 2)--}}
{{--                                                    <div class="badge badge-warning">Extra Semester</div>--}}
{{--                                                @endif--}}
{{--                                            </td>--}}



{{--                                            <td>--}}
{{--                                                @if($transcript -> exam_type == 0)--}}
{{--                                                    <div class="badge badge-success">1 Times</div>--}}
{{--                                                @elseif($transcript -> exam_type == 1)--}}
{{--                                                    <div class="badge badge-primary">2 Times</div>--}}
{{--                                                @elseif($transcript -> exam_type == 2)--}}
{{--                                                    <div class="badge badge-danger">Relearn</div>--}}
{{--                                                                                                @elseif($transcript -> exam_type == 3)--}}
{{--                                                                                                    <div class="badge badge-warning">Exam Skipped</div>--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
{{--                                        </div>--}}
{{--                                    </table>--}}

                                <table class="table table-striped">
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Tested</th>
                                        <th>Banned</th>
                                        <th>Skipped</th>
                                        <th>Score</th>
                                    </tr>
                        <tr>

                            <td>
                                @php
                                    $studentName = ''; // Khởi tạo biến để lưu trữ tên sinh viên
                                @endphp

                                @foreach($students as $student)
                                    @if($student->id == $transcript_detail->student_id)
                                        @php
                                            $studentName = $student->student_name; // Lưu trữ tên sinh viên khi tìm thấy id phù hợp
                                        @endphp
                                        @break // Thoát khỏi vòng lặp sau khi tìm thấy sinh viên cần
                                    @endif
                                @endforeach

                                {{ $studentName }} <!-- Hiển thị tên sinh viên -->
                            </td>


                            <td>
                                                    <input type="checkbox" value="1" name="note" @if($transcript_detail->note == 1) checked @endif></td>
                                                <td><input type="checkbox" value="2" name="note" @if($transcript_detail->note == 2) checked @endif></td>
                                                <td><input type="checkbox" value="3" name="note" @if($transcript_detail->note == 3) checked @endif>
                                                </td>
                                <td>
                                    <input type="text" name="score" placeholder="In range 10(float true)" class="form-control" value="{{ $transcript_detail->score }}">
                                </td>
                            </tr>
                        </table>
                <div class="text-right">
                                                    <button class="btn btn-outline-info">Update</button>
                                                </div>

            @endforeach
{{--            @endforeach--}}
        </form>
    @endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var rows = document.querySelectorAll('tr');

        rows.forEach(function(row) {
            var checkboxes = row.querySelectorAll('input[type="checkbox"]');
            var scoreInput = row.querySelector('input[name^="score"]');

            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    // Uncheck checkboxes in the same row
                    checkboxes.forEach(function(otherCheckbox) {
                        if (otherCheckbox !== checkbox) {
                            otherCheckbox.checked = false;
                        }
                    });

                    // Disable score input if checkbox with value 2 or 3 is checked in the same row
                    if (checkbox.checked && (checkbox.value === '2' || checkbox.value === '3')) {
                        scoreInput.disabled = true;
                        scoreInput.value = ''; // Xóa giá trị trong input score
                        // Additional actions when checkbox with value 2 or 3 is checked in this row
                        // Add handling code here
                    } else {
                        scoreInput.disabled = false;
                        // Additional actions when checkbox with value 2 or 3 is not checked in this row
                        // Add handling code here
                    }
                });
            });
        });
    });

</script>
