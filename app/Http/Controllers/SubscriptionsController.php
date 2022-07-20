<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscriptions;
use App\Models\payments;
use App\Models\Users;
use Validator;
use App\Models\Verification;
use URL;
use Illuminate\Http\Response;
use DB;
use Hash;
use DateTime;
use Session;

//For Stripe Payment
use Stripe;


class SubscriptionsController extends Controller
{
    public function index()  //New //RR(07-02-2022)
    {
         $userId = Session::get('id');
    
        $subscriptions  =  Subscriptions::orderBy('id', 'DESC')->first();  //table
        
        if(isset($userId)){
            $payments  =  payments::select('id','order_id','plan_name','startDate','endDate','amount_captured','payment_status','created_at')->where('user_id',$userId)->where('plan_id',1)->orderBY('id','DESC')->get();  //table
        
            $currentDate = date('m/d/Y', time());
            $current = '';
            foreach($payments as $pay){
                if(($pay->startDate <= $currentDate) && ($pay->endDate >= $currentDate)){
                    $current = $pay;
                }
            }
            $sqlQueryforWeek = "SELECT COUNT(*) as totakSub, user_id ,startDate,endDate FROM payments where plan_id=2  GROUP BY startDate;";
            $getWeekData = DB::select(DB::raw($sqlQueryforWeek));
           
        }
       
        return view('wemarkthespot.subscriptions', compact('subscriptions','payments','current','getWeekData','userId'));
    }


    public function oneweek(request $request){
        //Get data from post and view(payments page with user details pass on it ==> get form user table using session id) //RR
        $startDate = $request->post('startDate');
        $endDate   = $request->post('endDate');
        $amount    = $request->post('amount');
        
        if(empty($startDate) || empty($endDate) || empty($amount)){
            $result=array('status' => false,'message' =>"Please refresh and select a week.");
            echo json_encode($result);
            die;
        }

        //Check if the selected week is available or not //RR (07-02-2022)  //weekBusiness       
        // $selectOneWeek = payments::where(array('startDate'=>$startDate,'endDate'=>$endDate))->where('plan_name','!=', 'featuredBusiness')->get(['id']);
        $sql1 = "select count(*) as tot_count from payments where plan_id in (1,3) and startDate = '".$startDate."' and endDate = '".$endDate."' ";

        $selectOneWeek = DB::select($sql1);

        if($selectOneWeek[0]->tot_count >= 1){
            $result=array('status' => false,'message' =>"This week is already selected, please select other week.");
            echo json_encode($result);
            die;
        }
        //New End //RR (07-02-2022)

        $userId = Session::get('id'); //Get user Id from session
        if(!empty($userId)){
            // $userData = DB::table('users')->where('id',$userId)->first(); //Get User information using id//
            $request->session()->put('startDate', $startDate);
            $request->session()->put('endDate', $endDate);
            $request->session()->put('amount', $amount);
            $request->session()->put('planId', '1');

            $result=array('status' => true,'message' =>"Success");

        }else{
            $result=array('status' => false,'message' =>"You are not authorize!");
        }

        echo json_encode($result);
    }

    public function threeweek(request $request){
        //Get data from post and view(payments page with user details pass on it ==> get form user table using session id) //RR
        $startDate = $request->post('startDate');
        $endDate   = $request->post('endDate');
        $amount1    = $request->post('amount');
        
        if(empty($startDate) || empty($endDate) || empty($amount1)){
            $result=array('status' => false,'message' =>"Please refresh and select a week.");
            echo json_encode($result);
            die;
        }

         //Check if the selected week is available or not //RR (07-02-2022)  //featuredBusiness        
         //$selectthreeweek = payments::where(array('startDate'=>$startDate,'endDate'=>$endDate,'plan_name'=>'featuredBusiness'))->count();
         
         $sql2 = "select count(*) as tot_count from payments where plan_id in (2,3) and startDate = '".$startDate."' and endDate = '".$endDate."' ";
         
         $selectthreeweek = DB::select($sql2);
         
         if($selectthreeweek[0]->tot_count >= 3){
             $result=array('status' => false,'message' =>"This week is already selected, please select other week.");
             echo json_encode($result);
             die;
         }
         //New End //RR (07-02-2022)

        $userId = Session::get('id'); //Get user Id from session
        if(!empty($userId)){
            // $userData = DB::table('users')->where('id',$userId)->first(); //Get User information using id//
            $request->session()->put('startDate', $startDate);
            $request->session()->put('endDate', $endDate);
            $request->session()->put('amount', $amount1);
            $request->session()->put('planId', '2');

            $result=array('status' => true,'message' =>"Success");

        }else{
            $result=array('status' => false,'message' =>"You are not authorize!");
        }

        echo json_encode($result);
    }

