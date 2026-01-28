<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work;
use App\Models\UnapprovedWork;
use App\Models\User;

class AdministratorApplicationController extends Controller
{
    public function application(Request $request)
    {
        $tab = $request->query('tab', 'waiting');

        switch ($tab) {
            case 'done':
                $works = Work::where('update', 'yes')
                        ->get();

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
}
