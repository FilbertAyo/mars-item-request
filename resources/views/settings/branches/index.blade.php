<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Branches</h3>
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
                <a href="#">Branches</a>
            </li>

        </ul>
    </div>


    <div class="row">

        <div class="col-md-12">
            <div class="card shadow-none border">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Branches List</h4>
                    @can('update other settings')
                        <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New Branch
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>No. of Departments</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>No. of Departments</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($branches as $index => $branch)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->departments_count }}</td>
                                        <td>
                                            <div class="d-flex" style="gap: 3px;">
                                                @can('update other settings')
                                                    <form
                                                        action="{{ route('branch.destroy', Hashids::encode($branch->id)) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <div class="d-flex" style="gap: 3px;">
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger permission-alert">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                @endcan

                                                <a href="{{ route('branch.show', Hashids::encode($branch->id)) }}"
                                                    class="btn btn-sm btn-dark"><i class="bi bi-eye"></i></a>
                                            </div>

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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('branches.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="topic" class="form-label">Branch name</label>
                            <input type="text" class="form-control" id="topic" name="name" required>
                        </div>
                        <x-primary-button label="Add" />
                    </form>
                </div>
            </div>
        </div>
    </div>



</x-app-layout>
