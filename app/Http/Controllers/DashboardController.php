<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Petty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function redirect()
    {

        //This is very important but later should be moved to Console/Command for automatic update
        DB::table('petties')
            ->where('status', 'resubmission')
            ->where('updated_at', '<', now()->subDay())
            ->update(['status' => 'rejected']);

        Log::info('Dashboard-triggered auto-reject of stale petty cash requests.');

        //End of it

        $userNo = User::all()->count();
        $pettyNo = Petty::all()->count();
        $paidAmount = Petty::where('status', 'paid')->sum('amount');
        $myExpense = Petty::where('user_id', auth()->id())->where('status', 'paid')->sum('amount');
        $branchNo = Branch::all()->count();
        $departmentNo = Department::all()->count();

        return view('dashboard', compact('userNo', 'pettyNo', 'paidAmount', 'myExpense', 'branchNo', 'departmentNo'));
    }


}
