<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Petty Cash Replenishment</h3>
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
                <a href="#">Petty cash</a>
            </li>
        </ul>
    </div>

    <form action="{{ route('replenishment.pettycash') }}" method="GET" class="row g-3 mb-3">
        <div class="col-md-4">
            <label>From Date</label>
            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
        </div>
        <div class="col-md-4">
            <label>To Date</label>
            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
        </div>

        <div class="col-md-1 align-self-end">
            <button class="btn btn-primary w-100">Filter</button>
        </div>
    </form>

    <div class="col-md-3 mb-3">
        @can('approve petycash payments')
            @if (request('from') && request('to'))
                <form action="{{ route('replenishment.create') }}" method="GET" class="flex justify-content-end">
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <input type="hidden" name="to" value="{{ request('to') }}">
                    <button class="btn btn-primary w-100">New Replenishment</button>
                </form>
            @endif
        @endcan
    </div>


    <table class="table table-bordered">
        <thead class="table-light">
            <tr>

                <th>Date</th>
                <th>Particulars</th>
                <th>Amount (TZS)</th>
            </tr>
        </thead>
        <tbody>

          @forelse ($petties as $petty)
    @php
        $rowClass = $petty->replenishment_id ? 'table-danger' : '';
    @endphp

    <tr class="{{ $rowClass }}">
        <td>{{ $petty->paid_date ? \Carbon\Carbon::parse($petty->paid_date)->format('d/m/Y') : '-' }}</td>
        <td><strong>{{ $petty->request_for }}</strong></td>
        <td><strong>{{ number_format($petty->amount, 2) }}</strong></td>
    </tr>

    <tr class="{{ $rowClass }}">
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
    </tr>
@empty
    <tr>
        <td colspan="3">No Petty cash found.</td>
    </tr>
@endforelse

        </tbody>
    </table>



</x-app-layout>
