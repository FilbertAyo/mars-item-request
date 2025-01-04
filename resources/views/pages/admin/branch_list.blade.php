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

                            <li class="sidebar-item ">
                                <a class="sidebar-link" href="{{ route('admin.index') }}">
                                    <span class="align-middle">Users</span>
                                </a>
                            </li>

                            <li class="sidebar-item active">
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
                            <h1 class="h3 mb-3"> Branches List <a class="badge bg-primary text-white text-sm ms-2">
                                    {{ Auth::user()->department }}
                                </a></h1> <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Add Branch
                            </button>
                        </div>
                        
                        <div class="row">
                            <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                                <div class="card flex-fill">
                                    <div class="card-header">

                                        <h5 class="card-title mb-0">Branches</h5>
                                    </div>
                                    <table class="table table-hover my-0">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($branch->count() > 0)
                                                @foreach ($branch as $index => $branch)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td class="d-none d-xl-table-cell">{{ $branch->name }}</td>

                                                        <td>
                                                            <form action="{{ url('/destroy_branch/' . $branch->id) }}"
                                                                method="POST"
                                                                onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn-sm btn-danger">
                                                                    <i class="bi bi-trash"></i> Delete
                                                                </button>
                                                            </form>

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="3" class="text-center">No Branch found</td>
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
                                <h5 class="modal-title" id="exampleModalLabel">New Branch</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ url('/add_branch') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="topic" class="form-label">Branch name</label>
                                        <input type="text" class="form-control" id="topic" name="name" required>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Save</button>


                                </form>

                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>


@endsection
