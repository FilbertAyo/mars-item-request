<?php

namespace App\Http\Controllers;

use App\Mail\EmployeeConfirmation;
use App\Mail\FirstApprovalMail;
use App\Mail\LastApprovalMail;
use App\Mail\PettyRequestMail;
use App\Mail\RejectMail;
use App\Mail\ResubmissionMail;
use App\Mail\ResubmitMail;
use App\Mail\SuccessPayment;
use App\Models\ApprovalLog;
use App\Models\Deposit;
use App\Models\Petty;
use App\Models\PettyAttachment;
use App\Models\PettyList;
use App\Models\StartPoint;
use App\Models\Stop;
use App\Models\TransMode;
use App\Models\Trip;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Support\Facades\Auth;


class PettyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //This is very important but later should be moved to Console/Command for automatic update
        DB::table('petties')
            ->where('status', 'resubmission')
            ->where('updated_at', '<', now()->subDay())
            ->update(['status' => 'rejected']);

        Log::info('Dashboard-triggered auto-reject of stale petty cash requests.');
        //End of it

        // Fetch requests only for the logged-in user
        $requests = Petty::Where('user_id',  Auth::id())->orderBy('created_at', 'desc')
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
        $pickingPoints = StartPoint::all()->where('status', 'active');
        $trans = TransMode::all()->where('status', 'active');

        return view('pettycash.create', compact('pickingPoints', 'trans'));
    }
    public function show($hashid)
    {
        $id = Hashids::decode($hashid);

        $request = Petty::findOrFail($id[0]);
        $approval_logs = ApprovalLog::where('petty_id', $id[0])->get();

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountWords = $numberTransformer->toWords($request->amount);
        $amountWords = ucwords($amountWords);
        $amountInWords = 'TZS ' . $amountWords;

        $verifiedBy = ApprovalLog::where('petty_id', $id[0])->where('action', 'approved')->first();
        $approvedBy = ApprovalLog::where('petty_id', $id[0])->where('action', 'approved')->skip(1)->take(1)->first();
        $gm = User::where('email', 'gm@marstanzania.com')->first();

        return view('pettycash.view', compact('request', 'amountInWords', 'approval_logs', 'verifiedBy', 'approvedBy', 'gm'));
    }


    public function requests_list()
    {
        $requests = Petty::orderBy('created_at', 'desc')->where(
            'department_id',
            operator: Auth::user()->department_id
        )->get();

        return view('pettycash.approval.index', compact('requests'));
    }

    public function all_requests()
    {
        $requests = Petty::orderBy('created_at', 'desc')->get();

        return view('pettycash.approval.index', compact('requests'));
    }

    public function request_show($hashid)
    {
        $id = Hashids::decode($hashid);

        $request = Petty::findOrFail($id[0]);
        $latest = ApprovalLog::where('petty_id', $id[0])->where('user_id', Auth::user()->id)->latest()->first();
        $approval_logs = ApprovalLog::where('petty_id', $id[0])->get();
        $approval = optional($latest)->action;

        $verifiedBy = ApprovalLog::where('petty_id', $id[0])->where('action', 'approved')->first();
        $approvedBy = ApprovalLog::where('petty_id', $id[0])->where('action', 'approved')->skip(1)->take(1)->first();


        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountWords = $numberTransformer->toWords($request->amount);
        $amountWords = ucwords($amountWords);
        $amountInWords = 'TZS ' . $amountWords;


        return view('pettycash.approval.details', compact('request', 'amountInWords', 'approval', 'approval_logs', 'verifiedBy', 'approvedBy'));
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
            'trans_mode_id' => 'nullable',
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

        if ($request->request_for === 'Sales Delivery') {
            $request->validate([
                'attachments' => 'required|array|min:1',
                'attachments.*.customer_name' => 'required|string|max:255',
                'attachments.*.product' => 'required|string|max:255',
                'attachments.*.file' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048', // max 1MB
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
            'trans_mode_id' => $request->trans_mode_id,
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
        } elseif ($request->request_for === 'Transport') {

            $transportRequest = Trip::create([
                'petty_id' => $newRequest->id,
                'from_place' => $request->from_place,
            ]);
            foreach ($request->destinations as $destination) {
                $transportRequest->stops()->create([
                    'destination' => $destination,
                ]);
            }
        } elseif ($request->request_for === 'Sales Delivery') {

            foreach ($request->attachments as $attachment) {
                $filePath = $attachment['file']->store('attachments', 'public');

                PettyAttachment::create([
                    'petty_id' => $newRequest->id,
                    'name' => $attachment['customer_name'],
                    'product_name' => $attachment['product'],
                    'attachment' => $filePath
                ]);
            }
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

        // Get the requester
        $requester = User::find($request->user_id);
        $users = User::permission('first pettycash approval')
            ->where('department_id', $requester->department_id)
            ->get();

        // Prepare email data
        $name = $requester->name;
        $reason = $newRequest->request_for;
        $new_id = $newRequest->id;
        $encodedId = Hashids::encode($new_id);

        // Get only their email addresses
        $emails = $users->pluck('email')->toArray();
        if (empty($emails)) {
            return redirect()->back()->with('error', 'The request was not successfully because there is no verifier appointed in your department');
        }
        Mail::to($emails)->send(new PettyRequestMail($name,  $reason, $encodedId));

        return redirect()->back()->with('success', 'Request submitted successfully.');
    }


    public function f_approve($id)
    {
        ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => Auth::user()->id,
            'action' => 'approved',
        ]);

        $request = Petty::findOrFail($id);
        $request->status = 'processing';
        $request->save();

        // Get the requester
        $requester = User::find($request->user_id);

        $users = User::permission('last pettycash approval')
            ->where('department_id', $request->department_id)
            ->get();

        $name = $requester->name;
        $emails = $users->pluck('email')->toArray();
        $reason = $request->request_for;
        $encodedId = Hashids::encode($id);
        if ($emails) {
            Mail::to($emails)->send(new FirstApprovalMail($name, $reason,  $encodedId));
        }

        return redirect()->back()->with('success', 'Request approved and status updated');
    }

    public function l_approve($id)
    {
        ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => Auth::user()->id,
            'action' => 'approved',
        ]);

        $request = Petty::findOrFail($id);
        $request->status = 'approved';
        $request->save();

        $requester = User::find($request->user_id);

        $users = User::permission('approve petycash payments')
            ->where('department_id', $request->department_id)
            ->get();

        $name = $requester->name;
        $emails = $users->pluck('email')->toArray();
        $reason = $request->request_for;
        $encodedId = Hashids::encode($id);

        if ($emails) {
            Mail::to($emails)->send(new LastApprovalMail($name, $reason,  $encodedId));
        }

        return redirect()->back()->with('success', 'Request approved and status updated');
    }

    public function c_approve($id)
    {
        $request = Petty::findOrFail($id);

        // Check if already paid
        $alreadyPaid = ApprovalLog::where('petty_id', $id)
            ->where('action', 'paid')
            ->exists();

        if ($alreadyPaid) {
            return redirect()->back()->with('error', 'This petty cash request has already been paid.');
        }

        $latestDeposit = Deposit::latest()->first();

        if (!$latestDeposit) {
            return redirect()->back()->with('error', 'No deposit available.');
        }

        // Deduct amount
        $latestDeposit->remaining -= $request->amount;
        $latestDeposit->save();

        $log = ApprovalLog::Create([
            'petty_id' => $id,
            'user_id' => Auth::user()->id,
            'action' => 'paid',
        ]);

        $request->status = 'paid';
        $request->paid_date = $log->created_at;
        $request->save();

        $requester = User::find($request->user_id);
        $name = $requester->name;
        $requester_email = $requester->email;
        $reason = $request->request_for;
        $encodedId = Hashids::encode($id);

        Mail::to($requester_email)->send(new SuccessPayment($name, $reason,  $encodedId));

        return redirect()->back()->with('success', 'Payment done successfully, and the amount has been deducted from your deposit.');
    }


    public function reject(Request $request, $id)
    {
        $request->validate(['comment' => 'required|string', 'action' => 'required|string']);

        ApprovalLog::create([
            'petty_id' => $id,
            'user_id' => Auth::user()->id,
            'action' => $request->action,
            'comment' => $request->comment,
        ]);

        $petty = Petty::find($id);

        if ($petty) {
            $petty->status = $request->action;
            $petty->save();

            $requester = User::find($petty->user_id);
            $name = $requester->name;
            $requester_email = $requester->email;
            $reason = $petty->request_for;
            $encodedId = Hashids::encode($id);


            if ($request->action === 'rejected') {
                Mail::to($requester_email)->send(new RejectMail($name, $reason, $encodedId));
                return redirect()->back()->with('success', 'This request was rejected and feedback sent successfully.');
            } else {
                Mail::to($requester_email)->send(new ResubmitMail($name, $reason, $encodedId));
                return redirect()->back()->with('success', 'You recommended resubmission for this petty cash request and feedback was sent successfully.');
            }
        }

        return redirect()->back()->with('error', 'Request not found.');
    }


    public function edit(Request $request, $hashid)
    {
        $id = Hashids::decode($hashid);
        $pickingPoints = StartPoint::all()->where('status', 'active');
        $trans = TransMode::all()->where('status', 'active');

        $petty = Petty::findOrFail($id[0]);
        $items = PettyList::where('petty_id', $petty->id)->get();

        return view('pettycash.resubmission', compact('petty', 'pickingPoints', 'items', 'trans'));
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

        // Office Supplies specific validation
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

        if ($request->request_for === 'Sales Delivery') {
            $request->validate([
                'attachments' => 'required|array|min:1',
                'attachments.*.customer_name' => 'required|string|max:255',
                'attachments.*.product' => 'required|string|max:255',
                'attachments.*.file' => 'required|file|mimes:jpg,png,jpeg,pdf|max:1024', // max 1MB
            ]);
        }

        if (in_array($request->request_for, ['Sales Delivery', 'Transport'])) {
            $request->validate([
                'from_place' => 'required|string',
                'destinations' => 'required|array|min:1',
                'destinations.*' => 'required|string|max:255',
            ]);
        }

        $petty->lists()->delete();
        $petty->trips()->delete();
        $petty->attachments()->delete();


        if ($petty->status === 'resubmission') {
            $requester = User::find($request->user_id);

            // Get all users with the specific permission and same department
            $users = User::permission('first pettycash approval')
                ->where('department_id', $requester->department_id)
                ->get();

            // Prepare email data
            $name = $requester->name;
            $reason = $request->request_for;
            $id = $request->id;
            $encodedId = Hashids::encode($id);


            $emails = $users->pluck('email')->toArray();
            if (empty($emails)) {
                return redirect()->back()->with('error', 'The request was not successfully because there is no verifier appointed in your department');
            }
            Mail::to($emails)->send(new ResubmissionMail($name,  $reason, $encodedId));
        }

        $petty->update([
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'trans_mode_id' => $request->trans_mode_id,
            'request_for' => $request->request_for,
            'amount' => $request->amount,
            'reason' => $request->reason,
            'request_type' => $request->request_type,
            'status' => 'pending',
        ]);

        // 4. Handle Attachment
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->move(public_path('attachment'), $attachmentName);
            $petty->attachment = 'attachment/' . $attachmentName;
            $petty->save();
        }

        // 5. Rebuild Office Supplies if applicable
        if ($request->request_for === 'Office Supplies' && $request->has('items')) {
            foreach ($request->items as $index => $item) {
                PettyList::create([
                    'petty_id' => $petty->id,
                    'item_name' => $item,
                    'quantity' => $request->quantity[$index] ?? null,
                    'price' => $request->price[$index] ?? null,
                ]);
            }
        }

        // 6. Rebuild Trip and Stops if applicable
        if ($request->request_for === 'Transport') {
            $newTrip = Trip::create([
                'petty_id' => $petty->id,
                'from_place' => $request->from_place,
            ]);

            foreach ($request->destinations as $destination) {
                $newTrip->stops()->create([
                    'destination' => $destination,
                ]);
            }
        } elseif ($request->request_for === 'Sales Delivery') {

            foreach ($request->attachments as $attachment) {
                $filePath = $attachment['file']->store('attachments', 'public');

                PettyAttachment::create([
                    'petty_id' => $petty->id,
                    'name' => $attachment['customer_name'],
                    'product_name' => $attachment['product'],
                    'attachment' => $filePath
                ]);
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
            'user_id' => Auth::user()->id,
            'action' => 'resubmitted',
        ]);


        return redirect()->route('petty.index')->with('success', 'Petty Cash request resubmitted successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Petty $petty)
    {
        //
    }
}
