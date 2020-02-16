<?php
namespace App\Http\Controllers\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MyProject;
use App\User;
use App\Models\LanguageList;
use App\Models\LanguagePair;
use App\Models\Projects;
use App\Models\ProjectLanguages;
use App\Models\ProjectStringCorrections; 
use App\Models\ProjectCatagories;
use App\Models\ProjectData; 
use App\Models\TranslatedData;
use App\Models\SourceCode;
use App\Models\Subscription;
use App\Models\Plans; 
use App\Models\SubscriptionsHistory;
use App\Models\FreeCredits;
use Validator;
use Storage;
use Auth;
use DB;
use File;
use Response;
use Mpdf\Mpdf;
use DateTime;

use Mail;
use App\Mail\Email;

use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;


use \Convertio\Convertio;
use \Convertio\Exceptions\APIException;
use \Convertio\Exceptions\CURLException;
use \CloudConvert\Api;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
set_time_limit(3600);
class MyProjectController extends Controller
{
   
    /* Get language List */
    public function GetLanguagePair(Request $request) {
        $input = $request->input(); 
        $language_pair_list= array();
        $language_pair_list1 = DB::table('language_pair')->where('from_language',$input['id'])->get();
        foreach($language_pair_list1 as $list){
            $name =  LanguageList::where('id',$list->to_language)->first();
            $neslanguage_pair_list['name'] = $name['name'];
            $neslanguage_pair_list['id'] = $name['id'];
            $language_pair_list[] = $neslanguage_pair_list;
        }
        return $language_pair_list; 
    }

    /* Projects List Form*/

    public function MyProjectsList(Request $request){
        /* Project Details */

        
        $input = $request->input();
        $haveDataCheck = Projects::where('user_id',Auth::user()->id)->where('project_view',1)->orderBy('id','DESC')->count();
       
        if(empty($input['ProjectNameSearch'])){
            
            $Projects = Projects::where('user_id',Auth::user()->id)->where('project_view',1)->orderBy('id','DESC')->paginate(config('constants.PER_PAGE'));
        }
        else{
            $Projects = Projects::where('user_id',Auth::user()->id)->where('project_view',1)->where('project_name', 'like', '%'. $input['ProjectNameSearch'] .'%')->paginate(config('constants.PER_PAGE'));
        }
        /* Page Pagination */
         $results = $Projects;
         $data = array();
         $currentPage = LengthAwarePaginator::resolveCurrentPage();
         $collection = new Collection($results);
         $per_page = 5;
         $currentPageResults = $collection->slice(($currentPage-1) * $per_page, $per_page)->all();
         $data['results'] = new LengthAwarePaginator($currentPageResults, count($collection), $per_page);
         $data['results']->setPath($request->url());

        /* Fetch according All details For Projects Lists Page */
        foreach($Projects as $project){
            /* Project Origin language Name */
            $CurrentLanguageName = LanguageList::where('id',$project['current_language_id'])->first();
            $project['CurrentLanguageName'] = $CurrentLanguageName['name'];

            /* Destination Language Name*/
            $DestinationLanguageNameArray = array();
            $DestinationLanguageStatusArray = array();
            $DestinationLanguagesIdArray = array();
            $ProjectApprovalParagraphs = array();
            $ProjectPendingParagraphs = array();
            $ProjectTotalParagraphs = array();
            
            $TotalTranslatedTextCount = array();
            $TranslatedTextCount = array();
            $OriginalTextCount = array();
            $OriginalTextCountWord = array();

           



            $DestinationLanguageList = ProjectLanguages::where('p_id',$project['id'])->get();
          

          
            foreach($DestinationLanguageList as $DestinationLanguage){
                $OriginalTextCount1 = 0;
                $OriginalTextCountWord1 = 0;
                $TranslatedTextCount1 = 0;
               
                $DestinationLanguageName = LanguageList::where('id',$DestinationLanguage['language_id'])->first();
                $DestinationLanguageNameArray[] = $DestinationLanguageName['name'];
                $DestinationLanguageStatusArray[] =$DestinationLanguage['visibility_status'];
                $DestinationLanguagesIdArray[] = $DestinationLanguage['language_id'];

                /* */
                $ProjectApprovalParagraphs[] = ProjectData::where('p_id',$project['id'])->where('status',1)->where('language_id',$DestinationLanguage['language_id'])->count();
                $ProjectPendingParagraphs[] = ProjectData::where('p_id',$project['id'])->where('status',0)->where('language_id',$DestinationLanguage['language_id'])->count();
                $ProjectTotalParagraphs[] = ProjectData::where('p_id',$project['id'])->where('language_id',$DestinationLanguage['language_id'])->count();

                /* */
                $Result = DB::table('text_count')->select( DB::raw('SUM(length) as length'))->where('user_id',$project['user_id'])->where('p_id',$project['id'])->where('from_language',$project['current_language_id'])->where('to_language',$DestinationLanguage['language_id'])->get();
                $TotalTranslatedTextCount[] = $Result[0]->length;

                /* */
                // $SourceCodeDetails = SourceCode::where('p_id',$project['id'])->where('lang_code',$DestinationLanguage['language_id'])->first();
                // $OriginalTextCount[] = strlen(strip_tags($SourceCodeDetails['html_code']));
                // $TranslatedTextCount[] = strlen(strip_tags($SourceCodeDetails['translated_html_code']));

                $ProjectDataDetails = ProjectData::where('p_id',$project['id'])->where('language_id',$DestinationLanguage['language_id'])->get();
                foreach($ProjectDataDetails as $Line){
                    // $OriginalTextCount[] = strlen($Line['content_original_data']);
                    // $TranslatedTextCount[] = strlen($Line['content_translated_data']);
                    $OriginalTextCount1 += strlen($Line['content_original_data']);
                    $OriginalTextCountWord1 += str_word_count($Line['content_original_data']);
                    $TranslatedTextCount1 += strlen($Line['content_translated_data']);
                }
                
                $OriginalTextCount[] = $OriginalTextCount1;
                $OriginalTextCountWord[] = $OriginalTextCountWord1;
                $TranslatedTextCount[] = $TranslatedTextCount1;
             
            }
           
            $project['DestinationLanguageName'] = $DestinationLanguageNameArray;
            $project['DestinationLanguageStatus'] = $DestinationLanguageStatusArray;
            $project['DestinationLanguagesId'] = $DestinationLanguagesIdArray;
            $project['number'] = count($DestinationLanguageNameArray);

            /* */
            $project['ProjectApprovalParagraphs'] = $ProjectApprovalParagraphs;
            $project['ProjectPendingParagraphs'] = $ProjectPendingParagraphs;
            $project['ProjectTotalParagraphs'] = $ProjectTotalParagraphs;

            /* */
            
            //dd(array_sum($TranslatedTextCount));
            $project['TotalTranslatedTextCount'] = $TotalTranslatedTextCount;
            $project['OriginalTextCount'] = $OriginalTextCount;
            $project['OriginalTextCountWord'] = $OriginalTextCountWord;
            $project['TranslatedTextCount'] = $TranslatedTextCount;
            //dd($project);

        }
        
        if(empty($Projects)){
            $Projects=null;
        }

        $TotalCredit = FreeCredits::TotalCredit();

        return view('user.myProject.my_project_list',compact('TotalCredit','Projects','results','haveDataCheck'));
    }

   

