<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SubscriptionsHistory;
use App\Models\Plans;

use Auth;
use DB;

use App\Models\FreeCredits;

class HelpAndFeqController extends Controller
{
    public function HtmlAndFeq(){

        $TotalCredit = FreeCredits::TotalCredit();
 
        return view('user.myProject.HelpAndFeq',compact('TotalCredit'));
    }
}
