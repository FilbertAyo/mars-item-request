<x-app-layout>

                            <div class="mb-1" style="display: flex;justify-content: space-between;">

                                <h1 class="h3">Petty cash details

                                    @if ($request->status == 'pending')
                                        <span class="badge bg-info text-white text-sm ms-2">{{ $request->status }}</span>
                                    @elseif($request->status == 'processing')
                                        <span class="badge bg-warning text-white text-sm ms-2">{{ $request->status }}</span>
                                    @elseif($request->status == 'rejected')
                                        <span class="badge bg-danger text-white text-sm ms-2">{{ $request->status }}</span>
                                    @else
                                        <span class="badge bg-success text-white text-sm ms-2">{{ $request->status }}</span>
                                    @endif
                                </h1>

                                @if (auth()->user()->userType == 5)
                                    @if ($request->status == 'pending')
                                        <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle"
                                            role="button" data-bs-target="#staticBackdrop">
                                            Check
                                        </a>
                                    @endif
                                @elseif(auth()->user()->userType == 3 || auth()->user()->userType == 6)
                                @if ($request->status == 'processing')
                                    <a class="btn btn-dark" data-bs-toggle="modal" href="#exampleModalToggle"
                                        role="button" data-bs-target="#staticBackdrop">
                                        Check
                                    </a>
                                @endif
                            @endif

                            @if (auth()->user()->userType == 6 && $request->status == 'approved')
                                <a class="btn btn-primary" href="javascript:void(0);"
                                    onclick="confirmApproval('{{ route('c_approve.approve', ['id' => $request->id]) }}')">
                                    Pay
                                </a>
                            @endif

                                <script>
                                    function confirmApproval(url) {
                                        // Display confirmation dialog
                                        if (confirm('Are you sure you want to approve this request?')) {
                                            // Redirect to the URL to process the approval
                                            window.location.href = url;
                                        }
                                    }
                                </script>

                            </div>

                            <div class="row">


                                <div class="col-12 col-lg-12 col-xxl-12 d-flex">

                                    <div class="card shadow-sm border-0 col-12 mt-1">


                                        <div class="card-body">

                                            <div class="mb-4">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p class="fs-4"><strong>Petty cash for:</strong>
                                                            {{ $request->name }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Requested by:</strong>
                                                            {{ $request->request_by }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <div class="row">

                                                    <div class="col-md-6">
                                                        <p><strong>Reason:</strong> {{ $request->reason }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Petty cash type:</strong>
                                                            {{ $request->request_type }}</p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="mb-4">

                                                @if ($request->request_type == 'Petty Cash')
                                                    <h5 class="text-secondary mb-3 text-primary"><strong>List of
                                                            Items ,Service or Routes</strong></h5>

                                                    @if ($request->lists->count() > 0)
                                                        <ul class="list-group">
                                                            @foreach ($request->pettyLists as $item)
                                                                <li
                                                                    class="list-group-item d-flex justify-content-between align-items-center">
                                                                    {{ $item->item_name }}

                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <p class="text-muted">No items associated with this request.
                                                        </p>
                                                    @endif
                                                @else
                                                    <h5 class="text-secondary mb-3 text-primary">
                                                        <strong>Attachment</strong>
                                                        <!-- Download Icon -->
                                                        <a href="{{ asset($request->attachment) }}" download
                                                            class="badge bg-primary text-decoration-none ms-2">
                                                            download
                                                        </a>
                                                    </h5>

                                                    <!-- Thumbnail Image -->
                                                    <img src="{{ asset($request->attachment) }}" alt="Loading ..."
                                                        style="height: 200px; cursor: pointer;" data-bs-toggle="modal"
                                                        data-bs-target="#imageModal">

                                                    <!-- Full-Screen Image Modal -->
                                                    <div class="modal fade" id="imageModal" tabindex="-1"
                                                        aria-labelledby="imageModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="imageModalLabel">
                                                                        Attachment Image</h5>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body text-center">
                                                                    <img src="{{ asset($request->attachment) }}"
                                                                        alt="Loading ..." class="img-fluid">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                @endif
                                            </div>

                                            @if (!empty($request->comment))
                                                <div class="mb-4">
                                                    <h5 class="text-danger mb-3 text-primary"><strong>Reason for
                                                            rejection</strong></h5>
                                                    {{ $request->comment }}
                                                </div>
                                            @endif
                                            @if ($request->status == 'paid')
                                                <div class="text-end">
                                                    Paid: <div class="btn btn-success">TZS
                                                        {{ number_format($request->amount, 2) }}/=</div>
                                                </div>
                                            @else
                                                <div class="text-end">
                                                    Amount Needed: <div class="btn btn-secondary">TZS
                                                        {{ number_format($request->amount, 2) }}/=</div>
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                            </div>

                       
                    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" id="exampleModalToggle"
                        aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalToggleLabel">Purchase approval</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    After carefully reviewing the provided details of , please choose
                                    whether to
                                    approve or reject. Your decision will help us proceed with the appropriate action.
                                    <br>
                                    Do you approve or reject this item?
                                </div>
                                <div class="modal-footer">
                                    <button class="btn btn-danger" data-bs-target="#exampleModalToggle2"
                                        data-bs-toggle="modal" data-bs-dismiss="modal">Reject</button>

                                    @if ($request->status == 'processing')
                                        <a href=" {{ route('l_approve.approve', ['id' => $request->id]) }}"
                                            class="btn btn-success">Approve</a>
                                    @else
                                        <a href="{{ route('f_approve.approve', ['id' => $request->id]) }}"
                                            class="btn btn-success">Approve</a>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="exampleModalToggle2" aria-hidden="true"
                        aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="exampleModalToggleLabel2"> Reason for
                                        rejection</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form method="POST" action="{{ route('petty.reject', ['id' => $request->id]) }}">
                                        @csrf

                                        <div class="mb-3">
                                            <textarea class="form-control" id="description" name="comment" rows="4" required></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-success"
                                            data-bs-dismiss="modal">Submit</button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>

                </x-app-layout>