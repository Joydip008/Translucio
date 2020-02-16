<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\LanguagePair;
use App\Models\LanguageList;
use Validator;

use App\Models\Projects;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class LanguageController extends Controller
{

    
    public function restLanguageList(Request $request){ 
        $input = $request->input();
        $rest_language_list = LanguageList::get()->except($input['id']);
        //$rest_language_list = LanguageList::get();
        echo json_encode($rest_language_list);
    }


    /* List View Page Of Language Pair List*/
    public function index(Request $request){

        $LanguageListDetails = LanguageList::get();
        $LanguagePairDetails = LanguagePair::orderBy('updated_at','desc')->get();



        $data = array();
        foreach ($LanguagePairDetails as $key=>$item) {
                
          
            $from_language_name=LanguageList::where('id',$item['from_language'])->first();
            $to_language_name=LanguageList::where('id',$item['to_language'])->first();

          

            $item['from_language_name'] = $from_language_name['name'];
            $item['to_language_name'] = $to_language_name['name'];
        
        }

        // $results = $LanguagePairDetails;
        // //This would contain all data to be sent to the view
        // $data = array();

        // //Get current page form url e.g. &page=6
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // //Create a new Laravel collection from the array data
        // $collection = new Collection($results);

        // //Define how many items we want to be visible in each page
        // $per_page = 5;

        // //Slice the collection to get the items to display in current page
        // $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        // //Create our paginator and add it to the data array
        // $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        // //Set base url for pagination links to follow e.g custom/url?page=6
        // $data['results']->setPath($request->url());


        $languagePairUpdate='';
        $left_menu='language_pair';

        $rest_language_list ='';

        //if(!empty($LanguagePairDetails)){
            return view('admin.language_pair',compact('left_menu','LanguageListDetails','LanguagePairDetails','data','languagePairUpdate','rest_language_list'));
        //}
        
    }



    public function UpdateLanguagePair(Request $request,$id=null){
       

        $LanguageListDetails = LanguageList::get();
        $LanguagePairDetails = LanguagePair::orderBy('updated_at','desc')->get();
       
        $data = array();
        foreach ($LanguagePairDetails as $key=>$item) {
                
          
            $from_language_name=LanguageList::where('id',$item['from_language'])->first();
            $to_language_name=LanguageList::where('id',$item['to_language'])->first();

          

            $item['from_language_name'] = $from_language_name['name'];
            $item['to_language_name'] = $to_language_name['name'];
        
        }

        $languagePairUpdate = LanguagePair::where('id',$id)->first();


        // $results = $LanguagePairDetails;
        // //This would contain all data to be sent to the view
        // $data = array();

        // //Get current page form url e.g. &page=6
        // $currentPage = LengthAwarePaginator::resolveCurrentPage();

        // //Create a new Laravel collection from the array data
        // $collection = new Collection($results);

        // //Define how many items we want to be visible in each page
        // $per_page = 5;

        // //Slice the collection to get the items to display in current page
        // $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

        // //Create our paginator and add it to the data array
        // $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

        // //Set base url for pagination links to follow e.g custom/url?page=6
        // $data['results']->setPath($request->url());
        $left_menu='language_pair';

        $rest_language_list = LanguageList::get()->except($languagePairUpdate['from_language']);
        // dd($rest_language_list);
        return view('admin.language_pair',compact('left_menu','LanguageListDetails','LanguagePairDetails','data','languagePairUpdate','rest_language_list'));

    }










    /* Fetch All The Data Of Language Pair */






    /* Add New Language Pair */

    public function saveLanguagePair(Request $request){
        $input = $request->input();
      
        if(empty($input['id'])){

            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
                'api' => 'required',
                'credit_multiplier' => 'required',
                //'status' => 'required',
            ]);
    
            if($validator->fails()){
                return back()->withInput($input)->withErrors($validator);
            }
    
            else{
    
                /* Check */
                $LanguagePairHave = LanguagePair::where('from_language',$input['from'])->where('to_language',$input['to'])->where('api',$input['api'])->first();
    
                if(!empty($LanguagePairHave)){
    
                    return back()->with('message_languageHave' , 'Language Pair Already Added!'); 
    
                }
    
                /* Add New */
    
                $LanguagePair=new LanguagePair();
                $LanguagePair->to_language=$request['to'];
                $LanguagePair->from_language=$request['from'];
                $LanguagePair->api=$request['api'];
                $LanguagePair->credit_multiplier=$request['credit_multiplier'];
                if(!empty($request['status'])){
                    $LanguagePair->status=$request['status'];
                }
                else{
                    $LanguagePair->status=1;
                }
                if(!empty($request['do_not_translate'])){
                    $LanguagePair->do_not_translate=$request['do_not_translate'];
                }
                else{
                    $LanguagePair->do_not_translate=2;
                }
                if(!empty($request['always_translate_as'])){
                    $LanguagePair->always_translate_as=$request['always_translate_as'];
                }
                else{
                    $LanguagePair->always_translate_as=2;
                }
                $LanguagePair->save();
    
                return back()->with('message' , 'Language Pair Added Successfully!'); 
            }

        }

        else{

            $validator = Validator::make($request->all(), [
                'from' => 'required',
                'to' => 'required',
                'api' => 'required',
                'credit_multiplier' => 'required',
                //'status' => 'required',
            ]);
    
            if($validator->fails()){
                return back()->withInput($input)->withErrors($validator);
            }
    
            else{
    
                /* Check */

                if(!empty($input['status'])){
                    $status=$input['status'];
                }
                else{
                    $status=1;
                }

                if(!empty($input['do_not_translate'])){
                    $do_not_translate=$input['do_not_translate'];
                }
                else{
                    $do_not_translate=2;
                }
                if(!empty($input['always_translate_as'])){
                    $always_translate_as=$input['always_translate_as'];
                }
                else{
                    $always_translate_as=2;
                }

                $LanguagePairUpdate = LanguagePair::where('id',$input['id'])->first();
               
                $LanguagePairUpdate->update([

                    $LanguagePairUpdate->to_language=$input['to'],
                    $LanguagePairUpdate->from_language=$input['from'],
                    $LanguagePairUpdate->api=$input['api'],
                    $LanguagePairUpdate->credit_multiplier=$input['credit_multiplier'],
                    $LanguagePairUpdate->status=$status,
                    $LanguagePairUpdate->do_not_translate=$do_not_translate,
                    $LanguagePairUpdate->always_translate_as=$always_translate_as,

                ]);
    
                return back()->with('message' , 'Language Pair Updated   Successfully!'); 
            }

        }

        
    }

    /* Delete Language Pair */
    public function DeleteLanguagePair(Request $request){
        $input = $request->input();

        $LanguagePairDetails = LanguagePair::where('id',$input['id'])->first();


        /* Check First This Language Pair have any link to any project or not */

        $ProjectDetails = Projects::where('current_language_id',$LanguagePairDetails['to_language'])->get();

        if(sizeof($ProjectDetails)==0){
            $data = LanguagePair::where('id',$input['id'])->delete();
            return response()->json(array('success'=>true));
        }
        if(sizeof($ProjectDetails)>0){
            return response()->json(array('success'=>false));
        }
    }

    
}
