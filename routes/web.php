<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

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
Route::get('/testnotificationdemo', 'App\Http\Controllers\LoginController@testnotification');
Route::get('/testData', [App\Http\Controllers\HomeController::class, 'testData']);

Route::get('/admin_login', [App\Http\Controllers\backend\Admin::class, 'index'])->name('login');

Route::get('/testtoken', function () {
    return view('testtoken');
});


Route::get('/forget-password', 'App\Http\Controllers\backend\Admin@forget_password');
Route::post('/forgotadminpasswordformcheck', 'App\Http\Controllers\LoginController@forgotadminpasswordformcheck')->name('forgotadminpasswordformcheck');

//Route::get('/adminotp-verifictionforget/{id}','App\Http\Controllers\LoginController@adminotpverifictionforget');

Route::get('/adminotp-verifictionforget', 'App\Http\Controllers\LoginController@adminotpverifictionforget');
Route::post('/adminverify_otp', 'App\Http\Controllers\LoginController@adminverify_otp')->name('adminverify_otp');

Route::get('/adminforgetpasswordview/{id}', 'App\Http\Controllers\LoginController@adminforgetpasswordview');

Route::post('/verify_adminforgetpassword', 'App\Http\Controllers\LoginController@verify_adminforgetpassword')->name('verify_adminforgetpassword');



Route::get('/testtoken', [App\Http\Controllers\HomeController::class, 'testtoken']);
//store.token
Route::post('/store.token', [App\Http\Controllers\HomeController::class, 'storetoken'])->name('store.token');
//

Route::post('/send.web-notification', [App\Http\Controllers\HomeController::class, 'sendnotification'])->name('send.web-notification');

Route::any('/send_notification/{id}', [App\Http\Controllers\LoginController::class, 'send_notification'])->name('send_notification');



Route::any('/send_notification_newOffer/{last_offer_id}', [App\Http\Controllers\MyoffersController::class, 'send_notification_newOffer'])->name('send_notification_newOffer');






Route::get('/', [App\Http\Controllers\HomeController::class, 'homepage'])->name('home');
// Route::get('/', function () {
//     return view('home');
// });
Route::get('/fit-plans', [App\Http\Controllers\FitplansController::class, 'index']);
Route::get('/getdata_fit_plans', [App\Http\Controllers\FitplansController::class, 'getdata_fit_plans']);

Route::get('/fit-plans-detail/{tainerid}/{id}', [App\Http\Controllers\FitplansController::class, 'fit_plans_detail']);
Route::get('/payment-workout-by-yourself/{id}', [App\Http\Controllers\FitplansController::class, 'workout_payment']);
Route::get('/fit-plan-by-yourself-after-payment/{id}', [App\Http\Controllers\FitplansController::class, 'after_payment']);



//Route::get('/search_fitplans/{search}',[App\Http\Controllers\FitplansController::class, 'search_fitplans']);
Route::post('/search_fitplans', [App\Http\Controllers\FitplansController::class, 'search_fitplans'])->name("search_fitplans");
// Route::group(['middleware' => 'prevent-back-history'],function(){

Route::post('/filter_fitplans', [App\Http\Controllers\FitplansController::class, 'filter_fitplans'])->name("filter_fitplans");




Route::get('/aboutus', [App\Http\Controllers\AboutusController::class, 'index']);

//Route::get('/Be-a-Trainer', [App\Http\Controllers\FitnessTrainerController::class, 'index']);

//----------------------------fitness-trainer-data---------------------------

