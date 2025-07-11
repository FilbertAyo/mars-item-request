@extends('pettycash.create')

@section('content')
    @php
        $requestFor = old('request_for', session('step1.request_for', ''));
        $isTransporter = old('is_transporter', null);
    @endphp

    <form method="POST" action="{{ route('petty.store.step3') }}" enctype="multipart/form-data" id="pettyForm">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="card-title">Transport Route</div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12" id="route_section">

                        <div class="row">
                            <div class="col-md-6 col-lg-6">
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
                                    <div class="form-group destination-group position-relative mt-2">
                                        <label>To:</label>
                                        <div class="input-group">
                                            <input type="text" name="destinations[]"
                                                class="form-control destination-input" id="destination-0" autocomplete="off"
                                                placeholder="Enter destination" value="{{ old('destinations.0') }}">
                                            <button type="button" class="btn btn-danger btn-remove-destination">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                        <div class="dropdown-menu w-100 suggestion-box" data-for="destination-0"
                                            style="display: none;"></div>
                                    </div>
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_destination"> <i
                                        class="bi bi-geo-alt-fill"></i> Add Destination</button>
                            </div>
                            <div class="col-md-6 col-lg-6">
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

                    @if ($requestFor == 'Sales Delivery')
                        <div class="col-md-6 col-lg-6 mt-3" id="transporter_section">
                            <div class="form-group">
                                <label for="transporter" class="d-block">Is it Transporter? <span
                                        class="text-danger">*</span></label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_transporter"
                                        id="transporter_yes" value="1" {{ $isTransporter == '1' ? 'checked' : '' }}
                                        {{ $requestFor == 'Sales Delivery' ? 'required' : '' }}>
                                    <label class="form-check-label" for="transporter_yes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_transporter" id="transporter_no"
                                        value="0" {{ $isTransporter == '0' ? 'checked' : '' }}
                                        {{ $requestFor == 'Sales Delivery' ? 'required' : '' }}>
                                    <label class="form-check-label" for="transporter_no">No</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="col-md-6 mt-3" id="attachment_section">
                        <div class="form-group">
                            <label for="attachment">Attachment (jpg, png, pdf) <span class="text-danger">*</span></label>
                            <input type="file" name="attachment" id="attachment" class="form-control">
                        </div>
                    </div>

                    <div class="card-action">
                        <a href="{{ route('petty.create.step1') }}" class="btn btn-secondary me-2">Back</a>
                        @if (session('step1.request_for', '') == 'Transport')
                            <x-primary-button label="Submit" />
                        @else
                            <button type="submit" class="btn btn-primary">Next</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    <script>
        function toggleAttachmentVisibility() {
            const requestFor = @json($requestFor);
            const isTransporterYes = document.getElementById('transporter_yes').checked;
            const attachmentSection = document.getElementById('attachment_section');

            if (requestFor === 'Transport') {
                // Always show attachment if Transport
                attachmentSection.style.display = 'block';
            } else if (requestFor === 'Sales Delivery') {
                attachmentSection.style.display = 'none';
            }
        }

        // Run on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleAttachmentVisibility();

            // Add change event listeners on transporter radio buttons
            document.getElementById('transporter_yes').addEventListener('change', toggleAttachmentVisibility);
            document.getElementById('transporter_no').addEventListener('change', toggleAttachmentVisibility);
        });
    </script>

    <script>
        const autocompleteRoute = "{{ route('stops.autocomplete') }}";
    </script>
    <script src="{{ asset('assets/js/custom/destination.js') }}"></script>
@endsection
