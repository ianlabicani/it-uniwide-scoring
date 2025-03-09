@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4>Competitions</h4>

        <a href="{{ route('admin.competitions.create') }}" class="btn btn-primary mb-3">
            <i class="fas fa-plus"></i> Add Competition
        </a>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Location</th>
                        <th>Description</th>
                        <th>Criteria</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($competitions as $competition)
                        <tr>
                            <td>{{ $competition->id }}</td>
                            <td>{{ $competition->name }}</td>
                            <td>{{ \Carbon\Carbon::parse($competition->date)->format('F d, Y') }}</td>
                            <td>{{ $competition->location }}</td>
                            <td>{{ $competition->description ?? 'No description' }}</td>
                            <td>
                                @foreach($competition->criteria as $criterion)
                                    <div>
                                        <strong>{{ $criterion->name }}</strong>: {{ $criterion->percentage }}%
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                <a href="{{ route('admin.competitions.edit', $competition->id) }}"
                                    class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <button class="btn btn-sm btn-danger"
                                    onclick="deleteCompetition({{ $competition->id }}, '{{ $competition->name }}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No competitions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Delete Confirmation Modal -->
    <div class="modal fade" id="deleteCompetitionModal" tabindex="-1" aria-labelledby="deleteCompetitionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteCompetitionForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-trash"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="competitionName"></strong>?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Confirm Delete
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function deleteCompetition(id, name) {
            const form = document.getElementById('deleteCompetitionForm');
            form.action = `/admin/competitions/${id}`;
            document.getElementById('competitionName').textContent = name;

            const modal = new bootstrap.Modal(document.getElementById('deleteCompetitionModal'));
            modal.show();
        }
    </script>
@endsection
