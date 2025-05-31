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
                <a href="{{ route('item-request.index') }}">
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
                <h5 class="text-secondary mb-3 text-primary"><strong>General Information</strong></h5>
                <div class="mb-4">
                    <div class="row">
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Namer of Item</label>
                            <input type="text" class="form-control" value="{{ $item->name }}" disabled>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Quantity</label>
                            <input type="text" class="form-control" value="{{ $item->quantity }}" disabled>
                        </div>

                        <div class="form-group col-md-6 mb-3">
                            <label for="">Price at each item</label>
                            <input type="text" class="form-control" value=" {{ number_format($item->price, 2) }}/="
                                disabled>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Purchase Type</label>
                            <input type="text" class="form-control" value="{{ $item->p_type }}" disabled>
                        </div>
                        <div class="form-group col-md-6 mb-3">
                            <label for="">Justification</label>
                            <input type="text" class="form-control" value="{{ $item->justification->name ?? 'N/A' }}" disabled>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Reason</label>
                            <h6 class="text-muted bg-light p-3"> {{ $item->reason }}</h6>
                        </div>

                        <div class="form-group col-md-6">
                            <label>Status</label>
                            <p>
                                @if ($item->status == 'pending')
                                    <span class="btn bg-danger text-white">{{ $item->status }}</span>
                                @elseif($item->status == 'processing')
                                    <span class="btn bg-warning text-white">{{ $item->status }}</span>
                                @elseif($item->status == 'rejected')
                                    <span class="btn bg-secondary text-white">{{ $item->status }}</span>
                                @else
                                    <span class="btn bg-success text-white">{{ $item->status }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <h4 class="text-secondary mb-3 text-primary text-center">Total Amount:<strong> TZS
                        {{ number_format($item->amount, 2) }}/=</strong></h4>

            </div>

        </div>

    </div>


      <h3 class="fw-bold mb-3">Approval Timeline </h3>
    <div class="row">
        <div class="col-md-12">
            <ul class="timeline">
                <li>
                    <div class="timeline-badge">
                        <i class="bi bi-clipboard-data-fill"></i>
                    </div>
                    <div class="timeline-panel">
                        <div class="timeline-heading">
                            <h4 class="timeline-title">{{ $item->name }}</h4>
                            <p>
                                <small class="text-muted"><i class="bi bi-stopwatch"></i>
                                    {{ $item->created_at->diffForHumans() }} </small>
                            </p>
                        </div>
                        <div class="timeline-body">
                            <p class="text-muted">
                                {{ $item->reason }}
                            </p>
                        </div>
                    </div>
                </li>

                @foreach ($approval_logs as $approval)
                    <li class="{{ $loop->iteration %2 == 1 ? 'timeline-inverted': '' }}">

                        <div class="timeline-badge {{ $approval->action == 'rejected' ? 'danger' : 'success' }}">
                            <i
                                class="bi {{ $approval->action == 'rejected' ? 'bi-x-circle-fill' : 'bi-check-circle-fill' }} "></i>
                        </div>
                        <div class="timeline-panel">
                            <div class="timeline-heading">
                                <h4 class="timeline-title">
                                    {{ ucfirst($approval->action) }}
                                </h4>
                                <p>
                                    <small class="text-muted"><i class="bi bi-stopwatch"></i>
                                        {{ $approval->created_at->diffForHumans() }} by {{ $approval->user->name }}
                                    </small>
                                </p>
                            </div>

                            <div class="timeline-body">
                                <p class="text-muted">
                                    {{ $approval->comment }}
                                </p>
                            </div>
                        </div>


                    </li>
                @endforeach


            </ul>
        </div>
    </div>



</x-app-layout>
