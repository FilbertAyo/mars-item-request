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

        @if ($request->is_transporter == true && $request->attachment == null)
            <div class="">
                <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#uploadAttachmentModal">
                    <i class="bi bi-upload me-1"></i> Upload Attachment
                </button>
            </div>
        @elseif($request->request_for == 'Office Supplies' && $request->attachment == null)
            <div class="">
                <button class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#uploadAttachmentModal">
                    <i class="bi bi-upload me-1"></i> Upload Attachment
                </button>
            </div>
        @endif
        <!-- Modal -->
        <div class="modal fade" id="uploadAttachmentModal" tabindex="-1" aria-labelledby="uploadAttachmentModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form action="{{ route('petty.updateAttachment', $request->id) }}" method="POST"
                    enctype="multipart/form-data" class="modal-content">
                    @csrf
                    @method('POST')
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadAttachmentModalLabel">Upload Attachment</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="attachment" class="form-label">Choose File <span
                                    class="text-danger">*</span></label>
                            <input type="file" name="attachment" id="attachment" class="form-control" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <x-primary-button label="Upload"/>
                    </div>
                </form>
            </div>
        </div>


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
                        <div class="form-group col-md-4 mb-3">
                            <label for="">Request for</label>
                            <input type="text" class="form-control" value="{{ $request->request_for }}" disabled>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="">Request type</label>
                            <input type="text" class="form-control" value="{{ $request->request_type }}" disabled>
                        </div>
                        <div class="form-group col-md-4 mb-3">
                            <label for="">Date issued:</label>
                            <input type="text" class="form-control" value="{{ $request->created_at }}" disabled>
                        </div>
                        <div class="form-group col-md-6">
                            <label>Description</label>
                            <h6 class="text-muted bg-light p-3">{!! nl2br(e($request->reason)) !!}</h6>
                        </div>

                        @if ($request->paid_date)
                            <div class="form-group col-md-4 mb-3">
                                <label for="">Date Paid:</label>
                                <input type="text" class="form-control" value="{{ $request->paid_date }}" disabled>
                            </div>
                        @endif

                        <div class="form-group col-md-2">
                            <label>Status</label>
                            <p>
                                @if ($request->status == 'pending')
                                    <span class="btn bg-danger text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'processing')
                                    <span class="btn bg-warning text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'rejected')
                                    <span class="btn bg-secondary text-white">{{ $request->status }}</span>
                                @elseif($request->status == 'resubmission')
                                    <a href="{{ route('petty.edit', Hashids::encode($request->id)) }}"
                                        class="btn btn-label-danger"><i
                                            class="bi bi-pencil-square me-2"></i>{{ $request->status }}</a>
                                @else
                                    <span class="btn bg-success text-white">{{ $request->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>


                @include('elements.details')

                <h4 class="text-secondary mb-3 text-primary text-center">Total Amount:<strong> TZS
                        {{ number_format($request->amount, 2) }}/=</strong></h4>

            </div>
        </div>
    </div>


    @include('elements.approvals')

    @include('elements.print')


</x-app-layout>
