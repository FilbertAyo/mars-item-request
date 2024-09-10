<x-guest-layout>





    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="row justify-content-center w-100">
            <div class="text-center mb-3">
                <h3><strong class="text-success">Admin</strong></h3>

            </div>
    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="phone" :value="__('Phone number')" />
            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autocomplete="phone" />
            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
        </div>

        <div class="mt-4"  style="display: none;">
            <x-input-label for="userType" :value="__('userType')" />
            <x-text-input class="block mt-1 w-full" type="text" name="userType" value="0" />
        </div>

        <div class="mt-4"  style="display: none;">
            <x-input-label for="userType" :value="__('userType')" />
            <x-text-input class="block mt-1 w-full" type="text" name="department" value="System admin" />
        </div>




        <!-- Password -->
        <div class="mt-4">

            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <button type="submit" class="btn btn-block btn-success mt-4">Register</button>



    </form>
        </div>
    </div>

    </div>





    <script>
    function updateSelectedImage(input) {
        const span = document.getElementById('selectedImage');
        if (input.files && input.files[0]) {
            span.textContent = input.files[0].name;
        } else {
            span.textContent = 'Choose an image';
        }
    }
</script>

</x-guest-layout>
