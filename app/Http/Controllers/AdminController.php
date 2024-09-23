<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Detail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $branches = Detail::all();
        $user = User::all();

        return view('dashboard', compact('user','branches'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->phone),
            'userType' => $request->userType,
            'department'=>$request->department,
            'phone'=>$request->phone,
            'branch'=>$request->branch,
            'status'=>'active',
            'file'=> null, //Default value if no file is uploaded
        ]);

        event(new Registered($user));

        // Auth::login($user);

        return redirect()->back()->with('success', 'New user registered successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return view('details',compact('user'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function branch_list()
    {
        $branch = Detail::all();

        return view('pages.admin.branch_list',compact('branch'));
    }

    public function branch_store(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Add validation rules for the fields you need

        ]);

        Detail::create($validatedData);

        return redirect()->back()->with('success', 'New branch added successfully');
    }

    public function destroy_branch(string $id)
    {
        // Find the branch by ID
        $branch = Detail::find($id);

        if ($branch) {
            $branch->delete();

            return redirect()->back()->with('success', 'Branch deleted successfully');
        } else {
            // Redirect back with error message if branch not found
            return redirect()->back()->with('error', 'Branch not found');
        }
    }


}
