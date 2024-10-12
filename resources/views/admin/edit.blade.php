@extends('layout.master')
@section('content')
    <form method="post" action="{{route('admin.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($admins as $admin)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Edit Admin</h2></div>
                    <table class="table table-striped " >
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Username</label>
                                        <div class="input-group">
                                            <input type="text" name="username" class="form-control" placeholder="Username" value="{{$admin->username}}" disabled>
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Password</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" placeholder="Password" value="{{$admin->password}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                    <button>Update</button>
    </form>
@endsection

{{--@extends('layout.master')--}}
{{--@section('content')--}}
{{--    <form method="post" action="{{route('student.update', $student)}}">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}

{{--            Student Name: <input type="text" name="student_name" value="{{$student->student_name}}"><br>--}}
{{--            Email: <input type="text" name="email" value="{{$student->email}}"><br>--}}
{{--            Phone: <input type="text" name="phone" value="{{$student->phone}}"><br>--}}
{{--            Password: <input type="text" name="password" value="{{$student->password}}"><br>--}}
{{--            Class: <select name="class_id">--}}
{{--                @foreach($classes as $class)--}}
{{--                    <option value="{{$class-> id}}"--}}
{{--                    @if($class->id == $student->class_id)--}}
{{--                        {{'selected'}}--}}
{{--                        @endif--}}
{{--                    >--}}
{{--                        {{$class->class_name}}--}}
{{--                    </option>--}}
{{--                @endforeach--}}
{{--            </select><br>--}}
{{--        <button class="btn btn-outline-info">Update</button>--}}
{{--    </form>--}}
{{--@endsection--}}
