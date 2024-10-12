@extends('layout.master')

@section('content')
    <form method="post" action="{{ route('class.update', $id) }}">
        @csrf
        @method('PUT')

        @foreach($classes as $class)
            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="text-left">Chỉnh sửa lớp</h2>
                    </div>

                    <div class="row">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên lớp</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tên lớp" value="{{ $class->class_name }}" name="class_name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Khối</label>
                                    <select name="grade_id" class="form-select">
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}" {{ $grade->id == $class->grade_id ? 'selected' : '' }}>
                                                {{ $grade->grade_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="label">Niên khóa</label>
                                    <select name="school_year_id" class="form-select">
                                        @foreach($school_years as $school_year)
                                            <option value="{{ $school_year->id }}" {{ $school_year->id == $class->school_year_id ? 'selected' : '' }}>
                                                {{ $school_year->sy_start }}-{{ $school_year->sy_end }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-info">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach


    </form>
@endsection
