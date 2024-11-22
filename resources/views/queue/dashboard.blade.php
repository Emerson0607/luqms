<x-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/adapter/7.0.0/adapter.min.js"></script>

    <div class="page-inner" style="padding-left:50px">
        <div class="d-flex justify-content-center pl-5">
            <div class="row">
                <div class="row mb-4">
                    <h2 class="op-7 mb-2">Laguna University Queuing Management System</h2>
                </div>

                @guest
                    <div>
                        <x-nav-link href="/login" :active="request()->is('/login')">Log In</x-nav-link>
                        <x-nav-link href="/register" :active="request()->is('/register')">Register</x-nav-link>
                    </div>
                @endguest

                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                            <div class="d-flex flex-column justify-content-center align-items-center queue-window">
                                <h5 id="client-status">Waiting...</h5>
                                <h1><span id="client-number">---</span></h1>

                                <!-- Display current department dynamically -->
                                <h6 id="current-department-name-dashboard">
                                    {{ $current_department_name }}
                                    <p><span id="client-name">---</span></p>
                                </h6>

                            </div>
                            <div>
                                <button class="btn btn-primary btn-sm" id="fetch-oldest-client">Next</button>
                                <button class="btn btn-primary btn-sm" id="notify-button">Notify</button>
                                <button class="btn btn-primary btn-sm" id="wait-button">Wait</button>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form action="{{ route('generate.client') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-primary">Generate Client</button>
                            </form>
                        </div>
                    </div>
                    <div class="cold-12 col-md-4">
                        <div class="card queue-stack-card">
                            <div class="card-header text-center">
                                <p id="current-department-name-card">
                                    {{ session('current_department_name', 'No department selected') }}</p>
                                <ul class="list-group list-group-flush" id="user-list">
                                    <li class="list-group-item"></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>


</x-layout>
