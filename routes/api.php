<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('test-api',function(){
	return [];
});
Route::middleware('auth:api')->get('/user', function (Request $request) { 

    return $request->user();
});

Route::post('test-api1','ApiController@read_test');

//Route::get('language-pair','ApiController@LanguagePair')->middleware('cors');
Route::get('html-translate/{LanguageId?}','ApiController@HTML');

Route::get('/test-html-translate/{LanguageId?}',function(){
    echo "OKAY TEST DONE"; 

});

Route::get('test-api1','ApiController@read_test2');

// Route::post('translate-data','ApiController@plaintext');
//Route::post('language-pair','ApiController@plaintext')->middleware('cors');
Route::post('language-pair','ApiController@LanguagePairWiseCall')->middleware('cors');

// Route::get('language-pair-test','ApiController@plaintext1');

Route::post('html-response','ApiController@ResponseHtmlSourceCode')->middleware('cors');
Route::post('google-api-test','ApiController@GoogleApiTestMode')->middleware('cors');


Route::get('hello','ApiController@testApi123'); 



Route::get('/TEST2','ReplaceController@HTML_Translated');
Route::post('Hello123','ReplaceController@okay123');
//Route::post('/webhook-handles','ApiController@WebhookHandle')->name('webhook-handles');

Route::post('/webhook-handles','WebhookHandleController@WebhookHandle')->name('webhook-handles');


/* Amazon APi Section Start */
Route::post('/amazon-translate','ApiController@AmazonAPi')->name('amazon_translate');


/* Chat Box Api Test */
Route::any('/chat-box','ApiController@ChatBox')->name('chat_box');


/* New Api Test */
Route::post('/new-api-test',function(){
    echo "OKAY";
});


