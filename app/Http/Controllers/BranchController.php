<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Item;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $branches = Branch::withCount('departments')->get();

        return view('settings.branches.index', compact('branches'));
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
           // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Add validation rules for the fields you need

        ]);

        Branch::create($validatedData);

        return redirect()->back()->with('success', 'New branch added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $branch = Branch::findOrFail($id);
        $departments = $branch->departments; // Assuming you have a relationship defined in the Branch model

        return view('settings.branches.view', compact('branch','departments'));
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
            // Find the branch by ID
        $branch = Branch::find($id);

        if ($branch) {
            $branch->delete();

            return redirect()->back()->with('success', 'Branch deleted successfully');
        } else {
            // Redirect back with error message if branch not found
            return redirect()->back()->with('error', 'Branch not found');
        }
    }
}
