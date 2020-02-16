<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LanguageList;
use App\Models\LanguagePair;
use App\Models\ProjectData;
use App\Models\StringCorrections;
use App\Models\SourceCode;

use Spatie\PdfToText\Pdf;

use App\Models\Projects;
use App\Models\ProjectStringCorrections;
use Str;
use App\Models\TranslatedData;
use Illuminate\Broadcasting\Broadcasters\NullBroadcaster;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use \ConvertApi\ConvertApi;
use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use App\Models\DataVersion;
use DB;
use App\Models\TextCount;
use App\Models\FreeCredits;
//use App\Models\ProjectDetails;
use App\Models\ProofreadingAssociative;
use App\Models\DataSection;

use \Convertio\Convertio;
use \Convertio\Exceptions\APIException;
use \Convertio\Exceptions\CURLException;
use \CloudConvert\Api;
use Auth;
use Carbon\Carbon;

set_time_limit(3600);


use Aws\Translate\TranslateClient; 
use Aws\Exception\AwsException;

use Validator;

class ProofReadingController extends Controller
{
 
   
    public function ListProofReading(Request $request,$id,$language_id){
        // $input = $request->input();
        $text = null;
        $status = 2;//$request['status'];
        $type = 5;
        $page = null;
        $ParagraphPageIds = [];
        $ParagraphSectionIds = [];
       

        if(!empty($request['page'])){
            $page = $request['page'];
            $PageCode = SourceCode::where('p_id', $id)->where('lang_code', $language_id)->where('page_url', 'LIKE', '%'.$page.'%')->first();
            $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id', $id)->where('source_code_id', $PageCode['id'])->where('language_id', $language_id)->get();
            foreach($ProofreadingAssociativeDetails as $PRAD){
                $ParagraphPageIds[] = $PRAD['paragraph_id'];
            }
            //  dd($ParagraphIds);
        }
        else{
            $ParagraphPageIds = [];
            $page = null;
        }

        if(!empty($request['type'])){
            $type = $request['type'];
            $DataSectiponDetails = DataSection::where('p_id', $id)->where('language_id', $language_id)->where('data_section', $type)->get();
            foreach($DataSectiponDetails as $DSD){
                $paragraphSectionIds[] = $DSD['paragraph_id'];

            }

        }
        else{

            $paragraphSectionIds = [];
            $type = null;
        }
       


            

        $ProjectDetails = Projects::where('id',$id)->first();

        $OriginLanguage = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
    
        $DestinationLanguage = LanguageList::where('id',$language_id)->first();
    
        $LanguagePairDetails = LanguagePair::where('from_language',$ProjectDetails['current_language_id'])->where('to_language',$language_id)->first();

      

        $q = ProjectData::where('p_id', $id)->where('language_id',$language_id)->orderBy('created_at','ASC');

       
        
        if($request['text'] != null){

            $text = $request['text'];
            $q->where(function ($query) use ($text){
                    $query->where('content_original_data', 'LIKE', '%'.$text.'%')
                            ->orWhere('content_translated_data', 'LIKE', '%'.$text.'%');
                });
        }
        if($request['status'] != 2 && $request['status'] != null){

            $status = $request['status'];
            $q->where('status', $status);
            
        }

        if($request['type'] != 5 && $request['type'] != null){
            $type = $request['type'];
            $q->whereIn('id', $ParagraphSectionIds);
        }

        if($request['page'] != null){
            $page = $request['page'];
            $q->whereIn('id', $ParagraphPageIds);
        }

        
        
        $ParagraphDetails = $q->paginate(config('constants.PER_PAGE'));



        // if(!empty($status) || $status != Null){
        //     switch($status){
        //         case '1':
        //             // All
        //             $ParagraphDetails =  ProjectData::where('language_id',$language_id)
        //                     ->orderBy('created_at','ASC')
        //                     ->where(function ($query) use ($text){
        //                         $query->where('content_original_data', 'LIKE', '%'.$text.'%')
        //                               ->orWhere('content_translated_data', 'LIKE', '%'.$text.'%');
        //                     })->paginate(config('constants.PER_PAGE'));
        //             break;
        //         case '2':
        //             // Approved
        //             $ParagraphDetails =  ProjectData::where('language_id',$language_id)->where('status',1)
        //                     ->orderBy('created_at','ASC')
        //                     ->where(function ($query) use ($text){
        //                         $query->where('content_original_data', 'LIKE', '%'.$text.'%')
        //                               ->orWhere('content_translated_data', 'LIKE', '%'.$text.'%');
        //                     })->paginate(config('constants.PER_PAGE'));
        //             break;
        //         case '3':
        //             // Not Approved
        //             $ParagraphDetails =  ProjectData::where('language_id',$language_id)->where('status',0)
        //                     ->orderBy('created_at','ASC')
        //                     ->where(function ($query) use ($text){
        //                         $query->where('content_original_data', 'LIKE', '%'.$text.'%')
        //                               ->orWhere('content_translated_data', 'LIKE', '%'.$text.'%');
        //                     })->paginate(config('constants.PER_PAGE'));
        //             break;
        //     }
        // }
        
        // else{
           
        //     $ParagraphDetails =  ProjectData::where('language_id',$language_id)
        //                     ->orderBy('created_at','ASC')
        //                     ->paginate(config('constants.PER_PAGE'));
        // }
        
        
        // dd($ParagraphDetails);

        

        
       
         /* Html tag Name Array */ 
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';
        

        foreach($TagArray as $key => $val){
            foreach($ParagraphDetails as $line){
                $line['original_data']=str_replace($val, $key, $line['original_data']);
                $line['translated_data']=str_replace($val, $key, $line['translated_data']);
            }
        }

        foreach($TagArraystart as $key => $val){
            foreach($ParagraphDetails as $line){
                $line['original_data']=str_replace($val, $key, $line['original_data']);
                $line['translated_data']=str_replace($val, $key, $line['translated_data']);
            }
        }
       
        
        $results = $ParagraphDetails;
       
         //This would contain all data to be sent to the view
         $data = array();

         //Get current page form url e.g. &page=6
         $currentPage = LengthAwarePaginator::resolveCurrentPage();

         //Create a new Laravel collection from the array data
         $collection = new Collection($results);

         //Define how many items we want to be visible in each page
         $per_page = 5;

         //Slice the collection to get the items to display in current page
         $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();

         //Create our paginator and add it to the data array
         $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);

         //Set base url for pagination links to follow e.g custom/url?page=6
         $data['results']->setPath($request->url());
         
       

