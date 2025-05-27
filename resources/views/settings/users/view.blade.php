<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Profile</h3>
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
                <a href="{{ route('admin.index') }}">
                    Users
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Profile</a>
            </li>
        </ul>
    </div>


    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">User Information</h4>
                    @if ($user->status == 'active')
                        <form action="{{ route('admin.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Deactivate</button>
                        </form>
                    @else
                        <form action="{{ route('admin.activate', $user->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Activate</button>
                        </form>
                    @endif
                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <img src="/image/prof.jpeg" alt="User Image" class="img-fluid rounded-circle "
                                width="300" height="300">
                        </div>

                        <div class="col-md-8">
                            <ul class="list-group list-group-flush fs-5">
                                <li class="list-group-item bg-light">
                                    <strong class="me-2">Name:</strong> {{ $user->name }}
                                </li>
                                <li class="list-group-item">
                                    <strong class="me-2">Email:</strong> {{ $user->email }}
                                </li>
                                <li class="list-group-item bg-light">
                                    <strong class="me-2">Phone:</strong> {{ $user->phone }}
                                </li>
                                <li class="list-group-item">
                                    <strong class="me-2">Branch:</strong> {{ $user->branch?->name ?? 'No Branch' }}
                                </li>
                                <li class="list-group-item bg-light">
                                    <strong class="me-2">Department:</strong>
                                    {{ $user->department?->name ?? 'No Department' }}
                                </li>
                            </ul>

                            <div class="social-media mt-3 d-flex gap-2">
                                <!-- Email Button -->
                                <a class="btn btn-info" href="mailto:{{ $user->email }}">
                                    <i class="bi bi-envelope me-1"></i> Email
                                </a>

                                <!-- WhatsApp Button -->
                                <a class="btn btn-success"
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                                    target="_blank">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                </a>

                                <!-- Call Button -->
                                <a class="btn btn-primary" href="tel:{{ $user->phone }}">
                                    <i class="bi bi-telephone me-1"></i> Call
                                </a>
                            </div>

                        </div>



                    </div>
                </div>
            </div>
        </div>
    </div>


</x-app-layout>
