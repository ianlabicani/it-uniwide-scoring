@extends('judge.shell')

@section('judge-content')
    <div class="container mt-4">
        <h4>Evaluate Contestant: {{ $contestant->name }}</h4>

        <form action="{{ route('judge.competitions.score', [$competition->id, $contestant->id]) }}" method="POST">
            @csrf

            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Criteria</th>
                        <th>Percentage</th>
                        <th>Score (1-100)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($criteria as $criterion)
                                    @php
                                        $score = $scores->where('criteria_id', $criterion->id)->first();
                                    @endphp

                                    <tr>
                                        <td>{{ $criterion->name }}</td>
                                        <td>{{ $criterion->percentage }}%</td>
                                        <td>
                                            @if($score)
                                                <input type="number" class="form-control" value="{{ $score->score }}" disabled>
                                            @else
                                                <input type="number" class="form-control" name="scores[{{ $criterion->id }}]" min="1" max="100"
                                                    required>
                                            @endif
                                        </td>
                                    </tr>
                    @endforeach
                </tbody>
            </table>

            @if($scores->isEmpty())
                <button type="submit" class="btn btn-success">Submit Scores</button>
            @else
                <div class="alert alert-info">You have already scored this contestant.</div>
            @endif

            <a href="{{ route('judge.competitions.show', $competition->id) }}" class="btn btn-secondary">Back</a>
        </form>
    </div>
@endsection