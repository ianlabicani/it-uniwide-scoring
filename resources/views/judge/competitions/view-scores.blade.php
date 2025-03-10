@extends('judge.shell')

@section('judge-content')
    <div class="container mt-4">
        <h4>Scores for {{ $contestant->name }}</h4>

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Criteria</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $score)
                    <tr>
                        <td>{{ $score->criteria->name }}</td>
                        <td>{{ $score->score }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <a href="{{ route('judge.competitions.show', $competition->id) }}" class="btn btn-secondary">
            Back to Competition
        </a>
    </div>
@endsection