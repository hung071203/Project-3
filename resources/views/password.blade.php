

@extends('layout.master-mini3')

@section('content')
@if (session('error'))
        <div class="alert alert-danger" id="myText">
            {{ session('error') }}
        </div>
        {{ session()->forget('error') }}
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="myText">
            {{ session('success') }}
        </div>
        {{ session()->forget('success') }}
    @endif

    <form method="post" action="{{ route('admin.changPass') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div>
                    <h2 class="text-left mb-4">Đổi mật khẩu</h2>
                </div>
                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">
                            <!-- Class selection -->
                            <div class="form-group">
                                <label for="inputPassword5" class="form-label">Mật khẩu hiện tại</label>
                                <input type="password" name="oldPass" class="form-control" required aria-describedby="passwordHelpBlock">

                            </div>

                            <div class="form-group">
                                <label for="inputPassword5" class="form-label">Mật khẩu mới</label>
                                <input type="password" name="newPass" class="form-control" required aria-describedby="passwordHelpBlock">

                            </div>

                            <div class="form-group">
                                <label for="inputPassword5" class="form-label">Nhập lại mật khẩu mới</label>
                                <input type="password" name="reNew" class="form-control" required aria-describedby="passwordHelpBlock">

                            </div>

                            <button class="btn btn-success">Đổi mật khẩu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

