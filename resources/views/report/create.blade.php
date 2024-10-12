@extends('layout.masterStudentUser')
@section('content')

    <div class="col-lg-15 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title">Report</h1>
                <form method="post" action="{{ route('report.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="transcriptdetail_id">Select Transcript Detail:</label>
                        <select class=" input-group" id="transcriptdetail_id" name="transcriptdetail_id">
                            @foreach($transcriptDetails as $transcript_detail)
                                <option value="{{ $transcript_detail->id }}">{{ $transcript_detail->student->student_name  }}___{{$transcript_detail->subject_name}}___  @if($transcript_detail -> semester == 0)
                                        <div class="badge badge-primary">Semester 1</div>
                                    @elseif($transcript_detail -> semester == 1)
                                        <div class="badge badge-info">Semester 2</div>
                                    @elseif($transcript_detail -> semester == 2)
                                        <div class="badge badge-warning">Extra Semester</div>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="message">Message:</label>
                        <textarea class="form-control" id="message" placeholder="Message can be null" name="message" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Report</button>
                </form>
            </div>
        </div>
    </div>

@endsection
