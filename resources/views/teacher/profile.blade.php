@extends('layout.masterteacher')
@section('content')
    <h1>teacher Profile</h1>
    <p>Name: {{ $teacher->teacher_name }}</p>
    <p>Email: {{ $teacher->email }}</p>
    <p>Phone: {{ $teacher->phone }}</p>
{{--    <form method="POST" action="{{ route('teacher.profile.update') }}">--}}
{{--        @csrf--}}
{{--        @method('PUT')--}}
{{--        <div class="form-group">--}}
{{--            <label for="teacher_name">Full Name</label>--}}
{{--            <input type="text" name="teacher_name" id="teacher_name" value="{{ old('teacher_name', $teacher->teacher_name) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="email">Email</label>--}}
{{--            <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="phone">Phone</label>--}}
{{--            <input type="text" name="phone" id="phone" value="{{ old('phone', $teacher->phone) }}" class="form-control" required>--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="password">Password (Leave empty to keep the same)</label>--}}
{{--            <input type="password" name="password" id="password" class="form-control">--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <label for="password_confirmation">Confirm Password</label>--}}
{{--            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">--}}
{{--        </div>--}}
{{--        <button type="submit" class="btn btn-primary">Update Profile</button>--}}
{{--    </form>--}}
@endsection
