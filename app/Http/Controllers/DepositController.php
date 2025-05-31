<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deposits = Deposit::orderBy('id', 'desc')->where(
            'department_id',
            auth()->user()->department_id
        )->get();

        // Get the latest deposit entry to access the 'remaining' value
        $latestDeposit = Deposit::orderBy('id', 'desc')->where(
            'department_id',
            auth()->user()->department_id
        )->first();
        $remaining = $latestDeposit ? $latestDeposit->remaining : 0;


        return view("pettycash.finance.cashier", compact("deposits", "remaining"));
    }


    /**
     * Show the form for creating a new resource.
     */
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
