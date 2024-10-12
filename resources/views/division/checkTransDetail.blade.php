@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý bảng điểm chi tiết - Điểm học sinh</h1>
        <div class="card-body">
            <h2>Transcript: {{ $transcript->transcript_name }}</h2>
            @if($transcriptDetails->isEmpty())
                <p>Không tìm thấy.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID học sinh</th>
                        <th>Tên học sinh</th>
                        <th>Ghi chú</th>
                        <th>Điểm</th>
{{--                        <th>Rates</th>--}}
                        <!-- Add more columns if needed -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transcriptDetails as $detail)
                        <tr>
                            <td>{{ $detail->student->id }}</td>
                            <td>{{ $detail->student->student_name }}</td>
                            <td>
                                @if($detail -> note == 1)
                                    <div class="badge badge-info">Làm bài</div>
                                @elseif($detail -> note == 2)
                                    <div class="badge badge-danger">Bị cấm</div>
                                @elseif($detail -> note == 3)
                                    <div class="badge badge-warning">Vắng mặt</div>
                                @endif
                            </td>
                            <td @if($detail -> score <= 5) class="text-danger"
                                @elseif($detail -> score >= 5.01) class="text-success"
                                @endif>
                                {{$detail->score ?? 'None'}}


                            </td>
                            <td @if($detail -> score < 5)
                                {{$detail->score ?? 'None'}}
                                <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>
                            @elseif($detail -> score >= 5.01)
                                <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>
                                @endif



                                </td>
                            <!-- Add more columns if needed -->
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
