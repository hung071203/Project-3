@extends('layout.masterteacher')
@section('content')
    <form method="post" action="{{route('transcript.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($transcripts as $transcript)
            Transcript Name: <input type="text" name="transcript_name" value="{{ $transcript->transcript_name }}"><br>
            Division Information: <select name="division_id">
                @foreach($divisions as $division)
                    <option value="{{$division->id}}"
                    @if($division->id == $transcript->division_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$division->division_name}}_{{$division->subject->subject_name}}
                    </option>
                @endforeach
            </select><br>
            Semester:
            <select name="semester" class="input-group">
                <option value="0" @if($transcript->semester == 0) {{ 'selected' }} @endif>Semester 1</option>
                <option value="1" @if($transcript->semester == 1) {{ 'selected' }} @endif>Semester 2</option>
                <option value="2" @if($transcript->semester == 2) {{ 'selected' }} @endif>Extra Semester</option>
            </select><br>
            Subject: <select name="subject_id" class="input-group">
                @foreach($subjects as $subject)
                    <option value="{{$subject->id}}"
                    @if($subject->id == $transcript->subject_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$subject->subject_name}}
                    </option>
                @endforeach
            </select><br>
            Class: <select name="class_id" class="input-group">
                @foreach($classes as $class)
                    <option value="{{$class->id}}"
                    @if($class->id == $transcript->class_id)
                        {{'selected'}}
                        @endif
                    >
                        {{$class->class_name}} - R{{$class -> school_year_id}}
                    </option>
                @endforeach
            </select><br>
        @endforeach
        <button>Update</button>
    </form>
@endsection
