@extends('layout.master')
@section('content')
    <form method="post" action="{{route('admin.store')}}">
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Create Admin</h2></div>
                <table class="table table-striped " >

                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Username</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Username" name="username">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" placeholder="Password" name="password">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>


                    <button>Add</button>
    </form>
@endsection

{{--@extends('layout.master')--}}
{{--@section('content')--}}
{{--    <form method="post" action="{{route('student.store')}}">--}}
{{--        @csrf--}}
{{--        Student Name: <input type="text" name="student_name"><br>--}}
{{--        Email: <input type="text" name="email"><br>--}}
{{--        Phone: <input type="text" name="phone"><br>--}}
{{--        Password: <input type="text" name="password"><br>--}}
{{--        Class: <select name="class_id">--}}
{{--            @foreach($classes as $class)--}}
{{--                <option value="{{$class-> id}}">--}}
{{--                    {{$class->class_name}}--}}
{{--                </option>--}}
{{--            @endforeach--}}
{{--        </select><br>--}}
{{--        <button class="btn btn-outline-success">Add</button>--}}
{{--    </form>--}}
{{--@endsection--}}
