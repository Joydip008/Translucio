<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LanguageList;
use App\Models\Projects;
use App\Models\StringCorrections;
use App\Models\LanguagePair;
use App\Models\ProjectStringCorrections;
use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use App\Models\FreeCredits;

use Illuminate\Pagination\LengthAwarePaginator; 
use Illuminate\Support\Collection;

use DB;
use Auth;
class StringCorrectionController extends Controller
{
    //
    public function index1(Request $request){
        $input = $request->input();
        return $input;
    }
    public function index(Request $request , $project_id , $language_id ,$tabId, $search=null , $type=1){

        $input = $request->input();
        //dd($input);
        $search=$request->has('search') ? $request->input('search') : '';
        //dd($input);
        

        $DoNotTranslatedList = [];

        if($type==1){

            $DoNotTranslateDetails = ProjectStringCorrections::where('type',1)->where('p_id',$project_id)->where('to_language',$language_id)->orderBy('created_at','DESC')->get();
            if(sizeof($DoNotTranslateDetails)>0){
                $tmp_arr=array();
                foreach($DoNotTranslateDetails as $DNTID){
                    $tmp_arr[]=$DNTID['string_correction_id'];
                }
                $DoNotTranslatedList = StringCorrections::whereIn('id',$tmp_arr)->where('do_not_translate_string','LIKE','%'.$search.'%')->paginate(20);
            }
        }
        // else{
        //     $DoNotTranslateDetails = ProjectStringCorrections::where('type',1)->where('p_id',$project_id)->where('to_language',$language_id)->orderBy('created_at','DESC')->get();
           
        //     if(sizeof($DoNotTranslateDetails)>0){
        //         foreach($DoNotTranslateDetails as $DNTID){
        //             $DoNotTranslatedList[] = StringCorrections::where('id',$DNTID['string_correction_id'])->first();
        //         }
        //     }
            
        // }
        //dd($DoNotTranslatedList);
       
        //$DoNotTranslatedList=$DoNotTranslatedList->paginate(1);
        $AlwaysTranslatedList = [];
    
        //if($type==2){
           
            $AlwaysTranslateDetails = ProjectStringCorrections::where('type',2)->where('p_id',$project_id)->where('to_language',$language_id)->orderBy('created_at','DESC')->get();
        //    dd($AlwaysTranslateDetails);
            if(sizeof($AlwaysTranslateDetails)>0){
                $tmp_arr1=array();
                foreach($AlwaysTranslateDetails as $AWTID){
                    $tmp_arr1[]=$AWTID['string_correction_id'];
            
                   
                }
                $AlwaysTranslatedList = StringCorrections::whereIn('id', $tmp_arr1)->where('do_not_translate_string','LIKE','%'.$search.'%')->paginate(20);
                //$AlwaysTranslatedList->setPageName('other_page');
            }
            
       // }
       
    
      

        $ProjectLists = Projects::where('id', '!=', $project_id)->get();


        /* Need Form Language- To Language */

        $ProjectDetail = Projects::where('id',$project_id)->first();

        $FromLanguage = LanguageList::where('id',$ProjectDetail['current_language_id'])->first();

        $ToLanguage = LanguageList::where('id',$language_id)->first();

        //$LanguagePairDetails = LanguagePair::where('from_language',$ProjectDetail['current_language_id'])->where('to_language',$language_id)->first();

    

        /* Language Pair Details */

        $LanguagePairDetails = LanguagePair::where('from_language',$FromLanguage['id'])->where('to_language',$ToLanguage['id'])->first();
      
        $TotalCredit = FreeCredits::TotalCredit();
        return view('user.myProject.stringcorrection',compact('TotalCredit','DoNotTranslatedList','AlwaysTranslatedList','ProjectLists','FromLanguage','ToLanguage','ProjectDetail','LanguagePairDetails','search','tabId'));
    }


    /* Select Projects for String Corrections */

    public function SelectStringCorrectionProject(Request $request){ 

        $input = $request->input();
        

        //  $DoNotStringSearch=$request->has('DoNotStringSearch') ? $request->input('DoNotStringSearch') : null;

        //  if(!empty($DoNotStringSearch)){
        //     $assessor_details=$assessor_details->where('name','like','%'.$name.'%');
        // }
        // "project_id" => "1034"
        // "from_language" => "6"
        // "to_language" => "1" 
        // "project_select" => array:2 [▼
        //   0 => "1035"
        //   1 => "1036"

       

        if(!empty($input['project_select'])){

            foreach($input['project_select'] as $SelPro){


                $ProjectStringCorrectionDetails = ProjectStringCorrections::where('p_id',$SelPro)->where('from_language',$input['from_language'])->where('to_language',$input['to_language'])->get();

                //  dd($ProjectStringCorrectionDetails);
                foreach($ProjectStringCorrectionDetails as $data){

                    /* Check Here The String is have or not */
                    // $check = StringCorrections::where('p_id',$input['project_id'])->where('do_not_translate_string', $data['do_not_translate_string'])->first();
                  
                    // print_r($check['do_not_translate_string']);
                    // echo "<br>";
                    // echo "<br>";
                    // if(empty($check)){

                        $ProjectStringCorrections =new ProjectStringCorrections();
                        $ProjectStringCorrections->p_id = $input['project_id'];
                        $ProjectStringCorrections->string_correction_id = $data['string_correction_id'];
                        $ProjectStringCorrections->type = $data['type'];
                       
                        $ProjectStringCorrections->from_language = $data['from_language'];
                        $ProjectStringCorrections->to_language = $data['to_language'];
                        $ProjectStringCorrections->status = 1;
                        $ProjectStringCorrections->save();
                    //}
                }
            }
        }
        // exit();
        return back();
    }
}
