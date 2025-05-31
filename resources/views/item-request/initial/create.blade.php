<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">New Item Request</h3>
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
                <a href="{{ route('item-request.index') }}">
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

    <form method="POST" action="{{ route('item-request.store') }}" enctype="multipart/form-data" id="pettyForm">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="card-title">Request Info</div>
            </div>
            <div class="card-body">
                <div class="row">
                    <input type="hidden" name="branch_id" value="{{ Auth::user()->branch_id }} ">

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="itemName" class="form-label">Item name</label>
                            <input type="text" class="form-control" id="itemName" name="name" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="price" class="form-label">Expected price at each</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                required>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="justification" class="form-label">Justification</label>
                            <div class="input-group">
                                <!-- Justification Dropdown -->
                                <select class="form-select" id="justification" name="justification_id">
                                    <option value="" selected>Select justification</option>
                                    @foreach ($justification as $just)
                                        <option value="{{ $just->id }}">{{ $just->name }}</option>
                                    @endforeach
                                    <option value="Other">Other...</option>
                                </select>

                                <!-- Button to open the modal, placed next to the select dropdown -->
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#addJustificationModal">
                                    New Justification
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 col-lg-12 mt-3">
                        <div class="form-group">
                            <label for="reason">Request Description:</label>
                           <textarea class="form-control" name="reason" rows="4" required></textarea>
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-action">
                <x-primary-button label="Request" />
            </div>
        </div>
    </form>



    <div class="modal fade" id="addJustificationModal" tabindex="-1" aria-labelledby="addJustificationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJustificationModalLabel">Add Justification</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('justify.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Justification</label>
                            <input type="text" class="form-control" name="name"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                       <x-primary-button label="Add" />
                    </div>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>
