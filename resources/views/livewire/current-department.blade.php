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

    <div class="active-window-card" style=" width:100%; height:400px;">
        <div class="charter-card">
            <h1>CHARTER</h1>
        </div>
        <div class="queue-card">
            <div class="serving-card">
                <div class="serving">
                    <div class="window-name">
                        WINDOW 1
                    </div>
                    <div class="window-client">
                        <div class="window-status">
                            Now Serving
                        </div>
                        <div class="window-queue">
                            <div class="client-number">
                                201-0579
                            </div>
                            <div class="client-stack">
                                <div>Next</div>
                                <div class="client-three">
                                    <p>201-0578</p>
                                    <p>201-0577</p>
                                    <p>201-0576</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div> {{-- end of serving-card --}}

            <div class="waiting-card">
                <div class="waiting-title">
                    Waiting List
                </div>
                <div class="waiting-stack-card">
                    <div>
                        <div class="waiting-window-name">
                            Window 1
                        </div>
                        <div class="waiting-window-client">
                            <ul>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <div class="waiting-window-name">
                            Window 1
                        </div>
                        <div class="waiting-window-client">
                            <ul>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                            </ul>
                        </div>
                    </div>

                    <div>
                        <div class="waiting-window-name">
                            Window 1
                        </div>
                        <div class="waiting-window-client">
                            <ul>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                            </ul>
                        </div>
                    </div>

                    {{-- shared waiting list --}}
                    <div>
                        <div class="waiting-window-name">
                            SHARED WINDOW
                        </div>
                        <div class="waiting-window-client">
                            <ul>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                                <li>201-0579</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="allwindow-card" style="background-color: rgb(243, 243, 248); margin-top: 40rem;">
        @if (isset($allWindowQueue) && $allWindowQueue->isNotEmpty())
            @foreach ($allWindowQueue as $window)
                <div class="d-flex align-items-center card queue-ongoing-card pb-2">
                    <p class="text-start w-400">
                        <span class="window-name" style="font-size: 32px;">
                            {{ $window->w_name ?? '---' }}
                        </span>
                    </p>


                    <div class="queue-window text-center" style="padding-bottom: 0; margin-bottom: 24px;">
                        @if ($window->c_status == 'On Break')
                            <h5 style="color: orange">
                                {{ $window->c_status ?? 'On Break' }}
                            </h5>
                            <h1 style="font-size: 48px;">
                                <span class="window-number">{{ $window->studentNo ?? '---' }}</span>
                            </h1>
                            {{-- <h1 class="client-name">---
                            </h1> --}}
                        @else
                            @if ($window->c_status === null)
                                <h5 style="color: rgb(236, 242, 49)">
                                    Waiting...
                                </h5>
                            @else
                                <h5>
                                    {{ $window->c_status }}
                                </h5>
                            @endif
                            <h1 style="font-size: 48px;">
                                <span class="window-number">{{ $window->studentNo ?? '---' }}</span>
                            </h1>
                            {{-- <h1 class="client-name">
                                @if ($window->gName === '---')
                                    ---
                                @else
                                    {{ $window->gName === 'Guest' ? $window->gName : $window->gName . ' ' . ($window->sName ?? '---') }}
                                @endif
                            </h1> --}}
                        @endif
                    </div>

                    <ol class="list-group allWindowList {{ $window->clients->count() > 3 ? 'two-columns' : '' }} mt-1">
                        @if ($window->clients->isNotEmpty())
                            @foreach ($window->clients as $client)
                                <li class="list-group-item {{ $loop->first ? 'active' : '' }}">

                                    <div class="client-details">
                                        @if ($loop->first)
                                            <div class="status wavy">Waiting...</div>
                                        @endif
                                        <div class="studentNo"> {{ $client->studentNo }}</div>
                                        {{-- <div class="studentName">
                                            {{ $client->gName }} {{ $client->sName }}
                                        </div> --}}
                                    </div>
                                </li>
                            @endforeach
                        @else
                            <li class="list-group-item text-danger text-center">
                                No clients in queue.
                            </li>
                        @endif
                    </ol>

                </div>
            @endforeach
        @else
            <div class="col-12 text-center">
                <p style="font-size: 18px; color: gray;">No window available</p>
            </div>
        @endif
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
