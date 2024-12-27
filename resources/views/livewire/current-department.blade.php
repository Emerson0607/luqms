<div style="background-color: rgb(243, 243, 248);" wire:poll.1s="updateDepartment" class="main-header">
    <nav class="allWindow-nav" style="height: 70px; background-color:green">
        {{-- <div class="lu-logo"><img style="width:50px; height:50px; border-radius:50%" src="img/logo/LU.png"></div> --}}
        <div class="dept-logo"> <img style="width:50px; height:50px; border-radius:50%"
                src="{{ asset($currentDepartmentImage) }}"></div>

        <div class="dept-title"> {{ $currentUserDepartment }}</div>

        <button style="position: absolute; top: 1.2rem; right: 1.5rem; background-color:rgba(255, 255, 255, 0); border:0"
            id="fullscreenBtn">
            <svg id="fullscreenIcon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="20"
                height="20">
                <path
                    d="M32 32C14.3 32 0 46.3 0 64l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-64 64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0 0-64zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 0 64c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-96 0zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32l0-96z"
                    fill="white" />
            </svg>
        </button>
    </nav>

    <div class="active-window-card">
        <div class="charter-card">
            <video class="corporate-video" width="320" height="240" controls loop>
                <source src="{{ asset('charter/lu-cv.mp4') }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            @livewire('slide-show')

        </div>
        <div class="queue-card">
            <div class="serving-card">
                @if (isset($allWindowQueue) && $allWindowQueue->isNotEmpty())
                    @foreach ($allWindowQueue as $window)
                        <div class="serving">
                            <div class="window-name">
                                {{ $window->w_name ?? '---' }}
                            </div>
                            <div class="window-queue">
                                <div class="client-number">
                                    @if ($window->c_status == 'On Break')
                                        <h3 style="color: orange">
                                            {{ $window->c_status ?? 'On Break' }}
                                        </h3>
                                        <h1>
                                            {{ $window->studentNo ?? '---' }}
                                        </h1>
                                    @else
                                        @if ($window->c_status === null)
                                            <h3 style="color: rgb(236, 242, 49)">
                                                Waiting...
                                            </h3>
                                        @else
                                            <h3>
                                                {{ $window->c_status }}
                                            </h3>
                                        @endif
                                        <h1>
                                            {{ $window->studentNo ?? '---' }}
                                        </h1>
                                    @endif
                                </div>
                                <div class="client-stack">
                                    <div class="next">Next</div>
                                    <div class="client-three">
                                        @if ($window->clients->isNotEmpty())
                                            @foreach ($window->clients as $client)
                                                <p>{{ $client->studentNo }}</p>
                                            @endforeach
                                        @else
                                            <p> No clients in queue.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p style="font-size: 18px; color: gray;">No window available</p>
                    </div>
                @endif
            </div> {{-- end of serving-card --}}
            <div class="waiting-card">
                <div class="waiting-title">
                    Waiting List
                </div>
                <div class="waiting-stack-card">
                    @if (isset($allWaitingList) && $allWaitingList->isNotEmpty())
                        @php
                            $groupedWindows = $allWaitingList->groupBy(function ($item) {
                                return $item->shared_name === 'None' ? $item->w_name : $item->shared_name;
                            });
                        @endphp
                        @foreach ($groupedWindows as $groupName => $windows)
                            <div>
                                <div class="waiting-window-name">
                                    {{ $groupName }}
                                </div>
                                <div class="waiting-window-client">
                                    <ul>
                                        @php
                                            $uniqueClients = collect();
                                            foreach ($windows as $window) {
                                                if (isset($window->clients) && $window->clients->isNotEmpty()) {
                                                    foreach ($window->clients as $client) {
                                                        if (
                                                            !$uniqueClients->contains('studentNo', $client->studentNo)
                                                        ) {
                                                            $uniqueClients->push($client);
                                                        }
                                                    }
                                                }
                                            }
                                        @endphp
                                        @if ($uniqueClients->isNotEmpty())
                                            @foreach ($uniqueClients as $client)
                                                <li>{{ $client->studentNo }}</li>
                                            @endforeach
                                        @else
                                            <li>No clients in queue.</li>
                                        @endif
                                    </ul>
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
</div>

<script>
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const fullscreenIcon = document.getElementById('fullscreenIcon');

    const expandIcon = `
        <path d="M32 32C14.3 32 0 46.3 0 64l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-64 64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32L32 32zM64 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0 0-64zM320 32c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 0 64c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-96 0zM448 352c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32l0-96z" fill="white" />
    `;

    const compressIcon = `
        <path d="M160 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 64-64 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l96 0c17.7 0 32-14.3 32-32l0-96zM32 320c-17.7 0-32 14.3-32 32s14.3 32 32 32l64 0 0 64c0 17.7 14.3 32 32 32s32-14.3 32-32l0-96c0-17.7-14.3-32-32-32l-96 0zM352 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32l96 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-64 0 0-64zM320 320c-17.7 0-32 14.3-32 32l0 96c0 17.7 14.3 32 32 32s32-14.3 32-32l0-64 64 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0z" fill="white" />
    `;

    fullscreenBtn.addEventListener('click', () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen().catch(err => {
                alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
            });
            fullscreenIcon.innerHTML = compressIcon;
        } else {
            document.exitFullscreen();
            fullscreenIcon.innerHTML = expandIcon;
        }
    });
</script>
