@extends('judge.shell')

@section('judge-content')
    <div class="container mt-4">
        <h4>Competition Details</h4>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $competition->name }}</h5>
                <p class="card-text">
                    <strong>Date:</strong> {{ $competition->formatted_date }}<br>
                    <strong>Location:</strong> {{ $competition->location }}<br>
                    <strong>Description:</strong> {{ $competition->description ?? 'No description provided.' }}<br>
                    <strong>Status:</strong>
                    <span class="badge bg-warning">Not Yet Judged</span>
                </p>
            </div>
        </div>

        <h5 class="mt-4">Judges</h5>
        @if($competition->judges->isEmpty())
            <p>No judges assigned yet.</p>
        @else
            <ul class="list-group">
                @foreach($competition->judges as $judge)
                    <li class="list-group-item">{{ $judge->name }}</li>
                @endforeach
            </ul>
        @endif

        <h5 class="mt-4">Contestants and Ranking</h5>
        @if($competition->contestants->isEmpty())
            <p>No contestants registered yet.</p>
        @else
            @php
                // Calculate total scores for each contestant
                $contestantScores = $competition->contestants->map(function ($contestant) use ($competition) {
                    $totalScore = $competition->scores()
                        ->where('user_id', $contestant->id)
                        ->sum('score');

                    return [
                        'contestant' => $contestant,
                        'totalScore' => $totalScore
                    ];
                });

                // Sort the contestants by total score in descending order
                $rankedContestants = $contestantScores->sortByDesc('totalScore')->values();
            @endphp

            <ul class="list-group">
                @foreach($rankedContestants as $index => $entry)
                    @php
                        $contestant = $entry['contestant'];
                        $totalScore = $entry['totalScore'];
                        $rank = $index + 1;

                        // Check if the judge already evaluated this contestant
                        $alreadyScored = $competition->scores()
                            ->where('user_id', $contestant->id)
                            ->where('judge_id', auth()->id())
                            ->exists();
                    @endphp

                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong>#{{ $rank }} - {{ $contestant->name }}</strong>
                            <br>
                            @php
                                $criteriaCount = $competition->criteria->count();
                                $averageScore = $criteriaCount > 0 ? $totalScore / $criteriaCount : 0;
                            @endphp

                            <small class="text-muted">
                                <strong>Total Score:</strong> {{ number_format($averageScore, 2) }}
                            </small>
                        </div>

                        @if(!$alreadyScored)
                            <a href="{{ route('judge.competitions.evaluate', [$competition->id, $contestant->id]) }}"
                                class="btn btn-primary btn-sm">
                                Evaluate
                            </a>
                        @else
                            <a href="{{ route('judge.competitions.view-scores', [$competition->id, $contestant->id]) }}"
                                class="btn btn-success btn-sm">
                                View Scores
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <a href="{{ route('judge.competitions.index') }}" class="btn btn-secondary mt-3">
            Back to Competitions
        </a>
    </div>
@endsection