<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Http\Requests\AdminLoginRequest;


class AdministratorLoginController extends Controller
{
    // ログイン画面を表示する
    public function login(Request $request)
    {
        return view('administrator_login');
    }

    // ログイン(管理者)する
    public function authenticate(AdminLoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/admin/attendance/list');
        }

        return back()
            ->withErrors(['email' => 'ログイン情報が登録されていません'])
            ->onlyInput('email');
    }

    // ログアウト(管理者)する
    public function logout(\Illuminate\Http\Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
