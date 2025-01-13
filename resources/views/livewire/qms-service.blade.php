
<!-- filepath: /c:/Users/joshu/Herd/luqms/resources/views/livewire/qms-service.blade.php -->
<div >

<div class="manage-window-card">

    {{-- sweet alert for error handling --}}
    @if ($errors->has('editWName'))
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first('editWName') }}',
                timer: 3000, // Auto-close after 3 seconds
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->has('editPersonnel'))
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first('editPersonnel') }}',
                timer: 3000, // Auto-close after 3 seconds
                showConfirmButton: false
            });
        </script>
    @endif

    @if ($errors->has('editSWName'))
        <script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '{{ $errors->first('editSWName') }}',
                timer: 3000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('sweetalert'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('sweetalert') }}',
            });
        </script>
    @endif

    {{-- for manage window --}}
    <div class="card mw-table">
        <div class="mw-header">
            <div class="d-flex align-items-center">
                <h4 class="mw-title">Manage Window</h4>
                <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#manageWindow">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="card-body mw-table-body">
            <!-- Add Modal -->
            <form method="POST" action="/personnel">
                @csrf
                <div class="modal fade" id="manageWindow" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> QMS</span> <span class="fw-light">Window</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body mw-add-window-body" style="margin-right:10px;">
                                <p class="small">Create a new window.</p>
                                <div class="row">
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="w_id">Window</label>
                                            <input style="border:0;" type="text" name="w_name" id="w_name" oninput="formatInput(this)">
                                        </div>
                                    </div>
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
                                <div class="mw-services-card">
                                    {{-- Services --}}
                                    @if ($services)
                                        @foreach ($services as $service)
                                            <div class="col-md-12 col-12 mb-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="services[]"
                                                        value="{{ $service->service_id }}" id="service-{{ $service->service_id }}">
                                                    <label class="form-check-label text-wrap" for="service-{{ $service->service_id }}">
                                                        {{ $service->service_name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <p>No services available for your department.</p>
                                    @endif
                                </div>
                                {{-- for dept_id --}}
                                <input type="text" style="display: none;" name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
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
            <div class="modal fade" id="editQueueModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel">Edit Window</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editQueueForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <div class="modal-body mw-edit-modal">
                                <div class="row">
                                    {{-- for window name --}}
                                    <div class="col-md-4 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editWName">Window</label>
                                            <input style="border:0;" type="text" name="editWName" id="editWName" oninput="formatInput(this)">
                                        </div>
                                    </div>
                                    {{-- for personnel --}}
                                    <div class="col-md-8 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editPersonnel">Personnel</label>
                                            <select id="editPersonnel" class="form-control" name="editPersonnel" required>
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
                                    <div class="col-md-5 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editStatus">Status</label>
                                            <select name="editStatus" id="editStatus" class="form-control">
                                                <option value="1">Active</option>
                                                <option value="0">Inactive</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editShared">Shared Window</label>
                                            <select name="editShared" id="editShared" class="form-control">
                                                <option value="None">None</option>
                                                @if ($sharedWindows->isNotEmpty())
                                                    @foreach ($sharedWindows as $window)
                                                        <option value="{{ $window->w_name }}">{{ $window->w_name }}</option>
                                                    @endforeach
                                                @else
                                                    <option value="" disabled>No available options</option>
                                                @endif
                                            </select>
                                        </div>
                                    </div>
                                    {{-- services --}}
                                    <div class="mw-services-card">
                                        <label>Services</label>
                                        @if ($services)
                                            @foreach ($services as $service)
                                                <div class="form-check">
                                                    <input class="form-check-input qmsQueueCheckbox" type="checkbox" name="editService[]"
                                                        value="{{ $service->service_id }}" id="editService-{{ $service->service_id }}">
                                                    <label class="form-check-label mw-services-label" for="editService-{{ $service->service_id }}">
                                                        {{ $service->service_name }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        @else
                                            <p>No services available for your department.</p>
                                        @endif
                                    </div>
                                    {{-- for dept_id --}}
                                    <input type="text" style="display: none;" name="editDeptId" id="editDeptId" value="{{ session('current_department_id') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteQueueModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteModalLabel">Delete Window</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this window?</p>
                        </div>
                        <div class="modal-footer">
                            <form id="deleteQueueForm" method="POST" action="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Window Table -->
            <div class="table-responsive mw-table-content">
                <table id="add-row" class="display table">
                    <thead>
                        <tr class="mw-column-name">
                            {{-- <th>#</th> --}}
                            <th>Window</th>
                            <th>Personnel</th>
                            <th>Status</th>
                            <th>Shared</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($windowLists->isNotEmpty())
                            @foreach ($windowLists as $index => $i)
                                <tr class="mw-column-name">
                                    {{-- <td>{{ $index + 1 }}</td> --}}
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
                                    <td>{{ $i->shared_name }}</td>
                                    <td>
                                        <div class="form-button-action">
                                            <!-- Edit Button -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editQueueModal"
                                                class="btn btn-link btn-primary btn-lg"
                                                onclick="editQueueWindow({{ $i->id }}, '{{ $i->w_name }}', {{ $i->dept_id }})">
                                                <i class="fa fa-edit mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteQueueModal"
                                                class="btn btn-link btn-danger" onclick="deleteQueueWindow({{ $i->id }})">
                                                <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="mw-column-name">
                                <td colspan="6" class="text-center">No window available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- for shared window --}}
    <div class="card mw-table">
        <div class="mw-header">
            <div class="d-flex align-items-center">
                <h4 class="mw-title">Shared Window</h4>
                <button class="mw-btn-add ms-auto" data-bs-toggle="modal" data-bs-target="#manageSharedWindow">
                    <i class="fa fa-plus"></i>
                </button>
            </div>
        </div>
        <div class="mw-table-body">
            <!-- Add shared Modal -->
            <form method="POST" action="/sharedWindow">
                @csrf
                <div class="modal fade" id="manageSharedWindow" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header border-0">
                                <h5 class="modal-title">
                                    <span class="fw-mediumbold"> Shared</span> <span class="fw-light">Window</span>
                                </h5>
                                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" style="margin-right:10px;">
                                <p class="small">Create new shared window.</p>
                                <div class="row">
                                    <div class="col-md-6 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="w_name">Shared Window Name</label>
                                            <input style="border:0;" type="text" name="w_name" id="w_name" oninput="formatInput(this)">
                                        </div>
                                    </div>
                                </div>
                                {{-- for dept_id --}}
                                <input type="text" style="display: none;" name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
                            </div>
                            <div class="modal-footer border-0">
                                <button type="submit" class="btn btn-primary">Add</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Edit shared Modal -->
            <div class="modal fade" id="editSharedModal" tabindex="-1" aria-labelledby="editSharedLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editSharedLabel">Edit Shared Window</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editSharedForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="row">
                                    {{-- for window name --}}
                                    <div class="col-md-4 pe-0">
                                        <div class="form-group form-group-default">
                                            <label for="editSWName">Shared Window Name</label>
                                            <input style="border:0;" type="text" name="editSWName" id="editSWName" oninput="formatInput(this)">
                                        </div>
                                    </div>
                                    {{-- for dept_id --}}
                                    <input type="text" style="display: none;" name="editSDeptId" id="editSDeptId" value="{{ session('current_department_id') }}">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Save changes</button>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete shared modal -->
            <div class="modal fade" id="deleteSharedModal" tabindex="-1" aria-labelledby="deleteSharedLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteSharedLabel">Delete Window</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this shared window?</p>
                        </div>
                        <div class="modal-footer">
                            <form id="deleteSharedForm" method="POST" action="">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manage Shared Window Table -->
            <div class="table-responsive mw-table-content">
                <table id="add-row" class="display table">
                    <thead>
                        <tr class="mw-column-name">
                            {{-- <th>#</th> --}}
                            <th>Shared Window Name</th>
                            <th style="width: 10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($sharedWindows->isNotEmpty())
                            @foreach ($sharedWindows as $index => $sharedWindow)
                                <tr class="mw-column-name">
                                    {{-- <td>{{ $index + 1 }}</td> --}}
                                    <td>{{ $sharedWindow->w_name }}</td>
                                    <td>
                                        <div class="form-button-action">
                                            <!-- Edit Button -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#editSharedModal"
                                                class="btn btn-link btn-primary btn-lg"
                                                onclick="editSharedWindow({{ $sharedWindow->id }}, '{{ $sharedWindow->w_name }}', {{ $sharedWindow->dept_id }})">
                                                <i class="fa fa-edit mw-btn-edit"><span class="mw-btn-edit-text">Edit</span></i>
                                            </button>
                                            <!-- Delete Button -->
                                            <button type="button" data-bs-toggle="modal" data-bs-target="#deleteSharedModal"
                                                class="btn btn-link btn-danger" onclick="deleteSharedWindow({{ $sharedWindow->id }})">
                                                <i class="fa fa-times"><span class="mw-btn-edit-text">Delete</span></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="mw-column-name">
                                <td colspan="3" class="text-center">No shared window available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="manage-charter">
    @if ($charters)
        @if ($charters->video1 ===  NULL)
            <!-- Show the form if video1 is null -->
            <form action="/charter1" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 p-6 mx-auto form-container" id="uploadForm">
                @csrf
                <div>
                    <label class="block text-black" for="video1">Upload Residential ID *</label>
                    <input class="border border-border rounded-lg p-2 w-full" type="file" name="video1" id="video1" required />
                    <input type="text" readonly name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
                </div>

                <!-- Simple Progress Bar -->
                <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
                    <div id="progressBar" class="bg-blue-500 h-4 rounded-full" style="width: 0%"></div>
                </div>

                <!-- Loading Spinner -->
                <div id="loadingSpinner" style="display: none;" class="mt-4">
                    <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>

                <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 p-2 rounded-lg w-full mt-4">Register</button>
            </form>
        @else

        <div class="video1">
            <h1>Charter 1</h1>
            <!-- Display the video if video1 is not null -->
            <video width="100%" height="240" controls>
                <source src="{{ asset('storage/' . $charters->video1) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <!-- Delete button for video1 -->
            <form id="deleteVideoForm" action="{{ route('charter.deleteVideo', $charters->id) }}" method="POST" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="button" id="deleteVideoButton">
                    <i class="fas fa-times mr-2"></i>
                </button>
            </form>
        </div>
        @endif
    @else
       <!-- Show the form if video1 is null -->
       <form action="/charter1" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 p-6 mx-auto form-container" id="uploadForm">
        @csrf
        <div>
            <label class="block text-black" for="video1">Upload Residential ID *</label>
            <input class="border border-border rounded-lg p-2 w-full" type="file" name="video1" id="video1" required />
            <input type="text" readonly name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
        </div>

        <!-- Simple Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
            <div id="progressBar" class="bg-blue-500 h-4 rounded-full" style="width: 0%"></div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" style="display: none;" class="mt-4">
            <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>

        <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 p-2 rounded-lg w-full mt-4">Register</button>
    </form>
    @endif


    {{-- for video2 --}}
    @if ($charters)
    @if ($charters->video2 ===  NULL)
        <!-- Show the form if video1 is null -->
        <form action="/charter2" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 p-6 mx-auto form-container" id="uploadForm2">
            @csrf
            <div>
                <label class="block text-black" for="video2">Upload Residential ID *</label>
                <input class="border border-border rounded-lg p-2 w-full" type="file" name="video2" id="video2" required />
                <input type="text" readonly name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
            </div>

            <!-- Simple Progress Bar -->
            <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
                <div id="progressBar" class="bg-blue-500 h-4 rounded-full" style="width: 0%"></div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" style="display: none;" class="mt-4">
                <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>

            <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 p-2 rounded-lg w-full mt-4">Register</button>
        </form>
    @else

    {{--charter 2 button not working when the charter1 is delete  --}}




    <div class="video1">
        <h1>Charter 2</h1>
        <!-- Display the video if video1 is not null -->
        <video width="100%" height="240" controls>
            <source src="{{ asset('storage/' . $charters->video2) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>

        <!-- Delete button for video1 -->
        <form id="deleteVideoForm2" action="{{ route('charter.deleteVideo2', $charters->id) }}" method="POST" class="mt-4">
            @csrf
            @method('DELETE')
            <button type="button" id="deleteVideoButton2">
                <i class="fas fa-times mr-2"></i>
            </button>
        </form>
    </div>
    @endif
@else
   <!-- Show the form if video1 is null -->
   <form action="/charter2" method="POST" enctype="multipart/form-data" class="space-y-4 mt-4 p-6 mx-auto form-container" id="uploadForm2">
    @csrf
    <div>
        <label class="block text-black" for="video2">Upload video to display</label>
        <input class="border border-border rounded-lg p-2 w-full" type="file" name="video2" id="video2" required />
        <input type="text" readonly name="dept_id" id="dept_id" value="{{ session('current_department_id') }}">
    </div>

    <!-- Simple Progress Bar -->
    <div class="w-full bg-gray-200 rounded-full h-4 mt-4">
        <div id="progressBar" class="bg-blue-500 h-4 rounded-full" style="width: 0%"></div>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" style="display: none;" class="mt-4">
        <div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-blue-500" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>

    <button type="submit" class="bg-blue-500 text-white hover:bg-blue-600 p-2 rounded-lg w-full mt-4">Register</button>
</form>
@endif
</div>
</div>

<!-- Include SweetAlert library (if not already included) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('deleteVideoButton').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default button behavior

        // Display SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if the user confirms
                document.getElementById('deleteVideoForm').submit();
            }
        });
    });


