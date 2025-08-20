<x-app-layout>
    <div class="page-header">
        <h3 class="fw-bold mb-3">Catalogues List</h3>
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
                <a href="#">Catalogues</a>
            </li>

        </ul>
    </div>
    <div class="row">
        <div class="col-md-12">
            <!-- Form Section -->
            <div class="card shadow-none border mb-4">
                <div class="card-body">
                    <form action="{{ route('catalogues.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <div class="mb-3">
                                <label for="name" class="form-label">Catalogue Name</label>
                                <input type="text" name="name" class="form-control" required
                                    placeholder="e.g. Samsung">
                            </div>
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo (Image)</label>
                                <input type="file" name="logo" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cards Section -->
            <div class="row">
                @foreach ($catalogues as $catalogue)
                    <div class="col-md-6 col-xl-3">
                        <div class="card position-relative h-100 shadow-none border">
                            <div class="card-body text-center">
                                <h4 class="mb-3">{{ $catalogue->name }}</h4>

                                @if ($catalogue->logo)
                                    <img src="{{ asset($catalogue->logo) }}"
                                         alt="{{ $catalogue->name }}"
                                         class="img-fluid mx-auto d-block"
                                         style="height: 150px; object-fit: contain;">
                                @else
                                    <p>No Logo</p>
                                @endif

                                <!-- Action buttons -->
                                <div class="d-flex justify-content-center gap-2 mt-3">
                                    <!-- View button -->
                                    <a href="{{ route('catalogues.show', $catalogue->id) }}"
                                       class="btn btn-primary btn-sm">
                                        View
                                    </a>

                                    <!-- Delete button -->
                                    <form action="{{ route('catalogues.destroy', $catalogue->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this catalogue?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>


        </div>
    </div>
</x-app-layout>
