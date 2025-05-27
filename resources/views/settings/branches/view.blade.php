<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">{{ $branch->name }} Branch</h3>
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
                <a href="{{ route('branches') }}">
                         Branches
                     </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Departments</a>
            </li>
        </ul>
    </div>


    <div class="row">

        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Departments List</h4>
                    <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <span class="btn-label">
                          <i class="bi bi-plus-lg"></i>
                        </span>
                        New Department
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($departments as $index => $department)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $department->name }}</td>
                                        <td>
                                            <div class="d-flex" style="gap: 3px;">

                                                <form action="{{ route('department.destroy',$department->id )  }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this Department?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>

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
                    <h5 class="modal-title" id="exampleModalLabel">New Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('department.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="branch_id" value="{{ $branch->id }}">

                        <div class="mb-3">
                            <label for="topic" class="form-label">Department name</label>
                            <input type="text" class="form-control" id="topic" name="name" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>



</x-app-layout>
