<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use Cookie;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('auth.user');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return redirect()->route('my_project');
        
    }

    public function registration_success()
    {
        return view('auth.registration_successMessage');
    }

    public function logOut() {
        Cookie::queue(Cookie::forget('translucioLogin'));
        Auth::logout();
        return redirect('/');
    }
}
