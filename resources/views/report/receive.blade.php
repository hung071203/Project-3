@extends('layout.master')

@section('content')

    @csrf
    <table class="table table-striped">
        <!-- Headers -->
        <thead>
        <tr>
            <th>Student</th>
            <th>Class</th>
            <th>grade</th>
            <th>Subject</th>
            <th>Semester</th>
            <th>Grading teacher</th>
            <th>Status</th>
            <th>Admin Action</th>
        </tr>
        </thead>
        <!-- Data Rows -->
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td>{{ $report->student_name }}</td>
                <td>{{ $report->class_name }}_{{ $report->sy_name }}</td>
                <td>{{ $report->grade_name }}</td>
                <td>{{ $report->subject_name }}</td>
                <td>
                    @if($report -> semester == 0)
                        <div class="badge badge-primary">Semester 1</div>
                    @elseif($report -> semester == 1)
                        <div class="badge badge-info">Semester 2</div>
                    @elseif($report -> semester == 2)
                        <div class="badge badge-warning">Extra Semester</div>
                    @endif
                </td>
                <td>{{ $report->teacher_name }}</td>
                <td>
                    @if($report->status == 0)
                        <div class="badge badge-warning">Pending</div>
                    @elseif($report->status == 1)
                        <div class="badge badge-info">Accepted sent to teacher</div>
                    @elseif($report->status == 2)
                        <div class="badge badge-danger">Rejected</div>
                    @elseif($report->status == 3)
                        <div class="badge badge-success">Completed</div>
                    @endif
                </td>
                <td>
                    @if($report->status == 0)
                        <form action="{{ route('report.update', ['id' => $report->id]) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- This line overrides the method to PUT -->
                            <input type="hidden" name="transcriptdetail_id" value="{{ $report->transcriptdetail_id }}">
                            <input type="hidden" name="message" value="{{ $report->message }}">
                            <input type="hidden" name="status" value="1">
                            <!-- Buttons for updating the report -->
                            <button type="submit"  onclick="return confirmUpdate();" class=".edit-button btn btn-primary">Accept</button>
                        </form>
                        <form action="{{ route('report.update', ['id' => $report->id]) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- This line overrides the method to PUT -->
                            <input type="hidden" name="transcriptdetail_id" value="{{ $report->transcriptdetail_id }}">
                            <input type="hidden" name="message" value="{{ $report->message }}">
                            <input type="hidden" name="status" value="2">
                            <button type="submit" onclick="return confirmUpdate();" class=".edit-button btn btn-danger">Reject</button>
                        </form>

                    @endif

                </td>
                <script>
                    function confirmUpdate() {
                        // Sử dụng hộp thoại xác nhận
                        return confirm('Warning!!!This Action will change a status and it wont be change anymore. Are you sure with this');
                    }
                </script>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

