<x-app-layout>

     <div class="page-header">
        <h3 class="fw-bold mb-3">RUNNING BALANCE REPORT</h3>
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
                <a href="#">Cash Flow</a>
            </li>

        </ul>
    </div>

    <div class="mb-3 d-flex justify-content-between gap-2">
        <form method="GET" action="{{ route('cashflow.index') }}">

            <div class="col-md-12">
                <select name="filter_type" class="form-control" onchange="this.form.submit()">
                    <option value="">Show All</option>
                    <option value="daily" {{ request('filter_type') == 'daily' ? 'selected' : '' }}>Daily</option>
                    <option value="monthly" {{ request('filter_type') == 'monthly' ? 'selected' : '' }}>Monthly
                    </option>
                </select>
            </div>

        </form>

        <div class="">
            <a href="{{ route('cashflow.download', ['format' => 'pdf', 'filter_type' => request('filter_type')]) }}"
                class="btn btn-danger">
                Download PDF
            </a>
            <a href="{{ route('cashflow.download', ['format' => 'excel', 'filter_type' => request('filter_type')]) }}"
                class="btn btn-success">
                Download Excel
            </a>
        </div>
    </div>


    <table class="table table-bordered">
        <thead>
            <tr>
                <th>{{ $filterType ? 'Date' : 'Transaction Date' }}</th>

                @unless ($isFiltered)
                    <th>Name</th>
                    <th>Deposit</th>
                @endunless

                <th>Deduction</th>
                <th>Running Balance</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $tx)
                <tr>
                    <td>{{ $tx['label'] ?? \Carbon\Carbon::parse($tx['date'])->format('Y-m-d') }}</td>

                    @unless ($isFiltered)
                        <td>{{ $tx['requested_by'] }}</td>
                        <td><strong>{{ $tx['deposit'] ? number_format($tx['deposit']) : '-' }}</strong></td>
                    @endunless

                    <td>{{ $tx['deduction'] ? number_format($tx['deduction']) : '-' }}</td>
                    <td class="{{ $tx['remaining'] < 0 ? 'text-danger' : '' }}">
                        {{ number_format($tx['remaining']) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>




    <script>
        function toggleInputs() {
            const type = document.querySelector('[name="filter_type"]').value;
            document.getElementById('date-input').style.display = type === 'daily' ? 'block' : 'none';
            document.getElementById('month-input').style.display = type === 'monthly' ? 'block' : 'none';
        }
        document.addEventListener('DOMContentLoaded', toggleInputs);
    </script>

</x-app-layout>
