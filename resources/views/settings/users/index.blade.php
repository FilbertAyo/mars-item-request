<x-app-layout>

<div class="page-header">
        <h3 class="fw-bold mb-3">Users</h3>
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
                <a href="#">Users</a>
            </li>

        </ul>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Users List</h4>
                    <a href="{{ route('admin.create') }}" class="btn btn-dark">
                        <span class="btn-label">
                          <i class="bi bi-plus-lg"></i>
                        </span>
                        New User
                    </a>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                     <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Branch</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone number</th>
                                    <th>Branch</th>
                                    <th>Department</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($user as $index => $user)
                                    @if ($user->userType == 0)
                                        @continue
                                    @endif
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td>{{ $user->department->name }}</td>
                                        <td>{{ $user->branch->name }}</td>
                                        <td>{{ $user->userType }}</td>
                                        <td> <span
                                                class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ ucfirst($user->status) }}
                                            </span></td>
                                        <td>
                                           
                                            <a href="{{ route('admin.show', $user->id) }}" class="btn-sm btn-black">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>




</x-app-layout>
