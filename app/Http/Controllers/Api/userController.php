<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Carbon\Carbon;
use Validator;
use App\Models\User;
use App\Models\Fitness_survey;
use App\Models\Verification;
use App\Models\Faqs;
use App\Models\Categorys;
use App\Models\BusinessVisits;
use App\Models\Aboutus;
use App\Models\Payments;
use App\Models\Contactus;
use App\Models\Businessreviewlikedislike;
use Hash;
use DateTime;
use Session;
use App\Models\Quoates;
use App\Models\Giweaway;
use App\Models\Hotspots;
use App\Models\BusinessFav;
use App\Models\Replies;
use App\Models\BuinessReports;
use App\Models\SubCategorys;
use App\Models\BusinessReviews;
use App\Models\CheckInOut;
use Reflector;

class userController extends Controller
{
    
    public function onlineOnly(Request $request){  //New //RR

        if(isset($request->onlineOnly)){
            $online = $request->onlineOnly;

            $data = User::where('business_type', 1)->get();
            if(!empty($data)){
                $result = array('status' => true, 'message' => 'Data Found', 'data'=>$data);
            }else{
                $result = array('status' => false, 'message' => 'No Data Found', 'data'=>'');
            }
        }else{
            $result = array('status' => false, 'message' => 'Online only field is required');
        }
        echo json_encode($result);
    }
    
    public function offers(Request $request){  //New //RR
        if(!empty($request->business_user_id)){
            $user_id = $request->business_user_id;

            $data = DB::table('offers')->where('user_id',$user_id)->get();

            if(!empty($data)){
                $result = array('status' => true, 'message' => 'Data Found', 'data'=>$data);
            }else{
                $result = array('status' => false, 'message' => 'No Data Found', 'data'=>'');
            }
        }else{
            $result = array('status' => false, 'message' => 'Business User Id is required');
        }
        echo json_encode($result);
    }
    
    public function hablamosEspanol(Request $request){
        if(!empty($request->hablamos_espanol)){
            $hablamosEspanol = $request->hablamos_espanol;
            
            $data = User::where('hablamos_espanol', 1)->get();

            if(!empty($data)){
                $result = array('status' => true, 'message' => 'Data Found', 'data'=>$data);
            }else{
                $result = array('status' => false, 'message' => 'No Data Found', 'data'=>'');
            }
        }else{
            $result = array('status' => false, 'message' => 'Hablamos Espanol is required');
        }
        echo json_encode($result);
    }


