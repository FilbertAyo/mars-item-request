<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Reports</h3>
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
                <a href="#">Reports</a>
            </li>

        </ul>
    </div>

   <div class="row">

    @can('view cashflow movements')
        <div class="col-sm-6 col-md-3">
            <a href="{{ route('reports.petties') }}" class="text-decoration-none">
                <div class="card card-stats card-light card-round">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-5">
                                <div class="icon-big text-center">
                                   <i class="bi bi-wallet-fill"></i>
                                </div>
                            </div>
                            <div class="col-7 col-stats">
                                <div class="numbers">
                                    <div class="h5">Petty Cash Report</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endcan

    <div class="col-sm-6 col-md-3">
        <a href="{{ route('reports.users') }}" class="text-decoration-none">
            <div class="card card-stats card-light card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                                <i class="bi bi-people"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <div class="h5">Users List</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-md-3">
        <a href="{{ route('reports.transaction') }}" class="text-decoration-none">
            <div class="card card-stats card-light card-round">
                <div class="card-body">
                    <div class="row">
                        <div class="col-5">
                            <div class="icon-big text-center">
                             <i class="bi bi-coin"></i>
                            </div>
                        </div>
                        <div class="col-7 col-stats">
                            <div class="numbers">
                                <div class="h5">Transactions</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </a>
    </div>
</div>


</x-app-layout>
