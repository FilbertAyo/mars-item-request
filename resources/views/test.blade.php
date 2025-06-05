<x-app-layout>

   

    <form method="POST" action="{{ route('petty.update', $petty->id) }}" enctype="multipart/form-data" id="pettyForm">
        @csrf
        @method('PUT')

        <div class="card">
            <div class="card-header">
                <div class="card-title">Request Info</div>
            </div>
            <div class="card-body">
                <div class="row">

                  
                  

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
                                        @foreach ($items as $item)
                                            <tr class="mb-2">
                                                <td>
                                                    <input type="text" name="items[]" class="form-control"
                                                        placeholder="eg. Rims paper"
                                                        value="{{ old('items.0', $item->item_name) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control"
                                                        placeholder="eg. 10"
                                                        value="{{ old('quantity.0', $item->quantity) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="price[]" class="form-control"
                                                        placeholder="eg. 1000"
                                                        value="{{ old('price.0', $item->price) }}">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-remove-item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div>
                            <button type="button" id="add_item_btn" class="btn btn-secondary mt-2">Add Item</button>
                        </div>
                    </div>
                    


                    <div class="col-md-12 mt-3" id="route_section" style="display: none;">

                        <div class="row">
                            <div class="col-md-6 col-lg-6 mt-3">

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
                                                {{ old('from_place') == $pick->id ? 'selected' : '' }}>
                                                {{ $pick->name }}
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
                                        @foreach ($trans as $transport)
                                            <option value="{{ $transport->id }}"
                                                {{ old('trans_mode_id', $transport->trans_mode_id) == $transport->id ? 'selected' : '' }}>
                                                {{ $transport->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
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
                @if ($petty->status == 'pending')
                    <x-primary-button label="Update" />
                @elseif($petty->status == 'resubmission')
                    <x-primary-button label="Resubmit" />
                @endif

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
