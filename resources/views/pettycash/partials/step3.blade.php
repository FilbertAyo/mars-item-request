@extends('pettycash.create')

@section('content')
    @php
        // request_for should work for both create (session) and edit (model)
        $requestFor = old('request_for', session('step1.request_for', $pettyCash->request_for ?? ''));
        $isTransporter = old('is_transporter', $trip->is_transporter ?? null);

        // Pre-fill destinations from old() -> DB -> at least one empty row
        $destinations = old('destinations', !empty($stops) ? $stops : ['']);
    @endphp

    <form method="POST"
        action="{{ $mode === 'edit' ? route('petty.update.step3', Hashids::encode($pettyCash->id)) : route('petty.store.step3') }}"
        enctype="multipart/form-data" id="pettyForm">
        @csrf
        @if ($mode === 'edit')
            @method('PUT')
        @endif

        <div class="card">
            <div class="card-header">
                <div class="card-title">Transport Route</div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12" id="route_section">
                        <div class="row">
                            <div class="col-md-6 col-lg-6">
                                {{-- From --}}
                                <div class="form-group">
                                    <label for="from_place">From:</label>
                                    <select name="from_place" id="from_place" class="form-control">
                                        <option value="">-- Select Starting Point --</option>
                                        @foreach ($pickingPoints as $pick)
                                            <option value="{{ $pick->id }}"
                                                {{ (string) old('from_place', $trip->from_place ?? '') === (string) $pick->id ? 'selected' : '' }}>
                                                {{ $pick->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Destinations --}}
                                <div id="destination_fields">
                                    @foreach ($destinations as $i => $destination)
                                        <div class="form-group destination-group position-relative mt-2">
                                            <label>To:</label>
                                            <div class="input-group">
                                                <input type="text" name="destinations[]"
                                                    class="form-control destination-input"
                                                    id="destination-{{ $i }}" autocomplete="off"
                                                    placeholder="Enter destination"
                                                    value="{{ old("destinations.$i", $destination) }}">
                                                <button type="button" class="btn btn-danger btn-remove-destination">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                            <div class="dropdown-menu w-100 suggestion-box"
                                                data-for="destination-{{ $i }}" style="display: none;"></div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" class="btn btn-sm btn-secondary mt-2" id="add_destination">
                                    <i class="bi bi-geo-alt-fill"></i> Add Destination
                                </button>
                            </div>

                            {{-- Transport Mode --}}
                            <div class="col-md-6 col-lg-6">
                                <div class="form-group">
                                    <label for="trans_mode_id">Transport Mode:</label>
                                    <select name="trans_mode_id" id="trans_mode_id" class="form-control" required>
                                        <option value="" disabled
                                            {{ old('trans_mode_id', $pettyCash->transMode->name ?? '') ? '' : 'selected' }}>
                                            -- Select Transport Mode --
                                        </option>
                                        @foreach ($trans as $transport)
                                            <option value="{{ $transport->id }}"
                                                {{ (string) old('trans_mode_id', $trip->trans_mode_id ?? '') === (string) $transport->id ? 'selected' : '' }}>
                                                {{ $transport->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Transporter (only for Sales Delivery) --}}
                    @if ($requestFor === 'Sales Delivery')
                        @php
                            $isTransporter = isset($pettyCash) ? $pettyCash->is_transporter : old('is_transporter');
                        @endphp

                        <div class="col-md-6 col-lg-6 mt-3" id="transporter_section">
                            <div class="form-group">
                                <label for="transporter" class="d-block">
                                    Is it Transporter? <span class="text-danger">*</span>
                                </label>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_transporter"
                                        id="transporter_yes" value="1" {{ $isTransporter == '1' ? 'checked' : '' }}
                                        required>
                                    <label class="form-check-label" for="transporter_yes">Yes</label>
                                </div>

                                <div class="form-check form-check-inline">

                                    <input class="form-check-input" type="radio" name="is_transporter" id="transporter_no"
                                        value="0" {{ $isTransporter == '0' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="transporter_no">No</label>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Attachment --}}
                    <div class="col-md-6 mt-3" id="attachment_section">
                        <div class="form-group">
                            <label for="attachment">
                                Attachment (jpg, png, pdf)
                                @if ($requestFor === 'Transport')
                                    <span class="text-danger">*</span>
                                @endif
                            </label>
                            <input type="file" name="attachment" id="attachment" class="form-control">

                            @if ($mode === 'edit')
                                <small class="d-block mt-3">
                                    Current: <a href="{{ asset($pettyCash->attachment) }}" target="_blank"
                                        class="badge badge-primary">View file</a>
                                </small>
                            @endif
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="card-action">
                        <a href="{{ $mode === 'edit' ? route('petty.edit.step1', Hashids::encode($pettyCash->id)) : route('petty.create.step1') }}"
                            class="btn btn-secondary me-2">Back</a>

                        @php
                            $isTransportFlow = $requestFor === 'Transport';
                        @endphp

                        @if ($mode === 'edit')
                            <x-primary-button label="{{ $isTransportFlow ? 'Update' : 'Update and Next' }}" />
                        @else
                            @if ($isTransportFlow)
                                <x-primary-button label="Submit" />
                            @else
                                <button type="submit" class="btn btn-primary">Next</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </form>

    {{-- Visibility logic --}}
    <script>
        function toggleAttachmentVisibility() {
            const requestFor = @json($requestFor);
            const attachmentSection = document.getElementById('attachment_section');

            if (!attachmentSection) return;

            if (requestFor === 'Transport') {
                attachmentSection.style.display = 'block';
            } else if (requestFor === 'Sales Delivery') {
                attachmentSection.style.display = 'none';
            } else {
                attachmentSection.style.display = 'block';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            toggleAttachmentVisibility();

            // Guarded listeners (exist only if Sales Delivery block rendered)
            const yesEl = document.getElementById('transporter_yes');
            const noEl = document.getElementById('transporter_no');

            if (yesEl) yesEl.addEventListener('change', toggleAttachmentVisibility);
            if (noEl) noEl.addEventListener('change', toggleAttachmentVisibility);
        });
    </script>

    {{-- Destinations autocomplete --}}
    <script>
        const autocompleteRoute = "{{ route('stops.autocomplete') }}";
    </script>
    <script src="{{ asset('assets/js/custom/destination.js') }}"></script>
@endsection
