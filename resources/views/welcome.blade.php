<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome | IT UNIWIDE 2025</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <!-- Hero Section -->
    <section class="py-5 text-center bg-primary text-white">
        <div class="container">
            <h1 class="fw-bold">Welcome to IT UNIWIDE 2025</h1>
            <p class="lead mb-4">
                Witness the outstanding talents and skills from various campuses.
            </p>
            <a href="{{ route('login') }}" class="btn btn-warning btn-lg">Join the Event</a>
        </div>
    </section>

    <!-- General Programming Overall Result -->
    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4">🏆 General Programming Overall Result</h3>

            @php
// Fetch all 5 General Programming competitions
$generalProgrammingCompetitions = \App\Models\Competition::whereIn('name', [
    'Gen Prog - Problem 1',
    'Gen Prog - Problem 2',
    'Gen Prog - Problem 3',
    'Gen Prog - Problem 4',
    'Gen Prog - Problem 5'
])->get();

// Get all contestants who joined Gen Prog
$contestants = \App\Models\User::whereHas('scores', function ($query) use ($generalProgrammingCompetitions) {
    $query->whereIn('competition_id', $generalProgrammingCompetitions->pluck('id'));
})->get();

// Calculate the overall result
$rankedContestants = $contestants->map(function ($contestant) use ($generalProgrammingCompetitions) {
    $totalScore = 0;
    $totalCriteria = 0;

    foreach ($generalProgrammingCompetitions as $competition) {
        $scores = $competition->scores()->where('user_id', $contestant->id)->get();
        foreach ($scores as $score) {
            $criteria = $score->criteria;
            foreach ($criteria as $criterion) {
                $totalScore += $score->score;
                $totalCriteria++;
            }
        }
    }

    $overallWeightedMean = $totalCriteria > 0 ? $totalScore / $totalCriteria : 0;

    return [
        'contestant' => $contestant,
        'overall_mean' => $overallWeightedMean
    ];
})->sortByDesc('overall_mean')->values();
            @endphp

            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">🏆 General Programming Overall Rankings</h5>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @foreach($rankedContestants as $index => $entry)
                                                @php
    $contestant = $entry['contestant'];
    $overallMean = $entry['overall_mean'];
    $rank = $index + 1;
                                                @endphp

                                                <li class="list-group-item d-flex justify-content-between">
                                                    <span>
                                                        <strong>
                                                            {{ $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : '🎖️')) }}
                                                            {{ $contestant->name }}
                                                        </strong>
                                                    </span>
                                                    <span class="text-muted">
                                                        {{ number_format($overallMean, 2) }} pts (Weighted Mean)
                                                    </span>
                                                </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Individual Competitions With Weighted Mean -->
    <section class="py-5 bg-light">
        <div class="container">
            <h3 class="text-center mb-4">🏆 Individual Competition Results</h3>

            @if($completedCompetitions->isNotEmpty())
                    @foreach($completedCompetitions as $competition)
                            <div class="card mb-4 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <h5 class="mb-0">
                                        {{ $competition->name }}
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <ul class="list-group list-group-flush">
                                        @php
        $rankedContestants = $competition->scores()
            ->select('user_id', \DB::raw('SUM(score) as total_score'))
            ->groupBy('user_id')
            ->get();

        $rankedContestants = $rankedContestants->map(function ($contestantScore) use ($competition) {
            $user = \App\Models\User::find($contestantScore->user_id);
            $totalScore = 0;
            $criteriaCount = 0;

            $scores = $competition->scores()->where('user_id', $user->id)->get();
            foreach ($scores as $score) {
                $criteria = $score->criteria;
                foreach ($criteria as $criterion) {
                    $totalScore += $score->score;
                    $criteriaCount++;
                }
            }

            $weightedMean = $criteriaCount > 0 ? $totalScore / $criteriaCount : 0;

            return [
                'user' => $user,
                'weighted_mean' => $weightedMean
            ];
        })->sortByDesc('weighted_mean')->values();

        $rank = 1;
                                        @endphp

                                        @foreach($rankedContestants as $entry)
                                                        @php
            $contestant = $entry['user'];
            $weightedMean = $entry['weighted_mean'];
                                                        @endphp

                                                        <li class="list-group-item d-flex justify-content-between">
                                                            <span>
                                                                <strong>
                                                                    {{ $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : ($rank == 3 ? '🥉' : '🎖️')) }}
                                                                    {{ $contestant->name }}
                                                                </strong>
                                                            </span>
                                                            <span class="text-muted">
                                                                {{ number_format($weightedMean, 2) }} pts (Weighted Mean)
                                                            </span>
                                                        </li>

                                                        @php $rank++; @endphp
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                    @endforeach
            @endif
        </div>
    </section>

</body>

</html>
