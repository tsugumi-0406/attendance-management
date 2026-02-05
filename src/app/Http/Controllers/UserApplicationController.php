<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\UnapprovedWork;
use Illuminate\Support\Facades\Auth;

class UserApplicationController extends Controller
{
    public function application(Request $request)
    {
        $tab = $request->query('tab', 'waiting');

        $isAdmin = Auth::guard('admin')->check();

        if ($isAdmin) {
            switch ($tab) {
                case 'done':
                    $works = Work::with('user')->where('update', 'done')->get();

                    $unapproved_works = collect();

                    $user = Work::with('user');

                break;

                case 'waiting':
                default:  
                    $unapproved_works = UnapprovedWork::get();

                    $works = collect();

                    $user = UnapprovedWork::with('user');
        
                break;
            }
            return view('administrator_application', compact('tab', 'works', 'unapproved_works', 'user'));
        }

        $user = Auth::user();

        switch ($tab) {
            case 'done':
                $works = Work::where('user_id', $user->id)
                        ->where('update', 'done')
                        ->get();

                $unapproved_works = collect();

            break;

            case 'waiting':
            default:  
                $unapproved_works = UnapprovedWork::where('user_id', $user->id)
                ->get();

                $works = collect();
    
            break;
        }

        return view('user_application', compact('tab', 'user', 'unapproved_works', 'works'));
    }
}
