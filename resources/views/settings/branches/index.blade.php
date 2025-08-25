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
                <div class="card-body">
                    <!-- Create/Edit Form -->
                    <form method="POST" action="{{ route('branches.store') }}" id="branchForm">
                        @csrf
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <!-- Will change to PUT on edit -->
                        <input type="hidden" name="branch_id" id="branchId">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="name" id="branchName"
                                    placeholder="Branch Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="region" id="branchRegion"
                                    placeholder="Region" required>
                            </div>
                            <div class="col-md-6">
                                <input type="url" class="form-control" name="location_url" id="branchLocation"
                                    placeholder="Location URL" required>
                            </div>
                            <div class="col-md-6">
                                <select name="status" id="branchStatus" class="form-control">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <x-primary-button label="Save" />
                    </form>

                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-header mb-1" style="display: flex; justify-content: space-between;">
                    <h4 class="h3 mb-3">Branches List</h4>
                </div>

                <div class="card-body">


                    <!-- Branches Table -->
                    <div class="table-responsive mt-4">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Region</th>
                                    <th>No. of Departments</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($branches as $index => $branch)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $branch->name }}</td>
                                        <td>{{ $branch->region }}</td>
                                        <td>{{ $branch->departments_count }}</td>
                                        <td>{{ ucfirst($branch->status) }}</td>
                                        <td>
                                            <div class="d-flex" style="gap: 3px;">
                                                <button type="button" class="btn btn-sm btn-primary edit-btn"
                                                    data-id="{{ Hashids::encode($branch->id) }}"
                                                    data-name="{{ $branch->name }}" data-region="{{ $branch->region }}"
                                                    data-location="{{ $branch->location_url }}"
                                                    data-status="{{ $branch->status }}">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <a href="{{ route('branch.show', Hashids::encode($branch->id)) }}"
                                                    class="btn btn-sm btn-dark"><i class="bi bi-eye"></i></a>
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


    <script>
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const form = document.getElementById('branchForm');
                form.action = `/branches/${id}`; // Must match PUT route
                document.getElementById('formMethod').value = 'PUT';
                document.getElementById('branchId').value = id;

                document.getElementById('branchName').value = this.dataset.name;
                document.getElementById('branchRegion').value = this.dataset.region;
                document.getElementById('branchLocation').value = this.dataset.location;
                document.getElementById('branchStatus').value = this.dataset.status;
            });
        });
    </script>




</x-app-layout>
