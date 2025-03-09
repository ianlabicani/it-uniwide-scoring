@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4>Add Competition</h4>

        <form action="{{ route('admin.competitions.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Date</label>
                <input type="date" name="date" class="form-control @error('date') is-invalid @enderror"
                    value="{{ old('date') }}" required>
                @error('date')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Location</label>
                <input type="text" name="location" class="form-control @error('location') is-invalid @enderror"
                    value="{{ old('location') }}" required>
                @error('location')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Description</label>
                <textarea name="description"
                    class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <h5>Criteria</h5>
            <div id="criteria-list">
                @if(old('criteria'))
                    @foreach(old('criteria') as $index => $criterion)
                        <div class="input-group mb-2">
                            <input type="text" name="criteria[{{ $index }}][name]"
                                class="form-control @error("criteria.{$index}.name") is-invalid @enderror"
                                placeholder="Criteria Name" value="{{ $criterion['name'] }}" required>
                            <input type="number" name="criteria[{{ $index }}][percentage]"
                                class="form-control @error("criteria.{$index}.percentage") is-invalid @enderror" placeholder="%"
                                value="{{ $criterion['percentage'] }}" required>
                            <button type="button" class="btn btn-danger" onclick="removeCriteria(this)">
                                <i class="fas fa-trash"></i>
                            </button>
                            @error("criteria.{$index}.name")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @error("criteria.{$index}.percentage")
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    @endforeach
                @else
                    <div class="input-group mb-2">
                        <input type="text" name="criteria[0][name]" class="form-control" placeholder="Criteria Name" required>
                        <input type="number" name="criteria[0][percentage]" class="form-control" placeholder="%" required>
                        <button type="button" class="btn btn-danger" onclick="removeCriteria(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                @endif
            </div>

            <button type="button" class="btn btn-primary mb-3" onclick="addCriteria()">
                <i class="fas fa-plus"></i> Add Criteria
            </button>

            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Save
            </button>
        </form>
    </div>

    <script>
        let criteriaCount = {{ old('criteria') ? count(old('criteria')) : 1 }};

        function addCriteria() {
            let html = `
                    <div class="input-group mb-2">
                        <input type="text" name="criteria[\${criteriaCount}][name]" class="form-control" placeholder="Criteria Name" required>
                        <input type="number" name="criteria[\${criteriaCount}][percentage]" class="form-control" placeholder="%" required>
                        <button type="button" class="btn btn-danger" onclick="removeCriteria(this)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            document.getElementById('criteria-list').insertAdjacentHTML('beforeend', html);
            criteriaCount++;
        }

        function removeCriteria(button) {
            button.closest('.input-group').remove();
        }
    </script>
@endsection
