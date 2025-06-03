<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold mb-3">Request Details</h3>
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
                <a href="{{ route('petty.index') }}">
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


    <div class="row">

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
                            <h6 class="text-muted bg-light p-3">{!! nl2br(e($request->reason)) !!}</h6>
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
                                    <a href="{{ route('petty.edit', $request->id) }}" class="btn btn-label-danger"><i
                                            class="bi bi-pencil-square me-2"></i>{{ $request->status }}</a>
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

            </div>

        </div>

    </div>


     @include('elements.approvals')


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
