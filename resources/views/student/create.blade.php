@extends('layout.master')
@section('content')
    <form method="post" action="{{route('student.store')}}">
        <div class="card">
            <div class="card-body">
                <h2 class="text-left mb-4">Thêm mới học sinh</h2>
                @csrf

                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">
                            <div class="form-group">
                                <label class="label">Tên học sinh</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Tên học sinh" name="student_name">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">Email</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Email" name="email">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">Số điện thoại</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Số điện thoại" name="phone">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">Mật khẩu</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Mật khẩu" name="password">
                                    <div class="input-group-append"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="label">Lớp</label>
                                <div class="input-group">
                                    <select name="class_id" class="form-select">
                                        @foreach($classes as $class)
                                            <option value="{{$class->id}}">
                                                {{$class->class_name}}_{{$class->sy_name}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-outline-success">Thêm</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
