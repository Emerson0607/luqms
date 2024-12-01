{{-- <div class="d-flex align-items-center card queue-ongoing-card pb-2">
    <div class="d-flex flex-column justify-content-center align-items-center queue-window">
        <h5 id="client-status">Waiting...</h5>
        <h1><span id="client-number">---</span></h1>

        <!-- Display current department dynamically -->
        <h6 class="current_department">
            {{ session('current_department_name') }}
            <p><span id="client-name">---</span></p>
        </h6>
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
</div> --}}

<div class="d-flex align-items-center card queue-ongoing-card pb-2">
    <div wire:poll.1s="renderQueue" class="d-flex flex-column justify-content-center align-items-center queue-window">

        @if ($currentUserWindow)
            <h5 id="client-status">{{ $currentUserWindow->status }}</h5>
            <h1><span id="client-number">{{ $currentUserWindow->number }}</span></h1>
            <h6 class="current_department">{{ session('current_department_name') }}</h6>
            <p><span id="client-name">{{ $currentUserWindow->name }}</span></p>

            <div>
                <button wire:click="nextQueue" class="btn btn-primary btn-sm" id="fetch-oldest-client">Next</button>
                <button class="btn btn-primary btn-sm" id="notify-button">Notify</button>
                <button wire:click="waitQueue" class="btn btn-primary btn-sm" id="wait-button">Wait</button>
            </div>
        @else
            <h5 id="client-status">Create Window</h5>
            <h1><span id="client-number">No Window</span></h1>
            <h6 class="current_department">assigned to current personnel </h6>
        @endif
    </div>

    <button wire:click="generateClient" type="submit" class="btn btn-primary">Generate Client</button>

</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
{{-- for notify button --}}
<script>
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
</script>
