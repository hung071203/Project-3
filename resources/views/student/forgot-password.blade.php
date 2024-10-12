@extends('layout.master-mini')
@section('content')
    <div class="content-wrapper d-flex align-items-center justify-content-center auth theme-one" style="background-image: url({{ url('assets/images/auth/login_2.jpg') }}); background-size: cover;">
        <div class="row w-100">
            <div class="col-lg-4 mx-auto">
                <div class="auto-form-wrapper">
                    <form action="{{ route('student.sendResetLinkEmail') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="label">Email</label>
                            <div class="input-group">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Phone</label>
                            <div class="input-group">
                                <input type="text" name="phone" class="form-control" placeholder="Phone" required>
                                <div class="input-group-append">
                                    <span class="input-group-text">
                                        <i class="mdi mdi-check-circle-outline"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button class="btn btn-primary submit-btn btn-block">Reset Password</button>
                        </div>
                        <div class="form-group text-center">
                            <a href="{{ route('student.login') }}" class="text-small text-black">Back to Login</a>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if($errors->any())
                            <div class="alert alert-danger">
                                Invalid Email or Phone
                            </div>
                        @endif
                    </form>
                </div>
                <!-- Add additional content if needed -->
            </div>
        </div>
    </div>
@endsection
