<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold mb-3">Deposits</h3>
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
                <a href="#">Deposits</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3">
                        @if ($remaining < 0)
                            <div class="fs-5 bg-danger badge">Remaining Amount:
                                <strong>{{ number_format($remaining, 0, '.', ',') }}/=</strong>
                            </div>
                        @else
                            <div class="fs-5 bg-success badge">Remaining Amount:
                                <strong>{{ number_format($remaining, 0, '.', ',') }}/=</strong>
                            </div>
                        @endif
                    </h4>

                    @can('approve petycash payments')
                    <a class="btn btn-dark" data-bs-toggle="modal" href="#exampleModalToggle" role="button"
                        data-bs-target="#staticBackdrop">
                        <span class="btn-label">
                            <i class="bi bi-plus-lg"></i>
                        </span>
                        Deposit
                    </a>
                    @endcan
                    
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Deposit Amount</th>
                                    <th>Remaing Balance</th>
                                    <th>Date deposited</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Deposit Amount</th>
                                    <th>Remaing Balance</th>
                                    <th>Date deposited</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($deposits as $index => $deposit)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ number_format($deposit->deposit, 0, '.', ',') }}/=
                                        </td>
                                        <td>
                                            <span class="{{ $deposit->remaining < 0 ? 'text-danger' : '' }}">
                                                {{ number_format($deposit->remaining, 0, '.', ',') }}/=
                                            </span>

                                        </td>
                                        <td>
                                            {{ $deposit->created_at }}</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>




    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" id="exampleModalToggle" aria-hidden="true"
        aria-labelledby="exampleModalToggleLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalToggleLabel">New Deposit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form method="POST" action="{{ route('deposit.store') }}" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                    <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Amount of Money</label>
                            <input type="number" name="deposit" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Date Deposited</label>
                            <input type="date" name="created_at" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="name">Description *optional</label>
                            <textarea name="description" class="form-control"></textarea>
                        </div>

                        <x-primary-button label="Add" />
                    </div>

                </form>


            </div>
        </div>
    </div>

</x-app-layout>
