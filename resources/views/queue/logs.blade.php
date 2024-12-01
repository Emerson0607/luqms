<x-layout>

    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h3 class="fw-bold mb-3">Logs</h3>
                <ul class="breadcrumbs mb-3">
                    <li class="nav-item {{ request()->is('/logs') ? 'active' : '' }}">
                        <a href="logs">Personnel</a>
                    </li>
                    <li class="separator">
                        <i class="icon-arrow-right"></i>
                    </li>
                    <li class="nav-item {{ request()->is('#') ? 'active' : '' }}">
                        <a href="#">Client</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Cashier Transaction</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Department</th>
                                            <th>In</th>
                                            <th>Out</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($logs as $log)
                                            <tr>
                                                <td>{{ $log->p_fname }} {{ $log->p_lname }}</td> <!-- Full name -->
                                                <td>{{ $log->department }}</td> <!-- Department -->
                                                <td>{{ \Carbon\Carbon::parse($log->time_in)->format('h:i A') }}</td>
                                                <!-- Time In -->
                                                <td>{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('h:i A') : 'Still logged in' }}
                                                </td>

                                                <td>{{ $log->date }}</td> <!-- Date -->
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>
