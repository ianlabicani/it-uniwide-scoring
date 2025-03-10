@extends('admin.shell')

@section('admin-content')
    <div class="container mt-4">
        <h4 class="mb-3">Users</h4>

        <!-- ✅ Tabs for Judges and Contestants -->
        <ul class="nav nav-tabs mb-3" id="userTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="judges-tab" data-bs-toggle="tab" data-bs-target="#judges"
                    type="button" role="tab">Judges</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="contestants-tab" data-bs-toggle="tab" data-bs-target="#contestants"
                    type="button" role="tab">Contestants</button>
            </li>
        </ul>

        <div class="tab-content" id="userTabsContent">
            <!-- ✅ Judges Table -->
            <div class="tab-pane fade show active" id="judges" role="tabpanel">
                <h5 class="d-flex justify-content-between">
                    <span>Judges</span>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addJudgeModal">
                        <i class="fas fa-user-plus"></i> Add Judge
                    </button>
                </h5>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($judges as $judge)
                                <tr>
                                    <td>{{ $judge->id }}</td>
                                    <td>{{ $judge->name }}</td>
                                    <td>{{ $judge->email }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteJudgeModal"
                                            data-user-id="{{ $judge->id }}" data-user-name="{{ $judge->name }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ✅ Contestants Table -->
            <div class="tab-pane fade" id="contestants" role="tabpanel">
                <h5 class="d-flex justify-content-between">
                    <span>Contestants</span>
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addContestantModal">
                        <i class="fas fa-user-plus"></i> Add Contestant
                    </button>
                </h5>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($contestants as $contestant)
                                <tr>
                                    <td>{{ $contestant->id }}</td>
                                    <td>{{ $contestant->name }}</td>
                                    <td>{{ $contestant->email }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#deleteJudgeModal"
                                            data-user-id="{{ $contestant->id }}" data-user-name="{{ $contestant->name }}">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
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
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <input type="hidden" name="role" value="Judge">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Judge</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ✅ Add Contestant Modal -->
    <div class="modal fade" id="addContestantModal" tabindex="-1" aria-labelledby="addContestantModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.users.addContestant') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addContestantModalLabel">Add Contestant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-3" placeholder="Name" required>
                        <input type="email" name="email" class="form-control mb-3" placeholder="Email" required>
                        <input type="password" name="password" class="form-control mb-3" placeholder="Password" required>
                        <input type="hidden" name="role" value="Contestant">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Contestant</button>
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
