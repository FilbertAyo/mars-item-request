<?php

namespace App\Http\Controllers;

use App\Mail\EmployeeConfirmation;
use App\Mail\FirstApprovalMail;
use App\Mail\LastApprovalMail;
use App\Mail\PettyRequestMail;
use App\Mail\RejectMail;
use App\Mail\SuccessPayment;
use App\Models\ApprovalLog;
use App\Models\Deposit;
use App\Models\Petty;
use App\Models\PettyList;
use App\Models\StartPoint;
use App\Models\Stop;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use NumberToWords\NumberToWords;

class PettyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch requests only for the logged-in user
        $requests = Petty::Where('user_id', auth()->id())->orderBy('created_at', 'desc')
            ->get();

        return view('pettycash.request', compact('requests'));
    }

    public function autocomplete(Request $request)
    {
        $term = $request->get('term');

        $results = Stop::where('destination', 'LIKE', "%{$term}%")
            ->distinct() // Ensures unique destination values
            ->pluck('destination')
            ->take(10);

        return response()->json($results);
    }

    public function create()
    {
        // $pickingPoints = Trip::pluck('from_place', 'id');
        $pickingPoints = StartPoint::all()->where('status', 'active');
        return view('pettycash.create', compact('pickingPoints'));
    }
    public function show($id)
    {
        $request = Petty::findOrFail($id);
        $approval_logs = ApprovalLog::where('petty_id', $id)->get();

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWords = ucfirst($numberTransformer->toWords($request->amount)) . ' Shillings';

    $verifiedBy = ApprovalLog::where('petty_id',$id)->where('action','approved')->first();
        $approvedBy = ApprovalLog::where('petty_id',$id)->where('action','approved')->skip(1)->take(1)->first();


        return view('pettycash.view', compact('request', 'amountInWords','approval_logs','verifiedBy','approvedBy'));
    }


    public function requests_list()
    {
        $requests = Petty::orderBy('created_at', 'desc')->where(
            'department_id',
            auth()->user()->department_id
        )->get();

        return view('pettycash.approval.index', compact('requests'));
    }

    public function request_show($id)
    {
        $request = Petty::findOrFail($id);
        $latest = ApprovalLog::where('petty_id', $id)->where('user_id', auth()->id())->latest()->first();
        $approval_logs = ApprovalLog::where('petty_id', $id)->get();
        $approval = optional($latest)->action;

        $verifiedBy = ApprovalLog::where('petty_id',$id)->where('action','approved')->first();
        $approvedBy = ApprovalLog::where('petty_id',$id)->where('action','approved')->skip(1)->take(1)->first();

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountInWords = ucfirst($numberTransformer->toWords($request->amount)) . ' Shillings';


        return view('pettycash.approval.details', compact('request', 'amountInWords','approval', 'approval_logs','verifiedBy','approvedBy'));
    }

    private function generateUniquePettyCode()
    {
        do {
            $code = strtoupper(Str::random(2)) . rand(100, 999); // e.g. AB123
        } while (Petty::where('code', $code)->exists());

        return $code;
    }
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

        if ($request->department_id === null) {
            return redirect()->back()->with('error', 'You must be a member of department to request Petty cash, Please contact Admin to assign department');
        }

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

        $code = $this->generateUniquePettyCode();

        $newRequest = Petty::create([
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'request_for' => $request->request_for,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'request_type' => $request->request_type,
            'code' => $code,
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

        $users = User::all();

        $name = User::find($request->user_id)->name;
        $reason = $newRequest->name;
        $id = $newRequest->id;
        $email = $users->pluck('email')->toArray();

        // Mail::to($email)->send(new PettyRequestMail($name, $email, $reason, $id));

        return redirect()->back()->with('success', 'Request submitted successfully.');
    }


    public function f_approve($id)
    {
        // Find the request by its ID

        ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => auth()->id(),
            'action' => 'approved',
        ]);

        $request = Petty::findOrFail($id);
        $request->status = 'processing';
        $request->save();

        $users = User::all();
        $name = User::find($request->user_id)->name;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        // Mail::to($email)->send(new FirstApprovalMail($name, $reason, $id));

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Request approved and status updated');
    }

    public function l_approve($id)
    {
        ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => auth()->id(),
            'action' => 'approved',
        ]);

        $request = Petty::findOrFail($id);
        // Change the status to "processing"
        $request->status = 'approved';
        $request->save();

        $users = User::all();
        $name = User::find($request->user_id)->name;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        // Mail::to($email)->send(new LastApprovalMail($name, $reason, $id));

        return redirect()->back()->with('success', 'Request approved and status updated');
    }

    public function c_approve($id)
    {
        $request = Petty::findOrFail($id);

        $latestDeposit = Deposit::latest()->first();

        if (!$latestDeposit) {
            return redirect()->back()->with('error', 'No deposit available.');
        }

        // if ($latestDeposit->remaining < $request->amount) {
        //     return redirect()->back()->with('error', 'Insufficient balance of your petty cash account');
        // }

        $latestDeposit->remaining -= $request->amount;
        $latestDeposit->save();

        // Change the status to "paid" for the request
        $request->status = 'paid';
        $request->save();

        ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => auth()->id(),
            'action' => 'paid',
        ]);

        $users = User::all();
        $name = User::find($request->user_id)->name;
        $user_email = User::find($request->user_id)->email;
        $email = $users->pluck('email')->toArray();
        $reason = $request->name;
        $id = $request->id;

        // Mail::to($email)->send(new SuccessPayment($name, $reason, $id));
        // Mail::to($user_email)->send(new EmployeeConfirmation($name, $reason, $id));

        return redirect()->back()->with('success', 'Payment successful, and the amount has been deducted.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string','action'=>'required|string']);

        ApprovalLog::create([
            'petty_id' => $id,
            'user_id' => auth()->id(),
            'action' => $request->action,
            'comment' => $request->comment,
        ]);

        $petty = Petty::find($id);

        if ($petty) {
            $petty->status = $request->action;
            $petty->save();

            $name = User::find($petty->user_id)->name;
            $user_email = User::find($petty->user_id)->email;
            $reason = $petty->name;
            $id = $petty->id;

            // Mail::to($user_email)->send(new RejectMail($name, $reason, $id));

            // Redirect back with success message
            return redirect()->back()->with('success', 'This request rejected and feedback sent successfully.');
        }

        // If item is not found, redirect with error
        return redirect()->back()->with('error', 'Request not found.');
    }

    /**
     * Display the specified resource.
     */



    public function edit(Request $request, $id)
    {

          $pickingPoints = StartPoint::all()->where('status', 'active');

           $petty = Petty::findOrFail($id);
           $items = PettyList::where('petty_id',$petty->id)->get();

        return view('pettycash.resubmission', compact('petty','pickingPoints','items'));

    }

    /**
     * Update the specified resource in storage.
     */
  public function update(Request $request, Petty $petty)
{
    $request->validate([
        'user_id' => 'required|string',
        'request_for' => 'required|string|max:255',
        'amount' => 'required|numeric|min:0',
        'reason' => 'nullable|string|max:1000',
        'request_type' => 'required|string',
        'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
    ]);

    if ($request->department_id === null) {
        return redirect()->back()->with('error', 'You must be a member of a department to update Petty cash. Please contact Admin.');
    }

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

    if (in_array($request->request_for, ['Sales Delivery', 'Transport'])) {
        $request->validate([
            'from_place' => 'required|string',
            'destinations' => 'required|array|min:1',
            'destinations.*' => 'required|string|max:255',
        ]);
    }

    // Update core fields
    $petty->update([
        'user_id' => $request->user_id,
        'department_id' => $request->department_id,
        'request_for' => $request->request_for,
        'amount' => $request->amount,
        'reason' => $request->reason,
        'request_type' => $request->request_type,
        'status'=>'pending',
    ]);

    // Handle new attachment
    if ($request->hasFile('attachment')) {
        $attachment = $request->file('attachment');
        $attachmentName = time() . '_' . $attachment->getClientOriginalName();
        $attachment->move(public_path('attachment'), $attachmentName);
        $petty->attachment = 'attachment/' . $attachmentName;
        $petty->save();
    }

    // Update Office Supplies
    if ($request->request_for === 'Office Supplies' && $request->has('items')) {
        // Clear old petty list
        $petty->pettyLists()->delete();

        foreach ($request->items as $index => $item) {
            PettyList::create([
                'petty_id' => $petty->id,
                'item_name' => $item,
                'quantity' => $request->quantity[$index] ?? null,
                'price' => $request->price[$index] ?? null,
            ]);
        }
    }

    // Update Transport/Sales Delivery
    elseif (in_array($request->request_for, ['Sales Delivery', 'Transport'])) {
        // Delete old trip and stops
        $trip = $petty->trip;
        if ($trip) {
            $trip->stops()->delete();
            $trip->delete();
        }

        $newTrip = Trip::create([
            'petty_id' => $petty->id,
            'from_place' => $request->from_place,
        ]);

        foreach ($request->destinations as $destination) {
            $newTrip->stops()->create([
                'destination' => $destination,
            ]);
        }
    }

      ApprovalLog::Create([
            'petty_id' =>  $petty->id,
            'user_id' => auth()->id(),
            'action' => 'resubmitted',
        ]);

    return redirect()->back()->with('success', 'Petty Cash request resubmitted successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petty $petty)
    {
        //
    }
}
