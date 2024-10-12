@extends('layout.master')
@section('content')
    <form method="post" action="{{ route('sy.store') }}">
        @csrf
        <div class="card">
            <div class="card-body">

                <div class=""><h2 class="text-left mb-4">Tạo niên khóa mới</h2></div>
                <table class="table table-striped ">

                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label for="sy_start" class="label">Năm bắt đầu</label>
                                    <div class="input-group">
                                        <input type="tel"  class="form-control @error('sy_start') is-invalid @enderror" id="sy_start" placeholder="Năm bắt đầu" name="sy_start" onkeypress="return isNumberKey(event)" oninput="calculateEndYear()" required>
                                        <div class="input-group-append">
                                        </div>
                                        @error('sy_start')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sy_end" class="label">Năm kết thúc</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control @error('sy_end') is-invalid @enderror" id="sy_end" placeholder="Năm kết thúc" name="sy_end" onkeypress="return isNumberKey(event)" readonly required>
                                        <div class="input-group-append">
                                        </div>
                                        @error('sy_end')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="label">Tên niên khóa</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tên niên khóa" name="sy_name">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>

                                <button class="btn btn-outline-success" type="submit">Thêm mới</button>
                            </div>
                        </div>
                    </div>
                </table>
            </div>
        </div>
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
