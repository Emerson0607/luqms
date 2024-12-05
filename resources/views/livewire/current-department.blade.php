<div wire:poll.1s="updateDepartment" class="main-header">
    <nav class="allWindow-nav" style="height: 70px">

        <div class="lu-logo"><img style="width:50px; height:50px; border-radius:50%" src="img/logo/LU.png"></div>
        <div class="dept-title"> {{ $currentUserDepartment }}</div>
        <div class="dept-logo"> <img style="width:50px; height:50px; border-radius:50%"
                src="{{ asset($currentDepartmentImage) }}"></div>
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
