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
use Validator;
use Storage;
use Auth;
use DB;

use Spatie\PdfToText\Pdf;
use Smalot\PdfParser\Parser;
use \ConvertApi\ConvertApi;


use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
set_time_limit(3600);
class ApiController extends Controller
{
    function read_docx(){

        $string='<w:p w:rsidR="00573201" w:rsidRDefault="00573201" w:rsidP="00374161"><w:pPr><w:pStyle w:val="NoSpacing"/><w:rPr><w:b/><w:bCs/><w:color w:val="FF0000"/><w:u w:val="single"/></w:rPr></w:pPr><w:r w:rsidRPr="00026D06"><w:rPr><w:color w:val="000000" w:themeColor="text1"/><w:u w:val="single"/></w:rPr><w:t xml:space="preserve">Admin ajoutera toutes les villes. Chaque ville sera un petit portail ind�pendant avec </w:t></w:r><w:r w:rsidR="004B3DAE"><w:rPr><w:color w:val="000000" w:themeColor="text1"/><w:u w:val="single"/></w:rPr><w:t xml:space="preserve">amis, </w:t></w:r><w:r w:rsidRPr="00026D06"><w:rPr><w:color w:val="000000" w:themeColor="text1"/><w:u w:val="single"/></w:rPr><w:t>pages et groupes.</w:t></w:r>';
    $y=preg_replace('/<\s*w:t[^>]*>/', "| ",strip_tags($string,'<w:t>')) ;
$y=str_replace('</w:t>','|',str_replace("| ","",$y),$y);

$yr=explode('|',$y);
dd($yr);
       // $string="hello, apurba hello world";
//echo Str::replaceFirst('hello','hi',$string);
    //    if(Str::startsWith($string, 'apurba hello world'));
    //  echo str_replace('hello','hi',$string);
    //    echo $string;
    //    echo 'valid';
      //exit;
        $fileName="New_TestTable.docx";
        $filename = public_path('/assets/upload/'.$fileName);
        $destFile=public_path('assets/upload/'.'ab.docx');
        $details_arr=[];
        $content_header='';
        $zip = zip_open($filename);
        $header_arr=[];
        $footer_arr=[];

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
           
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            echo $header_substr=substr(zip_entry_name($zip_entry),0,17);
           // echo '<br>';
            if ($header_substr!="word/_rels/header") continue;
            $raw_s=explode('/',zip_entry_name($zip_entry));
            $header_arr[]=substr($raw_s[2],0,strlen($raw_s[2])-5);
            //$content_header.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }
        zip_close($zip);

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
           
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;
            echo $header_substr=substr(zip_entry_name($zip_entry),0,17);
           // echo '<br>';
            if ($header_substr!="word/_rels/footer") continue;

            $raw_s=explode('/',zip_entry_name($zip_entry));
            print_r($raw_s);
            $footer_arr[]=substr($raw_s[2],0,strlen($raw_s[2])-5);
            //$content_header.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }
        zip_close($zip);

      dd($header_arr);
      dd($footer_arr);

      
        $striped_content = '';
        $content = '';

        $zip = zip_open($filename);

        if (!$zip || is_numeric($zip)) return false;

        while ($zip_entry = zip_read($zip)) {
           
            if (zip_entry_open($zip, $zip_entry) == FALSE) continue;

            if (zip_entry_name($zip_entry) != "word/document.xml") continue;

            $content.= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
            
            zip_entry_close($zip_entry);
        }

        zip_close($zip);


       



         
         
        //  $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        //  $content = str_replace('</w:r></w:p>', "", $content);


         //$content = str_replace('&', "and ", $content);
        

        // $content = str_replace('<w:p>', "\C1 ", $content);
         //$content = str_replace('<w:p w:rsidR="00573201" w:rsidRDefault="00573201" w:rsidP="00374161">', "\C1 ", $content);
         
         $content = preg_replace('/<\s*w:p[^>]>/', "\C1 ", $content);

        //  dd($content);
        
         //$content = str_replace('<w:p xml:space="preserve">', "\C1 ", $content);
      
        
         $content = str_replace('</w:p>', "\C2 ", $content);

        //  dd($content);
         //dd(strip_tags(str_replace('<w:t></w:t>',"",'<w:t>hello world India tchno Exponent</w:t>')));
      
        //  $data= $striped_content = strip_tags($content);
       // $data= $striped_content;
        //
        $data=$content;
         $arr = explode("\C1 ",$data);
        // dd($arr);
         $arr = explode("\C2 ",implode($arr));

         //dd($arr);
         $c1=0;
         foreach($arr as $c){
            //  if($c1<80)
            //  {
             $details_arr[$c] = $c;
            //  }
            //  else
            //  {
            //      break;
            //  }
             $c1++;
         }
         //dd($details_arr);
         
        //dd($arr);
        // $j=0;
        // $nesArray = array();
        // for($i=1; $i<strlen($data); $i++){
        //     if($data[$i] === "\n"){
        //         /* Now insert into Db */
                

        //             if(!empty($nesArray)){
        //                 $ProjectData = new ProjectData();
        //                 $ProjectData->project_id = '7777777';
        //                 $ProjectData->paragraph_id = uniqid();
        //                 $ProjectData->data = implode('', $nesArray);
        //                 $ProjectData->save();
        //                 $details_arr[implode('', $nesArray)]=implode('', $nesArray);

        //             }
                
        //         $j=0;
        //         $nesArray = array();
        //     }
        //     else{
    
        //         if($data[$i]!= "\n"){
        //             $nesArray[$j] = $data[$i];
        //             $j++;
        //         }
        //     }
        // }

       
    
        foreach($details_arr as $key => $val)
        {
            
            $details_arr[$key]=$this->testApi($val);
          
        }
        //dd($details_arr);

     
        $zip = new \PhpOffice\PhpWord\Shared\ZipArchive;

        //This is the main document in a .docx file.
        $fileToModify = 'word/document.xml';

        $file = public_path('/assets/upload/New_TestTable.docx');
        $temp_file = public_path('/assets/upload/ab.docx');
        copy($file,$temp_file);
//         foreach($details_arr as $key => $val)
//             {

//         $docx = new \IRebega\DocxReplacer\Docx($temp_file);

// $docx->replaceText($key, $val);
//             }
//             dd($details_arr);

        if ($zip->open($temp_file) === TRUE) {
            //Read contents into memory
            $oldContents = $zip->getFromName($fileToModify);
            $newContents=$oldContents;
        // dd ($newContents);
            foreach($details_arr as $key => $val)
            {
                echo $key.'----------------->'.$val;
                
                echo '<br><br>';

             
                $newContents = str_replace($key,$val, $newContents);
               //$newContents=Str::replaceFirst($key,$val, $newContents);
               
                //dd($newContents);

            }
           
            //exit();

          
            //Delete the old...
            $zip->deleteName($fileToModify);
           // Write the new...
            $zip->addFromString($fileToModify, $newContents);
            
            //And write back to the filesystem.
            $return =$zip->close();
            If ($return==TRUE){
                echo "Success!";
                dd($details_arr);
            }
        } else {
            echo 'failed';
        }
      }


//       public function testApi($test_data='')
//     {
//        // $test_data='<w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:t>Transport</w:t></w:r><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:tab/></w:r><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:tab/></w:r><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:tab/></w:r><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:t>:  binnen Nederland &amp; België, gelijkvloers.</w:t></w:r>';
//         //$test_data = '<w:p w:rsidP="00374161" w:rsidRDefault="00573201" w:rsidR="00573201"><w:pPr> <w:pStyle w:val="NoSpacing"/><w:rPr><w:b/><w:bCs/> <w:color w:val="FF0000"/><w:u w:val="single"/></w:rPr></w:pPr><w:r w:rsidRPr="00026D06"><w:rPr><w:t xml:space="preserve">Admin will add all cities. Each city will be small independent portal with </w:t></w:r><w:r w:rsidR="004B3DAE"><w:rPr> <w:color w:val="000000" w:themeColor="text1"/> <w:u w:val="single"/></w:rPr><w:t xml:space="preserve">friends, </w:t></w:r> <w:r w:rsidRPr="00026D06"></w:p><w:r w:rsidRPr="00026D06"><w:rPr><w:color w:val="000000" w:themeColor="text1"/><w:u w:val="single"/></w:rPr><w:t>pages and groups.</w:t></w:r></w:p>';
//        // $test_data='<w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr></w:pPr><w:r><w:rPr><w:rFonts w:asciiTheme="majorHAnsi" w:hAnsiTheme="majorHAnsi" w:cstheme="majorHAnsi"/><w:sz w:val="22"/></w:rPr><w:t>Mimaki biedt 24 maanden fabrieksgarantie op alle machine onderdelen inclusief printkoppen. De garantie omvat echter niet de plaatsing van deze onderdelen. Met de On-Site garantie uitbreiding staat DIMIX garant voor het gratis plaatsen van deze onderdelen op uw locatie. Hiermee voorkomt u onvoorziene kosten.</w:t></w:r>';
//         $client = new \GuzzleHttp\Client();
// //$test_data='hello world<w:t>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</w:t>';
//         // 'https://api.deepl.com/v2/translate?text=Hello%20World. The chair is black.!&target_lang=DE&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357'
//        // $test_data='Hello world <para>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</para>';
//         $request = $client->post('https://api.deepl.com/v2/translate?text='.$test_data.'&tag_handling=xml&target_lang=FR&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357');
//       $response=$request->getBody();
     
//         $res=json_decode($response);
//         //dd($res->translations[0]);
//       return $res->translations[0]->text;
//     }

