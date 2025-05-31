<section>


     <div class="col-md-12 mt-3">
                <div class="card card-profile">
                  <div
                    class="card-header"
                    style="background-image: url('assets/img/blogpost.jpg')"
                  >
                    <div class="profile-picture">
                      <div class="avatar avatar-xl">
                        <img
                          src="{{ asset('image/prof.jpeg') }}"
                          alt="..."
                          class="avatar-img rounded-circle"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="card-body">
                    <div class="user-profile text-center">
                      <div class="name">{{ $user->name }}</div>
                      <div class="job">{{ $user->email }} | {{ $user->phone }}</div>
                      {{-- <div class="desc">Admin</div> --}}
                    </div>
                  </div>
                  <div class="card-footer">
                    <div class="row user-stats text-center">
                      <div class="col">
                        <div class="number">{{ $user->branch->name }}</div>
                        <div class="title">Branch</div>
                      </div>
                      <div class="col">
                        <div class="number">{{ $user->department->name }}</div>
                        <div class="title">Department</div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>



</section>
