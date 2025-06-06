<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold mb-3">Warranties</h3>
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
                <a href="#">Warranties</a>
            </li>

        </ul>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1 d-flex justify-content-between">
                    <h4 class="h3 mb-3">Warranties List</h4>
                    {{-- @can('create warranty') --}}
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#createWarrantyModal">
                        <span class="btn-label"><i class="bi bi-plus-lg"></i></span>
                        New Warranty
                    </button>

                    {{-- @endcan --}}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Customer Name</th>
                                    <th>Model</th>
                                    <th>Serial Number</th>
                                    <th>Amount</th>
                                    <th>Photo</th>
                                    <th>Created At</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Customer Name</th>
                                    <th>Model</th>
                                    <th>Serial Number</th>
                                    <th>Amount</th>
                                    <th>Photo</th>
                                    <th>Created At</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($warranties as $index => $warranty)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $warranty->customer_name }}</td>
                                        <td>{{ $warranty->model ?? '-' }}</td>
                                        <td>{{ $warranty->serial_number ?? '-' }}</td>
                                        <td>{{ number_format($warranty->amount) }}</td>
                                        <td>
                                            @if ($warranty->photo)
                                                <img src="{{ asset('storage/' . $warranty->photo) }}" alt="Photo"
                                                    width="50">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $warranty->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="modal fade " id="createWarrantyModal" tabindex="-1" aria-labelledby="createWarrantyModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('warranty.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createWarrantyModalLabel">Add Warranty</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="closeModalBtn"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Customer Name</label>
                            <input type="text" class="form-control" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label>Model</label>
                            <input type="text" class="form-control" name="model">
                        </div>
                        <div class="mb-3">
                            <label>Serial Number</label>
                            <input type="text" class="form-control" name="serial_number">
                        </div>
                        <div class="mb-3">
                            <label>Amount</label>
                            <input type="number" class="form-control" name="amount">
                        </div>
                        <div class="mb-3">
                            <label>Photo</label>
                            <input type="file" class="form-control" name="photo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save Warranty</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                            id="manualCloseBtn">Close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


</x-app-layout>
