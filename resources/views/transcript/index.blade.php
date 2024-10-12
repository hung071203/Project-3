@extends('layout.masterteacher')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger" id="myText">
            {{ session('error') }}
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success" id="myText">
            {{ session('success') }}
        </div>
    @endif


    <div class="col-lg-15 grid-margin stretch-card" >
        <div class="card">
            <div class="card-body">
                <h1>Quản lý bảng điểm</h1>
{{--                <a class="btn btn-success btn-fw; menu-icon mdi mdi-creation" href="{{route('transcript.create')}}">Add Transcript</a>--}}
                <table class="table table-striped" >
                    <tr>
{{--                        <th>ID Transcript</th>--}}
                        <th>Tên bảng điểm</th>
                        <th>Hình thức kiểm tra</th>
                        <th>Kỳ học</th>

                        <th>Lớp</th>
                        <th>Khối</th>
                        <th>Môn học</th>
                        <th>Giáo viên</th>
                        <th>Trạng thái và hành động</th>
                        <th>Cài đặt</th>

                        <th>Chi tiết</th>
                    </tr>
                    @foreach($transcripts as $transcript)
                        <tr>
{{--                            <td>--}}
{{--                                {{$transcript->id}}--}}
{{--                            </td>--}}
                            <td>
                                {{$transcript->transcript_name}}
                            </td>
                            <td>
                                @if($transcript -> division-> exam_type == 1)
                                    <div class="badge badge-success">Miệng, thường xuyên</div>
                                @elseif($transcript -> division->exam_type == 2)
                                    <div class="badge badge-info">15 phút</div>
                                @elseif($transcript -> division->exam_type == 3)
                                    <div class="badge badge-warning">1 tiết</div>
                                @elseif($transcript ->division-> exam_type == 4)
                                    <div class="badge badge-danger">Cuối kỳ</div>
                                @endif
                            </td>
                            <td>
                                @if($transcript -> semester == 1)
                                    <div class="badge badge-primary">Kỳ 1</div>
                                @elseif($transcript -> semester == 2)
                                    <div class="badge badge-info">Kỳ 2</div>
                                @endif
                            </td>
                            <td>
                                {{$transcript->class_name}}_{{$transcript->sy_name}}
                            </td>
                            <td>
                                {{$transcript->grade_name}}
                            </td>
                            <td>
                                {{$transcript->subject_name}}
                            </td>
                            <td>
                                {{$transcript->teacher_name}}
                            </td>
{{--                            <td>--}}

{{--                                @if($transcript->isFinish())--}}
{{--                                    <div class="badge badge-success">Finished</div>--}}
{{--                                @else--}}
{{--                                    <div class="badge badge-warning">In Progress</div>--}}
{{--                                @endif--}}
{{--                            </td>--}}
                            <td>
                                @if($transcript->isFinish())
                                    <div class="badge badge-success">Hoàn thành</div>
{{--                                    <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}
                                @else
{{--                                    @if($transcript->exam_type == 1)--}}
{{--                                        <a class="btn btn-outline-success" href="{{route('transdetail.create', ['transcript_id' => $transcript->id])}}">Add Point</a>--}}
{{--                                    @elseif($transcript->exam_type == 2)--}}
{{--                                        @if($transcript->transcriptDetails->where('transcript_id', $transcript->id)->count() > 0)--}}
{{--                                            <div class="badge badge-success">Finished</div>--}}
{{--                                            <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Check</a>--}}

{{--                                        @else--}}
{{--                                            <a class="btn btn-outline-dark" href="{{route('transdetail.created', ['transcript_id' => $transcript->id])}}">Add Point</a>--}}
                                            <a class="btn btn-outline-success" href="{{route('transdetail.create', ['transcript_id' => $transcript->id])}}">Thêm điểm</a>
                                        @endif
{{--                                    @endif--}}
{{--                                @endif--}}
                            </td>
                            <td>
                                <form method="post" action="{{ route('transcript.destroy', $transcript -> id ) }}" id="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-reddit mdi mdi-trash-can" type="submit" onclick="return confirmDelete();"></button>
                                </form>

                                <script>
                                    function confirmDelete() {
                                        // Sử dụng hộp thoại xác nhận
                                        return confirm('Do you want to delete?');
                                    }
                                </script>
                            </td>
                            <td>
                                <a class="btn btn-outline-success" href="{{route('transdetail.index', ['transcript_id' => $transcript->id])}}">Kiểm tra</a>

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
