<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold mb-3">My Requests</h3>
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
                <a href="#">Requests</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Requests List</h4>
                    @can('request pettycash')
                        <a href="{{ route('petty.create') }}" class="btn btn-primary">
                            <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New Request
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        No.</th>
                                    <th>Request for</th>
                                    <th>Request type</th>
                                    <th>Amount</th>
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
                                    <th>Amount</th>
                                    <th>Date Requested</th>
                                    <th>Status</th>
                                    <th>Action</th>

                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($requests as $index => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}
                                             @if($item->is_transporter == true &&  $item->attachment == null)
                                            <img src="{{ asset('image/beep.gif') }}" alt="Route Icon"
                                            style="width: 44px; height: 44px;" class="me-2">
                                            @endif
                                        </td>
                                        <td>{{ $item->request_for }}</td>
                                        <td>{{ $item->request_type }}</td>
                                        <td>{{ number_format($item->amount) }} </td>
                                        <td>{{ $item->created_at }} </td>
                                        <td>
                                            @if ($item->status == 'pending')
                                                <span class="badge bg-danger">{{ $item->status }}</span>
                                            @elseif($item->status == 'processing')
                                                <span class="badge bg-warning">{{ $item->status }}</span>
                                            @elseif($item->status == 'rejected')
                                                <span class="badge bg-secondary">{{ $item->status }}</span>
                                            @elseif($item->status == 'resubmission')
                                                <span class="badge btn-label-danger">{{ $item->status }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $item->status }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('petty.show', Hashids::encode($item->id)) }}"
                                                class="btn btn-sm btn-secondary text-white"><i
                                                    class="bi bi-eye"></i></a>
                                            @if ($item->status == 'pending' || $item->status == 'resubmission')
                                                <a href="{{ route('petty.edit', Hashids::encode($item->id)) }}"
                                                    class="btn btn-sm btn-secondary">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            @else
                                                <button type="button" class="btn btn-sm btn-secondary"
                                                    onclick="showEditBlockedNotice()">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                            @endif

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



    <script>
        function showEditBlockedNotice() {
            $.notify({
                icon: 'bi-exclamation-circle-fill',
                title: 'Error!',
                message: "You can't edit this because it's already in process.",
            }, {
                type: 'danger',
                placement: {
                    from: "top",
                    align: "right"
                },
                time: 2000,
            });
        }
    </script>



</x-app-layout>