    public function Search(Request $request)
    {
        $name = $request->input('business_name');
        $data = User::whereHas('category', function (Builder $query) use ($name) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        })
            ->orWhereHas('subcategory', function (Builder $query) use ($name) {
                $query->where('name', 'LIKE', '%' . $name . '%');
            })
            ->orWhere('business_name', 'like', '%' . $name . '%')
            ->where('role', 99)
            ->get();
        if (count($data) > 0) {
            $total_review=0;
            foreach ($data as $b) {
               
                $b->firecount = BusinessReviews::where('business_id', $b->id)->where('tag', "fire")->count();
                $b->okcount = BusinessReviews::where('business_id', $b->id)->where('tag', "OkOk")->count();
                $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('tag', "Not Cool")->count();
                $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
                //$totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();
                $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->count();
                
                $b->totalReviewusers = $totalreviews;
                $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();
            }
        }
        if(count($data)!=0)
        {
            $result = array('status' => true, 'message' => 'Search .', 'total' => count($data), 'data' => $data);
        }
        else
        {
            $result = array('status' => false, 'message' => 'No Data Found', 'total' => count($data), 'data' => $data);
      
        }
        echo json_encode($result);
    }

    public function filter(Request $request)
    {
        $name = $request->input('key');

        $key = json_decode($name);
        if(!empty($key))
        {
            $data = User::whereHas('subcategory', function (Builder $query) use ($key) {
            $query->whereIn('id', $key);
            });
        }
        else
        {
            $data = User::where('id', "<>",0);
                
        }
        if ($request->online) {
            $data->orWhere('login_status', $request->online);
        }
        if($request->business_type){
            $data->Where('business_type', $request->business_type);
        }

        if($request->hablamos_espanol=='1'){
            $data->Where('hablamos_espanol', $request->hablamos_espanol);
        }

        if($request->religious_spiritual=='1'){
            $data->Where('religious_spiritual', $request->religious_spiritual);
        }
        if($request->current_promotion=='1'){
            $data->Where('current_promotion', $request->current_promotion);
        }
        

        $data->where('role', 99);
        $get = $data->get();
       // dd(count($get));
        
        if (count($get) > 0) {
            foreach ($get as $b) {
                $b->firecount = BusinessReviews::where('business_id', $b->id)->where('tag', "fire")->count();
                $b->okcount = BusinessReviews::where('business_id', $b->id)->where('tag', "OkOk")->count();
                $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('tag', "Not Cool")->count();
                $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
                $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status', '!=',2)->count();
                $b->totalReviewusers = $totalreviews;
                $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();
            }
        }
        $result = array('status' => true, 'message' => 'Search .', 'total' => count($get), 'data' => $get);
        echo json_encode($result);
    }
    public function logincheck(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;

        if (!isset($email)) {
            $result = array('status' => false, 'message' => 'Email is required.');
        } else if (!isset($request->password)) {
            $result = array('status' => false, 'message' => 'Password is required.');
        } else {

            $password = md5($request->password);
            $emailExist = DB::table('users')->where('email', $email)->first();
            if (!empty($emailExist)) {
                if ($emailExist->email = $email) {
                    if ($emailExist->status == 1) {
                        if (Hash::check($request->password, $emailExist->password)) {

                            $data['login_check'] = 1;
                            
                            $data['device_token'] = isset($request->fcm_token) ? $request->fcm_token : '';

                            DB::table('users')->where('email', $email)->update($data);
                            $image_url = url('public/images/userimage.png');
                            $emailExist->image =  isset($emailExist->image) ? $emailExist->image : $image_url;
                            $result = array('status' => true, 'message' => 'You are successfully logged in.', 'data' => $emailExist, 'old_password' => $request->password);
                        } else {
                            $result = array('status' => false, 'message' => 'Invalid Password');
                        }
                    } else {
                        $result = array('status' => false, 'message' => 'Your account has been deactivated by admin');
                    }
                } else {
                    $result = array('status' => false, 'message' => 'Invalid Email address');
                }
            } else {
                $result = array('status' => false, 'message' => 'User not registered');
            }
        }
        echo json_encode($result);
    }

    //user register 
    public function userRegister(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $username = $request->username;
        $email = $request->email;


        $emailExist = DB::table('users')->where('email', $email)->count();
        $userExist = DB::table('users')->where('username', $username)->count();
        if ($emailExist > 0) {
            $result = array('status' => false, 'message' => 'Email address already registered');
        } else {
            $username = $request->username;
            $email = $request->email;

            $image_url = url('public/images/userimage.png');
            $password = Hash::make($request->password);
            $otp =  rand(1000, 9999);
            $date = date("Y-m-d h:i:s", time());
            $data = ['username' => $username, 'name' => $username, 'image' => $image_url, 'email' => $email, 'password' => $password, 'status' => 1, 'role' => 97];
            $updated = DB::table('temp_users')->where('email', $request->email)->first();
            $up_otp = ['otp' => $otp, 'email' => $email, 'create_at' => $date, 'update_at' => $date];
            if (!empty($updated)) {
                DB::table('temp_users')->where('email', $request->email)->delete();

                DB::table('password_otp')->where('email', $request->email)->delete();
                $subject = "Register Otp";
                $message = "Register Otp OTP " . $otp;

                $update = DB::table('temp_users')->where('id', $request->id)->insert($data);
                $upt_success = DB::table('password_otp')->insert($up_otp);
            } else {
                $subject = "Register Otp";
                $message = "Register Otp OTP " . $otp;

                $update = DB::table('temp_users')->where('id', $request->id)->insert($data);
                $up_otp = ['otp' => $otp, 'email' => $email, 'create_at' => $date, 'update_at' => $date];
                $upt_success = DB::table('password_otp')->insert($up_otp);
            }



            // dd($data);

            // $addUsers =User::create($data);

            // dd($upt_success);
            if ($this->sendMail($request->email, $subject, $message)) {
                //  $this->sendMail($request->email,$subject,$message);
                $emailExist = DB::table('temp_users')->where('email', $email)->first();
                $result = array('status' => true, 'message' => 'OTP sent successfully.', 'data' => $emailExist);
            } else {
                $result = array('status' => false, 'message' => 'Something went wrong. Please try again.');
            }
        }
        echo json_encode($result);
    }

    public function userRegisterold(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $Validation = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'email' => 'required|email|unique:users',

        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $username = $request->username;
            $email = $request->email;
            $image_url = url('public/images/userimage.png');
            $password = Hash::make($request->password);
            $otp =  rand(1000, 9999);
            $date = date("Y-m-d h:i:s", time());
            $data = ['username' => $username, 'name' => $username, 'image' => $image_url, 'email' => $email, 'password' => $password, 'status' => 1, 'role' => 97];
            $subject = "Register Otp";
            $message = "Register Otp OTP " . $otp;


            $addUsers = User::create($data);
            $up_otp = ['otp' => $otp, 'email' => $email, 'create_at' => $date, 'update_at' => $date];
            $upt_success = DB::table('password_otp')->insert($up_otp);
            if ($addUsers) {
                $this->sendMail($request->email, $subject, $message);
                $emailExist = DB::table('users')->where('email', $email)->first();
                $result = array('status' => true, 'message' => 'User added successfully.', 'data' => $emailExist);
            } else {
                $result = array('status' => false, 'message' => 'Something went wrong. Please try again.');
            }
        }



        echo json_encode($result);
    }
    public function guestuser(Request $request)
    {
        //dd($request->input());
        date_default_timezone_set('Asia/Kolkata');

        $image_url = url('public/images/userimage.png');

        $username = $request->username;
        $email = $request->email;
        $password = Hash::make($request->password);
        $emailExist = DB::table('users')->where('email', $email)->count();
        $userExist = DB::table('users')->where('username', $username)->count();

        if (!isset($username)) {
            $result = array('status' => false, 'message' => 'Name is required.');
        } else if (!isset($email)) {
            $result = array('status' => false, 'message' => 'Email is required.');
        } else if (!isset($request->password)) {
            $result = array('status' => false, 'message' => 'Password is required.');
        } else if ($emailExist > 0) {
            $result = array('status' => false, 'message' => 'Email is already exist.');
        } else if ($userExist > 0) {
            $result = array('status' => false, 'message' => 'Username is already exist.');
        } else {
            $otp =  mt_rand(1000, 9999);
            $date = date("Y-m-d h:i:s", time());
            $data = ['username' => $username, 'name' => $username, 'image' => $image_url, 'email' => $email, 'password' => $password, 'status' => 1, 'created_at' => $date, 'updated_at' => $date, 'role' => 96];
            // echo "<pre>";
            // print_r($data);
            // exit;
            //    $subject="Register Otp";
            //  $message = "Register Otp OTP ". $otp;


            $addUsers = DB::table('users')->insert($data);
            //$up_otp = ['otp'=>$otp,'email'=>$email, 'create_at'=>$date, 'update_at'=>$date];
            //$upt_success = DB::table('password_otp')->insert($up_otp);
            if ($addUsers) {
                // $this->sendMail($request->email,$subject,$message);
                $result = array('status' => true, 'message' => 'Guest User added successfully.');
            } else {
                $result = array('status' => false, 'message' => 'Something went wrong. Please try again.');
            }
        }
        echo json_encode($result);
    }
    //email verification otp

    public function emailSentOtp(Request $request)
    {
        $email = $request->email;
        $otp =  mt_rand(1000, 9999);
        $date = date("Y-m-d h:i:s", time());
        $check_email = DB::table('email_otp')->where('email', $email)->count();

        $mail = new PHPMailer();
        $mail->SMTPDebug  = 0;
        $mail->IsSMTP();
        $mail->Mailer = "smtp";
        $mail->SMTPAuth = true;
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->IsHTML(true);
        $mail->Username = "sakil.appic@gmail.com";
        $mail->Password = 'APPICSOFTWARES';
        $mail->SetFrom("sakil.appic@gmail.com");
        $mail->Subject = "Email verification";
        $mail->Body = "Email verification OTP " . $otp;
        $mail->AddAddress($email);

        if ($check_email > 0) {
            $up_otp = ['otp' => $otp, 'create_at' => $date, 'update_at' => $date];
            $upt_success = DB::table('email_otp')->where('email', $email)->update($up_otp);
            if ($upt_success) {
                if ($mail->Send()) {
                    $result = array('status' => 200, 'message' => 'OTP sent your email successfully');
                }
            } else {
                $result = array('status' => 201, 'message' => 'OTP not sent');
            }
        } else {
            $gan_otp = ['email' => $email, 'otp' => $otp, 'create_at' => $date, 'update_at' => $date];
            $otp_success = DB::table('email_otp')->insert($gan_otp);

            if ($otp_success) {
                if ($mail->Send()) {
                    $result = array('status' => 200, 'message' => 'OTP sent your email successfully');
                } else {
                    $result = array('status' => 201, 'message' => 'OTP not sent');
                }
            } else {
                $result = array('status' => 201, 'message' => 'Something is Wrong.');
            }
        }
        echo json_encode($result);
    }

    public function emailVerification(Request $request)
    {
        $email = $request->email;
        $otp = $request->otp;

        $verify_otp = DB::table('email_otp')->where('email', $email)->where('otp', $otp)->first();
        $otp_expires_time = Carbon::now()->subMinutes(5);

        if ($verify_otp->create_at < $otp_expires_time) {
            $result = array('status' => false, 'message' => 'OTP is unvalid.');
        } else {
            $result = array('status' => true, 'message' => 'OTP valid successfully.');
        }
        echo json_encode($result);
    }


    public function forgotPassword(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;
        $otp =  mt_rand(1000, 9999);
        $date = date("Y-m-d h:i:s", time());
        if (!empty($email)) {
            $check_email = DB::table('users')->where('email', $email)->count();

            $subject = "Forgot password";
            $message = "Forgot password OTP " . $otp;
            if ($check_email > 0) {

                $this->sendMail($request->email, $subject, $message);
                // if($this->sendMail($email,$subject,$message))
                // {
                $up_otp = ['otp' => $otp, 'create_at' => $date, 'update_at' => $date, 'email' => $email];
                $upt_success = DB::table('password_otp')->where('email', $email)->update($up_otp);
                if ($upt_success) {

                    $result = array('status' => true, 'message' => 'OTP sent successfully');
                } else {
                    $upt_success2 = DB::table('password_otp')->insert($up_otp);
                    if ($upt_success2) {
                        $result = array('status' => true, 'message' => 'OTP sent successfully');
                    } else {
                        $result = array('status' => false, 'message' => 'OTP not Send');
                    }
                }
                //        }
                // else
                // {
                //          $result = array('status'=> false, 'message'=>'Otp not sent');
                // }
            } else {
                // $subject="Forgot password";
                // $message = "Forgot password OTP ". $otp;
                // if($this->sendMail($request->email,$subject,$message))
                // {
                //     $gan_otp = ['email'=> $email, 'otp'=>$otp, 'create_at'=>$date, 'update_at'=>$date];
                //     $otp_success = DB::table('password_otp')->insert($gan_otp);
                //     if($otp_success){
                //             $result = array('status'=> true, 'message'=>'Otp sent successfully');
                //     }else{
                //             $result = array('status'=> false, 'message'=>'Otp not sent');
                //         }
                // }
                // else
                // {
                //     $result = array('status'=> false, 'message'=>'Otp not sent');
                // }
                $result = array('status' => false, 'message' => 'Invalid Email Address');
            }
        } else {
            $result = array('status' => false, 'message' => 'Email is required');
        }
        echo json_encode($result);
    }
    public function resendotp(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;
        $otp =  mt_rand(1000, 9999);
        $date = date("Y-m-d h:i:s", time());
        if (!empty($email)) {
            $check_email = DB::table('temp_users')->where('email', $email)->count();

            $subject = "Resend Otp";
            $message = "Resend OTP " . $otp;
            if ($check_email > 0) {

                $this->sendMail($request->email, $subject, $message);
                // if($this->sendMail($email,$subject,$message))
                // {
                $up_otp = ['otp' => $otp, 'create_at' => $date, 'update_at' => $date, 'email' => $email];
                $upt_success = DB::table('password_otp')->where('email', $email)->update($up_otp);
                if ($upt_success) {

                    $result = array('status' => true, 'message' => 'OTP send successfully');
                } else {
                    $upt_success2 = DB::table('password_otp')->insert($up_otp);
                    if ($upt_success2) {
                        $result = array('status' => true, 'message' => 'OTP send successfully');
                    } else {
                        $result = array('status' => false, 'message' => 'OTP not Send');
                    }
                }
                //        }
                // else
                // {
                //          $result = array('status'=> false, 'message'=>'Otp not sent');
                // }
            } else {
                // $subject="Forgot password";
                // $message = "Forgot password OTP ". $otp;
                // if($this->sendMail($request->email,$subject,$message))
                // {
                //     $gan_otp = ['email'=> $email, 'otp'=>$otp, 'create_at'=>$date, 'update_at'=>$date];
                //     $otp_success = DB::table('password_otp')->insert($gan_otp);
                //     if($otp_success){
                //             $result = array('status'=> true, 'message'=>'Otp sent successfully');
                //     }else{
                //             $result = array('status'=> false, 'message'=>'Otp not sent');
                //         }
                // }
                // else
                // {
                //     $result = array('status'=> false, 'message'=>'Otp not sent');
                // }
                $result = array('status' => false, 'message' => 'Invalid Email Address');
            }
        } else {
            $result = array('status' => false, 'message' => 'Email is required');
        }
        echo json_encode($result);
    }
    public function get_allusers()
    {
        $get_allusers = DB::table('users')->where('role', 97)->get();
        if (!empty($get_allusers)) {
            $result = array('status' => true, "data" => $get_allusers);
        } else {
            $result = array('status' => false, "message" => "No Record Found");
        }
        echo json_encode($result);
    }

    public function passwordVerification(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;
        $otp = $request->otp;
        $method = $request->method;

        $verify_otp = DB::table('password_otp')->where('email', $email)->where('otp', $otp)->first();
        // echo "<pre>";
        // // print_r($verify_otp);
        // // exit;
        if (!empty($verify_otp)) {
            //    $otp_expires_time = Carbon::now()->subMinutes(5);
            $otp_expires_time =  date('m/d/Y h:i:s', time());
            if ($verify_otp->create_at < $otp_expires_time) {
                $result = array('status' => false, 'message' => 'OTP Expired.');
            } else {
                DB::table('password_otp')->where('email', $email)->delete();
                $user_data = DB::table('temp_users')->where('email', $email)->first();
                //  dd($user_data);

                $image_url = url('public/images/userimage.png');
                $date = date("Y-m-d h:i:s", time());
                if ($method == 1) {
                    $updateData['email'] = $user_data->email;
                    $updateData['name'] = $user_data->name;
                    $updateData['username'] = $user_data->username;
                    $updateData['country_code'] = $user_data->country_code;
                    $updateData['password'] = $user_data->password;
                    $updateData['role'] = $user_data->role;
                    $updateData['image'] =  isset($user_data->image) ? $user_data->image : $image_url;
                    $updateData['created_at'] =   $date;
                    $updateData['updated_at'] =   $date;
                    $updateData['status'] =   1;
                    //  dd($updateData);

                    DB::table('users')->insert($updateData);
                    DB::table('temp_users')->where('email', $email)->delete();
                    $insertedData =  DB::table('users')->where('email', $email)->first();
                    $result = array('status' => true, 'message' => 'Your account has been created successfully.', 'data' => $insertedData);
                } else {
                    $insertedData =  DB::table('users')->where('email', $email)->first();
                    $result = array('status' => true, 'message' => 'Password OTP verified.', 'data' => $insertedData);
                }
            }
        } else {
            $result = array('status' => false, 'message' => 'invalid Otp');
        }

        echo json_encode($result);
    }
    public function forgetpasswordVerification(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;
        $otp = $request->otp;

        $verify_otp = DB::table('password_otp')->where('email', $email)->where('otp', $otp)->first();
        // echo "<pre>";
        // // print_r($verify_otp);
        // // exit;
        if (!empty($verify_otp)) {
            //    $otp_expires_time = Carbon::now()->subMinutes(5);
            $otp_expires_time =  date('m/d/Y h:i:s', time());
            if ($verify_otp->create_at < $otp_expires_time) {
                $result = array('status' => false, 'message' => 'OTP Expired.');
            } else {
                DB::table('password_otp')->where('email', $email)->delete();
                $user_data = DB::table('users')->where('email', $email)->first();
                $image_url = url('public/images/userimage.png');
                $user_data->image =  isset($user_data->image) ? $user_data->image : $image_url;
                $result = array('status' => true, 'message' => 'OTP Valid Successfull.', 'data' => $user_data);
            }
        } else {
            $result = array('status' => false, 'message' => 'invalid email or OTP');
        }

        echo json_encode($result);
    }

    public function passwordUpdate(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email = $request->email;

        $newPassword = Hash::make($request->newPassword);
        // $confirmPassword = Hash::make($request->confirmPassword);

        if (!isset($email)) {
            $result = array('status' => false, 'message' => 'Email is required');
        } else if (!isset($request->newPassword)) {
            $result = array('status' => false, 'message' => 'New password is required');
        } else if (!isset($request->confirmPassword)) {
            $result = array('status' => false, 'message' => 'Confirm password is required');
        } else if (!Hash::check($request->confirmPassword, $newPassword)) {
            $result = array('status' => false, 'message' => 'password not match');
        } else {
            $date = date("Y-m-d h:i:s", time());
            $data = ['password' => $newPassword, 'updated_at' => $date];
            $pass_upde = DB::table('users')->where('email', $email)->update($data);
            if ($pass_upde) {
                $result = array('status' => true, 'message' => 'Password reset successfully.');
            } else {
                $result = array('status' => false, 'message' => 'password not changed.');
            }
        }

        echo json_encode($result);
    }


    public function signUp(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',

        ]);
        if ($validate->fails()) {
            $result = array("status" => false, "message" => "Validation Failed", "error" => $validate->errors());
        } else {
            $res = User::where('email', $request->email)->first();
            if ($res) {
                $result = array("status" => false, "message" => "User  Already Register");
            } else {
                $verify = new Verification();

                session(["name" => $request->name]);
                session(["email" => $request->email]);
                session(["phone" => $request->phone]);
                session(["language" => $request->language]);
                session(["password" => $request->password]);
                session(["fcm_token" => $request->fcm_token]);

                $otp =  mt_rand(100000, 999999);
                $verify->email = $request->email;
                $verify->otp = $otp;
                $verify->verify_at = Carbon::now();
                $verify->save();

                $subject = "Verify your account";
                $message = $otp;
                if ($this->sendMail($request->email, $subject, $message)) {
                    //   return view('Pages.email-verification');
                    $result = array('status' => true, 'message' => "Send Email in your Email Address");
                } else {
                    $result = array('status' => false, 'message' => "Something Went Wrong");
                    //  return view('signup');
                }


                // $user->password=Hash::make($request->password);
                // $user->save();
                // $result=array("status"=>true,"message"=>"Send Otp In your Email Address ","data"=>$user);
            }
            echo json_encode($result);
        }
    }

    public function edit(Request $request)
    {
        $useData = DB::table('users')->where('id', $request->id)->first();

        if (!empty($useData)) {

            $result = array('status' => true, 'data' => $useData);
        } else {
            $result = array('status' => false, 'message' => 'No Record Found');
        }
        echo json_encode($result);
    }

    public function profile_update(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        if (!empty($request->id)) {
            $usreData = DB::table('users')->where('id', $request->id)->first();

            if (!isset($request->name)) {
                $result = array('status' => false, 'message' => 'Name is required');
            } else if (!isset($request->country_code)) {
                $result = array('status' => false, 'message' => 'Please Enter country_code');
            } else if (!isset($request->phone)) {
                $result = array('status' => false, 'message' => 'Phone Number is required');
            } else if (!isset($request->dob)) {
                $result = array('status' => false, 'message' => 'Date of Birth is required');
            } else {
                $fileimage = "";
                $image_url = '';
                if ($request->hasfile('image')) {
                    $file_image = $request->file('image');
                    $fileimage = md5(date("Y-m-d h:i:s", time())) . "." . $file_image->getClientOriginalExtension();
                    $destination = public_path("images");
                    $file_image->move($destination, $fileimage);
                    $image_url = url('public/images') . '/' . $fileimage;
                } else {
                    $image_url = $usreData->image;
                }
                // echo $image_url;
                // exit;
                $updateData = array(
                    'name' => isset($request->name) ? $request->name : $usreData->name,
                    // 'email'=>isset($request->email)? $request->email : $usreData->email,
                    'phone' => isset($request->phone) ? $request->phone : $usreData->phone,
                    'dob' => isset($request->dob) ? $request->dob : $usreData->dob,
                    'country_code' => isset($request->country_code) ? $request->country_code : $usreData->country_code,
                    'image' => $image_url,
                    'updated_at' => date("Y-m-d h:i:s", time())
                );
                $updateRecord = DB::table('users')->where('id', $usreData->id)->update($updateData);
                if ($updateRecord) {
                    $updatedeData = DB::table('users')->where('id', $request->id)->first();
                    $result = array('status' => true, 'message' => 'Your profile is updated successfully.', 'data' => $updatedeData);
                } else {
                    $result = array('status' => false, 'message' => 'Profile Updated Failed.');
                }
            }
        } else {
            $result = array('status' => false, 'message' => 'No Record Found');
        }
        echo json_encode($result);
    }


    public function sendMail($email, $stubject = NULL, $message = NULL)
    {

        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);     // Passing `true` enables exceptions
        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = "smtp.gmail.com";
            $mail->Port = 587;
            $mail->SMTPSecure = "tls";
            $mail->SMTPAuth = true;
            $mail->Username = "wemarkspot@gmail.com"; //"raviappic@gmail.com";
            $mail->Password = "dwspcijqkcgagrzl"; //"audnjvohywazsdqo";
            $mail->addAddress($email, "User Name");
            $mail->Subject = $stubject;
            $mail->isHTML();
            $mail->Body = $message;
            $mail->setFrom("raviappic@gmail.com");
            $mail->FromName = "We Mark The Spot";

            if ($mail->send()) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function verifyOtp(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        $email =  session("email");
        $otp = $request->digit1 . $request->digit2 . $request->digit3 . $request->digit4 . $request->digit5 . $request->digit6;
        $otp = (int)$otp;
        $date1 = new DateTime(date('d-m-Y h:i:s', time()));
        $res = Verification::where('email', $email)->first();

        if ($res) {
            $date2 = new DateTime($res->verify_at);
            $differance = $date2->diff($date1);
            $diff = $differance->i;
            if ($diff <= 2) {
                $res = Verification::where('otp', $otp)->first();
                if ($res) {
                    Verification::where('email', $email)->delete();
                    //yaha databse me entry hogi  user ki sari details           
                    $user = new User();
                    $user->name =  session("name");
                    $user->email = session("email");
                    $user->phone = session("phone");
                    $user->language = session("language");
                    $user->password = session("password");
                    $user->device_token = session("fcm_token");
                    $user->save();

                    $result = array('status' => true, 'message' => 'User Register Successfully');
                } else {
                    $result = array('status' => false, 'message' => 'Wrong OTP ');
                }
            } else {
                $result = array('status' => false, 'message' => 'OTP Expired', 'time' => $differance);
            }
        } else {
            $result = array('status' => false, 'message' => 'email not exits ');
        }
        echo json_encode($result);
    }

    //   public function resendotp(Request $request]){
    //       'email' => 're'
    //   }




    public function fitness_one(Request $request)
    {
        //dd($request->input());
        $request->session()->put('gender', $request->gender);
        //session(["gender"=>$request->gender]);
        $request->session()->put('weight', $request->weight);
        //session(["weight"=>$request->weight]);
        //    dd($request->input());
        if (!is_null($request->weight_lb)) {
            // session(["weight_unit"=>$request->weight_unit]);
            $request->session()->put('weight_unit', $request->LB);
        } else {

            $request->session()->put('weight_unit', $request->KG);
        }
        //session(["height"=>$request->height]);
        $request->session()->put('height', $request->height);
        if (!is_null($request->height_cm)) {
            $request->session()->put('height_unit', "CN");
        } else {
            if ((!is_null($height_unit_ft)) && (!is_null($height_unit_in))) {
                $request->session()->put('height_unit', "IN");
            }
        }
        // session(["height_unit"=>$request->height_unit]);
        //dd($request->input());
        return view('Pages.fitness-survey2');
    }
    public function fitness_two(Request $request)
    {
        // dd($request->input());
        // session(["interest"=>$request->interest]);
        $request->session()->put('interest', $request->interest);
        // session(["bodyparts_work"=>$request->bodyparts_work]);
        $request->session()->put('bodyparts_work', $request->bodyparts_work);
        // session(["exercise"=>$request->exercise]);
        $request->session()->put('exercise', $request->exercise);
        // session(["height_unit"=>$request->height_unit]);
        return view('Pages.fitness-survey3');
    }

    public function fitness_survey(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'length_training' => 'required',
            'fitness_goal' => 'required',
            'diedt_impact' => 'required'
        ]);
        if ($validate->fails()) {
            $result = array('status' => false, 'message' => 'Validation failed', 'error' => $validate->errors());
        } else {
            //  dd($request->input());
            // session(["gender"=>$request->gender]);
            // session(["weight"=>$request->weight]);
            // session(["weight_unit"=>$request->weight_unit]);

            $fitness = new Fitness_survey();
            $fitness->gender = session()->get('gender'); // session("gender");
            $fitness->weight = session()->get('weight'); // session("weight");
            $fitness->weight_unit = session()->get('weight_unit'); // session("weight_unit");
            $fitness->height = session()->get('height'); // session("height");
            $fitness->height_unit = session()->get('height_unit'); //  session("height_unit");
            $fitness->interest = session()->get('interest'); // session("interest");
            $fitness->bodyparts_work = session()->get('bodyparts_work'); //session("bodyparts_work");
            $fitness->exercise = session()->get('exercise'); //  session("exercise");
            $fitness->length_training = $request->length_training;
            $fitness->fitness_goal = $request->fitness_goal;
            $fitness->diedt_impact =   $request->diedt_impact;
            // dd($fitness);
            $fitness->save();

            if ($fitness) {
                Session::forget('gender');
                Session::forget('weight');
                Session::forget('weight_unit');
                Session::forget('height');
                Session::forget('height_unit');
                Session::forget('interest');
                Session::forget('exercise');
                Session::forget('exercise');
                // $url =url('membership');
                // $result = array('status'=>true, 'message'=>'Fitness Survey Successfully',
                //     "url"=>$url );

                return redirect()->to('membership');
            } else {
                $result = array('status' => false, 'message' => 'Something Went Wrong');
            }
        }
        echo json_encode($result);
    }

    public function getquoatesdata2(Request $request) // 06-01-22
    {
        // dd($request->input());
        $id = $request->id;

        $latitude = $request->lat;
        $longitude = $request->long;
        $fcm_token = isset($request->fcm_token) ? $request->fcm_token :'';
        $updateData = array('lat' => $latitude, 'long' => $longitude,"device_token"=>$fcm_token);

        User::where('id', $id)->update($updateData);



        $dataArray = array();
        $Quoatesdata = Quoates::first();
        // $business = Giweaway::all();
        $business_details = Giweaway::join('users', 'users.id', '=', 'giweaways.user_id')->first(['users.*']);
        // dd($business_details->business_category);
        $uid = $business_details->id;

        $category_data = categorys::where('id', $business_details->business_category)->first();

        $business_details->category_name = $category_data->name;
        $data1 = DB::select("select (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where id =" . $uid);

        $distance = isset($data1[0]->distance) ?  number_format($data1[0]->distance, 2) : "0.0";
        $business_details->distance = $distance;
        // dd($business_details);
        // array_push($dataArray,$Quoatesdata);
        if (!is_null($Quoatesdata)) {

            $Quoatesdata->title = $Quoatesdata->name;
            $Quoatesdata->sub_heading = $Quoatesdata->short_information;
            $Quoatesdata->description = $Quoatesdata->detail_information;
            $result = array('status' => true, 'message' => 'Data', "quoatesdata" => $Quoatesdata, 'data' => $business_details);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }

    public function getquoatesdata(Request $request) //update code 7-1-22
    {
        // dd($request->input());
        $id = $request->id;

        $latitude = $request->lat;
        $longitude = $request->long;
        $device_token = $request->fcm_token;
        $updateData = array('lat' => $latitude, 'long' => $longitude, 'device_token' => $device_token);
      
      $userDetailsget=  User::where('id', $id)->update($updateData);
        $notification_statusget = User::select("notification_status")->where("id",$id)->first();
       

        $dataArray = array();
        $Quoatesdata = Quoates::first();
        //  echo    $sql ="select giweaways.*, users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.validity, payments.transaction_id, payments.payment_status, payments.payment_message, payments.status, payments.created_at, payments.updated_at from giweaways inner join payments on payments.user_id = giweaways.user_id inner join users on payments.user_id = users.id where payments.plan_id = 1 and date(payments.startDate) >= ".date("Y-m-d")." and date(payments.endDate) <= ".date("Y-m-d")." limit 1";

        // $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category
        // ,users.business_images, users.business_name, users.long,users.description, 
        // payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate,
        //  payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country,
        //   payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status,
        //   payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 1
        //     and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " limit 1";

//   $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category
//         ,users.business_images, users.business_name, users.long,users.description, 
//         payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate,
//          payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country,
//           payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status,
//           payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 1 or payments.plan_id = 3
//             and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "' limit 1";

        //=========== Here ===================================== //RR
        $ddate = date('Y-m-d');
        $week = date("W", strtotime($ddate));  //Get Week
        $year = date("Y", strtotime($ddate));  //Get Year
    
        $result = $this->Start_End_Date_of_a_week($week,$year);
        $first_day = date('m/d/Y', strtotime('-1 day', strtotime($result[0])));  //Start day of week
        $last_day = date('m/d/Y', strtotime('-1 day', strtotime($result[1])));   //End Day of Week

        $sql = "select  users.id as user_id,users.notification_status as notification_status, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category
        ,users.business_images, users.business_name, users.long,users.description, 
        payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate,
         payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country,
          payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status,
           payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where (payments.plan_id = 1 or payments.plan_id = 3) and (payments.startDate = '" . $result[0] . "' and payments.endDate = '" . $result[1] . "') order by payments.id DESC limit 1";
        //=========== Here ===================================== //RR

        $business_details = DB::select($sql);

        //    dd($business_details[0]);
        // $business = Giweaway::all();
        $object = (object)[];
        //dd($business_details[0]->business_category);
        if ($business_details) {
            $uid = $business_details[0]->user_id;
            $business_details[0]->avgratting = number_format(BusinessReviews::where('business_id', $uid)->avg('ratting'), 2);
            $category_data = categorys::where('id', $business_details[0]->business_category)->first();

            $business_details[0]->category_name = isset($category_data->name) ?  $category_data->name : '';
            $data1 = DB::select("select (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where id =" . $uid);

            $distance = isset($data1[0]->distance) ?  number_format($data1[0]->distance, 2) : "0.0";
            $business_details[0]->distance = $distance;
            $object = (object) $business_details[0];
        }

        // dd($business_details);
        // array_push($dataArray,$Quoatesdata);
        if (!is_null($Quoatesdata)) {

            $Quoatesdata->title = $Quoatesdata->name;
            $Quoatesdata->sub_heading = $Quoatesdata->short_information;
            $Quoatesdata->description = $Quoatesdata->detail_information;


            $giweaways = Giweaway::where('status', 0)->first();
          
            if($giweaways){
                $objectgiweaways = $giweaways;
            }else{
                $objectgiweaways = (object)[];
            }
           
            $result = array(
                "notification_status"=>isset($notification_statusget->notification_status) ? $notification_statusget->notification_status: 0,
                'status' => true, 'message' => 'Data', "quoatesdata" => $Quoatesdata,
              
                'data' => $object, 'giweaways' => $objectgiweaways
            );
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }









    public function getnearby(Request $request)
    {
        //  dd($request->all());
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();
        $u_id = $request->input('user_id'); //use for checkin check out status

        
        $latitude = $user_data->lat;
        $longitude = $user_data->long;

        // $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id = " . $user_id . " limit 1";
        
    
        $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3 and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
        
        $featuredBusiness = DB::select($sql);

        $sqldis = "select *,cast((((acos(sin((" . $latitude . "*pi()/180))*sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 1.6013711922 as distance FROM users p where role=99  and business_name IS NOT NULL and image IS NOT NULL having distance < 15 order by distance asc";
        $business_details = DB::select($sqldis);

        $review_count = BusinessReviews::where('user_id', $user_id)->count(); //comment 21 - 2 - 2022
        
        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
        $totalreview = 0;
        foreach ($business_details as $b) {
           //        $review_count = BusinessReviews::where('user_id', $b->id)->count();
            $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
            if ($checkInstatus > 0) {
                $b->checkIn_status = 1; //check in 
            } else {
                $b->checkIn_status = 0; // not check in 
            }

            $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
            $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
            $b->totalReviewusers = $totalreviews;
            $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
            $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();



            $category_data = categorys::where('id', $b->business_category)->first();
            $b->category_name = isset($category_data->name) ? $category_data->name : '';

            $b->review_count = isset($review_count) ? $review_count : 0;
            $b->distance = number_format($b->distance, 1);

            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
            if ($BusinessFav > 0) {
                $b->fav = 1;
            } else {
                $b->fav = 0;
            }
            $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
            $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
            $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
        }

        if (isset($business_details)) {
            $obj =  $featuredBusiness;

            $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }

    public function getnearbytest(Request $request)
    {

      //  echo $this->distance(26.8546, 75.7667, 26.8760, 75.7567, "m") ;
       // die;
        //  dd($request->all());
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();
        $u_id = $request->input('user_id'); //use for checkin check out status

       
        $latitude =$user_data->lat;
        $longitude =$user_data->long;

        
      //  $latitude ="26.87574004834337";//m plaza
       // $longitude = "75.75648098940503";//m plaza
        $distance =5;

echo        $query = 'SELECT *, (((acos(sin(('.$latitude.'*pi()/180)) * sin((`lat`*pi()/180)) + cos(('.$latitude.'*pi()/180)) * cos((`lat`*pi()/180)) * cos((('.$longitude.'- `long`) * pi()/180)))) * 180/pi()) * 60 * 1.1515 * 1.609344) as distance FROM `users` WHERE distance <= '.$distance.'';
die;

        // $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id = " . $user_id . " limit 1";
        
    
        $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3 and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
        
        $featuredBusiness = DB::select($sql);

        $sqldis = "select *,cast((((acos(sin((" . $latitude . "*pi()/180))*sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  and business_name IS NOT NULL and image IS NOT NULL having distance < 5 order by distance asc";

        
    //     $latitude = "23.033863";
    //  $longitude = "72.585022";

    //  $users = User::select("id", \DB::raw("6371 * acos(cos(radians(" . $latitude . "))
    //          * cos(radians(lat)) * cos(radians(long) - radians(" . $longitude . "))
    //          + sin(radians(" .$latitude. ")) * sin(radians(lat))) AS distance"))
    //          ->having('distance', '<', 1000)
    //          ->orderBy('distance')
    //          ->get()->toArray();
    //        dd($users);  
             die;
        echo $sqdis = "select *,6371 * acos(cos(radians(" . $latitude . "))
        * cos(radians(lat)) * cos(radians(long) - radians(" . $longitude . "))
        + sin(radians(" .$latitude. ")) * sin(radians(lat))) AS distance FROM users where role=99  and business_name IS NOT NULL and image IS NOT NULL having distance < 5 order by distance asc";
die;



    //  $latitude = "23.139422";  //your current lat
    //  $longitude = "-82.382617"; //your current long
     
        $business_details = DB::select($sqldis);

        $review_count = BusinessReviews::where('user_id', $user_id)->count(); //comment 21 - 2 - 2022
        
        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
        $totalreview = 0;
        foreach ($business_details as $b) {
           //        $review_count = BusinessReviews::where('user_id', $b->id)->count();
            $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
            if ($checkInstatus > 0) {
                $b->checkIn_status = 1; //check in 
            } else {
                $b->checkIn_status = 0; // not check in 
            }

            $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
            $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
            $b->totalReviewusers = $totalreviews;
            $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
            $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();



            $category_data = categorys::where('id', $b->business_category)->first();
            $b->category_name = isset($category_data->name) ? $category_data->name : '';

            $b->review_count = isset($review_count) ? $review_count : 0;
            $b->distance = number_format($b->distance, 1);

            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
            if ($BusinessFav > 0) {
                $b->fav = 1;
            } else {
                $b->fav = 0;
            }
            $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
            $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
            $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
        }

        if (isset($business_details)) {
            $obj =  $featuredBusiness;

            $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }

    
    function distance($lat1, $long1, $lat2, $long2, $unit) {
        if (($lat1 == $lat2) && ($long1 == $long2)) {
            $d->distance = '1 KM';
         }
        else{
             $theta = $long1 - $long2;
              $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                      $dist = acos($dist);
                      $dist = rad2deg($dist);
                      $miles = $dist * 60 * 1.1515;
                      $unit1 = strtoupper($unit);
                      if ($unit1 == "K") {
                            $latlong= ($miles * 1.609344);
                            return  number_format($latlong,2);
                      }else{
                         $latlong= ($miles * 1.609344);
                         return  number_format($latlong,2);
                      }
            }
      }
      



      function point2point_distance($lat1, $lon1, $lat2, $lon2, $unit) 
    { 
        $theta = $lon1 - $lon2; 
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)); 
        $dist = acos($dist); 
        $dist = rad2deg($dist); 
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") 
        {
            return ($miles * 1.609344); 
        } 
        else if ($unit == "N") 
        {
        return ($miles * 0.8684);
        } 
        else 
        {
        return $miles;
      }
    }   

      public function getnearbytest3(Request $request)
      {
          //  dd($request->all());
          $user_id = $request->input('id');
          $user_data = User::where('id', $user_id)->first();
          $u_id = $request->input('user_id'); //use for checkin check out status
  
           $lat1 = $user_data->lat;
           $lon1 = $user_data->long;
  
         
           $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3 and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
          
          $featuredBusiness = DB::select($sql);
  
        // echo  $sqldis = "select *,cast((((acos(sin((" . $latitude . "*pi()/180))*sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  and business_name IS NOT NULL and image IS NOT NULL having distance < 5 order by distance asc";
 
        $sqluser = "select * FROM users p where role=99 and business_name IS NOT NULL and image IS NOT NULL;";
        
        $business_details = DB::select($sqluser);
  
  //        $business_details = DB::select($sqldis);
  
          $review_count = BusinessReviews::where('user_id', $user_id)->count(); //comment 21 - 2 - 2022
          
          $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
          $totalreview = 0;
          $unit1="";
          foreach ($business_details as $b) {
            $lat2 = $b->lat;
            $lon2 = $b->long;
          
         //   $dist= 5;
           
            // if(!empty($lat2) && !empty($lon2) )
            // {
            //      if (($lat1 == $lat2) && ($long1 == $lon2)) {
            //     $d->distance = '1 KM';
            //  }
            //     else{
            //      $theta = $long1 - $lon2;
            
            //     $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            //               $dist = acos($dist);
            //               echo $dist = rad2deg($dist);
                          
            //               $miles = $dist * 60 * 1.1515;
            //               $unit1 = strtoupper($unit1);
            //               if ($unit1 == "K") {
            //                     $latlong= ($miles * 1.609344);
            //                     $d->distance = number_format($latlong,2);
            //               }else{
            //                  $latlong= ($miles * 1.609344);
            //                  $d->distance = number_format($latlong,2);
            //               }
            //     }

            // }
            // else
            // {
            //     $b->distance=0;
            // }

            $disresult =$this->point2point_distance($lat1, $lon1, $lat2, $lon2, $unit='');
         //       dd(var_dump($disresult));
            
                if(number_format($disresult, 2) <=5)
                {
                    $b->distance = number_format($disresult, 2);
               
            
//               $d->distance= isset($disresult) ? $disresult :0;
//                $d->distance = $distance;
             //        $review_count = BusinessReviews::where('user_id', $b->id)->count();
              $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
              if ($checkInstatus > 0) {
                  $b->checkIn_status = 1; //check in 
              } else {
                  $b->checkIn_status = 0; // not check in 
              }
  
              $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
              $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
              $b->totalReviewusers = $totalreviews;
              $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
              $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();
  
  
  
              $category_data = categorys::where('id', $b->business_category)->first();
              $b->category_name = isset($category_data->name) ? $category_data->name : '';
  
              $b->review_count = isset($review_count) ? $review_count : 0;
              $b->distance  = $b->distance; //number_format($b->distance, 2);
  
              $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
              if ($BusinessFav > 0) {
                  $b->fav = 1;
              } else {
                  $b->fav = 0;
              }
              $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
              $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
              $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
          }
        }
          if (isset($business_details)) {
              $obj =  $featuredBusiness;
  
              $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
          } else {
              $result = array('status' => false, 'message' => 'No record found', 'data' => '');
          }
          echo json_encode($result);
      }

    public function getnearbytest2(Request $request)
    {
        $lat="26.8953342";//"26.89554468652618";
        $lon="75.74851989999999";//"75.74847698135443";
        $lat2="26.877078520935783";
        $lon2="75.75294135251865";
        $unit="K";
    
            
        $data = DB::table("users")
            ->select("users.id"
                ,DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(users.lat)) 
                * cos(radians(users.long) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(users.lat))) AS distance"))
                ->groupBy("users.id")
                ->get();
 
      dd($data);

    
        ///   dd($this->distance($lat1, $lon1, $lat2, $lon2, $unit));

//     echo $sql="SELECT ((ACOS(SIN($lat1 * PI() / 180) * SIN(lat * PI() / 180) + COS($lat1 * PI() / 180) * COS(lat * PI() / 180) * COS(($lon2 – long) * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS 'distance' FROM users HAVING 'distance'<='10' ORDER BY 'distance' ASC";
// die;
//      $lat = "26.8953342";
// $lon = "75.74851989999999";
// $distance = DB::table("users")
// 	->select("users.id","users.business_name"
// 		,DB::raw("6371 * acos(cos(radians(" . $lat . ")) 
// 		* cos(radians(users.lat)) 
// 		* cos(radians(users.long) - radians(" . $lon . ")) 
// 		+ sin(radians(" .$lat. ")) 
// 		* sin(radians(users.lat))) AS distance"))
// 		//->groupBy("users.id")
// 		->get();
//dd($distance);
        //  dd($request->all());
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();
        $u_id = $request->input('user_id'); //use for checkin check out status

        $latitude = $user_data->lat;
        $longitude = $user_data->long;

    
        // $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id = " . $user_id . " limit 1";
        
    
        $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3 and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
        
        $featuredBusiness = DB::select($sql);

//    echo     $query = "SELECT *, (((acos(sin((".$latitude."*pi()/180)) * sin((`latitude`*pi()/180)) + cos((".$latitude."*pi()/180)) * cos((`latitude`*pi()/180)) * cos(((".$longitude."- `longitude`)*pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance FROM `table` WHERE distance <= 5";
// die;

echo        $sqldis = "select *,cast((((acos(sin((" . $latitude . "*pi()/180))*sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  and business_name IS NOT NULL and image IS NOT NULL having distance < 5 order by distance asc";

        $business_details = DB::select($sqldis);
die;
        $review_count = BusinessReviews::where('user_id', $user_id)->count(); //comment 21 - 2 - 2022
        
        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
        $totalreview = 0;
        foreach ($business_details as $b) {
           //        $review_count = BusinessReviews::where('user_id', $b->id)->count();
            $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
            if ($checkInstatus > 0) {
                $b->checkIn_status = 1; //check in 
            } else {
                $b->checkIn_status = 0; // not check in 
            }

            $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
            $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
            $b->totalReviewusers = $totalreviews;
            $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
            $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();



            $category_data = categorys::where('id', $b->business_category)->first();
            $b->category_name = isset($category_data->name) ? $category_data->name : '';

            $b->review_count = isset($review_count) ? $review_count : 0;
            $b->distance = number_format($b->distance, 1);

            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
            if ($BusinessFav > 0) {
                $b->fav = 1;
            } else {
                $b->fav = 0;
            }
            $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
            $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
            $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
        }

        if (isset($business_details)) {
            $obj =  $featuredBusiness;

            $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }




    public function getnearby4(Request $request)
    {

        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();

        $latitude = $user_data->lat;
        $longitude = $user_data->long;





        $business_details = DB::select("select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  having distance < 5 order by distance asc");
        //    echo json_encode($business_details);
        //  $distance =number_format($data1[0]->distance,2); 
        // $business_details[0]->distance=$distance;
        $review_count = BusinessReviews::where('user_id', $user_id)->count();
        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
        $totalreview = 0;
        foreach ($business_details as $b) {
            $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
            $totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();

            $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();

            $totalreview = $totalreview + $totalreviews;
            $b->totalReviewusers = $totalreview;

            $category_data = categorys::where('id', $b->business_category)->first();
            $b->category_name = isset($category_data->name) ? $category_data->name : '';
            // $b->user_count = isset($users) ? count($users) : 0;
            $b->user_count = isset($checkin_count) ? $checkin_count : 0;
            $b->review_count = isset($review_count) ? $review_count : 0;
            $b->distance = number_format($b->distance, 1);

            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
            if ($BusinessFav > 0) {
                $b->fav = 1;
            } else {
                $b->fav = 0;
            }
        }

        if (isset($business_details)) {
            $result = array('status' => true, 'message' => 'Data', 'data' => $business_details);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }
    public function business_review(Request $request)
    {

        //  date_default_timezone_set('Asia/Kolkata');
        if ($request->input()) {
            $id  = $request->input('id');
            $business_id = $request->input('business_id');
            $user_id = $request->input('user_id');
            if ($user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Review.');
            } else {
                $ratting = $request->input('ratting');
                $review  = $request->input('review');
                $image_video_status = $request->input('image_video_status'); // 0 for not image/video only ratting 1 for image 2 for video
                $array_wheere = array('id' => $id);
                $business_review =  BusinessReviews::where($array_wheere)->first();

                if (!isset($business_review)) {
                    $fileimage = "";
                    $image_url = '';
                    if ($request->hasfile('image')) {
                        $files = $request->file('image');
                        $c = 0;
                        foreach ($files as $file) {
                            $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                            $destination = public_path("images");
                            $file->move($destination, $fileimage);
                            $image_url .= url('public/images') . '/' . $fileimage . ",";
                            $c++;
                        }
                    }

                    $data = [
                        'business_id' => $business_id,
                        'user_id' => $user_id,
                        'ratting' => isset($ratting) ? $ratting : "0",
                        'review' =>  isset($review) ? $review : '',
                        'image' =>  $image_url,
                        'tag' => isset($request->tag) ? $request->tag : '',
                        'type' => $request->type,
                        'image_video_status' => isset($image_video_status) ? $image_video_status : '0',
                        'check_status' => isset($request->check_status) ? $request->check_status : '0',
                    ];

                    if ($request->type == 'CHECK_OUT') {
                        $dataupdate = [
                            'business_id' => $business_id,
                            'user_id' => $user_id,
                            'ratting' => $ratting,
                            'review' => $review,
                            'image' => $image_url,
                            'tag' => $request->tag,
                            'type' => $request->type,
                            'image_video_status' => $image_video_status,
                            'check_status' => 2,

                        ];
                        $message = 'You are successfully checked out.';
                    } else if ($request->type == 'CHECK_IN') {

                        $message = 'Review Submitted successfully.';
                        $insertRecord =    BusinessReviews::create($data);
                    } else if ($request->type == 'REVIEW') {
                        $insertRecord =    BusinessReviews::create($data);
                        $message = 'Review Submitted successfully.';
                    }
                    if ($insertRecord) {
                        $result = array('status' => true, 'message' => $message);
                    } else {
                        $result = array('status' => false, 'message' => 'Review added  Failed.');
                    }
                } else {
                    $fileimage = "";
                    $image_url = '';
                    if ($request->hasfile('image')) {

                        $files = $request->file('image');
                        $c = 0;
                        foreach ($files as $file) {
                            $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                            $destination = public_path("images");
                            $file->move($destination, $fileimage);
                            $image_url .= url('public/images') . '/' . $fileimage . ",";
                            $c++;
                        }
                    } else {
                        $image_url = $business_review->image;
                    }

                    $updateData = array(
                        'image_video_status' => isset($image_video_status) ? $image_video_status : $business_review->image_video_status,
                        'business_id' => isset($business_id) ? $business_id : $business_review->name,
                        'user_id' => isset($user_id) ? $user_id : $business_review->user_id,
                        'ratting' => isset($ratting) ? $ratting : $business_review->ratting,
                        'review' => isset($review) ? $review  : $business_review->review,
                        'tag' => isset($request->tag) ? $request->tag : '',
                        'image' => $image_url,

                    );

                    $updateRecord = BusinessReviews::where('id', $business_review->id)->update($updateData);
                    if ($updateRecord) {
                        $result = array('status' => true, 'message' => 'Review updated successfully.');
                    } else {
                        $result = array('status' => false, 'message' => 'Review Updated  Failed.');
                    }
                }
            }
        } else {
            $result = array('status' => false, 'message' => 'Something Went Wrong');
        }
        echo json_encode($result);
    }

    public function checkinAPiOld(Request $request)
    {
        if ($request->all()) {
            $checkinput = CheckInOut::where('user_id', $request->user_id)->where('check_status', 1)->first();

            if (isset($checkinput)) {

                if ($checkinput->business_id != $request->id) {

                    $data = [
                        'type' => 'CHECK_OUT',
                        'check_status' => 2,
                        'updated_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::where('id', $checkinput->id)->update($data);
                    $data = [
                        'business_id' => $request->id,
                        'user_id' => $request->user_id,
                        'type' => 'CHECK_IN',
                        'check_status' => 1,
                        'updated_at' => date("Y-m-d h:i:s", time()),
                        'created_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::create($data);
                    $message = 'Checked in successfully.';
                    $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                } else {
                    $data = [
                        'type' => 'CHECK_OUT',
                        'check_status' => 2,
                        'updated_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::where('id', $checkinput->id)->update($data);
                    $message = 'Checked out successfully.';

                    $result = array('status' => true, 'message' => $message, 'check_status' => 2);
                }
            } else {
                $checkinput = CheckInOut::where('user_id', $request->user_id)->where('check_status', 2)->where('business_id', $request->id)->first();
                if (isset($checkinput)) {
                    $data = [
                        'type' => 'CHECK_IN',
                        'check_status' => 1,
                        'updated_at' => date("Y-m-d h:i:s", time()),
                        'created_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::where('id', $checkinput->id)->Update($data);
                    $message = 'Checked in successfully.';
                    $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                } else {
                    $data = [
                        'business_id' => $request->id,
                        'user_id' => $request->user_id,
                        'type' => 'CHECK_IN',
                        'check_status' => 1,
                        'updated_at' => date("Y-m-d h:i:s", time()),
                        'created_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::create($data);
                    $message = 'Checked in successfully.';
                    $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                }
            }
        } else {
            $result = array('status' => false, 'message' => 'Something Went Wrong');
        }
        echo json_encode($result);
    }

    public function checkinAPicheck(Request $request)
    {
        if ($request->all()) {

            $checkinput = CheckInOut::where('user_id', $request->user_id)->where('check_status', 1)->first();
            if (isset($checkinput)) {
                $user_data = User::select('business_name')->where('id', $request->user_id)->first();

                $message = 'You are already Check In ' . $user_data->business_name;
                $result = array('status' => true, 'message' => $message, 'check_status' => 1);
            } else {
                $checkinput = CheckInOut::where('user_id', $request->user_id)->where('check_status', $request->type)->first();

                if ($checkinput->check_status == 2) {
                    $data = [
                        'business_id' => $request->id,
                        'user_id' => $request->user_id,
                        'type' => 'CHECK_IN',
                        'check_status' => 1,
                        'updated_at' => date("Y-m-d h:i:s", time()),
                        'created_at' => date("Y-m-d h:i:s", time())
                    ];
                    $insertRecord =    CheckInOut::create($data);
                    $message = 'You are successfully checked into this business.';
                }
                $result = array('status' => true, 'message' => $message, 'check_status' => 1);
            }
        } else {
            $result = array('status' => false, 'message' => 'Something Went Wrong');
        }
        echo json_encode($result);
    }

    public function checkinAPi(Request $request)
    {
        if ($request->all()) {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to CheckIn');
            } else {
                if ($request->type == 1) {
                    $checkinput = CheckInOut::where('user_id', $request->user_id)->where('check_status', 1)->first();
                    if (isset($checkinput)) {
                        $user_data = User::where('id', $checkinput->business_id)->where('business_name', '!=', null)->first();

                        if ($user_data) {
                            $message = 'You are already Check In ' . $user_data->business_name;
                            $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                        } else {
                            $message = 'You are already Check In XYZ';
                            $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                        }
                    } else {
                        $data = [
                            'business_id' => $request->id,
                            'user_id' => $request->user_id,
                            'type' => 'CHECK_IN',
                            'check_status' => 1,
                            
                        ];
                        $insertRecord = CheckInOut::create($data);
                        $message = 'You are successfully checked into this business.';
                        $result = array('status' => true, 'message' => $message, 'check_status' => 1);
                    }
                } else {
                    $checkinput = CheckInOut::where('user_id', $request->user_id)->where('business_id', $request->id)->where('check_status', 1)->first();
                    if (empty($checkinput)) {
                        $result = array('status' => false, 'message' => 'Your Not Checked In this Business');
                    } else {
                        // $as= CheckInOut::where('user_id', $request->user_id)->where('check_status', 1)->first();
                        $data = [
                            'business_id' => $request->id,
                            'user_id' => $request->user_id,
                            'type' => 'CHECK_OUT',
                            'check_status' => 2,
                            
                        ];
                        $insertRecord = CheckInOut::where('user_id', $request->user_id)->where('business_id', $request->id)->where('check_status', "1")->update($data);
                        $message = 'You are successfully checked out.';
                        $result = array('status' => true, 'message' => $message, 'check_status' => 2);
                    }
                }
            }
        } else {
            $result = array('status' => false, 'message' => 'Something Went Wrong');
        }
        echo json_encode($result);
    }

    public function checkInList(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validate->fails()) {
            $result = array('status' => false, "message" => 'Validation Failed', 'errors' => $validate->errors());
        } else {
            //$check = BusinessReviews::where('business_id', $request->business_id)
            $check = CheckInOut::with('business')->where('user_id', $request->user_id)  
                ->latest()             
                ->get();
            if ($check) {         
                
                $result = array('status' => true, "message" => 'check In List','data'=>$check);
            } else {
                $result = array('status' => false, "message" => 'check In First');
            }
        }

        echo json_encode($result);
    }

    public function business_review_delete(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'review_id' => 'required',
            'user_id' => 'required',

        ]);
        if ($validate->fails()) {
            $result = array('status' => false, "message" => 'Validation Failed', 'errors' => $validate->errors());
        } else {
            BusinessReviews::where('id', $request->review_id)->where('user_id', $request->user_id)->delete();
            $result = array('status' => true, "message" => 'Review Deleted');
        }
        echo json_encode($result);
    }

    public function checkOut(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required',
            'business_id' => 'required',

        ]);
        if ($validate->fails()) {
            $result = array('status' => false, "message" => 'Validation Failed', 'errors' => $validate->errors());
        } else {
            //$check = BusinessReviews::where('business_id', $request->business_id)
            $check = CheckInOut::where('business_id', $request->business_id)
                ->where('user_id', $request->user_id)
                ->where('check_status', 1)
                ->where('type', 'CHECK_IN')
                ->where('created_at', 'like', '%' . date('Y-m-d') . '%')
                ->first();
            if ($check) {
                $check->check_status = 2;
                $check->save();
                $result = array('status' => true, "message" => 'check Out');
            } else {
                $result = array('status' => false, "message" => 'check In First');
            }
        }

        echo json_encode($result);
    }




    public function community_reviews(Request $request)
    {
        //Businessreviewlikedislike
        if ($request->input()) {
            $user_id = $request->user_id;
            $business_id = $request->business_id;

            $usreData = BusinessReviews::join('users', "users.id", "=", "business_reviews.user_id")
                ->where(array('business_reviews.business_id' => $business_id))
                ->where(array('business_reviews.status' => 0))
                ->orderBy('business_reviews.id', 'desc')
                ->select(
                    'users.id',
                    'users.name',
                    'users.image as image',
                    'business_reviews.type',
                    'business_reviews.image_video_status',
                    'users.business_images as business_images',
                    'business_reviews.ratting',
                    'business_reviews.status',
                    'business_reviews.business_id as business_id',
                    'business_reviews.id as business_reviews_id',
                    'business_reviews.review',
                    'business_reviews.image as business_review_image'
                )
                ->get();
            $replies_co = 0;


            $like = 0;
            $dislike = 0;
            $like_status = 0;
            $total_likeDislike = 0;
            if (isset($usreData)) {
                foreach ($usreData as $review) {
                    $replies_count = Replies::where(['review_id' => $review->business_reviews_id])->count();
                    $review->replies_count = $replies_count;
                    if ($review->business_review_image) {
                        $review->business_review_image = explode(",", rtrim($review->business_review_image, ","));
                    } else {
                        $review->business_review_image = [];
                    }

                    if (!empty($review->business_review_image)) {
                        $review->business_review_image_status = $business_review_image_status = 1;
                    } else {
                        $review->business_review_image_status = $business_review_image_status = 0;
                    }

                    // $business_reviewlikeData = Businessreviewlikedislike::where([
                    //     'businessreview_id' => $review->business_reviews_id,
                    //     'user_id' => $user_id, 'business_id' => $business_id
                    // ])->first();



                    $total_like = Businessreviewlikedislike::where([
                        'businessreview_id' => $review->business_reviews_id,
                        'likedislike' => 1, 'business_id' => $business_id
                    ])->count();

                    $total_dislike = Businessreviewlikedislike::where([
                        'businessreview_id' => $review->business_reviews_id,
                        'likedislike' => 2, 'business_id' => $business_id
                    ])->count();


                    if (($total_like == 0) && ($total_dislike == 0)) {
                        $like_status = 0;
                    } else {
                        $likedislikeData = Businessreviewlikedislike::where([
                            'businessreview_id' => $review->business_reviews_id,
                            'user_id' => $user_id, 'business_id' => $business_id
                        ])->first();
                        if ($likedislikeData) {
                            $like_status =  $likedislikeData->likedislike;
                        } else {
                            $like_status = 0;
                        }
                    }
                    $review->like_status = $like_status;
                    $review->total_like = $total_like;
                    $review->total_dislike = $total_dislike;

                    $BuinessReports = BuinessReports::where(array('business_id' => $review->business_id, 'report_status' => 0, 'review_id' => $review->business_reviews_id))->count();
                    if ($BuinessReports > 0) {
                        $review->report_status = 1; //1 for report
                    } else {
                        $review->report_status = 0; //0 for not report
                    }
                }
            }


            if ($usreData) {
                $result = array('total_count'=>count($usreData),'status' => true, 'message' => 'data ', 'data' => $usreData);
            } else {
                $result = array('status' => false, 'message' => '', 'data' => '');
            }
            echo json_encode($result);
        }
    }

    public function community_reviewstest(Request $request)
    {
        //Businessreviewlikedislike
        if ($request->input()) {
            $user_id = $request->user_id;
            $business_id = $request->business_id;

            $usreData = BusinessReviews::join('users', "users.id", "=", "business_reviews.user_id")
                ->where(array('business_reviews.business_id' => $business_id))
                ->where(array('business_reviews.status' => 0))
                ->orderBy('business_reviews.id', 'desc')
                ->select(
                    'users.id',
                    'users.name',
                    'users.image as image',
                    'business_reviews.type',
                    'business_reviews.image_video_status',
                    'users.business_images as business_images',
                    'business_reviews.ratting',
                    'business_reviews.status',
                    'business_reviews.business_id as business_id',
                    'business_reviews.id as business_reviews_id',
                    'business_reviews.review',
                    'business_reviews.image as business_review_image'
                )
                ->get();
            $replies_co = 0;


            $like = 0;
            $dislike = 0;
            $like_status = 0;
            $total_likeDislike = 0;

            if (isset($usreData)) {
                foreach ($usreData as $review) {
                    $replies_count = Replies::where(['review_id' => $review->business_reviews_id])->count();
                    $review->replies_count = $replies_count;
                    if (!empty($review->business_review_image)) {
                        $review->business_review_image_status = $business_review_image_status = 1;
                    } else {
                        $review->business_review_image_status = $business_review_image_status = 0;
                    }

                    // $business_reviewlikeData = Businessreviewlikedislike::where([
                    //     'businessreview_id' => $review->business_reviews_id,
                    //     'user_id' => $user_id, 'business_id' => $business_id
                    // ])->first();



                    $total_like = Businessreviewlikedislike::where([
                        'businessreview_id' => $review->business_reviews_id,
                        'likedislike' => 1, 'business_id' => $business_id
                    ])->count();

                    $total_dislike = Businessreviewlikedislike::where([
                        'businessreview_id' => $review->business_reviews_id,
                        'likedislike' => 2, 'business_id' => $business_id
                    ])->count();


                    if (($total_like == 0) && ($total_dislike == 0)) {
                        $like_status = 0;
                    } else {
                        $likedislikeData = Businessreviewlikedislike::where([
                            'businessreview_id' => $review->business_reviews_id,
                            'user_id' => $user_id, 'business_id' => $business_id
                        ])->first();
                        if ($likedislikeData) {
                            $like_status =  $likedislikeData->likedislike;
                        } else {
                            $like_status = 0;
                        }
                    }
                    $review->like_status = $like_status;
                    $review->total_like = $total_like;
                    $review->total_dislike = $total_dislike;

                    $BuinessReports = BuinessReports::where(array('business_id' => $review->business_id, 'review_id' => $review->business_reviews_id))->count();
                    if ($BuinessReports > 0) {
                        $review->report_status = 1; //1 for report
                    } else {
                        $review->report_status = 0; //0 for not report
                    }
                }
            }


            if ($usreData) {
                $result = array('status' => true, 'message' => 'data ', 'data' => $usreData);
            } else {
                $result = array('status' => false, 'message' => '', 'data' => '');
            }
            echo json_encode($result);
        }
    }

    public function get_businessusers(Request $request)
    {
        if ($request->input()) {
            $name = $request->name;

            $business_name = User::where(['name' => $name, 'role' => 99])
                ->orWhere('name', 'like', '%' . $name . '%')->get();
            if ($business_name) {
                $result = array('status' => true, "message" => '', 'data' => $business_name);
            } else {
                $result = array('status' => true, "message" => 'no record found', 'data' => '');
            }
            echo json_encode($result);
        }
    }

    public function add_hotspots(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');
        if ($request->input()) {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to Add Hotspot');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }
                // echo $image_url;
                // exit;
                $data = [
                    'video_image_status' => $request->video_image_status,
                    'image' => $image_url,
                    'business_id' => isset($request->business_id) ? $request->business_id : '0',
                    'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                    'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                    'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                    'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                    'user_id' => $request->user_id,
                    'message' => $request->message,
                    'updated_at' => date("Y-m-d H:i:s", time()),
                    'created_at' => date("Y-m-d H:i:s", time())
                ];
                //  dd($data);
                $insertRecord =    Hotspots::create($data)->id;
                if ($insertRecord) {
                    //notification
                    //1. User should be notified when a favorite business is mentioned in the Hotspot.
                   
                    if($request->business_id!=0)
                    {
                        $getbusiness_fav1 =   DB::table('business_fav')->where("business_id",$request->business_id)->get();
                        if(!empty($getbusiness_fav1))
                        {
                           foreach($getbusiness_fav1 as $f)
                           {
                               $business1Details =   DB::table("users")->select("device_token","name")->where("id",$f->user_id)->first();
                       //   dd($business1Details);
                            $business1NameDetails =   DB::table("users")->select("device_token","name","business_name")->where("id",$f->business_id)->first();
                            $title2= $business1NameDetails->business_name;
                           // $body2 = $business1NameDetails->business_name. " business is added in the hotspot";
                            $body2 = $request->message;
                      
                            $device_token = $business1Details->device_token;
                        if(!empty($device_token))
                           {
                            $this->sendNotification($title2, $body2,$device_token, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                        }
                    }
                    if($request->business_id2!=0)
                    {
                        $getbusiness_fav2=   DB::table('business_fav')->where("business_id",$request->business_id2)->get();
                        if(!empty($getbusiness_fav2))
                        {
                            foreach($getbusiness_fav2 as $f2)
                            {

                            
                            $business1Details2 =   DB::table("users")->select("device_token","name")->where("id",$f2->user_id)->first();
                            $business1NameDetails2 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f2->business_id)->first();
                            $title3= $business1NameDetails2->business_name;
                           // $body3 = $business1NameDetails2->business_name." business is added in the hotspot";
                            $body3 = $request->message;
                          
                            $device_token2 = $business1Details2->device_token;
                           if(!empty($device_token2))
                           {
                            $this->sendNotification($title3, $body3,$device_token2, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                    }
                    }

                    if($request->business_id3!=0)
                    {
                        $getbusiness_fav3=   DB::table('business_fav')->where("business_id",$request->business_id3)->get();
                        if(!empty($getbusiness_fav3))
                        {
                            foreach($getbusiness_fav3 as $f3)
                            {
                                $business1Details3 =   DB::table("users")->select("device_token","name")->where("id",$f3->user_id)->first();
                            $business1NameDetails3 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f3->business_id)->first();
                            $title4= $business1NameDetails3->business_name;
                         //   $body4 = $business1NameDetails3->business_name." business is added in the hotspot";
                            $body4 = $request->message;
                          
                         $device_token4 = $business1Details3->device_token;
                           if(!empty($device_token4))
                           {
                            $this->sendNotification($title4, $body4,$device_token4, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                        }
                    }

                    if($request->business_id4!=0)
                    {
                        $getbusiness_fav4=   DB::table('business_fav')->where("business_id",$request->business_id4)->first();
                        if(!empty($getbusiness_fav4))
                        {
                            foreach($getbusiness_fav4 as $f4)
                            {
                             $business1Details4 =   DB::table("users")->select("device_token","name")->where("id",$f4->user_id)->first();
                            $business1NameDetails4 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f4->business_id)->first();
                            $title5= $business1NameDetails4->business_name;
                            //  $body5 = $business1NameDetails4->business_name." business is added in the hotspot";

                            $body5 = $request->message;
                           $device_token5 = $business1Details4->device_token;
                           if(!empty($device_token5))
                           {
                            $this->sendNotification($title5, $body5,$device_token5, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                        }
                    }
                    if($request->business_id5!=0)
                    {
                        $getbusiness_fav5=   DB::table('business_fav')->where("business_id",$request->business_id5)->get();
                        if(!empty($getbusiness_fav5))
                        {
                            foreach($getbusiness_fav5 as $f5)
                            {

                            
                            $business1Details5 =   DB::table("users")->select("device_token","name")->where("id",$f5->user_id)->first();
                            $business1NameDetails5 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f5->business_id)->first();
                            $title6= $business1NameDetails5->business_name;
                          //  $body6 = $business1NameDetails5->business_name." business is added in the hotspot";
                            $body6 = $request->message;
                           $device_token6 = $business1Details5->device_token;
                           if(!empty($device_token6))
                           {
                            $this->sendNotification($title6, $body6,$device_token6, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                    }
                    }
                    if($request->business_id6!=0)
                    {
                        $getbusiness_fav6=   DB::table('business_fav')->where("business_id",$request->business_id6)->get();
                        if(!empty($getbusiness_fav6))
                        {
                            foreach($getbusiness_fav6 as $f6)
                            {

                            
                            $business1Details6 =   DB::table("users")->select("device_token","name")->where("id",$f6->user_id)->first();
                            $business1NameDetails6 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f6->business_id)->first();
                            $title7= $business1NameDetails6->business_name;
                          //  $body7 = $business1NameDetails6->business_name." business is added in the hotspot";
                            $body7 = $request->message;  
                            $device_token7 = $business1Details5->device_token;
                           if(!empty($device_token7))
                           {
                            $this->sendNotification($title7, $body7,$device_token7, $insertRecord,"addhotspot","",1,$request->user_id);
                           }
                        }
                        }
                    }
                    
                    //end
                    $Details =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                
                   if(!empty($Details->device_token))
                   {
                     $deviceToken = $Details->device_token;
                
               $title = "Add a hotspot message";
                    $body = $Details->name." message is successfully posted in the hotspot";
                 //  $this->sendNotification($title, $body, $deviceToken,$insertRecord,"addhotspot","");
                }
                    //notification
                    $result = array('status' => true, 'message' => 'Your message is successfully posted in the hotspot.');
                } else {
                    $result = array('status' => false, 'message' => 'Message added Failed.');
                }
            }
            echo json_encode($result);
        }
    }
    public function add_hotspots3333(Request $request)//01-07-2022 old
    {
        date_default_timezone_set('Asia/Kolkata');
        if ($request->input()) {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to Add Hotspot');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }
                // echo $image_url;
                // exit;
                $data = [
                    'video_image_status' => $request->video_image_status,
                    'image' => $image_url,
                    'business_id' => isset($request->business_id) ? $request->business_id : '0',
                    'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                    'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                    'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                    'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                    'user_id' => $request->user_id,
                    'message' => $request->message,
                    'updated_at' => date("Y-m-d H:i:s", time()),
                    'created_at' => date("Y-m-d H:i:s", time())
                ];
                //  dd($data);
                $insertRecord =    Hotspots::create($data)->id;
                if ($insertRecord) {
                    //notification
                    //1. User should be notified when a favorite business is mentioned in the Hotspot.
                   
                    if($request->business_id!=0)
                    {
                        $getbusiness_fav1 =   DB::table('business_fav')->where("business_id",$request->business_id)->get();
                        if(!empty($getbusiness_fav1))
                        {
                           foreach($getbusiness_fav1 as $f)
                           {
                               $business1Details =   DB::table("users")->select("device_token","name")->where("id",$f->user_id)->first();
                       //   dd($business1Details);
                            $business1NameDetails =   DB::table("users")->select("device_token","name","business_name")->where("id",$f->business_id)->first();
                            $title2= $business1NameDetails->business_name;
                           // $body2 = $business1NameDetails->business_name. " business is added in the hotspot";
                            $body2 = $request->message;
                      
                            $device_token = $business1Details->device_token;
                        if(!empty($device_token))
                           {
                            $this->sendNotification($title2, $body2,$device_token, $insertRecord,"addhotspot","");
                           }
                        }
                        }
                    }
                    if($request->business_id2!=0)
                    {
                        $getbusiness_fav2=   DB::table('business_fav')->where("business_id",$request->business_id2)->get();
                        if(!empty($getbusiness_fav2))
                        {
                            foreach($getbusiness_fav2 as $f2)
                            {

                            
                            $business1Details2 =   DB::table("users")->select("device_token","name")->where("id",$f2->user_id)->first();
                            $business1NameDetails2 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f2->business_id)->first();
                            $title3= $business1NameDetails2->business_name;
                           // $body3 = $business1NameDetails2->business_name." business is added in the hotspot";
                            $body3 = $request->message;
                          
                            $device_token2 = $business1Details2->device_token;
                           if(!empty($device_token2))
                           {
                            $this->sendNotification($title3, $body3,$device_token2, $insertRecord,"addhotspot","");
                           }
                        }
                    }
                    }

                    if($request->business_id3!=0)
                    {
                        $getbusiness_fav3=   DB::table('business_fav')->where("business_id",$request->business_id3)->get();
                        if(!empty($getbusiness_fav3))
                        {
                            foreach($getbusiness_fav3 as $f3)
                            {
                                $business1Details3 =   DB::table("users")->select("device_token","name")->where("id",$f3->user_id)->first();
                            $business1NameDetails3 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f3->business_id)->first();
                            $title4= $business1NameDetails3->business_name;
                         //   $body4 = $business1NameDetails3->business_name." business is added in the hotspot";
                            $body4 = $request->message;
                          
                         $device_token4 = $business1Details3->device_token;
                           if(!empty($device_token4))
                           {
                            $this->sendNotification($title4, $body4,$device_token4, $insertRecord,"addhotspot","");
                           }
                        }
                        }
                    }

                    if($request->business_id4!=0)
                    {
                        $getbusiness_fav4=   DB::table('business_fav')->where("business_id",$request->business_id4)->first();
                        if(!empty($getbusiness_fav4))
                        {
                            foreach($getbusiness_fav4 as $f4)
                            {
                             $business1Details4 =   DB::table("users")->select("device_token","name")->where("id",$f4->user_id)->first();
                            $business1NameDetails4 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f4->business_id)->first();
                            $title5= $business1NameDetails4->business_name;
                            //  $body5 = $business1NameDetails4->business_name." business is added in the hotspot";

                            $body5 = $request->message;
                           $device_token5 = $business1Details4->device_token;
                           if(!empty($device_token5))
                           {
                            $this->sendNotification($title5, $body5,$device_token5, $insertRecord,"addhotspot","");
                           }
                        }
                        }
                    }
                    if($request->business_id5!=0)
                    {
                        $getbusiness_fav5=   DB::table('business_fav')->where("business_id",$request->business_id5)->get();
                        if(!empty($getbusiness_fav5))
                        {
                            foreach($getbusiness_fav5 as $f5)
                            {

                            
                            $business1Details5 =   DB::table("users")->select("device_token","name")->where("id",$f5->user_id)->first();
                            $business1NameDetails5 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f5->business_id)->first();
                            $title6= $business1NameDetails5->business_name;
                          //  $body6 = $business1NameDetails5->business_name." business is added in the hotspot";
                            $body6 = $request->message;
                           $device_token6 = $business1Details5->device_token;
                           if(!empty($device_token6))
                           {
                            $this->sendNotification($title6, $body6,$device_token6, $insertRecord,"addhotspot","");
                           }
                        }
                    }
                    }
                    if($request->business_id6!=0)
                    {
                        $getbusiness_fav6=   DB::table('business_fav')->where("business_id",$request->business_id6)->get();
                        if(!empty($getbusiness_fav6))
                        {
                            foreach($getbusiness_fav6 as $f6)
                            {

                            
                            $business1Details6 =   DB::table("users")->select("device_token","name")->where("id",$f6->user_id)->first();
                            $business1NameDetails6 =   DB::table("users")->select("device_token","name","business_name")->where("id",$f6->business_id)->first();
                            $title7= $business1NameDetails6->business_name;
                          //  $body7 = $business1NameDetails6->business_name." business is added in the hotspot";
                            $body7 = $request->message;  
                            $device_token7 = $business1Details5->device_token;
                           if(!empty($device_token7))
                           {
                            $this->sendNotification($title7, $body7,$device_token7, $insertRecord,"addhotspot","");
                           }
                        }
                        }
                    }
                    
                    //end
                    $Details =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                
                   if(!empty($Details->device_token))
                   {
                     $deviceToken = $Details->device_token;
                
               $title = "Add a hotspot message";
                    $body = $Details->name." message is successfully posted in the hotspot";
                 //  $this->sendNotification($title, $body, $deviceToken,$insertRecord,"addhotspot","");
                }
                    //notification
                    $result = array('status' => true, 'message' => 'Your message is successfully posted in the hotspot.');
                } else {
                    $result = array('status' => false, 'message' => 'Message added Failed.');
                }
            }
            echo json_encode($result);
        }
    }

    public function gethotspots2()
    {

        $imestamp = now();
        $unix_timestamp = now()->timestamp;

        $hotspots = Hotspots::join('users', 'users.id', '=', 'hotspots.user_id')
            ->join('users as business_users', 'business_users.id', '=', 'hotspots.business_id')
            ->orderBy('hotspots.created_at', 'desc')
            ->whereNotIn('hotspots.business_id', [0])
            // ->where('hotspots.created_at', Carbon::today())
            ->select('users.name as person_name', 'users.image as user_image', 'business_users.business_name as business_user_name', 'hotspots.*')
            ->get();

        $arr  = array();

        foreach ($hotspots as $k) {
            // echo "<pre>";
            // echo date("Y-m-d", strtotime($k->created_at));
            // echo ;
            //  echo Carbon::today();
            if (date("Y-m-d") == date("Y-m-d", strtotime($k->created_at))) {
                array_push($arr, $k->toArray());
            }
        }
        //  dd($arr);
        //  exit;
        // $hotspots2 = Hotspots::join('users', 'users.id', '=', 'hotspots.user_id')
        // ->orderBy('hotspots.created_at', 'desc')
        // ->whereIn('hotspots.business_id', [0])
        // ->select('users.name as person_name', 'users.image as user_image', 'hotspots.*')
        // ->get();

        //      $dataHotspots= array_merge($hotspots->toArray(),$hotspots2->toArray());
        if (isset($arr)) {
            $result = array("status" => true, "message" => "data", "data" => $arr);
        } else {
            $result = array("status" => false, "message" => "no Reocrd found", "data" => '');
        }
        echo json_encode($result);
    }
    public function gethotspots()
    {
        // date_default_timezone_set('Asia/Kolkata');

        $date = date("Y-m-d");

        // $imestamp = now();
        //  $unix_timestamp = now()->timestamp;
        $sql = "select users.name as person_name, users.image as user_image, business_users.business_name as 
                business_user_name, hotspots.* from hotspots inner join users on users.id = hotspots.user_id inner
                 join users as business_users on business_users.id = hotspots.business_id where hotspots.created_at
                  like '%" . $date . "%' order by hotspots.id desc";
        $rs = DB::select($sql);
        $rs = Hotspots::with('user:id,name,image')
            ->with('business:id,business_name,image')
            ->with('business2:id,business_name,image')
            ->with('business3:id,business_name,image')
            ->with('business4:id,business_name,image')
            ->with('business5:id,business_name,image')
            ->where('created_at', 'like', '%' . $date . '%')
            ->orderBy('id', 'desc')
            ->get();
        if (isset($rs) && !empty($rs)) {
            $image_array = array();
            foreach ($rs as $r) {

                $arrimage = explode(",", rtrim($r->image, ","));
                $r->image = $arrimage;
            }
        }
        // dd($rs);
        // exit;
        if (isset($rs)) {
            $result = array("status" => true, "message" => "data ", "data" => $rs);
        } else {
            $result = array("status" => false, "message" => "no Reocrd found", "data" => '');
        }
        echo json_encode($result);
    }

    public function gethotspots3()
    {
        // date_default_timezone_set('Asia/Kolkata');

        $date = date("Y-m-d");


        // $imestamp = now();
        //  $unix_timestamp = now()->timestamp;




        echo      $sql = "select users.name as person_name, users.image as user_image, business_users.business_name as 
                business_user_name, hotspots.* from hotspots inner join users on users.id = hotspots.user_id inner
                 join users as business_users on business_users.id = hotspots.business_id where hotspots.created_at
                  like '%" . $date . "%' order by hotspots.id desc";
        exit;
        $rs = DB::select($sql);

        if (isset($rs)) {
            $result = array("status" => true, "message" => "data", "data" => $rs);
        } else {
            $result = array("status" => false, "message" => "no Reocrd found", "data" => '');
        }
        echo json_encode($result);
    }

    public function BusinessFav(Request $request)
    {
        if ($request->input()) {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to favorite');
            } else {
                $fav = $request->fav;
                $user_id = $request->user_id;
                $business_id = $request->business_id;

                if ($fav == 1) {
                    $data = array(
                        'user_id' => $user_id, "business_id" => $business_id, 'fav' => 1, 'updated_at' => date("Y-m-d h:i:s", time()),
                        'created_at' => date("Y-m-d h:i:s", time())
                    );

                    $checked =   BusinessFav::create($data);
                    if ($checked) {
                        $result = array("status" => true, 'message' => "This business has been added successfully in your favorite list.");
                    } else {
                        $result = array("status" => false, 'message' => "Business removed from favorite  falils.");
                    }
                } else {
                    $data = array('user_id' => $user_id, "business_id" => $business_id);
                    $checked =   BusinessFav::where($data)->delete();

                    if ($checked) {
                        $result = array("status" => true, 'message' => "This business is successfully removed from your favorite list.");
                    } else {
                        $result = array("status" => false, 'message' => "Business removed from favorite  fails.");
                    }
                }
            }
            echo json_encode($result);
        }
    }

    public function replies_community_reviews1111(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'review_id' => 'required',
            'reply_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }

                if ($request->type == 'REVIEW') {


                    $data = array(
                        'business_id' => $request->business_id,
                        'video_image_status' => $request->video_image_status,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'REVIEW',
                        'image' => $image_url,
                        'message' => $request->message,
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' => date("Y-m-d G:i:s", time())
                    );

                    $inserted = Replies::create($data);
                    
                    if ($inserted) {
                        //notification
                         // changes code 06-06-22
                            $getReply = Replies::where("id",$inserted->id)->first();

                        if((!empty($getReply->reply_id)) && ($getReply->reply_id!=0))
                        {
                            $getRepldata2 = Replies::where("id",$getReply->reply_id)->first();
                            $userDetails1 =   DB::table("users")->select("device_token","name","id")->where("id",$request->user_id)->first();
                            $userDetails2 =   DB::table("users")->select("device_token","name","id")->where("id",$getRepldata2->user_id)->first();
                            $getbusiness_reviews2 = DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                            
                            if($getRepldata2->user_id !=  $request->user_id)
                           {
                                $deviceToken2= $userDetails2->device_token;
                                $title3 = "Reply on a review";
                              //  $body3 = ucfirst($userDetails1->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                              $body3 = ucfirst($userDetails1->name)." is replied on your review";


                            //   DB::table("notification")->insert(array(
                            //     "user_id"=>$request->user_id,
                            //     "review_id"=>$request->review_id,
                            //     "reply_id"=>$request->reply_id,
                            //     "type"=>$request->type,
                            //     "title"=>$title3,
                            //     "message"=>$body3,
                            //     "notification_user_id"=>$userDetails2->id
                            // ));

                                $this->sendNotification($title3, $body3, $deviceToken2,$getReply->review_id,"review",$inserted->id,0,$request->user_id);
                                // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                                if($getRepldata2->reply_id!=0)
                                {
                                    $getReply2 = Replies::where("id",$getRepldata2->reply_id)->first();
                                    $Detailsget2 =   DB::table("users")->select("device_token","name","id")->where("id",$getReply2->user_id)->first();
                                     $deviceToken44 = $Detailsget2->device_token;
                                    $title44 = "Reply on a review";
                                    $body44 = ucfirst($userDetails2->name)." is replied on ".ucfirst($userDetails2->name) ." review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title44,
                                    //     "message"=>$body44,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));

                                    $this->sendNotification($title44, $body44, $deviceToken44,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                            }
                            $Details2 =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews2->user_id)->first();
                             $deviceToken3 = $Details2->device_token;
                                if($Details2->id !=$request->user_id)
                                {
                                    $title4 = "Reply on a review";
                                   // $body4 = ucfirst($userDetails1->name)." is replied on ".ucfirst($Details2->name) ." review";

                                    $body4 = ucfirst($userDetails1->name)." is replied on your review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title4,
                                    //     "message"=>$body4,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));


                                    $this->sendNotification($title4, $body4, $deviceToken3,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                        }  
                        else if($getReply->reply_id==0)
                        {
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            
                            $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                    
                            $Details =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews->user_id)->first();
                        
                            $deviceToken = $Details->device_token;
                            $title = "Reply on a review";
                            $body = ucfirst($userDetails->name)." is replied on your review";
                            if($getbusiness_reviews->user_id!= $request->user_id)
                            {
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$Details->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                            }
                            
                        }
                        //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } 
                else if ($request->type == 'HOTSPOT') {
                    $data = array(
                        'business_id' => isset($request->business_id) ? $request->business_id : '0',
                        'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                        'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                        'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                        'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                        'video_image_status' => $request->video_image_status,
                        'image' => $image_url,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'HOTSPOT',
                        'message' => trim($request->message," "),
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' =>     date("Y-m-d G:i:s", time())
                    );
                    $inserted = Replies::create($data)->id;
                    if ($inserted) {
          
                       
                        $getUserDetailssend = DB::table("users")->where("id",$request->user_id)->first();
                            
                        if($request->reply_id==0)
                        {
                           $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();
                          //  $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();

                          $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDls->user_id)->first();
                            $reply_id = $repliesDeteails->reply_id;
                            
                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                $deviceToken = $getUserDetails->device_token;
                                $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }

                        }
                        else
                        {
                             $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                            $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();

                            $getHotspotDlss3345 = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $userDetailsfstparent =   DB::table("users","id")->where("id",$getHotspotDlss3345->user_id)->first(); //get token
                          
                            if($userDetailsfstparent->id !=$request->user_id)
                            {
                                $title303 = "Reply on a hotspot";

                                $res =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                if(!empty($res))
                                {
                                    $body303 = ucfirst($getUserDetailssend->name)." is replied on ".$res->name." hotspot reply ";
                                }
                                else{
                                    $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                }
                             
                                $deviceToken303 = $userDetailsfstparent->device_token;

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title303,
                                    "message"=>$body303,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));

                                if(!empty($request->reply_id))
                                {
                                    $getlevel1  =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                    $level1device= $getlevel1->device_token;
                                    $titlelevel1 = "Reply on a hotspot";
                                    $bodylevel1 = ucfirst($getUserDetailssend->name)." is replied on your hotspot reply";
                                    $this->sendNotification($titlelevel1, $bodylevel1, $level1device,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                }

                                $this->sendNotification($title303, $body303, $deviceToken303,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            else
                            {
                                if($userDetailsfstparent->id !=$request->user_id)
                               {
                                $title3003 = "Reply on a hotspot";
                                $body3003 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken3003 = $userDetailsfstparent->device_token;
                                
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title3003,
                                    "message"=>$body3003,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));



                                $this->sendNotification($title3003, $body3003, $deviceToken3003,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                               }
                            }

                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();//get name
                            $userDetails =   DB::table("users")->select("device_token","name","id")->where("id",$getHotspotDlss->user_id)->first(); //get token
                           
                            $reply_id = $request->review_id;
                            $deviceToken = $getUserDetails->device_token;
                        //    if($getHotspotDls->user_id!=$getUserDetailssend->user_id)
                        //    {

                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                
                                 $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name) ." is replied on ".ucfirst($getUserDetails->name)." hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));



                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            if($getHotspotDls->user_id != $getHotspotDlss->user_id)// add condition 15-06-2022
                            {
                                // if($getHotspotDlss->user_id != $userDetailsfstparent->id)
                                // {

                              
                                if($getHotspotDls->user_id != $request->user_id)
                                {
                                    $title33 = "Reply on a hotspot";
                                    $body33 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $deviceToken33 = $userDetails->device_token;
                                      if($getHotspotDlss->user_id !=$request->user_id)
                                      {

                                        DB::table("notification")->insert(array(
                                            "user_id"=>$request->user_id,
                                            "review_id"=>$request->review_id,
                                            "reply_id"=>$request->reply_id,
                                            "type"=>$request->type,
                                            "title"=>$title33,
                                            "message"=>$body33,
                                            "notification_user_id"=>$userDetailsfstparent->id
                                        ));

                                        $this->sendNotification($title33, $body33, $deviceToken33,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                     } 
                                }
                            //}
                            }
                            else
                            {
                                //16-06-22
                                if($userDetailsfstparent->id!=$request->user_id)
                                {

                                
                                $title334 = "Reply on a hotspot";
                                $body334 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $udevice_token = $userDetailsfstparent->device_token;

                                    DB::table("notification")->insert(array(
                                        "user_id"=>$request->user_id,
                                        "review_id"=>$request->review_id,
                                        "reply_id"=>$request->reply_id,
                                        "type"=>$request->type,
                                        "title"=>$title334,
                                        "message"=>$body334,
                                        "notification_user_id"=>$userDetails->id
                                    ));

                                    $this->sendNotification($title334, $body334, $udevice_token,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                    $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                }//
                            }


                        }
                    
                     //   dd($getUserDetails);
                     

                       //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else {
                    $result = array("status" => false, 'message' => "Something Went Wrong");
                }
            }
        }
        echo json_encode($result);
    }
    public function replies_community_reviews(Request $request)
    {
    
        date_default_timezone_set('Asia/Kolkata');

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'review_id' => 'required',
            'reply_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }

                if ($request->type == 'REVIEW') {


                    $data = array(
                        'business_id' => $request->business_id,
                        'video_image_status' => $request->video_image_status,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'REVIEW',
                        'image' => $image_url,
                        'message' => $request->message,
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' => date("Y-m-d G:i:s", time())
                    );

                    $inserted = Replies::create($data);
                    
                    if ($inserted) {
                        //notification
                         // changes code 06-06-22
                            $getReply = Replies::where("id",$inserted->id)->first();

                        if((!empty($getReply->reply_id)) && ($getReply->reply_id!=0))
                        {
                            $getRepldata2 = Replies::where("id",$getReply->reply_id)->first();
                            $userDetails1 =   DB::table("users")->select("device_token","name","id")->where("id",$request->user_id)->first();
                            $userDetails2 =   DB::table("users")->select("device_token","name","id")->where("id",$getRepldata2->user_id)->first();
                            $getbusiness_reviews2 = DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                            
                            if($getRepldata2->user_id !=  $request->user_id)
                           {
                                $deviceToken2= $userDetails2->device_token;
                                $title3 = "Reply on a review";
                              //  $body3 = ucfirst($userDetails1->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                              $body3 = ucfirst($userDetails1->name)." is replied on your review";


                            //   DB::table("notification")->insert(array(
                            //     "user_id"=>$request->user_id,
                            //     "review_id"=>$request->review_id,
                            //     "reply_id"=>$request->reply_id,
                            //     "type"=>$request->type,
                            //     "title"=>$title3,
                            //     "message"=>$body3,
                            //     "notification_user_id"=>$userDetails2->id
                            // ));

                                $this->sendNotification($title3, $body3, $deviceToken2,$getReply->review_id,"review",$inserted->id,0,$request->user_id);
                                // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                                if($getRepldata2->reply_id!=0)
                                {
                                    $getReply2 = Replies::where("id",$getRepldata2->reply_id)->first();
                                    $Detailsget2 =   DB::table("users")->select("device_token","name","id")->where("id",$getReply2->user_id)->first();
                                     $deviceToken44 = $Detailsget2->device_token;
                                    $title44 = "Reply on a review";
                                    $body44 = ucfirst($userDetails2->name)." is replied on ".ucfirst($userDetails2->name) ." review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title44,
                                    //     "message"=>$body44,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));

                                    $this->sendNotification($title44, $body44, $deviceToken44,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                            }
                            $Details2 =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews2->user_id)->first();
                             $deviceToken3 = $Details2->device_token;
                                if($Details2->id !=$request->user_id)
                                {
                                    $title4 = "Reply on a review";
                                   // $body4 = ucfirst($userDetails1->name)." is replied on ".ucfirst($Details2->name) ." review";

                                    $body4 = ucfirst($userDetails1->name)." is replied on your review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title4,
                                    //     "message"=>$body4,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));


                                    $this->sendNotification($title4, $body4, $deviceToken3,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                        }  
                        else if($getReply->reply_id==0)
                        {
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            
                            $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                    
                            $Details =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews->user_id)->first();
                        
                            $deviceToken = $Details->device_token;
                            $title = "Reply on a review";
                            $body = ucfirst($userDetails->name)." is replied on your review";
                            if($getbusiness_reviews->user_id!= $request->user_id)
                            {
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$Details->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                            }
                            
                        }
                        //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } 
                else if ($request->type == 'HOTSPOT') {
                    $data = array(
                        'business_id' => isset($request->business_id) ? $request->business_id : '0',
                        'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                        'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                        'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                        'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                        'video_image_status' => $request->video_image_status,
                        'image' => $image_url,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'HOTSPOT',
                        'message' => trim($request->message," "),
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' =>     date("Y-m-d G:i:s", time())
                    );
                    $inserted = Replies::create($data)->id;
                    if ($inserted) {
          
                       
                        $getUserDetailssend = DB::table("users")->where("id",$request->user_id)->first();
                            
                        if($request->reply_id==0)
                        {
                           $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();
                          //  $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();

                          $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDls->user_id)->first();
                            $reply_id = $repliesDeteails->reply_id;
                            
                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                $deviceToken = $getUserDetails->device_token;
                                $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }

                        }
                        else
                        {
                             $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                            $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();

                            $getHotspotDlss3345 = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $userDetailsfstparent =   DB::table("users","id")->where("id",$getHotspotDlss3345->user_id)->first(); //get token
                          
                            if($userDetailsfstparent->id !=$request->user_id)
                            {
                                $title303 = "Reply on a hotspot";

                                $res =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                if(!empty($res))
                                {
                                   // $body303 = ucfirst($getUserDetailssend->name)." is replied on ".$res->name." hotspot reply ";
                                   $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot reply ";
                                   
                                }
                                else{
                                    $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                }
                             
                                $deviceToken303 = $userDetailsfstparent->device_token;

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title303,
                                    "message"=>$body303,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));

                                if(!empty($request->reply_id))
                                {
                                    $getlevel1  =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                    $level1device= $getlevel1->device_token;
                                    $titlelevel1 = "Reply on a hotspot";
                                    $bodylevel1 = ucfirst($getUserDetailssend->name)." is replied on your hotspot reply";
                                    $this->sendNotification($titlelevel1, $bodylevel1, $level1device,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                }

                                $this->sendNotification($title303, $body303, $deviceToken303,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            else
                            {
                                if($userDetailsfstparent->id !=$request->user_id)
                               {
                                $title3003 = "Reply on a hotspot";
                                $body3003 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken3003 = $userDetailsfstparent->device_token;
                                
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title3003,
                                    "message"=>$body3003,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));



                                $this->sendNotification($title3003, $body3003, $deviceToken3003,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                               }
                            }

                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();//get name
                            $userDetails =   DB::table("users")->select("device_token","name","id")->where("id",$getHotspotDlss->user_id)->first(); //get token
                           
                            $reply_id = $request->review_id;
                            $deviceToken = $getUserDetails->device_token;
                        //    if($getHotspotDls->user_id!=$getUserDetailssend->user_id)
                        //    {

                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                
                                 $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name) ." is replied on ".ucfirst($getUserDetails->name)." hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));



                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            if($getHotspotDls->user_id != $getHotspotDlss->user_id)// add condition 15-06-2022
                            {
                                // if($getHotspotDlss->user_id != $userDetailsfstparent->id)
                                // {

                              
                                if($getHotspotDls->user_id != $request->user_id)
                                {
                                    $title33 = "Reply on a hotspot";
                                    $body33 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $deviceToken33 = $userDetails->device_token;
                                      if($getHotspotDlss->user_id !=$request->user_id)
                                      {

                                        DB::table("notification")->insert(array(
                                            "user_id"=>$request->user_id,
                                            "review_id"=>$request->review_id,
                                            "reply_id"=>$request->reply_id,
                                            "type"=>$request->type,
                                            "title"=>$title33,
                                            "message"=>$body33,
                                            "notification_user_id"=>$userDetailsfstparent->id
                                        ));

                                        $this->sendNotification($title33, $body33, $deviceToken33,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                     } 
                                }
                            //}
                            }
                            else
                            {
                                //16-06-22
                                if($userDetailsfstparent->id!=$request->user_id)
                                {

                                
                                $title334 = "Reply on a hotspot";
                                $body334 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $udevice_token = $userDetailsfstparent->device_token;

                                    DB::table("notification")->insert(array(
                                        "user_id"=>$request->user_id,
                                        "review_id"=>$request->review_id,
                                        "reply_id"=>$request->reply_id,
                                        "type"=>$request->type,
                                        "title"=>$title334,
                                        "message"=>$body334,
                                        "notification_user_id"=>$userDetails->id
                                    ));

                                    $this->sendNotification($title334, $body334, $udevice_token,$request->review_id,"hotspot",$inserted,0,$request->user_id);
                                    $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                }//
                            }


                        }
                    
                     //   dd($getUserDetails);
                     

                       //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else {
                    $result = array("status" => false, 'message' => "Something Went Wrong");
                }
            }
        }
        echo json_encode($result);
    }
    public function replies_community_reviews33333(Request $request)// 01-07-2022 comment
    {
        date_default_timezone_set('Asia/Kolkata');

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'review_id' => 'required',
            'reply_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }

                if ($request->type == 'REVIEW') {


                    $data = array(
                        'business_id' => $request->business_id,
                        'video_image_status' => $request->video_image_status,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'REVIEW',
                        'image' => $image_url,
                        'message' => $request->message,
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' => date("Y-m-d G:i:s", time())
                    );

                    $inserted = Replies::create($data);
                    
                    if ($inserted) {
                        //notification
                         // changes code 06-06-22
                            $getReply = Replies::where("id",$inserted->id)->first();

                        if((!empty($getReply->reply_id)) && ($getReply->reply_id!=0))
                        {
                            $getRepldata2 = Replies::where("id",$getReply->reply_id)->first();
                            $userDetails1 =   DB::table("users")->select("device_token","name","id")->where("id",$request->user_id)->first();
                            $userDetails2 =   DB::table("users")->select("device_token","name","id")->where("id",$getRepldata2->user_id)->first();
                            $getbusiness_reviews2 = DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                            
                            if($getRepldata2->user_id !=  $request->user_id)
                           {
                                $deviceToken2= $userDetails2->device_token;
                                $title3 = "Reply on a review";
                              //  $body3 = ucfirst($userDetails1->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                              $body3 = ucfirst($userDetails1->name)." is replied on your review";


                            //   DB::table("notification")->insert(array(
                            //     "user_id"=>$request->user_id,
                            //     "review_id"=>$request->review_id,
                            //     "reply_id"=>$request->reply_id,
                            //     "type"=>$request->type,
                            //     "title"=>$title3,
                            //     "message"=>$body3,
                            //     "notification_user_id"=>$userDetails2->id
                            // ));

                                $this->sendNotification($title3, $body3, $deviceToken2,$getReply->review_id,"review",$inserted->id);
                                // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                                if($getRepldata2->reply_id!=0)
                                {
                                    $getReply2 = Replies::where("id",$getRepldata2->reply_id)->first();
                                    $Detailsget2 =   DB::table("users")->select("device_token","name","id")->where("id",$getReply2->user_id)->first();
                                     $deviceToken44 = $Detailsget2->device_token;
                                    $title44 = "Reply on a review";
                                    $body44 = ucfirst($userDetails2->name)." is replied on ".ucfirst($userDetails2->name) ." review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title44,
                                    //     "message"=>$body44,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));

                                    $this->sendNotification($title44, $body44, $deviceToken44,$request->review_id,"review",$inserted->id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                            }
                            $Details2 =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews2->user_id)->first();
                             $deviceToken3 = $Details2->device_token;
                                if($Details2->id !=$request->user_id)
                                {
                                    $title4 = "Reply on a review";
                                   // $body4 = ucfirst($userDetails1->name)." is replied on ".ucfirst($Details2->name) ." review";

                                    $body4 = ucfirst($userDetails1->name)." is replied on your review";

                                    // DB::table("notification")->insert(array(
                                    //     "user_id"=>$request->user_id,
                                    //     "review_id"=>$request->review_id,
                                    //     "reply_id"=>$request->reply_id,
                                    //     "type"=>$request->type,
                                    //     "title"=>$title4,
                                    //     "message"=>$body4,
                                    //     "notification_user_id"=>$Detailsget2->id
                                    // ));


                                    $this->sendNotification($title4, $body4, $deviceToken3,$request->review_id,"review",$inserted->id);
                                    // $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                    // echo json_encode($result);
                                    // exit;
                                }
                        }  
                        else if($getReply->reply_id==0)
                        {
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            
                            $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                    
                            $Details =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews->user_id)->first();
                        
                            $deviceToken = $Details->device_token;
                            $title = "Reply on a review";
                            $body = ucfirst($userDetails->name)." is replied on your review";
                            if($getbusiness_reviews->user_id!= $request->user_id)
                            {
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$Details->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                // echo json_encode($result);
                                // exit;
                            }
                            
                        }
                        //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } 
                else if ($request->type == 'HOTSPOT') {
                    $data = array(
                        'business_id' => isset($request->business_id) ? $request->business_id : '0',
                        'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                        'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                        'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                        'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                        'video_image_status' => $request->video_image_status,
                        'image' => $image_url,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'HOTSPOT',
                        'message' => trim($request->message," "),
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' =>     date("Y-m-d G:i:s", time())
                    );
                    $inserted = Replies::create($data)->id;
                    if ($inserted) {
          
                       
                        $getUserDetailssend = DB::table("users")->where("id",$request->user_id)->first();
                            
                        if($request->reply_id==0)
                        {
                           $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();
                          //  $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();

                          $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDls->user_id)->first();
                            $reply_id = $repliesDeteails->reply_id;
                            
                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                $deviceToken = $getUserDetails->device_token;
                                $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }

                        }
                        else
                        {
                             $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                            $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();

                            $getHotspotDlss3345 = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $userDetailsfstparent =   DB::table("users","id")->where("id",$getHotspotDlss3345->user_id)->first(); //get token
                          
                            if($userDetailsfstparent->id !=$request->user_id)
                            {
                                $title303 = "Reply on a hotspot";

                                $res =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                if(!empty($res))
                                {
                                    $body303 = ucfirst($getUserDetailssend->name)." is replied on ".$res->name." hotspot reply ";
                                }
                                else{
                                    $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                }
                             
                                $deviceToken303 = $userDetailsfstparent->device_token;

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title303,
                                    "message"=>$body303,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));

                                if(!empty($request->reply_id))
                                {
                                    $getlevel1  =DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                                    $level1device= $getlevel1->device_token;
                                    $titlelevel1 = "Reply on a hotspot";
                                    $bodylevel1 = ucfirst($getUserDetailssend->name)." is replied on your hotspot reply";
                                    $this->sendNotification($titlelevel1, $bodylevel1, $level1device,$request->review_id,"hotspot",$inserted);
                                }

                                $this->sendNotification($title303, $body303, $deviceToken303,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            else
                            {
                                if($userDetailsfstparent->id !=$request->user_id)
                               {
                                $title3003 = "Reply on a hotspot";
                                $body3003 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken3003 = $userDetailsfstparent->device_token;
                                
                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title3003,
                                    "message"=>$body3003,
                                    "notification_user_id"=>$userDetailsfstparent->id
                                ));



                                $this->sendNotification($title3003, $body3003, $deviceToken3003,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                               }
                            }

                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();//get name
                            $userDetails =   DB::table("users")->select("device_token","name","id")->where("id",$getHotspotDlss->user_id)->first(); //get token
                           
                            $reply_id = $request->review_id;
                            $deviceToken = $getUserDetails->device_token;
                        //    if($getHotspotDls->user_id!=$getUserDetailssend->user_id)
                        //    {

                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                
                                 $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name) ." is replied on ".ucfirst($getUserDetails->name)." hotspot";

                                DB::table("notification")->insert(array(
                                    "user_id"=>$request->user_id,
                                    "review_id"=>$request->review_id,
                                    "reply_id"=>$request->reply_id,
                                    "type"=>$request->type,
                                    "title"=>$title,
                                    "message"=>$body,
                                    "notification_user_id"=>$getUserDetails->id
                                ));



                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            if($getHotspotDls->user_id != $getHotspotDlss->user_id)// add condition 15-06-2022
                            {
                                // if($getHotspotDlss->user_id != $userDetailsfstparent->id)
                                // {

                              
                                if($getHotspotDls->user_id != $request->user_id)
                                {
                                    $title33 = "Reply on a hotspot";
                                    $body33 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $deviceToken33 = $userDetails->device_token;
                                      if($getHotspotDlss->user_id !=$request->user_id)
                                      {

                                        DB::table("notification")->insert(array(
                                            "user_id"=>$request->user_id,
                                            "review_id"=>$request->review_id,
                                            "reply_id"=>$request->reply_id,
                                            "type"=>$request->type,
                                            "title"=>$title33,
                                            "message"=>$body33,
                                            "notification_user_id"=>$userDetailsfstparent->id
                                        ));

                                        $this->sendNotification($title33, $body33, $deviceToken33,$request->review_id,"hotspot",$inserted);
                                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                     } 
                                }
                            //}
                            }
                            else
                            {
                                //16-06-22
                                if($userDetailsfstparent->id!=$request->user_id)
                                {

                                
                                $title334 = "Reply on a hotspot";
                                $body334 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $udevice_token = $userDetailsfstparent->device_token;

                                    DB::table("notification")->insert(array(
                                        "user_id"=>$request->user_id,
                                        "review_id"=>$request->review_id,
                                        "reply_id"=>$request->reply_id,
                                        "type"=>$request->type,
                                        "title"=>$title334,
                                        "message"=>$body334,
                                        "notification_user_id"=>$userDetails->id
                                    ));

                                    $this->sendNotification($title334, $body334, $udevice_token,$request->review_id,"hotspot",$inserted);
                                    $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                }//
                            }


                        }
                    
                     //   dd($getUserDetails);
                     

                       //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else {
                    $result = array("status" => false, 'message' => "Something Went Wrong");
                }
            }
        }
        echo json_encode($result);
    }
    public function replies_community_reviews_23_6(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'review_id' => 'required',
            'reply_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }

                if ($request->type == 'REVIEW') {


                    $data = array(
                        'business_id' => $request->business_id,
                        'video_image_status' => $request->video_image_status,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'REVIEW',
                        'image' => $image_url,
                        'message' => $request->message,
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' => date("Y-m-d G:i:s", time())
                    );

                    $inserted = Replies::create($data);
                    
                    if ($inserted) {
                        //notification
                         // changes code 06-06-22
                            $getReply = Replies::where("id",$inserted->id)->first();

                        if((!empty($getReply->reply_id)) && ($getReply->reply_id!=0))
                        {
                            $getRepldata2 = Replies::where("id",$getReply->reply_id)->first();
                            $userDetails1 =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            $userDetails2 =   DB::table("users")->select("device_token","name")->where("id",$getRepldata2->user_id)->first();
                            $getbusiness_reviews2 = DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                            
                            if($getRepldata2->user_id !=  $request->user_id)
                           {
                                $deviceToken2= $userDetails2->device_token;
                                $title3 = "Reply on a review";
                              //  $body3 = ucfirst($userDetails1->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                              $body3 = ucfirst($userDetails1->name)." is replied on your review";
                                $this->sendNotification($title3, $body3, $deviceToken2,$getReply->review_id,"review",$inserted->id);
                                if($getRepldata2->reply_id!=0)
                                {
                                    $getReply2 = Replies::where("id",$getRepldata2->reply_id)->first();
                                    $Detailsget2 =   DB::table("users")->select("device_token","name")->where("id",$getReply2->user_id)->first();
                                     $deviceToken44 = $Detailsget2->device_token;
                                    $title44 = "Reply on a review";
                                    $body44 = ucfirst($userDetails2->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                                    $this->sendNotification($title44, $body44, $deviceToken44,$request->review_id,"review",$inserted->id);
                                }
                            }
                            $Details2 =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews2->user_id)->first();
                             $deviceToken3 = $Details2->device_token;
                                if($Details2->id !=$request->user_id)
                                {
                                    $title4 = "Reply on a review";
                                   // $body4 = ucfirst($userDetails1->name)." is replied on ".ucfirst($Details2->name) ." review";

                                    $body4 = ucfirst($userDetails1->name)." is replied on your review";

                                    $this->sendNotification($title4, $body4, $deviceToken3,$request->review_id,"review",$inserted->id);
                                }
                        }  
                        else if($getReply->reply_id==0)
                        {
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            
                            $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                    
                            $Details =   DB::table("users")->select("device_token","name")->where("id",$getbusiness_reviews->user_id)->first();
                        
                            $deviceToken = $Details->device_token;
                            $title = "Reply on a review";
                            $body = ucfirst($userDetails->name)." is replied on your review";
                            if($getbusiness_reviews->user_id!= $request->user_id)
                            {
                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id);
                            }
                            
                        }
                        //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else if ($request->type == 'HOTSPOT') {
                    $data = array(
                        'business_id' => isset($request->business_id) ? $request->business_id : '0',
                        'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                        'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                        'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                        'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                        'video_image_status' => $request->video_image_status,
                        'image' => $image_url,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'HOTSPOT',
                        'message' => trim($request->message," "),
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' =>     date("Y-m-d G:i:s", time())
                    );
                    $inserted = Replies::create($data)->id;
                    if ($inserted) {
          
                       
                        $getUserDetailssend = DB::table("users")->where("id",$request->user_id)->first();
                            
                        if($request->reply_id==0)
                        {
                           $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();
                          //  $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();

                          $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDls->user_id)->first();
                            $reply_id = $repliesDeteails->reply_id;
                            
                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                $deviceToken = $getUserDetails->device_token;
                                $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                // DB::table("notification")->insert(array(
                                //     "user_id"=>$request->user_id,
                                //     "review_id"=>$request->review_id,
                                //     "reply_id"=>$request->reply_id,
                                //     "type"=>$request->type,
                                //     "title"=>$title,
                                //     "message"=>$body,
                                //     "notification_user_id"=>$getUserDetails->id
                                // ));

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }

                        }
                        else
                        {
                             $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                            $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();

                            $getHotspotDlss3345 = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $userDetailsfstparent =   DB::table("users","id")->where("id",$getHotspotDlss3345->user_id)->first(); //get token
                          
                            if($userDetailsfstparent->id !=$request->user_id)
                            {
                                $title303 = "Reply on a hotspot";
                                $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken303 = $userDetailsfstparent->device_token;

                                // DB::table("notification")->insert(array(
                                //     "user_id"=>$request->user_id,
                                //     "review_id"=>$request->review_id,
                                //     "reply_id"=>$request->reply_id,
                                //     "type"=>$request->type,
                                //     "title"=>$title303,
                                //     "message"=>$body303,
                                //     "notification_user_id"=>$userDetailsfstparent->id
                                // ));


                                $this->sendNotification($title303, $body303, $deviceToken303,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            else
                            {
                                if($userDetailsfstparent->id !=$request->user_id)
                               {
                                $title3003 = "Reply on a hotspot002";
                                $body3003 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken3003 = $userDetailsfstparent->device_token;
                                
                                // DB::table("notification")->insert(array(
                                //     "user_id"=>$request->user_id,
                                //     "review_id"=>$request->review_id,
                                //     "reply_id"=>$request->reply_id,
                                //     "type"=>$request->type,
                                //     "title"=>$title3003,
                                //     "message"=>$body3003,
                                //     "notification_user_id"=>$userDetailsfstparent->id
                                // ));



                                $this->sendNotification($title3003, $body3003, $deviceToken3003,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                               }
                            }

                            $getUserDetails = DB::table("users","id")->where("id",$getHotspotDls->user_id)->first();//get name
                            $userDetails =   DB::table("users")->select("device_token","name","id")->where("id",$getHotspotDlss->user_id)->first(); //get token
                           
                            $reply_id = $request->review_id;
                            $deviceToken = $getUserDetails->device_token;
                        //    if($getHotspotDls->user_id!=$getUserDetailssend->user_id)
                        //    {

                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                
                                 $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name) ." is replied on ".ucfirst($getUserDetails->name)." hotspot";

                                // DB::table("notification")->insert(array(
                                //     "user_id"=>$request->user_id,
                                //     "review_id"=>$request->review_id,
                                //     "reply_id"=>$request->reply_id,
                                //     "type"=>$request->type,
                                //     "title"=>$title,
                                //     "message"=>$body,
                                //     "notification_user_id"=>$getUserDetails->id
                                // ));



                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                                $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                            }
                            if($getHotspotDls->user_id != $getHotspotDlss->user_id)// add condition 15-06-2022
                            {
                                // if($getHotspotDlss->user_id != $userDetailsfstparent->id)
                                // {

                              
                                if($getHotspotDls->user_id != $request->user_id)
                                {
                                    $title33 = "Reply on a hotspot";
                                    $body33 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $deviceToken33 = $userDetails->device_token;
                                      if($getHotspotDlss->user_id !=$request->user_id)
                                      {

                                        DB::table("notification")->insert(array(
                                            "user_id"=>$request->user_id,
                                            "review_id"=>$request->review_id,
                                            "reply_id"=>$request->reply_id,
                                            "type"=>$request->type,
                                            "title"=>$title33,
                                            "message"=>$body33,
                                            "notification_user_id"=>$userDetailsfstparent->id
                                        ));

                                        $this->sendNotification($title33, $body33, $deviceToken33,$request->review_id,"hotspot",$inserted);
                                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                     } 
                                }
                            //}
                            }
                            else
                            {
                                //16-06-22
                                $title334 = "Reply on a hotspot";
                                $body334 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $udevice_token = $userDetailsfstparent->device_token;

                                    DB::table("notification")->insert(array(
                                        "user_id"=>$request->user_id,
                                        "review_id"=>$request->review_id,
                                        "reply_id"=>$request->reply_id,
                                        "type"=>$request->type,
                                        "title"=>$titltitle334e33,
                                        "message"=>$body334,
                                        "notification_user_id"=>$userDetails->id
                                    ));

                                    $this->sendNotification($title334, $body334, $udevice_token,$request->review_id,"hotspot",$inserted);
                                    $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                                echo json_encode($result);
                                exit;
                                //
                            }


                        }
                    
                     //   dd($getUserDetails);
                     

                       //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else {
                    $result = array("status" => false, 'message' => "Something Went Wrong");
                }
            }
        }
        echo json_encode($result);
    }


    //
    public function replies_community_reviews_17_06(Request $request)
    {
        date_default_timezone_set('Asia/Kolkata');

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'review_id' => 'required',
            'reply_id' => 'required',
            'type' => 'required',
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
            } else {

                $fileimage = "";
                $image_url = '';

                if ($request->hasfile('image')) {
                    $files = $request->file('image');
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                    }
                }

                if ($request->type == 'REVIEW') {


                    $data = array(
                        'business_id' => $request->business_id,
                        'video_image_status' => $request->video_image_status,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'REVIEW',
                        'image' => $image_url,
                        'message' => $request->message,
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' => date("Y-m-d G:i:s", time())
                    );

                    $inserted = Replies::create($data);
                    
                    if ($inserted) {
                        //notification
                         // changes code 06-06-22
                            $getReply = Replies::where("id",$inserted->id)->first();

                        if((!empty($getReply->reply_id)) && ($getReply->reply_id!=0))
                        {
                            $getRepldata2 = Replies::where("id",$getReply->reply_id)->first();
                            $userDetails1 =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            $userDetails2 =   DB::table("users")->select("device_token","name")->where("id",$getRepldata2->user_id)->first();
                            $getbusiness_reviews2 = DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                            
                            if($getRepldata2->user_id !=  $request->user_id)
                           {
                                $deviceToken2= $userDetails2->device_token;
                                $title3 = "Reply on a review";
                              //  $body3 = ucfirst($userDetails1->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                              $body3 = ucfirst($userDetails1->name)." is replied on your review";
                                $this->sendNotification($title3, $body3, $deviceToken2,$getReply->review_id,"review",$inserted->id);
                                if($getRepldata2->reply_id!=0)
                                {
                                    $getReply2 = Replies::where("id",$getRepldata2->reply_id)->first();
                                    $Detailsget2 =   DB::table("users")->select("device_token","name")->where("id",$getReply2->user_id)->first();
                                     $deviceToken44 = $Detailsget2->device_token;
                                    $title44 = "Reply on a review";
                                    $body44 = ucfirst($userDetails2->name)." is replied on ".ucfirst($userDetails2->name) ." review";
                                    $this->sendNotification($title44, $body44, $deviceToken44,$request->review_id,"review",$inserted->id);
                                }
                            }
                            $Details2 =   DB::table("users")->select("device_token","name","id")->where("id",$getbusiness_reviews2->user_id)->first();
                             $deviceToken3 = $Details2->device_token;
                                if($Details2->id !=$request->user_id)
                                {
                                    $title4 = "Reply on a review";
                                 //   $body4 = ucfirst($userDetails1->name)." is replied on ".ucfirst($Details2->name) ." review";

                                    $body4 = ucfirst($userDetails1->name)." is replied on your review";

                                    $this->sendNotification($title4, $body4, $deviceToken3,$request->review_id,"review",$inserted->id);
                                }
                        }  
                        else if($getReply->reply_id==0)
                        {
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                            
                            $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                    
                            $Details =   DB::table("users")->select("device_token","name")->where("id",$getbusiness_reviews->user_id)->first();
                        
                            $deviceToken = $Details->device_token;
                            $title = "Reply on a review";
                            $body = ucfirst($userDetails->name)." is replied on your review";
                            if($getbusiness_reviews->user_id!= $request->user_id)
                            {
                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id);
                            }
                            
                        }
                        //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else if ($request->type == 'HOTSPOT') {
                    $data = array(
                        'business_id' => isset($request->business_id) ? $request->business_id : '0',
                        'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
                        'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
                        'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
                        'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
                        'video_image_status' => $request->video_image_status,
                        'image' => $image_url,
                        'user_id' => $request->user_id,
                        'review_id' => $request->review_id,
                        'reply_id' => $request->reply_id,
                        'type' => 'HOTSPOT',
                        'message' => trim($request->message," "),
                        'updated_at' => date("Y-m-d G:i:s", time()),
                        'created_at' =>     date("Y-m-d G:i:s", time())
                    );
                    $inserted = Replies::create($data)->id;
                    if ($inserted) {
          
                       
                        $getUserDetailssend = DB::table("users")->where("id",$request->user_id)->first();
                            
                        if($request->reply_id==0)
                        {
                           $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $getUserDetails = DB::table("users")->where("id",$getHotspotDls->user_id)->first();
                          //  $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();

                          $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDls->user_id)->first();
                            $reply_id = $repliesDeteails->reply_id;
                            
                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                $deviceToken = $getUserDetails->device_token;
                                $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name)." is replied on your hotspot";

                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                            }

                        }
                        else
                        {

                            

                            $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
                            $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                            $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();

                            $getHotspotDlss3345 = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
                            $userDetailsfstparent =   DB::table("users","id")->where("id",$getHotspotDlss3345->user_id)->first(); //get token
                          
                            if($userDetailsfstparent->id !=$request->user_id)
                            {
                                $title303 = "Reply on a hotspot";
                                $body303 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken303 = $userDetailsfstparent->device_token;
                                $this->sendNotification($title303, $body303, $deviceToken303,$request->review_id,"hotspot",$inserted);
                            }
                            else
                            {
                                if($userDetailsfstparent->id !=$request->user_id)
                               {
                                $title3003 = "Reply on a hotspot";
                                $body3003 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                $deviceToken3003 = $userDetailsfstparent->device_token;
                                $this->sendNotification($title3003, $body3003, $deviceToken3003,$request->review_id,"hotspot",$inserted);
                               }
                            }

                            $getUserDetails = DB::table("users")->where("id",$getHotspotDls->user_id)->first();//get name
                            $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDlss->user_id)->first(); //get token
                           
                            $reply_id = $request->review_id;
                            $deviceToken = $getUserDetails->device_token;
                        //    if($getHotspotDls->user_id!=$getUserDetailssend->user_id)
                        //    {

                            if($getHotspotDls->user_id != $request->user_id)
                            {
                                
                                 $title = "Reply on a hotspot";
                                $body = ucfirst($getUserDetailssend->name) ." is replied on ".ucfirst($getUserDetails->name)." hotspot";
                                $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);
                            }
                            if($getHotspotDls->user_id != $getHotspotDlss->user_id)// add condition 15-06-2022
                            {
                                // if($getHotspotDlss->user_id != $userDetailsfstparent->id)
                                // {

                              
                                if($getHotspotDls->user_id != $request->user_id)
                                {
                                    $title33 = "Reply on a hotspot";
                                    $body33 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $deviceToken33 = $userDetails->device_token;
                                      if($getHotspotDlss->user_id !=$request->user_id)
                                      {
                                        $this->sendNotification($title33, $body33, $deviceToken33,$request->review_id,"hotspot",$inserted);
                                     } 
                                }
                            //}
                            }
                            else
                            {
                                //16-06-22
                                $title334 = "Reply on a hotspot";
                                $body334 = ucfirst($getUserDetailssend->name)." is replied on your hotspot";
                                    $udevice_token = $userDetailsfstparent->device_token;
                                    $this->sendNotification($title334, $body334, $udevice_token,$request->review_id,"hotspot",$inserted);
                                //
                            }


                        }
                    
                     //   dd($getUserDetails);
                     

                       //notification
                        $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
                    } else {
                        $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
                    }
                } else {
                    $result = array("status" => false, 'message' => "Something Went Wrong");
                }
            }
        }
        echo json_encode($result);
    }
    //
    // public function replies_community_reviews(Request $request)
    // {
    //     date_default_timezone_set('Asia/Kolkata');

    //     $Validation = Validator::make($request->all(), [
    //         'user_id' => 'required',
    //         'review_id' => 'required',
    //         'reply_id' => 'required',
    //         'type' => 'required',
    //         'message' => 'required'
    //     ]);

    //     if ($Validation->fails()) {
    //         $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
    //     } else {
    //         if ($request->user_id == 72) {
    //             $result = array('status' => false, 'message' => 'Please login or signup first to Reply');
    //         } else {

    //             $fileimage = "";
    //             $image_url = '';

    //             if ($request->hasfile('image')) {
    //                 $files = $request->file('image');
    //                 $c = 0;
    //                 foreach ($files as $file) {
    //                     $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
    //                     $destination = public_path("images");
    //                     $file->move($destination, $fileimage);
    //                     $image_url .= url('public/images') . '/' . $fileimage . ",";
    //                     $c++;
    //                 }
    //             }

    //             if ($request->type == 'REVIEW') {


    //                 $data = array(
    //                     'business_id' => $request->business_id,
    //                     'video_image_status' => $request->video_image_status,
    //                     'user_id' => $request->user_id,
    //                     'review_id' => $request->review_id,
    //                     'reply_id' => $request->reply_id,
    //                     'type' => 'REVIEW',
    //                     'image' => $image_url,
    //                     'message' => $request->message,
    //                     'updated_at' => date("Y-m-d G:i:s", time()),
    //                     'created_at' => date("Y-m-d G:i:s", time())
    //                 );

    //                 $inserted = Replies::create($data);
                    
    //                 if ($inserted) {
    //                     //notification
                    
    //                   //  dd($inserted);
    //                     $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                        
    //                     $getbusiness_reviews =   DB::table("business_reviews")->select("user_id")->where("id",$request->review_id)->first();
                  
    //                     $Details =   DB::table("users")->select("device_token","name")->where("id",$getbusiness_reviews->user_id)->first();
                       
    //                     $deviceToken = $Details->device_token;
    //                     $title = "Reply on a review";
    //                     $body = $userDetails->name." is replied on your review";
    //                     $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review",$inserted->id);

    //                     //notification
    //                     $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
    //                 } else {
    //                     $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
    //                 }
    //             } else if ($request->type == 'HOTSPOT') {
    //                 $data = array(
    //                     'business_id' => isset($request->business_id) ? $request->business_id : '0',
    //                     'business_id5' => isset($request->business_id5) ? $request->business_id5 : '0',
    //                     'business_id2' => isset($request->business_id2) ? $request->business_id2 : '0',
    //                     'business_id3' => isset($request->business_id3) ? $request->business_id3 : '0',
    //                     'business_id4' => isset($request->business_id4) ? $request->business_id4 : '0',
    //                     'video_image_status' => $request->video_image_status,
    //                     'image' => $image_url,
    //                     'user_id' => $request->user_id,
    //                     'review_id' => $request->review_id,
    //                     'reply_id' => $request->reply_id,
    //                     'type' => 'HOTSPOT',
    //                     'message' => trim($request->message," "),
    //                     'updated_at' => date("Y-m-d G:i:s", time()),
    //                     'created_at' =>     date("Y-m-d G:i:s", time())
    //                 );
    //                 $inserted = Replies::create($data)->id;
    //                 if ($inserted) {
    //                    //notification
    //                     // $getreplyDetails =  DB::table("replies")->where("type","HOTSPOT")->where("reply_id",0)->where("review_id",$request->review_id)->first();
                    
    //                     // if(!empty($getreplyDetails) && ($getreplyDetails->reply_id==0) && ($getreplyDetails->user_id!=$request->user_id))
    //                     // {
                           
    //                     //            $reply_id = $getreplyDetails->reply_id;
    //                     //         $getHotspotDetails =DB::table("hotspots")->where("id",$getreplyDetails->review_id)->first();
    //                     //         $Details =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDetails->user_id)->first();
    //                     //         $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
    //                     // }
    //                     // else
    //                     // {
    //                     //     $getreplyDetails =  DB::table("replies")->where("type","HOTSPOT")->where("id",$request->review_id)->first();
    //                     //     if(!empty($getreplyDetails))
    //                     //     {
    //                     //         $reply_id = $getreplyDetails->reply_id;
    //                     //         $getHotspotDetails =DB::table("replies")->where("id",$getreplyDetails->user_id)->first();
    //                     //         $Details =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDetails->user_id)->first();
    //                     //         $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
    //                     //     }
    //                     // }
                       
                            
    //                     if($request->reply_id==0)
    //                     {
    //                        $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
    //                         $getHotspotDls = DB::table("hotspots")->where("id",$repliesDeteails->review_id)->first();
    //                         $getUserDetails = DB::table("users")->where("id",$getHotspotDls->user_id)->first();
    //                         $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
    //                         $reply_id = $repliesDeteails->reply_id;

    //                     }
    //                     else
    //                     {
    //                         $repliesDeteails =  DB::table("replies")->where("id",$inserted)->first();
    //                         $getHotspotDls = DB::table("replies")->where("id",$repliesDeteails->reply_id)->first();
                         
    //                         $getHotspotDlss = DB::table("hotspots")->where("id",$getHotspotDls->review_id)->first();
    //                        // dd($getHotspotDlss);
    //                         $getUserDetails = DB::table("users")->where("id",$getHotspotDls->user_id)->first();
    //                         $userDetails =   DB::table("users")->select("device_token","name")->where("id",$getHotspotDlss->user_id)->first();
                          
    //                         $reply_id = $request->review_id;
    //                     }
                    
    //                  //   dd($getUserDetails);
    //                    $deviceToken = $getUserDetails->device_token;
    //                    $title = "Reply on a hotspot";
    //                    $body = $userDetails->name." is replied on your hotspot";
    //                    $this->sendNotification($title, $body, $deviceToken,$request->review_id,"hotspot",$inserted);

    //                    //notification
    //                     $result = array("status" => true, 'message' => "Reply added successfully", 'record_count' => 1);
    //                 } else {
    //                     $result = array("status" => false, 'message' => "Reply added Failed", 'record_count' => 0);
    //                 }
    //             } else {
    //                 $result = array("status" => false, 'message' => "Something Went Wrong");
    //             }
    //         }
    //     }
    //     echo json_encode($result);
    // }


    public function get_replies_community_reviews(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $Validation = Validator::make($request->all(), [
            'review_id' => 'required',
            'type' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $data = Replies::with('user:id,name,image')
                ->with('business:id,business_name,image')
                ->with('business2:id,business_name,image')
                ->with('business3:id,business_name,image')
                ->with('business4:id,business_name,image')
                ->with('business5:id,business_name,image')
                ->where('review_id', $request->review_id)
                ->where('type', $request->type)->get();
                
                
            //  dd($data);  //test

            foreach ($data as $k => $d) {
                if ($d->business_id != 0) {
                    $data[$k]['business_name'] = User::where('id', $d->business_id)->pluck('business_name')->first();
                } else {
                    $data[$k]['business_name'] = '';
                }
                if ($d->image) {
                    $data[$k]['image'] = explode(',', rtrim($d->image, ","));
                } else {
                    $data[$k]['image'] = [];
                }
            }
            //     dd($data);
            // foreach($data as $d)
            // {
            //     $timestamp = strtotime($d->created_at);
            //     $new_date_format = date('Y-m-d H:i:s', $timestamp);
            //     $date1 = date_create($new_date_format);
            //   //  $date1 = date_create($d->created_at);
            //     $date2 = date_create(date("Y-m-d H:m:i"));
            //     $diff = date_diff($date1,$date2);
            //     $d->dif =$diff;
            //     $diffInSeconds = $diff->s; //45
            //     $diffInMinutes = $diff->i;
            //     $d->hour = $diff->h;
            //     $d->Seconds = $diffInSeconds;
            //     $d->Minutes = $diffInMinutes;
            // }

            $tree = function ($replies_reviews, $reply_id = 0) use (&$tree) {
                $branch = array();
                foreach ($replies_reviews as $element) {

                    if ($element['reply_id'] == $reply_id) {

                        $children = $tree($replies_reviews, $element['id']);
                        if ($children) {
                            $element['children'] = $children;
                        } else {
                            $element['children'] = [];
                        }
                        $branch[] = $element;
                    }
                }
                return $branch;
            };

            $tree = $tree($data);
            
            $result  = array("status" => true, "message" => '', "data" => $tree);
        }

        echo json_encode($result);
    }


    public function get_replies_community_reviews_OLD(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'review_id' => 'required',
            'type' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $data = Replies::with('user:id,name,image')
                ->with('business:id,business_name,image')
                ->with('business2:id,business_name,image')
                ->with('business3:id,business_name,image')
                ->with('business4:id,business_name,image')
                ->with('business5:id,business_name,image')
                ->where('review_id', $request->review_id)
                ->where('type', $request->type)->get();
            //  dd($data);

            foreach ($data as $k => $d) {
                if ($d->business_id != 0) {
                    $data[$k]['business_name'] = User::where('id', $d->business_id)->pluck('business_name')->first();
                } else {
                    $data[$k]['business_name'] = '';
                }
                if ($d->image) {
                    $data[$k]['image'] = explode(',', rtrim($d->image, ","));
                } else {
                    $data[$k]['image'] = [];
                }
            }
            //    dd($data);
            // foreach($data as $d)
            // {
            //     $timestamp = strtotime($d->created_at);
            //     $new_date_format = date('Y-m-d H:i:s', $timestamp);
            //     $date1 = date_create($new_date_format);
            //   //  $date1 = date_create($d->created_at);
            //     $date2 = date_create(date("Y-m-d H:m:i"));
            //     $diff = date_diff($date1,$date2);
            //     $d->dif =$diff;
            //     $diffInSeconds = $diff->s; //45
            //     $diffInMinutes = $diff->i;
            //     $d->hour = $diff->h;
            //     $d->Seconds = $diffInSeconds;
            //     $d->Minutes = $diffInMinutes;
            // }


            $tree = function ($replies_reviews, $reply_id = 0) use (&$tree) {
                $branch = array();
                foreach ($replies_reviews as $element) {

                    if ($element['reply_id'] == $reply_id) {

                        $children = $tree($replies_reviews, $element['id']);
                        if ($children) {
                            $element['children'] = $children;
                        } else {
                            $element['children'] = [];
                        }
                        $branch[] = $element;
                    }
                }
                return $branch;
            };

            $tree = $tree($data);

            $result  = array("status" => true, "message" => '', "data" => $tree);
        }

        echo json_encode($result);
    }


    public function get_replies_community_reviews1(Request $request)
    {

        $Validation = Validator::make($request->all(), [
            'review_id' => 'required',
            'type' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $data = Replies::with('user:id,name,image')->with('business:id,name,image')->with('business2:id,name,image')->with('business3:id,name,image')->with('business4:id,name,image')->with('bu5siness:id,name,image')->where('review_id', $request->review_id)->where('type', $request->type)->get();
            foreach ($data as $d) {
                $date1 = date_create($d->created_at);
                $date2 = date_create(date("Y-m-d H:m:i"));
                $diff = date_diff($date1, $date2);
                $d->hour = $diff->h;
            }

            $tree = function ($replies_reviews, $reply_id = 0) use (&$tree) {
                $branch = array();
                foreach ($replies_reviews as $element) {

                    if ($element['reply_id'] == $reply_id) {

                        $children = $tree($replies_reviews, $element['id']);
                        if ($children) {
                            $element['children'] = $children;
                        } else {
                            $element['children'] = [];
                        }
                        $branch[] = $element;
                    }
                }

                return $branch;
            };

            $tree = $tree($data);

            $result  = array("status" => true, "message" => '', "data" => $tree);
        }

        echo json_encode($result);
    }
    public function userCheckInList(Request $request)
    {

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',

        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $data = BusinessReviews::with('user:id,name,image')->where('check_status', 1)->where('type', 'CHECK_IN')->get();



            $result  = array("status" => true, "message" => '', "data" => $data);
        }

        echo json_encode($result);
    }

    public function getbusinessFav(Request $request)
    {

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',

        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $user_id = $request->user_id;
            $user_data = User::where('id', $user_id)->first();
            $latitude = $user_data->lat;
            $longitude = $user_data->long;

            $business_details = DB::select("select *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where role=99 order by distance desc ");

            $review_count = BusinessReviews::where('user_id', $user_id)->count();
            $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();

            foreach ($business_details as $b) {
                $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id, 'fav' => 1])->count();
                if ($BusinessFav > 0) {
                    $b->fav = 1;
                    $category_data = categorys::where('id', $b->business_category)->first();
                    $b->category_name = isset($category_data->name) ? $category_data->name : '';
                    $b->user_count = isset($users) ? count($users) : 0;
                    $b->review_count = isset($review_count) ? $review_count : 0;
                    $b->distance = number_format($b->distance, 1);
                } else {
                    $b->fav = 0;
                }
            }
            $filter_data = array();
            $v = 0;
            foreach ($business_details as $k => $f) {
                if ($f->fav == 1) {
                    $filter_data[] = $f;
                }
                $v++;
            }
            // print_r($filter_data);
            // exit;
            if (isset($filter_data)) {
                $result = array('status' => true, 'message' => 'Data', 'data' => $filter_data);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
        }
        echo json_encode($result);
    }

    public function addBuinessReports(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'business_id' => 'required',
            'review_id' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to Report');
            } else {
                $data = BuinessReports::create(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'review_id' => $request->review_id));
                if ($data) {
                   $getbusiness_reviews =  DB::table("business_reviews")->where("id",$request->review_id)->first();
                   // dd($getbusiness_reviews);
                    //notification
                 //   $getDetauls =DB::table("business_reports")->where("user_id",$request->user_id)->first();
                    if($getbusiness_reviews->user_id != $request->user_id){
                        $Details =   DB::table("users")->select("device_token")->where("id",$getbusiness_reviews->user_id)->first();
                        $getuserDetails =   DB::table("users")->select("name")->where("id",$request->user_id)->first();
                        $title="Report a Review";
                        //$body=" A user flagged ";
                        $body= ucfirst($getuserDetails->name)." reported on your review";
                        $deviceToken = $Details->device_token;

                         $this->sendNotification($title, $body, $deviceToken,$request->review_id,"review","",0,$request->user_id);


                    }
                    //notification

                    $result  = array("status" => true, "message" => 'Report Added Successfully');
                } else {
                    $result  = array("status" => False, "message" => 'Report Added Failed');
                }
            }
        }

        echo json_encode($result);
    }

    public function Businesslikedislike2(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'business_id' => 'required',
            'businessreview_id' => 'required',
            'likedislike' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            // dd($request->business_id);
            $data = Businessreviewlikedislike::create(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id, 'likedislike' => $request->likedislike));
            if ($data) {

             

                $result  = array("status" => true, "message" => 'Like dislike Added Successfully');
            } else {
                $result  = array("status" => False, "message" => 'Like dislike  Added Failed');
            }
        }
        echo json_encode($result);
    }
    public function Businesslikedislike(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'business_id' => 'required',
            'businessreview_id' => 'required',
            'likedislike' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to like');
            } else {
                $checkResult = Businessreviewlikedislike::where(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id))->count();
                if ($checkResult > 0) {
                    $data =  Businessreviewlikedislike::where(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id))->update(array('likedislike' => $request->likedislike));
                } else {
                    $data = Businessreviewlikedislike::create(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id, 'likedislike' => $request->likedislike));
                }

                if ($data) {

                    $businessreview = DB::table("business_reviews")->where("id",$request->businessreview_id)->first();
                    if($businessreview->user_id !=$request->user_id)
                    {
                        $Details =   DB::table("users")->select("device_token","name")->where("id",$businessreview->user_id)->first();
                        $userDetails =   DB::table("users")->select("device_token","name")->where("id",$request->user_id)->first();
                       // dd($Details);
                        $deviceToken = $Details->device_token;
                        $title="Review";
                        $notificationData = array();
                        $businessreview_id = $request->businessreview_id;
                         $type="review";   
                        if($request->likedislike==1)
                        {
                            $type= "review";
                            $body = $userDetails->name." like your review";
                        }
                        else
                        {
                            $type= "review";
                            $body = $userDetails->name." dislike your review";
                        }
                        $this->sendNotification($title, $body, $deviceToken,$businessreview_id,$type,"",0,$request->user_id);
                    }
                    $result  = array("status" => true, "message" => 'Like dislike Added Successfully');
                } else {
                    $result  = array("status" => False, "message" => 'Like dislike  Added Failed');
                }
            }
        }
        echo json_encode($result);
    }

    public function BusinessSearch2(Request $request)
    {
        $user_id = $request->input('id');
        $key = $request->key;
        $user_data = User::where('id', $user_id)->first();
        if ($user_data) {
            $latitude = $user_data->lat;
            $longitude = $user_data->long;





            $business_details = DB::select("select *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where role=99  and  p.business_name like '%" . $key . "%' order by distance desc ");

            //  $distance =number_format($data1[0]->distance,2); 
            // $business_details[0]->distance=$distance;
            $review_count = BusinessReviews::where('user_id', $user_id)->count();
            $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();

            foreach ($business_details as $b) {
                $category_data = categorys::where('id', $b->business_category)->first();
                $b->category_name = isset($category_data->name) ? $category_data->name : '';
                $b->user_count = isset($users) ? count($users) : 0;
                $b->review_count = isset($review_count) ? $review_count : 0;
                $b->distance = number_format($b->distance, 1);

                $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
                if ($BusinessFav > 0) {
                    $b->fav = 1;
                } else {
                    $b->fav = 0;
                }
            }

            if (isset($business_details)) {
                $result = array('status' => true, 'message' => 'Data', 'data' => $business_details);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
        }

        echo json_encode($result);
    }
    public function searchBybusinessNameCategoryNameSubCategoryName2(Request $request)
    {
        if ($request->all()) {
            $name = $request->input('business_name');
            //   $category_name = $request->input('category_name');
            // $sub_category_name = $request->input('sub_category_name');

            if (!empty($name)) {
                $user_data = User::where(['business_name' => $name, 'role' => 99])
                    ->orWhere('business_name', 'like', '%' . $name . '%')
                    ->whereNotNull('business_name')
                    ->get();
            } else if (!empty($category_name)) {

                $user_data = Categorys::join('users', "users.business_category", "=", "categorys.id")
                    ->where(['categorys.name' => $category_name])
                    ->orWhere('categorys.name', 'like', '%' . $category_name . '%')
                    ->select([
                        "users.*",
                        "categorys.name as category_name",
                    ])
                    ->get();
            } else if (!empty($sub_category_name)) {

                $user_data = SubCategorys::join('users', "users.business_sub_category", "=", "sub_categorys.id")
                    ->join('categorys', "sub_categorys.category_id", "=", "categorys.id")
                    ->where('users.role', 99)
                    //->where('sub_categorys.name', $sub_category_name)
                    ->where('sub_categorys.name', 'like', '%' . $sub_category_name . '%')
                    ->select(
                        "users.*",
                        "categorys.name as category_name",
                        "sub_categorys.name as sub_category_name",
                    )
                    ->get();
            }
            // dd($user_data);
            if (!empty($user_data)) {

                foreach ($user_data as $u) {
                    if ((!empty($u->lat)) && (!empty($u->long)) && (!empty($u->business_name))) {
                        $latitude = $u->lat;
                        $longitude = $u->long;
                        $user_id = $u->id;
                        $business_details = DB::select("select *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where role=99 order by distance asc ");

                        $review_count = BusinessReviews::where('user_id', $user_id)->count();
                        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();

                        foreach ($business_details as $b) {
                            $category_data = categorys::where('id', $b->business_category)->first();
                            $b->category_name = isset($category_data->name) ? $category_data->name : '';
                            $b->user_count = isset($users) ? count($users) : 0;
                            $b->review_count = isset($review_count) ? $review_count : 0;
                            $b->distance = number_format($b->distance, 1);

                            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
                            if ($BusinessFav > 0) {
                                $b->fav = 1;
                            } else {
                                $b->fav = 0;
                            }
                        }
                    }
                    if (isset($business_details)) {
                        $result = array('status' => true, 'message' => 'Data', 'data' => $business_details);
                    } else {
                        $result = array('status' => false, 'message' => 'No record found', 'data' => '');
                    }
                }
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
            echo json_encode($result);
        }
    }

    public function searchBybusinessNameCategoryNameSubCategoryName(Request $request)
    {
        if ($request->all()) {
            $name = $request->input('business_name');

            // $category_name = $request->input('category_name');
            // $sub_category_name = $request->input('sub_category_name');

            if (!empty($name)) {
                $user_data = User::where(['business_name' => $name, 'role' => 99])
                    ->orWhere('business_name', 'like', '%' . $name . '%')
                    ->whereNotNull('business_name')
                    ->get();

                //   dd($user_data);
                if (count($user_data) == 0) {
                    $user_data1 = Categorys::join('users', "users.business_category", "=", "categorys.id")
                        ->where(['categorys.name' => $name])
                        ->orWhere('categorys.name', 'like', '%' . $name . '%')
                        ->select([
                            "users.*",
                            "categorys.name as category_name",
                        ])
                        ->get();
                    $user_data = $user_data1;

                    if (count($user_data1) == 0) {

                        $user_data2 = SubCategorys::join('users', "users.business_sub_category", "=", "sub_categorys.id")
                            ->join('categorys', "sub_categorys.category_id", "=", "categorys.id")
                            ->where('users.role', 99)
                            //->where('sub_categorys.name', $sub_category_name)
                            ->where('sub_categorys.name', 'like', '%' . $name . '%')
                            ->select(
                                "users.*",
                                "categorys.name as category_name",
                                "sub_categorys.name as sub_category_name",
                            )
                            ->get();
                        $user_data = $user_data2;
                    }
                }
            }

            if (count($user_data) > 0) {
                //dd($user_data);
                // foreach ($user_data as $u) {
                //     if ((!empty($u->lat)) && (!empty($u->long)) && (!empty($u->business_name))) {
                //         $latitude = $u->lat;
                //         $longitude = $u->long;
                //         $user_id = $u->id;
                //         $business_details = DB::select("select *, (((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as distance FROM users p where role=99 order by distance asc ");

                //         $review_count = BusinessReviews::where('user_id', $user_id)->count();
                //         $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();

                //         foreach ($business_details as $b) {
                //             $category_data = categorys::where('id', $b->business_category)->first();
                //             $b->category_name = isset($category_data->name) ? $category_data->name : '';
                //             $b->user_count = isset($users) ? count($users) : 0;
                //             $b->review_count = isset($review_count) ? $review_count : 0;
                //             $b->distance = number_format($b->distance, 1);
                //             $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
                //             if ($BusinessFav > 0) {
                //                 $b->fav = 1;
                //             } else {
                //                 $b->fav = 0;
                //             }
                //         }
                //     }
                //     if (!empty($business_details)) {
                //         $result = array('status' => true, 'message' => 'Data', 'data' => $business_details);
                //     } else {
                //         $result = array('status' => false, 'message' => 'No record found', 'data' => '');
                //     }
                //     echo json_encode($result);
                // }
                if (!empty($user_data)) {

                    $result = array('status' => true, 'message' => 'Data', 'data' => $user_data);
                } else {
                    $result = array('status' => false, 'message' => 'No record found', 'data' => '');
                }
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
            echo json_encode($result);
        }
    }
    public function BusinessSearch33(Request $request) // old code 08-o1-22
    {
        if ($request->input()) {
            $user_id = $request->input('id');
            $key = $request->key;
            \Log::debug("search key  " . $key);
            $hotspots = Hotspots::join('users', 'users.id', '=', 'hotspots.user_id')
                ->join('users as business_users', 'business_users.id', '=', 'hotspots.business_id')
                ->where('business_users.business_name', 'like', '%' . $key . '%')
                ->select(
                    'users.id',
                    'users.name as person_name',
                    'users.image as user_image',
                    'business_users.business_name as business_user_name',
                    'hotspots.*'
                )
                ->orderBy('business_users.created_at', 'desc')
                ->get();

            //    \Log::debug($hotspots);
            if (count($hotspots) > 0) {
                $result = array("status" => true, "message" => "data", "data" => $hotspots);
            } else {
                $result = array("status" => false, "message" => "no Reocrd found");
            }
        } else {
            $result = array("status" => false, "message" => "no Reocrd found");
        }

        echo json_encode($result);
    }

    public function BusinessSearch(Request $request) //9-o1-22 code
    {
        $date = date("Y-m-d");

        if ($request->input()) {
            $user_id = $request->input('id');
            $key = $request->key;
            // \Log::debug("search key  " . $key);

            $sql = "SELECT hotspots.*,
                    users.id,
                    users.name as person_name,
                    users.image as user_image,
                    business_users.business_name as business_user_name
                    from hotspots 
                    inner join users as business_users on business_users.id = hotspots.business_id
                    inner join users on users.id = hotspots.user_id 
                    where hotspots.message
                    like '%" . $key . "%' and hotspots.created_at
                    like '%" . $date . "%' order by business_users.created_at desc";

            // $hotspots = Hotspots::join('users', 'users.id', '=', 'hotspots.user_id')
            //     ->join('users as business_users', 'business_users.id', '=', 'hotspots.business_id')                
            //     ->where('business_users.business_name', 'like', '%'.$key.'%')
            //     ->whereDate('hotspots.created_at',$date)
            //     ->select(
            //         'users.id',
            //         'users.name as person_name',
            //         'users.image as user_image',
            //         'business_users.business_name as business_user_name',
            //         'hotspots.*'
            //     )
            //     ->orderBy('business_users.created_at', 'desc')
            //     ->get();

            //    \Log::debug($hotspots);

            $rs = DB::select($sql);

            if (isset($rs)) {
                $result = array("status" => true, "message" => "data", 'total' => count($rs), "data" => $rs);
            } else {
                $result = array("status" => false, "message" => "no Reocrd found", "data" => '');
            }
            // if (count($hotspots)>0) {
            //     $result = array("status" => true, "message" => "data", "data" => $hotspots);
            // } else {
            //     $result = array("status" => false, "message" => "no Reocrd found");
            // }
        } else {
            $result = array("status" => false, "message" => "no Reocrd found");
        }

        echo json_encode($result);
    }
    
    public function BusinessSearchtext(Request $request) //9-o1-22 code
    {
        $date = date("Y-m-d");
        if($request->all())
        {
            $key = $request->key;
            
       echo $sql = "select users.name as person_name, users.image as user_image, business_users.business_name as 
                business_user_name, hotspots.* from hotspots inner join users on users.id = hotspots.user_id inner
                 join users as business_users on business_users.id = hotspots.business_id where hotspots.created_at
                  like '%" . $date . "%' and business_user_name like '%" . $key . "%' order by hotspots.id desc";
        exit;
        $rs = DB::select($sql);
        $rs = Hotspots::with('user:id,name,image')
            ->with('business:id,business_name,image')
            ->with('business2:id,business_name,image')
            ->with('business3:id,business_name,image')
            ->with('business4:id,business_name,image')
            ->with('business5:id,business_name,image')
            ->where('created_at', 'like', '%' . $date . '%')
            ->orderBy('id', 'desc')
            ->get();
        if (isset($rs) && !empty($rs)) {
            $image_array = array();
            foreach ($rs as $r) {

                $arrimage = explode(",", rtrim($r->image, ","));
                $r->image = $arrimage;
            }
        }
        // dd($rs);
        // exit;
        if (isset($rs)) {
            $result = array("status" => true, "message" => "data ", "data" => $rs);
        } else {
            $result = array("status" => false, "message" => "no Reocrd found", "data" => '');
        }
        echo json_encode($result);
        }
    }
    
    public function getreviewbyuserid(Request $request)
    {
        $date= date("Y-m-d");

        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $user_id = $request->user_id;

            $usreData = BusinessReviews::join('users', 'users.id', "=", "business_reviews.business_id")
                //->('business_reviews.id', $user_id)
                ->select(
                    'users.id',
                    'business_reviews.user_id as user_id',
                    'business_reviews.business_id as business_id',
                    'business_reviews.id as business_reviews_id',
                     'business_reviews.user_id as user_id',
                    'business_reviews.created_at',
                    'business_reviews.ratting',
                    'business_reviews.status',
                    "users.business_name",
                    "users.business_images",
                    "business_reviews.review",
                    "business_reviews.image_video_status",
                    "business_reviews.tag",
                    'business_reviews.type',
                    'business_reviews.image as business_review_image',
                )
                ->where('business_reviews.user_id', $user_id)
                ->where('users.business_name', '!=', NULL)
                ->where('users.image', '!=', NULL)

                // ->where('business_reviews.type', 'REVIEW')
                ->latest()
                ->get();
                   
            foreach ($usreData as $review) {
                $business_reviews_id = $review->business_reviews_id;
                $business_reviewsDetails =     DB::table("business_reviews")
                                                ->join("users","business_reviews.user_id","=","users.id")
                                                ->where("business_reviews.id",$business_reviews_id)->first();
                
                $review->review_user_name =isset($business_reviewsDetails->name) ? $business_reviewsDetails->name: "";
                $replies_count = Replies::where(['review_id' => $review->business_reviews_id])->count();
                $review->replies_count = $replies_count;
                if ($review->business_review_image) {
                    $img = trim(rtrim($review->business_review_image, ","));
                    $images = rtrim($img, ',');
                    if ($images) {
                        $t = array();
                        $all = explode(",", $images);
                        foreach ($all as $key => $value) {
                            array_push($t, trim($value));
                        }
                        $review->business_review_image = $t;
                        $t = array();
                    } else {
                        $review->business_review_image = [];
                    }
                } else {
                    $review->business_review_image = [];
                }
            }
      $getHotspot =  db::select("SELECT * FROM `replies` WHERE `type` LIKE '%HOTSPOT%' and user_id = ".$user_id." and created_at like '%".$date."%' order by created_at desc ");
         
            if ($usreData) {
                if(!empty($getHotspot))
                {
                    foreach($getHotspot as $h)
                    {
                        $review__reviewsDetails = DB::table("hotspots")
                        ->select("users.name as username","hotspots.id","users.image as userImage")
                        ->join("users","hotspots.user_id","=","users.id")
                        ->where("hotspots.id",$h->review_id)->first();
                       // dd($review__reviewsDetails);
                        $h->review_user_name = isset($review__reviewsDetails->username) ? $review__reviewsDetails->username : '';
                        $h->userImage = isset($review__reviewsDetails->userImage) ? $review__reviewsDetails->userImage : '';
                    }
                }
                $result  = array("status" => true, "message" => '', 'data' => $usreData,'Hotspot'=>isset($getHotspot) ? $getHotspot : '');
            } else {
                $result  = array("status" => False, "message" => ' Failed', 'data' => '');
            }
        }
        echo json_encode($result);
    }

    public function editReview(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'reviews_id' => 'required',

        ]);
        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
              $image_video_status=$request->image_video_status;
            $review = BusinessReviews::where('id', $request->reviews_id)->first();
            if ($review) {
                $fileimage = "";
                $image_url = '';
                if ($request->hasfile('image')) {

                    $files = $request->file('image');
                  
                    $c = 0;
                    foreach ($files as $file) {
                        $fileimage = md5(date("Y-m-d h:i:s", time())) . $c . "." . $file->getClientOriginalExtension();
                        $destination = public_path("images");
                        $file->move($destination, $fileimage);
                        $image_url .= url('public/images') . '/' . $fileimage . ",";
                        $c++;
                        \Log::debug($image_url);
                    }
                   
                } else {
                    if($image_video_status==2)
                    {
                      $image_url = $review->image;
                    }
//                     else
//                     {
// //                    $image_url = null;
//                     $image_video_status=0;        
//                     }
                
                }
                $updateData = array(
                    'image_video_status' => $image_video_status,
                    'ratting' => isset($request->ratting) ? $request->ratting : $review->ratting,
                    'review' => isset($request->review) ? $request->review  : $review->review,
                    'tag' => isset($request->tag) ? $request->tag : $review->review,
                    'image' => $image_url,
                );
                BusinessReviews::where('id', $request->reviews_id)->update($updateData);
                $result  = array("status" => true, "message" => 'Review Updated Successfully');
            } else {
                $result  = array("status" => False, "message" => 'Review Updated Failed');
            }
        }
        echo json_encode($result);
    }

    public function deleteImage(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'image' => 'required',
            'review_id' => 'required',

        ]);
        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {

            $images = explode(',', $request->image);

            $review = BusinessReviews::where('id', $request->review_id)->first();
            if ($review) {
                $img = $review->image;
                foreach ($images as  $key => $image) {
                    $imag = $image . ",";
                    $img = str_replace($imag, " ", $img);
                }

                BusinessReviews::where('id', $request->review_id)->update(['image' => $img]);
                $u = BusinessReviews::where('id', $request->review_id)->first();
                if ($u) {

                    $k = trim($u->image);

                    if ($k) {
                    } else {
                        BusinessReviews::where('id', $request->review_id)->update(['image_video_status' => 0]);
                    }
                }
                $result  = array("status" => true, "message" => 'Review Updated Successfully');
            } else {
                $result  = array("status" => False, "message" => 'Review Updated Failed');
            }
        }
        echo json_encode($result);
    }

    public function deletereview(Request $request)
    {
        if(!empty($request->business_id))
        {
            $Validation = Validator::make($request->all(), [
                'user_id' => 'required',
                'business_id' => 'required',
                'reviews_id' => 'required',
            ]);
    
        }
        else
        {
            $Validation = Validator::make($request->all(), [
                'user_id' => 'required',
                'reviews_id' => 'required',
            ]);
    
        }
        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            if ($request->user_id == 72) {
                $result = array('status' => false, "message" => 'Please login or signup first to Delete');
            } else {
                $user_id = $request->user_id;
                $business_id = $request->business_id;
                $reviews_id = $request->reviews_id;

                $BusinessReviews = BusinessReviews::where('id', $reviews_id)->delete();

                //array('reviews_id'=>$reviews_id,'user_id'=>$user_id)
                //array('businessreview_id'=>$reviews_id,'user_id'=>$user_id,'business_id'=>$business_id)
                if(!empty($request->business_id))
                {
                    $Replies = Replies::where('review_id', $reviews_id)->delete();
              
                 $Businessreviewlikedislike =    Businessreviewlikedislike::where('businessreview_id', $reviews_id)->delete();
                 $BuinessReports = BuinessReports::where('review_id', $reviews_id)->where('user_id', $user_id)->where("business_id", $business_id)->delete();
                }
                else
                {  $Replies = Replies::where('id', $reviews_id)->delete();
                     $Replies = Replies::where('reply_id', $reviews_id)->delete();
                }
                //array('businessreview_id'=>$reviews_id,'user_id'=>$user_id,'business_id'=>$business_id)

                $result  = array("status" => true, "message" => 'Review Deleted Successfully');
            }
        }
        echo json_encode($result);
    }
    public function getbusinessDetailsbyId(Request $request)
    {
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();
        $loginUser = $request->user_id;
        $getUser = User::where('id', $loginUser)->first();
        $latitude = $getUser->lat;
        $longitude = $getUser->long;
        if (!empty($latitude) &&  !empty($longitude)) {
            $sql = "select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users as p where role=99 and id=" . $user_id;

            $business_details = DB::select($sql);

            $review_count = BusinessReviews::where('user_id', $user_id)->count();
            $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
            $totalreview = 0;
            //  dd($business_details);
            foreach ($business_details as $b) {
                $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
                $totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();

                $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
                $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();

                $b->totalReviewusers = $totalreviews;

                $category_data = categorys::where('id', $b->business_category)->first();
                $b->category_name = isset($category_data->name) ? $category_data->name : '';
                // $b->user_count = isset($users) ? count($users) : 0;
                //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
                $b->review_count = isset($review_count) ? $review_count : 0;
                $b->distance = number_format($b->distance, 1);

                $BusinessFav = BusinessFav::where(['user_id' => $loginUser, 'business_id' => $user_id])->count();
                //    dd($BusinessFav);
                if ($BusinessFav > 0) {
                    $b->fav = 1;
                } else {
                    $b->fav = 0;
                }
                $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
                $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
                $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();

                $checkInstatus = CheckInOut::where('business_id', $user_id)->where('user_id', $loginUser)->where('type', "CHECK_IN")->count();
                if ($checkInstatus > 0) {
                    $b->checkIn_status = 1; //check in 
                } else {
                    $b->checkIn_status = 0; // not check in 
                }
            }

            if (count($business_details) > 0) {
                $result = array('status' => true, 'message' => 'Data', 'data' => $business_details[0]);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
        } else {

            $review_count = BusinessReviews::where('user_id', $user_id)->count();
            $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
            $totalreview = 0;
            $business_details = $user_data;
            // foreach ($business_details as $k=> $b) 
            // {
            $business_details->avgratting = number_format(BusinessReviews::where('business_id', $business_details->id)->avg('ratting'), 1);

            $totalreviews = BusinessReviews::where('business_id', $business_details->id)->groupBy('user_id')->count();

            $checkin_count = BusinessReviews::where('business_id', $business_details->id)->where('type', "CHECK_IN")->count();
            $business_details->user_count = isset($checkin_count) ? $checkin_count : 0;
            $totalreview = $totalreview + $totalreviews;
            $business_details->totalReviewusers = $totalreview;

            $category_data = categorys::where('id', $business_details->business_category)->first();
            $business_details->category_name = isset($category_data->name) ? $category_data->name : '';
            // $b->user_count = isset($users) ? count($users) : 0;
            //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
            $business_details->review_count = isset($review_count) ? $review_count : 0;
            $business_details->distance = "0.0"; //number_format($b->distance, 1);
            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $business_details->id])->count();

            if ($BusinessFav > 0) {
                $business_details->fav = 1;
            } else {
                $business_details->fav = 0;
            }
            //}
            if (isset($business_details)) {
                $result = array('status' => true, 'message' => 'Data', 'data' => $business_details);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
        }
        echo json_encode($result);
    }

    public function getAllbusiness()
    {

        //   $data = User::where('role',99)
        $data = User::where(array('role' => 99))->whereNotNull('business_name')
            ->whereNotNull('image')
            ->orderBy('users.id', 'desc')
            ->select(
                "users.id as business_id",
                "users.business_name",
                "users.lat",
                "users.long"
            )
            ->get();
        foreach ($data as $b) {
            $b->firecount = BusinessReviews::where('business_id', $b->business_id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
            $b->okcount = BusinessReviews::where('business_id', $b->business_id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
            $b->notcool_count = BusinessReviews::where('business_id', $b->business_id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
        }
        if ($data) {
            $result  = array("status" => true, "message" => '', "data" => $data);
        } else {
            $result  = array("status" => False, "message" => '', "data" => '');
        }
        echo json_encode($result);
    }

    public function getfilterbusiness()
    {
        $category_data = Categorys::with('subcategory')->where('status', 0)->get();
        if (!empty($category_data)) {
            $result  = array("status" => true, "message" => '', "data" => $category_data);
        } else {
            $result  = array("status" => false, "message" => '', "data" => '');
        }
        echo json_encode($result);
    }
    public function getfaq()
    {
        $faq = Faqs::where('status', 0)->orderby("id","desc")->get();

        if (!empty($faq)) {
            $result  = array("status" => true, "message" => '', "data" => $faq);
        } else {
            $result  = array("status" => false, "message" => '', "data" => '');
        }
        echo json_encode($result);
    }

    public function BusinessVisits(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'business_id' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $user_id = $request->user_id;
            $business_id = $request->business_id;

            $BusinessVisits = BusinessVisits::where('user_id', $user_id)->where('business_id', $business_id)->first();
            if (!empty($BusinessVisits)) {
                $updateData = BusinessVisits::where('id', $BusinessVisits->id)->update(array('visit_count' => $BusinessVisits->visit_count + 1));
                if ($updateData) {
                    $result  = array("status" => true, "message" => 'visit updated successfully');
                } else {
                    $result  = array("status" => False, "message" => 'fails');
                }
            } else {
                $insertData = BusinessVisits::create(array('user_id' => $request->user_id, 'business_id' => $request->business_id, 'visit_count' => 1));
                if ($insertData) {
                    $result  = array("status" => true, "message" => 'visit add successfully');
                } else {
                    $result  = array("status" => False, "message" => 'fails');
                }
            }
        }
        echo json_encode($result);
    }

    public function contactus(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'name' => 'required',
            'email' => 'required',
            'country_code' => 'required',
            'phone' => 'required',
            'comment' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {

            $insertData = Contactus::create(array('user_id' => $request->user_id, 'name' => $request->name, 'email' => $request->email, 'country_code' => $request->country_code, 'phone' => $request->phone, 'comment' => $request->comment));
            if ($insertData) {
                $result  = array("status" => true, "message" => 'contact us added successfully');
            } else {
                $result  = array("status" => False, "message" => 'fails');
            }
        }
        echo json_encode($result);
    }

    public function getabout()
    {
        $data = Aboutus::all();
        if ($data) {
            $result  = array("status" => true, "message" => '', 'data' => $data[0]['description']);
        } else {
            $result  = array("status" => False, "message" => 'fails');
        }
        echo json_encode($result);
    }
    public function changepassword1(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'old_password' => 'required',
            'password' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else if ($request->old_password == $request->password) {
            $result  = array("status" => false, "message" => 'Old passowrd and new password same');
        } else {
            $insertData = User::where('id', $request->user_id)->update(array('password' => Hash::make($request->password)));
            if ($insertData) {
                $result  = array("status" => true, "message" => 'change password successfully');
            } else {
                $result  = array("status" => False, "message" => 'fails');
            }
        }
        echo json_encode($result);
    }
    public function changepassword(Request $request)
    {
        $Validation = Validator::make($request->all(), [
            'user_id' => 'required',
            'old_password' => 'required',
            'password' => 'required',
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } 
        // else if ($request->old_password == $request->password) {
        //     $result  = array("status" => false, "message" => 'Old passowrd and new password same');
        // } 
        else {
          
            $password = $request->old_password;
            
            $get = User::where('id', $request->user_id)->first();
           if(!Hash::check($password, $get->password))
           {
              $result  = array("status" => false, "message" => 'invalid old passowrd');
            }
            else
            {
                 $insertData = User::where('id', $request->user_id)->update(array('password' => Hash::make($request->password)));   
                   if ($insertData) {
                    $result  = array("status" => true, "message" => 'change password successfully');
                } else {
                    $result  = array("status" => false, "message" => 'fails');
                }
               
            }
           
           
        }
        echo json_encode($result);
    }
    //if(Hash::check($request->password, $user->password))

    public function get_businessoftheweek(Request $request)
    {
        if ($request->input()) {
            // $user_id = $request->user_id;
            // $sql = "select payments.*,  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description from payments inner join users on payments.user_id = users.id where payments.plan_id = 1 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id =" . $user_id . " ";
            
            $ddate = date('Y-m-d');
            $week = date("W", strtotime($ddate));  //Get Week
            $year = date("Y", strtotime($ddate));  //Get Year
        
            $result = $this->Start_End_Date_of_a_week($week,$year);
            $first_day = date('m/d/Y', strtotime('-1 day', strtotime($result[0])));  //Start day of week
            $last_day = date('m/d/Y', strtotime('-1 day', strtotime($result[1])));   //End Day of Week

            $sql = "select payments.*, users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description from payments inner join users on payments.user_id = users.id where (payments.plan_id = 1 or payments.plan_id = 3) and (payments.startDate = '" . $result[0] . "' and payments.endDate = '" . $result[1] . "') order by payments.id DESC limit 1";
    
            $business_details = DB::select($sql);
            if (isset($business_details)) {
                // $object = (object) $business_details[0];
                $result = array('status' => true, 'message' => 'Data',  'data' => $business_details);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
            echo json_encode($result);
        }
    }

    public function cronjobcheckout()
    {
        $getcheckin = checkOut::select('id')->where('type', "CHECK_IN")->get();
        //  dd($getcheckin);
        $c = 0;
        foreach ($getcheckin as $ck) {
            $updateArray = array('type' => "CHECK_OUT", 'check_status' => 0);
            $updatedata = CheckInOut::where('id', $ck->id)->update($updateArray);
            $c++;
        }
        if (count($getcheckin) == $c) {
            $result = array('status' => true, 'message' => "check Out Cron Job");
        } else {
            $result = array('status' => false, 'message' => "check Out Cron Job");
        }
        echo json_encode(array('data' => $result));
    }
    
    
    //=========Added New for feature Business //RR START ============================================================================

    function Start_End_Date_of_a_week($week, $year)
    {
        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+1-$day)*24*3600;
        $dates[0] = date('m/d/Y', $time);
        $time += 6*24*3600;
        $dates[1] = date('m/d/Y', $time);
        return $dates;
    }

    public function featuredBusiness__oldd(Request $request)  //New //RR
    {
        $ddate = date('Y-m-d');
        $week = date("W", strtotime($ddate));  //Get Week
        $year = date("Y", strtotime($ddate));  //Get Year
    
        $result = $this->Start_End_Date_of_a_week($week,$year);
        $first_day = date('m/d/Y', strtotime('-1 day', strtotime($result[0])));  //Start day of week
        $last_day = date('m/d/Y', strtotime('-1 day', strtotime($result[1])));   //End Day of Week
    
        $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where (payments.plan_id = 2 or payments.plan_id = 3) and (payments.startDate = '" . $result[0] . "' and payments.endDate = '" . $result[1] . "')  order by payments.id DESC limit 3"; 

        $featuredBusiness = DB::select($sql);

        if (isset($featuredBusiness)) {
            $result = array('status' => true, 'message' => 'Data', 'data' => $featuredBusiness);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }

    //=========Added New for feature Business //RR END ============================================================================

    public function featuredBusiness(Request $request)  //Backup - old //RR
    {

        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();

        $latitude = $user_data->lat;
        $longitude = $user_data->long;

        // ==================== Start
        $ddate = date('Y-m-d');
        $week = date("W", strtotime($ddate));  //Get Week
        $year = date("Y", strtotime($ddate));  //Get Year
    
        $result = $this->Start_End_Date_of_a_week($week,$year);
        $first_day = date('m/d/Y', strtotime('-1 day', strtotime($result[0])));  //Start day of week
        $last_day = date('m/d/Y', strtotime('-1 day', strtotime($result[1])));   //End Day of Week
    
        $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where (payments.plan_id = 2 or payments.plan_id = 3) and (payments.startDate = '" . $result[0] . "' and payments.endDate = '" . $result[1] . "')  order by payments.id DESC limit 3"; 
        // ======================= End

        // $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3 and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
        
        // print_r($sql);
        // die;  //test
        
        $featuredBusiness = DB::select($sql);
        if(!empty($featuredBusiness))
        {
            $c =0;
            foreach($featuredBusiness as  $f)
            {
                $f->user_count = CheckInOut::where('business_id', $f->user_id)->where('check_status', 1)->count();
                $f->avgratting = number_format(BusinessReviews::where('business_id', $f->user_id)->avg('ratting'), 1);
                $c++;
            }
     
     
            $business_details = DB::select("select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  having distance < 5 order by distance asc limit 3");
        
            $review_count = BusinessReviews::where('user_id', $user_id)->count();
        
            $featuredBusiness[0]->review_count = isset($review_count) ? $review_count : 0;
            $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
            $totalreview = 0;
            foreach ($business_details as $b) {
                $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
                $totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();

                $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
                $b->user_count = isset($checkin_count) ? $checkin_count : 0;
                $totalreview = $totalreview + $totalreviews;
                $b->totalReviewusers = $totalreview;

                $category_data = categorys::where('id', $b->business_category)->first();
                $b->category_name = isset($category_data->name) ? $category_data->name : '';
                // $b->user_count = isset($users) ? count($users) : 0;
                //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
                $b->review_count = isset($review_count) ? $review_count : 0;
                $b->distance = number_format($b->distance, 1);

                $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
                if ($BusinessFav > 0) {
                    $b->fav = 1;
                } else {
                    $b->fav = 0;
                }
            }

            if (isset($business_details)) {
                $obj =  $featuredBusiness;

                $result = array('status' => true, 'message' => 'Data', 'data' => $obj);
            } else {
                $result = array('status' => false, 'message' => 'No record found', 'data' => '');
            }
        }
        else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
           
        echo json_encode($result);
    }

    // public function getallBusinesslist(Request $request)
    // {
    //     if($request->input())
    //     {
    //         $user_id = $request->input('id');//use for checkin check out status

    //     //    $user_id = $request->input('id');
    //         $user_data = User::where('id', $user_id)->first();

    //         $latitude = $user_data->lat;
    //         $longitude = $user_data->long;

    //         $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id = " . $user_id . " limit 1";
    //         $featuredBusiness = DB::select($sql);

    //         $business_details = DB::select("select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  having distance < 5 order by distance asc");

    //         $review_count = BusinessReviews::where('user_id', $user_id)->count();
    //         $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();


    //         $totalreview = 0;
    //         foreach ($business_details as $b) 
    //         {
    //             $checkInstatus = BusinessReviews::where('business_id', $b->id)->where('user_id',$user_id)->where('check_status',1)->count();
    //             if($checkInstatus>0)
    //             {
    //                 $b->checkIn_status =1;//check in 
    //             }
    //             else
    //             {
    //                 $b->checkIn_status =0; // not check in 
    //             }

    //             $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
    //             $totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();

    //             $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
    //             $b->user_count = isset($checkin_count) ? $checkin_count : 0;
    //             $totalreview = $totalreview + $totalreviews;
    //             $b->totalReviewusers = $totalreview;

    //             $category_data = categorys::where('id', $b->business_category)->first();
    //             $b->category_name = isset($category_data->name) ? $category_data->name : '';
    //             // $b->user_count = isset($users) ? count($users) : 0;
    //             //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
    //             $b->review_count = isset($review_count) ? $review_count : 0;
    //             $b->distance = number_format($b->distance, 1);

    //             $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
    //             if ($BusinessFav > 0) {
    //                 $b->fav = 1;
    //             } else {
    //                 $b->fav = 0;
    //             }
    //             $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
    //             $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
    //             $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
    //         }

    //             if(isset($data))
    //             {
    //                 $result = array('status'=>true,'message'=>"data",'data'=>$data);
    //             }
    //             else
    //             {
    //                 $result = array('status' => false, 'message' => 'No record found', 'data' => '');
    //             }
    //     } 
    //     else
    //     {
    //         $result = array('status' => false, 'message' => 'No record found', 'data' => '');
    //     }
    //     echo json_encode($result);
    // }

    public function getallBusinesslist(Request $request)
    {
        $user_id = $request->input('id');
        $user_data = User::where('id', $user_id)->first();
        //   $u_id = $request->input('user_id');//use for checkin check out status

        $latitude = $user_data->lat;
        $longitude = $user_data->long;

        $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2  and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "' and payments.user_id = " . $user_id . " limit 1";
        
   $featuredBusiness = DB::select($sql);

        $business_details = DB::select("select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99 and business_name IS NOT NULL and image IS NOT NULL having distance  order by distance asc");
        //   echo json_encode($business_details);
        //  $distance =number_format($data1[0]->distance,2); 
        // $business_details[0]->distance=$distance;
        $review_count = BusinessReviews::where('user_id', $user_id)->count();
        $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
        $totalreview = 0;
        foreach ($business_details as $b) {
            $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
            if ($checkInstatus > 0) {
                $b->checkIn_status = 1; //check in 
            } else {
                $b->checkIn_status = 0; // not check in 
            }
            $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
            $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=','2')->count();
            $b->totalReviewusers = $totalreviews;
            $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
            $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();



            $category_data = categorys::where('id', $b->business_category)->first();
            $b->category_name = isset($category_data->name) ? $category_data->name : '';
            // $b->user_count = isset($users) ? count($users) : 0;
            //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
            $b->review_count = isset($review_count) ? $review_count : 0;
            $b->distance = number_format($b->distance, 1);

            $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
            if ($BusinessFav > 0) {
                $b->fav = 1;
            } else {
                $b->fav = 0;
            }
            $b->firecount = BusinessReviews::where('business_id', $b->id)->where('tag', "fire")->count();
            $b->okcount = BusinessReviews::where('business_id', $b->id)->where('tag', "OkOk")->count();
            $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('tag', "Not Cool")->count();
        }

        if (isset($business_details)) {
            $obj =  $featuredBusiness;

            $result = array('total_count'=>count($business_details), 'status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
        } else {
            $result = array('status' => false, 'message' => 'No record found', 'data' => '');
        }
        echo json_encode($result);
    }

    // public function getallBusinesslist(Request $request)
    // {
    //     if($request->input())
    //     {
    //         $user_id = $request->input('id');
    //         $user_data = User::where('id', $user_id)->first();
    //         $u_id = $request->input('user_id');//use for checkin check out status

    //         $latitude = $user_data->lat;
    //         $longitude = $user_data->long;

    //         $sql = "select  users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments inner join users on payments.user_id = users.id where payments.plan_id = 2 and date(payments.startDate) >= " . date("Y-m-d") . " and date(payments.endDate) <= " . date("Y-m-d") . " and payments.user_id = " . $user_id . " limit 1";
    //         $featuredBusiness = DB::select($sql);

    //         $business_details = DB::select("select *, cast((((acos(sin((" . $latitude . "*pi()/180)) * sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 0.6213711922 as distance FROM users p where role=99  having distance < 5 order by distance asc");
    //         //   echo json_encode($business_details);
    //         //  $distance =number_format($data1[0]->distance,2); 
    //         // $business_details[0]->distance=$distance;
    //         $review_count = BusinessReviews::where('user_id', $user_id)->count();
    //         $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
    //         $totalreview = 0;
    //         foreach ($business_details as $b) {
    //                 $checkInstatus = BusinessReviews::where('business_id', $b->id)->where('user_id',$user_id)->where('check_status',1)->count();
    //             if($checkInstatus>0)
    //             {
    //                 $b->checkIn_status =1;//check in 
    //             }
    //             else
    //             {
    //                 $b->checkIn_status =0; // not check in 
    //             }

    //             $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
    //             $totalreviews = BusinessReviews::where('business_id', $b->id)->groupBy('user_id')->count();

    //             $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
    //             $b->user_count = isset($checkin_count) ? $checkin_count : 0;
    //             $totalreview = $totalreview + $totalreviews;
    //             $b->totalReviewusers = $totalreview;

    //             $category_data = categorys::where('id', $b->business_category)->first();
    //             $b->category_name = isset($category_data->name) ? $category_data->name : '';
    //             // $b->user_count = isset($users) ? count($users) : 0;
    //             //  $b->user_count = isset($checkin_count) ? count($checkin_count) : 0;
    //             $b->review_count = isset($review_count) ? $review_count : 0;
    //             $b->distance = number_format($b->distance, 1);

    //             $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
    //             if ($BusinessFav > 0) {
    //                 $b->fav = 1;
    //             } else {
    //                 $b->fav = 0;
    //             }
    //             $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
    //             $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
    //             $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();

    //         }



    // //     $data = User::where('role',99)->get();

    // //     foreach($data as $b)
    // //     {
    // //         $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
    // //         $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
    // //         $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
    // //         $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();

    // //     }
    // //     if(isset($data))
    // //     {
    // //         $result = array('status'=>true,'message'=>"data",'data'=>$data);
    // //     }
    // //     else
    // //     {
    // //         $result = array('status' => false, 'message' => 'No record found', 'data' => '');
    // //     }
    // // }
    // // else
    // // {
    // //     $result = array('status' => false, 'message' => 'No record found', 'data' => '');

    // // }
    //     echo json_encode($result);
    // }
    
    public function privacypolicy(){
        $privacypolicy = DB::table("privacypolicy")->first();
        if(!empty($privacypolicy)){
            $result  =array("status"=>true,"data"=>$privacypolicy);
        }
        else
        {
            $result  =array("status"=>true,"data"=>"");
        }
        echo json_encode($result);
    }
    public function multipleimage(Request $request)
    {
        $files = $request->file('image');
        //$errors = [];
        //  $fileimage="";
        //  $image_url='';
        //  $c=0;
        // foreach ($files as $file) 
        // {      
        //    $fileimage=md5(date("Y-m-d h:i:s", time())).$c.".".$file->getClientOriginalExtension();
        //    $destination=public_path("images");
        //    $file->move($destination,$fileimage);
        //    $image_url.=url('public/images').'/'.$fileimage.",";
        //     $c++;
        // }  
        // echo $image_url;  
    }
    public function testnotification(Request $request)
    {

   //  dd($Details); 
     $title = $request->title;
        $body = $request->body;
     $Details =   DB::table("users")->select("device_token")->where("id",$request->id)->first();
        $deviceToken = $Details->device_token;
        $this->sendNotification($title, $body, $deviceToken);
    }

    public function sendnotificationendofthemonth(){
        
   
//         $a_date = date('Y-m-d', strtotime('first day of last month'));
// echo date("Y-m-t", strtotime($a_date));
// die;
        $today = \Carbon\Carbon::now(); //Current Date and Time

   //     $lastDayofMonth =    \Carbon\Carbon::parse($today)->endOfMonth()->toDateString();
     //   $lastDayofMonth = date("Y-m-d");
      
     date_default_timezone_set("Asia/Kolkata");   //India time (GMT+5:30)
   
      $t1 = date("H:i A");
//&& ($t1<='05:00 PM')
        if((date("Y-m-d")==date('Y-m-01')) && ($t1=='12:01 AM') )
        {
           
            $mth= date('m')-1;
            if($mth<10)
            {
                $mth="0".$mth;
            }
           $like =$year= date('Y')."-".$mth;
   
    
       $getBusinessDetails =  User::where("role","99")->where("created_at","like",'%'.$like.'%')->get();
    //  dd($getBusinessDetails)
       $getAllUser  = User::select("device_token")->where("role",97)->get();
     
       if(!empty($getAllUser)){
           foreach($getAllUser as $user){
             $device_token = $user->device_token;
               if(!empty($device_token)){
                   $title = "Add New Business";
                   $body = "New Business added on the platform please check.";
             
                   $this->sendNotification($title, $body, $device_token,"","latest","",0,0);
               } 
            }
            }
       }
       else{
        echo json_encode(array("Status"=>false,"message"=>"not send"));
       }
    
       }

       public function sendnotificationendofthemonthtest(){
        
        $today = \Carbon\Carbon::now(); //Current Date and Time

        $lastDayofMonth =    \Carbon\Carbon::parse($today)->endOfMonth()->toDateString();
    


        
     
//       if(!empty($getAllUser)){
  //         foreach($getAllUser as $user){
             $device_token = "cGDI0PC6RaeA5kBbOQOKOZ:APA91bHnSDXj_n_Ylq6Of8r2NL-_9pgEaIcpSiCZNFRyrGG0kKIGkWk7I_Jl2A9tOHIzbvY1t-ZX5Vg7laFAwmDDkGTE7Lnj9vHSgBBJXF1nRd9LIS8vUkbljreYgn2pwSwHQpTkGvmO";
               if(!empty($device_token)){
                   $title = "Add New Business";
                   $body = "New Business added on the platform please check.";
                   $this->sendNotification($title, $body, $device_token,"","businesslist","");
               } 
    //        }
      //      }
       }
      // }

       public function usernotificationstatus(Request $request)
       {

           if($request->all())
           {

                $user_id = $request->user_id;
                $notification_status  = $request->notification_status;
               $updateData= User::where("id",$user_id)->update(array("notification_status"=>$notification_status));
                
               if($updateData)
                {
                    if($request->notification_status==0)
                    {
                        $msg ="Deactive Notification ";
                    }
                    else
                    {
                        $msg ="Active Notification ";
                    }
                    $result = array("status"=>true,"message"=>$msg);
                }
                else
                {
                    $result = array("status"=>false,"message"=>"");
                }
                echo json_encode($result);
           }
       }
       
    public function sendNotification($title, $body, $deviceToken,$businessreview_id,$type,$reply_id,$ck,$current_app_user_id)
    {
        $sendNotification= User::where("device_token",$deviceToken)->where("notification_status",1)->first();
       
        if($current_app_user_id!=0)
        {
            

            $get_current_app_user= User::where("id",$current_app_user_id)->first();
            if($get_current_app_user->id !=$sendNotification->id)
            {
    
                if(isset($sendNotification->notification_status) && $sendNotification->notification_status==1)
                {
    
    
                    $url = 'https://fcm.googleapis.com/fcm/send';
                    //    $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
                    $FcmToken = array($deviceToken);
                    $serverKey = env("FCK");
                    $data = [
                        "registration_ids" => $FcmToken,
                        "notification" => [
                            "title" => $title,
                            "body" => $body,
                            'businessreview_id'=>$businessreview_id,
                            "type"=>$type,
                            "reply_id"=>$reply_id
                        ],
                        "data" => [
                            "title" => $title,
                            "body" => $body,
                            'businessreview_id'=>$businessreview_id,
                            "type"=>$type,
                            "reply_id"=>$reply_id
                        ],
                        "apns"=>[
                            "payload"=>[
                                    "aps"=>[
                                        "title" => $title,
                                        "body" => $body,
                                        'businessreview_id'=>$businessreview_id,
                                        "type"=>$type,
                                        "reply_id"=>$reply_id
                                    ]
                            ] 
                        ],
                    ];
    
                    $encodedData = json_encode($data);
                    $headers = [
                        'Authorization:key=' . $serverKey,
                        'Content-Type: application/json',
                    ];
    
                    $ch = curl_init();
    
                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                    // Disabling SSL Certificate support temporarly
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    
                    // Execute post
                    $result = curl_exec($ch);
    
                    if ($result === FALSE) {
                        die('Curl failed: ' . curl_error($ch));
                    }
    
                    // Close connection
                    curl_close($ch);
                    // FCM response
        
                }
               
            }

        }
        else
        {

           
            if(isset($sendNotification->notification_status) && $sendNotification->notification_status==1)
            {


                $url = 'https://fcm.googleapis.com/fcm/send';
                //    $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
                $FcmToken = array($deviceToken);
                $serverKey = env("FCK");
                $data = [
                    "registration_ids" => $FcmToken,
                    "notification" => [
                        "title" => $title,
                        "body" => $body,
                        'businessreview_id'=>$businessreview_id,
                        "type"=>$type,
                        "reply_id"=>$reply_id
                    ],
                    "data" => [
                        "title" => $title,
                        "body" => $body,
                        'businessreview_id'=>$businessreview_id,
                        "type"=>$type,
                        "reply_id"=>$reply_id
                    ],
                    "apns"=>[
                        "payload"=>[
                                "aps"=>[
                                    "title" => $title,
                                    "body" => $body,
                                    'businessreview_id'=>$businessreview_id,
                                    "type"=>$type,
                                    "reply_id"=>$reply_id
                                ]
                        ] 
                    ],
                ];

                $encodedData = json_encode($data);
                $headers = [
                    'Authorization:key=' . $serverKey,
                    'Content-Type: application/json',
                ];

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
                // Disabling SSL Certificate support temporarly
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);

                // Execute post
                $result = curl_exec($ch);

                if ($result === FALSE) {
                    die('Curl failed: ' . curl_error($ch));
                }

                // Close connection
                curl_close($ch);
                // FCM response
              //  dd($result);
            }
        }
        

        // dd($sendNotification);
       
    }

    
   public function donationhistory($id){
   
       $paymentsHistory = DB::table("payments")->where("plan_id",0)->where("user_id",$id)->get();
        if(!empty($paymentsHistory)){
            $result  =array("status"=>true,"data"=>$paymentsHistory);
        }
        else{
            $result  =array("status"=>false ,"data"=>"");
        }
        echo json_encode($result);
   }

 
   public function getreviewDetailsbyId($id){
       if(!empty($id)){
       $getbusiness_reviews=    DB::table("business_reviews")
           ->join("users","users.id","=","business_reviews.user_id")
           ->join("users as businessuser","businessuser.id","=","business_reviews.business_id")
           ->where("business_reviews.id",$id)
           ->first(["business_reviews.*","users.name","users.image as user_image","businessuser.business_name"]);
           if(!empty($getbusiness_reviews)){
            $getbusiness_reviews->image = explode(",",rtrim($getbusiness_reviews->image,","));
           
               $result = array("status"=>true,"data"=>$getbusiness_reviews);
           }
           else
           {
            $result = array("status"=>false,"data"=>"");
           }
       }
       echo json_encode($result);
   }
   public function gethotspotDetailsbyId($id){
    if(!empty($id)){
    $getbusiness_hotspot= DB::table("hotspots")
        ->join("users","users.id","=","hotspots.user_id")
        ->where("hotspots.id",$id)
        ->first(["hotspots.*","users.name","users.image"]);
        if(!empty($getbusiness_hotspot)){

            if($getbusiness_hotspot->business_id!=0)
            {
                $businessDetails1 = DB::table("users")->where("id",$getbusiness_hotspot->business_id)->first();
                $getbusiness_hotspot->business_name1 = isset($businessDetails1->business_name) ?  $businessDetails1->business_name :'' ;
            }
            else
            {
                $getbusiness_hotspot->business_name1 = "";
            }
            if($getbusiness_hotspot->business_id2!=0)
            {
                $businessDetails2 = DB::table("users")->where("id",$getbusiness_hotspot->business_id2)->first();
                $getbusiness_hotspot->business_name2 =  isset($businessDetails2->business_name) ?  $businessDetails2->business_name :'' ;
            }
            else
            {
                $getbusiness_hotspot->business_name2 = "";
            }
            if($getbusiness_hotspot->business_id3!=0)
            {
                $businessDetails3 = DB::table("users")->where("id",$getbusiness_hotspot->business_id3)->first();
                $getbusiness_hotspot->business_name3 =isset($businessDetails3->business_name) ?  $businessDetails3->business_name :'' ;
            }
            else
            {
                $getbusiness_hotspot->business_name3 = "";
            }
            if($getbusiness_hotspot->business_id4!=0)
            {
                $businessDetails4 = DB::table("users")->where("id",$getbusiness_hotspot->business_id4)->first();
                $getbusiness_hotspot->business_name4 =isset($businessDetails4->business_name) ?  $businessDetails4->business_name :'' ;
            }
            else
            {
                $getbusiness_hotspot->business_name4 = "";
            }
            if($getbusiness_hotspot->business_id5!=0)
            {
                $businessDetails5 = DB::table("users")->where("id",$getbusiness_hotspot->business_id5)->first();
                $getbusiness_hotspot->business_name5 =isset($businessDetails5->business_name) ?  $businessDetails5->business_name :'' ;
            }
            else
            {
                $getbusiness_hotspot->business_name5 = "";
            }
            $result = array("status"=>true,"data"=>$getbusiness_hotspot);
        }
        else
        {
         $result = array("status"=>false,"data"=>"");
        }
    }
    echo json_encode($result);
}


public function getterms_conditions()
{
    $data = DB::table('terms_conditions')->first();
    if ($data) {
        $result  = array("status" => true, "message" => '', 'data' => $data);
    } else {
        $result  = array("status" => False, "message" => 'fails');
    }
    echo json_encode($result);
}



public function getlastmonthData(Request $request){

    $user_id = $request->input('id');
    $user_data = User::where('id', $user_id)->first();
    $u_id = $request->input('user_id'); //use for checkin check out status

    
    $latitude = $user_data->lat;
    $longitude = $user_data->long;
 $mth= date('m')-1;
    if($mth<10)
    {
        $mth="0".$mth;
    }
   $like =$year= date('Y')."-".$mth;

     $sql = "select users.id as user_id, users.opeing_hour,users.closing_hour,users.ratting, users.lat,users.business_category,users.business_images,users.business_name, users.long,users.description, payments.plan_id, payments.plan_name, payments.plan_price, payments.startDate, payments.endDate, payments.user_id, payments.customer_name, payments.billing_email, payments.billing_address, payments.country, payments.city, payments.zip_code, payments.transaction_id, payments.payment_status, payments.seller_message, payments.status, payments.created_at, payments.updated_at from payments left join users on payments.user_id = users.id where payments.plan_id = 2 or payments.plan_id = 3  and date(payments.startDate) >= '" . date("m/d/Y") . "' and date(payments.endDate) <= '" . date("m/d/Y") . "'  limit 3";   
    $featuredBusiness = DB::select($sql);

   
   
    $sqldis = "select *,cast((((acos(sin((" . $latitude . "*pi()/180))*sin((p.lat*pi()/180))+cos((" . $latitude . "*pi()/180)) * cos((p.lat*pi()/180)) * cos(((" . $longitude . "-p.long)*pi()/180))))*180/pi())*60*1.1515*1.609344) as decimal(10,2)) * 1.6013711922 as distance FROM users p where role=99 and created_at like '%".$like."%' and business_name IS NOT NULL and image IS NOT NULL having distance < 15 order by distance asc";
   $business_details = DB::select($sqldis);
  
  
   $review_count = BusinessReviews::where('user_id', $user_id)->count(); //comment 21 - 2 - 2022
        
   $users = BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
   $totalreview = 0;

   foreach ($business_details as $b) {
    //        $review_count = BusinessReviews::where('user_id', $b->id)->count();
     $checkInstatus = CheckInOut::where('business_id', $b->id)->where('user_id', $user_id)->where('check_status', 1)->count();
     if ($checkInstatus > 0) {
         $b->checkIn_status = 1; //check in 
     } else {
         $b->checkIn_status = 0; // not check in 
     }

     $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
     $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
     $b->totalReviewusers = $totalreviews;
     $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
     $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();



     $category_data = categorys::where('id', $b->business_category)->first();
     $b->category_name = isset($category_data->name) ? $category_data->name : '';

     $b->review_count = isset($review_count) ? $review_count : 0;
     $b->distance = number_format($b->distance, 1);

     $BusinessFav = BusinessFav::where(['user_id' => $user_id, 'business_id' => $b->id])->count();
     if ($BusinessFav > 0) {
         $b->fav = 1;
     } else {
         $b->fav = 0;
     }
     $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
     $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
     $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
 }

 if (isset($business_details)) {
    $obj =  $featuredBusiness;

    $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => $obj);
} else {
    $result = array('status' => false, 'message' => 'No record found', 'data' => '');
}
echo json_encode($result);

   
            }

public function getlastmonthData2(){

    $mth= date('m')-1;
    if($mth<10)
    {
        $mth="0".$mth;
    }
   $like =$year= date('Y')."-".$mth;

    // $like =$year= date('Y')."-".$mth= date('m');
       $sqldis = "select * FROM users p where role=99 and created_at like '%".$like."%'; ";
       $business_details = DB::select($sqldis);
       $review_count ="0";
       $users =BusinessReviews::select('user_id')->groupBy('user_id')->get()->toArray();
       $totalreview = 0;
       foreach ($business_details as $b) {
                           $review_count = BusinessReviews::where('user_id', $b->id)->count();
                    $checkInstatus = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();
                    if ($checkInstatus > 0) {
                        $b->checkIn_status = 1; //check in 
                    } else {
                        $b->checkIn_status = 0; // not check in 
                    }
        
                    $b->avgratting = number_format(BusinessReviews::where('business_id', $b->id)->avg('ratting'), 1);
                    $totalreviews = BusinessReviews::where('business_id', $b->id)->where('status','!=', 2)->groupBy('user_id')->count();
                    $b->totalReviewusers = $totalreviews;
                    $checkin_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->count();
                    $b->user_count = CheckInOut::where('business_id', $b->id)->where('check_status', 1)->count();
        
        
        
                    $category_data = categorys::where('id', $b->business_category)->first();
                    $b->category_name = isset($category_data->name) ? $category_data->name : '';
        
                    $b->review_count = isset($review_count) ? $review_count : 0;
                    $b->distance = 0;
        
                    $BusinessFav = BusinessFav::where([ 'business_id' => $b->id])->count();
                    if ($BusinessFav > 0) {
                        $b->fav = 1;
                    } else {
                        $b->fav = 0;
                    }
                    $b->firecount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "fire")->count();
                    $b->okcount = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "OkOk")->count();
                    $b->notcool_count = BusinessReviews::where('business_id', $b->id)->where('type', "CHECK_IN")->where('tag', "Not Cool")->count();
                }
        
                if (isset($business_details)) {
                 
        
                    $result = array('status' => true, 'message' => 'Data', 'data' => $business_details, 'featuredBusiness' => "");
                } else {
                    $result = array('status' => false, 'message' => 'No record found', 'data' => '');
                }
                echo json_encode($result);
            }
}  
// $data = array('gender' =>$request->gender, 'weight' =>$request->weight, 'weight_unit'=>$request->weight_unit,
// 'height'=>$request->height, 'height_unit'=>$request->height_unit, 'interest'=>$request->interest,
// 'bodyparts_work'=>$request->bodyparts_work, 'exercise'=>$request->exercise, 'length_training'=>$request->length_training,

// 'fitness_goal'=>$request->fitness_goal, 'diedt_impact'=>$request->diedt_impact);
// dd($data);