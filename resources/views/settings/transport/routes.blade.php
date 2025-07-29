<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Map</h3>
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
                <a href="#">Routes</a>
            </li>

        </ul>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-none border">
                <div class="card-body">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d507136.3907891347!2d38.924628115660624!3d-6.76948018622701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4bae169bd6f1%3A0x940f6b26a086a1dd!2sDar%20es%20Salaam!5e0!3m2!1sen!2stz!4v1748330065301!5m2!1sen!2stz"
                        width="600" height="250" style="border: 0; width: 100%" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-none border">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4>Collection Points</h4>

                    @can('update other settings')
                        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                            data-bs-target="#exampleModal">
                            <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New Point
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-dark permission-alert">
                           <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New Point
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Pick Point</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Pick Point</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($pickingPoints as $index => $pickingPoint)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $pickingPoint->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $pickingPoint->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $pickingPoint->status }}
                                            </span>
                                        </td>
                                        <td>
                                            @can('update other settings')
                                                <form action="{{ route('picking-point.toggle', $pickingPoint->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm">
                                                        <i
                                                            class="bi fs-2 {{ $pickingPoint->status === 'active' ? 'bi-toggle-on text-success' : 'bi-toggle-off text-danger' }}"></i>
                                                    </button>
                                                </form>
                                            @else
                                                <div class="d-flex" style="gap: 3px;">
                                                    <button type="button" class="btn btn-sm  permission-alert">
                                                        <i
                                                            class="bi fs-2 {{ $pickingPoint->status === 'active' ? 'bi-toggle-on text-success' : 'bi-toggle-off text-danger' }}"></i>
                                                    </button>
                                                </div>
                                            @endcan

                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-none border">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4>Transport Mode</h4>

                    @can('update other settings')
                        <button type="button" class="btn btn-sm btn-dark" data-bs-toggle="modal"
                            data-bs-target="#exampleModal2">
                            <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New
                        </button>
                    @else
                        <button type="button" class="btn btn-sm btn-dark permission-alert">
                           <span class="btn-label">
                                <i class="bi bi-plus-lg"></i>
                            </span>
                            New
                        </button>
                    @endcan
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Vehicle</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Pick Point</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($trans_mode as $index => $mode)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $mode->name }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $mode->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $mode->status }}
                                            </span>
                                        </td>
                                        <td>

                                            @can('update other settings')
                                                <form action="{{ route('trans.toggle', $mode->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm">
                                                        <i
                                                            class="bi fs-2 {{ $mode->status === 'active' ? 'bi-toggle-on text-success' : 'bi-toggle-off text-danger' }}"></i>

                                                    </button>
                                                </form>
                                            @else
                                                <div class="d-flex" style="gap: 3px;">
                                                    <button type="button" class="btn btn-sm  permission-alert">
                                                        <i
                                                            class="bi fs-2 {{ $mode->status === 'active' ? 'bi-toggle-on text-success' : 'bi-toggle-off text-danger' }}"></i>

                                                    </button>
                                                </div>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Collection Point</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('start.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="topic" class="form-label">Pick Point</label>
                            <input type="text" class="form-control" id="topic" name="name" required>
                        </div>
                        <x-primary-button label="Save" />
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">New Transport Mode</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('transport.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="topic" class="form-label">Name</label>
                            <input type="text" class="form-control" id="topic" name="name" required>
                        </div>
                        <x-primary-button label="Save" />
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
