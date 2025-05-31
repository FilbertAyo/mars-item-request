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
        <a href="{{ route('reports.petties.download', ['type' => 'csv'] + request()->all()) }}"
            class="btn btn-info">Download CSV</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Request For</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($petties as $petty)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $petty->user->name }}</td>
                    <td>{{ $petty->request_for }}</td>
                    <td>{{ $petty->amount }}</td>
                    <td>{{ $petty->created_at->format('d M Y') }}</td>
                    <td>{{ $petty->status }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No petties found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</x-app-layout>
