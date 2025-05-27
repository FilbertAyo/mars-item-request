<?php

namespace App\Http\Controllers;

use App\Mail\NotificationMail;
use App\Models\Agenda;
use App\Models\Branch;
use App\Models\Department;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::all();
    
        return view('settings.users.index', compact('user'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $branches = Branch::all();
        $departments = Department::all();

        return view('settings.users.create',compact('branches','departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'phone' => ['required', 'regex:/^0[76][0-9]{8}$/'],
            'userType' => ['required', 'string'],
        ]);

        $randomNo = Str::random(6);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($randomNo),
            'userType' => $request->userType,
            'department_id' => $request->department_id,
            'phone' => $request->phone,
            'branch_id' => $request->branch_id,
            'status' => 'active',
            'file' => null, //Default value if no file is uploaded
        ]);

        event(new Registered($user));
        // Send notification email
        $name = $user->name;
        $email = $user->email;
        $password = $randomNo;

        // Mail::to($email)->send(new NotificationMail($name, $email, $password));

        return redirect()->back()->with('success', 'New user registered successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('settings.users.view', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
{
    $user = User::find($id);

    if ($user) {
        // Update the user's status to 'inactive'
        $user->status = 'inactive';
        $user->save();

        return redirect()->route('admin.index')->with('success', 'User status updated to inactive successfully');
    } else {
        // Redirect back with error message if user not found
        return redirect()->route('admin.index')->with('error', 'User not found');
    }
}

public function activate(string $id)
{
    $user = User::find($id);

    if ($user) {
        // Update the user's status to 'inactive'
        $user->status = 'active';
        $user->save();

        return redirect()->route('admin.index')->with('success', 'User status updated to active successfully');
    } else {
        // Redirect back with error message if user not found
        return redirect()->route('admin.index')->with('error', 'User not found');
    }
}


}
