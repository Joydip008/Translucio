<?php
namespace App\Http\Controllers;
header('Access-Control-Allow-Origin: *');
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
use App\Models\StringCorrections;
use App\Models\WebhookEventsHistory;

use App\Models\TranslatedData;
use App\Models\SourceCode;
use App\Models\TextCount;
use App\Models\SubscriptionsHistory;
use App\Models\Plans;
use App\Models\ExtraChargeHistory;
use App\Models\DataVersion;
use Validator;
use Storage;
use Auth;
use DB;
use File;
use Arr;
use Mail;
use App\Mail\SubscribeNewPlan;
use App\Models\ChatData;

use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

//use Google\Cloud\Translate\TranslateClient;
set_time_limit(3600);

use Aws\Translate\TranslateClient; 
use Aws\Exception\AwsException;
class ApiController extends Controller
{
    /* Main Function Where Different Language Pair Wise Call 1. plaintext for Deepl 2. GoogleApi for google */
    public function LanguagePairWiseCall(Request $request){

        /* Data From ALoke The End */
        $input = $request->input();
        $data = [];

        /* Check Given url valid or not */
        // $regex = '/^(https:\/\/)?(http:\/\/)?(www.)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        $p1 = '/:\/\/(www[0-9]?\.)?(.[^/:]+)/i';

        /* First Validation Check */
        $validator = Validator::make($request->all(), [
            'url'  => 'required',//'regex:' . $regex,//'required|active_url' ,
            // 'text' => 'required',
        ]);
        /* Return back response to Validation Fail */
        if($validator->fails()){
            $data['data'] = $input;
            $data['msg'] = 'validation error';
            return $data;
        }
        else{
            /* Check URL Is registered or not */
            
            $url = $input['url'];  // Page Url Full URL of a web site page 
            $ArrayUrl=parse_url($url);  // Fetch For Host Name 
            $mainUrl = $ArrayUrl['host'];  // Host Name 

            $ProjectDetails = Projects::where('website_url',$mainUrl)->first(); // Check Project have or not
            $UserDetails = User::where('id',$ProjectDetails['user_id'])->first();
            
            
            /* URL is not registered in our system Response Back */
            if(empty($ProjectDetails) || $ProjectDetails == null){
                $data['status'] = 0;
                $data['msg'] = 'url is not register';
                $data['data'] = 'error';
                return $data;
            }
            else{
                /* First Check That USer in which Plan have */
                $SubscriptionDetails = SubscriptionsHistory::where('user_id',$ProjectDetails['user_id'])->where('status','=','Y')->first();
                $PlanDetails = Plans::where('stripe_plan_id',$SubscriptionDetails['stripe_plan'])->first();

                if($PlanDetails['plan_name'] === 'Free'){ 

                    /* That User In Free Plans */
                    /* Check the whole content First And also Available credits balance */
                    $creditUsed=$this->creditUsed($ProjectDetails['user_id'],$SubscriptionDetails);
                    $TotalCredit = $PlanDetails['translation_credits']-$creditUsed; //Available credits balance

                    //$OriginalHtmlCode = $input['text'];
                    $FetchContent = strlen(strip_tags($input['text']));

                    if($TotalCredit < $FetchContent){
                        /* Mail Send to That user to tell please subscribe a plane ypu are now a Free Plan */
                        $data1 = [
                            'message' => 'Thank You!', 
                            'name' => ucfirst($UserDetails['name']), 
                        ]; 
                 
                        Mail::to($UserDetails['email'])->send(new SubscribeNewPlan($data1));
                        $data['status'] = 0;
                        $data['msg'] = 'Subscription a Plan ! Check your mail!';
                        $data['data'] = 'error';
                        return $data;
                    }
                }

                /* URL is have in our system */

                /* For HrefLang Tag Coding */
                $ProjectLanguageSourceCode = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
                $WebSitePageUrl = SourceCode::where('p_id',$ProjectDetails['id'])->first();
                $HrefLangResponse = '<link rel="alternate" hreflang='.$ProjectLanguageSourceCode['sortname'].' href='.$WebSitePageUrl['page_url'].'/>';
                $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
                foreach($ProjectLanguagesList as $Language){
                    $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
                    $Language['sortname'] = $LanguageListDetails['sortname'];
                    $Language['name'] = $LanguageListDetails['name'];
                    $LanguagePairList=$ProjectLanguagesList->toArray();
                }   
                foreach($LanguagePairList as $LanguagePair)
                {
                    $HrefLangResponse .= '<link rel="alternate" hreflang='.$LanguagePair['sortname'].' href='.$WebSitePageUrl['page_url'].'?lang='.$LanguagePair['sortname'].'/>';   
                }
                $OriginalHtmlSourceCode = str_replace('<head>', '<head>'.$HrefLangResponse,$input['text']);

               

                /* Check That page Url save in our system or not */
                
                /* Check Language Pair of that Project and call the functions */
                
                $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
               
                foreach($ProjectLanguagesList as $LanguageName){

                    if(!empty($input['lang_code'])){
                        $UrlCheck = str_replace("?lang=".$input['lang_code'], "", $input['url']);
                    }
                    else{
                        $UrlCheck = $input['lang_code'];
                    }

                    $HtmlCodeDetails = SourceCode::where('page_url',$UrlCheck)->where('lang_code',$LanguageName['language_id'])->first();

                    $LanguagePairDetails = LanguagePair::where('from_language',$ProjectDetails['current_language_id'])->where('to_language',$LanguageName['language_id'])->first();
                    $LanguageListDetails = LanguageList::where('id',$LanguageName['language_id'])->first();
                    $LanguageName['sortname'] = $LanguageListDetails['sortname'];
                    $LanguageName['name'] = $LanguageListDetails['name'];

                    $from_language = $ProjectDetails['current_language_id'];
                    $to_language = $LanguageName['language_id'];

                    if(empty($HtmlCodeDetails) || $HtmlCodeDetails == null){

                        if($LanguagePairDetails['api']  == 'D'){
                            $this->DeeplApi($OriginalHtmlSourceCode,$input['url'],$mainUrl,$from_language,$to_language); // Deepl APi Task
                        }
                        elseif($LanguagePairDetails['api']  == 'G'){
                            $this->GoogleApi($OriginalHtmlSourceCode,$input['url'],$mainUrl,$from_language,$to_language); // Google Api Task
                        }
                        elseif($LanguagePairDetails['api'] == 'A'){
                            $this->AmazonApi($OriginalHtmlSourceCode,$input['url'],$mainUrl,$from_language,$to_language); // Amazon Api Task
                        }
                    }
                }
                
                $LanguagePairList = $ProjectLanguagesList->toArray();
                $drop_down_html= '<select id="ChooseLanguage"  onChange="translateContent(event)" style="display: block; width: 130px; background-color:
                #fff; padding: 3px 4px; border: none; font-size: 13px;position: fixed; bottom: 10px; left: 10px; z-index: 10000;">';
                $drop_down_html .= '<option value=""> -- Select One --</option>';
                foreach($LanguagePairList as $LanguagePair)
                {
                    $drop_down_html .= '<option value='.$LanguagePair['sortname'].'>'.$LanguagePair['name'].'</option>';   
                }
                $drop_down_html .= '</select>';
                $data1['status'] = 1;
                $data1['data']=$drop_down_html; 
                $data1['msg']='Success';
                return $data1;
            }
        }
        $data['status'] = 0;
        $data['msg'] = 'Something Error!';
        $data['data'] = 'error';
        return $data;
    }
   
