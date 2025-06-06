<x-app-layout>

    <h2>User Reports</h2>

    <form action="{{ route('reports.petties') }}" method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <label>From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>
        <div class="col-md-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="">-- All Petty Cash --</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <!-- Add more status options if needed -->
            </select>
        </div>
        <div class="col-md-1 align-self-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>


    <div class="mb-3">
        <a href="{{ route('reports.petties.download', ['type' => 'pdf'] + request()->all()) }}"
            class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF</a>
        <a href="{{ route('reports.petties.download', ['type' => 'excel'] + request()->all()) }}"
            class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i> Download Excel</a>

    </div>


    <table class="table table-bordered">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Particulars</th>
            <th>Amount (TZS)</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($petties as $petty)
            <!-- Summary Row -->
            <tr>
                <td>{{ $petty->created_at->format('d/m/Y') }}</td>
                <td><strong>{{ $petty->request_for }}</strong></td>
                <td><strong>{{ number_format($petty->amount, 2) }}</strong></td>
                <td>{{ ucfirst($petty->status) }}</td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <div class="mb-1">
                        <strong>Name:</strong> {{ $petty->user->name }}
                    </div>
                    <div class="mb-1">
                         {{ $petty->reason }}
                    </div>

                    @if ($petty->request_for == 'Sales Delivery')
                        <div class="mb-1">
                            <strong><em>Delivery Details:</em></strong>
                            <ul class="mb-1">
                                @foreach ($petty->attachments as $attachment)
                                    <li>{{ $attachment->name }}: {{ $attachment->product_name }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <strong><em>Routes:</em></strong>
                            <ul>
                                @foreach ($petty->trips as $trip)
                                    <li>
                                        {{ $trip->startPoint->name }}
                                        @foreach ($trip->stops as $stop)
                                            → {{ $stop->destination }}
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    @elseif ($petty->request_for == 'Transport')
                        <div>
                            <strong><em>Routes:</em></strong>
                            <ul>
                                @foreach ($petty->trips as $trip)
                                    <li>
                                        {{ $trip->startPoint->name }}
                                        @foreach ($trip->stops as $stop)
                                            → {{ $stop->destination }}
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                    @elseif ($petty->request_for == 'Office Supplies')
                        <div>
                            <strong><em>Items:</em></strong>
                            <ul>
                                @foreach ($petty->lists as $item)
                                    <li>
                                        {{ $item->item_name }} ({{ $item->quantity }}) –
                                        TZS {{ number_format($item->price) }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </td>
                <td></td>
                <td></td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center">No petties found.</td>
            </tr>
        @endforelse
    </tbody>
</table>



</x-app-layout>
