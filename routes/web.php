<?php

/* 
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Request;
use Aws\Translate\TranslateClient;
use Aws\Exception\AwsException;

Route::get('/', function () {


    if(Auth::check() && Auth::user()->role_id==2){
        return redirect()->route('my_project');
    }
    elseif(Auth::check() && Auth::user()->role_id==1){
        return redirect()->route('admin_dashboard');
    }
    else{
        return view('auth.login'); 
    }
});

define('ADMIN_PREFIX', 'admin');

//Auth::routes();



// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Registration Routes...
Route::get('register/{code?}', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// Password Reset Routes...
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');


//Auth::routes(); 
// Authentication Routes... 
Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('login'); 
Route::post('admin/login', 'Auth\LoginController@login'); 
Route::post('admin/logout', 'Auth\LoginController@logout')->name('logout'); 
// Password Reset Routes... 
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request'); 
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email'); 
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset'); 
Route::post('password/reset', 'Auth\ResetPasswordController@reset');




/* Login With Google Route*/
Route::get('auth/google', 'Auth\LoginController@redirectToGoogle');
Route::get('auth/google/callback', 'Auth\LoginController@handleGoogleCallback');

// NORMAL USER ROUTE START //
Route::get('/active/{token}','User\ProfileController@AccountActive'); 
Route::get('/registration_success', 'HomeController@registration_success');

