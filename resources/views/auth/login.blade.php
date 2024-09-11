<x-guest-layout>

    <style>
        /* Background animation */
        .animated-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('/path/to/background.jpg'); /* Replace with your background image */
            background-size: cover;
            background-position: center;
            filter: brightness(50%);
            z-index: 1;
            animation: backgroundAnimation 10s infinite alternate ease-in-out;
        }

        /* Animation for background image */
        @keyframes backgroundAnimation {
            0% {
                transform: scale(1);
                opacity: 1;
            }
            100% {
                transform: scale(1.1);
                opacity: 0.9;
            }
        }

        /* Fade-in animation for text */
        .fade-in {
            opacity: 0;
            animation: fadeIn 2s forwards;
        }

        .delay-1 {
            animation-delay: 1s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-fluid vh-100">
        <div class="row h-100">

            <!-- Left Color Wall -->
            <div class="col-md-6 bg-dark d-flex justify-content-center align-items-center position-relative">
                <!-- Background Animation -->
                <div class="animated-bg"></div>

                <!-- Content with Overlay -->
                <div class="text-white text-center position-relative" style="z-index: 2;">
                    <h2 class="fade-in">Welcome Again</h2>
                    <div class="text-center mb-4 justify-content-center">
                        <img src="/image/longlogo.png" alt="Logo" style="width: 400px;">
                    </div>
                    {{-- <p class="fade-in delay-1">Mars communication management system</p> --}}
                </div>
            </div>

            <!-- Right Login Form -->
            <div class="col-md-6 d-flex justify-content-center align-items-center">
                <div class="col-md-8 col-lg-6">

                    
                

                    <!-- Form Wrapper with Background and Padding -->
                    <div class="bg-light p-4 rounded shadow">
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
                    <!-- End Form Wrapper -->
                </div>
            </div>


        </div>
    </div>

</x-guest-layout>
