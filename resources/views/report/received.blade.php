@extends('layout.masterteacher')

@section('content')

    @csrf
    <table class="table table-striped">
        <!-- Headers -->
        <h1>Report Information</h1>
        <thead>

        <tr>
            <th>Student</th>
            <th>Class</th>
            <th>grade</th>
            <th>Subject</th>
            <th>Semester</th>
            <th>Old Score</th>
            <th>Rates</th>
            <th>Grading teacher</th>
            <th>Status</th>
            <th>Additional</th>
        </tr>
        </thead>
        <!-- Data Rows -->
        <tbody>
        @foreach($reports as $report)
            @if($report->status == 1 || $report->status == 3 && $report->teacher_name)
                <tr>
                    <td>{{ $report->student_name }}</td>
                    <td>{{ $report->class_name }}_{{ $report->sy_name }}</td>
                    <td>{{ $report->grade_name }}</td>
                    <td>{{ $report->subject_name }}</td>
                    <td>
                        @if($report->semester == 0)
                            <div class="badge badge-primary">Semester 1</div>
                        @elseif($report->semester == 1)
                            <div class="badge badge-info">Semester 2</div>
                        @elseif($report->semester == 2)
                            <div class="badge badge-warning">Extra Semester</div>
                        @endif
                    </td>
                    <td @if($report -> score <= 5) class="text-danger"
                        @elseif($report -> score >= 5.01) class="text-success"
                        @endif>
                        {{$report->score ?? 'None'}}


                    </td>
                    <td @if($report -> score < 5)
                        {{$report->score ?? 'None'}}
                        <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>
                    @elseif($report -> score >= 5.01)
                        <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>
                        @endif</td>
                    <td>{{ $report->teacher_name }}</td>
                        <td>
                            @if($report->status == 1)
                                <div class="badge badge-info">Wait for action</div>
                            @elseif($report->status == 3)
                                <div class="badge badge-success">Completed</div>
                            @endif
                        </td>
                        <td>
                            @if($report->message !== null)
                                <button class="btn btn-primary" onclick="showMessage('{{ $report->message }}')">Check</button>
                            @endif
                        </td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>

    <script>
        function showMessage(message) {
            alert("Message from Student: " + message);
        }
    </script>

@endsection
