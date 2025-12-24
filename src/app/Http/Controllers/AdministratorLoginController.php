<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorLoginController extends Controller
{
    public function login(Request $request)
    {
        return view('administrator_login');
    }
}
