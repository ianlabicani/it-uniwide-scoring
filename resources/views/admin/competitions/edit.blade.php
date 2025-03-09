@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4>Edit Competition</h4>

        <form action="{{ route('admin.competitions.update', $competition->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Competition Name</label>
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
                <label for="description" class="form-label">Description</label>
                <textarea name="description" class="form-control">{{ $competition->description }}</textarea>
            </div>

            {{-- Judges --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="judges" class="form-label">Assign Judges</label>
                        <select name="judges[]" class="form-control" multiple>
                            @foreach($judges as $judge)
                                <option value="{{ $judge->id }}"
                                    {{ $competition->judges->contains($judge->id) ? 'selected' : '' }}>
                                    {{ $judge->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Contestants --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="contestants" class="form-label">Assign Contestants</label>
                        <select name="contestants[]" class="form-control" multiple>
                            @foreach($contestants as $contestant)
                                <option value="{{ $contestant->id }}"
                                    {{ $competition->contestants->contains($contestant->id) ? 'selected' : '' }}>
                                    {{ $contestant->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            {{-- Criteria --}}
            <h5>Criteria</h5>
            <div id="criteria-container">
                @foreach ($competition->criteria as $index => $criterion)
                    <div class="criteria-item">
                        <div class="input-group mb-2">
                            <input type="hidden" name="criteria[{{ $index }}][id]" value="{{ $criterion->id }}">
                            <input type="text" name="criteria[{{ $index }}][name]" class="form-control"
                                value="{{ $criterion->name }}" placeholder="Criteria Name" required>
                            <input type="number" name="criteria[{{ $index }}][percentage]" class="form-control"
                                value="{{ $criterion->percentage }}" placeholder="Percentage" required>
                            <button type="button" class="btn btn-danger remove-criteria">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary mb-3" id="add-criteria">
                <i class="fas fa-plus"></i> Add Criteria
            </button>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </form>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let criteriaIndex = {{ $competition->criteria->count() }};

            document.getElementById('add-criteria').addEventListener('click', function () {
                const container = document.getElementById('criteria-container');
                const newCriteria = `
                        <div class="criteria-item">
                            <div class="input-group mb-2">
                                <input type="hidden" name="criteria[${criteriaIndex}][id]" value="">
                                <input type="text" name="criteria[${criteriaIndex}][name]" class="form-control"
                                    placeholder="Criteria Name" required>
                                <input type="number" name="criteria[${criteriaIndex}][percentage]" class="form-control"
                                    placeholder="Percentage" required>
                                <button type="button" class="btn btn-danger remove-criteria">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    `;
                container.insertAdjacentHTML('beforeend', newCriteria);
                criteriaIndex++;
            });

            document.getElementById('criteria-container').addEventListener('click', function (event) {
                if (event.target.classList.contains('remove-criteria')) {
                    event.target.closest('.criteria-item').remove();
                }
            });
        });
    </script>
@endpush
