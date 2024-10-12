@extends('layout.master')
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
    <a href="{{route('admin.create')}}">Add</a>
    <table border="1px" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <th>ID</th>
            <th>grade Name</th>
            <th>Password</th>
        </tr>
        @foreach($admins as $admin)
            <tr>
                <td>
                    {{$admin->id}}
                </td>
                <td>
                    {{$admin->username}}
                </td>
                <td>
                    {{$admin->password}}
                </td>
                <td>
                    <a href="{{ route('admin.edit', $admin-> id) }}">Edit</a>
                </td>
                <td>
                    <form method="post" action="{{ route('admin.destroy', $admin->id) }}">
                        @method('DELETE')
                        @csrf
                        <button>Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
