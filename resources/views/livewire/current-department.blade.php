<div wire:poll.1s="updateDepartment" class="main-header">
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom justify-content-between"
        style="height: 70px">
        <img style="width:50px; height:50px; border-radius:50%" src="img/logo/LU.png">

        {{ $currentUserDepartment }}

        <img style="width:50px; height:50px; border-radius:50%" src="{{ asset($currentDepartmentImage) }}">
    </nav>

    <div class="container overflow-auto" style="height: 100%; margin-top:2rem;">
        <div id="window-container" class="row all-window-queue1 ">
            <div class="row" wire:poll.500ms="fetchAllWindows">
                @if (empty($allWindowQueue))
                    <div class="col-12 text-center">
                        <p style="font-size: 18px; color: gray;">No window available</p>
                    </div>
                @else
                    {{-- @foreach ($allWindowQueue as $window)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                                <p class="text-start w-100">
                                    <span class="window-name"
                                        style="font-size: 12px;">{{ $window['window_name'] ?? '---' }}</span>
                                </p>
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center queue-window text-center">
                                    <h5 style="font-size: 24px;">{{ $window['status'] ?? 'Waiting...' }}</h5>
                                    <h1 style="font-size: 48px;">
                                        <span class="window-number">{{ $window['number'] ?? '---' }}</span>
                                    </h1>
                                    <h1 style="font-size: 24px;">
                                        <span class="window-number">{{ $window['name'] ?? '---' }}</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    @endforeach --}}
                    @forelse ($allWindowQueue as $window)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                                <p class="text-start w-100">
                                    <span class="window-name"
                                        style="font-size: 12px;">{{ $window['window_name'] ?? '---' }}</span>
                                </p>
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center queue-window text-center">
                                    <h5 style="font-size: 24px;">{{ $window['status'] ?? 'Waiting...' }}</h5>
                                    <h1 style="font-size: 48px;">
                                        <span class="window-number">{{ $window['number'] ?? '---' }}</span>
                                    </h1>
                                    <h1 style="font-size: 24px;">
                                        <span class="window-number">{{ $window['name'] ?? '---' }}</span>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-md-12 text-center">
                            <p class="text-muted">No data available</p>
                        </div>
                    @endforelse

                @endif
            </div>

        </div>
    </div>
</div>
