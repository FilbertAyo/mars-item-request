<section>


    <div class="col-md-12 mt-3">
        <div class="card card-profile">
            <div class="card-header" style="background-image: url('{{ asset('assets/img/blogpost.jpg') }}')">
                <div class="profile-picture">
                    <a type="button" class="avatar avatar-xxl" data-bs-toggle="modal" data-bs-target="#exampleModal" style="margin: 0;">
                        <img src="{{ asset(Auth::user()->file ?? 'image/prof.jpeg') }}" alt="..."
                            class="avatar-img rounded-circle" />
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="user-profile text-center">
                    <div class="name">{{ $user->name }}</div>
                    <div class="job">{{ $user->email }} | {{ $user->phone }}</div>



                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Upload Profile Image</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('profile.image') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <div class="mb-3">
                                            <input type="file" name="profile_image" class="form-control mb-3"
                                                required>
                                            <x-primary-button label="Upload" class="w-100" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card-footer">
                <div class="row user-stats text-center">
                    <div class="col">
                        <div class="number">{{ $user->branch->name ?? 'N/A' }}</div>
                        <div class="title">Branch</div>
                    </div>
                    <div class="col">
                        <div class="number">{{ $user->department->name ?? 'N/A' }}</div>
                        <div class="title">Department</div>
                    </div>

                </div>
            </div>
        </div>
    </div>



</section>
