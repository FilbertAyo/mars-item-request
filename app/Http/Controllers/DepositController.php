<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Petty;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RunningBalanceExport;
use App\Models\ApprovalLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::orderBy('id', 'desc')->where(
            'department_id',
            Auth::user()->department_id
        )->get();

        // Get the latest deposit entry to access the 'remaining' value
        $latestDeposit = Deposit::orderBy('id', 'desc')->where(
            'department_id',
            Auth::user()->department_id
        )->first();
        $remaining = $latestDeposit ? $latestDeposit->remaining : 0;


        return view("pettycash.finance.cashier", compact("deposits", "remaining"));
    }


    private function getCashflowTransactions($filterType)
    {
        $user = Auth::user();
        $departmentId = $user->department_id;
        $userId = $user->id;

        $allDeposits = Deposit::where('department_id', $departmentId)->get();
        $allPetty = Petty::with('user')
            ->where('department_id', $departmentId)
            ->where('status', 'paid')
            ->get();

        $allTransactions = collect();

        foreach ($allDeposits as $deposit) {
            $allTransactions->push([
                'date' => $deposit->created_at,
                'type' => 'Deposit',
                'requested_by' => optional($deposit->department)->name,
                'deposit' => $deposit->deposit ?? 0, // Ensure it's never null
                'deduction' => 0,
            ]);
        }

        foreach ($allPetty as $petty) {
            $allTransactions->push([
                'date' => $petty->created_at,
                'type' => 'Petty',
                'requested_by' => optional($petty->user)->name ?? 'Unknown',
                'deposit' => 0,
                'deduction' => $petty->amount ?? 0,
            ]);
        }

        $allTransactions = $allTransactions->sortBy('date')->values();
        $initialBalance = 0;
        $runningBalance = $initialBalance;

        if (in_array($filterType, ['daily', 'monthly'])) {
            $grouped = $allTransactions->groupBy(function ($tx) use ($filterType) {
                return $filterType === 'daily'
                    ? \Carbon\Carbon::parse($tx['date'])->toDateString()
                    : \Carbon\Carbon::parse($tx['date'])->format('F Y');
            });

            $groupedData = [];

            foreach ($grouped as $key => $group) {
                $dayTotal = 0;
                foreach ($group as $tx) {
                    if ($tx['type'] === 'Deposit') {
                        $runningBalance += $tx['deposit'] ?? 0;
                    } elseif ($tx['type'] === 'Petty') {
                        $runningBalance -= $tx['deduction'] ?? 0;
                        $dayTotal += $tx['deduction'] ?? 0;
                    }
                }
                $groupedData[] = [
                    'label' => $key,
                    'deduction' => $dayTotal,
                    'remaining' => $runningBalance,
                    'department' => optional(Auth::user()->department)->name,
                ];
            }

            return [
                'transactions' => $groupedData,
                'isFiltered' => true,
                'filterType' => $filterType,
            ];
        }

        // Default (no filter)
        $transactions = $allTransactions->map(function ($tx) use (&$runningBalance) {
            if ($tx['type'] === 'Deposit') {
                $runningBalance += $tx['deposit'];
            } elseif ($tx['type'] === 'Petty') {
                $runningBalance -= $tx['deduction'];
            }
            $tx['remaining'] = $runningBalance;
            return $tx;
        });

        return [
            'transactions' => $transactions,
            'isFiltered' => false,
            'filterType' => null,
        ];
    }


    public function cashflow(Request $request)
    {
        $filterType = $request->input('filter_type');
        $data = $this->getCashflowTransactions($filterType);

        return view('pettycash.finance.cashflow', $data);
    }


    public function download(Request $request)
    {
        $format = $request->get('format');
        $filterType = $request->get('filter_type');

        $data = $this->getCashflowTransactions($filterType);
        $transactions = $data['transactions'];
        $department = strtoupper(Auth::user()->department->name);

        if ($filterType === 'daily' || $filterType === 'monthly') {
            $isFiltered = true;
        } else {
            $isFiltered = false;
        }

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('pettycash.finance.cashflow_pdf', [
                'transactions' => $transactions,
                'department' => $department,
                'filterType' => $filterType,
                'isFiltered' => $isFiltered, // ✅ Add this line
            ]);

            return $pdf->download('daily_running_balance_report.pdf');
        }

        if ($format === 'excel') {
            return Excel::download(
                new RunningBalanceExport(collect($transactions), $department, $isFiltered),
                'running_balance_report.xlsx'
            );
        }

        return back()->with('error', 'Invalid format requested.');
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'deposit' => ['required', 'numeric', 'min:100'],
            'user_id' => 'required|string',
            'department_id' => 'required|string',
        ]);

        $lastDeposit = Deposit::latest()->first();

        // Calculate the new remaining balance by adding the new deposit to the previous remaining balance
        $newRemaining = ($lastDeposit ? $lastDeposit->remaining : 0) + $request->deposit;

        // Save the new deposit and remaining balance
        $newRequest = Deposit::create([
            'deposit' => $request->deposit,
            'user_id' => $request->user_id,
            'department_id' => $request->department_id,
            'created_at' => $request->created_at,
            'description' => $request->description,
            'remaining' => $newRemaining,
        ]);

        return redirect()->back()->with('success', 'Fund deposited successfully with updated balance.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Deposit $deposit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Deposit $deposit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Deposit $deposit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Deposit $deposit)
    {
        //
    }
}
