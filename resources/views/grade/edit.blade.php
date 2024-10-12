@extends('layout.master')
@section('content')
    <form method="post" action="">
        @method('PUT')
        @csrf
        @foreach($grades as $grade)
            <div class="card">
                <div class="card-body">
                    <div><h2 class="text-left mb-4">Chỉnh sửa khối</h2></div>
                    <table class="table table-striped">
                        <div class="row w-80">
                            <div class="col-lg-10 mx-auto">
                                <div class="auto-form-wrapper">
                                    <div class="form-group">
                                        <label class="label">Tên khối</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Tên khối" value="{{ $grade->grade_name }}" name="grade_name">
                                            <div class="input-group-append">
                                            </div>
                                        </div>

                                    </div>
                                    <button class="btn btn-outline-info">Cập nhật</button>
                                </div> <!-- .auto-form-wrapper -->
                            </div> <!-- .col-lg-10 -->
                        </div> <!-- .row -->
                    </table>
                </div> <!-- .card-body -->
            </div> <!-- .card -->
        @endforeach

    </form>

@endsection
