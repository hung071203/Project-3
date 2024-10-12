@extends('layout.masterStudentUser')

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
            <th>Test times</th>
            <th>Semester</th>
            <th>New Score</th>
            <th>Rate</th>
            <th></th>
        </tr>
        </thead>
        <!-- Data Rows -->
        <tbody>
        @foreach($reexamines as $reexamine)
            <tr>
                <td>{{ $reexamine->student_name }}</td>
                <td>{{ $reexamine->class_name }}_{{ $reexamine->sy_name }}</td>
                <td>{{ $reexamine->grade_name }}</td>
                <td>{{ $reexamine->subject_name }}</td>
                <td>
                    @if($reexamine -> exam_type == 0)
                        <div class="badge badge-success">1 times</div>
                    @elseif($reexamine -> exam_type == 1)
                        <div class="badge badge-primary">2 times</div>
                    @elseif($reexamine -> exam_type == 2)
                        <div class="badge badge-danger">Banned</div>
                    @elseif($reexamine -> exam_type == 3)
                        <div class="badge badge-warning">Exam Skipped</div>
                    @endif
                </td>
                <td>
                    @if($reexamine -> semester == 0)
                        <div class="badge badge-primary">Semester 1</div>
                    @elseif($reexamine -> semester == 1)
                        <div class="badge badge-info">Semester 2</div>
                    @elseif($reexamine -> semester == 2)
                        <div class="badge badge-warning">Extra Semester</div>
                    @endif
                </td>


                <td @if($reexamine -> new_score <= 5) class="text-danger"
                    @elseif($reexamine -> new_score >= 5.01) class="text-success"
                    @endif>
                    {{$reexamine->new_score ?? 'None'}}


                </td>

                <td @if($reexamine -> new_score < 5)
                    {{$reexamine->new_score ?? 'None'}}
                    <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>
                @elseif($reexamine -> new_score >= 5.01)
                    <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>
                    @endif</td>


            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

