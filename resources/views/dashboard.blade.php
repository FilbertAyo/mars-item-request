<x-app-layout>


    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
        <div>
            <h3 class="fw-bold mb-3">Dashboard</h3>
            <h6 class="op-7 mb-2">Welcome back, {{ Auth::user()->name }}</h6>
        </div>
        <div class="ms-md-auto py-2 py-md-0">
            <a href="{{ route('chatify') }}" class="btn btn-label-info btn-round me-2">Messages</a>
        </div>
    </div>
    @can('view cashflow movements')
    <div class="row">
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Users</p>
                                <h4 class="card-title">{{ $userNo }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="bi bi-card-list"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Petty Cash</p>
                                <h4 class="card-title">{{ $pettyNo }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-danger bubble-shadow-small">
                                <i class="bi bi-wallet2"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Paid Pettycash</p>
                                <h4 class="card-title">{{ number_format($paidAmount) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="card card-stats card-round">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-icon">
                            <div class="icon-big text-center icon-primary bubble-shadow-small">
                                <i class="bi bi-building-fill"></i>
                            </div>
                        </div>
                        <div class="col col-stats ms-3 ms-sm-0">
                            <div class="numbers">
                                <p class="card-category">Branches/Departments</p>
                                <h4 class="card-title">{{ $branchNo }} [{{ $departmentNo }}]</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endcan

    @can('request pettycash')
    <div class="row">
        <div class="col-md-4">
            <div class="card card-primary card-round">
                <div class="card-header">
                    <div class="card-head-row">
                        <div class="card-title">My Petty Cash Expense</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <a href="{{ route('petty.index') }}" class="btn btn-sm btn-label-light">
                                    Requests
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="card-category">Total Amount(TZS)</div>
                </div>
                <div class="card-body pb-0">
                    <div class="mb-4">
                        <h1>{{ number_format($myExpense )}}</h1>
                    </div>

                </div>
            </div>

        </div>
    </div>
    @endcan




</x-app-layout>
