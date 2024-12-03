<div class="mt-5" wire:poll.1s="renderClient">
    <p class="current_department">
        {{ session('current_department_name', 'No department selected') }}</p>
    <ol id="user-list">
        @if (!empty($clients))
            @foreach ($clients as $client)
                <li>name: {{ $client->name }}, number: {{ $client->number }}</li>
            @endforeach
        @else
            <li>No personnel found for the selected department.</li>
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
