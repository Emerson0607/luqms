<x-layout>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/adapter/7.0.0/adapter.min.js"></script>
    <div class="page-inner auto-refresh-dashboard" style="padding-left:50px;">
        <div class="d-flex justify-content-center pl-5">
            <div class="row">
                @guest
                    <div>
                        <x-nav-link href="/login" :active="request()->is('/login')">Log In</x-nav-link>
                        <x-nav-link href="/register" :active="request()->is('/register')">Register</x-nav-link>
                    </div>
                @endguest

                @livewire('queueing-window')

            </div>
        </div>
    </div>
</x-layout>
