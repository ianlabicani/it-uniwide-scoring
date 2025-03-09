@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4>Competition Details</h4>

        {{-- Competition Details --}}
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $competition->name }}</h5>
                <p><strong>Date:</strong> {{ $competition->date }}</p>
                <p><strong>Location:</strong> {{ $competition->location }}</p>
                <p><strong>Description:</strong> {{ $competition->description }}</p>
            </div>
        </div>

        {{-- Judges --}}
        <h5>Judges</h5>
        <ul class="list-group mb-4">
            @foreach($competition->judges as $judge)
                <li class="list-group-item">
                    {{ $judge->name }}
                </li>
            @endforeach
        </ul>

        {{-- Contestants --}}
        <h5>Contestants</h5>
        <ul class="list-group mb-4">
            @foreach($competition->contestants as $contestant)
                <li class="list-group-item">
                    {{ $contestant->name }}
                </li>
            @endforeach
        </ul>

        {{-- Criteria --}}
        <h5>Criteria</h5>
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Criteria Name</th>
                    <th>Percentage (%)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($competition->criteria as $index => $criterion)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $criterion->name }}</td>
                        <td>{{ $criterion->percentage }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>



        <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Competitions
        </a>
        <a href="{{ route('admin.competitions.edit', $competition->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
    </div>
@endsection
