<div class="row">
    <div class="col-md-12 m-5" style="width:95%">
        @if ($errors->has('editWName'))
            <p id="error-message" class="alert alert-danger">
                {{ $errors->first('editWName') }}
            </p>

            <script type="text/javascript">
                // Set timeout to hide the error message after 3 seconds
                setTimeout(function() {
                    const errorMessage = document.getElementById('error-message');
                    if (errorMessage) {
                        errorMessage.style.display = 'none'; // Hide the error message
                    }
                }, 3000); // 3000 milliseconds = 3 seconds
            </script>
        @endif


        <div class="card">


            <div class="card-header">
                <div class="d-flex align-items-center">
                    <h4 class="card-title">Manage Window</h4>
                    <button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal"
                        data-bs-target="#manageWindow">
                        <i class="fa fa-plus"></i> Add
                    </button>
                </div>
            </div>
            <div class="card-body">

                <!-- Add Modal -->
                <form method="POST" action="/personnel">
                    @csrf
                    <div class="modal fade" id="manageWindow" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog " role="document">
                            <div class="modal-content ">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">
                                        <span class="fw-mediumbold"> QMS</span> <span class="fw-light">Window</span>
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body" style="margin-right:10px;">
                                    <p class="small">Create a new window.</p>
                                    <div class="row">
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="w_id">Window</label>
                                                {{-- <select id="w_id" class="form-control" name="w_id" required>
                                                    <option value="" disabled selected>Select a Window</option>
                                                    @if ($inactiveWindows)
                                                        @foreach ($inactiveWindows as $inactiveWindow)
                                                            <option value="{{ $inactiveWindow->w_id }}">
                                                                {{ $inactiveWindow->w_name }}
                                                            </option>
                                                            <input style="display: none;" type="text" name="w_name"
                                                                id="w_name" value="{{ $inactiveWindow->w_name }}">
                                                        @endforeach
                                                    @else
                                                        <p>No window available for your department.</p>
                                                    @endif
                                                </select> --}}

                                                <input style="border:0;" type="text" name="w_name" id="w_name"
                                                    oninput="formatInput(this)">
                                            </div>
                                        </div>

                                        {{-- for w_status --}}
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="w_status">Status</label>
                                                <select name="w_status" id="w_status" class="form-control">
                                                    <option value="1" selected>Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="p_id">Personnel</label>
                                                <select id="p_id" class="form-control" name="p_id" required>
                                                    <option value="" disabled selected>Select a personnel</option>
                                                    @if ($personnels)
                                                        @foreach ($personnels as $personnel)
                                                            <option value="{{ $personnel->p_id }}">
                                                                {{ $personnel->firstname }} {{ $personnel->lastname }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <p>No window available for your department.</p>
                                                    @endif
                                                </select>
                                                @error('p_id')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Services --}}
                                    @if ($services)
                                        @foreach ($services as $service)
                                            <div class="col-md-12 col-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="services[]"
                                                        value="{{ $service->service_id }}"
                                                        id="service-{{ $service->service_id }}">
                                                    <label class="form-check-label text-wrap"
                                                        for="service-{{ $service->service_id }}">
                                                        {{ $service->service_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No services available for your department.</p>
                                    @endif

                                    {{-- for dept_id --}}
                                    <input type="text" style="display: none;" name="dept_id" id="dept_id"
                                        value="{{ session('current_department_id') }}">
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
                <div class="modal fade" id="editQueueModal" tabindex="-1" aria-labelledby="editModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Window</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="editQueueForm" method="POST" action="">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row">
                                        {{-- for window name --}}
                                        <div class="col-md-4 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="editWName">Window</label>
                                                <input style="border:0;" type="text" name="editWName"
                                                    id="editWName" oninput="formatInput(this)">
                                            </div>
                                        </div>

                                        {{-- for personnel --}}
                                        <div class="col-md-8 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="editPersonnel">Personnel</label>
                                                <select id="editPersonnel" class="form-control" name="editPersonnel"
                                                    required>

                                                    @if ($personnels)
                                                        @foreach ($personnels as $personnel)
                                                            <option value="{{ $personnel->p_id }}">
                                                                {{ $personnel->firstname }} {{ $personnel->lastname }}
                                                            </option>
                                                        @endforeach
                                                    @else
                                                        <p>No window available for your department.</p>
                                                    @endif
                                                </select>

                                            </div>
                                        </div>

                                        {{-- for w_status --}}
                                        <div class="col-md-6 pe-0">
                                            <div class="form-group form-group-default">
                                                <label for="editStatus">Status</label>
                                                <select name="editStatus" id="editStatus" class="form-control">
                                                    <option value="1">Active</option>
                                                    <option value="0">Inactive</option>
                                                </select>
                                            </div>
                                        </div>


                                        {{-- services --}}
                                        <div class="col-md-12 col-12 mb-3">
                                            <label>Services</label>
                                            @if ($services)
                                                @foreach ($services as $service)
                                                    <div class="form-check">
                                                        <input class="form-check-input qmsQueueCheckbox"
                                                            type="checkbox" name="editService[]"
                                                            value="{{ $service->service_id }}"
                                                            id="editService-{{ $service->service_id }}">
                                                        <label class="form-check-label"
                                                            for="editService-{{ $service->service_id }}">
                                                            {{ $service->service_name }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p>No services available for your department.</p>
                                            @endif
                                        </div>

                                        {{-- for dept_id --}}
                                        <input type="text" style="display: none;" name="editDeptId"
                                            id="editDeptId" value="{{ session('current_department_id') }}">

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
                <div class="modal fade" id="deleteQueueModal" tabindex="-1" aria-labelledby="deleteModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Delete Window</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete this window?</p>
                            </div>
                            <div class="modal-footer">
                                <form id="deleteQueueForm" method="POST" action="">
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
                                <th>#</th>
                                <th>Window</th>
                                <th>Personnel</th>
                                <th>Status</th>

                                <th style="width: 10%">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @if ($windowLists)
                                @foreach ($windowLists as $index => $i)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $i->w_name }}</td>
                                        <td>
                                            @php
                                                $personnel = $this->personnels->firstWhere('p_id', $i->p_id);
                                            @endphp
                                            @if ($personnel)
                                                {{ $personnel->firstname }} {{ $personnel->lastname }}
                                            @else
                                                No Personnel Assigned
                                            @endif
                                        </td>
                                        <td>
                                            @if ($i->w_status == 1)
                                                Active
                                            @else
                                                Inactive
                                            @endif
                                        </td>

                                        <td>
                                            <div class="form-button-action">
                                                <!-- Edit Button -->
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#editQueueModal"
                                                    class="btn btn-link btn-primary btn-lg"
                                                    onclick="editQueueWindow({{ $i->id }}, '{{ $i->w_name }}', {{ $i->dept_id }})">
                                                    <i class="fa fa-edit"></i>
                                                </button>


                                                <!-- Delete Button -->
                                                <button type="button" data-bs-toggle="modal"
                                                    data-bs-target="#deleteQueueModal" class="btn btn-link btn-danger"
                                                    onclick="deleteQueueWindow({{ $i->id }})">
                                                    <i class="fa fa-times"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <p>No window available for your department.</p>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- INPUT VALIDATION --}}
