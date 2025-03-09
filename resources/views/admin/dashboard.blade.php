@extends('admin.shell')

@section('admin-content')
    <div class="container">
        <h1>dashboard</h1>
        <a href="{{ route('admin.users.index') }}">users</a>
    </div>
@endsection
