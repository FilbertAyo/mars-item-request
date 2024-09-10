<x-app-layout>





    <div class="main">
        @include('layouts.navigation')

        <main class="content">
            <div class="container-fluid p-0">

                <div class="mb-1" style="display: flex;justify-content: space-between;">
                    <h1 class="h3 mb-3">Visitor</h1> <button type="button" class="btn btn-success"
                        data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Add visitor
                    </button>
                </div>

                <div class="row">
                    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
                        <div class="card flex-fill">
                            <div class="card-header">

                                <h5 class="card-title mb-0">Visitor</h5>
                            </div>
                            <table class="table table-hover my-0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($visitor->count() > 0)
                                        @foreach ($visitor as $index => $visitor)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $visitor->vs_name }}</td>
                                                <td class="d-none d-xl-table-cell">{{ $visitor->email }}</td>
                                                <td>
                                                    <form action="{{ route('visitor.destroy', $visitor->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this visitor?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="bi bi-trash"></i> Delete
                                                            </button>
                                                    </form>

                                                    </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center">No Exhibitor found</td>
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
                    <h5 class="modal-title" id="exampleModalLabel">New Visitor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('visitor.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Visitor name</label>
                            <input type="text" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" name="vs_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Visitor email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1"
                                aria-describedby="emailHelp" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Description</label>
                            <input type="text" class="form-control"name="description" required>
                        </div>

                        <button type="submit" class="btn btn-success">Save</button>
                    </form>
                </div>

            </div>
        </div>
    </div>


</x-app-layout>
