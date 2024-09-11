<x-app-layout>





    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid p-0">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3">Requests<a class="badge bg-primary text-white text-sm ms-2">
                            {{ Auth::user()->department }}
                        </a></h1> <button type="button" class="btn btn-dark" data-bs-toggle="modal"
                        data-bs-target="#exampleModal">
                        Add Request
                    </button>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">

                                <h5 class="card-title mb-0">Department item purchase requests</h5>
                            </div>
                            <table class="table table-hover my-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name of item</th>
                                        <th>Category</th>
                                        <th>Quantity</th>
                                        <th>Expected price</th>
                                        <th>Total amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($item->count() > 0)
                                        @foreach ($item as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->name }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->category }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->quantity }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->price }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $item->amount }}</td>
                                                <td>
                                                    {{-- <form action="{{ route('visitor.destroy', $visitor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this visitor?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                    </form> --}}
                                                    <span class="badge bg-danger">{{ $item->status }}</span>
                                                    <a href="{{ route('department.show', $item->id) }}"
                                                        class="badge bg-primary text-white">view</a>


                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">You have no request yet</td>
                                        </tr>
                                    @endif

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </main>


    </div>


    <script src="js/app.js"></script>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('department.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="itemName" class="form-label">Item name</label>
                            <input type="text" class="form-control" id="itemName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="day" class="form-label">Department</label>
                            <select class="form-select" id="day" name="department">
                                <option value="" selected>Select category</option>
                                <option value="Dep1">category 1</option>
                                <option value="Dep2">category 2</option>

                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Expected price at each</label>
                            <input type="number" step="0.01" class="form-control" id="price" name="price"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Reason</label>
                            <textarea class="form-control" id="description" name="reason" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-success">Save</button>
                    </form>

                </div>

            </div>
        </div>
    </div>


</x-app-layout>