    public function testApi($test_data)
    {
       // $test_data = "Beauty, Health & Household";
        $client = new \GuzzleHttp\Client();

        $options = [
            'form_params' => [
                "text" => $test_data
               ]
           ]; 
        
        //$test_data='hello world<w:t>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</w:t>';
        // 'https://api.deepl.com/v2/translate?text=Hello%20World. The chair is black.!&target_lang=DE&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357'
        // $test_data='Hello world <para>The standard header will only be seen on the homepage, while subsequent pages will only see a header with the page title and an “X” on the left.</para>';
        $request = $client->post('https://api.deepl.com/v2/translate?&target_lang=FR&tag_handling=xml&auth_key=dfec9916-d46f-c906-37be-c4f68ab97357',$options);
        $response=$request->getBody();
        $res=json_decode($response);
        return $res->translations[0]->text;
    }

    public function read_test()
    {
        return 'hello';
    }





    /* Check */
    public function check(){

        $fileName = public_path('/assets/upload/user/project_documentation/123.pdf');
        $outFileName = public_path('/assets/upload/user/project_documentation/out123.pdf');

        // $file_contents = file_get_contents($fileName);

        // // dd($file_contents);
        // $file_contents = str_replace("DOWNLOADING PDF FILES IN A WEB BROWSER",",OKAY WORLD",$file_contents);
        // file_put_contents($outFileName,$file_contents);



        // include 'File/SearchReplace.php' ;
 
        $files_to_search = array($fileName) ;
        $search_string  = "DOWNLOADING PDF FILES IN A WEB BROWSER";
        $replace_string = "OKAY WORLD";
        
        $snr = new File_SearchReplace($search_string,
                                    $replace_string,
                                    $files_to_search,
                                    '', // directorie(s) to search
                                    false) ;
        
        $snr->doSearch();
        
        echo "The number of replaces done : " . $snr->getNumOccurences();
        dd("OKAY");

        


    }

    
}
?>