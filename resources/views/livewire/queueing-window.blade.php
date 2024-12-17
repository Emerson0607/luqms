<div class="queue-ongoing-card">
    <div wire:poll.2s="renderQueue" class="d-flex flex-column justify-content-center align-items-center queue-window ">

        @if ($currentUserWindow)
            @if ($currentUserWindow->c_status == 'On Break')
                <h5 style="color: orange" id="client-status">{{ $currentUserWindow->c_status }}</h5>
                <h1><span id="client-number">---</span></h1>

                <p>
                    <span id="client-name">---</span>
                </p>
            @else
                <h5 id="client-status">{{ $currentUserWindow->c_status }}</h5>
                <h1><span id="client-number">{{ $currentUserWindow->studentNo }}</span></h1>

                {{-- <i class="fab fa-openid"></i> --}}
                {{-- <h6 class="current_department">{{ session('current_department_name') }}</h6> --}}
                <p>
                    <span id="client-name">
                        @if ($currentUserWindow->gName === 'Guest')
                            {{ $currentUserWindow->gName }}
                        @else
                            {{ $currentUserWindow->gName }} {{ $currentUserWindow->sName }}
                        @endif
                    </span>
                </p>
            @endif

            <div class="core-function-card">
                <button style="background-color: rgb(255, 34, 34) !important; border:0px;" id="fetch-oldest-client"
                    class="btn btn-primary btn-sm w-100">
                    Next
                </button>
                <button class="btn btn-primary btn-sm w-100" id="notify-button">
                    Notify
                </button>

                @if ($currentUserWindow->c_status == 'On Break')
                    <button style="background-color: orange !important; border:0px;" wire:click="continueQueue"
                        class="btn btn-primary btn-sm w-100" id="wait-button">
                        Continue
                    </button>
                @else
                    <button
                        style="background-color: rgb(114, 114, 114) !important; border:0px; color:rgb(243, 243, 243);"
                        wire:click="waitQueue" class="btn btn-primary btn-sm w-100" id="wait-button">
                        Break
                    </button>
                @endif

                <button style="background-color: green !important; border:0px;" type="button"
                    class="btn btn-primary btn-sm w-100" id="done-button" onclick="confirmDoneQueue()">
                    Done
                </button>
            </div>

            <div class="form-group form-group-default select-services-card">
                <label for="w_service">Services</label>
                <select id="w_service" class="form-control" name="w_service" wire:model="selectedService" required>
                    <option value="" selected>Select a service</option>
                    @if ($services)
                        @foreach ($services as $service)
                            {{-- <option value="{{ $service['service_id'] }}">
                                {{ $service['service_name'] }}
                            </option> --}}
                            <option value="{{ $service['service_id'] }}" title="{{ $service['service_name'] }}">
                                {{ $service['service_name'] }}
                            </option>
                        @endforeach
                    @else
                        <p>No service available for your department.</p>
                    @endif
                </select>

            </div>

            <!-- Display success message -->
            @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            @if ($currentUserWindow->shared_name == 'None')
                <div class="generate-client-card">
                    <div>
                        <!-- Student Number Input -->
                        <input type="text" wire:model="studentNo" wire:keydown.enter="pushClient" id="studentNo"
                            placeholder="Enter Student No." class="form-control">
                    </div>
                    <div class="or">or</div>
                    <div>
                        <button wire:click="generateClient" type="submit" class="btn btn-primary">Generate
                            Client</button>
                    </div>
                </div>
            @else
                <div class="generate-client-card">
                    <div>
                        <!-- Student Number Input -->
                        <input type="text" wire:model="studentNo" wire:keydown.enter="pushClient_s" id="studentNo_s"
                            placeholder="Enter Student No." class="form-control">
                    </div>
                    <div class="or">or</div>
                    <div>
                        <button wire:click="generateClient_s" type="submit" class="btn btn-primary">Generate
                            Client</button>
                    </div>
                </div>
            @endif
        @else
            <h5 id="client-status">Create Window</h5>
            <h1 style="font-size: 64px; margin-top:2rem;"><span id="client-number">No Active Window</span></h1>
            <h6 class="current_department">assigned to current personnel </h6>
        @endif

    </div>

    <div class="container qms-stack" wire:poll.2s="renderClient">
        <p class="current_department text-primary">
            {{ session('current_department_name', 'No department selected') }}
        </p>

        <ol id="user-list" class="list-group mt-3 allWindowList">
            @if ($clients->isNotEmpty())
                @foreach ($clients as $client)
                    <li class="list-group-item {{ $loop->first ? 'active' : '' }}">
                        <h5 style="font-size: 14px">{{ $client->studentNo }} - </h5>
                        {{ $client->gName }} {{ $client->sName }}
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- polling status --}}
<script>
    document.addEventListener('livewire:init', () => {
        let pollInterval;

        const startPolling = () => {
            if (!pollInterval) {
                pollInterval = setInterval(() => {
                    Livewire.emit('renderClient');
                }, 2000);
            }
        };

        const stopPolling = () => {
            clearInterval(pollInterval);
            pollInterval = null;
        };

        Livewire.on('updatePollStatus', (event) => {
            if (event.shouldPoll) {
                startPolling();
            } else {
                stopPolling();
            }
        });

        // Start polling initially
        startPolling();

        Livewire.on('log', (event) => {
            try {
                console[event.level](event.obj);
            } catch {
                console.log(event.obj);
            }
        });
    });