Route::post('/fitness-trainer-data', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_data'])->name('fitness-trainer-data');

Route::post('/set_password_fitness_trainer', [App\Http\Controllers\FitnessTrainerController::class, 'set_password_fitness_trainer'])->name('set_password_fitness_trainer')->middleware('rememberme');



Route::get('/manage_business_del/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'manage_business_del'])->name('manage_business_del')->middleware('rememberme');


Route::get('/manage_business_view/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_view'])->name('manage_business_view')->middleware('rememberme');

Route::post('/manage_business_editt', [App\Http\Controllers\FitnessTrainerController::class, 'firness_trainer_update'])->name('manage_business_editt')->middleware('rememberme');

Route::post('/fitness_trainer_filter', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_filter'])->name('fitness_trainer_filter')->middleware('rememberme');

Route::post('/business_review__remove', [App\Http\Controllers\FitnessTrainerController::class, 'business_review__remove'])->name('business_review__remove')->middleware('rememberme');

Route::any('/ReviewandRattingManagement', [App\Http\Controllers\FitnessTrainerController::class, 'ReviewandRattingManagement'])->middleware('rememberme');

Route::get('/inessintroductoryvideo', [App\Http\Controllers\FitnessTrainerController::class, 'inessintroductoryvideo'])->middleware('rememberme');


//
Route::post('/fitness_trainer_signin', [App\Http\Controllers\FitnessTrainerController::class, 'checklogin'])->name('fitness_trainer_signin');

Route::get('/fitness-trainer-login-my-profile', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_login_my_profile'])->name('fitness-trainer-login-my-profile');


Route::get('/fitness_trainer_logout', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_logout']);





Route::get('/trainer-forgot-password', [App\Http\Controllers\FitnessTrainerController::class, 'trainer_forgot_password']);

Route::post('/firness_forget_passwordcheck', [App\Http\Controllers\FitnessTrainerController::class, 'firness_forget_passwordcheck'])->name('firness_forget_passwordcheck');

Route::get('/reset-password/{token}', [App\Http\Controllers\FitnessTrainerController::class, 'trainer_reset_password']);

Route::Post('/trainer_updatepassword', [App\Http\Controllers\FitnessTrainerController::class, 'trainer_updatepassword'])->name('trainer_updatepassword');


// Route::get('/emailverification', function () {
//    return view('Pages.email-verification');
// });




Route::get('/signin', function () {
    Session::forget('fitness_tranner_id');
    Session::forget('name');
    Session::forget('email');
    Session::forget('mobile_number');
    Session::forget('upload_doc');
    Session::forget('address');
    return view('/signin');
});





Route::get('/dashboard', [App\Http\Controllers\backend\Admin::class, 'dashboard'])->middleware('rememberme');

//--------------------------------workout_plans---------------------------------
Route::get('workout_plans', [App\Http\Controllers\backend\workoutPlansController::class, 'index'])->middleware('rememberme');

Route::get('add_workout_plan', [App\Http\Controllers\backend\workoutPlansController::class, 'add_workout_plan_view'])->middleware('rememberme');
Route::post('/workout_plan_store', [App\Http\Controllers\backend\workoutPlansController::class, 'store'])->name('workout_plan_store')->middleware('rememberme');

Route::get('/workout_plans_del/{id}', [App\Http\Controllers\backend\workoutPlansController::class, 'delete'])->name('workout_plans_del')->middleware('rememberme');

Route::post('/workoutplansAll', [App\Http\Controllers\backend\workoutPlansController::class, 'deleteAll'])->name('workoutplansAll')->middleware('rememberme');
Route::any('/workout_plans_edit/{id}', [App\Http\Controllers\backend\workoutPlansController::class, 'edit'])->name('workout_plans_edit')->middleware('rememberme');

Route::post('/workout_plan_update', [App\Http\Controllers\backend\workoutPlansController::class, 'workout_plan_update'])->name('workout_plan_update')->middleware('rememberme');

Route::post('/workout_plans_active_desctive', [App\Http\Controllers\backend\workoutPlansController::class, 'workout_plans_active_desctive'])->name('workout_plans_active_desctive')->middleware('rememberme');

//==========================Add new Business Owner-------------


Route::any('add_new_business_owner', [App\Http\Controllers\FitnessTrainerController::class, 'add_new_business_owner'])->middleware('rememberme');

Route::any('add_new_business_by_admin', [App\Http\Controllers\FitnessTrainerController::class, 'add_new_business_by_admin'])->middleware('rememberme');

//=============================end New Business Owner------------------
//-------------------------PromoCode -----------------------------
Route::get('promocode_list', [App\Http\Controllers\FitnessTrainerController::class, 'getpromocode'])->middleware('rememberme');
Route::any('add_promocode', [App\Http\Controllers\FitnessTrainerController::class, 'add_promocode'])->middleware('rememberme');

Route::post('promocode-data', [App\Http\Controllers\FitnessTrainerController::class, 'promocodedata'])->middleware('rememberme');






Route::post('/promocode_status', [App\Http\Controllers\FitnessTrainerController::class, 'promocode_status'])->name('promocode_status')->middleware('rememberme');




Route::get('/promocode_delete/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'promocode_delete'])->name('promocode_delete')->middleware('rememberme');

Route::get('/promocode_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'promocode_edit'])->name('promocode_edit')->middleware('rememberme');




//--------------------end Promo Code------------------------------
//--------------------------------FitnessTrainer Start---------------------------------
Route::get('manager_business', [App\Http\Controllers\FitnessTrainerController::class, 'getdata'])->middleware('rememberme');
Route::get('manager_category', [App\Http\Controllers\FitnessTrainerController::class, 'getdatacategory'])->middleware('rememberme');




Route::post('custom-login', [App\Http\Controllers\backend\Admin::class, 'customLogin'])->name('login.custom');
Route::get('signout', [App\Http\Controllers\backend\Admin::class, 'signOut'])->name('signout')->middleware('rememberme');



Route::post('/user_change_password', [App\Http\Controllers\backend\userController::class, 'user_change_password'])->name('user_change_password');
Route::post('/update_admin_profile', [App\Http\Controllers\backend\userController::class, 'update_admin_profile'])->name('update_admin_profile');


Route::get('/fitness-survey', function () {
    return view('Pages.fitness-survey');
});
//user manage//



Route::get('/add_user', [App\Http\Controllers\backend\userController::class, 'create'])->name('add_user')->middleware('rememberme');
Route::post('store-data', [App\Http\Controllers\backend\userController::class, 'store'])->name('store-data')->middleware('rememberme');
Route::get('/user_view/{id}', [App\Http\Controllers\backend\userController::class, 'userView'])->middleware('rememberme');
Route::get('/user_edit/{id}', [App\Http\Controllers\backend\userController::class, 'edit'])->name('user_edit')->middleware('rememberme');
Route::post('/update_data/', [App\Http\Controllers\backend\userController::class, 'updateData'])->middleware('rememberme');

//Route::DELETE('/user_delete/{id}', [App\Http\Controllers\backend\userController::class, 'delete'])->name('user_delete');
Route::get('/user_delete/{id}', [App\Http\Controllers\backend\userController::class, 'delete'])->name('user_delete')->middleware('rememberme');
//ravi sir

Route::post('/verify_otp', 'App\Http\Controllers\Api\userController@verifyOtp')->name('verify_otp');
//  Route::post('/fitness-survey_three', 'App\Http\Controllers\Api\userController@fitness_survey')->name('fitness-survey_three');  

Route::post('/fitness_survey_one', 'App\Http\Controllers\Api\userController@fitness_one')->name('fitness_survey_one');
Route::post('/fitness_survey_two', 'App\Http\Controllers\Api\userController@fitness_two')->name('fitness_survey_two');
Route::post('/fitness_survey_three', 'App\Http\Controllers\Api\userController@fitness_survey')->name('fitness_survey_three');


Route::get('/membership', function () {
    return view('Pages.membership');
});


//=======================================wemarkthespot start===============================================================================

//admin panel

Route::get('/user_list', [App\Http\Controllers\backend\userController::class, 'index'])->name('user_list')->middleware('rememberme');
Route::get('/user-view/{id}', 'App\Http\Controllers\backend\userController@userview');
Route::post('/changeStatus/{id}', [App\Http\Controllers\backend\userController::class, 'changeStatus'])->name('changeStatus')->middleware('rememberme');


Route::post('/userchangestatus', [App\Http\Controllers\backend\userController::class, 'userchangeStatus'])->name('userchangeStatus')->middleware('rememberme');
Route::post('/userchangestatusactive', [App\Http\Controllers\backend\userController::class, 'userchangestatusactive'])->name('userchangestatusactive')->middleware('rememberme');



// end admin panel
Route::get('/my-offers', 'App\Http\Controllers\MyoffersController@index')->middleware('Website');

Route::post('/offerData', 'App\Http\Controllers\MyoffersController@offerData')->name('offerData')->middleware('Website');

Route::post('/editofferData', 'App\Http\Controllers\MyoffersController@editofferData')->name('editofferData')->middleware('Website');

Route::post('/deleteofferData', 'App\Http\Controllers\MyoffersController@deleteofferData')->name('deleteofferData')->middleware('Website');


// Route::get('my-offers',function(){
// return view('wemarkthespot.my-offers');
// });

Route::get('/subscriptions/', 'App\Http\Controllers\SubscriptionsController@index')->middleware('Website');
Route::get('/hotspot-updates', 'App\Http\Controllers\HotspotUpdatesController@index')->middleware('Website');
Route::get('/report-management', 'App\Http\Controllers\ReportManagementController@index')->middleware('Website');
Route::get('/community-reviews', 'App\Http\Controllers\CommunityReviewsController@index')->middleware('Website');
// Route::get('/pagination', 'App\Http\Controllers\CommunityReviewsController@pagination')->middleware('Website');

Route::get('/community-reviews/{limit}', 'App\Http\Controllers\CommunityReviewsController@pagination')->middleware('Website');
Route::post('/getoffertypebycategory_id', 'App\Http\Controllers\MyoffersController@getoffertypebycategory_id')->middleware('Website');



Route::get('/community_reportweb/{business_id}/{review_id}', 'App\Http\Controllers\CommunityReviewsController@community_reportweb')->middleware('Website');

Route::post('/replyform', 'App\Http\Controllers\CommunityReviewsController@communutyReplies')->middleware('Website');

Route::get('/contact-us', 'App\Http\Controllers\ContactUsController@index')->middleware('Website');
Route::post('/contactusform', 'App\Http\Controllers\ContactUsController@contactus')->middleware('Website');

Route::post('/fileexistsCheck', 'App\Http\Controllers\CommunityReviewsController@fileexistsCheck')->middleware('Website');




//Route::get('/login', 'App\Http\Controllers\LoginController@index');
Route::get('/payment', 'App\Http\Controllers\PaymentsController@index');

// Route::get('/my-account', 'App\Http\Controllers\MyAccountController@index');
Route::get('/faqs', 'App\Http\Controllers\FaqsController@index');

Route::get('/emailverification/{token}', 'App\Http\Controllers\LoginController@emailverification');
// Route::get('/otp-verifiction/{id}','App\Http\Controllers\LoginController@otp_verifiction');

Route::get('/otp-verifiction/', 'App\Http\Controllers\LoginController@otp_verifiction');



Route::get('/signin', 'App\Http\Controllers\LoginController@index')->name('signin');
Route::get('/signup', 'App\Http\Controllers\LoginController@signup');
Route::post('/signupuser', 'App\Http\Controllers\LoginController@signupuser')->name('signupuser');




Route::get('/websignout', 'App\Http\Controllers\LoginController@signout');
Route::get('/manage_business_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'fitness_trainer_edit'])->name('manage_business_edit')->middleware('rememberme');




Route::get('/ac-change-password', 'App\Http\Controllers\LoginController@acchangepassword')->name('ac-change-password')->middleware('Website');
Route::post('/business_user_change_psd', "App\Http\Controllers\LoginController@businessuserchangepsd")->name('business_user_change_psd')->middleware('Website');

Route::get('/webpayment', 'App\Http\Controllers\LoginController@webpayment')->name('webpayment')->middleware('Website');




Route::get('/my-subscription', 'App\Http\Controllers\LoginController@mysubscription')->name('mysubscription')->middleware('Website');
Route::get('/notifications', 'App\Http\Controllers\LoginController@notifications')->name('notifications')->middleware('Website');


Route::get('change-password', function () {
    return view('wemarkthespot.change-password');
})->middleware('Website');
Route::get('/my_profile', function () {
    return View('Pages.my_profile');
});


Route::get('/manager_donationhistory', 'App\Http\Controllers\FitnessTrainerController@manager_donationhistory')->middleware('rememberme');
Route::post('/webverify_otp', 'App\Http\Controllers\LoginController@verifyOtp')->name('webverify_otp');
Route::post('/manage_business_signin', [App\Http\Controllers\LoginController::class, 'checklogin'])->name('manage_business_signin');

Route::get('/my_account', 'App\Http\Controllers\LoginController@myaccount')->name('my_account')->middleware('Website');

Route::Post('/my_profile_edit', [App\Http\Controllers\LoginController::class, 'my_profile_edit'])->name('my_profile_edit');
Route::get('/add_category', [App\Http\Controllers\FitnessTrainerController::class, 'create'])->middleware('rememberme');

Route::get('/manage_aboutus', [App\Http\Controllers\FitnessTrainerController::class, 'manage_aboutus'])->middleware('rememberme');

Route::post('/terms_conditions_data', [App\Http\Controllers\FitnessTrainerController::class, 'manageterms_conditions'])->middleware('rememberme');

Route::any('/manage_terms_conditions', [App\Http\Controllers\FitnessTrainerController::class, 'manage_terms_conditions'])->middleware('rememberme');

Route::post('/category-data', [App\Http\Controllers\FitnessTrainerController::class, 'categorydata'])->name('category-data')->middleware('rememberme');
Route::post('/category-update', [App\Http\Controllers\FitnessTrainerController::class, 'categoryupdate'])->name('category-edit')->middleware('rememberme');

Route::any('/contact', 'App\Http\Controllers\FitnessTrainerController@getcontact')->middleware('rememberme');

Route::get('/business_giveaways', 'App\Http\Controllers\FitnessTrainerController@business_giveaways')->middleware('rememberme');

Route::any('/business_giveaways_status', 'App\Http\Controllers\FitnessTrainerController@business_giveaways_status')->middleware('rememberme');



Route::post('/contact_status', [App\Http\Controllers\FitnessTrainerController::class, 'contact_status'])->name('contact_status')->middleware('rememberme');


Route::post('/category_status', [App\Http\Controllers\FitnessTrainerController::class, 'category_status'])->name('category_status')->middleware('rememberme');

Route::get('/category_delete/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'delete'])->name('category_delete')->middleware('rememberme');

Route::get('/offerstype_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'offerstype_edit'])->name('offerstype_edit')->middleware('rememberme');


Route::get('/category_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'edit'])->name('category_edit')->middleware('rememberme');
Route::get('/category-view/{id}', 'App\Http\Controllers\FitnessTrainerController@categoryview');

Route::get('manage_sub_category', [App\Http\Controllers\FitnessTrainerController::class, 'getdatasubcategory'])->middleware('rememberme');
Route::get('/add_sub_category', [App\Http\Controllers\FitnessTrainerController::class, 'add_sub_category'])->middleware('rememberme');
Route::post('/subcategory-data', [App\Http\Controllers\FitnessTrainerController::class, 'subcategorydata'])->name('subcategory-data')->middleware('rememberme');
Route::get('/subcategory-view/{id}', 'App\Http\Controllers\FitnessTrainerController@subcategoryview');

Route::get('/subcategory_delete/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'subcategory_delete'])->name('subcategory_delete')->middleware('rememberme');

Route::get('/subcategory_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'subcategory_edit'])->name('subcategory_edit')->middleware('rememberme');
Route::post('/subcategory-update', [App\Http\Controllers\FitnessTrainerController::class, 'subcategoryupdate'])->name('subcategory-update')->middleware('rememberme');

Route::post('/subcategory_status', [App\Http\Controllers\FitnessTrainerController::class, 'subcategory_status'])->name('subcategory_status')->middleware('rememberme');

Route::post('/sub_category_by_category_id', [App\Http\Controllers\FitnessTrainerController::class, 'sub_category_by_category_id'])->name('sub_category_by_category_id');

Route::post('/fitness_trainer_delall', [App\Http\Controllers\FitnessTrainerController::class, 'deleteAll'])->name('fitness_trainer_delall')->middleware('rememberme');

Route::get('/forgetpsd', [App\Http\Controllers\LoginController::class, 'forgetpsd']);

Route::post('/forgotPasswordemailcheck', [App\Http\Controllers\LoginController::class, 'forgotPassword'])->name('forgotPasswordemailcheck');

// Route::get('/otp-verifictionforget/{id}','App\Http\Controllers\LoginController@otp_verifictionforget');

Route::get('/otp-verifictionforget', 'App\Http\Controllers\LoginController@otp_verifictionforget');


Route::post('/verify_otpforget', 'App\Http\Controllers\LoginController@verify_otpforget')->name('verify_otpforget');
//Route::get('/forget_pasword_views/{id}','App\Http\Controllers\LoginController@forget_pasword_view');

Route::get('/forget_pasword_views/', 'App\Http\Controllers\LoginController@forget_pasword_view');
Route::post('/verify_forgetpassword', 'App\Http\Controllers\LoginController@verify_forgetpassword')->name('verify_forgetpassword');


Route::get('/forget_pasword_view/{id}', 'App\Http\Controllers\LoginController@resend_otp');
//Route::get('/resend_otp/{id}', 'App\Http\Controllers\LoginController@resend_otp');

Route::get('/resend_otp', 'App\Http\Controllers\LoginController@resend_otp');

Route::get('/add_quotes', [App\Http\Controllers\FitnessTrainerController::class, 'add_quotes'])->middleware('rememberme');
Route::get('/quoates_managements', [App\Http\Controllers\FitnessTrainerController::class, 'quoates_managements'])->middleware('rememberme');

Route::post('/Quotes-data', [App\Http\Controllers\FitnessTrainerController::class, 'quotesdata'])->name('quotes-data')->middleware('rememberme');

Route::get('/quote-view/{id}', 'App\Http\Controllers\FitnessTrainerController@quoteview');

Route::get('/quote_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'quote_edit'])->name('quote_edit')->middleware('rememberme');


Route::get('/Introductory_video_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'Introductory_video_edit'])->name('Introductory_video_edit')->middleware('rememberme');



Route::post('/Quotes-edit', [App\Http\Controllers\FitnessTrainerController::class, 'quotes_edit'])->name('Quotes-edit')->middleware('rememberme');

Route::any('/IntroductorVideo-edit', [App\Http\Controllers\FitnessTrainerController::class, 'IntroductorVideoedit'])->name('IntroductorVideo-edit')->middleware('rememberme');


Route::get('manage_offer_type', [App\Http\Controllers\FitnessTrainerController::class, 'getdataoffer_type'])->middleware('rememberme');


Route::get('add_offertype', [App\Http\Controllers\FitnessTrainerController::class, 'add_offertype'])->middleware('rememberme');

Route::post('/offertype-data', [App\Http\Controllers\FitnessTrainerController::class, 'offerypedata'])->name('category-data')->middleware('rememberme');

Route::post('/offertype_status', [App\Http\Controllers\FitnessTrainerController::class, 'offertypestatus'])->name('offertype_status')->middleware('rememberme');

Route::post('/Introductory_video_status', [App\Http\Controllers\FitnessTrainerController::class, 'Introductory_video_status'])->name('Introductory_video_status')->middleware('rememberme');


Route::post('/editoffertype-data', [App\Http\Controllers\FitnessTrainerController::class, 'editoffertypedata'])->name('editoffertype-data')->middleware('rememberme');


Route::get('/business_report', [App\Http\Controllers\FitnessTrainerController::class, 'business_report'])->middleware('rememberme');

Route::post('/report_status', [App\Http\Controllers\FitnessTrainerController::class, 'report_status'])->middleware('rememberme');

Route::any('/Introductory_Video', [App\Http\Controllers\FitnessTrainerController::class, 'Introductory_Video'])->middleware('rememberme');

Route::any('/report_business_status', [App\Http\Controllers\FitnessTrainerController::class, 'report_business_status'])->middleware('rememberme');



Route::post('/likedislikeweb', 'App\Http\Controllers\FitnessTrainerController@likedislikeweb')->name('likedislikeweb')->middleware('Website');


Route::any('/faq', [App\Http\Controllers\FitnessTrainerController::class, 'getfaq'])->middleware('rememberme');

Route::any('/add_Faq', [App\Http\Controllers\FitnessTrainerController::class, 'add_Faq'])->middleware('rememberme');

Route::any('/Faq-data', [App\Http\Controllers\FitnessTrainerController::class, 'Faq_data'])->middleware('rememberme');


Route::any('/Faq_delete/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'Faq_delete'])->name('Faq_delete')->middleware('rememberme');

Route::get('/Faq_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'faq_edit'])->name('Faq_edit')->middleware('rememberme');
Route::post('/Faq-update', [App\Http\Controllers\FitnessTrainerController::class, 'Faq_update'])->middleware('rememberme');

Route::post('/Faq_status', [App\Http\Controllers\FitnessTrainerController::class, 'Faq_status'])->middleware('rememberme');

Route::any('/about-data', [App\Http\Controllers\FitnessTrainerController::class, 'aboutdata'])->middleware('rememberme');

Route::any('/edit_giweaways/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'edit_giweaways'])->name('edit_giweaways')->middleware('rememberme');

Route::post('/giweaway_update', [App\Http\Controllers\FitnessTrainerController::class, 'giweaway_update'])->name('giweaway_update')->middleware('rememberme');




//==========================SubscriptionsController=================work rahul sir


Route::post('/oneweek', [App\Http\Controllers\SubscriptionsController::class, 'oneweek'])->name('oneweek')->middleware('Website');
Route::post('/threeweek', [App\Http\Controllers\SubscriptionsController::class, 'threeweek'])->name('threeweek')->middleware('Website');
Route::post('/allweek', [App\Http\Controllers\SubscriptionsController::class, 'allweek'])->name('allweek')->middleware('Website');
Route::get('/webpayment', [App\Http\Controllers\SubscriptionsController::class, 'loadSubcriptionPayment'])->name('loadSubcriptionPayment')->middleware('Website');
Route::any('/submitSubcriptionPayment', [App\Http\Controllers\SubscriptionsController::class, 'submitSubcriptionPayment'])->name('submitSubcriptionPayment')->middleware('Website');


Route::any('admin_subscriptions', [App\Http\Controllers\FitnessTrainerController::class, 'getdataSubscriptions'])->middleware('rememberme');
Route::any('/subscriptions_edit/{id}', [App\Http\Controllers\FitnessTrainerController::class, 'editSubscriptions'])->middleware('rememberme');
Route::any('/subscriptions-update', [App\Http\Controllers\FitnessTrainerController::class, 'subscriptionsUpdate'])->name('subscriptions-update')->middleware('rememberme');

Route::any('admin_payment_details',[App\Http\Controllers\FitnessTrainerController::class, 'getPaymentDetails'])->middleware('rememberme');
Route::get('/admin_add_plans', [App\Http\Controllers\FitnessTrainerController::class, 'adminAddPlans'])->middleware('rememberme');

Route::any('/ContactReply', [App\Http\Controllers\FitnessTrainerController::class, 'ContactReply'])->name('ContactReply');

Route::any('/privacypolicy', [App\Http\Controllers\FitnessTrainerController::class, 'privacypolicy']);

//=======================================wemarkthespot AND===============================================================================

Route::post('/privacy-update', [App\Http\Controllers\FitnessTrainerController::class, 'privacypolicystore']);


Route::post('/strippayment', [App\Http\Controllers\FitnessTrainerController::class, 'strippaymentcheck'])->name("strippayment");
Route::get('/payment_failed', [App\Http\Controllers\FitnessTrainerController::class, 'payment_failed']);



Route::any('/payments/{id}/{amount}', [App\Http\Controllers\FitnessTrainerController::class, 'payment']);


