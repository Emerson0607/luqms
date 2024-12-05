<!DOCTYPE html>
<html lang="en">

<head>
    <title>LU-QMS</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />

    <link rel="icon" href="bootstrap-template/assets/img/emerson.ico" type="image/x-icon" />
    <x-header-bootstrap-import />
    @livewireStyles
</head>

<body class="overflow-hidden " style="height: 100%;">

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
                                    $userDepartments = session('user_department', []);
                                    $departments = \App\Models\DmsDepartment::whereIn('id', $userDepartments)
                                        ->orderBy('name', 'asc')
                                        ->get();

                                    $currentDepartmentId = session(
                                        'current_department',
                                        $departments->isNotEmpty() ? $departments->first()->id : null,
                                    );

                                    $defaultDepartmentId = $departments->isNotEmpty()
                                        ? $departments->first()->id
                                        : null;

                                @endphp

                                @if (!empty($departments) && $departments->isNotEmpty())
                                    @if (count($departments) === 1)
                                        <!-- Single department handling -->
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <span class="form-control">{{ $departments->first()->name }}</span>
                                                <input type="hidden" id="single-department-id"
                                                    value="{{ $departments->first()->id }}">
                                            </div>
                                        </div>
                                    @else
                                        <!-- If multiple departments, display dropdown -->
                                        <div class="row dept-dropdown">
                                            <select name="department" id="department" class="form-select"
                                                onchange="updateDepartment(this)">
                                                @foreach ($departments as $department)
                                                    <option value="{{ $department->id }}"
                                                        {{ $department->id == session('current_department_id', $defaultDepartmentId) ? 'selected' : '' }}>
                                                        {{ $department->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    @endif
                                @endif
                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div>
                                        @livewire('current-department-image')
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
                                                    @livewire('current-department-image')
                                                </div>
                                                <div class="u-text">

                                                    @auth
                                                        <h4>{{ Auth::user()->p_fname }} {{ Auth::user()->p_lname }}
                                                        </h4>
                                                    @endauth
                                                    <!-- Display the current department dynamically -->
                                                    <p class="text-muted current_department">
                                                        {{ session('current_department_name') }}
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

            <div class="container overflow-auto auto-refresh" style="height: 100%;">
                {{ $slot }}
            </div>

            {{-- javascript import --}}
            <x-js-bootstrap-down />

            <script>
                function updateDepartment(selectElement) {
                    const departmentId = selectElement.value;
                    const departmentName = selectElement.options[selectElement.selectedIndex].text;

                    fetch('{{ route('update-department') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                department_id: departmentId,
                                department_name: departmentName
                            })
                        })
                        .then(response => {
                            if (response.ok) {
                                return response.json();
                            }
                            throw new Error('Failed to update department.');
                        })
                        .then(data => {
                            // Update the department name in the UI
                            document.querySelectorAll('.current_department').forEach(element => {
                                element.textContent = departmentName;
                            });

                            // Reload the container content after successful department update
                            // reloadContainer();
                            // windowRefresh();
                            location.reload();

                        })
                        .catch(error => {
                            console.error(error);
                            alert('An error occurred while updating the department.');
                        });
                }

                function reloadContainer() {
                    const containerDiv = document.querySelector('.auto-refresh');

                    // Reload the content of the container div by re-fetching the page's HTML content.
                    fetch(window.location.href) // Fetch the current page again
                        .then(response => response.text())
                        .then(html => {
                            // Parse the response HTML and replace the content of the target div
                            const newDoc = new DOMParser().parseFromString(html, 'text/html');
                            const newContainer = newDoc.querySelector('.auto-refresh');

                            // Replace the content of the div
                            containerDiv.innerHTML = newContainer.innerHTML;
                        })
                        .catch(error => {
                            console.error(error);
                            alert('An error occurred while reloading the content.');
                        });
                }
            </script>
            @livewireScripts
</body>

</html>
