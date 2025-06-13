<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Replenishment Details</h3>
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
                <a href="{{ route('replenishment.index') }}">
                    Replenishment
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">Details</a>
            </li>
        </ul>
    </div>


    <div class="page-header flex-row justify-content-between">
        <h3 class="fw-bold mb-3">General Report</h3>

        @if ($replenishment->status == 'pending')
            @can('first pettycash approval')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                    Check
                </button>
            @endcan
        @elseif ($replenishment->status == 'processing')
            @can('last pettycash approval')
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#approvalModal">
                    Check
                </button>
            @endcan
        @endif

        <a href="{{ route('replenishment.download', $replenishment->id) }}" class="btn btn-primary">
            Download PDF
        </a>

    </div>




    <div class="modal-body">

        <div id="printable-pcv">
            <div class="pcv-container">
                @if ($replenishment->status == 'paid' || $replenishment->status == 'approved')
                    <div class="status-stamp paid">{{ strtoupper($replenishment->status) }}
                    </div>
                @else
                    <div class="status-stamp not-paid">{{ strtoupper($replenishment->status) }}</div>
                @endif
                <div class="pcv-header text-center">
                    <img src="{{ asset('image/longl.png') }}" alt="" class="mb-2" style="height: 60px;"><br>
                    P.O. BOX 20226, DSM, TANZANIA
                    TEL.: 022 2124760 | FAX: 2124759
                    Email: info@marstanzania.com
                </div>

                <div class="pcv-title">PETTY CASH VOUCHER</div>

                <div class="pcv-section pcv-flex pcv-space">
                    <div><span class="pcv-label">PCV No:</span>
                        #{{ str_pad($replenishment->id, 3, '0', STR_PAD_LEFT) }}
                    </div>
                    <div><span class="pcv-label">Date:</span>
                        {{ \Carbon\Carbon::parse($replenishment->created_at)->format('d/m/Y') }}</div>
                    <div class="pcv-label">
                        <span class="pcv-label">Department:</span> {{ Auth::user()->department->name ?? 'N/A' }}
                    </div>
                </div>

                <table class="pcv-table">
                    <thead>
                        <tr>
                            <th>DESCRIPTION</th>
                            <th>AMOUNT</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $replenishment->description }}</td>
                            <td>{{ number_format($replenishment->total_amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td><strong>TOTAL: {{ $amountInWords }}</strong></td>
                            <td><strong>{{ number_format($replenishment->total_amount, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>

                <div class="signature-row mb-5"
                    style="display: flex; justify-content: space-between; text-align: center; margin-top: 60px;">

                    <!-- Raised By -->
                    <div class="signature-block" style="flex: 1;">
                        <div>Raised by:</div>
                        <div
                            style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                            @if (!empty($initiator->user->signature))
                                <img src="{{ asset($initiator->user->signature) }}" alt="Signature"
                                    style="max-height: 60px;">
                            @endif
                        </div>
                        <div>{{ $initiator->user->name ?? '' }}</div>
                        <div>{{ $initiator->created_at->format('d/m/Y') }}</div>
                    </div>

                    <!-- Verified By -->
                    <div class="signature-block" style="flex: 1;">
                        <div>Verified by:</div>
                        <div
                            style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                            @if (!empty($verifier->user->signature))
                                <img src="{{ asset($verifier->user->signature) }}" alt="Signature"
                                    style="max-height: 60px;">
                            @endif
                        </div>
                        @if ($verifier)
                            <div>{{ $verifier->user->name ?? '' }}</div>
                            <div>{{ $verifier->created_at->format('d/m/Y') }}</div>
                        @endif
                    </div>

                    <!-- Approved By -->
                    <div class="signature-block" style="flex: 1;">
                        <div>Approved by:</div>
                        <div
                            style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                            @if (!empty($approver->user->signature))
                                <img src="{{ asset($approver->user->signature) }}" alt="Signature"
                                    style="max-height: 60px;">
                            @endif
                        </div>
                        @if ($approver)
                            <div>{{ $approver->user->name ?? '' }}</div>
                            <div>{{ $approver->created_at->format('d/m/Y') }}</div>
                        @endif
                    </div>

                </div>


            </div>

        </div>
    </div>

    <div class="card mt-5 p-3">
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th>Amount (TZS)</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($petties as $petty)
                    <!-- Summary Row -->
                    <tr>
                        <td>{{ $petty->created_at->format('d/m/Y') }}</td>
                        <td><strong>{{ $petty->request_for }}</strong></td>
                        <td><strong>{{ number_format($petty->amount, 2) }}</strong></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <div class="mb-1">
                                <strong>Name:</strong> {{ $petty->user->name }}
                            </div>
                            <div class="mb-1">
                                {{ $petty->reason }}
                            </div>

                            @if ($petty->request_for == 'Sales Delivery')
                                <div class="mb-1">
                                    <strong><em>Delivery Details:</em></strong>
                                    <ul class="mb-1">
                                        @foreach ($petty->attachments as $attachment)
                                            <li>{{ $attachment->name }}: {{ $attachment->product_name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div>
                                    <strong><em>Routes:</em></strong>
                                    <ul>
                                        @foreach ($petty->trips as $trip)
                                            <li>
                                                {{ $trip->startPoint->name }}
                                                @foreach ($trip->stops as $stop)
                                                    → {{ $stop->destination }}
                                                @endforeach
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif ($petty->request_for == 'Transport')
                                <div>
                                    <strong><em>Routes:</em></strong>
                                    <ul>
                                        @foreach ($petty->trips as $trip)
                                            <li>
                                                {{ $trip->startPoint->name }}
                                                @foreach ($trip->stops as $stop)
                                                    → {{ $stop->destination }}
                                                @endforeach
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif ($petty->request_for == 'Office Supplies')
                                <div>
                                    <strong><em>Items:</em></strong>
                                    <ul>
                                        @foreach ($petty->lists as $item)
                                            <li>
                                                {{ $item->item_name }} ({{ $item->quantity }}) –
                                                TZS {{ number_format($item->price) }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><strong>TOTAL:</strong></td>
                        <td><strong>{{ number_format($replenishment->total_amount, 2) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">No petties found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="signature-row mb-5"
            style="display: flex; justify-content: space-between; text-align: center; margin-top: 60px;">

            <!-- Raised By -->
            <div class="signature-block" style="flex: 1;">
                <div>Raised by:</div>
                <div
                    style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                    @if (!empty($initiator->user->signature))
                        <img src="{{ asset($initiator->user->signature) }}" alt="Signature" style="max-height: 60px;">
                    @endif
                </div>
                <div>{{ $initiator->user->name ?? '' }}</div>
                <div>{{ $initiator->created_at ? $initiator->created_at->format('d/m/Y') : '' }}</div>

            </div>

            <!-- Verified By -->
            <div class="signature-block" style="flex: 1;">
                <div>Verified by:</div>
                <div
                    style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                    @if (!empty($verifier->user->signature))
                        <img src="{{ asset($verifier->user->signature) }}" alt="Signature" style="max-height: 60px;">
                    @endif
                </div>
                @if ($verifier)
                    <div>{{ $verifier->user->name ?? '' }}</div>
                    <div>{{ $verifier->created_at->format('d/m/Y') }}</div>
                @endif

            </div>

            <!-- Approved By -->
            <div class="signature-block" style="flex: 1;">
                <div>Approved by:</div>
                <div
                    style="margin: 10px 0 5px; border-bottom: 1px solid #000; width: 50%; margin-left: auto; margin-right: auto;">
                    @if (!empty($approver->user->signature))
                        <img src="{{ asset($approver->user->signature) }}" alt="Signature" style="max-height: 60px;">
                    @endif
                </div>
                @if ($approver)
                    <div>{{ $approver->user->name ?? '' }}</div>
                    <div>{{ $approver->created_at->format('d/m/Y') }}</div>
                @endif
            </div>

        </div>
    </div>


    <div class="modal fade" id="approvalModal" data-bs-backdrop="static" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Replenishment Approval</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Step 1: Ask -->
                    <div id="step-approval">
                        <p>After reviewing this Replenishment, please choose to approve or reject.</p>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <!-- Reject Button -->
                            <button class="btn btn-danger" type="button" onclick="goToRejectStep()">Reject</button>

                            <!-- Approve Form -->
                            <form method="POST"
                                action="{{ $replenishment->status === 'processing'
                                    ? route('last.approve', ['id' => $replenishment->id])
                                    : route('initial.approve', ['id' => $replenishment->id]) }}">
                                @csrf
                                <x-primary-button label="Approve" />
                            </form>
                        </div>
                    </div>


                    <!-- Step 2: Reject Details -->
                    <div id="step-reject" class="d-none">
                        <form method="POST" action="{{ route('replenishment.destroy', $replenishment->id) }}">
                            @csrf
                            @method('DELETE')

                            <div class="form-group">
                                <label for="comment">Reason:</label>
                                <textarea class="form-control" name="comment" rows="4" required></textarea>
                            </div>

                            <div class="d-flex justify-content-between mt-3">
                                <button type="button" class="btn btn-secondary"
                                    onclick="backToApprovalStep()">Back</button>
                                <x-primary-button label='Submit' />
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        function goToRejectStep() {
            document.getElementById('step-approval').classList.add('d-none');
            document.getElementById('step-reject').classList.remove('d-none');
        }

        function backToApprovalStep() {
            document.getElementById('step-reject').classList.add('d-none');
            document.getElementById('step-approval').classList.remove('d-none');
        }
    </script>




    <style>
        .pcv-container {
            position: relative;
            font-family: Arial, sans-serif;
            padding: 20px;
            border: 1px solid #ccc;
            background: #fff;
            font-size: 14px;
        }

        .pcv-header {
            text-align: right;
            font-size: 13px;
        }

        .pcv-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin: 10px 0;
        }


        .pcv-flex {
            display: flex;
        }

        .pcv-space {
            justify-content: space-between;
        }

        .pcv-label {
            font-weight: bold;
            margin-right: 10px;
        }

        .pcv-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .pcv-table,
        .pcv-table th,
        .pcv-table td {
            border: 1px solid #000;
        }

        .pcv-table th,
        .pcv-table td {
            padding: 8px;
            text-align: left;
        }

        .status-stamp {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-25deg);
            font-size: 80px;
            font-weight: bold;
            opacity: 0.15;
            z-index: 0;
            white-space: nowrap;
            pointer-events: none;
            user-select: none;
        }

        .status-stamp.paid {
            color: green;
        }

        .status-stamp.not-paid {
            color: red;
        }
    </style>



</x-app-layout>
