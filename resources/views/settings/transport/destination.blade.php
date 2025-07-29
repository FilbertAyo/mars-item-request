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
                <a href="#">Destinations</a>
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


        <div class="col-md-12">
            <div class="card shadow-none border">

                <div class="card-header" style="display: flex;justify-content: space-between;">
                    <h4>Destination Points</h4>

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="multi-filter-select2" class="display table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Destination</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>No</th>
                                    <th>Destination</th>
                                </tr>
                            </tfoot>
                            <tbody>

                                @foreach ($stops as $index => $stop)
                                    <tr>
                                        <td>{{ $stops->firstItem() + $index }}</td>
                                        <td>{{ $stop->destination }}</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-3">
                            {{ $stops->links('pagination::bootstrap-5') }}

                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>




</x-app-layout>
