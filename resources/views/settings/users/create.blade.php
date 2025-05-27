<x-app-layout>



    <div class="page-header">
        <h3 class="fw-bold mb-3">New User</h3>
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
                <a href="#">New User</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form method="POST" action="{{ route('admin.store') }}">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Fill the Details</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter full name" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter email address" required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        placeholder="Enter phone number" required>
                                </div>
                            </div>

                            <!-- Branch -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="branch_id">Branch</label>
                                    <select class="form-control" name="branch_id" id="branch_id" required>
                                        <option value="" disabled selected>-- Select Branch --</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="department_id">Role | Department</label>
                                    <select class="form-control" name="department_id" id="department_id" required>
                                        <option value="" disabled selected>-- Select Department --</option>
                                        @foreach ($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Role -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="userType">Role *</label>
                                    <select class="form-control" name="userType" id="userType" required>
                                        <option value="" disabled selected>-- Select Role --</option>
                                        <option value="1">Petty Cash</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action mt-3">
                        <x-primary-button label="Register" />
                    </div>
                </div>
            </form>


        </div>
    </div>




</x-app-layout>
