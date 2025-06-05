<?php

namespace App\Http\Controllers;

use App\Exports\PettyExport;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;
use App\Models\Petty;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function usersReport(Request $request)
    {
        $query = User::where('status', 'active');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $users = $query->get();

        return view('reports.users.list', compact('users'));
    }

    public function downloadUsers(Request $request, $type)
    {
        $query = User::where('status', 'active');

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $users = $query->get();

        if ($type === 'pdf') {
            $pdf = Pdf::loadView('reports.users.pdf', compact('users'));
            return $pdf->download('USERS_LIST.pdf');
        }

        if (in_array($type, ['excel', 'csv'])) {
            return Excel::download(new UserExport($request->from, $request->to), 'USERS_LIST.' . ($type === 'csv' ? 'csv' : 'xlsx'));
        }

        return back();
    }

    public function pettyReport(Request $request)
    {
        $query = Petty::query();

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        $petties = $query->get();

        return view('reports.petty.list', compact('petties'));
    }

    public function downloadPetty(Request $request, $type)
    {
        $query = Petty::query();

        // Filter by date range
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }

        // Filter by status (e.g. paid, approved)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $petties = $query->get();

        // Export to PDF
        if ($type === 'pdf') {
            $pdf = Pdf::loadView('reports.petty.pdf', compact('petties'));
            return $pdf->download('PETTY CASH.pdf');
        }

        // Export to Excel or CSV
        if ($type === 'excel') {
            return Excel::download(new PettyExport($request->from, $request->from, $request->status), 'petty_cash.xlsx');
        }

        return back();
    }
}
