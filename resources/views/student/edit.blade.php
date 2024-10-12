@extends('layout.master')
@section('content')
    <form method="post" action="{{route('student.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($students as $student)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Chỉnh sửa học sinh</h2></div>
                    <table class="table table-striped">
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Tên học sinh</label>
                                        <div class="input-group">
                                            <input type="text" name="student_name" class="form-control" placeholder="Tên học sinh" value="{{$student->student_name}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Email</label>
                                        <div class="input-group">
                                            <input type="text" name="email" class="form-control" placeholder="Email" value="{{$student->email}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Số điện thoại</label>
                                        <div class="input-group">
                                            <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="{{$student->phone}}">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Mật khẩu mới</label>
                                        <div class="input-group">
                                            <input type="password" name="password" class="form-control" placeholder="" value="">
                                            <div class="input-group-append">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        Lớp<br>
                                        <select name="class_id" class="form-select">
                                            @foreach($classes as $class)
                                                <option value="{{$class->id}}"
                                                @if($class->id == $student->class_id)
                                                    {{'selected'}}
                                                    @endif
                                                >
                                                    {{$class->class_name}}{{$class->sy_name}}
                                                </option>
                                            @endforeach
                                        </select><br>
                                    </div>
                                </div>
                                <button class="btn btn-outline-info">Cập nhật</button>
                            </div>
                        </div>
                    </table>
                </div>
            </div>
        @endforeach

    </form>
@endsection
