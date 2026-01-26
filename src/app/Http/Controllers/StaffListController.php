<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class StaffListController extends Controller
{
    public function list(Request $request)
    {
        $staffs = User::all();

        return view('staff_list', compact('staffs'));
    }
}
