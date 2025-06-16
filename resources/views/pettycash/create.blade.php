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
                    <input type="hidden" name="department_id" value="{{ Auth::user()->department_id }}">

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="request_type">Request Type:</label>
                            <select name="request_type" id="request_type" class="form-control" required>
                                <option value="Petty Cash" {{ old('request_type') == 'Petty Cash' ? 'selected' : '' }}>
                                    Petty Cash</option>
                                <option value="Reimbursement"
                                    {{ old('request_type') == 'Reimbursement' ? 'selected' : '' }} disabled>
                                    Reimbursement
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="name">Request for</label>
                            <select id="request_for" class="form-control" required>
                                <option disabled {{ old('request_for') ? '' : 'selected' }}>-- Select Reason --</option>
                                <option value="Sales Delivery"
                                    {{ old('request_for') == 'Sales Delivery' ? 'selected' : '' }}>Sales Delivery
                                </option>
                                <option value="Transport" {{ old('request_for') == 'Transport' ? 'selected' : '' }}>
                                    Transport</option>
                                <option value="Office Supplies"
                                    {{ old('request_for') == 'Office Supplies' ? 'selected' : '' }}>Office Supplies
                                </option>
                                <option value="Others" {{ old('request_for') == 'Others' ? 'selected' : '' }}>Others
                                </option>
                            </select>


                            {{-- The 'required' attribute will be managed by JavaScript --}}
                            <input type="text" id="other_option" class="form-control mt-3"
                                placeholder="Enter Other reason" style="display: none;"
                                value="{{ old('other_option') }}">
                            <input type="hidden" name="request_for" id="final_request_for"
                                value="{{ old('request_for') }}">

                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="amount">Amount Needed:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required
                                value="{{ old('amount') }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="reason">Description:</label>
                            <textarea name="reason" class="form-control" rows="4" cols="50" required>{{ old('reason') }}</textarea>
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
                                                    placeholder="eg. Rims paper" value="{{ old('items.0') }}">

                                            </td>
                                            <td>
                                                <input type="number" name="quantity[]" class="form-control"
                                                    placeholder="eg. 10" value="{{ old('quantity.0') }}">

                                            </td>
                                            <td>
                                                <input type="number" name="price[]" class="form-control"
                                                    placeholder="eg. 1000" value="{{ old('price.0') }}">

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

                    <div class="col-md-12 mt-3" id="petty_attachments" style="display: none;">
                        <h5>Deliveries Attachments</h5>
                        <div class="form-group">
                            <label for="items">List of Items:</label>
                            <div id="attachments_container">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Customer Name</th>
                                            <th>Products</th>
                                            <th>Attachment (*photo < 2Mb)</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="mb-2">
                                            <td>
                                                <input type="text" name="attachments[0][customer_name]"
                                                    class="form-control mb-2" placeholder="Customer Name" required>

                                            </td>
                                            <td>
                                                <input type="text" name="attachments[0][product]"
                                                    class="form-control mb-2" required>
                                            </td>
                                            <td>
                                                <input type="file" name="attachments[0][file]"
                                                    class="form-control mb-2" required>

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
                            <button type="button" id="add_attachment_btn" class="btn btn-secondary mt-2">Add
                                Attachment</button>
                        </div>
                    </div>


                    <div class="col-md-12 mt-3" id="route_section" style="display: none;">

                        <div class="row">
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="d-flex align-items-center">
                                 
                                    <h5 class="mb-0">Transport Route</h5>
                                </div>
                                <div class="form-group">
                                    <label for="from_place">From:</label>
                                    <select name="from_place" class="form-control">
                                        <option value="">-- Select Starting Point --</option>
                                        @foreach ($pickingPoints as $pick)
                                            <option value="{{ $pick->id }}"
                                                {{ old('from_place') == $pick->id ? 'selected' : '' }}>
                                                {{ $pick->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div id="destination_fields">
                                    <div class="form-group position-relative">
                                        <label>To:</label>
                                        <input type="text" name="destinations[]" class="form-control"
                                            id="destination-input" autocomplete="off" placeholder="Enter destination"
                                            value="{{ old('destinations.0') }}">
                                        <div id="destination-suggestions" class="dropdown-menu w-100"
                                            style="display: none;">
                                        </div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_destination"> <i
                                        class="bi bi-geo-alt-fill"></i> Add
                                    Destination</button>
                            </div>
                            <div class="col-md-6 col-lg-6 mt-3">
                                <div class="form-group">
                                    <label for="trans_mode_id">Transport Mode:</label>
                                    <select name="trans_mode_id" id="trans_mode_id" class="form-control" required>
                                        <option value="" disabled {{ old('trans_mode_id') ? '' : 'selected' }}>
                                            -- Select Transport Mode --
                                        </option>
                                        @foreach ($trans as $transport)
                                            <option value="{{ $transport->id }}"
                                                {{ old('trans_mode_id') == $transport->id ? 'selected' : '' }}>
                                                {{ $transport->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                            </div>

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-6 mt-3" id="transporter_section" style="display: none;">
                        <div class="form-group">
                            <label for="transporter" class="d-block">Is it Transporter? <span
                                    class="text-danger">*</span></label>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_transporter"
                                    id="transporter_yes" value="1">
                                <label class="form-check-label" for="transporter_yes">Yes</label>
                            </div>

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_transporter"
                                    id="transporter_no" value="0">
                                <label class="form-check-label" for="transporter_no">No</label>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mt-3" id="attachment_section" style="display: none;">
                        <div class="form-group">
                            <label for="attachment">Attachment (jpg, png, pdf) <span
                                    class="text-danger">*</span></label>
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
        // ========== DOM ELEMENT DECLARATIONS ==========
        const requestTypeSelect = document.getElementById('request_type');
        const requestForSelect = document.getElementById('request_for');
        const otherInput = document.getElementById('other_option');
        const finalRequestFor = document.getElementById('final_request_for');
        const form = document.getElementById('pettyForm');
        const attachmentSection = document.getElementById('attachment_section');
        const officeExpenseSection = document.getElementById('office_expense_section');
        const routeSection = document.getElementById('route_section');
        const pettyAttachments = document.getElementById('petty_attachments');
        const itemsContainer = document.querySelector('#items_container');
        const addItemBtn = document.getElementById('add_item_btn');
        const addAttachmentBtn = document.getElementById('add_attachment_btn');
        const attachmentsContainer = document.querySelector('#attachments_container');
        const transporterSection = document.getElementById('transporter_section');
        const transporterYes = document.getElementById('transporter_yes');
        const transporterNo = document.getElementById('transporter_no');

        let attachmentIndex = 1; // Index for attachment fields

        // ========== UTILITY FUNCTIONS ==========

        function updateFinalRequestForValue() {
            finalRequestFor.value = requestForSelect.value === 'Others' ?
                otherInput.value.trim() :
                requestForSelect.value;
        }

        function setRequiredForChildren(containerId, isRequired, selector = 'input, select, textarea') {
            const container = document.getElementById(containerId);
            if (container) {
                const elements = container.querySelectorAll(selector);
                elements.forEach(element => {
                    element.required = isRequired;
                });
            }
        }

        // ========== EVENT LISTENERS ==========

        // Handle attachment section visibility
        requestTypeSelect.addEventListener('change', function() {
            const isReimbursement = this.value === 'Reimbursement';
            attachmentSection.style.display = isReimbursement ? 'block' : 'none';
        });


        function handleTransporterChange() {
            const isTransporter = document.querySelector('input[name="is_transporter"]:checked')?.value === "1";
            attachmentSection.style.display = isTransporter ? 'block' : 'none';
        }

        // Add event listeners for transporter radio buttons
        transporterYes.addEventListener('change', handleTransporterChange);
        transporterNo.addEventListener('change', handleTransporterChange);

        function toggleTransporterSection(show) {
            transporterSection.style.display = show ? 'block' : 'none';

            // Toggle required attribute only when visible
            transporterYes.required = show;
            transporterNo.required = show;

            if (!show) {
                // If hiding, clear selection
                transporterYes.checked = false;
                transporterNo.checked = false;
            }
        }

        // Optional: initialize state on page load
        window.addEventListener('DOMContentLoaded', handleTransporterChange);

        // Handle form section visibility and required state
        requestForSelect.addEventListener('change', function() {
            const value = this.value;
            const isOfficeSupplies = value === 'Office Supplies';
            const isOthers = value === 'Others';
            const isTransportOrSales = value === 'Transport' || value === 'Sales Delivery';
            const isDelivery = value === 'Sales Delivery';
            const isTransport = value === 'Transport';

            // Show/hide "Other" input
            otherInput.style.display = isOthers ? 'block' : 'none';
            otherInput.required = isOthers;

            // Office Supplies section
            officeExpenseSection.style.display = isOfficeSupplies ? 'block' : 'none';
            setRequiredForChildren('office_expense_section', isOfficeSupplies, 'input');

            // Route section
            routeSection.style.display = isTransportOrSales ? 'block' : 'none';
            setRequiredForChildren('route_section', isTransportOrSales, 'input, select');

            // Attachments for delivery
            pettyAttachments.style.display = isDelivery ? 'block' : 'none';
            setRequiredForChildren('petty_attachments', isDelivery, 'input, select');

            // Transporter section
            toggleTransporterSection(isDelivery);

            attachmentSection.style.display = isTransport ? 'block' : 'none';
            setRequiredForChildren('attachment_section', isTransport, 'input');

            updateFinalRequestForValue();
        });

        // Update final value on typing in "Other"
        otherInput.addEventListener('input', updateFinalRequestForValue);

        // Ensure final value is updated on submit
        form.addEventListener('submit', updateFinalRequestForValue);

        // ========== OFFICE SUPPLIES ADD/REMOVE ITEM ==========
        addItemBtn.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td><input type="text" name="items[]" class="form-control" required></td>
            <td><input type="number" name="quantity[]" class="form-control" required></td>
            <td><input type="number" name="price[]" class="form-control" required></td>
            <td><button type="button" class="btn btn-danger btn-remove-item"><i class="bi bi-trash"></i></button></td>
        `;
            itemsContainer.querySelector('tbody').appendChild(tr);
        });

        itemsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-item')) {
                e.target.closest('tr').remove();
            }
        });

        // ========== ATTACHMENT ADD/REMOVE ==========
        addAttachmentBtn.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.innerHTML = `
            <td><input type="text" name="attachments[${attachmentIndex}][customer_name]" class="form-control mb-2" placeholder="Customer Name" required></td>
            <td><input type="text" name="attachments[${attachmentIndex}][product]" class="form-control mb-2" required></td>
            <td><input type="file" name="attachments[${attachmentIndex}][file]" class="form-control mb-2" required></td>
            <td><button type="button" class="btn btn-danger btn-remove-item"><i class="bi bi-trash"></i></button></td>
        `;
            attachmentsContainer.querySelector('tbody').appendChild(tr);
            attachmentIndex++;
        });

        attachmentsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-item')) {
                e.target.closest('tr').remove();
            }
        });
    </script>


    <script>
        const autocompleteRoute = "{{ route('stops.autocomplete') }}";
    </script>
    <script src="{{ asset('assets/js/custom/destination.js') }}"></script>



</x-app-layout>
