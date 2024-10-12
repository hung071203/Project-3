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
    @csrf
    <td>
        <a href="{{ route('reexamine.create')}}" class="btn btn-primary">Make a Reexamine</a>
    </td>
    <table class="table table-striped">

        <!-- Headers -->
        <thead>
        <tr>
            <th>Student</th>
            <th>Class</th>
            <th>grade</th>
            <th>Subject</th>
            <th>Semester</th>
            <th>New Score</th>
            <th>Rate</th>
            <th></th>
        </tr>
        </thead>
        <!-- Data Rows -->
        <tbody>
        @foreach($reexamines as $reexamine)
            <tr>
                <td>{{ $reexamine->student_name }}</td>
                <td>{{ $reexamine->class_name }}_{{ $reexamine->sy_name }}</td>
                <td>{{ $reexamine->grade_name }}</td>
                <td>{{ $reexamine->subject_name }}</td>
                <td>
                    @if($reexamine -> semester == 0)
                        <div class="badge badge-primary">Semester 1</div>
                    @elseif($reexamine -> semester == 1)
                        <div class="badge badge-info">Semester 2</div>
                    @elseif($reexamine -> semester == 2)
                        <div class="badge badge-warning">Extra Semester</div>
                    @endif
                </td>


                <td @if($reexamine -> new_score <= 5) class="text-danger"
                    @elseif($reexamine -> new_score >= 5.01) class="text-success"
                    @endif>
                    {{$reexamine->new_score ?? 'None'}}


                </td>

                <td @if($reexamine -> new_score < 5)
                    {{$reexamine->new_score ?? 'None'}}
                    <div class="text-danger "> Fail <i class="mdi mdi-arrow-down"></i> </div>
                @elseif($reexamine -> new_score >= 5.01)
                    <div class="text-success"> Pass <i class="mdi mdi-arrow-up"></i> </div>
                    @endif</td>

                    <td>
                        <a href="{{route('reexamine.edit', $reexamine -> report_id )}}" class="mdi mdi-account-edit badge badge-primary">Edit</a>
                    </td>

                <script>
                    function confirmUpdate() {
                        // Sử dụng hộp thoại xác nhận
                        return confirm('Warning!!!This Action will change a status and it wont be change anymore. Are you sure with this');
                    }
                </script>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection

