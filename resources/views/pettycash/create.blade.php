<x-app-layout>



    <div class="page-header">
        <h3 class="fw-bold mb-3">New PettyCash</h3>
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
                <a href="{{ route('petty.index') }}">
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


    <form method="POST" action="{{ route('petty.store') }}" enctype="multipart/form-data" id="pettyForm">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="card-title">Request Info</div>
            </div>
            <div class="card-body">
                <div class="row">

                    <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="request_type">Request Type:</label>
                            <select name="request_type" id="request_type" class="form-control" required>
                                <option value="Petty Cash">Petty Cash</option>
                                <option value="Reimbursement">Reimbursement</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="name">Request for</label>
                            <select id="request_for" class="form-control" required>
                                <option disabled selected>-- Select Reason --</option>
                                <option value="Sales Delivery">Sales Delivery</option>
                                <option value="Transport">Transport</option>
                                <option value="Office Supplies">Office Supplies</option>
                                <option value="Others">Others</option>
                            </select>

                            {{-- The 'required' attribute will be managed by JavaScript --}}
                            <input type="text" id="other_option" class="form-control mt-3"
                                placeholder="Enter Other reason" style="display: none;">
                            <input type="hidden" name="request_for" id="final_request_for">
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="amount">Amount Needed:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="reason">Request Description:</label>
                            <textarea name="reason" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="col-12 mt-3" id="office_expense_section" style="display: none;">
                        <h5><i class="bi bi-clipboard me-2"></i>Office Materials</h5>
                        <div class="form-group">
                            <label for="items">List of Items:</label>
                            <div id="items_container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Item</th>
                                            <th>Quantity</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="mb-2">
                                            <td>
                                                <input type="text" name="items[]" class="form-control"
                                                    placeholder="eg. Rims paper">
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[]" class="form-control"
                                                    placeholder="eg. 10">
                                            </td>
                                            <td>
                                                <input type="number" name="price[]" class="form-control"
                                                    placeholder="eg. 1000">
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger btn-remove-item">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="add_item_btn" class="btn btn-secondary mt-2">Add Item</button>
                        </div>
                    </div>


                    <div class="col-md-12 mt-3" id="route_section" style="display: none;">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('image/sdfs.gif') }}" alt="Route Icon" style="width: 44px; height: 44px;"
                                class="me-2">
                            <h5 class="mb-0">Transport Route</h5>
                        </div>
                        <div class="form-group">
                            <label for="from_place">From:</label>
                            {{-- 'required' will be managed by JavaScript --}}
                            <select name="from_place" class="form-control">
                                <option value="">-- Select Starting Point --</option>
                                <option value="Samora">Samora</option>
                                <option value="Godown">Godown</option>
                            </select>
                        </div>
                        <div id="destination_fields">
                            <div class="form-group destination-group">
                                <label>To where:</label>
                                {{-- 'required' will be managed by JavaScript --}}
                                <input type="text" name="destinations[]" class="form-control"
                                    placeholder="Enter destination">
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_destination">+ Add
                            Another Destination</button>
                    </div>

                    <div class="col-md-12 mt-3" id="attachment_section" style="display: none;">
                        <div class="form-group">
                            <label for="attachment">Attach Receipt *:</label>
                            {{-- 'required' will be managed by JavaScript if Reimbursement is selected --}}
                            <input type="file" name="attachment" id="attachment" class="form-control">
                        </div>
                    </div>

                </div>
            </div>

            <div class="card-action">
                <x-primary-button label="Request" />
            </div>
        </div>
    </form>



    <script>
        const requestTypeSelect = document.getElementById('request_type'); // Renamed for clarity
        const requestForSelect = document.getElementById('request_for'); // Renamed for clarity
        const otherInput = document.getElementById('other_option');
        const finalRequestFor = document.getElementById('final_request_for');
        const form = document.getElementById('pettyForm');
        const attachmentSection = document.getElementById('attachment_section');
        const attachmentInput = document.getElementById('attachment'); // Get the attachment input itself

        // Function to update the hidden input's value
        function updateFinalRequestForValue() {
            if (requestForSelect.value === 'Others') {
                finalRequestFor.value = otherInput.value.trim();
            } else {
                finalRequestFor.value = requestForSelect.value;
            }
        }

        // Function to set/unset required attribute for elements within a container
        function setRequiredForChildren(containerId, isRequired, selector = 'input, select, textarea') {
            const container = document.getElementById(containerId);
            if (container) {
                const elements = container.querySelectorAll(selector);
                elements.forEach(element => {
                    element.required = isRequired;
                });
            }
        }

        // --- Event Listeners and Initial Setup ---

        // Initial setup when the page loads (in case of pre-selected values or reloads)
        document.addEventListener('DOMContentLoaded', function() {
            // Handle Request For sections on load
            const initialRequestForValue = requestForSelect.value;
            const isOfficeSupplies = initialRequestForValue === 'Office Supplies';
            const isOthers = initialRequestForValue === 'Others';
            const isTransportOrSales = (initialRequestForValue === 'Transport' || initialRequestForValue ===
                'Sales Delivery');

            otherInput.style.display = isOthers ? 'block' : 'none';
            otherInput.required = isOthers;

            document.getElementById('office_expense_section').style.display = isOfficeSupplies ? 'block' : 'none';
            setRequiredForChildren('office_expense_section', isOfficeSupplies, 'input');

            document.getElementById('route_section').style.display = isTransportOrSales ? 'block' : 'none';
            setRequiredForChildren('route_section', isTransportOrSales, 'input, select');

            updateFinalRequestForValue(); // Call for initial value

            // Handle Request Type sections on load (for attachment)
            const initialRequestTypeValue = requestTypeSelect.value;
            const isReimbursement = initialRequestTypeValue === 'Reimbursement';
            attachmentSection.style.display = isReimbursement ? 'block' : 'none';
            // Note: 'attachment' is optional by default, so we won't set it to required here
            // If you want it required for reimbursement, uncomment the next line:
            // attachmentInput.required = isReimbursement;
        });


        // On 'Request Type' select change (for attachment)
        requestTypeSelect.addEventListener('change', function() {
            const isReimbursement = this.value === 'Reimbursement';
            attachmentSection.style.display = isReimbursement ? 'block' : 'none';
            // If you want the attachment to be REQUIRED for reimbursement, uncomment this:
            // attachmentInput.required = isReimbursement;
        });


        // On 'Request for' select change
        requestForSelect.addEventListener('change', function() {
            const isOfficeSupplies = this.value === 'Office Supplies';
            const isOthers = this.value === 'Others';
            const isTransportOrSales = (this.value === 'Transport' || this.value === 'Sales Delivery');

            // Handle 'Others' input display and requirement
            otherInput.style.display = isOthers ? 'block' : 'none';
            otherInput.required = isOthers; // Dynamically set 'required'

            // Handle 'Office Supplies' section display and requirement for its children
            document.getElementById('office_expense_section').style.display = isOfficeSupplies ? 'block' : 'none';
            setRequiredForChildren('office_expense_section', isOfficeSupplies, 'input');

            // Handle 'Route' section display and requirement for its children
            document.getElementById('route_section').style.display = isTransportOrSales ? 'block' : 'none';
            setRequiredForChildren('route_section', isTransportOrSales, 'input, select');

            // Update the final value based on the current selection
            updateFinalRequestForValue();
        });

        // Update final value whenever the 'Other reason' input changes
        otherInput.addEventListener('input', updateFinalRequestForValue);

        // Ensure final value is always updated before form submission
        form.addEventListener('submit', function() {
            updateFinalRequestForValue();
        });

        // Add item (for Office Supplies section)
        document.getElementById('add_item_btn').addEventListener('click', function() {
            const isOfficeSuppliesSelected = true; // replace this with your actual condition
            const tbody = document.querySelector('#items_container tbody');

            const tr = document.createElement('tr');
            tr.innerHTML = `
        <td>
            <input type="text" name="items[]" class="form-control"  ${isOfficeSuppliesSelected ? 'required' : ''}>
        </td>
        <td>
            <input type="number" name="quantity[]" class="form-control"  ${isOfficeSuppliesSelected ? 'required' : ''}>
        </td>
        <td>
            <input type="number" name="price[]" class="form-control"  ${isOfficeSuppliesSelected ? 'required' : ''}>
        </td>
        <td>
            <button type="button" class="btn btn-danger btn-remove-item"><i class="bi bi-trash"></i></button>
        </td>
    `;
            tbody.appendChild(tr);
        });


        // Remove item (for Office Supplies section)
        document.getElementById('items_container').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-item')) {
                e.target.closest('.input-group').remove();
            }
        });

        // Add destination (for Route section)
        document.getElementById('add_destination').addEventListener('click', function() {
            const div = document.createElement('div');
            div.className = 'form-group destination-group mt-2';
            // Conditionally add 'required' to newly added fields if 'Transport' or 'Sales Delivery' is selected
            const isTransportOrSalesSelected = (requestForSelect.value === 'Transport' || requestForSelect.value ===
                'Sales Delivery');
            div.innerHTML = `
            <label>To where:</label>
            <div class="input-group">
                <input type="text" name="destinations[]" class="form-control" placeholder="Enter destination" ${isTransportOrSalesSelected ? 'required' : ''}>
                <button type="button" class="btn btn-danger btn-remove-destination"><i class="bi bi-trash"></i></button>
            </div>`;
            document.getElementById('destination_fields').appendChild(div);
        });

        // Remove destination (for Route section)
        document.getElementById('destination_fields').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-destination')) {
                e.target.closest('.destination-group').remove();
            }
        });
    </script>


</x-app-layout>
