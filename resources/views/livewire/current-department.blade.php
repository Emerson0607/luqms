<div wire:poll.1s="updateDepartment" class="main-header">
    <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom justify-content-between"
        style="height: 70px">
        <img style="width:50px; height:50px; border-radius:50%" src="img/logo/LU.png">

        {{ $currentUserDepartment }}

        <img style="width:50px; height:50px; border-radius:50%" src="{{ asset($currentDepartmentImage) }}">
    </nav>

    <div class="container overflow-auto" style="height: 100%; margin-top:2rem;">
        <div id="window-container" class="row all-window-queue1">
            <div class="row">
                @if (isset($allWindowQueue) && $allWindowQueue->isNotEmpty())
                    @foreach ($allWindowQueue as $window)
                        <div class="col-md-4">
                            <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                                <p class="text-start w-400">
                                    <span class="window-name" style="font-size: 32px;">
                                        {{ $window->w_name ?? '---' }}
                                    </span>
                                </p>
                                <div class="queue-window text-center" style="padding-bottom: 0; margin-bottom: 24px;">
                                    <h5 style="font-size: 24px;">
                                        {{ $window->c_status ?? 'Waiting...' }}
                                    </h5>
                                    <h1 style="font-size: 48px;">
                                        <span class="window-number">{{ $window->studentNo ?? '---' }}</span>
                                    </h1>
                                    <h1 style="font-size: 24px;">
                                        <span class="window-number">
                                            {{ $window->gName === 'Guest' ? $window->gName : $window->gName . ' ' . ($window->sName ?? '---') }}
                                        </span>
                                    </h1>
                                </div>
                                {{-- <ol class="list-group" style="width: 100%;">
                                    @if ($window->clients->isNotEmpty())
                                        @foreach ($window->clients as $client)
                                            <li style="font-size: 14px;"
                                                class="list-group-item {{ $loop->first ? 'active' : '' }}">
                                                <span
                                                    style="font-size: 7px; display: block; text-align: left;">{{ $client->studentNo }}</span>
                                                {{ $client->gName }} {{ $client->sName }}
                                                <br>

                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item text-danger text-center">
                                            No clients in queue.
                                        </li>
                                    @endif
                                </ol> --}}

                                <ol
                                    class="list-group allWindowList {{ $window->clients->count() > 5 ? 'two-columns' : '' }} mt-1">
                                    @if ($window->clients->isNotEmpty())
                                        @foreach ($window->clients as $client)
                                            <li class="list-group-item {{ $loop->first ? 'active' : '' }}">
                                                <span>{{ $client->studentNo }}</span>
                                                {{ $client->gName }} {{ $client->sName }}
                                                <br>
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="list-group-item text-danger text-center">
                                            No clients in queue.
                                        </li>
                                    @endif
                                </ol>

                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p style="font-size: 18px; color: gray;">No window available</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- <div class="container overflow-auto" style="height: 100%; margin-top:2rem;">
        <div id="window-container" class="row all-window-queue1 ">
            <div class="row">
              
                @if (empty($allWindowQueue))
                    <div class="col-12 text-center">
                        <p style="font-size: 18px; color: gray;">No window available</p>
                    </div>
                @else
                    @forelse ($allWindowQueue as $window)
                        <div class="col-md-4 mb-3">
                            <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                                <p class="text-start w-100">
                                    <span class="window-name"
                                        style="font-size: 12px;">{{ $window['w_name'] ?? '---' }}</span>
                                </p>
                                <div
                                    class="d-flex flex-column justify-content-center align-items-center queue-window text-center">
                                    <h5 style="font-size: 24px;">{{ $window['c_status'] ?? 'Waiting...' }}</h5>
                                    <h1 style="font-size: 48px;">
                                        <span class="window-number">{{ $window['studentNo'] ?? '---' }}</span>
                                    </h1>
                                    <h1 style="font-size: 24px;">
                                        <span class="window-number">
                                            {{ $window['gName'] === 'Guest' ? $window['gName'] : $window['gName'] . ' ' . ($window['sName'] ?? '---') }}

                                        </span>
                                    </h1>
                                </div>

                              
                                <div class="mt-5 container my-4">
                          

                                    <ol id="user-list" class="list-group mt-3">
                                        @if ($clients->isNotEmpty())
                                            @foreach ($clients as $client)
                                                <li class="list-group-item">
                                                    <strong>Name:</strong> {{ $client->gName }} {{ $client->sName }}
                                                    <br>
                                                    <strong>Student No.:</strong> {{ $client->studentNo }}
                                                </li>
                                            @endforeach
                                        @else
                                            <li class="list-group-item text-danger text-center">
                                                No clients in queue.
                                            </li>
                                        @endif
                                    </ol>
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
    </div> --}}
