@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4 class="mb-3 d-flex justify-content-between">
            <span>Users</span>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJudgeModal">
                <i class="fas fa-user-plus"></i> Add Judge
            </button>
        </h4>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Roles</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->isEmpty())
                                    <span class="badge bg-secondary">No Role</span>
                                @else
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if(!$user->roles->contains('name', 'admin')) {{-- Check if the user is not Admin --}}
                                    <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteJudgeModal"
                                        data-user-id="{{ $user->id }}" data-user-name="{{ $user->name }}">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                @else
                                    <span class="text-muted"><i class="fas fa-ban"></i> Cannot Delete</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ✅ Add Judge Modal -->
    <div class="modal fade" id="addJudgeModal" tabindex="-1" aria-labelledby="addJudgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.users.addJudge') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addJudgeModalLabel">Add Judge</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <input type="hidden" name="role" value="Judge">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Judge
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ DELETE CONFIRMATION MODAL -->
    <div class="modal fade" id="deleteJudgeModal" tabindex="-1" aria-labelledby="deleteJudgeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="deleteJudgeForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="deleteJudgeModalLabel">
                            <i class="fas fa-trash"></i> Confirm Delete
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete <strong id="judgeName"></strong>?
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
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteJudgeModal = document.getElementById('deleteJudgeModal');
            const deleteJudgeForm = document.getElementById('deleteJudgeForm');
            const judgeNameText = document.getElementById('judgeName');

            deleteJudgeModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget;
                const userId = button.getAttribute('data-user-id');
                const userName = button.getAttribute('data-user-name');

                // ✅ Update the modal text
                judgeNameText.textContent = userName;

                // ✅ Update the form action
                deleteJudgeForm.action = `/admin/users/${userId}`;
            });
        });
    </script>
@endsection