</script>
<script>
     document.getElementById('deleteVideoButton2').addEventListener('click', function(e) {
        e.preventDefault(); // Prevent default button behavior

        // Display SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to undo this action!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form if the user confirms
                document.getElementById('deleteVideoForm2').submit();
            }
        });
    });
</script>

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'File Upload Error',
                text: 'The file exceeds the allowed size limit of 1GB.',
            });
        </script>
    @endif


    <script>
        document.getElementById("uploadForm").addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the form from submitting the usual way
            var formData = new FormData(this); // Create a FormData object from the form

            var file = document.getElementById('video1').files[0]; // Access the file

            // Check file size (100MB limit)
            if (file.size > 102400000) { // 100MB = 102400000 bytes
                alert('File size exceeds the 100MB limit!');
                return;
            }

            var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest
            xhr.open("POST", "/charter1", true);

            // Include CSRF token in the request
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            // Show the loading spinner and hide the progress bar
            document.getElementById("loadingSpinner").style.display = 'block';
            document.getElementById("progressBar").style.width = '0%'; // Reset progress bar

            // Update progress bar on file upload progress
            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentage = (e.loaded / e.total) * 100; // Calculate upload progress
                    document.getElementById("progressBar").style.width = percentage + "%"; // Update progress bar width
                }
            });

            // Handle the response after upload completes
            xhr.onload = function() {
                // Hide the loading spinner when done
                document.getElementById("loadingSpinner").style.display = 'none';

                if (xhr.status === 200) {
                    alert("File uploaded successfully!");
                    // Refresh the page
                    location.reload();
                } else {
                    alert("Error uploading file!");
                }
            };

            // Handle any errors during the request
            xhr.onerror = function() {
                alert('Error uploading file!');
                document.getElementById("loadingSpinner").style.display = 'none';
            };

            xhr.send(formData); // Send the form data (including the file)
        });

    </script>

    <script>
        document.getElementById("uploadForm2").addEventListener("submit", function(e) {
            e.preventDefault(); // Prevent the form from submitting the usual way
            var formData = new FormData(this); // Create a FormData object from the form

            var file = document.getElementById('video2').files[0]; // Access the file

            // Check file size (100MB limit)
            if (file.size > 102400000) { // 100MB = 102400000 bytes
                alert('File size exceeds the 100MB limit!');
                return;
            }

            var xhr = new XMLHttpRequest(); // Create a new XMLHttpRequest
            xhr.open("POST", "/charter2", true);

            // Include CSRF token in the request
            xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

            // Show the loading spinner and hide the progress bar
            document.getElementById("loadingSpinner").style.display = 'block';
            document.getElementById("progressBar").style.width = '0%'; // Reset progress bar

            // Update progress bar on file upload progress
            xhr.upload.addEventListener("progress", function(e) {
                if (e.lengthComputable) {
                    var percentage = (e.loaded / e.total) * 100; // Calculate upload progress
                    document.getElementById("progressBar").style.width = percentage + "%"; // Update progress bar width
                }
            });

            // Handle the response after upload completes
            xhr.onload = function() {
                // Hide the loading spinner when done
                document.getElementById("loadingSpinner").style.display = 'none';

                if (xhr.status === 200) {
                    alert("File uploaded successfully!");
                    // Refresh the page
                    location.reload();
                } else {
                    alert("Error uploading file!");
                }
            };

            // Handle any errors during the request
            xhr.onerror = function() {
                alert('Error uploading file!');
                document.getElementById("loadingSpinner").style.display = 'none';
            };

            xhr.send(formData); // Send the form data (including the file)
        });

    </script>

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
                shared_name: selectedSharedName = '',
                w_status: selectedStatus = '' // Assuming this contains the selected service IDs
            } = selectedWindow;

            setDropdownValue(document.getElementById('editPersonnel'), selectedPersonnelId);
            setDropdownValue(
                document.getElementById('editStatus'),
                selectedStatus,
                selectedStatus == '1' ? 'Active' : 'Inactive'
            );

            setDropdownValue(document.getElementById('editShared'), selectedSharedName);

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

    // Delete shared window
    function deleteSharedWindow(pId) {
        document.getElementById('deleteSharedForm').action = `/sharedWindow/${pId}`;
    }

    // Edit shared window
    function editSharedWindow(pId, wName, deptId) {
        const selectedWindow = @json($sharedWindows).find(sharedWindow => sharedWindow.id == pId);

        if (selectedWindow) {
            // Extract values from the selected window
            const {
                w_name: selectedWName = '',
                p_id: selectedPersonnelId = '',
            } = selectedWindow;

            document.getElementById('editSWName').value = selectedWName;
            document.getElementById('editSharedForm').action = `/sharedWindow/${pId}`;
        } else {
            console.error('Selected user not found.');
        }
    }
</script>