    public function allweek(request $request){
        //Get data from post and view(payments page with user details pass on it ==> get form user table using session id) //RR
        $startDate = $request->post('startDate');
        $endDate   = $request->post('endDate');
        $amount2    = $request->post('amount');
        
        if(empty($startDate) || empty($endDate) || empty($amount2)){
            $result=array('status' => false,'message' =>"Please refresh and select a week.");
            echo json_encode($result);
            die;
        }

         //Check if the selected week is available or not //RR (07-02-2022)  //featuredBusiness        
        //  $selectallweek = payments::select('id')->where(array('startDate'=>$startDate,'endDate'=>$endDate,'plan_name'=>'weekAndFeatured'))->first();
        $sql3 = "select count(*) as tot_count from payments where plan_id in (1,3) and startDate = '".$startDate."' and endDate = '".$endDate."' ";
         $selectallweek = DB::select($sql3);

        //  if(!empty($selectallweek)){
        if($selectallweek[0]->tot_count >= 1){
             $result=array('status' => false,'message' =>"This week is already selected, please select other week.");
             echo json_encode($result);
             die;
         }
         //New End //RR (07-02-2022)

        $userId = Session::get('id'); //Get user Id from session
        if(!empty($userId)){
            // $userData = DB::table('users')->where('id',$userId)->first(); //Get User information using id//
            $request->session()->put('startDate', $startDate);
            $request->session()->put('endDate', $endDate);
            $request->session()->put('amount', $amount2);
            $request->session()->put('planId', '3');

            $result=array('status' => true,'message' =>"Success");

        }else{
            $result=array('status' => false,'message' =>"You are not authorize!");
        }

        echo json_encode($result);
    }


    public function loadSubcriptionPayment(){
        
        $userId = Session::get('id'); //Get user Id from session
        if(!empty($userId)){
            $userData = DB::table('users')->where('id',$userId)->first(); //Get User information using id//
        }

        // dd($userData);  //test
        return view('wemarkthespot.webpayment', compact('userData'));
    }


