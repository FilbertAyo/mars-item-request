<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Justification;
use Illuminate\Http\Request;

class ItemRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
          $item = Item::where('user_id', auth()->id())->get();

        $justification = Justification::all();

        return view('item-request.initial.index', compact('item','justification'));
    }

   /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function justify(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'justification' => 'required|string|max:255',

        ]);

        Justification::create([
            'justification' => $request->justification,
        ]);

        return redirect()->back()->with('success', 'Justification added successfully.');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'reason' => 'required|string',
            'quantity' => 'required|numeric',  // Add validation for quantity
            'price' => 'required|numeric',     // Add validation for price
        ]);

        // Calculate the amount
        $amount = $request->quantity * $request->price;

        // Determine the p_type based on the amount
        $p_type = $amount < 1000000 ? 'Minor' : 'Major';

        // Store the data in the database
        Item::create([
            'name' => $request->name,
            'quantity' => $request->quantity,
            'price' => $request->price,
            'amount' => $amount,
            'p_type' => $p_type,  // Set p_type here
            'reason' => $request->reason,
            'justification' => $request->justification,
            'branch' => $request->branch,
            'status' => 'pending',
            'user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Request sent successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Item::findOrFail($id);

        return view('pages.stageone.dep_details',compact('item'));
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
