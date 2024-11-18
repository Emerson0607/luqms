<x-layout>
    <div class="col-md-12 m-5" style="width:95%">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Manage Window Personnel</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">

                <!-- Add Modal -->
                <form method="POST" action="/personnel">
                    @csrf
                    <div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content ">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> New</span> <span class="fw-light">Account</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="margin-right:10px;">
                                    <p class="small">Create a new user account.</p>
                                    <div class="row">
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="w_id">Window</label>
                                                <select id="w_id" class="form-control" name="w_id" required>
                                                    <option value="" disabled selected>Select a Window</option>
                                                    @foreach ($all_windows as $all_window)
                                                        <option value="{{ $all_window->w_id }}">{{ $all_window->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('w_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="p_id">Personnel</label>
                                                <select id="p_id" class="form-control" name="p_id" required>
                                                    <option value="" disabled selected>Select a personnel</option>
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->p_id }}">
                                                            {{ $department->firstname }}

                                                            {{ $department->lastname }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('p_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-12 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="department">Department</label>

                                                <select id="department" class="form-control" name="department">
                                                    <option value=" {{ $currentDepartment }}"> {{ $currentDepartment }}
                                                    </option>
                                                </select>
                                                @error('department')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
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

                <!-- Edit Personnel Window Modal -->
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
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editWindow">Personnel</label>
                                            <select id="editWindow" class="form-control" name="editWindow" required>
                                                <option value="" disabled selected></option>
                                                @foreach ($all_windows as $all_window)
                                                    <option value="{{ $all_window->w_id }}">
                                                        {{ $all_window->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editName">Personnel</label>
                                            <select id="editName" class="form-control" name="editName" required>
                                                <option value="" disabled selected></option>
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->p_id }}">
                                                        {{ $department->firstname }}

                                                        {{ $department->lastname }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="editDepartment">Department</label>
                                        <input type="text" name="editDepartment" id="editDepartment"
                                            class="form-control" required>
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

                <!-- Personnel Table -->
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th> <!-- Add this for the number column -->
                                <th>Window</th>
                                <th>Personnel</th>
                                <th>Department</th>

                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->w_name }}</td>
                                    <td>{{ $user->p_fname }} {{ $user->p_lname }}</td>
                                    <td>{{ $user->department }}</td>

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
                                                onclick="deleteUser({{ $user->p_id }})">
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




    {{-- for table --}}

    <div class="col-md-12 m-5" style="width:95%">
        <div class="card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Manage Table</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#addRowModal1">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">

                <!-- Add Modal -->
                <form method="POST" action="/personnel/table">
                    @csrf
                    <div class="modal fade" id="addRowModal1" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content ">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> New</span> <span class="fw-light">Table</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="margin-right:10px;">
                                    <p class="small">Create a new table.</p>
                                    <div class="row">
                                        <div class="col-md-4 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="table_id">ID</label>
                                                <input name="table_id" id="table_id" type="text"
                                                    oninput="formatInput(this)">
                                                @error('table_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="table_window">Window</label>
                                                <input name="table_window" id="table_window" type="text"
                                                    oninput="formatInput(this)">
                                                @error('table_window')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="table_status">Status</label>
                                                <select name="table_status" id="table_status" class="form-control">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                                @error('table_status')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>


                                        <div class="col-md-12 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="table_department">Department</label>

                                                <select id="table_department" class="form-control"
                                                    name="table_department">
                                                    <option value=" {{ $currentDepartment }}">
                                                        {{ $currentDepartment }}
                                                    </option>
                                                </select>
                                                @error('department')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="modal-footer border-0">
                                    <button type="submit" class="btn btn-primary">Add</button>
                                    <button type="button" class="btn btn-danger"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Edit Personnel Window Modal -->
                <div class="modal fade" id="editModal1" tabindex="-1" aria-labelledby="editModalLabel1"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel1">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="editUserForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">

                                    <div class="col-md-4 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="edit_table_id">ID</label>
                                            <input name="edit_table_id" id="edit_table_id" type="text"
                                                oninput="formatInput(this)">
                                            @error('edit_table_id')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="edit_table_window">Window</label>
                                            <input name="edit_table_window" id="edit_table_window" type="text"
                                                oninput="formatInput(this)">
                                            @error('edit_table_window')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="edit_table_status">Status</label>
                                            <select name="edit_table_status" id="edit_table_status"
                                                class="form-control">
                                                <option value="1" selected>Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                            @error('edit_table_status')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="col-md-12 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="edit_table_department">Department</label>

                                            <select id="edit_table_department" class="form-control"
                                                name="edit_table_department">
                                                <option value=" {{ $currentDepartment }}">
                                                    {{ $currentDepartment }}
                                                </option>
                                            </select>
                                            @error('edit_table_department')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
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
                <div class="modal fade" id="deleteModal1" tabindex="-1" aria-labelledby="deleteModalLabel1"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel1">Delete User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this user?</p>
                            </div>
                            <div class="modal-footer">
                                <form id="deleteTableForm" method="POST" action="">
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

                <!-- Personnel Table -->
                <div class="table-responsive">
                    <table id="add-row" class="display table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th> <!-- Add this for the number column -->
                                <th>ID</th>
                                <th>Window</th>
                                <th>Status</th>
                                <th>Department</th>

                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($all_windows_tables as $index => $all_windows_table)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $all_windows_table->w_id }}</td>
                                    <td>{{ $all_windows_table->name }}</td>
                                    <td>
                                        @if ($all_windows_table->status == 1)
                                            Active
                                        @else
                                            Inactive
                                        @endif
                                    </td>

                                    <td>{{ $all_windows_table->department }}</td>

                                    <td>
                                        <div class="form-button-action">
                                            <!-- Edit Button -->
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#editModal1" class="btn btn-link btn-primary btn-lg"
                                                onclick="editTable({{ $all_windows_table->id }})">
                                                <i class="fa fa-edit"></i>
                                            </button>

                                            <!-- Delete Button -->
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#deleteModal1" class="btn btn-link btn-danger"
                                                onclick="deleteTable({{ $all_windows_table->id }})">
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


    @if (session('error'))
        <script>
            window.onload = function() {
                alert("{{ session('error') }}");
            };
        </script>
    @endif

    <script>
        // edit user
        function editUser(pId) {
            const selectedWindow = @json($users).find(user => user.id == pId);

            if (selectedWindow) {


                // Fill the edit form with the user's current data
                document.getElementById('editWindow').value = selectedWindow
                    .w_id; // Use w_id if you're selecting the window ID
                document.getElementById('editName').value = selectedWindow
                    .p_id; // This should be the personnel ID, not the name
                document.getElementById('editDepartment').value = selectedWindow.department; // This assumes it's a string
                document.getElementById('editUserForm').action = `/personnel/${pId}`; // Correct URL for PUT request
            } else {
                console.error('Selected user not found.');
            }
        }

        // Delete user function
        function deleteUser(pId) {
            document.getElementById('deleteUserForm').action = `/personnel/${pId}`;
        }

        // format input
        function formatInput(input) {
            // Remove leading spaces
            input.value = input.value.replace(/^\s+/g, '');

            // Replace multiple spaces with a single space
            input.value = input.value.replace(/\s+/g, ' ');

            // Convert to uppercase
            input.value = input.value.toUpperCase();
        }



        function editTable(pId) {
            const selectedTable = @json($all_windows_tables).find(all_windows_table => all_windows_table.id == pId);

            if (selectedTable) {
                // Fill the edit form with the user's current data
                document.getElementById('edit_table_id').value = selectedTable.w_id
                document.getElementById('edit_table_name').value = selectedTable.name;
                document.getElementById('edit_table_department').value = selectedTable.department;
                document.getElementById('edit_table_status').value = selectedTable.status;
                document.getElementById('editUserForm').action = `/personnel/table/${pId}`; // Correct URL for PUT request
            } else {
                console.error('Selected user not found.');
            }
        }

        // Delete table 
        function deleteTable(pId) {
            document.getElementById('deleteTableForm').action = `/personnel/table/${pId}`;
        }
    </script>

</x-layout>
