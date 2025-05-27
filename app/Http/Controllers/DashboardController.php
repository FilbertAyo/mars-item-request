<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
      public function redirect()
    {

        return view('dashboard');

        // $userType = Auth::user()->userType;

        // if ($userType == '0') {
        //     return redirect()->route('admin.index');
        // } elseif ($userType == '1') {
        //     return redirect()->route('department.index');
        // } elseif ($userType == '2') {
        //     return redirect()->route('branch.index');
        // } elseif ($userType == '3') {
        //     return redirect()->route('general.index');
        // } elseif ($userType == '4' || $userType == '5' || $userType == '6') {
        //     return redirect()->route('petty.index');
        // } else {
        //     redirect()->back()->with('status', "You're not authorized");
        // }
    }
}
