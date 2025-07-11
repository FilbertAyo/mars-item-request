<x-app-layout>


    <div class="page-header">
        <h3 class="fw-bold mb-3">New PettyCash</h3>
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
                <a href="{{ route('petty.index') }}">
                    Requests
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">New Request</a>
            </li>
        </ul>
    </div>


    @yield('content')



</x-app-layout>
