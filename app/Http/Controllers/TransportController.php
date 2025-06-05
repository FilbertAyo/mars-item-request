<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StartPoint;
use App\Models\Stop;
use App\Models\TransMode;

class TransportController extends Controller
{
    public function routes()
    {

        $pickingPoints = StartPoint::all();
        $trans_mode = TransMode::all();
        $stops = Stop::select('destination')
            ->groupBy('destination')
            ->paginate(10);
        return view('settings.transport.routes', compact('pickingPoints', 'stops', 'trans_mode'));
    }
    public function destination()
    {

        $pickingPoints = StartPoint::all();
        $stops = Stop::select('destination')
            ->groupBy('destination')
            ->paginate(10);
        return view('settings.transport.destination', compact('pickingPoints', 'stops'));
    }

    public function storeStart(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        StartPoint::updateOrCreate(
            ['name' => $request->name],
        );

        return redirect()->back()->with('success', 'Collection Point updated successfully');
    }
    public function storeTransport(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        TransMode::updateOrCreate(
            ['name' => $request->name],
        );

        return redirect()->back()->with('success', 'Transport Mode updated successfully');
    }

    public function toggleStatus($id)
    {
        $point = StartPoint::findOrFail($id);
        $point->status = $point->status === 'active' ? 'inactive' : 'active';
        $point->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
      public function transStatus($id)
    {
        $point = TransMode::findOrFail($id);
        $point->status = $point->status === 'active' ? 'inactive' : 'active';
        $point->save();

        return redirect()->back()->with('success', 'Status updated successfully.');
    }
}
