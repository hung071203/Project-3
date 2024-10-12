@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý khối - Chi tiết lớp học</h1>
        <div class="card-body">
            <h2>{{ $grade->grade_name }}</h2>
            @if($classes->isEmpty())
                <p>Không có lớp nào thuộc khối này.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID lớp</th>
                        <th>Tên lớp</th>
                        <th>Tổng học sinh</th>
                        <th>Hành động</th> <!-- New column for the Check Students button -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($classes as $class)
                        <tr>
                            <td>{{ $class->id }}</td>
                            <td>{{ $class->class_name }}</td>
                            <td>{{ $students->where('class_id', $class->id)->count() }}</td>
                            <td>
                                <a href="{{ route('grade.checkStudent', ['class_id' => $class->id]) }}"
                                   class="btn btn-info">Chi tiết học sinh</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
