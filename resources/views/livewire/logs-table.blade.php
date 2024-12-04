<div class="container">
    <div class="page-inner">
        <div class="page-header">
            <h3 class="fw-bold mb-3">Logs</h3>
            <ul class="breadcrumbs mb-3">
                <li class="nav-item">


                    <button style="text-decoration: none; color:rgb(42, 47, 91);"
                        class="btn btn-link {{ $currentTab === 'personnel' ? 'active' : '' }}"
                        wire:click="switchTab('personnel')">
                        Personnel
                    </button>
                </li>
                <li class="separator">
                    <i class="icon-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <button style="text-decoration: none; color:rgb(42, 47, 91)"
                        class="btn btn-link {{ $currentTab === 'client' ? 'active' : '' }}"
                        wire:click="switchTab('client')">
                        Client
                    </button>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">{{ $currentDepartmentName }} Transaction</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="basic-datatables" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        @if ($currentTab === 'personnel')
                                            <th>Name</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Date</th>
                                        @else
                                            <th>Client Name</th>
                                            <th>Client Number</th>
                                            <th>Service</th>
                                            <th>Processed By</th>
                                            <th>Date</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($logs as $log)
                                        <tr>
                                            @if ($currentTab === 'personnel')
                                                <td>{{ $log->p_fname }} {{ $log->p_lname }}</td>

                                                <td>{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</td>
                                                <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'Still logged in' }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}</td>
                                            @else
                                                <td>{{ $log->gName }} {{ $log->sName }}</td>
                                                <td>{{ $log->studentNo }}</td>
                                                <td>{{ $log->c_service_name }}</td>


                                                <td>{{ $log->firstname }} {{ $log->lastname }}</td>
                                                <td>{{ \Carbon\Carbon::parse($log->date)->format('Y-m-d') }}</td>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No logs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
