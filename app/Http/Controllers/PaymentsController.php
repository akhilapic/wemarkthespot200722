<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Payments;
use App\Models\User;


class PaymentsController extends Controller
{
    public function index()
    {
        return view('wemarkthespot.payment');
    }

    public function subscription_expiry()
    {
        $subscription = Payments::where('endDate', '=', Date('m/d/Y', strtotime('+7 days')))->get();
        if(!empty($subscription))
        {

            foreach($subscription as $subscriptions)
            {
                $deactivation = $subscriptions->endDate;
                $get_userDetails=  User::select("device_token")->where("id",$subscriptions->user_id)->first();
               if($get_userDetails->device_token)
               {
                $title="Your subscription Expiry ";
                $message = "Your plan is ".$subscriptions->plan_name." expring on ".$deactivation;
                $deviceToken = $get_userDetails->device_token;
                $this->sendNotification($title,$message,$deviceToken,$subscriptions->user_id,"");
               }
               
            }
        }
    }
    public function sendNotification($title, $body, $deviceToken,$business_id,$type)
    {
        $sendNotification= User::where("device_token",$deviceToken)->where("notification_status",1)->first();
      
        if($sendNotification->notification_status==1)
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
                "businessreview_id"=>$business_id,
                "type"=>$type,
                "reply_id"=>""
                
            ]
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