</script>

{{-- for notify button --}}
<script>
    // notify
    document.addEventListener('DOMContentLoaded', function() {
        const notifyButton = document.getElementById('notify-button');
        if (notifyButton) {
            notifyButton.addEventListener('click', function() {
                const clientNumber = document.getElementById('client-number').innerText;

                if (clientNumber === '---') {
                    Swal.fire({
                        icon: 'info',
                        title: 'No Clients to Notify',
                        text: 'There are currently no clients assigned to this window.',
                        confirmButtonText: 'OK'

                    });
                    return;
                }

                const message = `The next client number is ${clientNumber}`;
                const speech = new SpeechSynthesisUtterance(message);
                speech.voice = speechSynthesis.getVoices()[0];
                speech.rate = 1;
                speech.pitch = 1;
                speechSynthesis.speak(speech);
            });
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        // Attach event listener to the "Next" button
        document.getElementById('fetch-oldest-client').addEventListener('click', function() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to proceed with the next client?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, trigger Livewire method nextQueue
                    @this.call('nextQueue');
                }
            });
        });


    });
</script>

{{-- sweet alert --}}
<script>
    function confirmDoneQueue() {
        Swal.fire({
            title: 'Are you sure?',
            text: 'You are about to mark the current client as Done.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'No, cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                @this.call('doneQueue');
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Listen for the 'no-client-to-serve' event and show SweetAlert
        window.addEventListener('no-client-to-serve', event => {
            Swal.fire({
                icon: 'info',
                title: 'No Clients Available',
                text: 'There are currently no clients to serve.',
                confirmButtonText: 'OK'
            });
        });
        window.addEventListener('done-next', event => {
            Swal.fire({
                icon: 'info',
                title: 'You still have a client currently being served in the queue',
                text: 'Please complete their process before proceeding.',
                confirmButtonText: 'OK'
            });
        });
        window.addEventListener('no-service-selected', event => {
            Swal.fire({
                icon: 'warning',
                title: 'No Service Selected',
                text: 'Please select a service before proceeding.',
                confirmButtonText: 'OK'
            });
        });

        window.addEventListener('done-no-client-selected', event => {
            Swal.fire({
                icon: 'warning',
                title: 'No client Selected',
                text: 'Please select a client before proceeding.',
                confirmButtonText: 'OK'
            });
        });


    });
</script>

{{-- for wait/ continue  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const waitButton = document.getElementById('wait-button');
        const clientNumber = document.getElementById('client-number');
        const clientName = document.getElementById('client-name');

        // When the "Wait" button is clicked
        waitButton.addEventListener('click', function() {
            // Set the client number and name to "---"
            clientNumber.innerText = '---';
            clientName.innerText = '---';


        });
    });
</script>