    /* Add New Project Form  Web Site Project*/

    public function AddNewProject($id=null){

         /* Project Details Form Projects table */
         $Project = Projects::where('id',$id)->first(); // Which is select for update 

         /* All the Projects for select in string corrections list */
         $ProjectLists = Projects::where('user_id',Auth::user()->id)->where('project_type',1)->get();
         $TotalNumberProjects = count($ProjectLists);
         if($TotalNumberProjects==0){
             $ProjectLists=null;
         }

         /* List Of all Project category*/
         $ProjectCatagories = ProjectCatagories::all();
 
         /* All the Language list from language_list table */
         $LanguagesList = LanguagePair::distinct('from_language')->get('from_language');
         foreach($LanguagesList as $language){
             $name = LanguageList::where('id',$language['from_language'])->first();
             $language['name']=$name['name'];
         }
      
         /* Selected Destination language */
         $DestinationProjectLanguage = ProjectLanguages::where('p_id',$id)->get();

        /* Lanugae Pair List As per chhooed current language*/
        $LanguagePairList = LanguagePair::where('from_language',$Project['current_language_id'])->get();
        foreach($LanguagePairList as $LanPair){
            $LanguageName = LanguageList::where('id',$LanPair['to_language'])->first();
            $Name = $LanguageName['name'];
            $LanPair['name']=$Name;
        }

        /* Destination Language Name */
        foreach($DestinationProjectLanguage as $DesLanguage){
            $LanguageName = LanguageList::where('id',$DesLanguage['language_id'])->first();
            $Name = $LanguageName['name'];
            $DesLanguage['name']=$Name;
        }
 
         /* translated Time check */
         $translatedTime = $Project['translated_time'];
         if($translatedTime==null){
             $DeleteOption = 1; // 1 for delete okay
         }
         else{
             $currentTime = \Carbon\Carbon::now();
             $diff_in_days = $currentTime->diffInDays($translatedTime);
             if($diff_in_days>60){
                 $DeleteOption =1 ;
             }
             else{
                 $DeleteOption = 0; // 0 for not delete    
             }
         }

        $SubscriptionDetails = SubscriptionsHistory::where('user_id',Auth::user()->id)->where('status','Y')->first();
        $PlanDetails = Plans::where('stripe_plan_id',$SubscriptionDetails['stripe_plan'])->first();
        $NumberOfLanguage = $PlanDetails['max_languages'];

         /* Selected correct string project */

         $ProjectStringCorrections = ProjectStringCorrections::where('p_id',$id)->get();
         $arrayPSC=array();
         foreach($ProjectStringCorrections as $PSC){
            $arrayPSC[]=$PSC['others_pid'];
         }

         $TotalCredit = FreeCredits::TotalCredit();
        if(!empty($id)){
            /* Update Section */
          
            return view('user.myProject.add_new_project',compact('TotalCredit','Project','LanguagesList','ProjectCatagories','ProjectLists','DestinationProjectLanguage','LanguagePairList','DeleteOption','arrayPSC','NumberOfLanguage'));
        }
        else{
            /* New Section */
            $ProjectDetails = '';
            $ProjectLanguage='';
            $Project=null;
            $ProjectStringCorrections='';
            $DeleteOption=1;


           

            return view('user.myProject.add_new_project',compact('TotalCredit','Project','LanguagesList','ProjectCatagories','ProjectLists','DestinationProjectLanguage','ProjectStringCorrections','DeleteOption','NumberOfLanguage'));
        }
    }

