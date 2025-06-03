<x-app-layout>

    <div class=" page-header flex-row justify-content-between">
        <div class="page-header">
            <h3 class="fw-bold mb-3">{{ $request->user->name }}</h3>
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
                    <a href="{{ route('petty.list') }}">
                        Requests
                    </a>
                </li>
                <li class="separator">
                    <i class="bi bi-arrow-right"></i>
                </li>
                <li class="nav-item">
                    <a href="#">Details</a>
                </li>
            </ul>

        </div>

    </div>


    <div class="page-header flex-row justify-content-between">
        <h3 class="fw-bold mb-3">General Information</h3>

        @if ($request->status == 'pending')
            @can('first pettycash approval')
                <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button"
                    data-bs-target="#staticBackdrop">
                    Check
                </a>
            @endcan
        @endif

        @if ($request->status == 'processing')
            @can('last pettycash approval')
                <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button"
                    data-bs-target="#staticBackdrop">
                    Check
                </a>
            @endcan
        @endif

        @if ($request->status == 'approved')
            @can('approve petycash payments')
                <a class="btn btn-primary" href="javascript:void(0);"
                    onclick="confirmApproval('{{ route('c_approve.approve', ['id' => $request->id]) }}')">
                    Pay Now
                </a>
                <script>
                    function confirmApproval(url) {
                        if (confirm('Are you sure you want to pay this request?')) {
                            window.location.href = url;
                        }
                    }
                </script>
            @endcan
        @endif

    </div>



    <div class="row">

        @if ($approval == 'approved')
            <span href="#" class="p-3 btn-label-success w-100 mb-3" style="pointer-events: none;"> <i
                    class="bi bi-check-circle-fill m-3"></i>
                You Approved this Request</span>
        @elseif($approval == 'rejected')
            <span href="#" class="p-3 btn-label-danger w-100 mb-3" style="pointer-events: none;"> <i
                    class="bi bi-x-circle-fill m-3"></i>
                You Rejected this Request</span>
        @elseif($approval == 'paid')
            <span href="#" class="p-3 btn-label-success w-100 mb-3" style="pointer-events: none;"> <i
                    class="bi bi-wallet-fill m-3"></i>
                You Paid this Request</span>
        @endif

        <div class="card shadow-sm col-12 border-0">
            <div class="card-body">
                <div class='page-header flex-row justify-content-between'>
                    <h5 class="text-secondary mb-3 text-muted">Code: <strong>{{ $request->code }}</strong></h5>

                    <button type="button" class="btn btn-label-secondary" data-bs-toggle="modal"
                        data-bs-target="#pettyCashModal">
                        <i class="bi bi-printer-fill"></i>
                    </button>
                </div>
                <div class="mb-4">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Request for</label>
                            <input type="text" class="form-control" value="{{ $request->request_for }}" disabled>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Request type</label>
                            <input type="text" class="form-control" value="{{ $request->request_type }}" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description</label>
                            <h6 class="text-muted bg-light p-3">{{ $request->reason }}</h6>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Status</label>
                            <p>
                                @if ($request->status == 'pending')
                                    <span class="btn bg-danger text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'processing')
                                    <span class="btn bg-warning text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'rejected')
                                    <span class="btn bg-secondary text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'resubmission')
                                    <span class="btn btn-label-danger text-white">{{ $request->status }}</span>
                                @else
                                    <span class="btn bg-success text-white">{{ $request->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>


                <div class="mb-4">

                    @if ($request->request_for == 'Office Supplies')
                        <hr>
                        <h5 class="text-secondary mb-3 text-primary"><strong>List of Items</strong></h5>

                        @if ($request->lists->count() > 0)
                            <ul class="list-group">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Quantity</th>
                                            <th scope="col">Price (TZS)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($request->lists as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->price) }}/=</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </ul>
                        @else
                            <p class="text-muted">No items associated with this request.
                            </p>
                        @endif
                    @elseif($request->request_for == 'Sales Delivery' || $request->request_for == 'Transport')
                        <hr>
                        <h5 class="text-secondary mb-3 text-primary"><strong>Transport Route</strong></h5>

                        <div class="row">

                            @foreach ($request->trips as $trip)
                                <div class="col-sm-6 col-lg-3">
                                    <div class="card shadow-sm p-3">
                                        <div class="d-flex align-items-center">
                                            <span class="stamp stamp-md bg-danger me-3">
                                                <i class="bi bi-geo-fill"></i>
                                            </span>
                                            <div>
                                                <small class="text-muted">Collection Point</small>
                                                <h5 class="mb-1">
                                                    <b>{{ $trip->startPoint->name }}</b>
                                                </h5>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @foreach ($trip->stops as $stop)
                                    <div class="col-sm-6 col-lg-3">
                                        <div class="card shadow-sm p-3">
                                            <div class="d-flex align-items-center">
                                                <span
                                                    class="stamp stamp-md {{ $loop->last ? 'bg-success' : 'bg-warning' }} me-3">
                                                    <i
                                                        class="bi {{ $loop->last ? 'bi-pin-map-fill' : 'bi-arrow-right-square-fill' }}"></i>
                                                </span>
                                                <div>
                                                    <small class="text-muted">{{ $loop->last ? 'END' : 'TO' }}</small>
                                                    <h5 class="mb-1">
                                                        <b> {{ $stop->destination }}</b>
                                                    </h5>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach

                        </div>

                    @endif

                    @if ($request->request_type == 'Reimbursement')
                        <hr>
                        <h5 class="text-secondary mb-3 text-primary"><strong>Attachment</strong></h5>

                        <h5 class="text-secondary mb-3 text-primary">
                            <a href="{{ asset($request->attachment) }}" download
                                class="badge bg-primary text-decoration-none ms-2">
                                download
                            </a>
                        </h5>

                        <!-- Thumbnail Image -->
                        <img src="{{ asset($request->attachment) }}" alt="Loading ..."
                            style="max-height: 200px; max-width: 100%; cursor: pointer;" data-bs-toggle="modal"
                            data-bs-target="#imageModal">

                        <!-- Full-Screen Image Modal -->
                        <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="imageModalLabel">Supporting Evidence</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset($request->attachment) }}" alt="Loading ..."
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <h4 class="text-secondary mb-3 text-primary text-center">Total Amount:<strong> TZS
                        {{ number_format($request->amount, 2) }}/=</strong></h4>


                @if (!empty($request->comment))
                    <div class="mb-4">
                        <h5 class="text-danger mb-3 text-primary"><strong>Reason for
                                rejection</strong></h5>
                        {{ $request->comment }}
                    </div>
                @endif
            </div>

        </div>
    </div>


  @include('elements.approvals')



    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-backdrop="static"
        id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">Request approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    After carefully reviewing the provided details of {{ $request->user->name }} , please choose
                    whether to
                    approve or reject. Your decision will help us proceed with the appropriate action.
                    <br>
                    Do you approve or reject this request?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Reject</button>

                    <form
                        action="{{ $request->status == 'processing'
                            ? route('l_approve.approve', ['id' => $request->id])
                            : route('f_approve.approve', ['id' => $request->id]) }}"
                        method="POST" style="display: inline;">

                        @csrf
                        <x-primary-button label="Approve" />
                    </form>

                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModalToggle2" data-bs-backdrop="static" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"> Reason </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('petty.reject', ['id' => $request->id]) }}">
                        @csrf
                        <div class="form-group">
                            <label for="request_type">Select Action</label>
                            <select name="action" class="form-control" required>
                                <option value="rejected">
                                    Reject</option>
                                <option value="resubmission">Resubmit
                                </option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="request_type">Reason:</label>
                            <textarea class="form-control" id="description" name="comment" rows="4" required></textarea>
                        </div>

                        <x-primary-button label='Submit'/>

                    </form>
                </div>

            </div>
        </div>
    </div>


     <div class="modal fade" id="pettyCashModal" tabindex="-1" aria-labelledby="pcvModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pcvModalLabel"><button class="btn btn-secondary" onclick="printPCV()"><i
                                class="bi bi-printer-fill"></i></button></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="printable-pcv">
                        @include('elements.print')
                    </div>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
