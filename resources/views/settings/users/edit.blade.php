<x-app-layout>



    <div class="page-header">
        <h3 class="fw-bold mb-3">Edit User</h3>
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
                <a href="#">Edit User</a>
            </li>
        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">

            <form method="POST" action="{{ route('admin.update', $user->id) }}">
                @csrf
                @method('PUT')

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Details</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Full Name -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="name">Full Name</label>
                                    <input type="text" class="form-control" name="name" id="name"
                                        placeholder="Enter full name" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="email">Email Address</label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Enter email address" value="{{ old('email', $user->email) }}"
                                        required>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="phone">Phone Number</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        placeholder="Enter phone number" value="{{ old('phone', $user->phone) }}"
                                        required>
                                </div>
                            </div>

                            <!-- Branch -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="branch_id">Branch</label>
                                    <select class="form-control" name="branch_id" id="branch_id" required>
                                        <option value="" disabled
                                            {{ old('branch_id', $user->branch_id) ? '' : 'selected' }}>-- Select Branch
                                            --</option>
                                        @foreach ($branches as $branch)
                                            <option value="{{ $branch->id }}"
                                                {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>
                                                {{ $branch->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Department -->
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="department_id">Role | Department</label>
                                    <select class="form-control" name="department_id" id="department_id">
                                        <option value="" disabled
                                            {{ old('department_id', $user->department_id) ? '' : 'selected' }}>--
                                            Select Department --</option>
                                        {{-- Departments will be loaded via AJAX --}}
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-action mt-3">
                        <x-primary-button label="Update" />
                    </div>
                </div>
            </form>


        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const branchSelect = document.getElementById('branch_id');
            const departmentSelect = document.getElementById('department_id');

            branchSelect.addEventListener('change', function() {
                const branchId = this.value;

                // Clear existing department options
                departmentSelect.innerHTML =
                    '<option value="" disabled selected>-- Select Department --</option>';

                if (branchId) {
                    // Make an AJAX request to fetch departments for the selected branch
                    fetch('/departments/by/branch/' + branchId) // Adjust this URL to your route
                        .then(response => response.json())
                        .then(data => {
                            // --- Added logic here ---
                            if (data.length > 0) {
                                data.forEach(department => {
                                    const option = document.createElement('option');
                                    option.value = department.id;
                                    option.textContent = department.name;
                                    departmentSelect.appendChild(option);
                                });
                            } else {
                                const option = document.createElement('option');
                                option.value = ""; // Keep value empty or some indicator if needed
                                option.textContent = "No departments for this branch";
                                option.disabled = true; // Make it non-selectable
                                departmentSelect.appendChild(option);
                            }
                            // --- End of added logic ---
                        })
                        .catch(error => {
                            console.error('Error fetching departments:', error);
                            // You can also add a "No departments to show" option or an error message here
                            const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "Error loading departments";
                            option.disabled = true;
                            departmentSelect.appendChild(option);
                        });
                }
            });
        });
    </script>



</x-app-layout>