    /* Submit Project WEB PROJECT*/

    public function SubmitProject(Request $request){
        $input = $request->input();
        
        $regex = '/^(https:\/\/)?(http:\/\/)?(www.)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $validator = Validator::make($request->all(), [
             'project_name'  => 'required' ,
              'website_url'  => 'regex:' . $regex,//'required|active_url' ,
                //'website_url'  => 'required',//'required|active_url' ,
             'current_website_language'  => 'required' ,
             'project_category' => 'required',
             'Visibility' => 'required',
        ]);
        if($validator->fails()){ 
            /* return back*/
            return back()->withInput($input)->withErrors($validator);
        }
        else{

            $url = $input['website_url'];

            $url =  str_replace("https://", "", $url);
            $url =  str_replace("http://", "", $url);
            $url =  str_replace("www.", "", $url);

           

            $website_url = explode("/",$url);
            // dd($website_url);

            /* Check If Already Project Have Or Not */
            $ProjectDetails = Projects::where('website_url',$website_url[0])->first();

           
            if(empty($input['id'])){
                if(!empty($ProjectDetails)){
                    return redirect()->route('my_project')->with('messageError' , 'Already Added!');
                }
            }
            

            /* metadata_translation And media_translation check */
            if(!empty($input['metadata_translation'])){
                $metadata_translation=1;
            }
            else{
                $metadata_translation=0;
            }
            if(!empty($input['media_translation'])){ 
                $media_translation=1;
            }
            else{
                $media_translation=0;
            }

            if(!empty($input['AddLanguages'])){
                $NesArray = explode("," ,$input['AddLanguages'][0]);
            }
            $AddLanguages = $NesArray;
 
            /* If not Have Project Id then Create New One */
            if(empty($input['id'])){
                 /* Add Here */

                 /* First Add the Project Details In projects table */
                        $myProject = new Projects();
                     
                        $myProject->user_id = Auth::user()->id;
                        $myProject->project_name = $input['project_name'];
                        $myProject->status = 1;
                        $myProject->website_url = $website_url[0];//$input['website_url'];
                        $myProject->project_category = $input['project_category'];
                        $myProject->project_type = 1; // 1 For WebSite Project
                        $myProject->project_view = 1; // 1 = Display The Project In User Dash Board List
                        $api_key=$myProject->api_key = base64_encode($myProject['id']).'-'.base64_encode($input['website_url']);
                        $myProject->current_language_id = $input['current_website_language'];
                        $myProject->metadata_translation = $metadata_translation;
                        $myProject->media_translation = $media_translation;
                        $myProject->translated_time=null;
                        $myProject->save();
    
                /* Add the Project Destination Languages Details in project_language table */ 
                        /* Project Language Section */
                        if(!empty($AddLanguages)){
                            for($i=0; $i<sizeof($AddLanguages); $i++){
                                $ProjectLanguage = new ProjectLanguages();
                                $ProjectLanguage->p_id = $myProject['id'];
                                $ProjectLanguage->user_id = Auth::user()->id;
                                $ProjectLanguage->language_id = $AddLanguages[$i];
                                $ProjectLanguage->visibility_status = $input['Visibility'][$i];//$input['visibility_status'];
                                $ProjectLanguage->save();
                            }
                        }

                /* Check Others Projects string corrections select or not then insert into DB */
                        // if(!empty($input['project_select'])){
                        //     /* Then insert into DB */
                        //     for($i=0; $i<sizeof($input['project_select']); $i++){
                        //         $ProjectStringCorrections = new ProjectStringCorrections();
                        //         $ProjectStringCorrections->user_id=Auth::user()->id;
                        //         $ProjectStringCorrections->p_id=$myProject['id'];
                        //         $ProjectStringCorrections->others_pid=$input['project_select'][$i];
                        //         $ProjectStringCorrections->status=1;
                        //         $ProjectStringCorrections->save();
                        //     }
                        // }
                        session()->put('id', $myProject['id']);
                        $DeleteOption=1;
                        return redirect()->route('my_project');
                        //return back()->with('message' , 'Project added successfully Please Copy The APi Key!')->with('api_key',$api_key)->with('DeleteOption',$DeleteOption);
            }
            /* Update The existing Project */
            else{
                /* Update Here */
                $ProjectDetails = Projects::where('id',$input['id'])->first();  // From Projects Table 
                $ProjectLanguage = ProjectLanguages::where('p_id',$ProjectDetails['id'])->get();
                if(empty($ProjectLanguage)){
                    $ProjectLanguage = '';
                }

                /* Update ProjectDetails In The Projects Table */
                if(!empty($ProjectDetails)){
                    $ProjectDetails->update([
                        'project_name' => $input['project_name'],
                        'project_category' => $input['project_category'],
                        'website_url' => $website_url[0],//$input['website_url'],
                        'current_language_id' => $input['current_website_language'],
                        'metadata_translation' =>  $metadata_translation,
                        'media_translation' => $media_translation,
                    ]);
                }

                /* Update Or create Data On ProjectLanguage Table */

                /* delete Old Project Language List */
                $ProjectLanguage = ProjectLanguages::where('p_id',$ProjectDetails['id'])->delete();
                if(!empty($AddLanguages)){
                  
                    for($i=0; $i<sizeof($AddLanguages); $i++){
                        $ProjectLanguage = new ProjectLanguages();
                        $ProjectLanguage->p_id = $ProjectDetails['id'];
                        $ProjectLanguage->user_id = Auth::user()->id;
                        $ProjectLanguage->language_id = $AddLanguages[$i];
                        $ProjectLanguage->visibility_status = $input['Visibility'][$i];
                        $ProjectLanguage->save();

                    }
                }

                /* Update here String Correction Project */

                /* First Delete all previous selected project By using project Id */

                ProjectStringCorrections::where('p_id',$input['id'])->delete();

                /* Update New selected string correction Project*/

                // if(!empty($input['project_select'])){
                //     /* Then insert into DB */
                //     for($i=0; $i<sizeof($input['project_select']); $i++){
                //         $ProjectStringCorrections = new ProjectStringCorrections();
                //         $ProjectStringCorrections->user_id=Auth::user()->id;
                //         $ProjectStringCorrections->p_id=$input['id'];
                //         $ProjectStringCorrections->others_pid=$input['project_select'][$i];
                //         $ProjectStringCorrections->status=1;
                //         $ProjectStringCorrections->save();
                //     }
                // }
                return redirect()->route('my_project')->with('message' , 'SuccessFully Added!');
                //return back()->with('message' , 'Project Updated Successfully!');
            }
        }
    }

    /* Start Document Project Section */

      /* Add New Project Form Document Project */
      public function AddNewDocProject($id=null){

        /* Project Details Form Projects table */
        $Project = Projects::where('id',$id)->first(); // Which is select for update 

        /* All the Projects for select in string corrections list */
        $ProjectLists = Projects::where('user_id',Auth::user()->id)->where('project_type',2)->get();
        $TotalNumberProjects = count($ProjectLists);

        if($TotalNumberProjects==0){
            $ProjectLists=null; 
        }

        /* List Of all Project category*/
        $ProjectCatagories = ProjectCatagories::all();

        /* All the Language list from language_list table */
        $LanguagesList = LanguagePair::distinct('from_language')->get('from_language');
        foreach($LanguagesList as $language){
            $name = LanguageList::where('id',$language['from_language'])->first();
            $language['name']=$name['name'];
        }


        /* Selected Destination language */
        $DestinationProjectLanguage = ProjectLanguages::where('p_id',$id)->get();

        foreach($DestinationProjectLanguage as $DesLanguage){
            $LanguageName = LanguageList::where('id',$DesLanguage['language_id'])->first();
            $Name = $LanguageName['name'];
            $sortname = $LanguageName['sortname'];
            $DesLanguage['name']=$Name;
            $DesLanguage['sortname']=$sortname;
        }


        /* translated Time check */
        $translatedTime = $Project['translated_time'];
        if($translatedTime==null){
            $option = 1; // 1 for delete okay
        }
        else{
            $currentTime = Carbon\Carbon::now();
            $diff_in_days = $currentTime->diffInDays($translatedTime);
            if($diff_in_days>60){
                $option =1 ;
            }
            else{
                $option = 0; // 0 for not delete    
            }
        }
          /* translated Time check */
          $translatedTime = $Project['translated_time'];
          if($translatedTime==null){
              $DeleteOption = 1; // 1 for delete okay
          }
          else{
              $currentTime = \Carbon\Carbon::now();
              $diff_in_days = $currentTime->diffInDays($translatedTime);
              if($diff_in_days>60){
                  $DeleteOption =1 ;
              }
              else{
                  $DeleteOption = 0; // 0 for not delete    
              }
          }

          $LanguagePairList = LanguagePair::where('from_language',$Project['current_language_id'])->get();
          foreach($LanguagePairList as $LanPair){
              $LanguageName = LanguageList::where('id',$LanPair['to_language'])->first();
              $Name = $LanguageName['name'];
              $LanPair['name']=$Name;
          }

           /* Selected correct string project */

         $ProjectStringCorrections = ProjectStringCorrections::where('p_id',$id)->get();
         $arrayPSC=array();
         foreach($ProjectStringCorrections as $PSC){
            $arrayPSC[]=$PSC['others_pid'];
         }
         $TotalCredit = FreeCredits::TotalCredit();

        if(!empty($id)){
            /* Update Section */
            
            $form_type='edit';
            $ProjectDetails = Projects::where('id',$id)->first(); // Which projects request to update */
       
            return view('user.myProject.add_new_doc_project',compact('TotalCredit','Project','LanguagesList','ProjectCatagories','ProjectLists','DestinationProjectLanguage','LanguagePairList','DeleteOption','arrayPSC'));
        }
        else{
            /* New Section */
            $form_type='';
            $ProjectDetails = '';
            $ProjectLanguage='';
            $Project='';
            $DestinationProjectLanguage='';
            $DeleteOption=1;
           
            return view('user.myProject.add_new_doc_project',compact('TotalCredit','Project','LanguagesList','ProjectCatagories','ProjectLists','DestinationProjectLanguage','DeleteOption'));
        }
    }



    /* Submit Document Project */

    public function SubmitDocProject(Request $request){ 
        $input = $request->input();
        $file = $request->file();
      
        $validator = Validator::make($request->all(), [
             'current_website_language'  => 'required',  // Current Language Of Document Project 
             'project_category' => 'required',
             'Visibility' => 'required',
        ]);
        if($validator->fails()){
            /* return back*/
            return back()->withInput($input)->withErrors($validator);
        }
        else{
              /* If not Have Project Id then Create New One */
             
            if(empty($input['id'])){
                
                /* Add Here */

                /* Documentation File Upload*/
                /* if txt => pdf if docx => pdf if doc => pdf if pdf okay all the tasks through api call*/

                if (!empty($request->file('upload_document_project'))){
                    
           
                    $DocumentName = $request->file('upload_document_project')->getClientOriginalName();
                    $extension = $request->file('upload_document_project')->getClientOriginalExtension(); // Original File Extension
                   
                    $documentation = 'documentation'.time().'.'.$request->file('upload_document_project')->getClientOriginalExtension();
                   
                    /* set path of the file */
                    $destinationPath = public_path('/assets/upload/user/project_documentation');
                    /* put the file on the destination profile_image*/
                    $request->file('upload_document_project')->move($destinationPath, $documentation);

                }
                else{
                    $documentation='';
                }

                if(!empty($input['AddLanguages'])){
                    $NesArray = explode("," ,$input['AddLanguages'][0]);
                }
                $AddLanguages = $NesArray;
    
                /* First Add the Project Details In projects table */
                       $myProject = new Projects();
                       $myProject->user_id = Auth::user()->id;
                       $myProject->project_name = $DocumentName;
                       $myProject->status = 1;
                       $myProject->extension =  $extension;
                       $myProject->project_category = $input['project_category'];
                       $myProject->project_type = 2; // 1 For WebSite Project // 2 For Document Project 
                       $myProject->documentation_name = $documentation;
                       $myProject->current_language_id = $input['current_website_language'];
                       $myProject->save();
                      

               /* Add the Project Destination Languages Details in project_language table */ 
                       /* Project Language Section */
                       if(!empty($AddLanguages)){
                           for($i=0; $i<sizeof($AddLanguages); $i++){
                               $ProjectLanguage = new ProjectLanguages();
                               $ProjectLanguage->p_id = $myProject['id'];
                               $ProjectLanguage->user_id = Auth::user()->id;
                               $ProjectLanguage->language_id = $AddLanguages[$i];
                               $ProjectLanguage->visibility_status = $input['Visibility'][$i];//$input['visibility_status'];
                               $ProjectLanguage->save();
                           }
                       }
                       $source_lang = LanguageList::where('id',$input['current_website_language'])->first();

                /* Read File Data and Into DB */
                        $FileExtension = $request->file('upload_document_project')->getClientOriginalExtension();
                        /* For Doc and Docx */
                        if($FileExtension == 'docx'){

                            foreach($AddLanguages as $addLangVal)
                            {
                                $LanguageList=LanguageList::where('id',$addLangVal)->first();
                                /* First Save The Original data into DD project_data table */
                             

                                $data = $this->read_docx($documentation,$LanguageList['sortname'],$LanguageList['id'],$myProject['id'],$source_lang['sortname']);
                            }
                        }
                        if($FileExtension == 'pdf'){
                            
                             $fileName = public_path('/assets/upload/user/project_documentation/'.$documentation); // Its Original PDF File 

                             $name = explode(".",$documentation);
                             $uniqName = $name[0].'.docx';

                             $api = new Api("VlOvwyxcuWkkmIzSmjKKUZepBAnNiqavflyC8EechOjmv8SqNqWHEmSN8baajZ0e");
 
                             $api->convert([
                                 'inputformat' => 'pdf',
                                 'outputformat' => 'docx',
                                 'input' => 'upload',
                                 'file' => fopen($fileName, 'r'),
                             ])
                             ->wait()
                             ->download( public_path('/assets/upload/user/project_documentation/'.$uniqName));

                            foreach($AddLanguages as $addLangVal){

                                $LanguageList=LanguageList::where('id',$addLangVal)->first();


                                //$data = $this->read_docx($uniqName,$LanguageList['sortname'],$LanguageList['id'],$myProject['id'],$source_lang['sortname']);

                                $data = $this->read_docx($uniqName,$LanguageList['sortname'],$LanguageList['id'],$myProject['id'],$source_lang['sortname']);

                            }

                            
                        }

                        $myProject->update([

                            'translated_time' => \Carbon\Carbon::now(),

                        ]);
                        /* Delete The Original Pdf File We have main original pdf to DOcx file */

                        if($extension === 'pdf'){

                            unlink(public_path('/assets/upload/user/project_documentation/'.$documentation));
                        }
                        
                       session()->put('id', $myProject['id']);
                       session()->put('documentation_name', $documentation); 


                       /* Mail Send */
                       $email = Auth::user()->email;
                       $data1 = [
                            
                            'message' => 'Your file is successfully translated ! Enjoy with TRANSLUCIO', 
                            'name' => ucfirst(Auth::user()->name),
                            
                        ]; 
            

                        // Mail::to($email)->send(new Email($data1));
                        $myProject->update([
                            'project_view' => 1,
                        ]);
                         
                        return back()->with('message' , 'Project added successfully!');
           }
           else{
               //Update Project Here */
           }
        }
    }

    /* Delete Any Project */
    public function DeleteProject(Request $request){
        $input = $request->input();

        $DeleteOption=0;

        $Project = Projects::where('id',$input['id'])->first();
         /* translated Time check */

         $translatedTime = $Project['translated_time'];
         if($translatedTime==null){
             $DeleteOption = 1; // 1 for delete okay
         }
         else{
             $currentTime = \Carbon\Carbon::now();
             $diff_in_days = $currentTime->diffInDays($translatedTime);
             if($diff_in_days>60){
                 $DeleteOption =1 ;
             }
             else{
                 $DeleteOption = 0; // 0 for not delete    
             }
         }

         if($DeleteOption == 1){
             // OKAY DELETE ALL '''
             Projects::where('id',$input['id'])->delete(); // From Project Table

             ProjectStringCorrections::where('p_id',$input['id'])->delete();  // From String Correction Table 

             ProjectData::where('p_id',$input['id'])->delete();

             ProjectLanguages::where('p_id',$input['id'])->delete();

             return response()->json(array('success'=>true));
         }
         else{
            return response()->json(array('success'=>false));
         }
       
    }

    /* Download Document File */
    public function downloadFile(Request $request,$file_name){

        $file= public_path(). "/assets/upload/user/project_documentation/".$file_name;
        $headers = array(
                'Content-Type:application/octet-stream',
                );
        return response()->download($file, $file_name, $headers);
    }

    /* read_docx function Read the docx file in XMl format then tranlate and save in to DB */
    
    function read_docx($fileNameParam,$langCode,$langId,$projectId,$source_lang){

        $filename = public_path('/assets/upload/user/project_documentation/'.$fileNameParam);
        $destFile1=$langCode.'_'.$fileNameParam;
        $destFile=public_path('assets/upload/user/project_documentation/'.$destFile1);
        $details_arr=[];
        $details_arr_footer=[];
        $details_arr_header=[];
        $inArrayCheck = [];
      
        $striped_content = '';
        $content = '';
        $contentFooter = '';
        $contentHeader = '';
        $contentHeaderFooter = '';
        $header_arr=[];
        $footer_arr=[];

        /* For test */
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

            // print_r($header_arr);
            // print_r($footer_arr);
            // exit();

            // dd($mainList);
            // exit();
            // dd($contentHeader);


        /* End Test */


        

        /* This Below Code is the List of name header and footer where data have */

        // $zip = zip_open($filename);
        
        // if (!$zip || is_numeric($zip)) return false;

        // while ($zip_entry = zip_read($zip)) {
           
        //     if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
        //         $header_substr=substr(zip_entry_name($zip_entry),0,17);
           
        //     if ($header_substr!="word/_rels/header") continue;
        //         $raw_s=explode('/',zip_entry_name($zip_entry));
            
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
        // zip_close($zip);

        


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
    

         /* header Section */
        for($i=0; $i<sizeof($header_arr); $i++){

            $contentHeader = '';
            $details_arr_header = [];
            $CheckData = '';

            $zip = zip_open($filename);

            if (!$zip || is_numeric($zip)) return false;

            while ($zip_entry = zip_read($zip)) {
            
                if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

                if (zip_entry_name($zip_entry) != "word/".$header_arr[$i]) continue;

                $contentHeader.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                
                zip_entry_close($zip_entry);
            }

            zip_close($zip);  

            $arr = [];

            if(!empty($contentHeader) || $contentHeader != null)
            {

                $contentHeader = preg_replace('/<\s*w:p[^>]>/', "\C1 ", $contentHeader);
                $contentHeader = str_replace('</w:p>', "\C2 ", $contentHeader);
                $data=$contentHeader;
                $arr = explode("\C1 ",$data);
                $arr = explode("\C2 ",implode($arr));

                foreach($arr as $c){
                    $CheckData = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $c);
                    $CheckData = str_replace('</w:r></w:p>', "", $CheckData);
                    $CheckData = str_replace('<w:t>', "", $CheckData);
                    $CheckData = str_replace('<w:t xml:space="preserve">', "\C1 ", $CheckData);
                    $CheckData = str_replace('</w:t>', "", $CheckData);
        
                    $CheckData = str_replace("\r\n", "", $CheckData); // Replace The String Line \r\n 
        
                    $CheckData= $striped_content = strip_tags($CheckData);

                    // if(strip_tags($c) != null){
                    //     $details_arr_header[$c] = $c;
                    // }

                    if(strlen($CheckData)>0){

                        $details_arr_header[$c] = $c;

                    }
                }
            }
            

            /* INSERT INTO DB */

            if(!empty($details_arr_header) || $details_arr_header != null){

                foreach($details_arr_header as $res){
    
                    $ProjectData = new ProjectData();
                    $ProjectData->p_id = $projectId;
                    $ProjectData->original_data = $res;
                    
                    $trans_temp_data='';
                    $trans_temp_data=$this->testApi($res,$langCode,$source_lang);
            
                    $details_arr_header[$res]=$trans_temp_data;
                    $ProjectData->file_name = $header_arr[$i];
                    $ProjectData->data_section = 1 ; // 1 = Header
        
            
                    $ProjectData->language_id = $langId;
                    $ProjectData->translated_data = $trans_temp_data;
                   
                    $ProjectData->save();
                    
                }
            }
           
        }



        /* For Main Body Section */ 

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
           
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }

        zip_close($zip);

        $arr = [];

        /* Main Body Content */
        
        $content = preg_replace('/<\s*w:p[^>]>/', "\C1 ", $content);
        $content = str_replace('</w:p>', "\C2 ", $content);
        $data=$content;
        $arr = explode("\C1 ",$data);
        $arr = explode("\C2 ",implode($arr));
        $c1=0;
        foreach($arr as $c){

            if(strip_tags($c) != null){
                $details_arr[$c] = $c;
          
                $c1++;
            }
        }

        foreach($details_arr as $key=>$res){

            $ProjectData = new ProjectData(); 
            $ProjectData->p_id = $projectId;
            $ProjectData->original_data = $res;
            
            $trans_temp_data='';
            $trans_temp_data=$this->testApi($res,$langCode,$source_lang);
            $details_arr[$res]=$trans_temp_data;
            $ProjectData->file_name = "document.xml";
            $ProjectData->data_section = 2; // 2 = Content
            $ProjectData->language_id = $langId;
            $ProjectData->translated_data = $trans_temp_data;
           
            $ProjectData->save();
        }


        /* Footer Section */
        for($i=0; $i<sizeof($footer_arr); $i++){

            $contentFooter = '';
            $details_arr_footer = [];
            $CheckData = '';

            $zip = zip_open($filename);

            if (!$zip || is_numeric($zip)) return false;

            while ($zip_entry = zip_read($zip)) {
            
                if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

                if (zip_entry_name($zip_entry) != "word/".$footer_arr[$i]) continue;

                $contentFooter.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                
                zip_entry_close($zip_entry);
            }

            zip_close($zip);  

            $arr = [];

            if(!empty($contentFooter) || $contentFooter != null)
            {

                $contentFooter = preg_replace('/<\s*w:p[^>]>/', "\C1 ", $contentFooter);
                $contentFooter = str_replace('</w:p>', "\C2 ", $contentFooter);
                $data=$contentFooter;
                $arr = explode("\C1 ",$data);
                $arr = explode("\C2 ",implode($arr));

                foreach($arr as $c){
                    

                    $CheckData = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $c);
                    $CheckData = str_replace('</w:r></w:p>', "", $CheckData);
                    $CheckData = str_replace('<w:t>', "", $CheckData);
                    $CheckData = str_replace('<w:t xml:space="preserve">', "\C1 ", $CheckData);
                    $CheckData = str_replace('</w:t>', "", $CheckData);
        
                    $CheckData = str_replace("\r\n", "", $CheckData); // Replace The String Line \r\n 
        
                    $CheckData= $striped_content = strip_tags($CheckData);

                    if(strlen($CheckData)>0){
                        $details_arr_footer[$c] = $c;
                    }

                    // if(strip_tags($c) != null){
                    //     $details_arr_footer[$c] = $c;
                    // }
                }
            }

            /* INSERT INTO DB */

            if(!empty($details_arr_footer) || $details_arr_footer != null){

                foreach($details_arr_footer as $res){
    
                    $ProjectData = new ProjectData();
                    $ProjectData->p_id = $projectId;
                    $ProjectData->original_data = $res;
                    
                    $trans_temp_data='';
                    $trans_temp_data=$this->testApi($res,$langCode,$source_lang);
            
                    $details_arr_footer[$res]=$trans_temp_data;
                    $ProjectData->file_name = $footer_arr[$i];
                    $ProjectData->data_section = 3 ; // 3 = Footer
        
            
                    $ProjectData->language_id = $langId;
                    $ProjectData->translated_data = $trans_temp_data;
                   
                    $ProjectData->save();
                }
            }
        }
        // dd($contentFooter);

        /* Replace Section */

        $file = public_path('/assets/upload/user/project_documentation/'.$fileNameParam);
        $temp_file = public_path('/assets/upload/user/project_documentation/'.$destFile1);

        if(file_exists($temp_file))
        unlink($temp_file);
        copy($file,$temp_file);


        $zip = new \PhpOffice\PhpWord\Shared\ZipArchive;

        /* Header Section */
        for($i=0; $i<sizeof($header_arr); $i++){

            /* Fetch Header Content As per File Wise */

            $HeaderContent = ProjectData::where('p_id',$projectId)->where('file_name',$header_arr[$i])->where('data_section',1)->where('language_id',$langId)->get();

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


        /* Main Body Content */


        $fileToModify = '';
        $fileToModify = 'word/document.xml';
        if ($zip->open($temp_file) === TRUE){

            $MainBodyContent = ProjectData::where('p_id',$projectId)->where('file_name',"document.xml")->where('data_section',2)->where('language_id',$langId)->get();

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

            $FooterContent = ProjectData::where('p_id',$projectId)->where('file_name',$footer_arr[$i])->where('data_section',3)->where('language_id',$langId)->get();

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

      // ----------------file content translate and changes start--------------//

      public function testApi($test_data,$langCode,$source_lang)
    {
        
       
        //$test_data='hello world<w:t>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</w:t>';
        // 'https://api.deepl.com/v2/translate?text=Hello%20World. The chair is black.!&target_lang=DE&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357'
       // $test_data='Hello world <para>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</para>';
       
       $client = new \GuzzleHttp\Client();
       $options = [
           'form_params' => [
               "text" =>"<w:p>".$test_data."</w:p>"
               ]
           ]; 
        
        
        $request = $client->post('https://api.deepl.com/v2/translate?target_lang='.$langCode.'&tag_handling=xml&split_sentences=TRUE&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
        $response=$request->getBody();
        $res=json_decode($response);
        return $res->translations[0]->text;
    }
} 
