<div class="mb-4">

    @if ($request->request_for == 'Sales Delivery')
        <h5 class="text-secondary mb-3"><strong>Attachments Details</strong></h5>

        <ul class="list-group mb-3">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Customer Name</th>
                        <th>Products</th>
                        <th>Attachment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($request->attachments as $attachment)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $attachment->name }}</td>
                            <td>{!! nl2br(e($attachment->product_name)) !!}
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal"
                                    data-bs-target="#attachmentModal{{ $loop->index }}" class="btn btn-secondary btn-sm">
                                    <i class="bi bi-eye-fill"></i>
                                </a>

                                <div class="modal fade" id="attachmentModal{{ $loop->index }}" tabindex="-1"
                                    aria-labelledby="attachmentModalLabel{{ $loop->index }}" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="attachmentModalLabel{{ $loop->index }}">
                                                    Attachment Preview</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body text-center">
                                                @php
                                                    $filePath = public_path($attachment->attachment);
                                                    $fallbackPath = 'storage' . asset($attachment->attachment);
                                                @endphp
                                                <img src="{{ file_exists($filePath) ? asset($attachment->attachment) : $fallbackPath }}"
                                                    alt="Attachment" class="img-fluid"
                                                    style="max-height: 80vh; object-fit: contain;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>

        </ul>
    @endif

    @if ($request->request_for == 'Office Supplies')

        <h5 class="text-secondary mb-3"><strong>List of Items</strong></h5>

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
        @endif
    @elseif($request->request_for == 'Sales Delivery' || $request->request_for == 'Transport')
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
                                <span class="stamp stamp-md {{ $loop->last ? 'bg-success' : 'bg-warning' }} me-3">
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

            <div class="form-group col-md-12 mb-3">
                <h5 class="text-secondary mb-3 text-primary"><strong>Transport Mode</strong></h5>
                <div class="col-sm-6 col-lg-3">
                    <div class="card shadow-sm p-3">
                        <div class="d-flex align-items-center">
                            <span class="stamp stamp-md me-3">
                                <i class="bi bi-bus-front"></i>
                            </span>
                            <div>
                                <small class="text-muted">{{ $request->transMode->name ?? 'N/A' }}</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    @endif

    @if ($request->is_transporter == true)
        <h5 class="text-secondary mb-3 text-primary"><strong>Attachment</strong></h5>
        @if (!empty($request->attachment))
            <a href="{{ asset($request->attachment) }}" download
                class="btn btn-primary text-white text-decoration-none">
                <i class="bi bi-download me-2"></i> Download
            </a>
        @else
            <span class="text-danger">No attachment available</span>
        @endif
    @elseif($request->request_for == 'Office Supplies')
        <h5 class="text-secondary mb-3 text-primary"><strong>Attachment</strong></h5>
        @if (!empty($request->attachment))
            <a href="{{ asset($request->attachment) }}" download
                class="btn btn-primary text-white text-decoration-none">
                <i class="bi bi-download me-2"></i> Download
            </a>
        @else
            <span class="text-danger">No attachment available</span>
        @endif
    @else
        @if (!empty($request->attachment))
            <h5 class="text-secondary mb-3 text-primary"><strong>Attachment</strong></h5>
            <a href="{{ asset($request->attachment) }}" download
                class="btn btn-primary text-white text-decoration-none">
                <i class="bi bi-download me-2"></i> Download
            </a>
        @endif

    @endif


</div>
