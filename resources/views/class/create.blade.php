@extends('layout.master')

@section('content')
    <form method="post" action="{{ route('class.store') }}">
        <div class="card">
            <div class="card-body">

                <div class="mb-4">
                    <h2 class="text-left">Create Class</h2>
                </div>

                <table class="table table-striped">
                    @csrf

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên lớp</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Class Name" name="class_name">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Khối</label>
                                    <select name="grade_id" class="form-select">
                                        @foreach($grades as $grade)
                                            <option value="{{ $grade->id }}">
                                                {{ $grade->grade_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="label">Niên khóa</label>
                                    <select name="school_year_id" class="form-select">
                                        @foreach($school_years as $school_year)
                                            <option value="{{ $school_year->id }}">
                                                ({{ $school_year->sy_start }}-{{ $school_year->sy_end }}) {{ $school_year->sy_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-outline-success">Thêm mới</button>
                        </div>
                    </div>

                </table>


            </div>
        </div>
    </form>
@endsection
