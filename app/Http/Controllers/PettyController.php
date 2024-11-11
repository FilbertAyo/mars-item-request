<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
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
        // Fetch requests only for the logged-in user
        $requests = Petty::with('pettyLists')
            ->where('user_id', auth()->id())
            ->get();

        return view('pettycash.all_request', compact('requests'));
    }

    public function first_approval()
    {
        $requests = Petty::with('pettyLists')->get();

        return view('pettycash.first_approval', compact('requests'));
    }
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Save the main request data (without attachment initially)
        $newRequest = Petty::create([
            'user_id' => $request->user_id,
            'name' => $request->name,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'request_type' => $request->request_type,
            'request_by' => $request->request_by,
            'status' => $request->status,
        ]);

        // Handle the attachment file
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('attachment'), $attachmentName);

            // Update the attachment field in the database
            $newRequest->attachment = 'attachment/' . $attachmentName;
            $newRequest->save(); // Save the updated model
        }

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

    public function f_approve($id)
{
    // Find the request by its ID
    $request = Petty::findOrFail($id);

    // Change the status to "processing"
    $request->status = 'processing';
    $request->save();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Request approved and status updated');
}

public function l_approve($id)
{
    // Find the request by its ID
    $request = Petty::findOrFail($id);
    // Change the status to "processing"
    $request->status = 'approved';
    $request->save();
    // Redirect back with a success message
    return redirect()->back()->with('success', 'Request approved and status updated');
}

public function c_approve($id)
{
    // Find the request by its ID
    $request = Petty::findOrFail($id);

    // Get the latest remaining amount from the Deposit table
    $latestDeposit = Deposit::latest()->first();


    if (!$latestDeposit) {
        return redirect()->back()->with('error', 'No deposit available for this request.');
    }

    if ($latestDeposit->remaining < $request->amount) {
        return redirect()->back()->with('error', 'Insufficient balance of your petty cash account');
    }

    $latestDeposit->remaining -= $request->amount;
    $latestDeposit->save();

    // Change the status to "paid" for the request
    $request->status = 'paid';
    $request->save();

    return redirect()->back()->with('success', 'Payment successful, and the amount has been deducted.');
}


public function reject(Request $request, $id)
{
    // Find the item by ID
    $req = Petty::find($id);

    if ($req) {
        // Update the status to 'processing'
        $req->comment = $request->comment;
        $req->status = 'rejected';
        $req->save();

        // Redirect back with success message
        return redirect()->back()->with('success', 'Feedback sent successfully.');
    }

    // If item is not found, redirect with error
    return redirect()->back()->with('error', 'Request not found.');
}

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $request = Petty::with('pettyLists')->findOrFail($id);

        return view('pettycash.all_details',compact('request'));
    }

    public function first_show($id)
    {
        $request = Petty::with('pettyLists')->findOrFail($id);

        return view('pettycash.first_details',compact('request'));
    }


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
