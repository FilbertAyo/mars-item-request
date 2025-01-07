<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-fluid vh-100">
        <div class="row h-100">
            <!-- Login Form Wrapper -->
            <div class="col-12 d-flex justify-content-center align-items-center">


                <!-- Form Card -->
                <div class="bg-light p-5 rounded shadow col-12 col-sm-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="mb-3" style="height: 80px;">
                        <img src="{{ asset('image/marslogo.png') }}" alt="Mars Logo" style="height: 100%; width: auto; object-fit: contain;">
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="form-group">
                            <label for="email">{{ __('Email') }}</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group mt-4">
                            <label for="password">{{ __('Password') }}</label>
                            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="form-group mt-4 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label for="remember_me" class="form-check-label">{{ __('Remember me') }}</label>
                        </div>

                        <!-- Login Button -->
                        <button type="submit" class="btn btn-dark btn-block mt-3">Login</button>

                        <!-- Forgot Password -->
                        <div class="mt-4 text-center">
                            @if (Route::has('password.request'))
                                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <!-- End Form Card -->
            </div>
        </div>
    </div>
</x-guest-layout>
