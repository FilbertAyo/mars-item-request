<?php

namespace App\Http\Controllers;

use App\Mail\EmployeeConfirmation;
use App\Mail\FirstApprovalMail;
use App\Mail\LastApprovalMail;
use App\Mail\PettyRequestMail;
use App\Mail\RejectMail;
use App\Mail\SuccessPayment;
use App\Models\Deposit;
use App\Models\Petty;
use App\Models\PettyList;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PettyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch requests only for the logged-in user
        $requests = Petty::Where('user_id', auth()->id())
            ->get();

        return view('pettycash.request', compact('requests'));
    }

    public function create()
    {
        return view('pettycash.create');
    }
    public function show($id)
    {
        $request = Petty::findOrFail($id);

        return view('pettycash.view', compact('request'));
    }


    public function requests_list()
    {
        $requests = Petty::all();

        return view('pettycash.approval.index', compact('requests'));
    }

    public function request_show($id)
    {
        $request = Petty::findOrFail($id);

        return view('pettycash.approval.details', compact('request'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'user_id' => 'required|string',
            'request_for' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
            'request_type' => 'required|String',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        if ($request->request_for === 'Office Supplies') {
            $request->validate([
                'items' => 'required|array|min:1',
                'items.*' => 'required|string|max:255',
                'quantity' => 'required|array',
                'quantity.*' => 'nullable|numeric|min:0',
                'price' => 'required|array',
                'price.*' => 'nullable|numeric|min:0',
            ]);
        }

        if ($request->request_for === 'Sales Delivery' || $request->request_for === 'Transport') {
            $request->validate([
                'from_place' => 'required|string',
                'destinations' => 'required|array|min:1',
                'destinations.*' => 'required|string|max:255',
            ]);
        }

        $newRequest = Petty::create([
            'user_id' => $request->user_id,
            'request_for' => $request->request_for,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'request_type' => $request->request_type,
        ]);

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('attachment'), $attachmentName);
            $newRequest->attachment = 'attachment/' . $attachmentName;
            $newRequest->save();
        }

        if ($request->request_for === 'Office Supplies' && $request->has('items')) {
            foreach ($request->items as $index => $item) {
                PettyList::create([
                    'petty_id' => $newRequest->id,
                    'item_name' => $item,
                    'quantity' => $request->quantity[$index] ?? null,
                    'price' => $request->price[$index] ?? null,
                ]);
            }
        } elseif ($request->request_for === 'Sales Delivery' || $request->request_for === 'Transport') {
            $transportRequest = Trip::create([
                'petty_id' => $newRequest->id,
                'from_place' => $request->from_place,
            ]);

            foreach ($request->destinations as $destination) {
                $transportRequest->stops()->create([
                    'destination' => $destination,
                ]);
            }
        }

        $users = User::where('userType', 5)->get();

        $name = User::find($request->user_id)->name;
        $reason = $newRequest->name;
        $id = $newRequest->id;
        $email = $users->pluck('email')->toArray();

        // Mail::to($email)->send(new PettyRequestMail($name, $email, $reason, $id));

        return redirect()->back()->with('success', 'Request saved successfully.');
    }

    public function f_approve($id)
    {
        // Find the request by its ID
        $request = Petty::findOrFail($id);

        // Change the status to "processing"
        $request->status = 'processing';
        $request->save();

        $users = User::where('userType', 3)->get();
        $name = User::find($request->user_id)->name;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        Mail::to($email)->send(new FirstApprovalMail($name, $reason, $id));

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

        $users = User::where('userType', 6)->get();
        $name = User::find($request->user_id)->name;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        Mail::to($email)->send(new LastApprovalMail($name, $reason, $id));

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

        $users = User::whereIn('userType', [3, 5, 6])->get();
        $name = User::find($request->user_id)->name;
        $user_email = User::find($request->user_id)->email;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        Mail::to($email)->send(new SuccessPayment($name, $reason, $id));
        Mail::to($user_email)->send(new EmployeeConfirmation($name, $reason, $id));

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

            $name = User::find($req->user_id)->name;
            $user_email = User::find($req->user_id)->email;
            $reason = $req->name;
            $id = $req->id;

            Mail::to($user_email)->send(new RejectMail($name, $reason, $id));

            // Redirect back with success message
            return redirect()->back()->with('success', 'Feedback sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Request not found.');
    }

    /**
     * Display the specified resource.
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
