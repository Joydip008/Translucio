<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use App\Models\LanguageList;
use Auth;
use DB;
use App\Models\FreeCredits;

class BecomeProofreaderController extends Controller
{
    public function BecomeProofreader(){ 

        /* Language List */
        $LanguagesList = LanguageList::get();

        $TotalCredit = FreeCredits::TotalCredit();

        $TranslatedText = null;
        //return view('user.myProject.BecomeProofreader',compact('TotalCredit'));
        return view('user.myProject.test_chat_box',compact('TotalCredit','LanguagesList','TranslatedText'));
    }
}
