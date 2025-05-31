<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLog;
use App\Models\Item;
use App\Models\Justification;
use App\Models\User;
use Illuminate\Http\Request;

class ItemRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::where('id', auth()->id())->first();
        $item = Item::where('branch_id', $user->branch_id)->orderBy('id', 'desc')->get();
        return view('item-request.initial.index', compact('item'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $justification = Justification::where('status','active')->get();

        return view('item-request.initial.create', compact('justification'));
    }

    public function justify(){
        $justifications = Justification::all();
        return view('item-request.justification', compact('justifications'));
    }

    public function justifyStore(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Justification::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Justification added successfully.');
    }

    public function toggleStatus($id)
{
    $justification = Justification::findOrFail($id);
    $justification->status = $justification->status === 'active' ? 'inactive' : 'active';
    $justification->save();

    return redirect()->back()->with('success', 'Status updated.');
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
            'quantity' => 'required|numeric',
            'price' => 'required|numeric',
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
            'justification_id' => $request->justification_id,
            'branch_id' => $request->branch_id,
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
         $latest = ApprovalLog::where('item_id', $id)->where('user_id', auth()->id())->latest()->first();
        $approval_logs = ApprovalLog::where('item_id', $id)->get();

        return view('item-request.initial.view', compact('item','approval_logs'));
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

    public function request_list()
    {
        $item = Item::orderBy('id', 'desc')->get();;
        return view('item-request.confirm.index', compact('item'));
    }

    public function details($id)
    {
        $item = Item::findOrFail($id);
          $latest = ApprovalLog::where('item_id', $id)->where('user_id', auth()->id())->latest()->first();
        $approval_logs = ApprovalLog::where('item_id', $id)->get();

        return view('item-request.confirm.details', compact('item','approval_logs'));
    }

    public function approve($id)
    {
        // Find the item by ID
        $item = Item::find($id);

        if ($item) {

            ApprovalLog::Create([
                'item_id' => $id,
                'user_id' => auth()->id(),
                'action' => 'approved',
            ]);

            $item->status = 'approved';
            $item->save();

            return redirect()->back()->with('success', 'This Request approved successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Item not found.');
    }

    public function reject(Request $request, $id)
    {
        // Find the item by ID
        $item = Item::find($id);

        if ($item) {

            ApprovalLog::create([
                'item_id' => $id,
                'user_id' => auth()->id(),
                'action' => 'rejected',
                'comment' => $request->comment,
            ]);

            $item->status = 'rejected';
            $item->save();

            // Redirect back with success message
            return redirect()->back()->with('success', 'You rejected this request and feedback sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Item not found.');
    }
}
