<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get the authenticated user
        $user = auth()->user();

        $item = Item::where('branch', $user->branch)->get();

        return view('pages.stagetwo.branch', compact('item'));
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
    public function reject(Request $request, $id)
    {
        // Find the item by ID
        $item = Item::find($id);

        if ($item) {
            // Update the status to 'processing'
            $item->branch_comment = $request->branch_comment;
            $item->status = 'rejected';
            $item->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Feedback sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Item not found.');
    }

    public function approve($id)
    {
        // Find the item by ID
        $item = Item::find($id);

        if ($item) {
            // Update the status to 'processing'
            $item->status = 'processing';
            $item->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Approval sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Item not found.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);

        return view('pages.stagetwo.b_details', compact('item'));
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
}
