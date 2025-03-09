@extends('judge.shell')

@section('judge-content')
    <div class="container">
        <button class="btn btn-primary">Evaluate</button>

        <h1>Competions to Judge</h1>
        {{-- show the competions for this judge --}}

        <h1>dashboard</h1>
        <p>
            show summary of the judge's activities
        </p>
        <hr>
        <p>
            show rankings
        </p>

        {{-- <a href="{{ route('judge.users.index') }}">users</a> --}}
    </div>
@endsection
