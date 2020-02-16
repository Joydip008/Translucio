<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LanguageList;

class TestController extends Controller
{
    //
    public function index($id){
        $data = LanguageList::where('id',$id)->first();
        return encode_json($data);
    }
}
