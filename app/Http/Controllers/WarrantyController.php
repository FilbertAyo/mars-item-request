<?php

namespace App\Http\Controllers;

use App\Models\Warranty;
use Illuminate\Http\Request;

class WarrantyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('warranty.index', [
            'warranties' => Warranty::all(),
        ]);
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
    $request->validate([
        'customer_name' => 'required|string',
        'model' => 'nullable|string',
        'serial_number' => 'nullable|string|unique:warranties',
        'amount' => 'nullable|string',
        'photo' => 'nullable|image|max:2048',
    ]);

    $data = $request->only(['customer_name', 'model', 'serial_number', 'amount']);

    if ($request->hasFile('photo')) {
        $data['photo'] = $request->file('photo')->store('warranties', 'public');
    }

    Warranty::create($data);

    // Reopen modal if 'manual close' wasn't triggered
    if (!$request->has('manual_close')) {
        return redirect()->back()->with('reopen_modal', true);
    }

    return redirect()->back();
}

    /**
     * Display the specified resource.
     */
    public function show(Warranty $warranty)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warranty $warranty)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warranty $warranty)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warranty $warranty)
    {
        //
    }
}
