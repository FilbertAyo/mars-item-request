<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Pharmacy;
use App\Models\UnverifiedPharmacy;
use App\Otp\UserRegistrationOtp;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Notification;
use SadiqSalau\LaravelOtp\Facades\Otp;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'userType' => $request->userType,
            'department'=>$request->department,
            'phone'=>$request->phone,
            'branch'=>'Samora Branch - Main',
            'status'=>'active',
            'file'=> null, //Default value if no file is uploaded
        ]);


        // $otp = Otp::identifier($request->email)->send(
        //     Notification::route('mail', $request->email)->notify(
        //         new UserRegistrationOtp(
        //             name: $request->name,
        //             email: $request->email,
        //             password: $request->password
        //         )
        //     )
        // );
        // return redirect(__($otp['status']));

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }


}