    function DeeplApi_BK($text,$url,$mainUrl,$from_language,$to_language) // Deeepl
    {
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
        
        $OriginalHtmlSourceCode=$text;  // Full page HTML source Code 

        /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        $ProjectDetails = Projects::where('website_url',$mainUrl)->first();

        /* Source Language Details */
        $sourcetLanguageListDetails =  LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        
        $sourceLanguage['sortname'] = $sourcetLanguageListDetails['sortname'];
        $sourceLanguage['name'] = $sourcetLanguageListDetails['name'];

        /* Destination Language */
        $DestinationLanguageListDetails = LanguageList::where('id',$to_language)->first();
        $destinationLanguage['sortname'] = $DestinationLanguageListDetails['sortname'];
        $destinationLanguage['name'] = $DestinationLanguageListDetails['name'];

             
        $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();

        foreach($ProjectLanguagesList as $Language){
            $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
            $Language['sortname'] = $LanguageListDetails['sortname'];
            $Language['name'] = $LanguageListDetails['name'];
        }
        
        /*MetaData Section */

        /* Meta data match REGEX pattern*/
        $pattern = '~<\s*meta\s(?=[^>]*?\b(?:name|property|http-equiv)\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=)))        
        [^>]*?\bcontent\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))[^>]*>~ix';

        /* Check HTML code section */

        $HtmlCodeDetails = SourceCode::where('page_url',$url)->where('lang_code',$to_language)->first();
        
        if(empty($HtmlCodeDetails) || $HtmlCodeDetails == null){

            /* Insert here */
            /* Insert Page Details Into Source Code Of html table */

            $SourceCode = new SourceCode();

            $SourceCode->page_url = $url;//$url;
            $SourceCode->p_id = $ProjectDetails['id'];
            $SourceCode->html_code = $text;  // Original Source Code
            $SourceCode->translated_html_code = $text;//$OriginalHtmlSourceCode; // Here Translated HTML source code 
            $SourceCode->lang_code = $to_language;
            $SourceCode->status = 0;

            $SourceCode->save();

            /* Now fetch Meta data content And Main Body Content From HTML Source Code */

            /* First Meta Data Section */
            if($ProjectDetails['metadata_translation'] == 1){

                $MetaDataArray = [];

                /* Now first fetch Meta data content */

                if(preg_match_all($pattern, $OriginalHtmlSourceCode, $out)){
                    /* This is Meta data content Array */
                    $MetaDataContent = $out;
                }
                else{
                    $MetaDataContent = null;
                }
                $MetaData = [];
                $FullMetaData=[];
                if(!empty($MetaDataContent) || $MetaDataContent != null)
                {
                    $FullData = $MetaDataContent[0];   
                    $IndexName = $MetaDataContent[1];
                    $IndexValue = $MetaDataContent[2];
                    for($i=0 ; $i<sizeof($IndexName); $i++){
                        if (strpos($IndexName[$i], 'title') !== false || strpos($IndexName[$i], 'description') !== false || strpos($IndexName[$i], 'keywords') !== false) {
                            $FullMetaData[] = $FullData[$i];
                        }
                        else{
                            $FullMetaData = [];
                        }
                    }
                }

                if(count($FullMetaData)>0){
                    foreach($FullMetaData as $Mdata){

                        $data = $this->DeeplTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $Mdata);

                        $OriginalMetaData[] = $Mdata;
                        $TranslateMetaData[] = $data;
                        $MetaDataArray[$Mdata] = $data;
                    }

                    for($i=0; $i<sizeof($OriginalMetaData); $i++){

                        preg_match_all($pattern, $OriginalMetaData[$i], $out);
                        $OriginalMetaDataArray[$i] = $out[2];

                        preg_match_all($pattern, $TranslateMetaData[$i], $out);
                        $TranslateMetaDataArray[$i] = $out[2];
                    }

                    if(count($OriginalMetaDataArray)!=0){

                        /* Translate All Meta Data In Below */

                        for($i=0; $i<count($OriginalMetaDataArray); $i++ ){
                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $OriginalMetaDataArray[$i])->where('data_section',4)->get();
                            if(sizeof($ParagraphDetails) == 0){

                                $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], implode($OriginalMetaDataArray[$i]), implode($OriginalMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), 4, $to_language, 0);

                                $this->InsertDataVersion($ParagraphId, implode($TranslateMetaDataArray[$i]), 'M', 1);

                                $this->InsertTextCount($ProjectDetails['id'], implode($OriginalMetaDataArray[$i]), $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);
                            }
                        } 
                    }
                    
                    foreach( $MetaDataArray as $key => $val){
                        $OriginalHtmlSourceCode = str_replace($key, $val, $OriginalHtmlSourceCode);
                    }
                }
            }

            /* End Meta Data Section */

            /* Now Main Body data Section */

            // $OriginalHtmlSourceCode = "<html>".$OriginalHtmlSourceCode."</html>";
            
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($OriginalHtmlSourceCode);
        
            $htmls = $doc->getElementsByTagName('html');

            if($htmls->length > 0)
            {
                $explodeData = $this->FetchHtmlData($htmls);
            }

            $new_arr=[];
            foreach($explodeData as $HtmlText){

                if($HtmlText !=null  || !empty($HtmlText) || $HtmlText != " " || $HtmlText != "\t" ){

                    foreach($handleclassArray as $key => $val){

                        $lastPos = 0;
                        $positions=array();
                        while (($lastPos = strpos($HtmlText, $key, $lastPos))!== false) {
                            $positions[] = $lastPos;
                            $lastPos = $lastPos + strlen($key);
                        }

                        $exr=0;
                        foreach ($positions as $value) {
                            $value=$value+$exr;
                            $firstoccurIndex= strpos($HtmlText,'>',$value);
                            $HtmlText=substr_replace($HtmlText,"|>|</para>",$firstoccurIndex,1);
                            $exr=$exr+9;
                        }
                    }
                    
                    foreach($TagArraystart as $key => $val){
                        $HtmlText=str_replace($val, '<para>'.$val, $HtmlText);
                    }
                    
                    foreach($TagArray as $key => $val){
                            $HtmlText=str_replace($val, '<para>'.$val.'</para>', $HtmlText);   
                    }

                    $data = $this->DeeplTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);

                    $new_arr[$HtmlText]=$data;
                    
                    /* Now Save The Translated data Into DB */
                    foreach($TagArray as $key => $val){
                        $data=str_replace($key, $val, $data);
                    }
                    foreach($TagArraystart as $key => $val){
                        $data=str_replace($key, $val, $data);
                    }

                    $data=str_replace('<para>', '', $data);
                    $data=str_replace('</para>', '', $data);
                    $HtmlText=str_replace('<para>', '', $HtmlText);
                    $HtmlText=str_replace('</para>', '', $HtmlText);

                    $TestHtmlText = $HtmlText;

                    /* For Pure String */
                    $content_original_data = $HtmlText;
                    $content_translated_data = $data;
                    foreach($TagArray as $key => $val){
                        $content_original_data=str_replace($val, $key, $content_original_data);
                        $content_translated_data=str_replace($val, $key, $content_translated_data);
                    }
                    foreach($TagArraystart as $key => $val){
                        $content_original_data=str_replace($val, $key, $content_original_data);
                        $content_translated_data=str_replace($val, $key, $content_translated_data);
                    }

                    $content_original_data = strip_tags($content_original_data);
                    $content_translated_data = strip_tags($content_translated_data);   
                    
                    foreach($TagArraystart as $key => $val){
                        $TestHtmlText=str_replace($val, $key, $TestHtmlText);
                    }
                    
                    foreach($TagArray as $key => $val){
                            $TestHtmlText=str_replace($val, $key, $TestHtmlText);
                    }
                    
                    $TestHtmlText = str_replace("\r\n","",$TestHtmlText);
                    $TestHtmlText = str_replace("\t","",$TestHtmlText);
                    $TestHtmlText = strip_tags(trim($TestHtmlText));
                    
                    if(preg_match('/[A-Za-z]/', $TestHtmlText) || preg_match('/[0-9]/', $TestHtmlText)){
                        if($TestHtmlText !== 'BESbswy'){
                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $HtmlText)->get();
                            if(sizeof($ParagraphDetails) == 0){

                                $HtmlText = $this->ExceptionalChange($HtmlText);
                                $data = $this->ExceptionalChange($data);

                                $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], $HtmlText, $content_original_data, $data, $content_translated_data, 5, $to_language, 0);

                                $this->InsertDataVersion($ParagraphId, $data, 'M', 1);

                                $this->InsertTextCount($ProjectDetails['id'], $TestHtmlText, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);

                            }
                        }
                    }
                }
            }
            
            /* Now Replace The Data matching from DB And Response back*/

            $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCode['id'])->where('language_id',$to_language)->get();
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

            $SourceCode->update([
                'translated_html_code' => $new_html,
            ]);
            return true;
        }
        return false;
    }

    function FetchHtmlData($divs)
    {
        static $HtmlData = [];
        if($divs->length > 0)
        {
            foreach ($divs as $div) 
            {
                if($div->hasChildNodes())
                {
                    $this->FetchHtmlData($div->childNodes);
                }
                else
                {
                   
                    if($div instanceof DOMCdataSection)
                    {
                        continue;
                    }
                    else
                    {  
                        if($div->nodeName != '#cdata-section' && $div->nodeName != '#comment'){
                            if(preg_match('/[A-Za-z]/', $div->nodeValue) || preg_match('/[0-9]/', $div->nodeValue)){

                                $HtmlData[] = $div->nodeValue;
                            }
                        }
                    }
                }
            }
        }
        return $HtmlData;
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

    /* Response HTML source Code */
    public function ResponseHtmlSourceCode(Request $request){

        $input = $request->input();

         /* Check Given url valid or not */
         //$regex = '/^(https:\/\/)?(http:\/\/)?(www.)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

         $validator = Validator::make($request->all(), [
            //  'url'  => 'regex:' . $regex,//'required|active_url' ,
            'url'  => 'required',
            'lang_code' => 'required',
            // 'text' => 'required',
        ]);
        if($validator->fails()){
 
             $data['status'] = 0; 
             $data['data'] = $validator;
             $data['msg'] = 'validation error';
             return $data;
        }
        else{
            $url = $input['url'];
            $lang_code = $input['lang_code']; //This is Language Sort Name
            // $text = $input['text'];

            $LanguageListDetails = LanguageList::where('sortname',$lang_code)->first();  // Matching Language Sort Name to Find the language Id
            $LanguageId = $LanguageListDetails['id'];

            /* Check Translated HTML Source code have or not and also Status*/
            //URL?lang=EN

            // $he = parse_url($url);
            // echo $he['host'];
            // exit();
            $HostDetails = parse_url($url);
            $HostName = $HostDetails['host'];
            $ProjectDetails = Projects::where('website_url',$HostName)->first();
            $DestinationLanguage = '?lang='.$input['lang_code'];
            $url = str_replace($DestinationLanguage,"",$url);
            // $url = $url[0];
            $HtmlCodeDetails = SourceCode::where('page_url',$url)->where('lang_code', $LanguageId)->first();
            /* Match The Original text is same or not */ 
            //if($HtmlCodeDetails['html_code'] === $text){

                if(!empty($HtmlCodeDetails)){

                    // if($HtmlCodeDetails['status'] == 0){ 
                        /* No change Available */

                        // <link rel="alternate" hreflang="nl" href="https://www.dimix.com/"/>
                        // <link rel="alternate" hreflang="fr" href="https://www.dimix.com/fr/"/>
                        //1. Project Source Language Code + 2. Project Page Url  => $input['url']

                        // $ProjectLanguageSourceCode = LanguageList::where('id',$ProjectDetails['current_language_id'])->first();

                        // $WebSitePageUrl = SourceCode::where('p_id',$ProjectDetails['id'])->first();

                        // $HrefLangResponse = '<link rel="alternate" hreflang='.$ProjectLanguageSourceCode['sortname'].' href='.$WebSitePageUrl['page_url'].'/>';

                        // $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
                
                        // foreach($ProjectLanguagesList as $Language){
    
                        //     $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
                        //     $Language['sortname'] = $LanguageListDetails['sortname'];
                        //     $Language['name'] = $LanguageListDetails['name'];
    
                        //     $LanguagePairList=$ProjectLanguagesList->toArray();

                        // }
            
                        //     // $drop_down_html= '<select id="ChooseLanguage"  onChange="translateContent(event)" style="display: block; width: 130px; background-color:
                        //     // #fff; padding: 3px 4px; border: none; font-size: 13px;position: fixed; bottom: 10px; left: 10px; z-index: 10000;">';
                        //     // $drop_down_html .= '<option value=""> -- Select One --</option>';
                     
                        //        foreach($LanguagePairList as $LanguagePair)
                        //        {
                        //         $HrefLangResponse .= '<link rel="alternate" hreflang='.$LanguagePair['sortname'].' href='.$WebSitePageUrl['page_url'].'?lang='.$LanguagePair['sortname'].'/>';   
                        //      }
                            
                           
                            
    
                        



                        /* Return back With response data */
                        $data['status'] = 1; 
                        $data['data'] = $HtmlCodeDetails['translated_html_code'];
                        // $data['hreglangdata'] = $HrefLangResponse;
                        $data['msg'] = 'Success';
                        return $data;
                //     }
                //     elseif($HtmlCodeDetails['status'] == 1 || ){
                //         /* here gain All Data Check And Replace.. Check 1st Do Not Translate and Always Translated as */
                //         /* Here Some Operation Have */
    
                //         /* Do Not Translate Section */
    
                //         /* Always Translated As */

                //         /* On Fly */
                //         $url =  str_replace("https://", "", $url);
                //         $url =  str_replace("http://", "", $url);
                //         $url =  str_replace("www.", "", $url);

                //         $url = explode("/",$url);
                //         $mainURL = $url[0];

                //         $ProjectDetails = Projects::where('website_url',$mainURL)->first();
                //         $SourceCode = SourceCode::where('p_id',$ProjectDetails['id'])->first();

                //         $OriginalHTMLSourceCode = $HtmlCodeDetails['html_code'];
                //         $TranslatedHTMLSourceCode = $HtmlCodeDetails['translated_html_code'];

                //         $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCode['id'])->where('language_id',$LanguageId)->get();

                //         $OriginalData = [];
                //         $TranslatedOriginalData = [];
                    
                //         foreach($TranslatedData as $data){
                            
                //             $OriginalData[] = $data['original_data'];
                //             $TranslatedOriginalData[] = $data['translated_data'];
                //             $Replace[$data['original_data']] =  $data['translated_data'];
                //         }

                //         foreach($Replace as $key => $val){

                //             $OriginalHTMLSourceCode = str_replace(trim($key), $val, $OriginalHTMLSourceCode);

                //         }

                //         /* update Into DB full Translated data */
                //         $SourceCode->update([

                //             'translated_html_code' => $OriginalHTMLSourceCode, 
                //         ]);


                //         /* Then Update The Details*/ 
                //         $data1['status'] = 1;
                //         $data1['data'] = $HtmlCodeDetails['translated_html_code'];
                //         $data1['msg'] = 'Success';
                //         return $data1;
                //     }
                }
                elseif(!empty($ProjectDetails)){

                    $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
                
                    foreach($ProjectLanguagesList as $Language){

                        $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
                        $Language['sortname'] = $LanguageListDetails['sortname'];
                        $Language['name'] = $LanguageListDetails['name'];

                        $LanguagePairList=$ProjectLanguagesList->toArray();
        
                        $drop_down_html= '<select id="ChooseLanguage"  onChange="translateContent(event)" style="display: block; width: 130px; background-color:
                        #fff; padding: 3px 4px; border: none; font-size: 13px;position: fixed; bottom: 10px; left: 10px; z-index: 10000;">';
                        $drop_down_html .= '<option value=""> -- Select One --</option>';
                 
                           foreach($LanguagePairList as $LanguagePair)
                           {
                             $drop_down_html .= '<option value='.$LanguagePair['sortname'].'>'.$LanguagePair['name'].'</option>';   
                         }
                         $drop_down_html .= '</select>';
                        //  Plans::ExtraChargeCalculation($ProjectDetails['user_id']);
                         $data1['status'] = 1;
                         $data1['data']=$drop_down_html; 
                         $data1['msg']='Success';
                         //dd($new_arr);
                         return $data1;

                    }
                }
                else{
    
                    /* Return HtmlCode For This Url is not Have */
                    $data['status'] = 0;
                    $data['data'] = null;
                    $data['msg'] = 'No Html data Found In Our System';
                    return $data;
                }

            // }
            // else{
            //     /* New HTML CODE is does not match with our system */
            //     /* Again Regenerate all the Process */
            // }
        }
    }

    /* Execute WebSite Update */

    public function webSiteExecute(Request $request){

        $input = $request->all();
        $project_id = $input['project'];
        $destination_lang = $input['language'];
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
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)->get();
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
        
        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)->get();
   
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


        $SourceCodeDetails = SourceCode::where('p_id',$project_id)->where('lang_code',$destination_lang)->first();

        $OriginalHtmlSourceCode = $SourceCodeDetails['html_code'];

        //4 = MetaData 5= Body Website 

        $BodyTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',5)->get();

        $MetaTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',4)->get();
        
        $OriginalData = [];
        $TranslatedOriginalData = [];
    
        
        foreach($BodyTranslatedData as $data){ 
                        
            $Replace[$data['original_data']] =  $data['translated_data'];
        }
        $MetaDataArray = [];
        if(count($MetaTranslatedData)>0){
            foreach($MetaTranslatedData as $data){ 
                        
                $MetaDataArray[$data['original_data']] =  $data['translated_data'];
            }
        }
        
        array_multisort(array_map('strlen', $Replace), $Replace);
        $Replace = array_reverse($Replace);
       
        foreach($Replace as $key=>$val){
            
            $OriginalData[] = $key;
            $TranslatedOriginalData[] = $val;
           
        }

         /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }


        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($OriginalHtmlSourceCode);
        $htmls = $doc->getElementsByTagName('html'); 
        
        if($htmls->length > 0)
        {
            $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
        }
       
        foreach( $MetaDataArray as $key => $val){
            $new_html = str_replace($key, $val, $new_html);
        }

        foreach($TagArray as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        foreach($TagArraystart as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        $new_html=$this->ExceptionalChange($new_html);
                   
        /* update Into DB full Translated data */
        $SourceCodeDetails->update([

            'translated_html_code' => $new_html, 
        ]);
       
        return back();
    }

    function creditMultiplier($source_id,$destination_id)
    {
        $LanguagePairList = LanguagePair::where('from_language',$source_id)->where('to_language',$destination_id)->first();
        return $LanguagePairList['credit_multiplier'];
    }
    function getDomain($url){
        $pieces = parse_url($url);
        $domain = isset($pieces['host']) ? $pieces['host'] : '';
        if(preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)){
            return $regs['domain'];
        }
        return FALSE;
    }

    /* GOOGLE API SECTION START */

    public function GoogleApi($text,$url,$mainUrl,$from_language,$to_language){

        /* Html tag Name Array */
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
        
        $OriginalHtmlSourceCode=$text;  // Full page HTML source Code 
    
        /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
            
        $ProjectDetails = Projects::where('website_url',$mainUrl)->first();

        /* Source Language Details */
        $sourcetLanguageListDetails =  LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        
        $sourceLanguage['sortname'] = $sourcetLanguageListDetails['sortname'];
        $sourceLanguage['name'] = $sourcetLanguageListDetails['name'];

        $DestinationLanguageListDetails = LanguageList::where('id',$to_language)->first();
        $destinationLanguage['sortname'] = $DestinationLanguageListDetails['sortname'];
        $destinationLanguage['name'] = $DestinationLanguageListDetails['name'];

            
        $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
                
        foreach($ProjectLanguagesList as $Language){

            $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
            $Language['sortname'] = $LanguageListDetails['sortname'];
            $Language['name'] = $LanguageListDetails['name'];

        }
            
        /*MetaData Section */

        /* Meta data match REGEX pattern*/

        $pattern = '~<\s*meta\s(?=[^>]*?\b(?:name|property|http-equiv)\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=)))        
        [^>]*?\bcontent\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))[^>]*>~ix';

        /* Check HTML code section */

        $HtmlCodeDetails = SourceCode::where('page_url',$url)->where('lang_code',$to_language)->first();
                
        if(empty($HtmlCodeDetails) || $HtmlCodeDetails == null){

            /* Insert here */
            /* Insert Page Details Into Source Code Of html table */

            $SourceCode = new SourceCode();

            $SourceCode->page_url = $url;//$url;
            $SourceCode->p_id = $ProjectDetails['id'];

            $SourceCode->html_code = $text;  // Original Source Code
            $SourceCode->translated_html_code = $text;//$OriginalHtmlSourceCode; // Here Translated HTML source code 
            $SourceCode->lang_code = $to_language;
            $SourceCode->status = 0;

            $SourceCode->save();

            /* Now fetch Meta data content And Main Body Content From HTML Source Code */
            /* First Meta Data Section */

            if($ProjectDetails['metadata_translation'] == 1){

                $MetaDataArray = [];

                /* Now first fetch Meta data content */

                if(preg_match_all($pattern, $OriginalHtmlSourceCode, $out)){
                    /* This is Meta data content Array */
                    $MetaDataContent = $out;
                }
                else{
                    $MetaDataContent = null;
                }
                
                $FullMetaData=[];
                        
                if(!empty($MetaDataContent) || $MetaDataContent != null)
                {
                    
                    for($i=0 ; $i<count($MetaDataContent[1]); $i++){
                        if (strpos($MetaDataContent[1][$i], 'title') !== false || strpos($MetaDataContent[1][$i], 'description') !== false || strpos($MetaDataContent[1][$i], 'keywords') !== false) {
                    
                            $FullMetaData[] = $MetaDataContent[0][$i];
                        }
                    }
                }

                if(count($FullMetaData)>0){

                    foreach($FullMetaData as $Mdata){

                        $output = $this->GoogleTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $Mdata);
                        $OriginalMetaData[] = $Mdata;
                        $TranslateMetaData[] = $output;
                        $MetaDataArray[$Mdata] = $output;
                    }

                    for($i=0; $i<sizeof($OriginalMetaData); $i++){

                        preg_match_all($pattern, $OriginalMetaData[$i], $out);
                        $OriginalMetaDataArray[$i] = $out[2];

                        preg_match_all($pattern, $TranslateMetaData[$i], $out);
                        $TranslateMetaDataArray[$i] = $out[2];
                    }

                    if(count($OriginalMetaDataArray)!=0){

                        /* Translate All Meta Data In Below */

                        for($i=0; $i<count($OriginalMetaDataArray); $i++ ){

                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $OriginalMetaDataArray[$i])->where('data_section',4)->get();
                            
                            if(sizeof($ParagraphDetails) == 0){

                                $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], implode($OriginalMetaDataArray[$i]), implode($OriginalMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), 4, $to_language, 0);

                                $this->InsertDataVersion($ParagraphId, implode($TranslateMetaDataArray[$i]), 'M', 1);

                                $this->InsertTextCount($ProjectDetails['id'], implode($OriginalMetaDataArray[$i]), $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);

                            }
                        } 
                    }
                    
                    foreach( $MetaDataArray as $key => $val){
                        $OriginalHtmlSourceCode = str_replace($key, $val, $OriginalHtmlSourceCode);
                    }
                }
            }
            /* End Meta data Section */

            /* Now Start Body Section */
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($OriginalHtmlSourceCode);
        
            $htmls = $doc->getElementsByTagName('html');

            if($htmls->length > 0)
            {
                $explodeData = $this->FetchHtmlData($htmls);
            }

            foreach($explodeData as $HtmlText){

                if($HtmlText !=null  || !empty($HtmlText) || $HtmlText != " " || $HtmlText != "\t" ){

                    $DBMatchHtmlText = $HtmlText;
                    
                    foreach($TagArray as $key => $val){
                            $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    foreach($TagArraystart as $key => $val){
                        $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    $TestHtmlText = $HtmlText;
                    $TestHtmlText = str_replace("\r\n","",$TestHtmlText);
                    $TestHtmlText = str_replace("\t","",$TestHtmlText);
                    

                    $TestHtmlText = strip_tags(trim($TestHtmlText));

                    if(preg_match('/[A-Za-z]/', $TestHtmlText) || preg_match('/[0-9]/', $TestHtmlText)){

                        if($TestHtmlText !== 'BESbswy' && str_word_count($TestHtmlText)>1){

                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $DBMatchHtmlText)->get();
                                    
                            if(sizeof($ParagraphDetails) == 0){
                                /* Call Google APi For Translate HtmlText*/

                                $outputText = $this->GoogleTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);

                                $content_original_data = strip_tags($HtmlText);
                                $content_translated_data = strip_tags($outputText);

                                foreach($TagArray as $key => $val){
                                        $HtmlText=str_replace($key, $val, $HtmlText);
                                        $outputText=str_replace($key, $val, $outputText);
                                }
                                foreach($TagArraystart as $key => $val){
                                    $HtmlText=str_replace($key, $val, $HtmlText);
                                    $outputText=str_replace($key, $val, $outputText);
                                }

                                if(sizeof($ParagraphDetails) == 0){

                                    $HtmlText = $this->ExceptionalChange($HtmlText);
                                    $outputText = $this->ExceptionalChange($outputText);

                                    $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], $HtmlText, $content_original_data, $outputText, $content_translated_data, 5, $to_language, 0);

                                    $this->InsertDataVersion($ParagraphId, $outputText, 'M', 1);

                                    $this->InsertTextCount($ProjectDetails['id'], $TestHtmlText, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);
                                }
                            }   
                        }
                    }
                }
            }
            /* Now Replace String Section Start From DB Translated Data */

            $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCode['id'])->where('language_id',$to_language)->get();

            
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

            $new_html=$this->ExceptionalChange($new_html);                    

            $SourceCode->update([
                'translated_html_code' => $new_html,
            ]);
            return true;
        }  
        return false;
    }

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

    /* Google Api Website Execute */
    public function webSiteExecuteGoogleApi(Request $request){

        $input = $request->all();
        $project_id = $input['project'];
        $destination_lang = $input['language'];
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
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)->get();
   
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

                        $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $langcode, $new_str);
                        
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

        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)->get();
   
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

        /* The ABove ALl codes are change IN DB */


        $SourceCodeDetails = SourceCode::where('p_id',$project_id)->where('lang_code',$destination_lang)->first();

        $OriginalHtmlSourceCode = $SourceCodeDetails['html_code'];

        //4 = MetaData 5= Body Website 

        $BodyTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',5)->get();

        $MetaTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',4)->get();
        
        $OriginalData = [];
        $TranslatedOriginalData = [];
    
        
        foreach($BodyTranslatedData as $data){    
            $Replace[$data['original_data']] =  $data['translated_data'];
        }
        
        $MetaDataArray = [];
        if(count($MetaTranslatedData)>0){
            foreach($MetaTranslatedData as $data){ 
                        
                $MetaDataArray[$data['original_data']] =  $data['translated_data'];
            }
        }
                
        array_multisort(array_map('strlen', $Replace), $Replace);
        $Replace = array_reverse($Replace);
       
        foreach($Replace as $key=>$val){
            
            $OriginalData[] = $key;
            $TranslatedOriginalData[] = $val;
           
        }

         /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }


        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($OriginalHtmlSourceCode);
        $htmls = $doc->getElementsByTagName('html'); 
        
        if($htmls->length > 0)
        {
            $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
        }

       
        foreach( $MetaDataArray as $key => $val){
            $new_html = str_replace($key, $val, $new_html);
        }

        foreach($TagArray as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        foreach($TagArraystart as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        $new_html=$this->ExceptionalChange($new_html);
       
        /* update Into DB full Translated data */
        $SourceCodeDetails->update([

            'translated_html_code' => $new_html, 
        ]);
       
        return back();
    }

    /* End Google APi Section */

    /* Amazon Api Section Start */
    public function AmazonApi($text,$url,$mainUrl,$from_language,$to_language){

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

        $OriginalHtmlSourceCode=$text;  // Full page HTML source Code 
    
        /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
            
        $ProjectDetails = Projects::where('website_url',$mainUrl)->first();

        /* Source Language Details */
        $sourcetLanguageListDetails =  LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        
        $sourceLanguage['sortname'] = $sourcetLanguageListDetails['sortname'];
        $sourceLanguage['name'] = $sourcetLanguageListDetails['name'];

        $DestinationLanguageListDetails = LanguageList::where('id',$to_language)->first();
        $destinationLanguage['sortname'] = $DestinationLanguageListDetails['sortname'];
        $destinationLanguage['name'] = $DestinationLanguageListDetails['name'];

            
        $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();
                
        foreach($ProjectLanguagesList as $Language){

            $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
            $Language['sortname'] = $LanguageListDetails['sortname'];
            $Language['name'] = $LanguageListDetails['name'];

        }
            
        /*MetaData Section */

        /* Meta data match REGEX pattern*/

        $pattern = '~<\s*meta\s(?=[^>]*?\b(?:name|property|http-equiv)\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=)))        
        [^>]*?\bcontent\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))[^>]*>~ix';

        /* Check HTML code section */

        $HtmlCodeDetails = SourceCode::where('page_url',$url)->where('lang_code',$to_language)->first();
                
        if(empty($HtmlCodeDetails) || $HtmlCodeDetails == null){

            /* Insert here */
            /* Insert Page Details Into Source Code Of html table */

            $SourceCode = new SourceCode();

            $SourceCode->page_url = $url;//$url;
            $SourceCode->p_id = $ProjectDetails['id'];

            $SourceCode->html_code = $text;  // Original Source Code
            $SourceCode->translated_html_code = $text;//$OriginalHtmlSourceCode; // Here Translated HTML source code 
            $SourceCode->lang_code = $to_language;
            $SourceCode->status = 0;

            $SourceCode->save();

            /* Now fetch Meta data content And Main Body Content From HTML Source Code */
            /* First Meta Data Section */

            if($ProjectDetails['metadata_translation'] == 1){

                $MetaDataArray = [];

                /* Now first fetch Meta data content */

                if(preg_match_all($pattern, $OriginalHtmlSourceCode, $out)){
                    /* This is Meta data content Array */
                    $MetaDataContent = $out;
                }
                else{
                    $MetaDataContent = null;
                }
                
                $FullMetaData=[];
                        
                if(!empty($MetaDataContent) || $MetaDataContent != null)
                {
                    
                    for($i=0 ; $i<count($MetaDataContent[1]); $i++){
                        if (strpos($MetaDataContent[1][$i], 'title') !== false || strpos($MetaDataContent[1][$i], 'description') !== false || strpos($MetaDataContent[1][$i], 'keywords') !== false) {
                    
                            $FullMetaData[] = $MetaDataContent[0][$i];
                        }
                    }
                }

                if(count($FullMetaData)>0){

                    foreach($FullMetaData as $Mdata){

                        $result = $this->AmazonTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $Mdata);
                        
                        $OriginalMetaData[] = $Mdata;
                        $TranslateMetaData[] = $result;
                        $MetaDataArray[$Mdata] = $result;
                    }

                    for($i=0; $i<sizeof($OriginalMetaData); $i++){

                        preg_match_all($pattern, $OriginalMetaData[$i], $out);
                        $OriginalMetaDataArray[$i] = $out[2];

                        preg_match_all($pattern, $TranslateMetaData[$i], $out);
                        $TranslateMetaDataArray[$i] = $out[2];
                    }

                    if(count($OriginalMetaDataArray)!=0){

                        /* Translate All Meta Data In Below */
                        for($i=0; $i<count($OriginalMetaDataArray); $i++ ){

                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $OriginalMetaDataArray[$i])->where('data_section',4)->get();
                            
                            if(sizeof($ParagraphDetails) == 0){

                                $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], implode($OriginalMetaDataArray[$i]), implode($OriginalMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), 4, $to_language, 0);

                                $this->InsertDataVersion($ParagraphId, implode($TranslateMetaDataArray[$i]), 'M', 1);

                                $this->InsertTextCount($ProjectDetails['id'], implode($OriginalMetaDataArray[$i]), $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);
                            }
                        } 
                    }
                    foreach( $MetaDataArray as $key => $val){
                        $OriginalHtmlSourceCode = str_replace($key, $val, $OriginalHtmlSourceCode);
                    }
                }
            }
            /* End Meta data Section */

            /* Now Start Body Section */
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($OriginalHtmlSourceCode);
        
            $htmls = $doc->getElementsByTagName('html');

            if($htmls->length > 0)
            {
                $explodeData = $this->FetchHtmlData($htmls);
            }

            foreach($explodeData as $HtmlText){

                if($HtmlText !=null  || !empty($HtmlText) || $HtmlText != " " || $HtmlText != "\t" ){

                    $DBMatchHtmlText = $HtmlText;
                    
                    foreach($TagArray as $key => $val){
                            $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    foreach($TagArraystart as $key => $val){
                        $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    $TestHtmlText = $HtmlText;
                    $TestHtmlText = str_replace("\r\n","",$TestHtmlText);
                    $TestHtmlText = str_replace("\t","",$TestHtmlText);
                    

                    $TestHtmlText = strip_tags(trim($TestHtmlText));

                    if(preg_match('/[A-Za-z]/', $TestHtmlText) || preg_match('/[0-9]/', $TestHtmlText)){

                        if($TestHtmlText !== 'BESbswy' && str_word_count($TestHtmlText)>1){

                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $DBMatchHtmlText)->get();
                                    
                            if(sizeof($ParagraphDetails) == 0){
                                /* Call Amazon APi For Translate HtmlText*/

                                $outputText = $this->AmazonTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);

                                $content_original_data = strip_tags($HtmlText);
                                $content_translated_data = strip_tags($outputText);

                                foreach($TagArray as $key => $val){
                                        $HtmlText=str_replace($key, $val, $HtmlText);
                                        $outputText=str_replace($key, $val, $outputText);
                                }
                                foreach($TagArraystart as $key => $val){
                                    $HtmlText=str_replace($key, $val, $HtmlText);
                                    $outputText=str_replace($key, $val, $outputText);
                                }

                                $HtmlText=$this->ExceptionalChange($HtmlText);            

                                $outputText=$this->ExceptionalChange($outputText);                  

                                if(sizeof($ParagraphDetails) == 0){

                                    $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], $HtmlText, $content_original_data, $outputText, $content_translated_data, 5, $to_language, 0);

                                    $this->InsertDataVersion($ParagraphId, $outputText, 'M', 1);

                                    $this->InsertTextCount($ProjectDetails['id'], $content_original_data, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);

                                }
                            }   
                        }
                    }
                }
            }
            /* Now Replace String Section Start From DB Translated Data */

            $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCode['id'])->where('language_id',$to_language)->get();

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

            $new_html=$this->ExceptionalChange($new_html);                    

                $SourceCode->update([
                'translated_html_code' => $new_html,
            ]);
            return true;
        }  
        return false;
    }

    /* Amazon APi Website Execute */
    public function webSiteExecuteAmazonApi(Request $request){

        $input = $request->all();
        $project_id = $input['project'];
        $destination_lang = $input['language'];
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
      
       $ProjectStringCorrectionsDoNot = ProjectStringCorrections::where('p_id',$project_id)->where('type',1)->where('to_language',$destination_lang)->where('status',0)->get();
   
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

        $ProjectStringCorrectionsAlways = ProjectStringCorrections::where('p_id',$project_id)->where('type',2)->where('to_language',$destination_lang)->where('status',0)->get();
   
        $StringCorrections = []; 
        
        foreach($ProjectStringCorrectionsAlways as $ProjectString){

            $StringAlways[] = StringCorrections::where('id',$ProjectString['string_correction_id'])->first();
        }

        if(count($StringAlways)>0){
            
            foreach($StringAlways as $string){

                $search_arr[]  =  $string['do_not_translate_string'];
                $mainReplace_arr[] = '<cite>'.$string['always_translate_as_string']."</cite>";
            }
           
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


        $SourceCodeDetails = SourceCode::where('p_id',$project_id)->where('lang_code',$destination_lang)->first();

        $OriginalHtmlSourceCode = $SourceCodeDetails['html_code'];

        //4 = MetaData 5= Body Website 

        $BodyTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',5)->get();

        $MetaTranslatedData = ProjectData::where('p_id',$project_id)->where('source_code_id',$SourceCodeDetails['id'])->where('language_id',$destination_lang)->where('data_section',4)->get();
        
        $OriginalData = [];
        $TranslatedOriginalData = [];
    
        foreach($BodyTranslatedData as $data){    
            $Replace[$data['original_data']] =  $data['translated_data'];
        }
        
        $MetaDataArray = [];
        if(count($MetaTranslatedData)>0){
            foreach($MetaTranslatedData as $data){ 
                $MetaDataArray[$data['original_data']] =  $data['translated_data'];
            }
        }
                
        array_multisort(array_map('strlen', $Replace), $Replace);
        $Replace = array_reverse($Replace);
       
        foreach($Replace as $key=>$val){
            $OriginalData[] = $key;
            $TranslatedOriginalData[] = $val;
        }

         /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        $doc = new \DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTML($OriginalHtmlSourceCode);
        $htmls = $doc->getElementsByTagName('html'); 
        
        if($htmls->length > 0)
        {
            $new_html = $this->replaceAllText2($htmls, $OriginalData, $TranslatedOriginalData,$doc);
        }
       
        foreach( $MetaDataArray as $key => $val){
            $new_html = str_replace($key, $val, $new_html);
        }

        foreach($TagArray as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        foreach($TagArraystart as $key => $val){
            $new_html=str_replace($val, $key, $new_html);
        }

        $new_html=$this->ExceptionalChange($new_html);                    
       
        /* update Into DB full Translated data */
        $SourceCodeDetails->update([
            'translated_html_code' => $new_html, 
        ]);
       
        return back();
    }

    /* All Translate Api Call Function Here */

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

    /* Now Change String Some Exceptional Changes */
    public function ExceptionalChange($text){

        $text=str_replace('&amp;','&',$text);                    
        $text=str_replace('&nbsp;',' ',$text);
        $text=str_replace('&amp;gt;','>',$text);
        $text=str_replace('&gt;','>',$text);
        $text=str_replace('&amp;gt;','>',$text);
        return $text;

    }

    /* Insert Data Into DB Section */

    /* For Project Data */
    public function InsertProjectData($id, $S_id, $original_data, $content_original_data , $translated_data, $content_translated_data, $data_section, $to_language, $status){
        $ProjectData = new ProjectData();
        $ProjectData->p_id = $id;
        $ProjectData->source_code_id = $S_id;
        $ProjectData->original_data = $original_data;

        $content_original_data = str_replace('\r\n','',$content_original_data);
        $content_original_data = str_replace('\t','',$content_original_data);

        $content_translated_data = str_replace('\r\n','',$content_translated_data);
        $content_translated_data = str_replace('\t','',$content_translated_data);


        $ProjectData->content_original_data = trim($content_original_data);
        $ProjectData->translated_data = $translated_data;
        $ProjectData->content_translated_data = trim($content_translated_data);
        $ProjectData->data_section = $data_section;
        $ProjectData->language_id = $to_language; 
        $ProjectData->status = $status;
        // if (!in_array(trim($Mdata), $TagArray))
        $ProjectData->save();
        return $ProjectData['id'];
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


     /* Replace Translated String On Fly For Google Api and Deepl Api and also amazon Api Both Conditions*/

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

                if(!empty($input['from_string'])){
                    $search_arr = $input['from_string'];
                    $replace_arr = "<para>" . $input['to_string'] . "</para>";
                }
                //$ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->where('original_data','LIKE','%'.$input['from_string'].'%')->get();
                $ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->get();

                $MatchParagraphDetails = [];
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

                    if (strpos($stringOriginal, $search_arr) !== false) {
                        $MatchParagraphDetails[] = $line;
                    }    
                }
                
                foreach($MatchParagraphDetails as $line){
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
                    if (strpos($new_str, $search_arr) !== false ) {
                        $Paragraph = ProjectData::where('id',$line['id'])->first();
                        $new_str = str_replace($search_arr, $replace_arr, $new_str);

                        $updatedParagraph = $this->DeeplTranslateApi($SourceLanguageCode, $DestinationLanguageCode, $new_str);

                        $updatedParagraph = str_replace('<para>', "", $updatedParagraph);
                        $updatedParagraph = str_replace('</para>', "", $updatedParagraph);

                        /* Check Here The tags have or not */

                        $ReplaceString = $replace_arr;

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
                        
                        $TextCountString=$this->ExceptionalChange($new_str);     
                        $updatedParagraph=$this->ExceptionalChange($updatedParagraph);   
                        
                        $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $input['to_language']);

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
                return response()->json(array('success'=>true));
            }


            /* Google APi call And Translate */
            
            elseif($LanguagePairDetails['api']  ==  'G'){
                if(!empty($input['from_string'])){
                    $search_arr = $input['from_string'];
                    $replace_arr = '<cite class="notranslate">'.$input['to_string'].'</cite>';
                }
                //$ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->where('original_data','LIKE','%'.$input['from_string'].'%')->get();
                $ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->get();
                $MatchParagraphDetails = [];

                if(!empty($ParagraphDetails)){
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

                        if (strpos($stringOriginal, $search_arr) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                    foreach($MatchParagraphDetails as $line){
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
                        if (strpos($new_str, $search_arr) !== false ) {

                            $Paragraph = ProjectData::where('id',$line['id'])->first();
                            
                            $new_str = str_replace($search_arr, $replace_arr, $new_str);

                            // Google Api Call //
                            $updatedParagraph = $this->GoogleTranslateApi($SourceLanguageCode, $DestinationLanguageCode, $new_str);
                                   
                            $updatedParagraph = str_replace('<cite class="notranslate">', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                            $ReplaceString = $replace_arr;

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
                            
                            $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $input['to_language']);

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

                            $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', '1');
                        }
                    }
                }
                return response()->json(array('success'=>true));
            }

            /* Amazon Api Call And Translate*/
            elseif($LanguagePairDetails['api']  ==  'A'){


                if(!empty($input['from_string'])){
                    $search_arr = $input['from_string'];
                    $replace_arr = '<cite>'.$input['to_string'].'</cite>';
                }
                //$ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->where('original_data','LIKE','%'.$input['from_string'].'%')->get();
                $ParagraphDetails = ProjectData::where('p_id',$input['project_id'])->where('language_id',$input['to_language'])->get();
                $MatchParagraphDetails = [];

                if(!empty($ParagraphDetails)){
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

                        if (strpos($stringOriginal, $search_arr) !== false) {
                            $MatchParagraphDetails[] = $line;
                        }    
                    }
                    foreach($MatchParagraphDetails as $line){
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
                        if (strpos($new_str, $search_arr) !== false ) {

                            $Paragraph = ProjectData::where('id',$line['id'])->first();
                            
                            $new_str = str_replace($search_arr, $replace_arr, $new_str);

                            $FirstPosition = strpos($new_str,"<cite>");
                            $LastPosition = strpos($new_str,"</cite>");

                            $SubStr = substr($new_str,$FirstPosition,$LastPosition);

                            $new_str = str_replace($SubStr, 'a123a ', $new_str);

                            

                            

                            // Amazon Api Call //

                            $updatedParagraph = $this->AmazonTranslateApi($SourceLanguageCode, $DestinationLanguageCode, $new_str);

                           
                            $updatedParagraph = str_ireplace('a123a ', $SubStr, $updatedParagraph);

                            // dd($updatedParagraph);
                            
                                   
                            $updatedParagraph = str_replace('<cite>', "", $updatedParagraph);
                            $updatedParagraph = str_replace('</cite>', "", $updatedParagraph);

                            $ReplaceString = $replace_arr;

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
                            
                            $this->InsertTextCount($ProjectDetails['id'], $TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $input['to_language']);

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

                            $this->InsertDataVersion($Paragraph['id'], $updatedParagraph, 'U', '1');
                        }
                    }
                }
                return response()->json(array('success'=>true));



            }
        }
    }

    function creditUsed($user_id,$SubscriptionDetails)
    {
        $res=  DB::table('text_count')->select( DB::raw('SUM(credit_used) as credit_used'))->where('user_id',$user_id)->whereBetween('translate_date', [$SubscriptionDetails['start_date'], $SubscriptionDetails['end_date']])->get();
    
        return $res[0]->credit_used;
    }









    function DeeplApi($text,$url,$mainUrl,$from_language,$to_language) // Deeepl
    {
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
      
        // $handleclassArray = [];
        // $handleclassArray['|a2|']='|a2#|';
        // $handleclassArray['|span|']='|span#|';
        
        $OriginalHtmlSourceCode=$text;  // Full page HTML source Code 

        /* Replace The Original Html Code using TagArray */
        foreach($TagArray as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }
        foreach($TagArraystart as $key => $val){
            $OriginalHtmlSourceCode=str_replace($key, $val, $OriginalHtmlSourceCode);
        }

        $ProjectDetails = Projects::where('website_url',$mainUrl)->first();

        /* Source Language Details */
        $sourcetLanguageListDetails =  LanguageList::where('id',$ProjectDetails['current_language_id'])->first();
        
        $sourceLanguage['sortname'] = $sourcetLanguageListDetails['sortname'];
        $sourceLanguage['name'] = $sourcetLanguageListDetails['name'];

        /* Destination Language */
        $DestinationLanguageListDetails = LanguageList::where('id',$to_language)->first();
        $destinationLanguage['sortname'] = $DestinationLanguageListDetails['sortname'];
        $destinationLanguage['name'] = $DestinationLanguageListDetails['name'];

             
        $ProjectLanguagesList = ProjectLanguages::where('p_id',$ProjectDetails['id'])->where('visibility_status', '!=', 2)->where('visibility_status', '!=', 1)->get();

        foreach($ProjectLanguagesList as $Language){
            $LanguageListDetails =  LanguageList::where('id',$Language['language_id'])->first();
            $Language['sortname'] = $LanguageListDetails['sortname'];
            $Language['name'] = $LanguageListDetails['name'];
        }
        
        /*MetaData Section */

        /* Meta data match REGEX pattern*/
        $pattern = '~<\s*meta\s(?=[^>]*?\b(?:name|property|http-equiv)\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=)))        
        [^>]*?\bcontent\s*=\s*(?|"\s*([^"]*?)\s*"|\'\s*([^\']*?)\s*\'|([^"\'>]*?)(?=\s*/?\s*>|\s\w+\s*=))[^>]*>~ix';

        /* Check HTML code section */

        $HtmlCodeDetails = SourceCode::where('page_url',$url)->where('lang_code',$to_language)->first();
        
        if(empty($HtmlCodeDetails) || $HtmlCodeDetails == null){

            /* Insert here */
            /* Insert Page Details Into Source Code Of html table */

            $SourceCode = new SourceCode();

            $SourceCode->page_url = $url;//$url;
            $SourceCode->p_id = $ProjectDetails['id'];
            $SourceCode->html_code = $text;  // Original Source Code
            $SourceCode->translated_html_code = $text;//$OriginalHtmlSourceCode; // Here Translated HTML source code 
            $SourceCode->lang_code = $to_language;
            $SourceCode->status = 0;

            $SourceCode->save();

            /* Now fetch Meta data content And Main Body Content From HTML Source Code */

            /* First Meta Data Section */
            if($ProjectDetails['metadata_translation'] == 1){

                $MetaDataArray = [];

                /* Now first fetch Meta data content */

                if(preg_match_all($pattern, $OriginalHtmlSourceCode, $out)){
                    /* This is Meta data content Array */
                    $MetaDataContent = $out;
                }
                else{
                    $MetaDataContent = null;
                }
                $MetaData = [];
                $FullMetaData=[];
                if(!empty($MetaDataContent) || $MetaDataContent != null)
                {
                    $FullData = $MetaDataContent[0];   
                    $IndexName = $MetaDataContent[1];
                    $IndexValue = $MetaDataContent[2];
                    for($i=0 ; $i<sizeof($IndexName); $i++){
                        if (strpos($IndexName[$i], 'title') !== false || strpos($IndexName[$i], 'description') !== false || strpos($IndexName[$i], 'keywords') !== false) {
                            $FullMetaData[] = $FullData[$i];
                        }
                        else{
                            $FullMetaData = [];
                        }
                    }
                }

                if(count($FullMetaData)>0){
                    foreach($FullMetaData as $Mdata){

                        $data = $this->DeeplTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $Mdata);

                        $OriginalMetaData[] = $Mdata;
                        $TranslateMetaData[] = $data;
                        $MetaDataArray[$Mdata] = $data;
                    }

                    for($i=0; $i<sizeof($OriginalMetaData); $i++){

                        preg_match_all($pattern, $OriginalMetaData[$i], $out);
                        $OriginalMetaDataArray[$i] = $out[2];

                        preg_match_all($pattern, $TranslateMetaData[$i], $out);
                        $TranslateMetaDataArray[$i] = $out[2];
                    }

                    if(count($OriginalMetaDataArray)!=0){

                        /* Translate All Meta Data In Below */

                        for($i=0; $i<count($OriginalMetaDataArray); $i++ ){
                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $OriginalMetaDataArray[$i])->where('data_section',4)->get();
                            if(sizeof($ParagraphDetails) == 0){

                                $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], implode($OriginalMetaDataArray[$i]), implode($OriginalMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), implode($TranslateMetaDataArray[$i]), 4, $to_language, 0);

                                $this->InsertDataVersion($ParagraphId, implode($TranslateMetaDataArray[$i]), 'M', 1);

                                $this->InsertTextCount($ProjectDetails['id'], implode($OriginalMetaDataArray[$i]), $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);
                            }
                        } 
                    }
                    
                    foreach( $MetaDataArray as $key => $val){
                        $OriginalHtmlSourceCode = str_replace($key, $val, $OriginalHtmlSourceCode);
                    }
                }
            }

            /* End Meta Data Section */

            /* Now Main Body data Section */

            // $OriginalHtmlSourceCode = "<html>".$OriginalHtmlSourceCode."</html>";
            
            $doc = new \DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTML($OriginalHtmlSourceCode);
        
            $htmls = $doc->getElementsByTagName('html');

            if($htmls->length > 0)
            {
                $explodeData = $this->FetchHtmlData($htmls);
            }

           
            
            foreach($explodeData as $HtmlText){

                if($HtmlText !=null  || !empty($HtmlText) || $HtmlText != " " || $HtmlText != "\t" ){

                    $DBMatchHtmlText = $HtmlText;
                    
                    foreach($TagArray as $key => $val){
                            $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    foreach($TagArraystart as $key => $val){
                        $HtmlText=str_replace($val, $key, $HtmlText);
                    }
                    $TestHtmlText = $HtmlText;
                    $TestHtmlText = str_replace("\r\n","",$TestHtmlText);
                    $TestHtmlText = str_replace("\t","",$TestHtmlText);
                    

                    $TestHtmlText = strip_tags(trim($TestHtmlText));

                    if(preg_match('/[A-Za-z]/', $TestHtmlText) || preg_match('/[0-9]/', $TestHtmlText)){

                        if($TestHtmlText !== 'BESbswy' && str_word_count($TestHtmlText)>1){

                            $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $DBMatchHtmlText)->get();
                                    
                            if(sizeof($ParagraphDetails) == 0){
                                /* Call Google APi For Translate HtmlText*/

                                $outputText = $this->DeeplTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);
                                //$this->GoogleTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);

                                $content_original_data = strip_tags($HtmlText);
                                $content_translated_data = strip_tags($outputText);

                                foreach($TagArray as $key => $val){
                                        $HtmlText=str_replace($key, $val, $HtmlText);
                                        $outputText=str_replace($key, $val, $outputText);
                                }
                                foreach($TagArraystart as $key => $val){
                                    $HtmlText=str_replace($key, $val, $HtmlText);
                                    $outputText=str_replace($key, $val, $outputText);
                                }

                                if(sizeof($ParagraphDetails) == 0){

                                    $HtmlText = $this->ExceptionalChange($HtmlText);
                                    $outputText = $this->ExceptionalChange($outputText);

                                    $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], $HtmlText, $content_original_data, $outputText, $content_translated_data, 5, $to_language, 0);

                                    $this->InsertDataVersion($ParagraphId, $outputText, 'M', 1);

                                    $this->InsertTextCount($ProjectDetails['id'], $TestHtmlText, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);
                                }
                            }   
                        }
                    }
                }
            }


            // $new_arr=[];
            // foreach($explodeData as $HtmlText){

            //     if($HtmlText !=null  || !empty($HtmlText) || $HtmlText != " " || $HtmlText != "\t" ){

            //         foreach($handleclassArray as $key => $val){

            //             $lastPos = 0;
            //             $positions=array();
            //             while (($lastPos = strpos($HtmlText, $key, $lastPos))!== false) {
            //                 $positions[] = $lastPos;
            //                 $lastPos = $lastPos + strlen($key);
            //             }

            //             $exr=0;
            //             foreach ($positions as $value) {
            //                 $value=$value+$exr;
            //                 $firstoccurIndex= strpos($HtmlText,'>',$value);
            //                 $HtmlText=substr_replace($HtmlText,"|>|</para>",$firstoccurIndex,1);
            //                 $exr=$exr+9;
            //             }
            //         }
                    
            //         foreach($TagArraystart as $key => $val){
            //             $HtmlText=str_replace($val, '<para>'.$val, $HtmlText);
            //         }
                    
            //         foreach($TagArray as $key => $val){
            //                 $HtmlText=str_replace($val, '<para>'.$val.'</para>', $HtmlText);   
            //         }

            //         $data = $this->DeeplTranslateApi($sourceLanguage['sortname'], $destinationLanguage['sortname'], $HtmlText);

            //         $new_arr[$HtmlText]=$data;
                    
            //         /* Now Save The Translated data Into DB */
            //         foreach($TagArray as $key => $val){
            //             $data=str_replace($key, $val, $data);
            //         }
            //         foreach($TagArraystart as $key => $val){
            //             $data=str_replace($key, $val, $data);
            //         }

            //         $data=str_replace('<para>', '', $data);
            //         $data=str_replace('</para>', '', $data);
            //         $HtmlText=str_replace('<para>', '', $HtmlText);
            //         $HtmlText=str_replace('</para>', '', $HtmlText);

            //         $TestHtmlText = $HtmlText;

            //         /* For Pure String */
            //         $content_original_data = $HtmlText;
            //         $content_translated_data = $data;
            //         foreach($TagArray as $key => $val){
            //             $content_original_data=str_replace($val, $key, $content_original_data);
            //             $content_translated_data=str_replace($val, $key, $content_translated_data);
            //         }
            //         foreach($TagArraystart as $key => $val){
            //             $content_original_data=str_replace($val, $key, $content_original_data);
            //             $content_translated_data=str_replace($val, $key, $content_translated_data);
            //         }

            //         $content_original_data = strip_tags($content_original_data);
            //         $content_translated_data = strip_tags($content_translated_data);   
                    
            //         foreach($TagArraystart as $key => $val){
            //             $TestHtmlText=str_replace($val, $key, $TestHtmlText);
            //         }
                    
            //         foreach($TagArray as $key => $val){
            //                 $TestHtmlText=str_replace($val, $key, $TestHtmlText);
            //                 $TextCountString = $TestHtmlText;    
            //         }
            //         $TestHtmlText = str_replace("\r\n","",$TestHtmlText);
            //         $TestHtmlText = str_replace("\t","",$TestHtmlText);
                    
            //         $TestHtmlText = strip_tags(trim($TestHtmlText));
            //         $TextCountString = strip_tags(trim($TextCountString));

            //         if(preg_match('/[A-Za-z]/', $TestHtmlText) || preg_match('/[0-9]/', $TestHtmlText)){
            //             if($TestHtmlText !== 'BESbswy'){
            //                 $ParagraphDetails = ProjectData::where('p_id',$ProjectDetails['id'])->where('language_id',$to_language)->where('original_data', '=', $HtmlText)->get();
            //                 if(sizeof($ParagraphDetails) == 0){

            //                     $HtmlText = $this->ExceptionalChange($HtmlText);
            //                     $data = $this->ExceptionalChange($data);

            //                     $ParagraphId = $this->InsertProjectData($ProjectDetails['id'], $SourceCode['id'], $HtmlText, $content_original_data, $data, $content_translated_data, 5, $to_language, 0);

            //                     $this->InsertDataVersion($ParagraphId, $data, 'M', 1);

            //                     $this->InsertTextCount($TextCountString, $ProjectDetails['user_id'], $ProjectDetails['current_language_id'], $to_language);

            //                 }
            //             }
            //         }
            //     }
            // }
            
            /* Now Replace The Data matching from DB And Response back*/

            $TranslatedData = ProjectData::where('p_id',$ProjectDetails['id'])->where('source_code_id',$SourceCode['id'])->where('language_id',$to_language)->get();
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

            $SourceCode->update([
                'translated_html_code' => $new_html,
            ]);
            return true;
        }
        return false;
    }





    /* Chat Box Api Section */
    public function ChatBox(Request $request){
        $LanguagesList = LanguageList::get();
        if($request['db'] == 'Y'){
            $ChatData = Chatdata::where('text', 'LIKE', $request['text'])->where('from_language',$request['origin_language'])->where('to_language',$request['destination_language'])->where('api',$request['api'])->first();
            if(!empty($ChatData)){
                $TranslatedText = $ChatData['translated_text'];
                return view('user.myProject.test_chat_box',compact('TranslatedText','LanguagesList'));
            }
            else{
                if($request['api'] === 'A'){
                    $TranslatedText = $this->AmazonTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
                }
                elseif($request['api'] === 'D'){
                    $TranslatedText = $this->DeeplTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
                }
                elseif($request['api'] === 'G'){
                    $TranslatedText = $this->GoogleTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
                }
                $ChatData = new ChatData();
                $ChatData->from_language=$request['origin_language'];
                $ChatData->to_language=$request['destination_language'];
                $ChatData->text=$request['text'];
                $ChatData->translated_text=$TranslatedText;
                $ChatData->api=$request['api'];
                $ChatData->save();
                return view('user.myProject.test_chat_box',compact('TranslatedText','LanguagesList'));
            }
        }
        else{
            if($request['api'] === 'A'){
                $TranslatedText = $this->AmazonTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
            }
            elseif($request['api'] === 'D'){
                $TranslatedText = $this->DeeplTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
            }
            elseif($request['api'] === 'G'){
                $TranslatedText = $this->GoogleTranslateApi($request['origin_language'], $request['destination_language'], $request['text']);
            }
            $ChatData = new ChatData();
            $ChatData->from_language=$request['origin_language'];
            $ChatData->to_language=$request['destination_language'];
            $ChatData->text=$request['text'];
            $ChatData->translated_text=$TranslatedText;
            $ChatData->api=$request['api'];
            $ChatData->save();
            return view('user.myProject.test_chat_box',compact('TranslatedText','LanguagesList'));
        }
    }











}
?>