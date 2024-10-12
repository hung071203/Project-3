@extends('layout.master')
@section('content')
    <form method="post" action="{{route('teacher.update', $id)}}">
        @csrf
        @method('PUT')
        @foreach($teachers as $teacher)
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <h2 class="text-left mb-4">Cập nhật giáo viên</h2>
                    </div>
                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên giáo viên</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tên giáo viên" value="{{$teacher->teacher_name}}" name="teacher_name">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Email</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" name="email" placeholder="Email" value="{{$teacher->email}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Số điện thoại</label>
                                    <div class="input-group">
                                        <input type="text" name="phone" class="form-control" placeholder="Số điện thoại" value="{{$teacher->phone}}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="label">Mật khẩu mới</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" name="password" placeholder="Mật khẩu mới" value="">
                                    </div>
                                </div>
                                {{--                                <div class="form-group">--}}
                                {{--                                    <label class="label">Khối</label>--}}
                                {{--                                    <div class="input-group">--}}
                                {{--                                        <select name="grade_id" class="form-control">--}}
                                {{--                                            @foreach($grades as $grade)--}}
                                {{--                                                <option value="{{$grade->id}}"--}}
                                {{--                                                @if($grade->id == $teacher->grade_id)--}}
                                {{--                                                    selected--}}
                                {{--                                                @endif--}}
                                {{--                                                >{{$grade->grade_name}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                            <button class="btn btn-outline-info">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </form>
@endsection
