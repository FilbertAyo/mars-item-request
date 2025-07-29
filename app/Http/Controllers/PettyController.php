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

    private function generateUniquePettyCode()
    {
        do {
            $code = strtoupper(Str::random(2)) . rand(100, 999); // e.g. AB123
        } while (Petty::where('code', $code)->exists());

        return $code;
    }

    public function step1(Request $request, $id = null)
    {
        $mode = $id ? 'edit' : 'create';
        $pettyCash = null;

        if ($id) {
            $decoded = Hashids::decode($id);

            if (empty($decoded)) {
                abort(404, 'Invalid ID');
            }

            $pettyCashId = $decoded[0];
            $pettyCash = Petty::findOrFail($pettyCashId);
        }

        return view('pettycash.partials.step1', compact('mode', 'pettyCash'));
    }

    public function storeStep1(Request $request)
    {

        $validated = $request->validate([
            'user_id' => 'required|integer',
            'department_id' => 'required|integer',
            'request_for' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
            'request_type' => 'required|string',
        ]);

        $code = $this->generateUniquePettyCode();
        $validated['code'] = $code;

        // Store in session
        session(['step1' => $validated]);

        if ($request->request_for === 'Office Supplies') {
            return redirect()->route('petty.create.step2');
        } elseif ($request->request_for === 'Sales Delivery' || $request->request_for === 'Transport') {
            return redirect()->route('petty.create.step3');
        }
    }

    public function updateStep1(Request $request, $hashid)
    {
        $decoded = Hashids::decode($hashid);
        if (empty($decoded)) {
            abort(404, 'Invalid ID');
        }

        $id = $decoded[0];
        $pettyCash = Petty::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|integer',
            'department_id' => 'required|integer',
            'request_for' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'reason' => 'nullable|string|max:1000',
            'request_type' => 'required|string',
        ]);

        $pettyCash->update($validated);

        // Optionally store in session for step continuity
        session(['step1' => $validated]);

        // Redirect based on request type
        if ($request->request_for === 'Office Supplies') {
            return redirect()->route('petty.edit.step2', $hashid);
        } elseif ($request->request_for === 'Sales Delivery' || $request->request_for === 'Transport') {
            return redirect()->route('petty.edit.step3', $hashid);
        }
    }


    public function step2(Request $request, $id = null)
    {
        $mode = $id ? 'edit' : 'create';
        $pettyCash = null;
        $items = [];

        if ($id) {
            $decoded = Hashids::decode($id);

            if (empty($decoded)) {
                abort(404, 'Invalid ID');
            }

            $pettyCashId = $decoded[0];
            $pettyCash = Petty::findOrFail($pettyCashId);

            // Fetch related items
            $items = $pettyCash->lists()->get(['item_name', 'quantity', 'price'])->toArray();
        }

        return view('pettycash.partials.step2', compact('mode', 'pettyCash', 'items'));
    }


    public function storeStep2(Request $request)
    {
        if (!session()->has('step1')) {
            return redirect()->route('petty.create.step1')->with('error', 'Please complete all fields first.');
        }

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
        ]);

        $data = session('step1');
        $newPetty = Petty::create($data);

        foreach ($validated['items'] as $i => $item) {
            PettyList::create([
                'petty_id' => $newPetty->id,
                'item_name' => $item,
                'quantity' => $validated['quantity'][$i],
                'price' => $validated['price'][$i],
            ]);
        }

        session()->forget(['step1', 'step2']);

        $requester = Auth::user();
        $users = User::permission('first pettycash approval')
            ->where('department_id', $requester->department_id)
            ->get();

        // Prepare email data
        $name = $requester->name;
        $reason = $newPetty->request_for;
        $new_id = $newPetty->id;
        $encodedId = Hashids::encode($new_id);

        // Get only their email addresses
        $emails = $users->pluck('email')->toArray();
        if (empty($emails)) {
            return redirect()->back()->with('error', 'The request was not successfully because there is no verifier appointed in your department');
        }
        Mail::to($emails)->send(new PettyRequestMail($name,  $reason, $encodedId));

        return redirect()->route('petty.index')->with('success', 'Petty cash request created successfully!');
    }

    public function updateStep2(Request $request, $id)
    {
        $decoded = Hashids::decode($id);

        if (empty($decoded)) {
            abort(404, 'Invalid ID');
        }

        $pettyCashId = $decoded[0];
        $petty = Petty::findOrFail($pettyCashId);

        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
            'quantity' => 'required|array|min:1',
            'quantity.*' => 'required|numeric|min:1',
            'price' => 'required|array|min:1',
            'price.*' => 'required|numeric|min:0',
        ]);

        // Delete existing items first (or you could update them instead)
        $petty->items()->delete();
        $petty->status = 'pending';
        $petty->save();

        foreach ($validated['items'] as $i => $item) {
            PettyList::create([
                'petty_id' => $petty->id,
                'item_name' => $item,
                'quantity' => $validated['quantity'][$i],
                'price' => $validated['price'][$i],
            ]);
        }

        ApprovalLog::Create([
            'petty_id' =>  $petty->id,
            'user_id' => Auth::user()->id,
            'action' => 'resubmitted',
        ]);

        session()->forget(['step1', 'step2']);

        return redirect()->route('petty.index')->with('success', 'Petty cash items updated successfully!');
    }


    public function step3(Request $request, $id = null)
    {
        $mode = $id ? 'edit' : 'create';
        $pettyCash = null;
        $trip = null;
        $stops = [];

        if ($id) {
            $decoded = Hashids::decode($id);

            if (empty($decoded)) {
                abort(404, 'Invalid ID');
            }

            $pettyCashId = $decoded[0];
            $pettyCash = Petty::findOrFail($pettyCashId);

            // Get associated trip
            $trip = Trip::where('petty_id', $pettyCash->id)->first();


            if ($trip) {
                $stops = Stop::where('trip_id', $trip->id)->pluck('destination')->toArray();
            }
        }

        $pickingPoints = StartPoint::where('status', 'active')->get();
        $trans = TransMode::where('status', 'active')->get();

        return view('pettycash.partials.step3', compact(
            'mode',
            'pettyCash',
            'pickingPoints',
            'trans',
            'trip',
            'stops'
        ));
    }

    public function storeStep3(Request $request)
    {
        if (!session()->has('step1')) {
            return redirect()->route('petty.create.step1')->with('error', 'Please complete all fields first.');
        }

        $validated = $request->validate([
            'from_place' => 'required|integer',
            'destinations' => 'required|array|min:1',
            'destinations.*' => 'required|string|max:255',
            'trans_mode_id' => 'required|integer',
            'is_transporter' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle file upload
        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->storeAs('public/attachments', $attachmentName);
            $attachmentPath = 'storage/attachments/' . $attachmentName;
        }

        $step1 = session('step1');

        // Merge all data for Petty creation
        $data = array_merge($step1, [
            'trans_mode_id' => $validated['trans_mode_id'],
            'is_transporter' => $validated['is_transporter'] ?? false,
            'attachment' => $attachmentPath,
        ]);

        $newPetty = Petty::create($data);

        // Check condition to determine next step
        if ($step1['request_for'] === 'Transport') {
            $trip = Trip::create([
                'petty_id' => $newPetty->id,
                'from_place' => $validated['from_place'],
            ]);

            foreach ($validated['destinations'] as $destination) {
                $trip->stops()->create([
                    'destination' => $destination,
                ]);
            }



            session()->forget(['step1', 'step3']);

            $requester = Auth::user();
            $users = User::permission('first pettycash approval')
                ->where('department_id', $requester->department_id)
                ->get();

            // Prepare email data
            $name = $requester->name;
            $reason = $newPetty->request_for;
            $new_id = $newPetty->id;
            $encodedId = Hashids::encode($new_id);

            // Get only their email addresses
            $emails = $users->pluck('email')->toArray();
            if (empty($emails)) {
                return redirect()->back()->with('error', 'The request was not successfully because there is no verifier appointed in your department');
            }
            Mail::to($emails)->send(new PettyRequestMail($name,  $reason, $encodedId));

            return redirect()->route('petty.index')->with('success', 'Petty cash request for Transport created successfully!');
        }

        if ($step1['request_for'] === 'Sales Delivery') {
            session([
                'petty_id' => $newPetty->id,
                'step3' => $validated,
                'trip_data' => [
                    'from_place' => $validated['from_place'],
                    'destinations' => $validated['destinations'],
                ]
            ]);
            return redirect()->route('petty.create.step4');
        }


        // Fallback if request_for is something else or invalid
        return redirect()->route('petty.create.step1')->with('error', 'Invalid request type.');
    }


    public function updateStep3(Request $request, $hashid)
    {
        $id = Hashids::decode($hashid)[0] ?? null;
        $petty = Petty::findOrFail($id);

        if (!session()->has('step1')) {
            return redirect()->route('petty.edit.step1', $hashid)->with('error', 'Please complete all fields first.');
        }

        $validated = $request->validate([
            'from_place' => 'required|integer',
            'destinations' => 'required|array|min:1',
            'destinations.*' => 'required|string|max:255',
            'trans_mode_id' => 'required|integer',
            'is_transporter' => 'nullable|boolean',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
        ]);

        // Handle file upload
        $attachmentPath = $petty->attachment;
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->storeAs('public/attachments', $attachmentName);
            $attachmentPath = 'storage/attachments/' . $attachmentName;
        }

        $step1 = session('step1');

        // Merge and update Petty
        $data = array_merge($step1, [
            'trans_mode_id' => $validated['trans_mode_id'],
            'is_transporter' => $validated['is_transporter'] ?? false,
            'attachment' => $attachmentPath,
            'status' => 'pending',
        ]);

        $petty->update($data);

        // Check condition to determine next step
        if ($step1['request_for'] === 'Transport') {
            $trip = $petty->trips()->firstOrCreate([], [
                'from_place' => $validated['from_place'],
            ]);

            // Delete and re-create destinations
            $trip->stops()->delete();
            foreach ($validated['destinations'] as $destination) {
                $trip->stops()->create(['destination' => $destination]);
            }

            session()->forget(['step1', 'step3']);

            ApprovalLog::Create([
                'petty_id' =>  $petty->id,
                'user_id' => Auth::user()->id,
                'action' => 'resubmitted',
            ]);

            return redirect()->route('petty.index')->with('success', 'Petty cash request for Transport updated successfully!');
        }

        if ($step1['request_for'] === 'Sales Delivery') {
            session([
                'petty_id' => $petty->id,
                'step3' => $validated,
                'trip_data' => [
                    'from_place' => $validated['from_place'],
                    'destinations' => $validated['destinations'],
                ]
            ]);
            return redirect()->route('petty.edit.step4', $hashid);
        }

        return redirect()->route('petty.edit.step1', $hashid)->with('error', 'Invalid request type.');
    }


    public function step4($id = null)
    {
        $mode = $id ? 'edit' : 'create';
        $pettyCash = null;
        $attachments = [];

        if ($id) {
            $decoded = Hashids::decode($id);

            if (empty($decoded)) {
                abort(404, 'Invalid ID');
            }

            $pettyCashId = $decoded[0];
            $pettyCash = Petty::with('attachments')->findOrFail($pettyCashId);
            $attachments = $pettyCash->attachments ?? [];

            foreach ($attachments as $attachment) {
                $rawProducts = $attachment->product_name;

                $products = collect(explode(',', $rawProducts))
                    ->map(fn($item) => trim($item))
                    ->filter()
                    ->map(function ($item) {
                        $parts = explode('-', $item, 2); // limit to 2 parts
                        return [
                            'name' => trim($parts[0] ?? ''),
                            'qty' => trim($parts[1] ?? ''),
                        ];
                    })
                    ->toArray();

                $attachment->products = $products;
            }
        }

        return view('pettycash.partials.step4', compact('mode', 'pettyCash', 'attachments'));
    }



    public function storeStep4(Request $request)
    {
        if (!session()->has('petty_id') || !session()->has('step3')) {
            return redirect()->route('petty.create.step1')->with('error', 'Please complete all fields first.');
        }

        $validated = $request->validate([
            'attachments' => 'required|array|min:1',
            'attachments.*.customer_name' => 'required|string|max:255',
            'attachments.*.products' => 'required|array|min:1',
            'attachments.*.products.*.name' => 'required|string|max:255',
            'attachments.*.products.*.qty' => 'required|integer|min:1',
            'attachments.*.file' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);


        $pettyId = session('petty_id');
        $pettyReason = session('request_for');
        $tripData = session('trip_data');

        // Save attachments
        foreach ($request->attachments as $attachment) {
            $productLines = [];

            foreach ($attachment['products'] as $product) {
                $productLines[] = $product['name'] . ' - ' . $product['qty'];
            }

            $productString = implode("\n", $productLines);

            $filePath = $attachment['file']->store('attachments', 'public');

            PettyAttachment::create([
                'petty_id' => $pettyId,
                'name' => $attachment['customer_name'],
                'product_name' => $productString,
                'attachment' => 'storage/' . $filePath
            ]);
        }


        // Save trip & stops if present
        if ($tripData) {
            $trip = Trip::create([
                'petty_id' => $pettyId,
                'from_place' => $tripData['from_place'],
            ]);

            foreach ($tripData['destinations'] as $destination) {
                $trip->stops()->create([
                    'destination' => $destination,
                ]);
            }
        }

        session()->forget(['step1', 'step3', 'petty_id', 'trip_data']);


        $requester = Auth::user();
        $users = User::permission('first pettycash approval')
            ->where('department_id', $requester->department_id)
            ->get();

        // Prepare email data
        $name = $requester->name;
        $reason = $pettyReason;
        $new_id = $pettyId;
        $encodedId = Hashids::encode($new_id);

        // Get only their email addresses
        $emails = $users->pluck('email')->toArray();
        if (empty($emails)) {
            return redirect()->back()->with('error', 'The request was not successfully because there is no verifier appointed in your department');
        }
        Mail::to($emails)->send(new PettyRequestMail($name,  $reason, $encodedId));


        return redirect()->route('petty.index')->with('success', 'Petty cash for Sales Delivery submitted successfully.');
    }


    public function updateStep4(Request $request, $id)
    {
        $decoded = Hashids::decode($id);
        if (empty($decoded)) {
            abort(404, 'Invalid ID');
        }

        $pettyId = $decoded[0];

        $validated = $request->validate([
            'attachments' => 'required|array|min:1',
            'attachments.*.customer_name' => 'required|string|max:255',
            'attachments.*.products' => 'required|array|min:1',
            'attachments.*.products.*.name' => 'required|string|max:255',
            'attachments.*.products.*.qty' => 'required|integer|min:1',
            'attachments.*.file' => 'nullable|file|mimes:jpg,png,jpeg,pdf|max:2048',
        ]);

        $petty = Petty::with('attachments')->findOrFail($pettyId);
        $petty->status = 'pending';
        $petty->save();

        // Remove old attachments (optional: delete files from storage too)
        foreach ($petty->attachments as $attachment) {
            // Optional: delete old file
            // if ($attachment->attachment && \Storage::disk('public')->exists(str_replace('storage/', '', $attachment->attachment))) {
            //     \Storage::disk('public')->delete(str_replace('storage/', '', $attachment->attachment));
            // }
            $attachment->delete();
        }

        // Save new attachments
        foreach ($request->attachments as $attachment) {
            $productLines = [];

            foreach ($attachment['products'] as $product) {
                $productLines[] = $product['name'] . ' - ' . $product['qty'];
            }

            $productString = implode("\n", $productLines);

            $filePath = null;
            if (!empty($attachment['file'])) {
                $filePath = $attachment['file']->store('attachments', 'public');
            }

            PettyAttachment::create([
                'petty_id' => $pettyId,
                'name' => $attachment['customer_name'],
                'product_name' => $productString,
                'attachment' => $filePath ? 'storage/' . $filePath : null,
            ]);
        }

        // Optional: update trip data if needed
        if ($request->has('trip_data')) {
            $tripData = $request->trip_data;

            // Remove existing trip and stops
            if ($petty->trip) {
                $petty->trip->stops()->delete();
                $petty->trip->delete();
            }

            $trip = Trip::create([
                'petty_id' => $pettyId,
                'from_place' => $tripData['from_place'],
            ]);

            foreach ($tripData['destinations'] as $destination) {
                $trip->stops()->create([
                    'destination' => $destination,
                ]);
            }
        }

        ApprovalLog::Create([
            'petty_id' =>  $petty->id,
            'user_id' => Auth::user()->id,
            'action' => 'resubmitted',
        ]);

        session()->forget(['step1', 'step3', 'petty_id', 'trip_data']);

        return redirect()->route('petty.index')->with('success', 'Petty cash attachments updated successfully.');
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

    public function requestsCashier()
    {
        $requests = Petty::orderBy('created_at', 'desc')->where(
            'department_id',
            operator: Auth::user()->department_id
        )->whereIn('status', ['approved', 'paid'])->get();

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


    public function updateAttachment(Request $request, $id)
    {
        $request->validate([
            'attachment' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx,xlsx,xls|max:2048',
        ]);

        $petty = Petty::findOrFail($id);

        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $attachmentName = time() . '_' . $attachment->getClientOriginalName();
            $attachment->storeAs('public/attachments', $attachmentName);
            $petty->attachment = 'storage/attachments/' . $attachmentName;
            $petty->save();

            return redirect()->back()->with('success', 'Attachment updated successfully.');
        }

        return redirect()->back()->with('error', 'No file uploaded.');
    }


}
