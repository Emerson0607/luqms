<x-layout>
    <div class="col-md-12 m-5" style="width:95%">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Manage User</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Add Modal -->
                <form method="POST" action="/user">
                    @csrf
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> New</span> <span class="fw-light">Account</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p class="small">Create a new user account.</p>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label for="w_id">Window ID</label>
                                                <input id="w_id" type="text" class="form-control"
                                                    placeholder="fill w_id" name="w_id" />
                                                <x-form-error name="w_id" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group form-group-default">
                                                <label for="name">Name</label>
                                                <input id="name" type="text" class="form-control"
                                                    placeholder="fill name" name="name" />
                                                <x-form-error name="name" />
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="department">Department</label>
                                                <select id="department" class="form-control" name="department">
                                                    <option value="" disabled selected>Select a Department
                                                    </option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->name }}">{{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="email">Email</label>
                                                <input id="email" type="email" class="form-control"
                                                    placeholder="fill email" name="email" />
                                                <x-form-error name="email" />
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-group-default">
                                                <label for="password">Password</label>
                                                <input id="password" type="password" name="password"
                                                    class="form-control" placeholder="fill password" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Edit User Modal -->
                <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="editUserForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="editName">Name</label>
                                        <input type="text" name="name" id="editName" class="form-control"
                                            required>
                                    </div>

                                    <div class="form-group">
                                        <label for="editDepartment">Department</label>
                                        <input type="text" name="department" id="editDepartment"
                                            class="form-control" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="editEmail">Email</label>
                                        <input type="email" name="email" id="editEmail" class="form-control"
                                            required>
                                        <x-form-error name="email" />
                                    </div>

                                    <div class="form-group">
                                        <label for="editPassword">Password (Leave blank to keep current
                                            password)</label>
                                        <input type="password" name="password" id="editPassword"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                    <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Modal -->
                <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this user?</p>
                            </div>
                            <div class="modal-footer">
                                <form id="deleteUserForm" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th> <!-- Add this for the number column -->
                                <th>Name</th>
                                <th>Office</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <!-- Use $index to get the row number -->
                                <tr>
                                    <td>{{ $index + 1 }}</td> <!-- Display the row number (starting from 1) -->
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->department }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->password }}</td>
                                    <td>
                                        <div class="form-button-action">
                                            <!-- Edit Button -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editModal"
                                                class="btn btn-link btn-primary btn-lg"
                                                onclick="editUser({{ $user->id }})">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal" class="btn btn-link btn-danger"
                                                onclick="deleteUser({{ $user->id }})">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>

    <script>
        // Edit user function
        function editUser(userId) {
            const user = @json($users); // Make the users data available to JavaScript
            const selectedUser = user.find(u => u.id === userId);

            document.getElementById('editName').value = selectedUser.name;
            document.getElementById('editDepartment').value = selectedUser.department;
            document.getElementById('editEmail').value = selectedUser.email;
            document.getElementById('editPassword').value = ''; // Leave the password field empty

            // Update the form action
            document.getElementById('editUserForm').action = `/user/${userId}`;
        }

        // Delete user function
        function deleteUser(userId) {
            document.getElementById('deleteUserForm').action = `/user/${userId}`;
        }
    </script>
</x-layout>
