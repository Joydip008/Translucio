<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\User;
class AdminController extends Controller
{
    //

    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('auth.admin');
       // $this->middleware('permission');
        
    }
    public function dashboard()
    {
        $userDetails=User::orderBy('created_at', 'DESC')->where('role_id',2)->take(3)->get();
        $left_menu='dashboard';
        if(!empty($userDetails)){
            return view('admin.dashboard',compact('userDetails','left_menu'));
        }
        else{
            $userDetails='';
            return view('admin.dashboard',compact('userDetails','left_menu'));
        }
    }
}
