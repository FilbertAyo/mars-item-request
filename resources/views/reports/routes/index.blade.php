<x-app-layout>


    <div class="page-header">
        <h3 class="fw-bold mb-3">Start - Destinations Routes</h3>
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
                <a href="#">Routes report</a>
            </li>

        </ul>
    </div>

    <div class="mb-3">
        <a href="{{ route('reports.route.download', ['type' => 'pdf'] + request()->all()) }}" class="btn btn-danger"><i
                class="bi bi-file-earmark-pdf-fill me-2"></i> Download PDF</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Pick Point</th>
                <th>Destinations</th>
                <th> Amount (Tsh)</th>
                <th>Transport Mode</th>
            </tr>
        </thead>
        <tbody>
            @forelse($routes as $index => $route)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $route['pick_point'] }}</td>
                    <td>
                        {{ implode(' → ', $route['destinations']->toArray()) }}
                    </td>

                    <td>{{ number_format($route['petty_amount'], 0) }}</td>
                    <td>{{ $route['transport_mode'] }}</td>
                </tr>

            @empty
                <tr>
                    <td colspan="4">No route found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>


</x-app-layout>
    