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

                        <x-primary-button label='Submit' />

                    </form>
                </div>

            </div>
        </div>
    </div>




</x-app-layout>