    public function submitSubcriptionPayment(request $request){   //Payments - Stripe Payments Gateway  //RR
        // save all data to a new payments table //RR
        $userId = Session::get('id'); //Get user Id from session
   
        if($request->all())
        {
            if($userId)
            {
                // $amount  = "";
                //  $amount = substr($request->post('amount'), 1);
                $amount = $request->post('amount');

                $startDate      = $request->post('startDate');
                $endDate        = $request->post('endDate');
               // $amount         = $request->post('amount');
                // $itemPrice      = round(substr($amount, 1));
                $itemPrice      = substr($amount, 1);
                $planId         = $request->post('planId');
                $planName = "";

                if(!empty($planId)){
                    if($planId == 1){
                        $planName = 'weekBusiness';
                    }else if($planId == 2){
                        $planName = 'featuredBusiness';
                    }else if($planId == 3){
                        $planName = 'weekAndFeatured';
                    }
                }

                $customer_name  = $request->post('customer_name');
                $billing_email  = $request->post('billing_email');
                $billing_add    = $request->post('billing_add');
                $country        = $request->post('country');
                $city           = $request->post('city');
                $zip_code       = $request->post('zip_code');

                $data = array(
                    'plan_id'=>$planId,
                    'plan_name'=>$planName,
                    'plan_price'=>$amount,//$amount,                                              
                    'startDate'=>$startDate,
                    'endDate'=>$endDate,
                    'user_id'=>$userId,
                    'customer_name'=>$customer_name,
                    'billing_email'=>$billing_email,
                    'billing_address'=>$billing_add,
                    'country'=>$country,
                    'city'=>$city,
                    'zip_code'=>$zip_code,
                    'status'=>isset($status)?$status:1,
                    'created_at'=>date("Y-m-d H:i:s")
                );

                $insertQuery = DB::table('payments')->insert($data);  //insert data into payments table
                if($insertQuery){
                   
                    $id = DB::getPdo()->lastInsertId(); //Laset_Inserted_Id

                    //-------Stripe Payment Gateway Code - Start-------------------------------

                        \Stripe\Stripe::setApiKey(env('SECRET_KEY'));

                        // $information = \Stripe\Charge::create([
                        //     'amount' => $itemPrice,
                        //     'currency' => 'usd',
                        //     'source' => $request->stripeToken,
                        //     'description' => 'Test payment from '. $billing_email,
                        // ]);

                        // $stripe = new \Stripe\StripeClient(env('SECRET_KEY'));
                        // $information = $stripe->paymentIntents->create([
                        //     'amount' => $itemPrice,
                        //     'currency' => 'usd',
                        //     // 'source' => $request->stripeToken,
                        //     'description' => 'Test payment from '. $billing_email,
                        // ]);

                        //add customer to stripe
                        $customer = \Stripe\Customer::create(array(
                            'name' => $customer_name,
                            'description' => 'Test Description',
                            'email' => $billing_email,
                            'source'  => $request->stripeToken,
                            "address" => ["city" => $city, "country" => $country, "line1" => $billing_add, "line2" => "", "postal_code" => $zip_code, "state" => ""]
                        ));  

                        // details for which payment performed
                        $payDetails = \Stripe\Charge::create(array( 
                            'customer' => $customer->id,
                            'amount'   => $itemPrice*100,
                            'currency' => 'usd',
                            'description' => $planName,
                            'metadata' => array(
                                'order_id' =>$id
                            )
                        )); 

                        $paymenyResponse = $payDetails->jsonSerialize();  // get payment details in array format
                    //-------Stripe Payment Gateway Code - End-------------------------------
                        $rand_str = $this->random_strings(8);
                        $datas = array(
                            'order_id' => $rand_str,
                            'transaction_id' => $paymenyResponse['id'],
                            'payment_status' => $paymenyResponse['status'],
                            'amount_captured' => $paymenyResponse['amount_captured'],
                            'currency'       => $paymenyResponse['currency'],
                            'seller_message' => $paymenyResponse['outcome']['seller_message'],
                            'updated_at' => date("Y-m-d H:i:s")
                        );

                        $updateQuery = DB::table('payments')->where('id', $id)->update($datas);  //Update table 

                        if($updateQuery){
                            $msgg = "Payment is successful, Your transaction Id is ". $paymenyResponse['id'];
                            $request->session()->put('subscriptionsmsg',$msgg );

                       
                //            $result = array('status' => true, 'message' =>"Payment Successful", "last_id"=>$id);  //After Sucessfull Payment
                             $subject="Subscription Plan";
                    $title = "Subscription Plan Purchase";

                    $messagesub = "Subscription Plan Purchase: You've successfully purchased ".$planName;
                    
                    $getUserDeviceToken = Users::select("device_token")->where("id",$userId)->first();
                  //  dd($getUserDeviceToken);
                    $this->sendNotification($title,$messagesub,$getUserDeviceToken->device_token,"","");
                            return redirect('/subscriptions')->with("subscriptionsmsg",$msgg);
                        }
                }else{
              //      $result = array('status' => false,'message' =>"Record not inserted, Please try again.");
              $request->session()->put('subscriptionsmsgerror', 'Record not inserted, Please try again.');
                    return redirect('subscriptions')->with("subscriptionsmsgerror","Record not inserted, Please try again.");
                }
            }else{
                //Not Authorize
            //    $result=array('status' => false,'message' =>"Id missing, you are not authorize!");
                $request->session()->put('subscriptionsmsgerror', 'Id missing, you are not authorize!');  
            return redirect('subscriptions')->with("subscriptionsmsgerror","Id missing, you are not authorize!");
            }
        }
        else{
 return redirect('subscriptions')->with("subscriptionsmsgerror","Id missing, you are not authorize!");
       
        } 
        //echo json_encode($result);
    }
    
    
    public function random_strings($length_of_string){  //Randon number for Booking Id //In Use //RR
        $str_result = '0123456789';
        $yy = substr(str_shuffle($str_result), 0, $length_of_string);
        return '#'.$yy;
    }


    public function sendNotification($title, $body, $deviceToken,$type,$reply_id)
    {
        $sendNotification= Users::where("device_token",$deviceToken)->where("notification_status",1)->first();
           
        if(isset($sendNotification->notification_status) && $sendNotification->notification_status==1)
        {

       
        $url = 'https://fcm.googleapis.com/fcm/send';
        //    $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
        $FcmToken = array($deviceToken);
       $serverKey = env('web_server_key');

        $data = [
            "registration_ids" => $FcmToken,
            "notification" => [
                "title" => $title,
                "body" => $body,
            ],
            "data" => [
                "title" => $title,
                "body" => $body,
                "type"=>$type,
                "reply_id"=>$reply_id
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
        //  echo json_encode($result);
    }
}

}
