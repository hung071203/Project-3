@extends('layout.masterStudentUser')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger" id="myText">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="myText">
            {{ session('success') }}
        </div>
    @endif
    <div class="col-lg-15 grid-margin stretch-card">

        <div class="card">
            <div class="card-body">
                <h1 class="card-title"></h1>
                @if(count($reports) === 0)
                    <div class="alert alert-info" >
                        No Results Found
                    </div>
                @else
                @endif
                <td>
                    <a href="
                    {{ route('report.create')}}
                    " class="btn btn-primary">Report to admin</a>
                </td>
                <table class="table table-striped " >

                    <tr>
{{--                        <th>Student_Name</th>--}}
{{--                        <th>Class</th>--}}
{{--                        <th>grade</th>--}}
                        <th>Subject</th>
{{--                        <th>Test time</th>--}}
                        <th>Semester</th>
                        <th>Status</th>
{{--                        <th>Score</th>--}}
{{--                        <th>Rates</th>--}}
                    </tr>
                    @foreach($reports as $report)
                        <tr>
{{--                            <td>--}}
{{--                                {{$report->student->student_name}}--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {{$report->class_name}}_{{$report->sy_name}}--}}
{{--                            </td>--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                {{$report->grade_name}}--}}
{{--                            </td>--}}

                            <td>
                                {{$report->subject_name}}
                            </td>
{{--                            <td>--}}
{{--                                @if($report -> exam_type == 0)--}}
{{--                                    <div class="badge badge-success">1 times</div>--}}
{{--                                @elseif($report -> exam_type == 1)--}}
{{--                                    <div class="badge badge-primary">2 times</div>--}}
{{--                                @elseif($report -> exam_type == 2)--}}
{{--                                    <div class="badge badge-danger">Banned</div>--}}
{{--                                @elseif($report -> exam_type == 3)--}}
{{--                                    <div class="badge badge-warning">Exam Skipped</div>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            <td>
                                @if($report -> semester == 0)
                                    <div class="badge badge-primary">Semester 1</div>
                                @elseif($report -> semester == 1)
                                    <div class="badge badge-info">Semester 2</div>
                                @elseif($report -> semester == 2)
                                    <div class="badge badge-warning">Extra Semester</div>
                                @endif
                            </td>
                            <td>
                                @if($report->status == 0)
                                    <div class="badge badge-warning">Pending</div>
                                @elseif($report->status == 1)
                                    <div class="badge badge-info">Accepted Wait For teacher Action</div>
                                @elseif($report->status == 2)
                                    <div class="badge badge-danger">Rejected</div>
                                @elseif($report->status == 3)
                                    <div class="badge badge-success">Completed</div>
                                @endif
                            </td>
{{--                            <td @if($report -> score < 5) class="text-danger"--}}
{{--                                @elseif($report -> score >= 5.01) class="text-success"--}}
{{--                                @endif>--}}
{{--                                {{$report->score ?? 'None'}}--}}


{{--                            </td>--}}
{{--                            <td @if($report -> score < 5)--}}
{{--                                {{$report->score ?? 'None'}}--}}
{{--                                <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>--}}
{{--                            @elseif($report -> score >= 5.01)--}}
{{--                                <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>--}}
{{--                                @endif--}}
{{--                                </td>--}}

                        </tr>
                    @endforeach
                </table>
@endsection
