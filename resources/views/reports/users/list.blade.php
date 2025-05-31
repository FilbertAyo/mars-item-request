<x-app-layout>

    <h2>User Reports</h2>

    <form action="{{ route('reports.users') }}" method="GET" class="row g-3 mb-3">
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
        <a href="{{ route('reports.users.download', ['type' => 'pdf'] + request()->all()) }}"
            class="btn btn-danger"><i class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF</a>
        <a href="{{ route('reports.users.download', ['type' => 'excel'] + request()->all()) }}"
            class="btn btn-success"><i class="bi bi-file-earmark-excel-fill"></i> Download Excel</a>
        <a href="{{ route('reports.users.download', ['type' => 'csv'] + request()->all()) }}"
            class="btn btn-info">Download CSV</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
               <th>Name</th>
                <th>Email</th>
                <th>Phone Number</th>
                <th>Branch Name</th>
                <th>Department Name</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $user)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->phone }}</td>
                    <td>{{ $user->branch->name }}</td>
                    <td>{{ $user->department->name ?? 'N/A'}}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</x-app-layout>
