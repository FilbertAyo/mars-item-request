<x-app-layout>

    <div class="page-header">
        <h3 class="fw-bold mb-3">Justifications</h3>
        <ul class="breadcrumbs mb-3">
            <li class="nav-home">
                <a href="{{ route('dashboard') }}">
                    <i class="bi bi-house-fill"></i>
                </a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-justify">
                <a href="#">Justifications</a>
            </li>

        </ul>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="card">

                <div class="card-header mb-1" style="display: flex;justify-content: space-between;">
                    <h4 class="h3 mb-3"> Justifications List</h4>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select" class="display table table-striped table-hover">
                            <thead>
                                <tr>

                                    <th>No.</th>
                                    <th>Justification</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No.</th>
                                    <th>Justification</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($justifications as $index => $justify)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $justify->name }}</td>

                                        <td>
                                            <a href="{{ route('justify.toggleStatus', $justify->id) }}">
                                                <i
                                                    class="bi fs-3 {{ $justify->status === 'active' ? 'bi-toggle-on text-success': 'bi-toggle-off text-danger'  }}"></i>

                                            </a>
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



</x-app-layout>
