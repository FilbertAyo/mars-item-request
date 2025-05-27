<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Left Image: visible only on large screens -->
            <div class="d-none d-lg-block col-lg-6 p-0">
                <div class="h-100 w-100"
                    style="background: url('{{ asset('image/pettpic2.jpg') }}') center center / cover no-repeat;"></div>
            </div>

            <!-- Right Form: always visible, centered on smaller screens -->
            <div class="col-12 col-lg-6 d-flex flex-column justify-content-center align-items-center">


              <div class="card text-center p-5 w-100" style="max-width: 500px;">
                <div class="brand-logo mb-2">
                  <img src="{{ asset('image/marslogo.png') }}" style="height: 50px;">
                </div>
                <h5 class="font-weight-bold">Sign in to continue.</h5>
                  <form method="POST" action="{{ route('login') }}" class="pt-3">
                        @csrf
                  <div class="form-group">
                            <input id="email" class="form-control" type="email" name="email" placeholder="Username"
                                value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="input-group">
                                <input id="password" class="form-control" type="password" placeholder="********" name="password" required
                                    autocomplete="current-password">

                            </div>
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                  <div class=" form group m-2">
                    <button type="submit" class="btn btn-primary mt-3 w-100">SIGN IN</button>
                  </div>
                  <div class="my-2 d-flex justify-content-between align-items-center">
                    <div class="form-check">
                      <label class="form-check-label text-muted">
                        <input type="checkbox" class="form-check-input"> Keep me signed in </label>
                    </div>
                    @if (Route::has('password.request'))
                                <a class="text-sm text-danger hover:text-gray-900"
                                    href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                  </div>

                  <div class="text-center mt-4 font-weight-light"> Don't have an account? <a href="https://wa.me/255741400900" target="_blank" class="text-primary">Contact Admin</a>
                  </div>
                </form>
              </div>

            </div>

            </div>

        </div>


</x-guest-layout>
