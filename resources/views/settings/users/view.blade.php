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

                    @can('users management settings')
                    <div class="d-flex align-items-center gap-2">
                        @if ($user->status == 'active')
                            <!-- Deactivate Form -->
                            <form action="{{ route('admin.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to deactivate this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-none p-0">
                                    <i class="bi bi-toggle-on text-success fs-2"></i>
                                </button>
                            </form>
                        @else
                            <!-- Activate Form -->
                            <form action="{{ route('admin.activate', $user->id) }}" method="POST"
                                onsubmit="return confirm('Are you sure you want to activate this user?');">
                                @csrf
                                <button type="submit" class="btn btn-none p-0">
                                    <i class="bi bi-toggle-off text-danger fs-2"></i>
                                </button>
                            </form>
                        @endif
                        <button type="button" class="btn btn-label-primary" data-bs-toggle="modal"
                            data-bs-target="#addRole">
                            Assign Role
                        </button>
                    </div>

                    @endcan

                </div>

                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <img src="{{ asset($user->file ?? 'image/prof.jpeg') }}" alt="User Image" class="img-fluid rounded-circle "
                                width="300" height="300">
                        </div>
                        <div class="col-md-8">
                            <div class="container">
                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Full Name</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control" value="{{ $user->name }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Email Address</label>
                                    <div class="col-sm-9">
                                        <input type="email" readonly class="form-control" value="{{ $user->email }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Phone Number</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control" value="{{ $user->phone }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Branch</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $user->branch?->name ?? 'No Branch' }}">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <label class="col-sm-3 col-form-label">Department</label>
                                    <div class="col-sm-9">
                                        <input type="text" readonly class="form-control"
                                            value="{{ $user->department?->name ?? 'No Department' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="social-media mt-4 d-flex gap-2">
                                <a class="btn btn-info" href="mailto:{{ $user->email }}">
                                    <i class="bi bi-envelope me-1"></i> Email
                                </a>
                                <a class="btn btn-success"
                                    href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $user->phone) }}"
                                    target="_blank">
                                    <i class="bi bi-whatsapp me-1"></i> WhatsApp
                                </a>
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


    <div class="modal fade" id="addRole" tabindex="-1" aria-labelledby="addRoleLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleLabel">Assign Permissions</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('assign.permissions', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <div class="row">
                                @foreach ($permissions as $permission)
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="permissions[]"
                                                value="{{ $permission->name }}" id="perm_{{ $permission->id }}"
                                                {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <x-primary-button label="Assign" />
                    </div>
                </form>

            </div>
        </div>
    </div>


</x-app-layout>