         $TotalCredit = FreeCredits::TotalCredit();
    
       
        if($ProjectDetails['project_type']  ==  1){  // 1 For Web SIte 
            return view('user.myProject.proofreading_website',compact('TotalCredit','ParagraphDetails','OriginLanguage','DestinationLanguage','results','LanguagePairDetails','ProjectDetails','status','text','page','type'));
        }
        elseif($ProjectDetails['project_type']  ==  2) // 2 for Documents
            return view('user.myProject.proofreading',compact('TotalCredit','ParagraphDetails','OriginLanguage','DestinationLanguage','results','LanguagePairDetails','ProjectDetails','status','text','page','type'));
    }
    
 

    /* Approved Paragraph */
    public function ApprovedParagraph(Request $request){

        $input = $request->input();
        $ProjectData = ProjectData::where('id',$input['id'])->first();
        $ProjectData->update([
            'status' => 1,
        ]);
        $data['msg']='Approved by<span>: '.  Auth::user()->name. ' at '.$ProjectData['updated_at'].' </span>';
        return response()->json(array('success'=>$data));
    }


    public function UpdateParagraph(Request $request){ 
        $input = $request->input();
        $ProjectData = ProjectData::where('id',$input['id'])->first();
        $details=$ProjectData['translated_data'];

        $x=preg_replace('/<\s*w:t[^>]*>/', "|**|",strip_tags($details,'<w:t>')) ;
        $x=explode('|##|',str_replace('</w:t>','|##|',str_replace("|**|","",$x),$x));
        
        $y=preg_replace('/<\s*w:t[^>]*>/', "|**|",strip_tags($input['data'],'<w:t>')) ;
        $y=explode('|##|',str_replace('</w:t>','|##|',str_replace("|**|","",$y),$y));

        foreach($x as $x11)
        {
            $x1[]=trim($x11);
        }
        foreach($x1 as $key =>$each)
        {
            if(array_key_exists($key,$y))
           $details= str_replace($each,$y[$key], $details);
        }

        $ProjectData->update([
            'status' => 1,
            'translated_data' => $details,
        ]);

        if(!empty($ProjectData['source_code_id']) || $ProjectData['source_code_id'] !==  null){
            $SourceCodeDetails = SourceCode::where('p_id',$ProjectData['p_id'])->where('lang_code',$ProjectData['language_id'])->first();
            $SourceCodeDetails->update([
                'status' => 0,
            ]);
        }
        $x=$details;
        $x=preg_replace('/<\s*w:t[^>]*>/', "|**|",strip_tags($x,'<w:t>')) ;
        $x=html_entity_decode(str_replace('</w:t>','|##|',str_replace("|**|","",$x),$x));
        $x=str_replace('|##|','',$x);
        $data['msg']='Approved by<span>: '.  Auth::user()->name. ' at '.$ProjectData['updated_at'].' </span>';
        $data['data']=$x;
        return response()->json(array('success'=>$data));
        
    }


    public function UpdateParagraphWebsite(Request $request){ 
        $input = $request->input();
       
        $ProjectData = ProjectData::where('id',$input['id'])->first();
        $OldTranslatedData = $ProjectData['translated_data'];
        $edited_Data=$input['data'];
        $OriginalEditedData = $edited_Data;

        
        $ProofreadingAssociativeDetails = ProofreadingAssociative::where('paragraph_id',$ProjectData['id'])->get();

    
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';

        foreach($TagArray as $key => $val){
            $edited_Data=str_replace($key, $val, $edited_Data);
        }

        foreach($TagArraystart as $key => $val){
            $edited_Data=str_replace($key, $val, $edited_Data);
        }
        $edited_Data=str_replace('&amp;','&',$edited_Data);                    
        $edited_Data=str_replace('&nbsp;',' ',$edited_Data);
        $edited_Data=str_replace('&amp;gt;','>',$edited_Data);
        $edited_Data=str_replace('&gt;','>',$edited_Data);
        $edited_Data=str_replace('&amp;gt;','>',$edited_Data);



       /* Execution Code Here Start */

        foreach($TagArray as $key => $val){
            $OldTranslatedData=str_replace($val, $key, $OldTranslatedData);
        }

        foreach($TagArraystart as $key => $val){
            $OldTranslatedData=str_replace($val, $key, $OldTranslatedData);
        }

        foreach($ProofreadingAssociativeDetails as $PRAD){
            $SourceCodeDetails = SourceCode::where('id',$PRAD['source_code_id'])->first();
            $TranslatedHtmlSourceCode = $SourceCodeDetails['translated_html_code'];
    
            // dd($OriginalEditedData);
            $TranslatedHtmlSourceCode = str_replace($OldTranslatedData, $OriginalEditedData, html_entity_decode($TranslatedHtmlSourceCode));

            $SourceCodeDetails->update([
                'translated_html_code' => $TranslatedHtmlSourceCode,
            ]);
        }
        /* Execution Code Here End */



        $ProjectData->update([
            'status' => 1, 
            'translated_data' => $edited_Data,
        ]);

        
        if(!empty($ProjectData['source_code_id']) || $ProjectData['source_code_id'] !==  null){
            $SourceCodeDetails = SourceCode::where('p_id',$ProjectData['p_id'])->where('lang_code',$ProjectData['language_id'])->first();
            $SourceCodeDetails->update([
                'status' => 0,
            ]);
        }

        /* First All Data Version Select and Status 0 */
        DB::table('data_version')->where('paragraph_id', $ProjectData['id'])
            ->chunkById(100, function ($DataVersionDetails) {
                foreach ($DataVersionDetails as $Data) {
                    DB::table('data_version')
                        ->where('id', $Data->id)
                        ->update(['status' => 0]);
                }
            });
        /* Insert Into Data Version Table */
        $DataVersion = new DataVersion();
        $DataVersion->paragraph_id = $ProjectData['id'];
        $DataVersion->change_data = $edited_Data;
        $DataVersion->change_type = 'U';
        $DataVersion->status = 1;
        $DataVersion->save();


        $data['msg']='Approved by<span>: '.  Auth::user()->name. ' at '.$ProjectData['updated_at'].' </span>';
        $data['data']=$input['data'];
        return response()->json(array('success'=>$data));
        
    }


    /* String Correction Section From ProofReading Page Section */

    public function doNotTranslate(Request $request){

        $input = $request->input();        

         /* Validation Rules */
         $validator = Validator::make($request->all(),[
            'string' => 'required',
        ]);
        /* If validator false return back */
        if($validator->fails()){
            return back()->withInput($input)->withErrors($validator);
        }
        else{ 

            if(empty($input['existingId'])){

                /*Check String is Already have or not */
                $StringCorrectionsCheck = StringCorrections::where('do_not_translate_string', 'LIKE', $input['string'])->where('from_language', $input['from_language'])->where('to_language', $input['to_language'])->first();

                if($StringCorrectionsCheck == null){
                    /* Insert Both */
                    $StringCorrections = new StringCorrections();
                    $StringCorrections->do_not_translate_string = $input['string'];
                    $StringCorrections->from_language = $input['from_language'];
                    $StringCorrections->to_language = $input['to_language'];
                    $StringCorrections->status = 0; // ACTIVE
                    $StringCorrections->save();

                    /* Relation */
                    $ProjectStringCorrections = new ProjectStringCorrections();
                    $ProjectStringCorrections->p_id = $input['id'];
                    $ProjectStringCorrections->string_correction_id = $StringCorrections['id'];
                    $ProjectStringCorrections->type=1;
                    $ProjectStringCorrections->from_language = $input['from_language'];
                    $ProjectStringCorrections->to_language = $input['to_language']; 
                    $ProjectStringCorrections->status = 0;  // 0=> Not Used , 1=> Applied  
                    $ProjectStringCorrections->save();
                    return back()->with('success', 'Successfully Added');

                }
                elseif($StringCorrectionsCheck != null){
                    /* Now String Already Have */
                    /* Now Check Relation is have or not */

                    $ProjectStringCorrectionsCheck = ProjectStringCorrections::where('p_id', $input['id'])->where('string_correction_id', $StringCorrectionsCheck['id'])->where('from_language', $input['from_language'])->where('to_language', $input['to_language'])->first();

                    if($ProjectStringCorrectionsCheck == null){

                        /* Relation */
                        $ProjectStringCorrections = new ProjectStringCorrections();
                        $ProjectStringCorrections->p_id = $input['id'];
                        $ProjectStringCorrections->string_correction_id = $StringCorrectionsCheck['id'];
                        $ProjectStringCorrections->type=1;
                        $ProjectStringCorrections->from_language = $input['from_language'];
                        $ProjectStringCorrections->to_language = $input['to_language']; 
                        $ProjectStringCorrections->status = 0;  // => Not Used , 1=> Applied  
                        $ProjectStringCorrections->save();
                        return back()->with('success', 'Successfully Added');

                    }
                    else{
                        return back()->with('error', 'That Line Already Added!');
                    }
                }
            }
            else{
                /* Update Here */
                $data = StringCorrections::where('id',$input['existingId'])->first();
                $data->update([
                    'do_not_translate_string' => $input['string'],
                    'status' => 0,
                ]);

                return response()->json(array('success'=>true));
            }
        }
        return response()->json(array('error'=>false));
    }

    public function alwaysTranslateAS(Request $request){

        $input = $request->input();

        /* Validator Rules */
        //The do not translate string has already been taken.
        if(empty($input['existingId'])){

            $validator = Validator::make($request->all(),[

                'from_string' => 'required',
                'to_string' => 'required',
                'from_language' => 'required',
                'to_language' => 'required',
            ]);

            if($validator->fails()){
                return back()->withInput($input)->withErrors($validator);
            }

            else{ 
                /*Check String is Already have or not */
                $StringCorrectionsCheck = StringCorrections::where('do_not_translate_string', 'LIKE', $input['from_string'])->where('from_language', $input['from_language'])->where('to_language', $input['to_language'])->first();

                if($StringCorrectionsCheck == null){
                    /* Insert Both */
                    $StringCorrections = new StringCorrections();
                    $StringCorrections->do_not_translate_string = $input['from_string'];
                    $StringCorrections->always_translate_as_string = $input['to_string'];
                    $StringCorrections->from_language = $input['from_language'];
                    $StringCorrections->to_language = $input['to_language'];
                    $StringCorrections->status = 0; // ACTIVE
                    $StringCorrections->save();

                    /* Relation */
                    $ProjectStringCorrections = new ProjectStringCorrections();
                    $ProjectStringCorrections->p_id = $input['id'];
                    $ProjectStringCorrections->string_correction_id = $StringCorrections['id'];
                    $ProjectStringCorrections->type=2;
                    $ProjectStringCorrections->from_language = $input['from_language'];
                    $ProjectStringCorrections->to_language = $input['to_language']; 
                    $ProjectStringCorrections->status = 0;  // 0=> Not Used , 1=> Applied  
                    $ProjectStringCorrections->save();
                    return back()->with('success', 'Successfully Added');

                }
                elseif($StringCorrectionsCheck != null){
                    /* Now String Already Have */
                    /* Now Check Relation is have or not */

                    $ProjectStringCorrectionsCheck = ProjectStringCorrections::where('p_id', $input['id'])->where('string_correction_id', $StringCorrectionsCheck['id'])->where('from_language', $input['from_language'])->where('to_language', $input['to_language'])->first();

                    
                    if($ProjectStringCorrectionsCheck == null){

                        /* Relation */
                        $ProjectStringCorrections = new ProjectStringCorrections();
                        $ProjectStringCorrections->p_id = $input['id'];
                        $ProjectStringCorrections->string_correction_id = $StringCorrectionsCheck['id'];
                        $ProjectStringCorrections->type=1;
                        $ProjectStringCorrections->from_language = $input['from_language'];
                        $ProjectStringCorrections->to_language = $input['to_language']; 
                        $ProjectStringCorrections->status = 0;  // => Not Used , 1=> Applied  
                        $ProjectStringCorrections->save();
                        return back()->with('success', 'Successfully Added');

                    }
                    else{
                        return back()->with('error', 'That Line Already Added!');
                    }
                }
            }
        }
        else{
            /* Update Here */
            $data = StringCorrections::where('id',$input['existingId'])->first();
            $data->update([
                'always_translate_as_string' => $input['to_string'],
                'status' => 0,
            ]);

            /* Call The Execution Section As per Language Pair Api Wise */

            // return back()->with('success', 'Successfully Updated');
            return response()->json(array('success'=>true));
        }
    }

    /* Update Project */

    public function UpdatedProject($project_id,$destination_lang){

        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];

        $StringCorrections = StringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->get();
      
        if(sizeof($StringCorrections)>0){
            if(!empty($StringCorrections) || $StringCorrections != null){

                foreach($StringCorrections as $string){
    
                    $search_arr[]  =  $string['do_not_translate_string'];
                }
            }
            else{
                $search_arr[] = Null;
            }
           
            if(!empty($StringCorrections) || $StringCorrections != null){
    
                if(!empty($search_arr)){
    
                    foreach($search_arr as $search_txt){
        
                        $replace_arr[] = "<para>" . $search_txt . "</para>";
                    }
                }
            }
            else{
    
                $replace_arr = array();
            }
           

            if(!empty($StringCorrections) || $StringCorrections != null){
    
                for($i=0; $i<count($search_arr); $i++){
                    $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('original_data','LIKE','%'.$search_arr[$i].'%')->get();
                   
                   foreach($ParagraphDetails as $Paragraph){

                        $new_str = str_ireplace($search_arr, $replace_arr, $Paragraph['original_data']);

                        $client = new \GuzzleHttp\Client();
                           
                            $request = $client->post('https://api.deepl.com/v2/translate?text='.$new_str.'&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357');
                            $response=$request->getBody();
                            $res=json_decode($response);
                            $updatedParagraph =  $res->translations[0]->text;
                            $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</para>', "", $updatedParagraph);
                          
                            $data = ProjectData::where('id',$Paragraph['id'])->first();
                            $data->update([
        
                                'translated_data' => $updatedParagraph,
        
                            ]);       
                   }
            
                }
    
            }

        }

        $search_arr = [];

        $replace_arr = [];

        $ParagraphDetails = [];

        $StringCorrectionsAlways = StringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
        
        if(sizeof($StringCorrectionsAlways)>0){

            if(!empty($StringCorrectionsAlways) || $StringCorrectionsAlways != null){

                foreach($StringCorrectionsAlways as $string){
    
                    $search_arr[]  =  $string;
        
                }
        
                if(!empty($search_arr)){
        
                    foreach($search_arr as $search_txt){
        
                        $replace_arr[] = "<para>" . $search_txt . "</para>";
                        $mainReplace_arr[] = "<para>" . $search_txt['always_translate_as_string'] . "</para>";
                    }
                }
              
                for($i=0; $i<count($search_arr); $i++){
        
                    $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('original_data','LIKE','%'.$search_arr[$i]['do_not_translate_string'].'%')->get();
               
                   foreach($ParagraphDetails as $Paragraph){
        
                        $new_str = str_ireplace($search_arr[$i]['do_not_translate_string'], $mainReplace_arr[$i], $Paragraph['original_data']);
        
                        $client = new \GuzzleHttp\Client();
                           
                            $request = $client->post('https://api.deepl.com/v2/translate?text='.$new_str.'&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357');
                            $response=$request->getBody();
                            $res=json_decode($response);
                            $updatedParagraph =  $res->translations[0]->text;
                            $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</para>', "", $updatedParagraph);
                            $data = ProjectData::where('id',$Paragraph['id'])->first();
                            $data->update([
                                'translated_data' => $updatedParagraph,
                            ]);       
                   }
                }
            }
        }
        return back();
    }

    /* Delete Section Starts */
    public function deleteDoNotTranslate(Request $request){ 

        $input = $request->input();

        /* First Delete From ProjectStringCorrections */

        ProjectStringCorrections::where('p_id',$input['project_id'])->where('from_language', $input['from_language'])->where('to_language', $input['to_language'])->where('type', $input['type'])->delete();
        
        
        //StringCorrections::where('id',$input['id'])->first();
        
    
        return response()->json(array('success'=>true));

    }

    public function DownloadUpdatedProject($project_id,$destination_lang){

        
        /* Update And Download Projct */

        /* First Update Project here */
        // print_r($project_id);
        // echo "<br>";
        // print_r($destination_lang);
        // exit();

 
        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];

        $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->get();
   
        

        $StringCorrections = []; 
        foreach($ProjectStringCorrectionsDoNot as $ProjectString){
            $StringCorrections[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }

        if(sizeof($StringCorrections)>0){
            if(!empty($StringCorrections) || $StringCorrections != null){

                foreach($StringCorrections as $string){
    
                    $search_arr[]  =  $string['do_not_translate_string'];
                }
            }
            else{
    
                $search_arr[] = Null;
    
            }
           
            if(!empty($StringCorrections) || $StringCorrections != null){
    
                if(!empty($search_arr)){
    
                    foreach($search_arr as $search_txt){
        
                        $replace_arr[] = "<para>" . $search_txt . "</para>";
                    }
                }
            }
            else{
    
                $replace_arr = array();
            }

            if(!empty($StringCorrections) || $StringCorrections != null){
    
                for($i=0; $i<count($search_arr); $i++){
                    $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('original_data','LIKE','%'.$search_arr[$i].'%')->get();
                   foreach($ParagraphDetails as $Paragraph){

                        $new_str = str_ireplace($search_arr, $replace_arr, $Paragraph['original_data']);

                        $client = new \GuzzleHttp\Client();
                           
                            $request = $client->post('https://api.deepl.com/v2/translate?text='.$new_str.'&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357');
                            $response=$request->getBody();
                            $res=json_decode($response);
                            $updatedParagraph =  $res->translations[0]->text;
                            $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</para>', "", $updatedParagraph);
                          
                            $data = ProjectData::where('id',$Paragraph['id'])->first();
                            $data->update([
        
                                'translated_data' => $updatedParagraph,
        
                            ]);       
                   }
            
                }
    
            }

        }

        $search_arr = [];

        $replace_arr = [];

        $ParagraphDetails = [];

        $StringAlways = [];

        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
        

        foreach($ProjectStringCorrectionsAlways as $ProjectString){

            $StringAlways[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }

       
        if(sizeof($StringAlways)>0){

            if(!empty($StringAlways) || $StringAlways != null){

                foreach($StringAlways as $string){
    
                    $search_arr[]  =  $string;
        
                }
        
                if(!empty($search_arr)){
        
                    foreach($search_arr as $search_txt){
        
                        $replace_arr[] = "<para>" . $search_txt . "</para>";
                        $mainReplace_arr[] = "<para>" . $search_txt['always_translate_as_string'] . "</para>";
                    }
                }
              
                for($i=0; $i<count($search_arr); $i++){
        
                    $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('original_data','LIKE','%'.$search_arr[$i]['do_not_translate_string'].'%')->get();
               
                   foreach($ParagraphDetails as $Paragraph){
        
                        $new_str = str_ireplace($search_arr[$i]['do_not_translate_string'], $mainReplace_arr[$i], $Paragraph['original_data']);
        
                        $client = new \GuzzleHttp\Client();
                           
                            $request = $client->post('https://api.deepl.com/v2/translate?text='.$new_str.'&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357');
                            $response=$request->getBody();
                            $res=json_decode($response);
                            $updatedParagraph =  $res->translations[0]->text;
                            $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</para>', "", $updatedParagraph);
                            $data = ProjectData::where('id',$Paragraph['id'])->first();
                            $data->update([
                                'translated_data' => $updatedParagraph,
                            ]);       
                   }
                }
            }
        }
     

        /* Download The Updated Project */



        $ProjectDetails = Projects::where('id',$project_id)->first();
        
        if($ProjectDetails['extension'] === 'docx'){

            $OriginalFileName = $ProjectDetails['documentation_name'];

        }
        elseif($ProjectDetails['extension'] === 'pdf'){
            $OriginalFileNamePDF = $ProjectDetails['documentation_name'];
 

            $ExplodeName = explode(".",$OriginalFileNamePDF);

            $OriginalFileName = $ExplodeName[0].'.docx';

        }
        

        // $ProjectData = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->get();


        /* Now Main Replace Section  Data fetch From Db and replace */

        $details_arr=[];
        $details_arr_footer=[];
        $details_arr_header=[];
        $inArrayCheck = [];
      
        $striped_content = '';
        $content = '';
        $contentFooter = '';
        $contentHeader = '';
        
       
        $filename = public_path('/assets/upload/user/project_documentation/'.$OriginalFileName);
        $destFile1='updated'.$destination_lang.'_'.$OriginalFileName;
        $destFile=public_path('assets/upload/user/project_documentation/'.$destFile1);



        $file = public_path('/assets/upload/user/project_documentation/'.$OriginalFileName);
        $temp_file = public_path('/assets/upload/user/project_documentation/'.$destFile1);


        /* This Below Code is the List of name header and footer where data have */

        $header_arr=[];
        $footer_arr=[];
        $contentHeaderFooter = '';


        $lineData = [];
        $zip = zip_open($filename);
        if (!$zip || is_numeric($zip)) return false;

            while ($zip_entry = zip_read($zip)) {
            
                if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

                if (zip_entry_name($zip_entry) != "word/_rels/document.xml.rels") continue;

                $contentHeaderFooter.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                
                zip_entry_close($zip_entry);
            }

            zip_close($zip);  

            // dd($contentHeaderFooter);

            $contentHeaderFooter = explode(" ",$contentHeaderFooter);

           

            foreach($contentHeaderFooter as $line){
                if(strpos($line, 'header') !== false || strpos($line, 'footer') !== false){

                    $lineData[] = substr($line,8,11); 
                }
            }
            // dd($lineData);
            $mainList = [];

            /* Again Filter */
            foreach($lineData as $check){
                if(strpos($check, 'header') !== false || strpos($check, 'footer') !== false){
                    //dd($check);
                    $name = explode(".",$check);
                    $mainList[] = $name[0];
                }
            }

            foreach($mainList as $line1){
                if(strpos($line1, 'header') !== false){
                    $header_arr[] = $line1.".xml";
                }
                elseif(strpos($line1 , 'footer') !== false){
                    $footer_arr[] = $line1.".xml";
                }
            }

            // dd($footer_arr);




                // if (!$zip || is_numeric($zip)) return false;

                // while ($zip_entry = zip_read($zip)) {
                
                //     if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
                //     $header_substr=substr(zip_entry_name($zip_entry),0,17);
                
                //     if ($header_substr!="word/_rels/header") continue;
                //     $raw_s=explode('/',zip_entry_name($zip_entry));
                //     $header_arr[]=substr($raw_s[2],0,strlen($raw_s[2])-5);
                    
                //     zip_entry_close($zip_entry);
                // }
                // zip_close($zip);

                // $zip = zip_open($filename);

                // if (!$zip || is_numeric($zip)) return false;

                // while ($zip_entry = zip_read($zip)) {
                
                //     if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
                //      $footer_substr=substr(zip_entry_name($zip_entry),0,17);
                
                //     if ($footer_substr!="word/_rels/footer") continue;

                //     $raw_s=explode('/',zip_entry_name($zip_entry));
                
                //     $footer_arr[]=substr($raw_s[2],0,strlen($raw_s[2])-5);
                    
                    
                //     zip_entry_close($zip_entry);
                // }
                //  zip_close($zip);


                // if(sizeof($footer_arr) == 0){
                //     $footer_arr = ['footer1.xml'];
                // }
                // if(sizeof($header_arr) == 0){
                //     $header_arr = ['header1.xml'];
                // }

                // print_r($header_arr);
                // echo "<br>";
                // print_r($footer_arr);
                // exit();

                if(file_exists($temp_file))
                    unlink($temp_file);
                
                copy($file,$temp_file);



                

                

                $zip = new \PhpOffice\PhpWord\Shared\ZipArchive;

                // /* Header Section */
                for($i=0; $i<sizeof($header_arr); $i++){

                    /* Fetch Header Content As per File Wise */

                    $HeaderContent = ProjectData::where('p_id',$ProjectDetails['id'])->where('file_name',$header_arr[$i])->where('data_section',1)->where('language_id',$destination_lang)->get();

                    // dd(sizeof($HeaderContent));
                    if(sizeof($HeaderContent)>0){
                        $details_arr_header = [];
                        foreach($HeaderContent as $headerContent){

                            $details_arr_header[$headerContent['original_data']] = $headerContent['translated_data'];
                        }

                        $fileToModifyHeader = '';

                        if(!empty($details_arr_header) || $details_arr_header !=null){

                            $fileToModifyHeader = 'word/'.$header_arr[$i];
                        }

                        if ($zip->open($temp_file) === TRUE) {
                            
                            if(!empty($details_arr_header || $details_arr_header !=null)){
                                $oldContentsHeader = $zip->getFromName($fileToModifyHeader);
                                $newContentsHeader=$oldContentsHeader;

                                foreach($details_arr_header as $key => $val){

                                    $newContentsHeader = str_replace($key,$val, $newContentsHeader);
                                }
                            }
                        }
                        if(!empty($details_arr_header)){

                            $zip->deleteName($fileToModifyHeader); 

                        }
                        if(!empty( $newContentsHeader)){


                            $zip->addFromString($fileToModifyHeader, $newContentsHeader);
                        }
                        $return =$zip->close();
                    }
                }


                /* Main Body Content */


                $fileToModify = '';
                $fileToModify = 'word/document.xml';
                if ($zip->open($temp_file) === TRUE){

                    $MainBodyContent = ProjectData::where('p_id',$ProjectDetails['id'])->where('file_name',"document.xml")->where('data_section',2)->where('language_id',$destination_lang)->get();

                
                    $details_arr = [];
                    foreach($MainBodyContent as $mainContent){

                        $details_arr[$mainContent['original_data']] = $mainContent['translated_data'];
                    }
                    
                    $oldContents = $zip->getFromName($fileToModify);
                    $newContents=$oldContents;

                    foreach($details_arr as $key => $val){
                        
                            $newContents = str_replace($key,$val, $newContents);
                        
                    }
                    $zip->deleteName($fileToModify);
                    $zip->addFromString($fileToModify, $newContents);
                    $return =$zip->close();

                }
            

                /* Footer Section */

                for($i=0; $i<sizeof($footer_arr); $i++){

                    /* Fetch Header Content As per File Wise */

                    $FooterContent = ProjectData::where('p_id',$ProjectDetails['id'])->where('file_name',$footer_arr[$i])->where('data_section',3)->where('language_id',$destination_lang)->get();

                    if(sizeof($FooterContent)>0){
                        $details_arr_footer = [];
                        foreach($FooterContent as $footerContent){

                            $details_arr_footer[$footerContent['original_data']] = $footerContent['translated_data'];
                        }

                        $fileToModifyFooter = '';

                        if(!empty($details_arr_footer) || $details_arr_footer !=null){

                            $fileToModifyFooter = 'word/'.$footer_arr[$i];
                        }

                        if ($zip->open($temp_file) === TRUE) {
                            
                            if(!empty($details_arr_footer || $details_arr_footer !=null)){
                                $oldContentsFooter = $zip->getFromName($fileToModifyFooter);
                                $newContentsFooter = $oldContentsFooter;

                                foreach($details_arr_footer as $key => $val){

                                    $newContentsFooter = str_replace($key,$val, $newContentsFooter);
                                }
                            }
                        }
                        if(!empty($details_arr_footer)){

                            $zip->deleteName($fileToModifyFooter);

                        }
                        if(!empty( $newContentsFooter)){

                            $zip->addFromString($fileToModifyFooter, $newContentsFooter);
                        }
                        $return =$zip->close();
                    }
                }
                
                if ($return==TRUE){
                        
                    $headers = array(
                        'Content-Type:application/octet-stream',
                    );
                    if($ProjectDetails['extension'] == 'docx'){

                        return response()->download($destFile, $ProjectDetails['project_name'], $headers);

                    }
                    elseif($ProjectDetails['extension'] == 'pdf'){

                        $fileName = $destFile;

                        $name1 = explode(".", $destFile1);
                        $name = $name1[0];
                        $name = $name.'.pdf';


                        $api = new Api("VlOvwyxcuWkkmIzSmjKKUZepBAnNiqavflyC8EechOjmv8SqNqWHEmSN8baajZ0e");

                        $api->convert([
                            'inputformat' => 'docx',
                            'outputformat' => 'pdf',
                            'input' => 'upload',
                            'file' => fopen($fileName, 'r'),
                        ])
                        ->wait()
                        ->download( public_path('/assets/upload/user/project_documentation/'.$name));

                        /* Download */
                        return response()->download(public_path('/assets/upload/user/project_documentation/'.$name), $ProjectDetails['project_name'], $headers);
                        
                    }
                            
                }
            }




            /* website translation execute */
            //   public function webSiteExecute($project_id,$destination_lang){

            //     // dd($destination_lang);
                

            //     $ProjectDetails = Projects::where('id',$project_id)->first();


            //     $ProjectData = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();
            //     $SourceCodeDetails = SourceCode::where('p_id',$project_id)->where('lang_code',$destination_lang)->first();
            //     $OriginalHTMLSourceCode = $SourceCodeDetails['html_code'];
            //     $TranslatedHTMLSourceCode = $SourceCodeDetails['translated_html_code'];

            //     $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();

            //     $OriginalData = [];
            //     $TranslatedOriginalData = [];

            //     foreach($TranslatedData as $data){
                                    
            //         $OriginalData[] = $data['original_data'];
            //         $TranslatedOriginalData[] = $data['translated_data'];
            //         $Replace[$data['original_data']] =  $data['translated_data'];
            //     }

            //     foreach($Replace as $key => $val){

            //         $OriginalHTMLSourceCode = str_replace(trim($key), $val, $OriginalHTMLSourceCode);
            //     }
            //     /* update Into DB full Translated data */
            //     $SourceCodeDetails->update([

            //         'translated_html_code' => $OriginalHTMLSourceCode, 
            //     ]);
            //     return back();
            // }


































    
        /* data Version Section */


        public function VersionData(Request $request){


        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';
       
      
        $data = [];
        $input = $request->input();

        $paragraph_id = $input['paragraph_id'];

        $ParagraphDetails = ProjectData::where('id',$paragraph_id)->first();

        $OriginalData = $ParagraphDetails['original_data'];

        $MachineTranslateData = DataVersion::where('paragraph_id',$paragraph_id)->where('change_type','M')->first();

        $MachineTranslateData = $MachineTranslateData['change_data'];

         /* Changes data List */
         $ChangesDataList = DataVersion::where('paragraph_id',$paragraph_id)->where('change_type','U')->get();

        foreach($TagArray as $key => $val){
            $OriginalData = str_replace($val, $key, $OriginalData);
            $MachineTranslateData = str_replace($val, $key, $MachineTranslateData);
            foreach($ChangesDataList as $Data){
                $Data['change_data'] = str_replace($val, $key, $Data['change_data']);
            }
        }
        foreach($TagArraystart as $key => $val){
            $OriginalData = str_replace($val, $key, $OriginalData);
            $MachineTranslateData = str_replace($val, $key, $MachineTranslateData);
            foreach($ChangesDataList as $Data){
                $Data['change_data'] = str_replace($val, $key, $Data['change_data']);
            }
        }
        
        if(!empty($ParagraphDetails)){           
            return view('user.myProject.version',compact('OriginalData','MachineTranslateData','ChangesDataList'));
        }
    }

    public function VersionControllerSwap(Request $request){

        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';

        $input = $request->input();
        $id = $input['id'];

        $VersionData = DataVersion::where('id',$id)->first();

        DB::table('data_version')->where('paragraph_id',$VersionData['paragraph_id'])->where('id', '!=', $id)
            ->chunkById(100, function ($DataList) {
                foreach ($DataList as $Data) {
                    DB::table('data_version')
                        ->where('id', $Data->id)
                        ->update(['status' => 0]);
                }
            });
        $VersionData->update([
            'status' => 1,
        ]);

        $ProjectData = ProjectData::where('id',$VersionData['paragraph_id'])->first();
        $OldTranslatedData = $ProjectData['translated_data'];
        $ProjectData->update([

            'translated_data' => $VersionData['change_data'],
        ]);

        $NewTranslatedData =  $VersionData['change_data'];

        foreach($TagArray as $key => $val){
            $NewTranslatedData=str_replace($val, $key, $NewTranslatedData);
            $OldTranslatedData=str_replace($val, $key, $OldTranslatedData);
        }
        foreach($TagArraystart as $key => $val){
            $NewTranslatedData=str_replace($val, $key, $NewTranslatedData);
            $OldTranslatedData=str_replace($val, $key, $OldTranslatedData);
        }

        $ProofreadingAssociativeDetails = ProofreadingAssociative::where('paragraph_id', $ProjectData['id'])->get();

        foreach($ProofreadingAssociativeDetails as $PRAD){

            $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

            $TranslatedHtmlSourceCode = $SourceCodeDetails['translated_html_code'];

            $TranslatedHtmlSourceCode = str_replace($OldTranslatedData, $NewTranslatedData, html_entity_decode($TranslatedHtmlSourceCode));

            $SourceCodeDetails->update([

                'translated_html_code' => $TranslatedHtmlSourceCode,

            ]);

        }
        
        return response()->json(array('success'=>true));
    }

    /* Google Api Section Start From here */

    function translate($api_key,$text,$target,$source=false)
    {
        $url = 'https://www.googleapis.com/language/translate/v2?key=' . $api_key . '&q=' . rawurlencode($text);
        $url .= '&target='.$target;
        if($source)
        $url .= '&source='.$source;
    
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);                 
        curl_close($ch);
    
        $obj =json_decode($response,true); //true converts stdClass to associative array.
        return $obj;
    }   

    function creditMultiplier($source_id,$destination_id)
    {
        $LanguagePairList = LanguagePair::where('from_language',$source_id)->where('to_language',$destination_id)->first();
        return $LanguagePairList['credit_multiplier'];
    }



    /* Execution Start Section */

    /* Deepl Execution Start */
    public function DeeplExecute($project_id, $destination_lang, $DoNotString, $AlwaysString, $Type){
        
        // $Type = 1 Do Not Translate , 2 = Always Translate As 
        $OriginalDoNotTranslate = $DoNotString;
       /* Html tag Name Array */
       $TagArray = [];
       $TagArray['</strong>'] = '|/strong|';
       $TagArray['<strong>'] = '|strong|';
       $TagArray['</aside>'] = '|/aside|';
       $TagArray['<aside>'] = '|aside|';
       $TagArray['</big>'] = '|/big|';
       $TagArray['<big>'] = '|big|';
       $TagArray['</a>'] = '|/a3|';
       $TagArray['</b>'] = '|/b|';
       $TagArray['</u>'] = '|/u|';
       $TagArray['<u>'] = '|u|';
       $TagArray['<b>'] = '|b|';
       $TagArray['<img '] = '|img|';
       $TagArray['</span>'] = '|/span|';

       $TagArraystart=[];
       $TagArraystart['<a '] = '|a2|';
       $TagArraystart['<span'] = '|span|';
     
       $handleclassArray = [];
       $handleclassArray['|a2|']='|a2#|';
       $handleclassArray['|span|']='|span#|';

        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];

        $ProjectDetails = Projects::where('id',$project_id)->first();

        /* Do Not Translate Start */

        if($Type == '1'){

            if(!empty($DoNotString) || $DoNotString !=null){
        
                $search_arr  =  $DoNotString;
                $replace_arr = "<para>" . $DoNotString . "</para>";
                $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
    
                $MatchParagraphDetails = [];
    
                foreach($ParagraphDetails as $line){
    
                    $stringOriginal = html_entity_decode($line['content_original_data']);
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);
    
                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
    
                $ExplodeData = explode(" ",$DoNotString);
                $length = sizeof($ExplodeData);
                $FirstData = $ExplodeData[0];
                $LastData = $ExplodeData[$length-1];
    
                foreach($MatchParagraphDetails as $line){
    
                    if (strpos(html_entity_decode($line['content_original_data']), $FirstData) !== false && strpos(html_entity_decode($line['content_original_data']), $LastData) !== false) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        $new_str = html_entity_decode($Paragraph['original_data']);
                        
                        $new_str = str_replace($FirstData, '<para>'.$FirstData, $new_str);
                        $new_str = str_replace($LastData, $LastData.'</para>', $new_str);
                        
                        foreach($TagArray as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        foreach($TagArraystart as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        
                        $client = new \GuzzleHttp\Client();
                        $options = [
                            'form_params' => [
                                "text" =>$new_str
                            ]
                        ]; 
    
                        $request = $client->post('https://api.deepl.com/v2/translate?&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
                        $response=$request->getBody();
                        $res=json_decode($response);
                        $updatedParagraph =  $res->translations[0]->text;
                        
                        $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</para>', "", $updatedParagraph);

                        $OriginalUpdateParagraph = $updatedParagraph;
                        
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);                
                        }
    
                        $TextCountString = strip_tags($new_str);
    
                        $TextCountString = $this->ExceptionalChange($TextCountString);
                        $updatedParagraph = $this->ExceptionalChange($updatedParagraph);
    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);
    
                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);  
    
                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
    
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
    
            DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
                ->chunkById(100, function ($projects_string_corrections) {
                    foreach ($projects_string_corrections as $string) {
                        DB::table('projects_string_corrections')
                            ->where('id', $string->id)
                            ->update(['status' => 1]);
                    }
                });
        }

        /* Do Not Translate End */

        $search_arr = [];
        $replace_arr = [];
        $ParagraphDetails = [];
        $StringAlways = [];
        $MatchParagraphDetails = [];
        $ProjectStringCorrectionsAlways = [];
        $mainReplace_arr = [];
        $ParagraphDetails = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];
        
        /* Start Always Translate Execution */

        if($Type == '2'){

            if(!empty($DoNotString) || $DoNotString != null){
           
                $search_arr  =  $DoNotString;
                $mainReplace_arr = "<para>" . $AlwaysString . "</para>";
                
                $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
               
                foreach($ParagraphDetails as $line){
    
                    $stringOriginal = html_entity_decode($line['content_original_data']);
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);
    
                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
                 
    
                foreach($MatchParagraphDetails as $line){
    
                    $new_str = html_entity_decode($line['original_data']);
                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str); 
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }
    
                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); 
    
                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); 
                        
                    $new_str = strip_tags($new_str);
    
                    if (strpos($new_str, $search_arr) !== false ) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        $new_str = str_replace($search_arr, $mainReplace_arr, $new_str);
    
                        $client = new \GuzzleHttp\Client();
                        $options = [
                            'form_params' => [
                                "text" =>$new_str
                            ]
                        ]; 
    
                        $request = $client->post('https://api.deepl.com/v2/translate?&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
                        $response=$request->getBody();
                        $res=json_decode($response);
                        $updatedParagraph =  $res->translations[0]->text;
                        $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</para>', "", $updatedParagraph);

                        $OriginalUpdateParagraph = $updatedParagraph;
    
                        /* Check Here The tags have or not */
    
                        $ReplaceString = $mainReplace_arr;
    
                        $ReplaceString = str_replace("<para>","",$ReplaceString);
                        $ReplaceString = str_replace("</para>","",$ReplaceString);
    
                        $ExplodeData = explode(" ",$ReplaceString);
                        
                        $length = sizeof($ExplodeData);
        
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];
                            
                        for($j=0; $j<count($TagDetails[0]); $j++){
                            
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){
    
                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                                
                                foreach($TagDetailsEnd[1] as $key => $TagEnd){
    
                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){
                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        unset($TagDetailsEnd[1][$key]);
                                    }
                                }
                            }
                        }
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                            
                        $TextCountString = strip_tags($new_str);
    
                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);
    
                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);  
    
                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
    
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
    
            DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
                ->chunkById(100, function ($projects_string_corrections) {
                    foreach ($projects_string_corrections as $string) {
                        DB::table('projects_string_corrections')
                            ->where('id', $string->id)
                            ->update(['status' => 1]);
                    }
                });
        }
        
        /* End Always Translate */

        /* The ABove ALl codes are change IN DB */

        /* List Of All the effected paragraphs */

        /* List Of All the effected paragraphs */

        $MatchParagraphDetails = [];
        $ProofreadingAssociativeDetails = [];
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
        foreach($ParagraphDetails as $line){
            $stringOriginal = html_entity_decode($line['content_original_data']);
            foreach($TagArray as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }     
            foreach($TagArraystart as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }    
            $stringOriginal = str_replace("\n","",$stringOriginal);
            
            $stringOriginal = strip_tags($stringOriginal);

            if (strpos($stringOriginal, $DoNotString) !== false) {
                $MatchParagraphDetails[] = $line;
            }    
        }

        foreach($MatchParagraphDetails as $MPD){
            $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
        }

        foreach($ProofreadingAssociativeDetails as $PRADetails){

            foreach($PRADetails as $PRAD){

                $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

                $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

                foreach($TagArray as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                foreach($TagArraystart as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                $TranslatedData = [];
                /* Fetch paragraph ids */
                $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();
                foreach($ProofreadingAssociativeDetails as $Associative){

                    $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                    
                }

                $OriginalData = [];
                $TranslatedOriginalData = [];
            
                foreach($TranslatedData as $data){
                    $Replace[$data['original_data']] =  $data['translated_data'];
                }
                        
                array_multisort(array_map('strlen', $Replace), $Replace);
                $Replace = array_reverse($Replace);

                foreach($Replace as $key=>$val){
                    $OriginalData[] = $key;
                    $TranslatedOriginalData[] = $val;
                }
        
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($OriginalHtmlSourceCode);
                $htmls = $doc->getElementsByTagName('html');
                
                if($htmls->length > 0)
                {
                    $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
                }
                
                foreach($TagArray as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                foreach($TagArraystart as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                /* update Into DB full Translated data */

                $new_html = $this->ExceptionalChange($new_html);

                $SourceCodeDetails->update([
                    'translated_html_code' => $new_html,
                ]);
            }
        }

        return back();
    }


    /* Google Api Website Execute */
    public function GoogleExecute($project_id, $destination_lang, $DoNotString, $AlwaysString, $Type){

        $OriginalDoNotTranslate = $DoNotString;
        /* Html tag Name Array */
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';

        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];  // Destination Language Code 

        $ProjectDetails = Projects::where('id',$project_id)->first();

        /* Source Language Details */
        $SourceLanguageDetails = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        $SourceLanguageCode = $SourceLanguageDetails['sortname'];  // Source Language Code
      
      
        if($Type == '1'){

            if(!empty($DoNotString) || $DoNotString != null){
           
                $search_arr  =  $DoNotString;
                $replace_arr = '<cite class="notranslate">' . $DoNotString . "</cite>";
                      
                $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
    
                $MatchParagraphDetails = [];
                foreach($ParagraphDetails as $line){
    
                    $stringOriginal = html_entity_decode($line['content_original_data']);
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);
    
                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
                $ExplodeData = explode(" ",$DoNotString);
                
                $length = sizeof($ExplodeData);
    
                $FirstData = $ExplodeData[0];
                $LastData = $ExplodeData[$length-1];
                
    
                foreach($MatchParagraphDetails as $line){
    
                   
                    if (strpos(html_entity_decode($line['content_original_data']), $FirstData) !== false && strpos(html_entity_decode($line['content_original_data']), $LastData) !== false) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
    
                        $new_str = html_entity_decode($Paragraph['original_data']);
                        
                        $new_str = str_replace($FirstData, '<cite class="notranslate">'.$FirstData, $new_str);
                        $new_str = str_replace($LastData, $LastData.'</cite>', $new_str);
                        
                        foreach($TagArray as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        foreach($TagArraystart as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
    
                        $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $langCode, $new_str);
                        
                        $updatedParagraph = str_replace('<cite class="notranslate">', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);
    
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
                        $OriginalUpdateParagraph = $updatedParagraph;

                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
    
                        $TextCountString = strip_tags($new_str);
    
                        $TextCountString=$this->ExceptionalChange($TextCountString);
    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);
    
                        
                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);     
    
                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
    
            DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
                ->chunkById(100, function ($projects_string_corrections) {
                    foreach ($projects_string_corrections as $string) {
                        DB::table('projects_string_corrections')
                            ->where('id', $string->id)
                            ->update(['status' => 1]);
                    }
                });

        }
    
        $search_arr = [];
        $replace_arr = [];
        $ParagraphDetails = [];
        $StringAlways = [];
        $MatchParagraphDetails = [];
        $ProjectStringCorrectionsAlways = [];
        $mainReplace_arr = [];
        $ParagraphDetails = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];

    
        if($Type == '2'){

            if(!empty($DoNotString) || $DoNotString != null){

                $search_arr  =  $DoNotString;
                $MatchParagraphDetails = [];
                $mainReplace_arr = '<cite class="notranslate">' . $AlwaysString . "</cite>";
                
                $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
                
                foreach($ParagraphDetails as $line){
    
                    $stringOriginal = html_entity_decode($line['content_original_data']);
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);
    
                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
                  
    
                foreach($MatchParagraphDetails as $line){
    
                    $new_str = html_entity_decode($line['original_data']);
                    
                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str); 
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }
    
                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); // Tag Details Start
    
                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); // Tag Details End
                    
                    $new_str = strip_tags($new_str);
    
                    if (strpos($new_str, $search_arr) !== false ) {
                            $Paragraph = ProjectData::where('id',$line['id'])->first();
                        
                        $new_str = str_replace($search_arr, $mainReplace_arr, $new_str);
    
                        $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $langCode, $new_str);
    
                        $updatedParagraph = str_replace('<cite class="notranslate">', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);
    
                        $OriginalUpdateParagraph = $updatedParagraph;

                        $ReplaceString = $mainReplace_arr;
    
                        $ReplaceString = str_replace('<cite class="notranslate">',"",$ReplaceString);
                        $ReplaceString = str_replace("</cite>","",$ReplaceString);
    
                        $ExplodeData = explode(" ",$ReplaceString);
                    
                        $length = sizeof($ExplodeData);
            
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];
    
                        for($j=0; $j<count($TagDetails[0]); $j++){
                        
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){
                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                                foreach($TagDetailsEnd[1] as $key => $TagEnd){
                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){
                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        unset($TagDetailsEnd[1][$key]);        
                                    }
                                }
                            }
                        }
    
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
    
                        $TextCountString = strip_tags($new_str);
    
                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
                    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);
    
                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);  
    
                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
    
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
    
            DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
                ->chunkById(100, function ($projects_string_corrections) {
                    foreach ($projects_string_corrections as $string) {
                        DB::table('projects_string_corrections')
                            ->where('id', $string->id)
                            ->update(['status' => 1]);
                    }
                });

        }
        
        /* The ABove ALl codes are change IN DB */

        /* List Of All the effected paragraphs */

        $MatchParagraphDetails = [];
        $ProofreadingAssociativeDetails = [];
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
        foreach($ParagraphDetails as $line){
            $stringOriginal = html_entity_decode($line['content_original_data']);
            foreach($TagArray as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }     
            foreach($TagArraystart as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }    
            $stringOriginal = str_replace("\n","",$stringOriginal);
            
            $stringOriginal = strip_tags($stringOriginal);

            if (strpos($stringOriginal, $DoNotString) !== false) {
                $MatchParagraphDetails[] = $line;
            }    
        }

        foreach($MatchParagraphDetails as $MPD){
            $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
        }

        foreach($ProofreadingAssociativeDetails as $PRADetails){

            foreach($PRADetails as $PRAD){

                $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

                $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

                // dd($OriginalHtmlSourceCode);

                foreach($TagArray as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                foreach($TagArraystart as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                $TranslatedData = [];
                /* Fetch paragraph ids */
                $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();
                foreach($ProofreadingAssociativeDetails as $Associative){

                    $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                    
                }

                $OriginalData = [];
                $TranslatedOriginalData = [];
            
                foreach($TranslatedData as $data){
                    $Replace[$data['original_data']] =  $data['translated_data'];
                }
                        
                array_multisort(array_map('strlen', $Replace), $Replace);
                $Replace = array_reverse($Replace);

                foreach($Replace as $key=>$val){
                    $OriginalData[] = $key;
                    $TranslatedOriginalData[] = $val;
                }
        
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($OriginalHtmlSourceCode);
                $htmls = $doc->getElementsByTagName('html');
                
                if($htmls->length > 0)
                {
                    $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
                }
                
                foreach($TagArray as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                foreach($TagArraystart as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                /* update Into DB full Translated data */

                $new_html = $this->ExceptionalChange($new_html);

                $SourceCodeDetails->update([
                    'translated_html_code' => $new_html,
                ]);
            }
        }
        return back();
    }

    /* Amazon APi Website Execute */
    public function AmazonExecute($project_id, $destination_lang, $DoNotString, $AlwaysString, $Type){

        $OriginalDoNotTranslate = $DoNotString;
        /* Html tag Name Array */
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';


        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];  // Destination Language Code 

        $ProjectDetails = Projects::where('id',$project_id)->first();

        /* Source Language Details */
        $SourceLanguageDetails = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        $SourceLanguageCode = $SourceLanguageDetails['sortname'];  // Source Language Code

       $ProjectStringCorrectionsAlways = [];
       $MatchParagraphDetails = [];
       $ParagraphDetails = [];
       $mainReplace_arr = [];
       $StringAlways = [];
       $replace_arr = [];
       $search_arr = [];
       $TagDetails = [];
       $FirstData = [];
       $LastData = [];

       if($Type == '1'){
        if(!empty($DoNotString) || $DoNotString != null){
            
            $search_arr  =  $DoNotString;
            $MatchParagraphDetails = [];
           
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
            
            foreach($ParagraphDetails as $line){
                $stringOriginal = html_entity_decode($line['content_original_data']);
                foreach($TagArray as $key => $val){
                    $stringOriginal = str_replace($val, $key, $stringOriginal);
                }     
                foreach($TagArraystart as $key => $val){
                    $stringOriginal = str_replace($val, $key, $stringOriginal);
                }    
                $stringOriginal = str_replace("\n","",$stringOriginal);
                
                $stringOriginal = strip_tags($stringOriginal);

                if (strpos($stringOriginal, $search_arr) !== false) {
                    $MatchParagraphDetails[] = $line;
                }    
            }
            
            $DoNotTranslateData = $DoNotString;

            $ExplodeData = explode(" ",$DoNotTranslateData);
            
            $length = sizeof($ExplodeData);

            $FirstData = $ExplodeData[0];
            $LastData = $ExplodeData[$length-1];

            foreach($MatchParagraphDetails as $line){

                if (strpos(html_entity_decode($line['content_original_data']), $FirstData) !== false && strpos(html_entity_decode($line['content_original_data']), $LastData) !== false) {
                    
                    $Paragraph = ProjectData::where('id',$line['id'])->first();

                    $new_str = html_entity_decode($Paragraph['original_data']);

                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }

                    $new_str = str_replace($FirstData, '<cite>'.$FirstData, $new_str);
                    $new_str = str_replace($LastData, $LastData.'</cite>', $new_str);

                    $FirstPosition = strpos($new_str,'<cite>');
                    $LastPosition = strpos($new_str,'</cite>');

                    $SubStr = substr($new_str,$FirstPosition,$LastPosition);

                    
                    $new_str = str_replace($SubStr, 'ce32f2f323', $new_str);
                       
                    $updatedParagraph = $this->AmazonTranslateApi($SourceLanguageCode, $langCode, $new_str); // Amazon Api Call

                    $updatedParagraph = str_replace('ce32f2f323', $SubStr, $updatedParagraph);

                    $updatedParagraph = str_replace('<cite>', "", $updatedParagraph);
                    $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                    $TextCountString = strip_tags($new_str);
                    $TextCountString=$this->ExceptionalChange($TextCountString);

                    $updatedParagraph=$this->ExceptionalChange($updatedParagraph);

                    $OriginalUpdateParagraph = $updatedParagraph;

                    foreach($TagArray as $key => $val){
                        $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                    }
                    foreach($TagArraystart as $key => $val){
                        $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                    }

                    $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                    $Paragraph->update([
                        'translated_data' => $updatedParagraph,
                    ]);     

                    DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                    ->chunkById(100, function ($DataVersionDetails) {
                        foreach ($DataVersionDetails as $Data) {
                            DB::table('data_version')
                                ->where('id', $Data->id)
                                ->update(['status' => 0]);
                        }
                    });

                    $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });
       }

        $ProjectStringCorrectionsAlways = [];
        $MatchParagraphDetails = [];
        $ParagraphDetails = [];
        $mainReplace_arr = [];
        $StringAlways = [];
        $replace_arr = [];
        $search_arr = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];

        if($Type == '2'){
            if(!empty($DoNotString) || $DoNotString != null){

                $search_arr  =  $DoNotString;
                $mainReplace_arr = '<cite>'.$AlwaysString."</cite>";
                $MatchParagraphDetails = [];
               
                $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
                    
                foreach($ParagraphDetails as $line){
    
                    $stringOriginal = html_entity_decode($line['content_original_data']);
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);
    
                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
                
               
                foreach($MatchParagraphDetails as $line){
                   
                    $new_str = html_entity_decode($line['original_data']);
                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str); 
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }
    
                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); // Tag Details Start
    
                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); // Tag Details End
                        
                    $new_str = strip_tags($new_str);
    
                    if (strpos($new_str, $search_arr) !== false ) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        
                        $new_str = str_replace($search_arr, $mainReplace_arr, $new_str);
    
                        $FirstPosition = strpos($new_str,'<cite>');
                        $LastPosition = strpos($new_str,'</cite>');
    
                        $SubStr = substr($new_str,$FirstPosition,$LastPosition);
    
                        
                        $new_str = str_replace($SubStr, 'ce32f2f323', $new_str);
    
                        $updatedParagraph = $this->AmazonTranslateApi($SourceLanguageCode, $langCode, $new_str); // Amazon Api Call
    
                        $updatedParagraph = str_replace('ce32f2f323', $SubStr, $updatedParagraph);
                        
                        $updatedParagraph = str_replace('<cite>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);
    
                        $ReplaceString = $mainReplace_arr;
    
                        $ReplaceString = str_replace('<cite>',"",$ReplaceString);
                        $ReplaceString = str_replace("</cite>","",$ReplaceString);
    
                        $OriginalUpdateParagraph = $updatedParagraph;

                        $ExplodeData = explode(" ",$ReplaceString);
                    
                        $length = sizeof($ExplodeData);
        
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];
    
                        for($j=0; $j<count($TagDetails[0]); $j++){
                    
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){
                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                                
                                foreach($TagDetailsEnd[1] as $key => $TagEnd){
                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){
                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        unset($TagDetailsEnd[1][$key]);        
                                    }
                                }
                            }
                        }
    
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
    
                        $TextCountString = strip_tags($new_str);
    
                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);
    
                        $Paragraph->update([
    
                            'translated_data' => $updatedParagraph,
    
                        ]);  
    
                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
    
                        // /* Insert Into Data Version Table */
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                            
                    }
                }
            }
    
            DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
                ->chunkById(100, function ($projects_string_corrections) {
                    foreach ($projects_string_corrections as $string) {
                        DB::table('projects_string_corrections')
                            ->where('id', $string->id)
                            ->update(['status' => 1]);
                    }
                });
        }

        

        /* List Of All the effected paragraphs */

        /* List Of All the effected paragraphs */

        $MatchParagraphDetails = [];
        $ProofreadingAssociativeDetails = [];
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
        foreach($ParagraphDetails as $line){
            $stringOriginal = html_entity_decode($line['content_original_data']);
            foreach($TagArray as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }     
            foreach($TagArraystart as $key => $val){
                $stringOriginal = str_replace($val, $key, $stringOriginal);
            }    
            $stringOriginal = str_replace("\n","",$stringOriginal);
            
            $stringOriginal = strip_tags($stringOriginal);

            if (strpos($stringOriginal, $DoNotString) !== false) {
                $MatchParagraphDetails[] = $line;
            }    
        }

        foreach($MatchParagraphDetails as $MPD){
            $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
        }

        foreach($ProofreadingAssociativeDetails as $PRADetails){


            foreach($PRADetails as $PRAD){

                $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

                $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

                foreach($TagArray as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                foreach($TagArraystart as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                $TranslatedData = [];
                /* Fetch paragraph ids */
                $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->get();
                
                foreach($ProofreadingAssociativeDetails as $Associative){

                    $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                    
                }

                $OriginalData = [];
                $TranslatedOriginalData = [];
            
                foreach($TranslatedData as $data){
                    $Replace[$data['original_data']] =  $data['translated_data'];
                }
                        
                array_multisort(array_map('strlen', $Replace), $Replace);
                $Replace = array_reverse($Replace);

                foreach($Replace as $key=>$val){
                    $OriginalData[] = $key;
                    $TranslatedOriginalData[] = $val;
                }
        
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($OriginalHtmlSourceCode);
                $htmls = $doc->getElementsByTagName('html');
                
                if($htmls->length > 0)
                {
                    $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
                }
                
                foreach($TagArray as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                foreach($TagArraystart as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                /* update Into DB full Translated data */

                $new_html = $this->ExceptionalChange($new_html);

                $SourceCodeDetails->update([
                    'translated_html_code' => $new_html,
                ]);
            }
        }
        return back();
    }


     /* For Data Version */
     public function InsertDataVersion($id, $change_data, $change_type, $status){
        $DataVersion = new DataVersion();
        $DataVersion->paragraph_id = $id;
        $DataVersion->change_data = $change_data;
        $DataVersion->change_type = $change_type; // Machine
        $DataVersion->status = $status;
        $DataVersion->save();
        return true;
    }


     /* For Text Count */
     public function InsertTextCount($ProjectId, $text, $user_id, $current_id, $destination_id){
        $textCount=new TextCount();
        $textCount->text=$text;
        $textCount->length=strlen(trim($text));
        $textCount->user_id=$user_id;

        $textCount->p_id=$ProjectId;
        $textCount->from_language=$current_id;
        $textCount->to_language=$destination_id;

        $creditMultiplier=$this->creditMultiplier($current_id,$destination_id);
        $textCount->credit_used=($creditMultiplier*strlen(trim($text)));
        $textCount->translate_date=date('Y-m-d');
        $textCount->bill_status='N';
        $textCount->payment_status='N';
        // if (!in_array(trim($HtmlText), $TagArray))
        $textCount->save();
        return true;

    }

     /* Now Change String Some Exceptional Changes */
     public function ExceptionalChange($text){

        $text=str_replace('&amp;','&',$text);                    
        $text=str_replace('&nbsp;',' ',$text);
        $text=str_replace('&amp;gt;','>',$text);
        $text=str_replace('&gt;','>',$text);
        $text=str_replace('&amp;gt;','>',$text);
        return $text;

    }

    /* For Google Translate */
    public function GoogleTranslateApi($sourceLanguage, $destinationLanguage, $text){
        $api_key = 'AIzaSyAA5VLWM0JVxrvmXS7Cvo09FnT14hBfmoQ';
       
        $obj = $this->translate($api_key,$text,$destinationLanguage,$sourceLanguage);
        if($obj != null)
        {
            if(isset($obj['error']))
            {
                echo "Error is : ".$obj['error']['message'];
            }
            else
            {
                $output = $obj['data']['translations'][0]['translatedText'];
                return $output;
            }
        }
        // else
        //     echo "UNKNOW ERROR";
    }

     /* For Amazon Translate */
     public function AmazonTranslateApi($sourceLanguage, $destinationLanguage, $text){
        $client = new TranslateClient([
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => 'AKIAWBMT42INPMAOMOXO',
                'secret' => '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',
            ]
        ]);
        try
        {        
            $result = $client->TranslateText([
                'SourceLanguageCode' => $sourceLanguage,
                'TargetLanguageCode' => $destinationLanguage, 
                'Text' => $text, 
            ]);
            return $result['TranslatedText'];
        }
        catch (AwsException $e) 
        {
            return $e->getMessage();
        }
    }

    /* For Deepl Translate */
    public function DeeplTranslateApi($sourceLanguage, $destinationLanguage, $text){
        $client = new \GuzzleHttp\Client();
        $options = [
        'form_params' => [
            "text" =>$text
            ]
        ]; 
        $request = $client->post('https://api.deepl.com/v2/translate?source_lang='.$sourceLanguage.'&target_lang='.$destinationLanguage.'&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
        $response=$request->getBody();
        $res=json_decode($response);
        $data=$res->translations[0]->text;
        return $data;
    }

    function replaceAllText2($divs, $search_arr, $replace_arr,$doc)
    {
        
        if($divs->length > 0)
        {
            foreach ($divs as $div) 
            {
                
                if($div->hasChildNodes())
                {
            
                    $this->replaceAllText2($div->childNodes, $search_arr, $replace_arr,$doc);
                }
                else
                {
                    if($div instanceof DOMCdataSection)
                    {
                      
                        continue;
                    }
                    else
                    {
                        $div->nodeValue = str_replace($search_arr, $replace_arr, $div->nodeValue);

                    }
                }
            }
        }

        $new_html = $doc->saveHTML();
        
        return $new_html;
       
    }



    /* On the fly Concept */

    public function ReplaceTranslatedString(Request $request){

        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|'; 
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';

        $input = $request->input();
        $language = LanguageList::where('id',$input['to_language'])->first();
        $langCode = $language['sortname'];

        //project_id: "1029", from_language: "6", to_language: "1", from_string: "Hello 12345", to_string: "Heloo54321"
        // the project_id variable actullay is the paragraph id 

        $ProjectDetails = Projects::where('id',$input['project_id'])->first();

        $LanguagePairDetails = LanguagePair::where('from_language',$input['from_language'])->where('to_language',$input['to_language'])->first();

        $LanguageNameSource = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();

        $SourceLanguageCode = $LanguageNameSource['sortname'];

        $LanguageNameDestination = LanguageList::where('id',$input['to_language'])->first();
        $DestinationLanguageCode = $LanguageNameDestination['sortname'];

        if(!empty($LanguagePairDetails)){
            /* Deepl Api Call And Translate */
            if($LanguagePairDetails['api']  ==  'D'){

                $this->DeeplExecute($input['project_id'], $input['to_language'], $input['from_string'], $input['to_string'], 2);
                return response()->json(array('success'=>true));
            }

            /* Google APi call And Translate */
            elseif($LanguagePairDetails['api']  ==  'G'){
                $this->GoogleExecute($input['project_id'], $input['to_language'], $input['from_string'], $input['to_string'], 2);
                return response()->json(array('success'=>true));
            }

            /* Amazon Api Call And Translate*/
            elseif($LanguagePairDetails['api']  ==  'A'){
                $this->AmazonExecute($input['project_id'], $input['to_language'], $input['from_string'], $input['to_string'], 2);
                return response()->json(array('success'=>true));
            }
        }
    }



    
    /* Select Projects for String Corrections */

    public function SelectStringCorrectionProject(Request $request){ 

        $input = $request->input();
        
        // dd($input);
        /* First Check That Project That Language have Any data Or Not */

        $ProofreadingAssociative = ProofreadingAssociative::where('p_id', $input['project_id'])->where('language_id', $input['to_language'])->get();
        // dd(count($ProofreadingAssociative));
        


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

                         /* All The String Corrections Save IN TO that project */
                        /* Now Like Execute Section Work */
                        $LanguagePairDetails = LanguagePair::where('from_language', $data['from_language'])->where('to_language', $data['to_language'])->first();
                        if($LanguagePairDetails['api'] == 'D'){
                            //dd("OKAY1");
                            /* Deepl Call */
                            $this->StringCorrectionOtherProjectsDeepl($input['project_id'], $data['to_language']);

                        }
                        elseif($LanguagePairDetails['api'] == 'G'){
                            //dd("OKAY2");
                            /* Google Call */
                            $this->StringCorrectionOtherProjectsGoogle($input['project_id'], $data['to_language']);
                            
                        }
                        elseif($LanguagePairDetails['api'] == 'A'){
                            //dd("OKAY3");
                            /* Amazon Call */
                            $this->StringCorrectionOtherProjectsAmazon($input['project_id'], $data['to_language']);

                        }

                    //}
                }
            }
        }

        if(count($ProofreadingAssociative) == 0){
            return back()->with('error', 'In This Project No data So when added data must click on execution button for string corrections apply');
        }

        else{
            return back()->with('success', 'All the string corrections successfully applied to your data!');
        }
    }




    /* String From Other Projects Executes Section */

    public function StringCorrectionOtherProjectsDeepl($project_id, $destination_lang){

        // $input = $request->all();
        // $project_id = $input['project'];
        // $destination_lang = $input['language'];
        /* Now Change INTO  MAIN HTML SOURCE CODE */

       /* Html tag Name Array */
       $TagArray = [];
       $TagArray['</strong>'] = '|/strong|';
       $TagArray['<strong>'] = '|strong|';
       $TagArray['</aside>'] = '|/aside|';
       $TagArray['<aside>'] = '|aside|';
       $TagArray['</big>'] = '|/big|';
       $TagArray['<big>'] = '|big|';
       $TagArray['</a>'] = '|/a3|';
       $TagArray['</b>'] = '|/b|';
       $TagArray['</u>'] = '|/u|';
       $TagArray['<u>'] = '|u|';
       $TagArray['<b>'] = '|b|';
       $TagArray['<img '] = '|img|';
       $TagArray['</span>'] = '|/span|';

       $TagArraystart=[];
       $TagArraystart['<a '] = '|a2|';
       $TagArraystart['<span'] = '|span|';
     
       $handleclassArray = [];
       $handleclassArray['|a2|']='|a2#|';
       $handleclassArray['|span|']='|span#|';

        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];

        $ProjectDetails = Projects::where('id',$project_id)->first();
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->get();
       $StringCorrections = []; 
       foreach($ProjectStringCorrectionsDoNot as $ProjectString){
           $StringCorrections[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
       }

        if(sizeof($StringCorrections)>0){
            if(!empty($StringCorrections) || $StringCorrections != null){
                foreach($StringCorrections as $string){
                    $search_arr[]  =  $string['do_not_translate_string'];
                }
            }
            else{
                $search_arr[] = Null;
            }
           
            if(!empty($StringCorrections) || $StringCorrections != null){
    
                if(!empty($search_arr)){
    
                    foreach($search_arr as $search_txt){
        
                        $replace_arr[] = "<para>" . $search_txt . "</para>";
                    }
                }
            }
            else{
    
                $replace_arr = array();
            }
            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();

            if(!empty($StringCorrections) || $StringCorrections != null){
    
                for($i=0; $i<count($search_arr); $i++){

                    foreach($ParagraphDetails as $line){

                        $stringOriginal = $line['original_data'];
                        foreach($TagArray as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }     
                        foreach($TagArraystart as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }    
                        $stringOriginal = str_replace("\n","",$stringOriginal);
                        
                        $stringOriginal = strip_tags($stringOriginal);

                        if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                }
            }
            
            foreach($StringCorrections as $uniuqe){
                $DoNotTranslateData = $uniuqe['do_not_translate_string'];

                $ExplodeData = explode(" ",$DoNotTranslateData);
                
                $length = sizeof($ExplodeData);

                $FirstData[] = $ExplodeData[0];
                $LastData[] = $ExplodeData[$length-1];
            }

            foreach($MatchParagraphDetails as $line){

                $i=0;
                for($i=0; $i<count($FirstData); $i++){
                    if (strpos($line['original_data'], $FirstData[$i]) !== false && strpos($line['original_data'], $LastData[$i]) !== false) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        $new_str = $Paragraph['original_data'];
                       
                        $new_str = str_replace($FirstData[$i], '<para>'.$FirstData[$i], $new_str);
                        $new_str = str_replace($LastData[$i], $LastData[$i].'</para>', $new_str);
                        
                        foreach($TagArray as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        foreach($TagArraystart as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        
                        $client = new \GuzzleHttp\Client();
                        $options = [
                            'form_params' => [
                                "text" =>$new_str
                            ]
                        ]; 

                        $request = $client->post('https://api.deepl.com/v2/translate?&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
                        $response=$request->getBody();
                        $res=json_decode($response);
                        $updatedParagraph =  $res->translations[0]->text;
                        
                        $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</para>', "", $updatedParagraph);
                        
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);                
                        }

                        $TextCountString = strip_tags($new_str);

                        $TextCountString = $this->ExceptionalChange($TextCountString);
                        $updatedParagraph = $this->ExceptionalChange($updatedParagraph);

                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);  

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });

                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });
 

        $search_arr = [];
        $replace_arr = [];
        $ParagraphDetails = [];
        $StringAlways = [];
        $MatchParagraphDetails = [];
        $ProjectStringCorrectionsAlways = [];
        $mainReplace_arr = [];
        $ParagraphDetails = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];
        
        //$ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
        
        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
   
        $StringCorrections = []; 
        
        foreach($ProjectStringCorrectionsAlways as $ProjectString){

            $StringAlways[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }
        
        if(sizeof($StringAlways)>0){
            
            if(!empty($StringAlways) || $StringAlways != null){

                foreach($StringAlways as $string){
                    $search_arr[]  =  $string['do_not_translate_string'];
                     $mainReplace_arr[] = "<para>" . $string['always_translate_as_string'] . "</para>";
                }
            }
            else{
                $search_arr[] = Null;
                $mainReplace_arr[] = null;    
            }

            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();
           
            if(!empty($StringAlways) || $StringAlways != null){
    
                for($i=0; $i<count($search_arr); $i++){
                    //$ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('original_data','LIKE', '%'.$search_arr[$i].'%' )->get();
                    
                    foreach($ParagraphDetails as $line){

                        $stringOriginal = $line['original_data'];
                        foreach($TagArray as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }     
                        foreach($TagArraystart as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }    
                        $stringOriginal = str_replace("\n","",$stringOriginal);
                        
                        $stringOriginal = strip_tags($stringOriginal);

                        if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                }
            }

            foreach($MatchParagraphDetails as $line){
              
                $i=0;
                for($i=0; $i<count($search_arr); $i++){

                    $new_str = $line['original_data'];
                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str); 
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }

                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); 

                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); 
                    
                    $new_str = strip_tags($new_str);

                    if (strpos($new_str, $search_arr[$i]) !== false ) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        $new_str = str_replace($search_arr[$i], $mainReplace_arr[$i], $new_str);

                        $client = new \GuzzleHttp\Client();
                        $options = [
                            'form_params' => [
                                "text" =>$new_str
                            ]
                        ]; 

                        $request = $client->post('https://api.deepl.com/v2/translate?&target_lang='.$langCode.'&tag_handling=xml&ignore_tags=para&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
                        $response=$request->getBody();
                        $res=json_decode($response);
                        $updatedParagraph =  $res->translations[0]->text;
                        $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</para>', "", $updatedParagraph);

                        /* Check Here The tags have or not */

                        $ReplaceString = $mainReplace_arr[$i];

                        $ReplaceString = str_replace("<para>","",$ReplaceString);
                        $ReplaceString = str_replace("</para>","",$ReplaceString);

                        $ExplodeData = explode(" ",$ReplaceString);
                        
                        $length = sizeof($ExplodeData);
        
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];
                        
                        for($j=0; $j<count($TagDetails[0]); $j++){
                            
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){

                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                               

                                foreach($TagDetailsEnd[1] as $key => $TagEnd){

                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){
                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        unset($TagDetailsEnd[1][$key]);
                                    }
                                }
                            }
                        }
                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        
                        $TextCountString = strip_tags($new_str);

                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);

                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);  

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });

                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });

        /* The ABove ALl codes are change IN DB */
        /* List Of All the effected paragraphs */

        $MatchParagraphDetails = [];
        $ProofreadingAssociativeDetails = [];
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
        // foreach($ParagraphDetails as $line){
        //     $stringOriginal = html_entity_decode($line['content_original_data']);
        //     foreach($TagArray as $key => $val){
        //         $stringOriginal = str_replace($val, $key, $stringOriginal);
        //     }     
        //     foreach($TagArraystart as $key => $val){
        //         $stringOriginal = str_replace($val, $key, $stringOriginal);
        //     }    
        //     $stringOriginal = str_replace("\n","",$stringOriginal);
            
        //     $stringOriginal = strip_tags($stringOriginal);

        //     if (strpos($stringOriginal, $DoNotString) !== false) {
        //         $MatchParagraphDetails[] = $line;
        //     }    
        // }

        foreach($ParagraphDetails as $MPD){
            $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
        }

        foreach($ProofreadingAssociativeDetails as $PRADetails){

            foreach($PRADetails as $PRAD){

                $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

                $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

                // dd($OriginalHtmlSourceCode);

                foreach($TagArray as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                foreach($TagArraystart as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                $TranslatedData = [];
                /* Fetch paragraph ids */
                $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();
                foreach($ProofreadingAssociativeDetails as $Associative){

                    $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                    
                }

                $OriginalData = [];
                $TranslatedOriginalData = [];
            
                foreach($TranslatedData as $data){
                    $Replace[$data['original_data']] =  $data['translated_data'];
                }
                        
                array_multisort(array_map('strlen', $Replace), $Replace);
                $Replace = array_reverse($Replace);

                foreach($Replace as $key=>$val){
                    $OriginalData[] = $key;
                    $TranslatedOriginalData[] = $val;
                }
        
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($OriginalHtmlSourceCode);
                $htmls = $doc->getElementsByTagName('html');
                
                if($htmls->length > 0)
                {
                    $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
                }
                
                foreach($TagArray as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                foreach($TagArraystart as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                /* update Into DB full Translated data */

                $new_html = $this->ExceptionalChange($new_html);

                $SourceCodeDetails->update([
                    'translated_html_code' => $new_html,
                ]);
            }
        }
       
       
        return back();
    }

    public function StringCorrectionOtherProjectsGoogle($project_id, $destination_lang){

        // $input = $request->all();
        // $project_id = $input['project'];
        // $destination_lang = $input['language'];
        /* Now Change INTO  MAIN HTML SOURCE CODE */

        /* Html tag Name Array */
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';

        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];  // Destination Language Code 

        $ProjectDetails = Projects::where('id',$project_id)->first();

        /* Source Language Details */
        $SourceLanguageDetails = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        $SourceLanguageCode = $SourceLanguageDetails['sortname'];  // Source Language Code
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->get();
   
       $StringCorrections = []; 
       foreach($ProjectStringCorrectionsDoNot as $ProjectString){
           $StringCorrections[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
       }
       

        if(sizeof($StringCorrections)>0){
            if(!empty($StringCorrections) || $StringCorrections != null){

                foreach($StringCorrections as $string){
    
                    $search_arr[]  =  $string['do_not_translate_string'];
                }
            }
            else{
                $search_arr[] = Null;
            }
           
            if(!empty($StringCorrections) || $StringCorrections != null){
    
                if(!empty($search_arr)){
    
                    foreach($search_arr as $search_txt){
                        $replace_arr[] = '<cite class="notranslate">' . $search_txt . "</cite>";
                    }
                }
            }
            else{
    
                $replace_arr = array();
            }
            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();

            if(!empty($StringCorrections) || $StringCorrections != null){
    
                for($i=0; $i<count($search_arr); $i++){
                    
                    foreach($ParagraphDetails as $line){

                        $stringOriginal = $line['original_data'];
                        foreach($TagArray as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }     
                        foreach($TagArraystart as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }    
                        $stringOriginal = str_replace("\n","",$stringOriginal);
                        
                        $stringOriginal = strip_tags($stringOriginal);

                        if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                }
            }
            
            foreach($StringCorrections as $uniuqe){
                $DoNotTranslateData = $uniuqe['do_not_translate_string'];

                $ExplodeData = explode(" ",$DoNotTranslateData);
                
                $length = sizeof($ExplodeData);

                $FirstData[] = $ExplodeData[0];
                $LastData[] = $ExplodeData[$length-1];
            }

            foreach($MatchParagraphDetails as $line){

                $i=0;
                for($i=0; $i<count($FirstData); $i++){
                    if (strpos($line['original_data'], $FirstData[$i]) !== false && strpos($line['original_data'], $LastData[$i]) !== false) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();

                        $new_str = $Paragraph['original_data'];
                       
                        $new_str = str_replace($FirstData[$i], '<cite class="notranslate">'.$FirstData[$i], $new_str);
                        $new_str = str_replace($LastData[$i], $LastData[$i].'</cite>', $new_str);
                        
                        foreach($TagArray as $key => $val){
        
                            $new_str = str_replace($val, $key, $new_str);
        
                        }
                        foreach($TagArraystart as $key => $val){
        
                            $new_str = str_replace($val, $key, $new_str);
                
                        }

                        $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $langCode, $new_str);
                        
                        $updatedParagraph = str_replace('<cite class="notranslate">', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }

                        $TextCountString = strip_tags($new_str);

                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);

                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);     

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });
 

        $search_arr = [];
        $replace_arr = [];
        $ParagraphDetails = [];
        $StringAlways = [];
        $MatchParagraphDetails = [];
        $ProjectStringCorrectionsAlways = [];
        $mainReplace_arr = [];
        $ParagraphDetails = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];

        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
   
        $StringCorrections = []; 
        
        foreach($ProjectStringCorrectionsAlways as $ProjectString){

            $StringAlways[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }

        if(sizeof($StringAlways)>0){
            
            if(!empty($StringAlways) || $StringAlways != null){

                foreach($StringAlways as $string){
    
                    $search_arr[]  =  $string['do_not_translate_string'];
                    $mainReplace_arr[] = '<cite class="notranslate">' . $string['always_translate_as_string'] . "</cite>";
                }
            }
            else{
                $search_arr[] = Null;
                $mainReplace_arr[] = null;
            }
           
            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();
            
            if(!empty($StringAlways) || $StringAlways != null){
    
                for($i=0; $i<count($search_arr); $i++){
                    
                    foreach($ParagraphDetails as $line){

                        $stringOriginal = $line['original_data'];
                        foreach($TagArray as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }     
                        foreach($TagArraystart as $key => $val){
                            $stringOriginal = str_replace($val, $key, $stringOriginal);
                        }    
                        $stringOriginal = str_replace("\n","",$stringOriginal);
                        
                        $stringOriginal = strip_tags($stringOriginal);

                        if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                }
            }

            foreach($MatchParagraphDetails as $line){
              
                $i=0;
                for($i=0; $i<count($search_arr); $i++){

                    $new_str = $line['original_data'];
                    
                    foreach($TagArray as $key => $val){
        
                        $new_str = str_replace($val, $key, $new_str); 
    
                    }
                    foreach($TagArraystart as $key => $val){
    
                        $new_str = str_replace($val, $key, $new_str);
            
                    }

                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); // Tag Details Start

                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); // Tag Details End
                    
                    $new_str = strip_tags($new_str);

                    if (strpos($new_str, $search_arr[$i]) !== false ) {
                         $Paragraph = ProjectData::where('id',$line['id'])->first();
                        
                        $new_str = str_replace($search_arr[$i], $mainReplace_arr[$i], $new_str);

                        $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $langCode, $new_str);

                        $api_key = 'AIzaSyAA5VLWM0JVxrvmXS7Cvo09FnT14hBfmoQ';

                        $updatedParagraph = str_replace('<cite class="notranslate">', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                        $ReplaceString = $mainReplace_arr[$i];

                        $ReplaceString = str_replace('<cite class="notranslate">',"",$ReplaceString);
                        $ReplaceString = str_replace("</cite>","",$ReplaceString);

                        $ExplodeData = explode(" ",$ReplaceString);
                    
                        $length = sizeof($ExplodeData);
        
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];

                        for($j=0; $j<count($TagDetails[0]); $j++){
                    
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){

                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                                

                                foreach($TagDetailsEnd[1] as $key => $TagEnd){

                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){

                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        
                                        unset($TagDetailsEnd[1][$key]);        

                                    }

                                }
                                
                            }

                        }

                        foreach($TagArray as $key => $val){
        
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
        
                        }
                        foreach($TagArraystart as $key => $val){
        
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                
                        }

                        $TextCountString = strip_tags($new_str);

                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
                    
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        
                        $Paragraph->update([
    
                            'translated_data' => $updatedParagraph,
    
                        ]);  

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });

                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });

        /* List Of All the effected paragraphs */

        $MatchParagraphDetails = [];
        $ProofreadingAssociativeDetails = [];
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status', 0)->get();
            // foreach($ParagraphDetails as $line){
            //     $stringOriginal = html_entity_decode($line['content_original_data']);
            //     foreach($TagArray as $key => $val){
            //         $stringOriginal = str_replace($val, $key, $stringOriginal);
            //     }     
            //     foreach($TagArraystart as $key => $val){
            //         $stringOriginal = str_replace($val, $key, $stringOriginal);
            //     }    
            //     $stringOriginal = str_replace("\n","",$stringOriginal);
                
            //     $stringOriginal = strip_tags($stringOriginal);

            //     if (strpos($stringOriginal, $DoNotString) !== false) {
            //         $MatchParagraphDetails[] = $line;
            //     }    
            // }

        foreach($ParagraphDetails as $MPD){
            $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
        }

        foreach($ProofreadingAssociativeDetails as $PRADetails){

            foreach($PRADetails as $PRAD){

                $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

                $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

                // dd($OriginalHtmlSourceCode);

                foreach($TagArray as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                foreach($TagArraystart as $key => $val){
                    $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
                }

                $TranslatedData = [];
                /* Fetch paragraph ids */
                $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();
                foreach($ProofreadingAssociativeDetails as $Associative){

                    $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                    
                }

                $OriginalData = [];
                $TranslatedOriginalData = [];
            
                foreach($TranslatedData as $data){
                    $Replace[$data['original_data']] =  $data['translated_data'];
                }
                        
                array_multisort(array_map('strlen', $Replace), $Replace);
                $Replace = array_reverse($Replace);

                foreach($Replace as $key=>$val){
                    $OriginalData[] = $key;
                    $TranslatedOriginalData[] = $val;
                }
        
                $doc = new \DOMDocument();
                libxml_use_internal_errors(true);
                $doc->loadHTML($OriginalHtmlSourceCode);
                $htmls = $doc->getElementsByTagName('html');
                
                if($htmls->length > 0)
                {
                    $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
                }
                
                foreach($TagArray as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                foreach($TagArraystart as $key => $val){
                    $new_html=str_replace($val, $key, $new_html);
                }

                /* update Into DB full Translated data */

                $new_html = $this->ExceptionalChange($new_html);

                $SourceCodeDetails->update([
                    'translated_html_code' => $new_html,
                ]);
            }
        }
        return back();
    }

    /* Amazon APi Website Execute */
    public function StringCorrectionOtherProjectsAmazon($project_id, $destination_lang){

        // $input = $request->all();
        // $project_id = $input['project'];
        // $destination_lang = $input['language'];
        /* Now Change INTO  MAIN HTML SOURCE CODE */

        /* Html tag Name Array */
        $TagArray = [];
        $TagArray['</strong>'] = '|/strong|';
        $TagArray['<strong>'] = '|strong|';
        $TagArray['</aside>'] = '|/aside|';
        $TagArray['<aside>'] = '|aside|';
        $TagArray['</big>'] = '|/big|';
        $TagArray['<big>'] = '|big|';
        $TagArray['</a>'] = '|/a3|';
        $TagArray['</b>'] = '|/b|';
        $TagArray['</u>'] = '|/u|';
        $TagArray['<u>'] = '|u|';
        $TagArray['<b>'] = '|b|';
        $TagArray['<img '] = '|img|';
        $TagArray['</span>'] = '|/span|';

        $TagArraystart=[];
        $TagArraystart['<a '] = '|a2|';
        $TagArraystart['<span'] = '|span|';
      
        $handleclassArray = [];
        $handleclassArray['|a2|']='|a2#|';
        $handleclassArray['|span|']='|span#|';


        $language = LanguageList::where('id',$destination_lang)->first();
        $langCode = $language['sortname'];  // Destination Language Code 

        $ProjectDetails = Projects::where('id',$project_id)->first();

        /* Source Language Details */
        $SourceLanguageDetails = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        $SourceLanguageCode = $SourceLanguageDetails['sortname'];  // Source Language Code
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->get();
   
       $StringCorrections = []; 
       foreach($ProjectStringCorrectionsDoNot as $ProjectString){
           $StringCorrections[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
       }

       $ProjectStringCorrectionsAlways = [];
       $MatchParagraphDetails = [];
       $ParagraphDetails = [];
       $mainReplace_arr = [];
       $StringAlways = [];
       $replace_arr = [];
       $search_arr = [];
       $TagDetails = [];
       $FirstData = [];
       $LastData = [];

        if(count($StringCorrections)>0){
            
            foreach($StringCorrections as $string){
                $search_arr[]  =  $string['do_not_translate_string'];
            }
            // if(count($search_arr)>0){
            //     foreach($search_arr as $search_txt){
            //         $replace_arr[$search_txt] = uniqid();//'<cite>'.$search_txt."</cite>";
            //     }
            // }

            // dd($replace_arr);
            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();

            for($i=0; $i<count($search_arr); $i++){
                foreach($ParagraphDetails as $line){
                    $stringOriginal = $line['original_data'];
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);

                    if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
            }
            
            foreach($StringCorrections as $uniuqe){
                $DoNotTranslateData = $uniuqe['do_not_translate_string'];

                $ExplodeData = explode(" ",$DoNotTranslateData);
                
                $length = sizeof($ExplodeData);

                $FirstData[] = $ExplodeData[0];
                $LastData[] = $ExplodeData[$length-1];
            }

            foreach($MatchParagraphDetails as $line){

                $i=0;
                for($i=0; $i<count($FirstData); $i++){
                    if (strpos($line['original_data'], $FirstData[$i]) !== false && strpos($line['original_data'], $LastData[$i]) !== false) {
                        
                        $Paragraph = ProjectData::where('id',$line['id'])->first();

                        $new_str = $Paragraph['original_data'];

                        foreach($TagArray as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }
                        foreach($TagArraystart as $key => $val){
                            $new_str = str_replace($val, $key, $new_str);
                        }

                        $new_str = str_replace($FirstData[$i], '<cite>'.$FirstData[$i], $new_str);
                        $new_str = str_replace($LastData[$i], $LastData[$i].'</cite>', $new_str);

                        $FirstPosition = strpos($new_str,'<cite>');
                        $LastPosition = strpos($new_str,'</cite>');

                        $SubStr = substr($new_str,$FirstPosition,$LastPosition);

                        
                        $new_str = str_replace($SubStr, 'ce32f2f323', $new_str);
                        // dd($new_str);

                        // $new_str = str_replace('ce32f2f323', $SubStr, $new_str);

                        
                        
                        // foreach($TagArray as $key => $val){
                        //     $new_str = str_replace($val, $key, $new_str);
                        // }
                        // foreach($TagArraystart as $key => $val){
                        //     $new_str = str_replace($val, $key, $new_str);
                        // }
                        // dd($new_str);
                        $updatedParagraph = $this->AmazonTranslateApi($SourceLanguageCode, $langCode, $new_str); // Amazon Api Call

                        $updatedParagraph = str_replace('ce32f2f323', $SubStr, $updatedParagraph);

                        $updatedParagraph = str_replace('<cite>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }

                        $TextCountString = strip_tags($new_str);

                        $TextCountString=$this->ExceptionalChange($TextCountString);

                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);
                
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        $Paragraph->update([
                            'translated_data' => $updatedParagraph,
                        ]);     

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });

                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                        
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });
 

        $ProjectStringCorrectionsAlways = [];
        $MatchParagraphDetails = [];
        $ParagraphDetails = [];
        $mainReplace_arr = [];
        $StringAlways = [];
        $replace_arr = [];
        $search_arr = [];
        $TagDetails = [];
        $FirstData = [];
        $LastData = [];

        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->get();
   
        $StringCorrections = []; 
        
        foreach($ProjectStringCorrectionsAlways as $ProjectString){

            $StringAlways[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }

        if(count($StringAlways)>0){
            
            foreach($StringAlways as $string){

                $search_arr[]  =  $string['do_not_translate_string'];
                $mainReplace_arr[] = '<cite>'.$string['always_translate_as_string']."</cite>";
            }
            $MatchParagraphDetails = [];
            $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->get();
            
            for($i=0; $i<count($search_arr); $i++){
                
                foreach($ParagraphDetails as $line){

                    $stringOriginal = $line['original_data'];
                    foreach($TagArray as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }     
                    foreach($TagArraystart as $key => $val){
                        $stringOriginal = str_replace($val, $key, $stringOriginal);
                    }    
                    $stringOriginal = str_replace("\n","",$stringOriginal);
                    
                    $stringOriginal = strip_tags($stringOriginal);

                    if (strpos($stringOriginal, $search_arr[$i]) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
            }
           
            foreach($MatchParagraphDetails as $line){
                $i=0;
                for($i=0; $i<count($search_arr); $i++){
                    $new_str = $line['original_data'];
                    foreach($TagArray as $key => $val){
                        $new_str = str_replace($val, $key, $new_str); 
                    }
                    foreach($TagArraystart as $key => $val){
                        $new_str = str_replace($val, $key, $new_str);
                    }

                    preg_match_all('~<([^/][^>]*?)>~' ,  $new_str, $TagDetails); // Tag Details Start

                    preg_match_all('~<([</][^>]*?)>~' ,  $new_str, $TagDetailsEnd); // Tag Details End
                    
                    $new_str = strip_tags($new_str);

                    if (strpos($new_str, $search_arr[$i]) !== false ) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        
                        $new_str = str_replace($search_arr[$i], $mainReplace_arr[$i], $new_str);

                        $FirstPosition = strpos($new_str,'<cite>');
                        $LastPosition = strpos($new_str,'</cite>');

                        $SubStr = substr($new_str,$FirstPosition,$LastPosition);

                        
                        $new_str = str_replace($SubStr, 'ce32f2f323', $new_str);

                        // dd($new_str);

                        $updatedParagraph = $this->AmazonTranslateApi($SourceLanguageCode, $langCode, $new_str); // Amazon Api Call

                        $updatedParagraph = str_replace('ce32f2f323', $SubStr, $updatedParagraph);
                       
                        $updatedParagraph = str_replace('<cite>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                        $ReplaceString = $mainReplace_arr[$i];

                        $ReplaceString = str_replace('<cite>',"",$ReplaceString);
                        $ReplaceString = str_replace("</cite>","",$ReplaceString);

                        $ExplodeData = explode(" ",$ReplaceString);
                    
                        $length = sizeof($ExplodeData);
        
                        $FirstData = $ExplodeData[0];
                        $LastData = $ExplodeData[$length-1];

                        for($j=0; $j<count($TagDetails[0]); $j++){
                    
                            if (strpos($updatedParagraph, $TagDetails[0][$j]) == false ){
                                $updatedParagraph = str_replace($FirstData,$TagDetails[0][$j].$FirstData,$updatedParagraph);
                                
                                foreach($TagDetailsEnd[1] as $key => $TagEnd){
                                    // $unsetIndex = $key;
                                    $TagEnd = explode("/",$TagEnd);
                                    if(strpos($updatedParagraph, '<'.$TagEnd[1]) !== false ){
                                        $updatedParagraph = str_replace($LastData, $LastData.'</'.$TagEnd[1].'>',$updatedParagraph);
                                        unset($TagDetailsEnd[1][$key]);        
                                    }
                                }
                            }
                        }

                        foreach($TagArray as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }
                        foreach($TagArraystart as $key => $val){
                            $updatedParagraph = str_replace($key, $val, $updatedParagraph);
                        }

                        $TextCountString = strip_tags($new_str);

                        $TextCountString=$this->ExceptionalChange($TextCountString);
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);

                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $destination_lang);

                        $Paragraph->update([
    
                            'translated_data' => $updatedParagraph,
    
                        ]);  

                        DB::table('data_version')->where('paragraph_id', $Paragraph['id'])
                        ->chunkById(100, function ($DataVersionDetails) {
                            foreach ($DataVersionDetails as $Data) {
                                DB::table('data_version')
                                    ->where('id', $Data->id)
                                    ->update(['status' => 0]);
                            }
                        });

                        // /* Insert Into Data Version Table */
                        $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', 1);
                         
                    }
                }
            }
        }

        DB::table('projects_string_corrections')->where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)
            ->chunkById(100, function ($projects_string_corrections) {
                foreach ($projects_string_corrections as $string) {
                    DB::table('projects_string_corrections')
                        ->where('id', $string->id)
                        ->update(['status' => 1]);
                }
            });

        /* The ABove ALl codes are change IN DB */


       /* List Of All the effected paragraphs */

       $MatchParagraphDetails = [];
       $ProofreadingAssociativeDetails = []; 
        $ParagraphDetails = ProjectData::where('p_id',$project_id)->where('language_id',$destination_lang)->where('status',0)->get();
        //    foreach($ParagraphDetails as $line){
        //        $stringOriginal = html_entity_decode($line['content_original_data']);
        //        foreach($TagArray as $key => $val){
        //            $stringOriginal = str_replace($val, $key, $stringOriginal);
        //        }     
        //        foreach($TagArraystart as $key => $val){
        //            $stringOriginal = str_replace($val, $key, $stringOriginal);
        //        }    
        //        $stringOriginal = str_replace("\n","",$stringOriginal);
            
        //        $stringOriginal = strip_tags($stringOriginal);

        //        if (strpos($stringOriginal, $DoNotString) !== false) {
        //            $MatchParagraphDetails[] = $line;
        //        }    
        //    }

       foreach($ParagraphDetails as $MPD){
           $ProofreadingAssociativeDetails[] = ProofreadingAssociative::where('paragraph_id',$MPD['id'])->where('language_id',$destination_lang)->get();
       }

       foreach($ProofreadingAssociativeDetails as $PRADetails){

           foreach($PRADetails as $PRAD){

               $SourceCodeDetails = SourceCode::where('id', $PRAD['source_code_id'])->first();

               $OriginalHtmlSourceCode = html_entity_decode($SourceCodeDetails['html_code']);

               // dd($OriginalHtmlSourceCode);

               foreach($TagArray as $key => $val){
                   $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
               }

               foreach($TagArraystart as $key => $val){
                   $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
               }

               $TranslatedData = [];
               /* Fetch paragraph ids */
               $ProofreadingAssociativeDetails = ProofreadingAssociative::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->get();
               foreach($ProofreadingAssociativeDetails as $Associative){

                   $TranslatedData[] = ProjectData::where('id',$Associative['paragraph_id'])->where('p_id',$ProjectDetails['id'])->where('language_id',$destination_lang)->first();
                   
               }

               $OriginalData = [];
               $TranslatedOriginalData = [];
           
               foreach($TranslatedData as $data){
                   $Replace[$data['original_data']] =  $data['translated_data'];
               }
                       
               array_multisort(array_map('strlen', $Replace), $Replace);
               $Replace = array_reverse($Replace);

               foreach($Replace as $key=>$val){
                   $OriginalData[] = $key;
                   $TranslatedOriginalData[] = $val;
               }
       
               $doc = new \DOMDocument();
               libxml_use_internal_errors(true);
               $doc->loadHTML($OriginalHtmlSourceCode);
               $htmls = $doc->getElementsByTagName('html');
               
               if($htmls->length > 0)
               {
                   $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
               }
               
               foreach($TagArray as $key => $val){
                   $new_html=str_replace($val, $key, $new_html);
               }

               foreach($TagArraystart as $key => $val){
                   $new_html=str_replace($val, $key, $new_html);
               }

               /* update Into DB full Translated data */

               $new_html = $this->ExceptionalChange($new_html);

               $SourceCodeDetails->update([
                   'translated_html_code' => $new_html,
               ]);
           }
       }
       
        return back();
    }
}
 