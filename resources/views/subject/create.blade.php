@extends('layout.master')
@section('content')
    <form method="post" action="{{ route('subject.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">
                <div class="">
                    <h2 class="text-left mb-4">Thêm môn học</h2>
                </div>
                <div class="row w-80">
                    <div class="col-lg-10 mx-auto">
                        <div class="auto-form-wrapper">
                            <div class="form-group">
                                <label class="label">Tên môn học</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="" name="subject_name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="label">Khối</label>
                                <div class="input-group">
                                    @foreach($grades as $grade)
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="grade_id[]" value="{{ $grade->id }}" id="grade{{ $grade->id }}">
{{--                                            <label class="form-check-label" for="grade{{ $grade->id }}">--}}
                                                {{ $grade->grade_name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button class="btn btn-inverse-outline-success">Thêm mới</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

<!-- Add these in your master layout if not already included -->
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

<script>
    $(document).ready(function() {
        $('.form-check-input').change(function() {
            if ($(this).is(':checked')) {
                $(this).closest('.form-check').addClass('active');
            } else {
                $(this).closest('.form-check').removeClass('active');
            }
        });
    });
</script>

<style>
    /* Remove default checkbox styling */
    .form-check-input:checked + .form-check-label::before {
        background-color: transparent !important;
    }
    /* Style for active checkboxes */
    .form-check.active .form-check-label {
        color: #007bff; /* Adjust this color as needed */
    }
    .form-check.active .form-check-input {
        border-color: #007bff; /* Adjust this color as needed */
    }


</style>
<style>
    .form-check {
        display: inline-block;
        width: 30%; /* Điều chỉnh chiều rộng của checkbox */
        margin-right: 10px; /* Khoảng cách giữa các checkbox */
    }

    .form-check-input:checked + .form-check-label::before {
        background-color: transparent !important;
    }

    .form-check.active .form-check-label {
        color: #007bff; /* Màu cho checkbox khi được chọn */
    }

    .form-check.active .form-check-input {
        border-color: #007bff; /* Màu viền cho checkbox khi được chọn */
    }
</style>
