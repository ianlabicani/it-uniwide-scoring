@extends('judge.shell')

@section('judge-content')
    <div class="container mt-4">
        <h4>Competitions to Judge</h4>

        @if($competitions->isEmpty())
            <div class="alert alert-info">
                You have not been assigned to any competitions yet.
            </div>
        @else
            <div class="list-group">
                @foreach($competitions as $competition)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">{{ $competition->name }}</h5>
                            <small class="text-muted">
                                <i class="fas fa-calendar"></i> {{ $competition->formatted_date }} |
                                <i class="fas fa-map-marker-alt"></i> {{ $competition->location }}
                            </small>
                        </div>
                        {{-- <a href="{{ route('judge.evaluations.create', $competition->id) }}" class="btn btn-primary">
                            <i class="fas fa-star"></i> Evaluate
                        </a> --}}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