Route::get('public/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/successful-account-activation',function(){
    return view('user.successful_account_activation'); 
});
//Route::get('/successful-account-activation','User\FreePlanCreateController@FreePlanCreate');

    Route::group(['middleware' => ['auth','auth.user']], function () {

            Route::get('/home', 'HomeController@index')->name('home');
            Route::get('/my-profile','User\ProfileController@myprofile')->name('my_profile');
            Route::post('/profile-update','User\ProfileController@ProfileUpdate')->name('profile_update');

            Route::get('/change-password','User\ProfileController@ChangePassword')->name('change_password');
            Route::post('/change-password-process','User\ProfileController@ChangePasswordProcess')->name('change_password_process');
            
            /* Buy Plan Section */
            Route::get('/buy-plan','User\BuyPlanController@planList')->name('plan_list');
            Route::get('/buy-plan/payment-form/{id}','User\BuyPlanController@PaymentForm')->name('payment_form');
            Route::post('payment','User\BuyPlanController@Payment')->name('payment');

            /* User My Project Section */
            Route::get('/my-project/{ProjectNameSearch?}','User\MyProjectController@MyProjectsList')->name('my_project');
            Route::get('/edit-project-web/{id?}','User\MyProjectController@AddNewProject')->name('add_new_project');  // Web Project
            Route::get('/edit-doc-project/{id?}','User\MyProjectController@AddNewDocProject')->name('add_new_doc_project'); // Doc Project 
            Route::post('/submit-project','User\MyProjectController@SubmitProject')->name('submit_project'); // Web Project
            Route::post('/submit-doc-project','User\MyProjectController@SubmitDocProject')->name('submit_doc_project');

            Route::get('/language-details/{id?}','User\TestController@index')->name('language_details');


            //
            //DownloadUpdatedProject


            Route::post('/website-execute','ApiController@webSiteExecute')->name('website_execute'); 
            
            Route::post('/website-execute-google-api','ApiController@webSiteExecuteGoogleApi')->name('website_execute_google_api'); 
 
            Route::post('/website-execute-amazon-api','ApiController@webSiteExecuteAmazonApi')->name('website_execute_amazon_api'); 


            /* Get Language Pair List As Per Current Language Select */ 
            Route::post('/get-language-pair','User\MyProjectController@GetLanguagePair')->name('get_language_pair_list');


            /* Delete Project Instant */
            Route::get('/delete-project','User\MyProjectController@DeleteProject')->name('delete_project');

            /* Download Documentation Project */
            Route::get('/download-project/{name?}','User\MyProjectController@downloadFile')->name('download_project');

        
            Route::get('/test-download','User\MyProjectController@downloadFile');



            /* ProofReading Section */ 
                Route::any('/proof-reading/{project_id}/{language_id}/{val?}','User\ProofReadingController@ListProofReading')->name('proof_reading_dashboard');

                //Route::get('/proof-reading-page/{project_id}/{language_id}/{page?}','User\ProofReadingController@ListProofReadingPage')->name('proof_reading_dashboard');


                

                Route::post('/add-string-correction','User\ProofReadingController@doNotTranslate')->name('do_not_translate');
                Route::post('/delete-string-correction','User\ProofReadingController@deleteDoNotTranslate')->name('delete_translate_string');
                Route::post('/always-translate-as','User\ProofReadingController@alwaysTranslateAS')->name('always_translate_as');
                

            /* Download Updated Project */

            Route::get('/updated-project/{project_id}/{destination_lang}','User\ProofReadingController@UpdatedProject')->name('updated_project');

            Route::get('/download-updated-project/{project_id}/{destination_lang}','User\ProofReadingController@DownloadUpdatedProject')->name('download_updated_project');

            Route::get('/update-web-html/{project_id}/{destination_lang}','User\ProofReadingController@UpdateWebHtml')->name('update_web_html');
            /* Approved Paragraph Section */

                 Route::get('/approved-paragraph','User\ProofReadingController@ApprovedParagraph')->name('approved_paragraph');
            
            /* Update Paragraph By User End */

                Route::get('/update-paragraph','User\ProofReadingController@UpdateParagraph')->name('update_paragraph');

                Route::get('/update_website','User\ProofReadingController@UpdateParagraphWebsite')->name('update_website');
            /* Replace String On Fly */
                Route::post('/replace-translated-string','User\ProofReadingController@ReplaceTranslatedString')->name('replace_translated_string');


 

            /* StringCorrection Section */
            ///proof-reading/{project_id}/{language_id}  

            Route::get('/string-correction/{project_id}/{language_id}/{tab_id?}','User\StringCorrectionController@index')->name('string_correction_dashboard');

            Route::post('/string-correction-change','User\StringCorrectionController@index1')->name('index1');

            // /* Read File Data */ 

            //     Route::get('/read-file-doc','ReadFileController@read_doc')->name('read_doc');


            /* Translate Data In Deepl Api Section */

                /* Deepl Api Call */

            Route::get('/translate-data','User\StringCorrectionController@TranslateData')->name('translate_data');

            /* Select Projects for String Corrections */
            Route::post('/string-correction','User\ProofReadingController@SelectStringCorrectionProject')->name('select_string_correction_project');

            Route::get('/xml','User\MyProjectController@testApi');
            Route::get('/xmltest','User\ApiController@read_docx');


            /* Version Data Section */
            Route::POST('/version-data','User\ProofReadingController@VersionData')->name('version_data');

            Route::POST('/version-data-swap','User\ProofReadingController@VersionControllerSwap')->name('version_data_swap');


            /* Transaction History */
            Route::get('/transaction-history','User\TransactionHistoryController@TransactionHistory')->name('transaction_history');

            /* Help And Feq */ 
            Route::get('/help&feq','User\HelpAndFeqController@HtmlAndFeq')->name('help_and_faq');

            /* Become A Proofreader */
            Route::get('/become-proofreader','User\BecomeProofreaderController@BecomeProofreader')->name('become_Proofreader');

            /* Invite A Friend */
            Route::get('/invite-friend','User\InviteFriendController@InviteFriend')->name('invite_friend');

            Route::post('/invite-whatsApp','User\InviteFriendController@InviteFriendWhatsApp')->name('InviteWhatsApp');

            Route::get('/invite-facebbok','User\InviteFriendController@InviteFriendFaceBook')->name('InviteFaceBook');

            Route::post('/invite-by-email','User\InviteFriendController@MailSend')->name('MailSend');

            Route::post('/invite-by-linkedin','User\InviteFriendController@LinkedinShare')->name('LinkedinShare');

    });








// NORMAL USER ROUTE END //

// Route::get('/admin_dashboard', 'AdminController@dashboard')->name('admin_dashboard');









