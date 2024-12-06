<div class="container qms-stack" wire:poll.1s="renderClient">

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
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('log', (event) => {
            try {
                console[event[0].level](event[0].obj); // Use dynamic log level
            } catch {
                console.log(event[0]); // Fallback in case of error
            }
        });
    });
</script>
