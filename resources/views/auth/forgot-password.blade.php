<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container-fluid vh-100">
        <div class="row h-100">
            <div class="col-md-12 d-flex justify-content-center align-items-center">
                <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px; border-radius: 15px;">
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-dark">Forgot Password</h3>
                        <p class="text-muted">
                            No problem! Enter your email address, and we'll send you a link to reset your password.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Email') }}</label>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="form-control @error('email') is-invalid @enderror"
                                placeholder="Enter your email">
                            @error('email')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark btn-block mt-3">Email Password Reset Link</button>
                        <!-- Submit Button -->
                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
