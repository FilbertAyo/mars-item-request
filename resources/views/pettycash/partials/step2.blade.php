@extends('pettycash.create')

@section('content')
    <form method="POST" action="{{ route('petty.store.step2') }}" enctype="multipart/form-data" id="pettyForm">
        @csrf
        <div class="card">
            <div class="card-header">
                <div class="card-title"><i class="bi bi-clipboard me-2"></i>Office Materials</div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-12 mt-3" id="office_expense_section">
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
                                        @php
                                            $items = old('items', session('step2.items', ['']));
                                            $quantities = old('quantity', session('step2.quantity', ['']));
                                            $prices = old('price', session('step2.price', ['']));
                                        @endphp

                                        @foreach ($items as $index => $item)
                                            <tr class="mb-2">
                                                <td>
                                                    <input type="text" name="items[]" class="form-control"
                                                        placeholder="eg. Rims paper"
                                                        value="{{ old("items.$index", $item) }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="quantity[]" class="form-control"
                                                        placeholder="eg. 10"
                                                        value="{{ old("quantity.$index", $quantities[$index] ?? '') }}">
                                                </td>
                                                <td>
                                                    <input type="number" name="price[]" class="form-control"
                                                        placeholder="eg. 1000"
                                                        value="{{ old("price.$index", $prices[$index] ?? '') }}">
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

                </div>
            </div>

            <div class="card-action">
                <a href="{{ route('petty.create.step1') }}" class="btn btn-secondary me-2">Back</a>
                <x-primary-button label="Submit" />

            </div>

        </div>
    </form>

    <script>
        const addItemBtn = document.getElementById('add_item_btn');
        const itemsContainer = document.getElementById('items_container');

        addItemBtn.addEventListener('click', function() {
            const tr = document.createElement('tr');
            tr.classList.add('mb-2');
            tr.innerHTML = `
                <td><input type="text" name="items[]" class="form-control" required></td>
                <td><input type="number" name="quantity[]" class="form-control" required></td>
                <td><input type="number" name="price[]" class="form-control" required></td>
                <td><button type="button" class="btn btn-danger btn-remove-item"><i class="bi bi-trash"></i></button></td>
            `;
            itemsContainer.querySelector('tbody').appendChild(tr);
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-remove-item')) {
                e.target.closest('tr').remove();
            }
        });
    </script>
@endsection
