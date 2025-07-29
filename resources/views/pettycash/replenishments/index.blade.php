<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Replenishments</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-fill"></i>
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Replenishments</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Replenishment List</h4>
                    @can('approve petycash payments')
                        <a href="{{ route('replenishment.pettycash') }}" class="btn btn-primary"><i
                                class="bi bi-plus-circle me-2"></i> New Replenishment</a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Total Amount</th>
                                    <th>Date Requested</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Request for</th>
                                    <th>Request type</th>
                                    <th>Total Amount</th>
                                    <th>Date Requested</th>

                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($replenishments as $replenishment)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $replenishment->from }}</td>
                                        <td>{{ $replenishment->to }}</td>
                                        <td>{{ number_format($replenishment->total_amount) }} </td>
                                        <td>{{ $replenishment->created_at }} </td>
                                        <td>
                                            @if ($replenishment->status == 'pending')
                                                <span class="badge bg-danger">Pending</span>
                                            @elseif($replenishment->status == 'approved')
                                                <span class="badge bg-success">Approved</span>
                                            @elseif($replenishment->status == 'rejected')
                                                <span class="badge bg-secondary">Rejected</span>
                                            @else
                                                <span class="badge bg-warning">Processing</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('replenishment.show', Hashids::encode($replenishment->id)) }}"
                                                class="btn btn-sm btn-secondary text-white"><i
                                                    class="bi bi-eye"></i></a>

                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>




</x-app-layout>
