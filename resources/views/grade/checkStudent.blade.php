@extends('layout.master')

@section('content')
    <div class="card">
        <h1 class="dropdown-item-title d-flex justify-content-center">Quản lý khối - Chi tiết học sinh </h1>
        <div class="card-body">
            <h2>Lớp: {{ $class->class_name }}</h2>
            @if($students->isEmpty())
                <p>Lớp hiện không có học sinh.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID học sinh</th>
                        <th>Tên học sinh</th>
                        <!-- Add more columns if needed -->
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($students as $student)
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>{{ $student->student_name }}</td>
                            <!-- Add more columns if needed -->
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
