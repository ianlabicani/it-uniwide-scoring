@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4 class="mb-3">Edit Competition</h4>

        <form action="{{ route('admin.competitions.update', $competition->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" value="{{ $competition->name }}" required>
            </div>

            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ $competition->date }}" required>
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ $competition->location }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description (Optional)</label>
                <textarea name="description" class="form-control" rows="4">{{ $competition->description }}</textarea>
            </div>

            <h5>Criteria</h5>
            <div id="criteria-container">
                @foreach($competition->criteria as $criterion)
                    <div class="row mb-2 criterion-item">
                        <input type="hidden" name="criteria[{{ $loop->index }}][id]" value="{{ $criterion->id }}">
                        <div class="col-md-6">
                            <label class="form-label">Criteria Name</label>
                            <input type="text" name="criteria[{{ $loop->index }}][name]" class="form-control"
                                value="{{ $criterion->name }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Percentage (%)</label>
                            <input type="number" name="criteria[{{ $loop->index }}][percentage]" class="form-control"
                                value="{{ $criterion->percentage }}" min="1" max="100" required>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm"
                                onclick="removeCriterion(this, {{ $criterion->id }})">
                                <i class="fas fa-trash"></i> Remove
                            </button>
                        </div>
                    </div>

                @endforeach
            </div>

            <button type="button" class="btn btn-primary btn-sm mt-3" onclick="addCriterion()">
                <i class="fas fa-plus"></i> Add Criteria
            </button>

            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('admin.competitions.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Update Competition
                </button>
            </div>
        </form>
    </div>

    <script>
        let criterionIndex = {{ $competition->criteria->count() }};

        function addCriterion() {
            const container = document.getElementById('criteria-container');

            const html = `
                                <div class="row mb-2 criterion-item">
                                    <div class="col-md-6">
                                        <label class="form-label">Criteria Name</label>
                                        <input type="text" name="criteria[${criterionIndex}][name]" class="form-control" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Percentage (%)</label>
                                        <input type="number" name="criteria[${criterionIndex}][percentage]" class="form-control" min="1" max="100" required>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.criterion-item').remove()">
                                            <i class="fas fa-trash"></i> Remove
                                        </button>
                                    </div>
                                </div>
                            `;

            container.insertAdjacentHTML('beforeend', html);
            criterionIndex++;
        }

        function removeCriterion(button, criterionId) {
            // Mark it as deleted
            button.closest('.criterion-item').remove();
            const input = `<input type="hidden" name="deleted_criteria[]" value="${criterionId}">`;
            document.querySelector('form').insertAdjacentHTML('beforeend', input);
        }
    </script>
@endsection
