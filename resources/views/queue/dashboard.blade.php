<x-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/adapter/7.0.0/adapter.min.js"></script>

    <div class="page-inner auto-refresh-dashboard" style="padding-left:50px;">
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
                    <div class="col-md-8">
                        @livewire('queueing-window')
                    </div>
                    <div class="col-md-4">
                        @livewire('queueing-stack')
                    </div>
                </div>
            </div>
        </div>

    </div>


</x-layout>
