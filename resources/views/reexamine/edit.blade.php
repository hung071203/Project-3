@extends('layout.masterteacher')

@section('content')
    <form method="post" action="{{ route('reexamine.update', $report_id) }}">
        @csrf
        @method('PUT')
        @foreach($reexamines as $reexamine)
            <div class="card">
                <div class="card-body">
                    <div class=""><h2 class="text-left mb-4">Edit Point for Student in Reexamine</h2></div>
                    <table class="table table-striped">
                        <div class="form-group">
                            <label class="label">Report</label>
                            <div >
                                <select name="report_id" class="input-group" disabled>
                                    @foreach($reports as $report)
                                        <option value="{{$report-> id}}" @if($report->id == $reexamine->report_id)
                                            {{ 'selected' }}
                                            @endif
                                        >
                                            {{$report->transcript_detail->student->student_name}}___
                                            {{$report->transcript_detail->student->class-> class_name}}_
                                            {{$report->transcript_detail->student->class-> school_year->sy_name}}___
                                            {{$report->transcript_detail->transcript->division-> subject->subject_name}}___

                                            @if($report->transcript_detail->transcript->division->  semester == 0)
                                                <div class="badge badge-primary">Semester 1</div>
                                            @elseif($report->transcript_detail->transcript->division->  semester == 1)
                                                <div class="badge badge-info">Semester 2</div>
                                            @elseif($report->transcript_detail->transcript->division->  semester == 2)
                                                <div class="badge badge-warning">Extra Semester</div>
                                            @endif
                                            {{--                    {{$division->subject->subject_name}}--}}
                                        </option>
                                    @endforeach
                                </select><br>

                                <div class="form-group">
                                    <label class="label">New Score</label><br>
                                    <div>
                                        <input type="text" name="new_score" class="form-control" placeholder="In range 10 (float true)" value="{{ $reexamine->new_score }}">
                                    </div>
                                </div>

                    <button type="submit" class="btn btn-outline-primary">Update</button>
                </div>
            </div>
        @endforeach
    </form>
@endsection