<script>
    // format input
    function formatInput(input) {
        // Remove leading spaces
        input.value = input.value.replace(/^\s+/g, '');

        // Replace multiple spaces with a single space
        input.value = input.value.replace(/\s+/g, ' ');

        // Convert to uppercase
        input.value = input.value.toUpperCase();
    }
</script>

{{-- CRUD --}}
<script>
    function setDropdownValue(dropdown, selectedValue, displayText = null) {
        // Check if the selected value exists in the dropdown
        let optionExists = false;
        Array.from(dropdown.options).forEach(option => {
            if (option.value == selectedValue) {
                optionExists = true;
                dropdown.value = selectedValue; // Set the value if it exists
            }
        });

        // Add a new hidden option if the value doesn't exist
        if (!optionExists) {
            const newOption = new Option(displayText || selectedValue, selectedValue);
            newOption.style.display = "none"; // Hide the new option
            dropdown.add(newOption);
            dropdown.value = selectedValue; // Set the new value as selected
        }
    }

    // Edit window
    function editQueueWindow(pId, wName, deptId) {

        const selectedWindow = @json($windowLists).find(windowList => windowList.id == pId);

        if (selectedWindow) {
            // Extract values from the selected window
            const {
                w_name: selectedWName = '',
                p_id: selectedPersonnelId = '',
                w_status: selectedStatus = '' // Assuming this contains the selected service IDs
            } = selectedWindow;

            // Update dropdowns
            // setDropdownValue(document.getElementById('editWName'), selectedWName);
            setDropdownValue(document.getElementById('editPersonnel'), selectedPersonnelId);
            setDropdownValue(
                document.getElementById('editStatus'),
                selectedStatus,
                selectedStatus == '1' ? 'Active' : 'Inactive'
            );
            document.getElementById('editWName').value = selectedWName;

            const url = `/get-associated-services/${wName}/${deptId}`;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Network response was not ok: ${response.statusText}`);
                    }
                    return response.json();
                })
                .then(associatedServices => {
                    console.log("Associated Services: ", associatedServices);

                    document.querySelectorAll('.qmsQueueCheckbox').forEach(checkbox => {
                        // checkbox.checked = false; 

                        if (associatedServices.includes(parseInt(checkbox.value))) {
                            checkbox.checked = true; // Check associated services
                        }
                    });
                })
                .catch(error => {
                    console.error('Error fetching associated services:', error);
                });

            // Set the form action URL
            document.getElementById('editQueueForm').action = `/personnel/${pId}`;
        } else {
            console.error('Selected user not found.');
        }
    }

    // Delete window
    function deleteQueueWindow(pId) {
        document.getElementById('deleteQueueForm').action = `/personnel/${pId}`;
    }
</script>