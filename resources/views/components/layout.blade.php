<!DOCTYPE html>
<html lang="en">

<head>
    <title>LU-QMS</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <link rel="icon" href="bootstrap-template/assets/img/emerson.ico" type="image/x-icon" />
    <x-header-bootstrap-import />
</head>

<body class="overflow-hidden" style="height: 100%;">

    <div class="wrapper">
        <x-sidebar />

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            LU-QMS
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li>

                                {{-- for user department --}}
                                @php
                                    // Retrieve the department IDs from the session (assumed to be an array of department IDs)
                                    $userDepartments = session('user_department', []);

                                    // Fetch the department details from the database based on user departments
                                    $departments = \App\Models\DmsDepartment::whereIn('id', $userDepartments)
                                        ->orderBy('name', 'asc')
                                        ->get();

                                    // Get the current department from the session or set to the first department if empty
                                    $currentDepartmentId = session(
                                        'current_department',
                                        $departments->isNotEmpty() ? $departments->first()->id : null,
                                    );

                                    // Get the first department ID for default selection, if available
                                    $defaultDepartmentId = $departments->isNotEmpty()
                                        ? $departments->first()->id
                                        : null;

                                @endphp

                                @if (!empty($departments) && $departments->isNotEmpty())
                                    @if (count($departments) === 1)
                                        <!-- Single department handling -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">

                                                <span class="form-control"
                                                    id="current-department-name">{{ $departments->first()->name }}</span>
                                                <input type="hidden" id="single-department-id"
                                                    value="{{ $departments->first()->id }}">
                                            </div>
                                        </div>
                                    @else
                                        <!-- If multiple departments, display dropdown -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <select name="department" id="department" class="form-select"
                                                    onchange="updateDepartment(this)">
                                                    @foreach ($departments as $department)
                                                        <option value="{{ $department->id }}"
                                                            {{ $department->id == $currentDepartmentId ? 'selected' : '' }}>
                                                            {{ $department->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                            </div>
                                        </div>
                                    @endif
                                @endif








                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">



                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="bootstrap-template/assets/img/emerson.jpg" alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">

                                        @auth
                                            <span class="profile-username">
                                                <span class="op-7">Hi,</span>
                                                <span class="fw-bold">{{ Auth::user()->p_fname }}</span>
                                            </span>
                                        @endauth
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    <img src="bootstrap-template/assets/img/emerson.jpg"
                                                        alt="image profile" class="avatar-img rounded" />
                                                </div>
                                                <div class="u-text">

                                                    @auth
                                                        <h4>{{ Auth::user()->p_fname }} {{ Auth::user()->p_lname }}
                                                        </h4>
                                                    @endauth
                                                    <!-- Display the current department dynamically -->
                                                    <p class="text-muted" id="current-department">
                                                        @if ($currentDepartmentId)
                                                            Current Department:
                                                            {{ $departments->where('id', $currentDepartmentId)->first()->name }}
                                                        @else
                                                            No department selected.
                                                        @endif
                                                    </p>
                                                    {{-- <a href="profile.html" class="btn btn-xs btn-secondary btn-sm">View
                                                        Profile</a> --}}
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>

                                            @auth
                                                <form method="POST" action="/logout">
                                                    @csrf

                                                    <x-form-button class="dropdown-item ">Log Out</x-form-button>
                                                </form>
                                            @endauth


                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>


            </div>

            <div class="container overflow-auto" style="height: 100%;">
                {{ $slot }}

            </div>

            {{-- javascript import --}}
            <x-js-bootstrap-down />
            <x-queue />

            <script>
                $(document).ready(function() {
                    // Trigger update for users with a single department
                    var singleDepartmentId = $('#single-department-id').val();
                    if (singleDepartmentId) {
                        updateDepartment(singleDepartmentId);
                    }

                    // Update department when the dropdown changes
                    $('#department').change(function() {
                        var selectedDepartmentId = $(this).val();
                        updateDepartment(selectedDepartmentId);
                    });

                    // Update department via AJAX
                    function updateDepartment(departmentId) {
                        $.ajax({
                            url: '{{ route('update.department.ajax') }}',
                            method: 'POST',
                            data: {
                                department: departmentId,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(data) {
                                if (data.success) {
                                    // Update dynamic elements
                                    $('#current-department-name').text(data
                                        .department_name);
                                    $('#current-department').text('Current Department: ' + data
                                        .department_name);
                                    $('#current-department-name-card').text(data
                                        .department_name);
                                    $('#current-department-name-dashboard').text(data
                                        .department_name);
                                }
                            },
                            error: function(xhr) {
                                console.error('Failed to update department:', xhr.responseText);
                            }
                        });
                    }
                });
            </script>

</body>

</html>
