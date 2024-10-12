@extends('layout.master')
@section('content')

        <div class="card">
            <div class="card-body">


                <table class="table table-striped">
                <form method="post" action="{{route('grade.store')}}">
                    @csrf
                    <div><h2 class="text-left mb-4">Tạo mới khối</h2></div>
                    <div class="row w-80">
                        <div class="col-lg-10 mx-auto">
                            <div class="auto-form-wrapper">
                                <div class="form-group">
                                    <label class="label">Tên khối</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Tên khối" name="grade_name">
                                        <div class="input-group-append">
                                        </div>
                                    </div>
                                </div>
                                <button class="btn btn-inverse-success">Thêm</button>
                                <!-- Đóng tất cả các thẻ div -->
                            </div> <!-- .auto-form-wrapper -->
                        </div> <!-- .col-lg-10 -->
                    </div> <!-- .row -->
                </form>
                </table>

            </div> <!-- .card-body -->
        </div> <!-- .card -->


@endsection
