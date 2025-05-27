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
                    <a href="{{ route('petty.create') }}" class="btn btn-primary">
                          <span class="btn-label">
                        <i class="bi bi-plus-lg"></i>
                        </span>
                        New Request
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Request for</th>
                                   <th>Request type</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Request for</th>
                                    <th>Request type</th>
                                    <th>Amount</th>
                                     <th>Status</th>
                                    <th>Status</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                 @foreach ($requests as $index => $item)
                                 <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->request_for }}</td>
                                    <td>{{ $item->request_type }}</td>
                                    <td>{{ $item->amount }} </td>
                                    <td> @if ($item->status == 'pending')
                                            <span class="badge bg-danger">{{ $item->status }}</span>
                                        @elseif($item->status == 'processing')
                                            <span class="badge bg-warning">{{ $item->status }}</span>
                                        @elseif($item->status == 'rejected')
                                            <span class="badge bg-secondary">{{ $item->status }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $item->status }}</span>
                                        @endif</td>
                                        <td>

                                       
                                        <a href="{{ route('petty.show', $item->id) }}"
                                            class="btn btn-sm btn-secondary text-white"><i class="bi bi-eye"></i></a>
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
