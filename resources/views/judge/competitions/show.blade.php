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
                </p>
            </div>
        </div>
        <h5 class="mt-4">Judges</h5>
        @php
            $judgeCount = $competition->judges->count();
            $allJudgesDone = $judgeCount > 0 && $competition->scores()
                ->where('judge_id', auth()->id())
                ->select('user_id')
                ->groupBy('user_id')
                ->havingRaw('COUNT(criteria_id) = ?', [$competition->criteria->count()])
                ->count() == $competition->contestants->count();
        @endphp

        @if($competition->judges->isEmpty())
            <p>No judges assigned yet.</p>
        @else
            <ul class="list-group">
                @foreach($competition->judges as $judge)
                    @php
                        // Get the total number of contestants in this competition
                        $totalContestants = $competition->contestants->count();

                        // Get the number of contestants this judge has scored in THIS competition
                        $judgedCount = $competition->scores()
                            ->where('judge_id', $judge->id)
                            ->select('user_id')
                            ->groupBy('user_id')
                            ->havingRaw('COUNT(criteria_id) = ?', [$competition->criteria->count()])
                            ->count();


                        // Check if the judge has scored all contestants
                        $hasCompleted = $totalContestants > 0 && $judgedCount == $totalContestants;

                        if ($hasCompleted) {
                            $judgedCount--;
                        }

                    @endphp

                    <li class="list-group-item {{ $hasCompleted ? 'list-group-item-success' : '' }}">
                        {{ $judge->name }}
                        @if($hasCompleted)
                            <span class="badge bg-success ms-2">Completed</span>
                        @else
                            <span class="badge bg-warning ms-2">Pending</span>
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif




        <h5 class="mt-4">Contestants and Ranking</h5>
        @if($competition->contestants->isEmpty())
            <p>No contestants registered yet.</p>
        @else
            @php
                // Count total contestants
                $totalContestants = $competition->contestants->count();

                // Count the number of judges who have completed scoring for all contestants
                $judgesCompleted = $competition->judges->filter(function ($judge) use ($competition, $totalContestants) {
                    $scoredContestants = $competition->scores()
                        ->where('judge_id', $judge->id)
                        ->distinct('user_id')
                        ->count('user_id');
                    return $scoredContestants == $totalContestants;
                })->count();

                // Check if all judges have completed their evaluations
                $allJudgesDone = $judgesCompleted == $competition->judges->count();
            @endphp

            @if ($allJudgesDone)
                @php
                    // Calculate total scores for each contestant
                    $contestantScores = $competition->contestants->map(function ($contestant) use ($competition) {
                        $totalScore = $competition->scores()
                            ->where('user_id', $contestant->id)
                            ->sum('score');

                        // Count only the judges who actually scored this contestant
                        $totalJudges = $competition->scores()
                            ->where('user_id', $contestant->id)
                            ->count();

                        // Properly calculate the weighted score across the judges who scored
                        $weightedScore = $totalJudges > 0 ? $totalScore / $totalJudges : 0;

                        return [
                            'contestant' => $contestant,
                            'totalScore' => $weightedScore
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
                                <small class="text-muted">
                                    <strong>Total Weighted Score:</strong> {{ number_format($totalScore, 2) }}
                                </small>
                            </div>

                            @if($alreadyScored)
                                <a href="{{ route('judge.competitions.view-scores', [$competition->id, $contestant->id]) }}"
                                    class="btn btn-success btn-sm">
                                    View Scores
                                </a>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted">
                    📢 Waiting for all judges to complete their evaluations.
                    Scores and rankings will appear once all judges have evaluated every contestant.
                </p>

                <ul class="list-group">
                    @foreach($competition->contestants as $contestant)
                        @php
                            // Check if the judge already evaluated this contestant
                            $alreadyScored = $competition->scores()
                                ->where('user_id', $contestant->id)
                                ->where('judge_id', auth()->id())
                                ->exists();
                        @endphp

                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $contestant->name }}</strong>
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
        @endif



        <a href="{{ route('judge.competitions.index') }}" class="btn btn-secondary mt-3">
            Back to Competitions
        </a>
    </div>
@endsection