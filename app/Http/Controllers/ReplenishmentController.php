<?php

namespace App\Http\Controllers;

use App\Models\ApprovalLog;
use App\Models\Petty;
use App\Models\Replenishment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\Auth;


class ReplenishmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $replenishments = Replenishment::orderBy('created_at', 'desc')
            ->get();

        return view('pettycash.replenishments.index', compact('replenishments'));
    }

    /**
     * Show the form for creating a new resource.
     */


    public function create(Request $request)
    {
        $from = $request->from;
        $to = $request->to;

        // Check overlap with existing replenishments
        $overlap =  Replenishment::where('status', '!=', 'rejected')->where(function ($query) use ($from, $to) {
            $query->whereBetween('from', [$from, $to])
                ->orWhereBetween('to', [$from, $to])
                ->orWhere(function ($q) use ($from, $to) {
                    $q->where('from', '<=', $from)->where('to', '>=', $to);
                });
        })->exists();

        if ($overlap) {
            return redirect()->route('reports.petties')->with('error', 'This range overlaps with an existing replenishment. Please cross-check the dates on your replenishment list and try again.');
        }

        // Only sum paid petties without replenishment_id
        $fromInclusive = Carbon::parse($from)->startOfDay();
        $toInclusive = Carbon::parse($to)->endOfDay();

        $totalAmount = Petty::where('status', 'paid')
            ->whereBetween('created_at', [$fromInclusive, $toInclusive])
            ->whereNull('replenishment_id')
            ->sum('amount');

        return view('pettycash.replenishments.create', compact('from', 'to', 'totalAmount'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from' => 'required|date',
            'to' => 'required|date|after_or_equal:from',
            'total_amount' => 'required|numeric|min:0',
            'description' => 'required|string|max:255',
        ]);

        // Final overlap check
        $overlap =  Replenishment::where('status', '!=', 'rejected')->where(function ($query) use ($request) {
            $query->whereBetween('from', [$request->from, $request->to])
                ->orWhereBetween('to', [$request->from, $request->to])
                ->orWhere(function ($q) use ($request) {
                    $q->where('from', '<=', $request->from)->where('to', '>=', $request->to);
                });
        })->exists();

        if ($overlap) {
            return redirect()->route('reports.petties')->with('error', 'Range overlaps with an existing replenishment.');
        }


        DB::transaction(function () use ($request) {
            $replenishment = Replenishment::create([
                'from' => $request->from,
                'to' => $request->to,
                'total_amount' => $request->total_amount,
                'description' => $request->description,
            ]);
            ApprovalLog::Create([
                'replenishment_id' =>  $replenishment->id,
                'user_id' => Auth::user()->id,
                'action' => 'approved',
            ]);

            $fromInclusive = Carbon::parse($request->from)->startOfDay();
            $toInclusive = Carbon::parse($request->to)->endOfDay();

            Petty::where('status', 'paid')
                ->whereBetween('paid_date', [$fromInclusive, $toInclusive])
                ->whereNull('replenishment_id')
                ->update(['replenishment_id' => $replenishment->id]);
        });

        return redirect()->route('replenishment.index')->with('success', 'New Replenishment created and linked to petty cash successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($hashid)
    {
        $id = Hashids::decode($hashid)[0] ?? null;

        $replenishment = Replenishment::findOrFail($id);

        if ($replenishment->status === 'rejected') {
            $petties = Petty::whereDate('paid_date', '>=', $replenishment->from)
                ->whereDate('paid_date', '<=', $replenishment->to)
                ->get();
        } else {
            $petties = Petty::where('replenishment_id', $replenishment->id)->get();
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountWords = $numberTransformer->toWords($replenishment->total_amount);
        $amountWords = ucwords($amountWords);
        $amountInWords = 'TZS ' . $amountWords;

        $approvalLogs = ApprovalLog::where('replenishment_id', $replenishment->id)
            ->where('action', 'approved')
            ->orderBy('created_at')
            ->get();

        $initiator = $approvalLogs->get(0);  // Initiator
        $verifier = $approvalLogs->get(1); // Verifier
        $approver = $approvalLogs->get(2);

        return view('pettycash.replenishments.view', compact('amountInWords', 'replenishment', 'petties', 'initiator', 'verifier', 'approver'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Replenishment $replenishment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Replenishment $replenishment)
    {
        //
    }

    public function firstApprove($id)
    {
        $replenishment = Replenishment::findOrFail($id);
        $replenishment->status = 'processing';
        $replenishment->save();

        ApprovalLog::create([
            'replenishment_id' => $id,
            'user_id' => Auth::id(), // cleaner
            'action' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Replenishment first approved successfully.');
    }

    public function lastApprove($id)
    {
        $replenishment = Replenishment::findOrFail($id);
        $replenishment->status = 'approved';
        $replenishment->save();

        ApprovalLog::create([
            'replenishment_id' => $id,
            'user_id' => Auth::id(),
            'action' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Replenishment approved successfully.');
    }


    public function destroy(Replenishment $replenishment)
    {
        // Mark the replenishment as rejected
        $replenishment->status = 'rejected';
        $replenishment->save();

        // Unlink all petties associated with this replenishment
        Petty::where('replenishment_id', $replenishment->id)
            ->update(['replenishment_id' => null]);

        ApprovalLog::create([
            'replenishment_id' => $replenishment->id,
            'user_id' => Auth::user()->id,
            'action' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Replenishment rejected and unlinked from all petty cash records.');
    }


    public function downloadPDF($id)
    {

        $replenishment = Replenishment::findOrFail($id);

        if ($replenishment->status === 'rejected') {
            $petties = Petty::whereDate('paid_date', '>=', $replenishment->from)
                ->whereDate('paid_date', '<=', $replenishment->to)
                ->get();
        } else {
            $petties = Petty::where('replenishment_id', $replenishment->id)->get();
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $amountWords = $numberTransformer->toWords($replenishment->total_amount);
        $amountWords = ucwords($amountWords);
        $amountInWords = 'TZS ' . $amountWords;

        $approvalLogs = ApprovalLog::where('replenishment_id', $replenishment->id)
            ->where('action', 'approved')
            ->orderBy('created_at')
            ->get();

        $initiator = $approvalLogs->get(0) ?? null;
        $verifier = $approvalLogs->get(1) ?? null;
        $approver = $approvalLogs->get(2) ?? null;

        $pdf = Pdf::loadView('pettycash.replenishments.pdf', compact(
            'amountInWords',
            'replenishment',
            'petties',
            'initiator',
            'verifier',
            'approver'
        ));

        return $pdf->download('replenishment_' . $replenishment->id . '.pdf');
    }
}
