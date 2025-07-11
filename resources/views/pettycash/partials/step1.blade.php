@extends('pettycash.create')

@section('content')
    <form method="POST" action="{{ route('petty.store.step1') }}" enctype="multipart/form-data" id="pettyForm">
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
                                <option value="Petty Cash"
                                    {{ old('request_type', session('step1.request_type')) == 'Petty Cash' ? 'selected' : '' }}>
                                    Petty Cash
                                </option>
                                <option value="Reimbursement"
                                    {{ old('request_type', session('step1.request_type')) == 'Reimbursement' ? 'selected' : '' }}
                                    disabled>
                                    Reimbursement
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="name">Request for</label>
                            <select name="request_for" class="form-control" required>
                                <option disabled {{ old('request_for', session('step1.request_for')) ? '' : 'selected' }}>--
                                    Select Reason --</option>
                                <option value="Sales Delivery"
                                    {{ old('request_for', session('step1.request_for')) == 'Sales Delivery' ? 'selected' : '' }}>
                                    Sales Delivery</option>
                                <option value="Transport"
                                    {{ old('request_for', session('step1.request_for')) == 'Transport' ? 'selected' : '' }}>
                                    Transport</option>
                                <option value="Office Supplies"
                                    {{ old('request_for', session('step1.request_for')) == 'Office Supplies' ? 'selected' : '' }}>
                                    Office Supplies</option>
                               
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="amount">Amount Needed:</label>
                            <input type="number" step="0.01" name="amount" class="form-control" required
                                value="{{ old('amount', session('step1.amount')) }}">
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-6 mt-3">
                        <div class="form-group">
                            <label for="reason">Description:</label>
                            <textarea name="reason" class="form-control" rows="4" cols="50" required>{{ old('reason', session('step1.reason')) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-action">
                <button type="submit" class="btn btn-primary">Next</button>
            </div>
        </div>
    </form>
@endsection
