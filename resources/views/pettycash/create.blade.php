<x-app-layout>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>


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
                                    {{ old('request_type') == 'Reimbursement' ? 'selected' : '' }}>Reimbursement
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
                            <label for="reason">Request Description:</label>
                            <textarea name="reason" class="form-control" required>{{ old('reason') }}</textarea>

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


                    <div class="col-md-12 mt-3" id="route_section" style="display: none;">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('image/sdfs.gif') }}" alt="Route Icon"
                                style="width: 44px; height: 44px;" class="me-2">
                            <h5 class="mb-0">Transport Route</h5>
                        </div>
                        <div class="form-group">
                            <label for="from_place">From:</label>
                            {{-- 'required' will be managed by JavaScript --}}
                            <select name="from_place" class="form-control">
                                <option value="">-- Select Starting Point --</option>
                                @foreach ($pickingPoints as $pick)
                                    <option value="{{ $pick->id }}"
                                        {{ old('from_place') == $pick->id ? 'selected' : '' }}>{{ $pick->name }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <div id="destination_fields">
                            <div class="form-group position-relative">
                                <label>To where:</label>
                                <input type="text" name="destinations[]" class="form-control"
                                    id="destination-input" autocomplete="off" placeholder="Enter destination"
                                    value="{{ old('destinations.0') }}">

                                <div id="destination-suggestions" class="dropdown-menu w-100" style="display: none;">
                                </div>
                            </div>

                        </div>

                        <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_destination"> <i
                                class="bi bi-geo-alt-fill"></i> Add
                            Destination</button>
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
            div.className = 'form-group destination-group mt-2 position-relative';

            const uniqueId = 'destination-' + Date.now(); // unique ID for suggestion container
            const isTransportOrSalesSelected = (requestForSelect.value === 'Transport' || requestForSelect.value ===
                'Sales Delivery');

            div.innerHTML = `
        <label>To where:</label>
        <input type="text" name="destinations[]" class="form-control destination-input" id="${uniqueId}" placeholder="Enter destination" ${isTransportOrSalesSelected ? 'required' : ''} autocomplete="off">
        <div class="dropdown-menu w-100 suggestion-box" data-for="${uniqueId}" style="display: none;"></div>
    `;

            document.getElementById('destination_fields').appendChild(div);
        });


        // Remove destination (for Route section)
        document.getElementById('destination_fields').addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-destination')) {
                e.target.closest('.destination-group').remove();
            }
        });
    </script>



    <script>
        $(document).ready(function() {
            // =======================
            // STATIC INPUT AUTOCOMPLETE
            // =======================
            $('#destination-input').on('input', function() {
                let query = $(this).val();
                if (query.length < 1) {
                    $('#destination-suggestions').hide().empty();
                    return;
                }

                $.ajax({
                    url: "{{ route('stops.autocomplete') }}",
                    data: {
                        term: query
                    },
                    success: function(data) {
                        let dropdown = $('#destination-suggestions');
                        dropdown.empty();

                        if (data.length === 0) {
                            dropdown.hide();
                            return;
                        }

                        data.forEach(item => {
                            dropdown.append(
                                `<button type="button" class="dropdown-item">${item}</button>`
                            );
                        });

                        dropdown.show();
                    }
                });
            });

            // Suggestion click for static input
            $(document).on('click', '#destination-suggestions .dropdown-item', function() {
                $('#destination-input').val($(this).text());
                $('#destination-suggestions').hide();
            });

            // =======================
            // DYNAMIC INPUT AUTOCOMPLETE
            // =======================
            $(document).on('input', '.destination-input', function() {
                const $input = $(this);
                const query = $input.val();
                const inputId = $input.attr('id');
                const $dropdown = $(`.suggestion-box[data-for="${inputId}"]`);

                if (query.length < 1) {
                    $dropdown.hide().empty();
                    return;
                }

                $.ajax({
                    url: "{{ route('stops.autocomplete') }}",
                    data: {
                        term: query
                    },
                    success: function(data) {
                        $dropdown.empty();

                        if (data.length === 0) {
                            $dropdown.hide();
                            return;
                        }

                        data.forEach(item => {
                            $dropdown.append(
                                `<button type="button" class="dropdown-item">${item}</button>`
                            );
                        });

                        $dropdown.show();
                    }
                });
            });

            // Suggestion click for dynamic input
            $(document).on('click', '.suggestion-box .dropdown-item', function() {
                const selectedText = $(this).text();
                const $dropdown = $(this).closest('.suggestion-box');
                const inputId = $dropdown.data('for');
                $(`#${inputId}`).val(selectedText);
                $dropdown.hide();
            });

            // Hide all dropdowns when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.form-group, .destination-group').length) {
                    $('#destination-suggestions').hide(); // for static
                    $('.suggestion-box').hide(); // for dynamic
                }
            });
        });
    </script>

</x-app-layout>
