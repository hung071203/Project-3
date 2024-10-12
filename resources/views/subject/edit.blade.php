@extends('layout.master')
@section('content')
    @foreach($subjects as $subject)
    <form method="post" action="{{ route('subject.update', $subject->id) }}">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="">
                    <h2 class="text-left mb-4">Chỉnh sửa môn học</h2>
                </div>
                <table class="table table-striped">
                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên môn </label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="" value="{{ $subject->subject_name }}" name="subject_name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Khối</label>
                                    <div class="input-group">
                                        <select name="grade_id" class="form-select">
                                            @foreach($grades as $grade)
                                                <option value="{{ $grade->id }}"
                                                        @if($grade->id == $subject->grade_id)
                                                            selected
                                                    @endif
                                                >
                                                    {{ $grade->grade_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Kì học</label>
                                    <div class="input-group">
                                        <select name="semester" class="form-select">
                                            <option value="1" {{ $subject->semester == 1 ? 'selected' : '' }}>Kì 1</option>
                                            <option value="2" {{ $subject->semester == 2 ? 'selected' : '' }}>Kì 2</option>
                                            <!-- Add more options as needed -->
                                        </select>
                                    </div>
                                </div>

{{--                                <div class="form-group">--}}
{{--                                    <label class="label">Sách giảng dạy</label>--}}
{{--                                    <div class="input-group">--}}
{{--                                        <select name="text_book" class="form-select">--}}
{{--                                            <option value="1" {{ $subject->text_book == 1 ? 'selected' : '' }}>Sách giáo khoa lớp 10</option>--}}
{{--                                            <option value="2" {{ $subject->text_book == 2 ? 'selected' : '' }}>Sách giáo khoa lớp 11</option>--}}
{{--                                            <option value="3" {{ $subject->text_book == 3 ? 'selected' : '' }}>Sách giáo khoa lớp 12</option>--}}
{{--                                            <!-- Add more options as needed -->--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                </div>--}}

                                <button class="btn btn-inverse-outline-success">Update</button>
                            </div>
                        </div>
                    </div>
                </table>
            </div>
        </div>
    </form>
    @endforeach
@endsection
