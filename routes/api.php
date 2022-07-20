<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});



Route::post('/deleteImage', 'App\Http\Controllers\Api\userController@deleteImage');

Route::get('/offer_expiry', 'App\Http\Controllers\MyoffersController@offer_expiry');
Route::get('/subscription_expiry', 'App\Http\Controllers\PaymentsController@subscription_expiry');

//------------------------created by akhil---------------start
Route::post('/testnotification', 'App\Http\Controllers\Api\userController@testnotification');

Route::post('/user_register', 'App\Http\Controllers\Api\userController@userRegister');
Route::post('/login', 'App\Http\Controllers\Api\userController@logincheck');
Route::post('/forgot_password', 'App\Http\Controllers\Api\userController@forgotPassword');
Route::post('/resendotp', 'App\Http\Controllers\Api\userController@resendotp');

Route::post('/password_verification', 'App\Http\Controllers\Api\userController@passwordVerification');
Route::post('/password_update', 'App\Http\Controllers\Api\userController@passwordUpdate'); 
Route::post('/edit', 'App\Http\Controllers\Api\userController@edit'); 
Route::post('/profile_update', 'App\Http\Controllers\Api\userController@profile_update'); 
Route::get('/get_allusers', 'App\Http\Controllers\Api\userController@get_allusers'); 
Route::post('/guestuser', 'App\Http\Controllers\Api\userController@guestuser');

Route::post('/forgot_password_verify', 'App\Http\Controllers\Api\userController@forgetpasswordVerification');

Route::post('/get_homedata', 'App\Http\Controllers\Api\userController@getquoatesdata');

Route::post('/get_homedata2', 'App\Http\Controllers\Api\userController@getquoatesdata2');


Route::post('/getnearby', 'App\Http\Controllers\Api\userController@getnearby');

Route::post('/getnearbytest', 'App\Http\Controllers\Api\userController@getnearbytest');

Route::post('/business_review', 'App\Http\Controllers\Api\userController@business_review');
Route::get('/business_review_delete', 'App\Http\Controllers\Api\userController@business_review_delete');
Route::post('/checkOut', 'App\Http\Controllers\Api\userController@checkOut');

Route::post('/community_reviews', 'App\Http\Controllers\Api\userController@community_reviews');
Route::post('/community_reviewstest', 'App\Http\Controllers\Api\userController@community_reviewstest');
Route::post('/community_reviews1', 'App\Http\Controllers\Api\userController@community_reviews1');


Route::post('/add_hotspots', 'App\Http\Controllers\Api\userController@add_hotspots');

Route::get('/gethotspot', 'App\Http\Controllers\Api\userController@gethotspots');
Route::get('/gethotspot3', 'App\Http\Controllers\Api\userController@gethotspots3');

Route::post('/get_businessusers', 'App\Http\Controllers\Api\userController@get_businessusers');
Route::post('/businessfav', 'App\Http\Controllers\Api\userController@BusinessFav');

Route::post('/replies_community_reviews', 'App\Http\Controllers\Api\userController@replies_community_reviews');


Route::get('/get_replies_community_reviews', 'App\Http\Controllers\Api\userController@get_replies_community_reviews');
Route::post('/get_replies_community_reviews1', 'App\Http\Controllers\Api\userController@get_replies_community_reviews1');

Route::post('/multipleimage', 'App\Http\Controllers\Api\userController@multipleimage');

Route::get('/userCheckInList', 'App\Http\Controllers\Api\userController@userCheckInList');

Route::post('/getbusinessFavbyuserId', 'App\Http\Controllers\Api\userController@getbusinessFav');

Route::post('/addBuinessReports', 'App\Http\Controllers\Api\userController@addBuinessReports');


Route::post('/Businesslikedislike', 'App\Http\Controllers\Api\userController@Businesslikedislike');

Route::post('/Businesslikedislike2', 'App\Http\Controllers\Api\userController@Businesslikedislike2');

Route::post('/Businesssearch', 'App\Http\Controllers\Api\userController@BusinessSearch');
Route::post('/BusinessSearchtext', 'App\Http\Controllers\Api\userController@BusinessSearchtext');
Route::post('/Businesssearch2', 'App\Http\Controllers\Api\userController@BusinessSearch2');//use for hotsprts data

