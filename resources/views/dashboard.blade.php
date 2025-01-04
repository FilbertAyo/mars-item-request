@extends('layouts.petty_app')

@section('content')

    <div class="min-h-screen bg-gray-100">


        <main>
            <div class="wrapper">

                <nav id="sidebar" class="sidebar js-sidebar">
                    <div class="sidebar-content js-simplebar">
                        <a class="sidebar-brand" href="index.html">
                            <img src="/image/logo.png" alt="" class="img-fluid align-middle" style="max-height: 50px;">
                        </a>

                        <ul class="sidebar-nav">
                            <li class="sidebar-header">Pages</li>

                            <li class="sidebar-item active">
                                <a class="sidebar-link" href="{{ route('admin.index') }}">
                                    <span class="align-middle">Users</span>
                                </a>
                            </li>

                            <li class="sidebar-item">
                                <a class="sidebar-link" href="{{ url('/branch_list') }}">
                                    <span class="align-middle">Branch</span>
                                </a>
                            </li>
                        </ul>

                        <div class="sidebar-cta-content text-center p-2 bg-danger">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit">
                                    <strong class="d-inline-block">Logout</strong>
                                </button>
                            </form>
                        </div>


                    </div>
                </nav>
                <div class="main">
                    @include('layouts.navigation')

                    <main class="content">


                        <div class="mb-1" style="display: flex;justify-content: space-between;">
                            <h1 class="h3 mb-3"> Users <a class="badge bg-primary text-white text-sm ms-2">
                                    {{ Auth::user()->department }}
                                </a></h1>
                            <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add User
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-header">

                                        <h5 class="card-title mb-0">User registration</h5>
                                    </div>
                                    <table class="table table-hover my-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone number</th>
                                                <th>Department | Management</th>
                                                <th>Branch</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($user->count() > 0)
                                                @foreach ($user as $index => $user)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="d-none d-xl-table-cell">{{ $user->name }}</td>
                                                        <td class="d-none d-xl-table-cell">{{ $user->email }}</td>
                                                        <td>{{ $user->phone }}</td>
                                                        <td>{{ $user->department }}</td>
                                                        <td>{{ $user->branch }}</td>
                                                        <td>
                                                          

                                                            <span
                                                                class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                                {{ ucfirst($user->status) }}
                                                            </span>
                                                            <a href="{{ route('admin.show', $user->id) }}"
                                                                class="badge bg-primary text-white">view</a>


                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center">No user found</td>
                                                </tr>
                                            @endif

                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                    </main>


                </div>


                <script src="js/app.js"></script>



                <!-- Modal -->
                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">New User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('admin.store') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="topic" class="form-label">Full name</label>
                                        <input type="text" class="form-control" id="topic" name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="topicType" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="topicType" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="topicType" class="form-label">Phone number</label>
                                        <input type="text" class="form-control" id="topicType" name="phone">
                                    </div>
                                    <div class="mb-3">
                                        <label for="branch" class="form-label">Branch</label>
                                        <select class="form-select" id="branch" name="branch">
                                            <option value="" selected>Select branch</option>
                                            @foreach ($branches as $branch)
                                                <option value="{{ $branch->name }}">{{ $branch->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <label for="day" class="form-label">Role | Department</label>
                                            <select class="form-select" id="day" name="department">

                                                <option value="" selected>--Select Role --</option>
                                                <option value="Petty Cash">Petty Cash</option>
                                                <option value="Petty Cash Admin">Petty Cash Admin</option>
                                                <option value="Cashier">Cashier</option>
                                                <option value="IT department">IT department</option>
                                                <option value="Sales department">Sales department</option>
                                                <option value="Account and Finance">Account and Finance</option>
                                                <option value="Branch manager">Branch Manager</option>
                                                <option value="General manager">General Manager</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="dateTime" class="form-label">Approval stage</label>
                                            <input type="text" class="form-control" id="userType" name="userType"
                                                readonly>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>

                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            const daySelect = document.getElementById('day');
                                            const dateTimeInput = document.getElementById('userType');

                                            const userType = {
                                                'IT department': '1',
                                                'Sales department': '1',
                                                'Branch manager': '2',
                                                'General manager': '3',
                                                'Petty Cash': '4',
                                                'Petty Cash Admin': '5',
                                                'Cashier': '6',
                                            };

                                            daySelect.addEventListener('change', function() {
                                                const selectedDay = daySelect.value;
                                                dateTimeInput.value = userType[selectedDay] || '';
                                            });
                                        });
                                    </script>
                                </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>


@endsection
