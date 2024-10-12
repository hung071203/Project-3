@extends('layout.masterteacher')
@section('content')
    <form method="post" action="{{ route('transdetail.stored') }}">
        <div class="card">
            <div class="card-body">
                {{--                <div class=""><h2 class="text-left mb-4">Add Point for Student</h2></div>--}}
                @csrf
                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">



                            @foreach($transcripts as $transcript)
                                <input type="hidden" name="transcript_id" value="{{ $transcript->id }}">
                                <tr>
                                    <div class="row mb-4">
                                        <div class="col-md-3 offset-md-9">
                                            <tr>
                                                <label class="mdi mdi-account-badge-alert">Class: </label>
                                            </tr>
                                            <td>
                                                {{ $transcript->division->class->class_name }}_{{ $transcript->division->class->school_year->sy_name }}
                                            </td>
                                        </div>
                                    </div>
                                </tr>
                                <table class="table table-dark" >
                                    <tr>
                                        <th>Transcript Name</th>
                                        <th>Subject</th>
                                        <th>Semester</th>
                                        <th>Test Times</th>
                                    </tr>

                                    <div class="row mb-4">
                                        {{--                                        <label class="label">Transcript Name</label>--}}
                                        <td>
                                            {{ $transcript->transcript_name }}
                                        </td>

                                        {{--                                        <label class="label">Subject</label>--}}
                                        <td>
                                            {{ $transcript->division->subject->subject_name }}
                                        </td>

                                        {{--                                        <label class="label">Semester</label>--}}
                                        <td>
                                            @if($transcript->division -> semester == 0)
                                                <div class="badge badge-primary">Semester 1</div>
                                            @elseif($transcript->division -> semester == 1)
                                                <div class="badge badge-info">Semester 2</div>
                                            @elseif($transcript->division -> semester == 2)
                                                <div class="badge badge-warning">Extra Semester</div>
                                            @endif
                                        </td>


                                        {{--                                        <label class="label">Test Times</label>--}}
                                        <td>
                                            @if($transcript -> exam_type == 0)
                                                <div class="badge badge-success">1 Times</div>
                                            @elseif($transcript -> exam_type == 1)
                                                <div class="badge badge-primary">2 Times</div>
                                            @elseif($transcript -> exam_type == 2)
                                                <div class="badge badge-danger">Relearn</div>
                                                {{--                                            @elseif($transcript -> exam_type == 3)--}}
                                                {{--                                                <div class="badge badge-warning">Exam Skipped</div>--}}
                                            @endif
                                        </td>
                                    </div>
                                </table>
                            @endforeach
                            <table class="table table-striped">
                                <tr>
                                    <th>Student Name</th>
                                    <th>Tested</th>
                                    <th>Banned</th>
                                    <th>Skipped</th>
                                    <th>Score</th>
                                </tr>
                                @foreach($students as $student)
                                    <tr>

                                        <td>{{ $student->student_name }}</td>
                                        <td>
                                            <input type="checkbox" value="1" name="note_{{ $student->id }}"></td>
                                        <td><input type="checkbox" value="2" name="note_{{ $student->id }}"></td>
                                        <td><input type="checkbox" value="3" name="note_{{ $student->id }}">
                                        </td>
                                        <td class="input-group">
                                            <input type="text" name="score_{{ $student->id }}" class="form-control" placeholder="In range 10 (float true)">
                                        </td>
                                        <td> <input type="hidden" value=" {{ $student->id }}"></td>
                                    </tr>
                                @endforeach
                            </table>
                            <div class="text-right">
                                <button class="btn btn-outline-primary">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

