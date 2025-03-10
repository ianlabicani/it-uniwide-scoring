@extends('judge.shell')

@section('judge-content')
    <div class="container mt-4">
        <h4>All Competitions Assigned to You</h4>

        <div class="accordion" id="competitionAccordion">
            @foreach($competitions as $competition)
                    <div class="card mb-2">
                        <div class="card-header" id="heading-{{ $competition->id }}">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $competition->id }}" aria-expanded="true"
                                    aria-controls="collapse-{{ $competition->id }}">
                                    {{ $competition->name }}
                                </button>
                            </h5>
                        </div>

                        <div id="collapse-{{ $competition->id }}" class="collapse" aria-labelledby="heading-{{ $competition->id }}"
                            data-bs-parent="#competitionAccordion">
                            <div class="card-body">
                                <small>
                                    <strong>Date:</strong> {{ $competition->formatted_date }} |
                                    <strong>Location:</strong> {{ $competition->location }}
                                </small>

                                <h6 class="mt-3"><strong>Contestant Rankings</strong></h6>
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Contestant</th>
                                            <th>Total Average Score</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $rank = 1;
                                            $contestants = $competition->contestants->map(function ($contestant) use ($competition) {
                                                $scores = $competition->scores()->where('user_id', $contestant->id)->get();
                                                $totalScore = $scores->sum('score');
                                                $averageScore = $scores->count() > 0 ? $totalScore / $competition->criteria->count() : 0;
                                                return (object) [
                                                    'contestant' => $contestant,
                                                    'averageScore' => $averageScore,
                                                    'isEvaluated' => $scores->where('judge_id', auth()->id())->isNotEmpty()
                                                ];
                                            })->sortByDesc('averageScore');
                                        @endphp

                                        @foreach($contestants as $data)
                                            <tr>
                                                <td>{{ $rank++ }}</td>
                                                <td>{{ $data->contestant->name }}</td>
                                                <td>{{ number_format($data->averageScore, 2) }}</td>
                                                <td>
                                                    @if($data->isEvaluated)
                                                        <span class="badge bg-success">Evaluated</span>
                                                    @else
                                                        <span class="badge bg-warning">Pending</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <a href="{{ route('judge.competitions.show', $competition->id) }}" class="btn btn-primary btn-sm">
                                    View Competition Details
                                </a>
                            </div>
                        </div>
                    </div>
            @endforeach
        </div>
    </div>
@endsection