<div style="margin-bottom: 20rem" class="d-flex align-items-center card queue-ongoing-card pb-2">
    <div wire:poll.1s="renderQueue" class="d-flex flex-column justify-content-center align-items-center queue-window ">

        @if ($currentUserWindow)
            <h5 id="client-status">{{ $currentUserWindow->c_status }}</h5>
            <h1><span id="client-number">{{ $currentUserWindow->studentNo }}</span></h1>
            <h6 class="current_department">{{ session('current_department_name') }}</h6>
            <p>
                <span id="client-name">
                    @if ($currentUserWindow->gName === 'Guest')
                        {{ $currentUserWindow->gName }}
                    @else
                        {{ $currentUserWindow->gName }} {{ $currentUserWindow->sName }}
                    @endif
                </span>
            </p>

            <div class="d-flex justify-content-between flex-wrap">
                <div class="flex-grow-1 mx-1">
                    <button id="fetch-oldest-client" class="btn btn-primary btn-sm w-100">
                        Next
                    </button>
                </div>

                <div class="flex-grow-1 mx-1">
                    <button class="btn btn-primary btn-sm w-100" id="notify-button">
                        Notify
                    </button>
                </div>
                <div class="flex-grow-1 mx-1">
                    <button wire:click="waitQueue" class="btn btn-primary btn-sm w-100" id="wait-button">
                        Wait
                    </button>
                </div>
                <div class="flex-grow-1 mx-1">
                    <button type="button" class="btn btn-primary btn-sm w-100" id="done-button"
                        onclick="confirmDoneQueue()">
                        Done
                    </button>
                </div>
                <div class="form-group form-group-default">
                    <label for="w_service">Services</label>
                    <select id="w_service" class="form-control" name="w_service" wire:model="selectedService" required>
                        <option value="" selected>Select a service</option>

                        @if ($services)
                            @foreach ($services as $service)
                                <option value="{{ $service['service_id'] }}">
                                    {{ $service['service_name'] }}
                                </option>
                            @endforeach
                        @else
                            <p>No service available for your department.</p>
                        @endif

                    </select>

                </div>
            </div>
        @else
            <h5 id="client-status">Create Window</h5>
            <h1 style="font-size: 64px; margin-top:2rem;"><span id="client-number">No Active Window</span></h1>
            <h6 class="current_department">assigned to current personnel </h6>
        @endif
    </div>


    <div>
        <!-- Display success message -->
        @if (session()->has('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <!-- Student Number Input -->
        <input type="text" wire:model="studentNo" wire:keydown.enter="pushClient" id="studentNo"
            placeholder="Enter Student Number" class="form-control">

    </div>


    <button wire:click="generateClient" type="submit" class="btn btn-primary">Generate Client</button>

</div>



<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
