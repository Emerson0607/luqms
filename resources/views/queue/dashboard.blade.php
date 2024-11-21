<x-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/adapter/7.0.0/adapter.min.js"></script>


    <div class="page-inner" style="padding-left:50px">
        <div class="d-flex justify-content-center pl-5">

            {{-- style="border: 1px solid black; padding-left:40px" --}}
            <div class="row">
                <div class="row mb-4">

                    <h2 class="op-7 mb-2">Laguna University Queuing Management System</h2>
                </div>

                @guest
                    <div> <x-nav-link href="/login" :active="request()->is('/login')">Log In</x-nav-link>
                        <x-nav-link href="/register" :active="request()->is('/register')">Register</x-nav-link>
                    </div>
                @endguest



                <div class="row">
                    <div class="col-12 col-md-8">
                        <div class="d-flex align-items-center card queue-ongoing-card pb-2">

                            <div class="d-flex flex-column justify-content-center align-items-center queue-window">
                                <h5 id="client-status">Waiting...</h5>

                                <h1><span id="client-number">---</span></h1>

                                <h6>
                                    @php
                                        $dmsUserDepts = \App\Models\DmsUserDepts::where(
                                            'p_id',
                                            Auth::user()->p_id,
                                        )->first();
                                        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
                                    @endphp
                                    {{ $department ? $department->acronym : 'No department assigned' }} Transaction</h6>
                                <p><span id="client-name">---</span></p>
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
                                @php
                                    $dmsUserDepts = \App\Models\DmsUserDepts::where(
                                        'p_id',
                                        Auth::user()->p_id,
                                    )->first();
                                    $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);

                                @endphp
                                {{ $department ? $department->acronym : 'No department assigned' }} Queue Stack </div>
                            <ul class="list-group list-group-flush" id="user-list">
                                <li class="list-group-item"></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
