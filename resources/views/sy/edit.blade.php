@extends('layout.master')
@section('content')
    <form method="post" action="">
        @method('PUT')
        @csrf
        @foreach($school_years as $school_year)
            <div class="card">
                <div class="card-body">

                    <div class=""><h2 class="text-left mb-4">Chỉnh sửa niên khóa</h2></div>
                    <table class="table table-striped " >
            <div class="row w-80">
                <div class="col-lg-10 mx-auto">
                    <div class="auto-form-wrapper">
                        <div class="form-group">
                            <label class="label">Năm bắt đầu</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Năm bắt đầu" value="{{ $school_year->sy_start }}" name="sy_start" onkeypress="return isNumberKey(event)" oninput="calculateEndYear()" required>
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="label">Năm kết thúc</label>
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Năm kết thúc" value="{{ $school_year->sy_end }}" name="sy_end" onkeypress="return isNumberKey(event)" readonly required>
                                <div class="input-group-append">
                                </div>
                            </div>
                        </div>
{{--            School Year <input type="text " name ="sy_number" value="{{ $school_year->sy_number }}"><br>--}}
{{--            Name <input type="text " name ="sy_name" value="{{ $school_year->sy_name }}"><br>--}}
            <div class="form-group">
                <label class="label">Tên niên khóa</label>
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="School Year Name" value="{{ $school_year->sy_name }}" name="sy_name">
                    <div class="input-group-append">
                    </div>
                </div>
            </div>
        @endforeach
        <button class="btn btn-outline-info">Cập nhật</button>
    </form>
    <script>
        function isNumberKey(evt) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            if (charCode != 8 && charCode > 31 && (charCode < 48 || charCode > 57)) {
                return false;
            }
            return true;
        }

        function calculateEndYear() {
            var startYear = document.getElementById('sy_start').value;
            if (startYear.length === 4 && !isNaN(startYear)) {
                var endYear = parseInt(startYear) + 3;
                document.getElementById('sy_end').value = endYear;
            } else {
                document.getElementById('sy_end').value = '';
            }
        }
    </script>
@endsection
