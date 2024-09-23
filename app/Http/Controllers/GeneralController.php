<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class GeneralController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $item = Item::whereIn('status', ['processing', 'approved', 'rejected'])
            ->where('branch_comment', 'no comment')
            ->get();

        return view('pages.stagethree.general', compact('item'));
    }


    public function record()
    {
        $item = Item::whereIn('status', ['approved'])->get();
    
        // Calculate the sum of the 'amount' column
        $totalAmount = Item::whereIn('status', ['approved'])->sum('amount');
    
        // Pass both the item and the total amount to the view
        return view('pages.stagethree.record', compact('item', 'totalAmount'));
    }
    

    public function reject(Request $request, $id)
    {
        // Find the item by ID
        $item = Item::find($id);

        if ($item) {
            // Update the status to 'processing'
            $item->gm_comment = $request->gm_comment;
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
            $item->status = 'approved';
            $item->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'Approval sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Item not found.');
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);

        return view('pages.stagethree.g_details', compact('item'));
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
