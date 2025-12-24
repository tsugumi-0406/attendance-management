<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffListController extends Controller
{
    public function list(Request $request)
    {
        return view('staff_list');
    }
}
