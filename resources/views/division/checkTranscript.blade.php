@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý phân công - Kiểm tra bảng điểm </h1>
        <div class="card-body">
{{--            <h2>{{ $grade->grade_name }}</h2>--}}
            @if($transcripts->isEmpty())
                <p>Không tìm thấy</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID bảng điểm</th>
                        <th>Tên bảng điểm</th>
{{--                        <th>Exam Times</th>--}}
{{--                        <th>Number of Students</th>--}}
                        <th>Hành động</th> <!-- New column for the Check Students button -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transcripts as $transcript)
                        <tr>
                            <td>{{ $transcript->id }}</td>
                            <td>{{ $transcript->transcript_name }}</td>
{{--                            <td>@if($transcript -> exam_type == 0)--}}
{{--                                    <div class="badge badge-success">1 Times</div>--}}
{{--                                @elseif($transcript -> exam_type == 1)--}}
{{--                                    <div class="badge badge-info">2 Times</div>--}}
{{--                                @elseif($transcript -> exam_type == 2)--}}
{{--                                    <div class="badge badge-warning">Relearn</div>--}}
{{--                                @endif</td>--}}
{{--                            <td>{{ $students->where('class_id', $class->id)->count() }}</td>--}}
                            <td>
                                <a href="{{ route('division.checkTransDetail', ['transcript_id' => $transcript->id]) }}"
                                   class="mdi-eye">Kiểm tra</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
