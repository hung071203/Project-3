@extends('layout.master')
@section('content')
    <form method="post" action="{{route('teacher.store')}}">
        <div class="card">
            <div class="card-body">
                <h2 class="text-left mb-4">Tạo giáo viên</h2>
                <table class="table table-striped">
                    @csrf
                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên giáo viên</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tên giáo viên" name="teacher_name">
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
                                        <input type="text" class="form-control" placeholder="số điện thoại" name="phone">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Mật khẩu</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" placeholder="mật khẩu" name="password">
                                        <div class="input-group-append"></div>
                                    </div>
                                </div>
                                {{--
                                <div class="form-group">
                                    grade<br>
                                    <select name="grade_id" class="input-group">
                                        @foreach($grades as $grade)
                                            <option value="{{$grade->id}}">
                                                {{$grade->grade_name}}
                                            </option>
                                        @endforeach
                                    </select><br>
                                </div>
                                --}}
                                <button class="btn btn-outline-success">Thêm</button>
                            </div>
                        </div>
                    </div>
                </table>
            </div>
        </div>
    </form>
@endsection
