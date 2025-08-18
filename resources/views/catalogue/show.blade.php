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
                <a href="{{ route('catalogues.index') }}">Catalogues</a>
            </li>
            <li class="separator">
                <i class="bi bi-arrow-right"></i>
            </li>
            <li class="nav-item">
                <a href="#">{{ $catalogue->name }}</a>
            </li>

        </ul>
    </div>



    {{-- Success Message --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Message --}}
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>There were some issues:</strong>
            <ul class="mb-0 mt-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="row">

        <!-- Trigger Modal -->
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#uploadFileModal">
            Upload New Catalogue
        </button>


        <div class="col-md-12 col-xl-12">
            <h5 class="mb-3">Catalogue Files</h5>
            <div class="card shadow-none border">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th>Updated By</th>
                                    <th>Date Updated</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tbody>

                                @forelse($catalogue->files as $index => $file)
                                    <tr class="clickable-row" data-href="{{ asset($file->file_path) }}"
                                        style="cursor: pointer;">
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $file->user->name }}</td>
                                        <td>{{ $file->created_at->format('d M Y, H:i') }}</td>
                                        <td>
                                            <span class="d-flex align-items-center gap-2">
                                                <i
                                                    class="fas fa-circle text-{{ $file->status === 'visible' ? 'success' : 'secondary' }} f-10 m-r-5"></i>
                                                {{ ucfirst($file->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">No files yet.</td>
                                    </tr>
                                @endforelse

                            </tbody>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


    </div>


    <!-- Upload Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-labelledby="uploadFileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('catalogue.file.store', $catalogue->id) }}" method="POST"
                enctype="multipart/form-data" class="modal-content">
                @csrf
                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">

                <div class="modal-header">
                    <h5 class="modal-title">Upload Catalogue File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="file" class="form-label">Select PDF File</label>
                    <input type="file" name="file" accept="application/pdf" class="form-control" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.clickable-row').forEach(function(row) {
                row.addEventListener('click', function() {
                    window.open(this.dataset.href, '_blank');
                });
            });
        });
    </script>


</x-app-layout>
