@extends('layout.master-mini3')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Quản lý thông tin tài khoản </div>

            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ route('updateProfile') }}">
                    @csrf
                    @method('PUT')

                    @php
                        $data = [];
                        if (Auth::guard('admin')->check()){
                            $data = Auth::guard('admin')-> user();
                        }elseif (Auth::guard('student')->check()){
                            $data = Auth::guard('student')-> user();
                        }elseif (Auth::guard('teacher')->check()){
                            $data = Auth::guard('teacher')-> user();
                        }

                    @endphp
                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                @if($data->username)
                                    <div class="form-group">
                                        <label class="label">Tên đăng nhập</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tên người dùng" name="name" value="{{$data->username}}">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                @endif


                                @if(Auth::guard('teacher')->check() || Auth::guard('student')->check())
                                    <div class="form-group">
                                        <label class="label">Tên người dùng</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tên người dùng" name="name" value="{{$data->student_name ?? $data->teacher_name }}">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Email</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{$data->email}}">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="label">Số điện thoại</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="số điện thoại" name="phone" value="{{$data->phone}}">
                                            <div class="input-group-append"></div>
                                        </div>
                                    </div>
                                @endif
                                <button class="btn btn-outline-success">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
