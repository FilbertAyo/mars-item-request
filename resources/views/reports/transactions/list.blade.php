<x-app-layout>
    <h2>Pettycash Transactions</h2>

    <form action="{{ route('reports.transaction') }}" method="GET" class="row g-3 mb-3">
        <div class="col-md-5">
            <label>From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-5">
            <label>To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="col-md-2 align-self-end">
            <button class="btn btn-primary">Filter</button>
        </div>
    </form>

    <div class="mb-3">
        <a href="{{ route('reports.transaction.download', ['type' => 'pdf'] + request()->all()) }}" class="btn btn-danger"><i
                class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-round">
                <div class="card-header">
                    <div class="card-head-row card-tools-still-right">
                        <div class="card-title">Transaction History</div>
                        <div class="card-tools">
                            <div class="dropdown">
                                <button class="btn btn-icon btn-clean me-0" type="button" id="dropdownMenuButton"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="#">All Paid Transactions</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <!-- Projects table -->
                        <table class="table align-items-center mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">PVC Number</th>
                                    <th scope="col">Name</th>
                                    <th scope="col" class="text-end">Date & Time</th>
                                    <th scope="col" class="text-end">Amount</th>
                                    <th scope="col" class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <th scope="row">
                                            <button class="btn btn-icon btn-round btn-success btn-sm me-2">
                                                <i class="bi bi-check-lg"></i>
                                            </button>
                                            Payment from #{{ str_pad($transaction->id, 3, '0', STR_PAD_LEFT) }}
                                        </th>
                                        <td>
                                            {{ $transaction->user->name }}
                                        </td>
                                        <td class="text-end">
                                            {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y, h:ia') }}
                                        </td>
                                        <td class="text-end">
                                         {{ number_format($transaction->amount, 2) }}
                                        </td>
                                        <td class="text-end">
                                            <span class="badge badge-success">Completed</span>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
