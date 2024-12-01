<div>
    <!-- Dropdown to select a department -->
    <select wire:model="dept_id" wire:change="fetchPersonnels">
        <option value="">Select a department</option>
        @foreach ($userDepartments as $dept)
            <option value="{{ $dept->dept_id }}">{{ $dept->dept_id }}</option>
        @endforeach
    </select>

    <!-- List of personnel -->
    <ul>
        @if (!empty($personnels))
            @foreach ($personnels as $person)
                <li>{{ $person->firstname }}</li> <!-- Change to $person->firstname -->
            @endforeach
        @else
            <li>No personnel found for the selected department.</li>
        @endif
    </ul>

</div>
