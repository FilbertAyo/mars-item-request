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

  <div class="max-w-4xl mx-auto py-8">

        <ul class="space-y-2">
            <li>
                <a href="{{ route('reports.users') }}" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                    Users List
                </a>
            </li>

            @can('view cashflow movements')
            <li>
                <a href="{{ route('reports.petties') }}" class="block px-4 py-2 bg-gray-100 rounded hover:bg-gray-200">
                    Petty Cash Report
                </a>
            </li>
            @endcan
        </ul>
    </div>
</x-app-layout>