Route::post('/BusinessSearch33', 'App\Http\Controllers\Api\userController@BusinessSearch33');

Route::post('/getreviewbyuserid', 'App\Http\Controllers\Api\userController@getreviewbyuserid');
Route::post('/deletereview', 'App\Http\Controllers\Api\userController@deletereview');
Route::post('/editReview', 'App\Http\Controllers\Api\userController@editReview');


//rtrim($d->image, ",")

Route::post('/searchBybusinessNameCategoryNameSubCategoryName', 'App\Http\Controllers\Api\userController@Search');

Route::post('/searchBybusinessNameCategoryNameSubCategoryName2', 'App\Http\Controllers\Api\userController@searchBybusinessNameCategoryNameSubCategoryName2');

Route::post('/featuredBusiness', 'App\Http\Controllers\Api\userController@featuredBusiness');


Route::get('/getAllbusiness', 'App\Http\Controllers\Api\userController@getAllbusiness');

Route::get('/getfilterbusiness', 'App\Http\Controllers\Api\userController@getfilterbusiness');

Route::get('/getfaq', 'App\Http\Controllers\Api\userController@getfaq');

Route::post('/BusinessVisits', 'App\Http\Controllers\Api\userController@BusinessVisits');

Route::post('/contactus', 'App\Http\Controllers\Api\userController@contactus');
Route::post('/changepassword', 'App\Http\Controllers\Api\userController@changepassword');
Route::post('/getbusinessDetails', 'App\Http\Controllers\Api\userController@getbusinessDetailsbyId');

Route::get('/getabout', 'App\Http\Controllers\Api\userController@getabout');
Route::post('/filter', 'App\Http\Controllers\Api\userController@filter');

Route::post('/getallBusinesslist', 'App\Http\Controllers\Api\userController@getallBusinesslist');
Route::get('/cronjobcheckout', 'App\Http\Controllers\Api\userController@cronjobcheckout');


Route::post('/checkinAPi', 'App\Http\Controllers\Api\userController@checkinAPi');
Route::get('/checkInList', 'App\Http\Controllers\Api\userController@checkInList');
//-----------------------------------end-------------------------------------------------------------------

Route::post('/email_verification', 'App\Http\Controllers\Api\userController@emailVerification');
Route::post('/email_sent_otp', 'App\Http\Controllers\Api\userController@emailSentOtp');
Route::post('/get_businessoftheweek', 'App\Http\Controllers\Api\userController@get_businessoftheweek');

// Route::post('/verify_otp','App\Http\Controllers\LoginController@verifyOtp')->name('verify_otp');

Route::post('/onlineOnly', 'App\Http\Controllers\Api\userController@onlineOnly');    //In Use //RR
Route::post('/offers', 'App\Http\Controllers\Api\userController@offers');    //In Use //RR
Route::post('/hablamos_espanol', 'App\Http\Controllers\Api\userController@hablamosEspanol');    //In Use //RR

Route::get('/privacypolicy', 'App\Http\Controllers\Api\userController@privacypolicy');    //In Use //RR


Route::get('/payment', 'App\Http\Controllers\Api\userController@payment');    //In Use //Ak

Route::get('/donationhistory/{id}', 'App\Http\Controllers\Api\userController@donationhistory'); //Ak

Route::get('/getreviewDetailsbyId/{id}', 'App\Http\Controllers\Api\userController@getreviewDetailsbyId'); //Ak


Route::get('/gethotspotDetailsbyId/{id}', 'App\Http\Controllers\Api\userController@gethotspotDetailsbyId'); //Ak

Route::get('/sendnotificationendofthemonth', 'App\Http\Controllers\Api\userController@sendnotificationendofthemonth'); //Ak

Route::get('/sendnotificationendofthemonthtest', 'App\Http\Controllers\Api\userController@sendnotificationendofthemonthtest'); //Ak

Route::post('/usernotificationstatus', 'App\Http\Controllers\Api\userController@usernotificationstatus'); //Ak

Route::get('/getterms_conditions', 'App\Http\Controllers\Api\userController@getterms_conditions');

Route::post('/getlastmonthData', 'App\Http\Controllers\Api\userController@getlastmonthData');
Route::post('/getlastmonthData2', 'App\Http\Controllers\Api\userController@getlastmonthData2');

