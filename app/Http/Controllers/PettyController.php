<?php

namespace App\Http\Controllers;

use App\Models\Petty;
use App\Models\PettyList;
use Illuminate\Http\Request;

class PettyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = Petty::with('pettyLists')->get();

        return view('pettycash.all_request', compact('requests'));

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
        // Save the main request data
        $newRequest = Petty::create([
            'name' => $request->name,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'request_type' => $request->request_type,
            'request_by' => $request->request_by,
            'status' => $request->status,
            'attachment' => $request->file('attachment') ? $request->file('attachment')->store('receipts') : null,
        ]);

        // Save each office item if the request type is petty_cash
        if ($request->request_type === 'petty_cash' && $request->has('items')) {
            foreach ($request->items as $item) {
                PettyList::create([
                    'petty_id' => $newRequest->id,
                    'item_name' => $item,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Request saved successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = Petty::with('pettyLists')->findOrFail($id);

        return view('pettycash.all_details',compact('request'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Petty $petty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Petty $petty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petty $petty)
    {
        //
    }
}
