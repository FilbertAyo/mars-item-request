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
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                    Check
                </button>
            @endcan
        @endif

        @if ($request->status == 'processing')
            @can('last pettycash approval')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                    Check
                </button>
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
                    <h5 class="text-secondary mb-3 text-muted">Code: <strong>
                            @can('approve petycash payments')
                                {{ $request->code }}
                            @endcan
                        </strong></h5>

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


                @include('elements.details')

                <h4 class="text-secondary mb-3 text-primary text-center">Total Amount:<strong> TZS
                        {{ number_format($request->amount, 2) }}/=</strong></h4>

            </div>

        </div>
    </div>


    @include('elements.approvals')
    @include('elements.print')



    <div class="modal fade" id="approvalModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Request Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Step 1: Ask -->
                    <div id="step-approval">
                        <p>After reviewing {{ $request->user->name }}'s request, please choose to approve or reject.
                        </p>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button class="btn btn-danger" onclick="goToRejectStep()">Reject</button>
                            <form
                                action="{{ $request->status == 'processing'
                                    ? route('l_approve.approve', ['id' => $request->id])
                                    : route('f_approve.approve', ['id' => $request->id]) }}"
                                method="POST">
                                @csrf
                                <x-primary-button label="Approve" />
                            </form>
                        </div>
                    </div>

                    <!-- Step 2: Reject Details -->
                    <div id="step-reject" class="d-none">
                        <form method="POST" action="{{ route('petty.reject', ['id' => $request->id]) }}">
                            @csrf
                            <div class="form-group">
                                <label>Select Action</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action" value="rejected"
                                        id="rejectRadio" required>
                                    <label class="form-check-label" for="rejectRadio">Reject</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="action"
                                        value="resubmission" id="resubmitRadio" required>
                                    <label class="form-check-label" for="resubmitRadio">Resubmit</label>
                                </div>
                            </div>

                            <div class="form-group mt-3">
                                <label for="comment">Reason:</label>
                                <textarea class="form-control" name="comment" rows="4" required></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary"
                                    onclick="backToApprovalStep()">Back</button>
                                <x-primary-button label='Submit' />
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        function goToRejectStep() {
            document.getElementById('step-approval').classList.add('d-none');
            document.getElementById('step-reject').classList.remove('d-none');
        }

        function backToApprovalStep() {
            document.getElementById('step-reject').classList.add('d-none');
            document.getElementById('step-approval').classList.remove('d-none');
        }
    </script>




</x-app-layout>
