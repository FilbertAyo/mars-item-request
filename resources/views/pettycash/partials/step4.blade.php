@extends('pettycash.create')

@section('content')
    <form method="POST"
        action="{{ $mode == 'edit' ? route('petty.update.step4', Hashids::encode($pettyCash->id)) : route('petty.store.step4') }}"
        enctype="multipart/form-data" id="pettyForm">
        @csrf
        @if ($mode == 'edit')
            @method('PUT')
        @endif

        <div class="card shadow-none border">
            <div class="card-header">
                <div class="card-title">Deliveries Attachments</div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-12" id="petty_attachments">
                        <h5></h5>
                        <div class="form-group">
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
                                    @php
                                        $attachmentsData = old('attachments', $attachments);
                                    @endphp

                                    <tbody>
                                        @foreach ($attachmentsData as $i => $attach)
                                            <tr class="mb-2">
                                                <td>
                                                    <input type="text"
                                                        name="attachments[{{ $i }}][customer_name]"
                                                        class="form-control mb-2" placeholder="Customer Name"
                                                        value="{{ old("attachments.$i.customer_name", $attach->name ?? '') }}"
                                                        required>
                                                </td>
                                                <td>
                                                    <div class="products-table">
                                                        <table class="table table-sm table-borderless">
                                                            <thead>
                                                                <tr>
                                                                    <th>Product</th>
                                                                    <th>Qty</th>
                                                                    <th><button type="button"
                                                                            class="btn btn-sm btn-success add-product"><i
                                                                                class="bi bi-plus"></i></button></th>
                                                                </tr>
                                                            </thead>
                                                            <tbody class="product-rows">
                                                                @php
                                                                    $products = $attach->products ?? [
                                                                        ['name' => '', 'qty' => ''],
                                                                    ];
                                                                @endphp
                                                                @foreach ($products as $j => $product)
                                                                    <tr>
                                                                        <td>
                                                                            <input type="text"
                                                                                name="attachments[{{ $i }}][products][{{ $j }}][name]"
                                                                                class="form-control"
                                                                                value="{{ $product['name'] ?? '' }}"
                                                                                required>
                                                                        </td>
                                                                        <td>
                                                                            <input type="number"
                                                                                name="attachments[{{ $i }}][products][{{ $j }}][qty]"
                                                                                class="form-control"
                                                                                value="{{ $product['qty'] ?? '' }}"
                                                                                required>
                                                                        </td>
                                                                        <td>
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger remove-product"><i
                                                                                    class="bi bi-trash"></i></button>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="file" name="attachments[{{ $i }}][file]"
                                                        class="form-control mb-2">
                                                    @if (isset($attach->attachment))
                                                        <a href="{{ asset($attach->attachment) }}" target="_blank">View
                                                            existing</a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-remove-item">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                          <tr class="mb-2">
                                            <td>
                                                <input type="text" name="attachments[0][customer_name]"
                                                    class="form-control mb-2" placeholder="Customer Name" required>

                                            </td>
                                            <td>
                                                <div class="products-table">
                                                    <table class="table table-sm table-borderless">
                                                        <thead>
                                                            <tr>
                                                                <th>Product</th>
                                                                <th>Qty</th>
                                                                <th><button type="button"
                                                                        class="btn btn-sm btn-success add-product"><i
                                                                            class="bi bi-plus"></i></button></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="product-rows">
                                                            <tr>
                                                                <td><input type="text"
                                                                        name="attachments[0][products][0][name]"
                                                                        class="form-control" required></td>
                                                                <td><input type="number"
                                                                        name="attachments[0][products][0][qty]"
                                                                        class="form-control" required></td>
                                                                <td><button type="button"
                                                                        class="btn btn-sm btn-danger remove-product"><i
                                                                            class="bi bi-trash"></i></button></td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>

                                            <td>
                                                <input type="file" name="attachments[0][file]" class="form-control mb-2"
                                                    required>

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

                </div>
            </div>

            <div class="card-action">
                <a href="{{ $mode == 'edit' ? route('petty.edit.step3', Hashids::encode($pettyCash->id)) : route('petty.create.step3') }}"
                    class="btn btn-secondary me-2">Back</a>

                <x-primary-button label="{{ $mode == 'edit' ? 'Update' : 'Submit' }}" />

            </div>

        </div>
    </form>

    <script>
        let attachmentIndex = 1;

        // Add new attachment section
        document.getElementById('add_attachment_btn').addEventListener('click', function() {
            const container = document.querySelector('#attachments_container tbody');
            const firstRow = container.querySelector('tr');
            const newRow = firstRow.cloneNode(true);

            // Reset all inputs
            newRow.querySelectorAll('input').forEach(input => {
                input.value = '';

                // Update attachment index
                input.name = input.name.replace(/\[\d+]/, `[${attachmentIndex}]`);

                // Reset product sub-index to 0
                if (input.name.includes('[products]')) {
                    input.name = input.name.replace(/\[products]\[\d+]/g, '[products][0]');
                }
            });

            // Reset product rows to only one row
            const productRows = newRow.querySelector('.product-rows');
            productRows.innerHTML = `
        <tr>
            <td><input type="text" name="attachments[${attachmentIndex}][products][0][name]" class="form-control" required></td>
            <td><input type="number" name="attachments[${attachmentIndex}][products][0][qty]" class="form-control" required></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-product"><i class="bi bi-trash"></i></button></td>
        </tr>
    `;

            container.appendChild(newRow);
            attachmentIndex++;
        });

        // Add/remove product rows inside a specific attachment
        document.addEventListener('click', function(e) {
            // Handle add-product button
            if (e.target.closest('.add-product')) {
                const button = e.target.closest('.add-product');
                const productTable = button.closest('table');
                const productRows = productTable.querySelector('.product-rows');

                // Find attachment index from input name
                const firstInput = productRows.querySelector('input');
                const match = firstInput.name.match(/attachments\[(\d+)]/);
                const attachIndex = match ? match[1] : 0;
                const newIndex = productRows.querySelectorAll('tr').length;

                // Create new row
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td><input type="text" name="attachments[${attachIndex}][products][${newIndex}][name]" class="form-control" required></td>
            <td><input type="number" name="attachments[${attachIndex}][products][${newIndex}][qty]" class="form-control" required></td>
            <td><button type="button" class="btn btn-sm btn-danger remove-product"><i class="bi bi-trash"></i></button></td>
        `;
                productRows.appendChild(newRow);
            }

            // Handle remove-product button
            if (e.target.closest('.remove-product')) {
                const row = e.target.closest('tr');
                row.remove();
            }

            // Handle remove attachment section
            if (e.target.closest('.btn-remove-item')) {
                const row = e.target.closest('tr');
                row.remove();
            }
        });
    </script>
@endsection
