<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">New Replenishment</h3>
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
                <a href="{{ route('replenishment.index') }}">
                    Replenishments
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">New Replenishment</a>
            </li>
        </ul>
    </div>


    <form action="{{ route('replenishment.store') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="card-title">Replenishment Info</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="from" value="{{ $from }}">
                    <input type="hidden" name="to" value="{{ $to }}">

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label>Date Range:</label>
                            <input type="text" name="from" value="{{ $from }}" class="form-control"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label>To:</label>
                            <input type="text" name="to" value="{{ $to }}" class="form-control"
                                readonly>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label>Total Amount</label>
                            <input type="number" name="total_amount" value="{{ $totalAmount }}" readonly
                                class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="10" required></textarea>
                        </div>
                    </div>

                    <div class="card-action">
                        @can('approve petycash payments')
                        <x-primary-button label="Create" />
                        @endcan
                        <a href="{{ route('replenishment.index') }}" class="btn btn-secondary">Discard</a>
                    </div>
                </div>
            </div>
        </div>
    </form>

</x-app-layout>