// ADMIN ROUTE START // 

 Route::group(['namespace' => 'Admin', 'prefix' => ADMIN_PREFIX,'middleware' => ['auth','auth.admin']], function () {
    
        Route::get('/admin_dashboard', 'AdminController@dashboard')->name('admin_dashboard');


        /* Language Pair Section*/

        Route::get('/languagePair-list','LanguageController@index')->name('language_list');

        Route::post('/rest-languagePair-list','LanguageController@restLanguageList')->name('rest_language_list');

        Route::post('/add-language-pair','LanguageController@saveLanguagePair')->name('add_language_pair');

        Route::get('/add-language-pair/{id?}','LanguageController@UpdateLanguagePair')->name('update_language_pair');

 

        Route::get('delete-language-pair','LanguageController@DeleteLanguagePair')->name('delete_language_pair');

 

        /* Project Category Section */

        Route::get('/project-category','ProjectCategoryController@ProjectCategoryList')->name('project_category_list'); 

        Route::post('/add-project-category','ProjectCategoryController@AddProjectCategory')->name('add_project_category');

        Route::get('/add-project-category/{id?}','ProjectCategoryController@UpdateProjectCategory')->name('update_project_category');

        Route::get('delete-project-category','ProjectCategoryController@DeleteProjectCategory')->name('delete_project_category');
        

        /* Client Section*/

        Route::get('/client-list','ClientController@index')->name('client_list');
        Route::get('/client-details/{id}','ClientController@ClientDetails')->name('client_details');

        Route::get('/download-invoice/{id?}','ClientController@DownloadInvoice')->name('download_invoice');

        /* Credit Plans Section */


        /* Add Plans URL Only For ADD by Admins */

        Route::get('/add-plans','CreditPlansController@addPlansForm')->name('add_plans_form');
        Route::post('/add-plans_success','CreditPlansController@addPlan')->name('add_plan');

        /* END Add Plans URL Only For ADD by Admins */


        /* Update Plans Sections */
        Route::get('/credit-plans','CreditPlansController@CreditPlansList')->name('Credit_Plans_List');

        Route::get('/credit-plans/{id}','CreditPlansController@updatePlans')->name('update_plans'); 

        Route::post('/save-plans/{id}','CreditPlansController@savePlan')->name('save_plan');
        Route::get('/product-delete','CreditPlansController@index')->name('product-delete');

        /* Credit Purchase Invoices Section */
        Route::any('/purchase-invoices','CreditPurchaseInvoiceController@CreditPurchaseInvoiceList')->name('credit_purchase_invoice_list');
 });



 







 // ADMIN ROUTE END //


 /* Forgot Password Section */
Route::group(['middleware' => 'guest'],function(){
 
    Route::post('/send-link','Auth\ForgotPasswordController@sendMailLink')->name('send_mail_link');
    Route::get('/reset-forgot-password/{token}','Auth\ForgotPasswordController@CheckLink')->name('check_link');
    Route::post('/reset-password','Auth\ForgotPasswordController@resetPassword')->name('reset_password');
    Route::post('/reset-password','Auth\ForgotPasswordController@resetPassword')->name('changePasswordProcess');
 
});



/* test Section */
Route::get('/test',function(){
    return view('test1');
});

Route::get('crop-image', 'ImageCropController@index');
Route::post('crop-image', ['as'=>'croppie.upload-image','uses'=>'ImageCropController@imageCrop']);


/* Stripe Test Section*/
Route::get('/test-stripe','TestStripeController@index');

/* Test CREATE TAX IN STRIPE */

Route::get('/TAX','TestStripeController@index');

Route::get('/read','User\MyProjectController@abc');
Route::get('/readx','User\ApiController@read_docx');
Route::get('/testapi-html','ApiController@testApiHtml');

Route::get('/deepl','User\MyProjectController@testApi1');
Route::get('/he',function(){
    return view('user.testxml');
});

Route::get('/readword','User\ApiController@testApi');

Route::get('/check','User\ApiController@check');

