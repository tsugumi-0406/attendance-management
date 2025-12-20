<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserLoginController extends Controller
{
    public function login(Request $request)
    {
        return view('user_login');
    }
}
