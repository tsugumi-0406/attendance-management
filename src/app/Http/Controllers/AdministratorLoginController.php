<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Requests\AdminLoginRequest;


class AdministratorLoginController extends Controller
{
    public function login(Request $request)
    {
        return view('administrator_login');
    }

    public function authenticate(AdminLoginRequest $request)
    {
        // ここで既にバリデーション済み
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/admin/attendance/list');
        }

        return back()
            ->withErrors(['email' => 'メールアドレスまたはパスワードが違います'])
            ->onlyInput('email');
    }

    public function logout(\Illuminate\Http\Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
