<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserApplicationController extends Controller
{
    public function application(Request $request)
    {
        return view('user_application');
    }
}
