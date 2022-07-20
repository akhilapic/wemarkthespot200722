<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorys;
use App\Models\Offers;

use App\Models\Users;

use App\Models\OfferTypes;
use App\Models\User;
use Session;
use DB;
class MyoffersController extends Controller
{
    public function index()
    {
        $user_id = Session::get('id');
        $checkOffer = Offers::where("user_id",$user_id)->count();
        if($checkOffer>0)
        {
            User::where("id",$user_id)->update(array("current_promotion"=>1));
        }
        else
        {
            User::where("id",$user_id)->update(array("current_promotion"=>0));
        }
       $business_category  = Categorys::where('status',0)->select(['id','name'])->get();
        //   $offers = Offers::join('categorys','categorys.id',"=","offers.category_id")
        //   ->where('offers.status',0)
        //   ->where('offers.user_id',$user_id)
        // ->select(['offers.*','categorys.name as category_name'])
        // ->get();

        $offers = Offers::where('offers.user_id',$user_id)
     // ->select(['offers.*','categorys.name as category_name'])
      ->get();
//        dd($offers);
        
        $category_name="";
        foreach($offers as $off)
        {
            $attCategory = explode(",",$off->category_id);
            $CategoryDetails = Categorys::select("name")->whereIn("id",$attCategory)->get();
            foreach($CategoryDetails as $k=> $f)
            {
                $category_name .= $f['name'].","; 
            }
            $off->category_name = rtrim($category_name,",");
            $offerTypesdata  = OfferTypes::where('id',$off->offer_type)->first();
            if(!empty($offerTypesdata)>0)
            {
                $off->offer_typeselected = $offerTypesdata->name;
            }
            else
            {
                $off->offer_typeselected ="Offers type not select";
            }
        }
        $offerTypes  = OfferTypes::where('status',0)->get();
     
        return view('wemarkthespot.my-offers',compact('business_category','offers','offerTypes'));
    }


    public function offerData(Request $request)
    {   
        if($request->input())
        {
          //  dd($request->all());
            $user_id = $request->session()->get('id');
            $date = date("Y-m-d h:i:s", time());
            $insertData = array('offer_name'=>$request->name,'user_id'=>$user_id,'category_id'=>implode(",",$request->category_id),'offer_type'=>$request->offer_type,'activation'=>$request->activation,'deactivation'=>$request->deactivation,'offer_message'=>$request->offer_message,'created_at'=>$date,'updated_at'=>$date);

         //  dd($insertData);
            $insertoffer =Offers::create($insertData); //Offers::create($insert_data);
            if($insertoffer)
            {
             
                User::where("id",$user_id)->update(array("current_promotion"=>1));
                $userDetails = DB::table("business_fav")->where("business_id",$user_id)->get();
              
                if(!empty($userDetails)){
                    foreach($userDetails as $u){
                     $getUser=   User::where("id",$u->user_id)->first();
                     $getBusiness=   User::where("id",$user_id)->first();
                        if(!empty($getUser)){
                            $title=$getBusiness->business_name." Favourite business";
                          //  $body=$getBusiness->business_name." Offer & promotion added";
                          $body= "Your Favorite business ".$getBusiness->business_name. " has added Offer";;
                           
                          
                            $deviceToken = $getUser->device_token;
                          
                            if(!empty($deviceToken))
                            {
                                $this->sendNotification($title,$body,$deviceToken,$user_id,"business");
                            }
                            
                        }
                    }
                }
            $recentOfferAdd =Offers::where("id",$insertoffer->id)->first();
            $offerName  =$recentOfferAdd->offer_name;
            $offeractivation  =$recentOfferAdd->activation;
            $offerdeactivation  =$recentOfferAdd->deactivation;

                $result = array('status'=>true,'message'=>"Offer added successfully",
                    "offerName"=>$offerName,
                    "offeractivation"=>$offeractivation,
                    "offerdeactivation"=>$offerdeactivation,
                    "offer_msg"=>"your offer has been successfully crearted",
                    "last_offer_id" =>$insertoffer->id,"user_id"=>$user_id);
            }
            else
            {
                $result = array('status'=>false,'message'=>"Offer added fail");
            }
        }
        else
        {
            $result = array('status'=>false,'message'=>"please fill all offers details");
        }
        echo json_encode($result);
    }

    public function editofferData(Request $request)
    {   
       // dd($request->all());
        if($request->input())
        {
            $id = $request->id;
            $getOffers=Offers::where('id',$id)->first();
           
            $user_id = $request->session()->get('id');
            $date = date("Y-m-d h:i:s", time());
            $updatedtData = array(
                'offer_name'=>isset($request->name) ? $request->name : $getOffers->offer_name,
                'user_id'=>$user_id,
                'category_id'=>isset($request->category_id) ? implode(",",$request->category_id) : $getOffers->category_id,
                'offer_type'=>isset($request->offer_type) ? $request->offer_type : $getOffers->offer_type,
                'activation'=>isset($request->activation) ? $request->activation : $getOffers->activation,
                'deactivation'=>isset($request->deactivation) ? $request->deactivation : $getOffers->deactivation,
                'offer_message'=>isset($request->offer_message) ? $request->offer_message : $getOffers->offer_message,
                'updated_at'=>$date
            );
        //    dd($updatedtData);
           $update =  Offers::where('id',$id)->where('user_id',$user_id)->update($updatedtData);
            if($update)
            {
                $userDetails = DB::table("business_fav")->where("business_id",$user_id)->get();
             //  dd($userDetails);
                if(!empty($userDetails)){
                    foreach($userDetails as $u){
                     $getUser=   User::where("id",$u->user_id)->first();
                     $getBusiness=   User::where("id",$user_id)->first();
                        if(!empty($getUser)){
                            $title= $getBusiness->business_name;
                            $body=$getBusiness->business_name." has bean updated this business offers ";
                            $deviceToken = $getUser->device_token;
                          
                            if(!empty($deviceToken))
                            {
                                $this->sendNotification($title,$body,$deviceToken,$user_id,"business");
                            }
                            
                        }
                    }
                }
//                $result = array('status'=>true,'message'=>"Offer updated successfully")
                    $recentOfferAdd =Offers::where("id",$id)->first();
            $offerName  =$recentOfferAdd->offer_name;
            $offeractivation  =$recentOfferAdd->activation;
            $offerdeactivation  =$recentOfferAdd->deactivation;

                $result = array('status'=>true,'message'=>"Offer updated successfully",
                    "offerName"=>$offerName,
                    "offeractivation"=>$offeractivation,
                    "offerdeactivation"=>$offerdeactivation,
                    "offer_msg"=>"your offer has been successfully updated",
                    "last_offer_id" =>$id,"user_id"=>$user_id);
                
            }
            else
            {
                $result = array('status'=>false,'message'=>"Offer updated fail");
            }
        }
        echo json_encode($result);
    }

    public function deleteofferData(Request $request)
    {
        if($request->input())
        {
            $user_id = Session::get('id');
            $id = $request->id;
            $delete =  Offers::where('id',$id)->where('user_id',$user_id)->delete();
            if($delete)
            {
                $result = array('status'=>true,'message'=>"Offer delete successfully");
            }
            else
            {
                $result = array('status'=>false,'message'=>"Offer delete fail");
            }
            echo json_encode($result);
        }
    }

    public  function getoffertypebycategory_id(Request $request)
    {

        if($request->all())
        {
            $category_id = explode(",",$request->category_id);
             $offers = OfferTypes::whereIn('category_id',$category_id)->get();
          
            if(count($offers)>0)
            {
                $alloffer = OfferTypes::where('status',0)->get();
                $result = array('status'=>true,'data'=>$offers,'alloffer'=>$alloffer);
            }
            else
            {
                $offers = OfferTypes::where('status',0)->get();
                $result = array('status'=>false,'data'=>$offers,'alloffer'=>'');
            }
            echo json_encode($result);
        }
    }

    public function send_notification_newOffer($last_offer_id){
          $get_OfferDetails =  Offers::where('id',$last_offer_id)->first();
         


             $getNotification  = Users::select("device_token")->where("id",$get_OfferDetails->user_id)->first();
             //   dd($getNotification);
                //------------------------------add new Offer----------------------
                 if(!empty($getNotification->device_token))
                {

                    $title2 = "New offer";
                    $body2="At New offer creation time: You've successfully offer Nname ".$get_OfferDetails->name." created this offer with start Date ".$get_OfferDetails->activation. " & End Date ".$get_OfferDetails->deactivation." details";

                    $this->sendNotification($title2,$body2,$getNotification->device_token,$get_OfferDetails->user_id,"");
                }
                // --------------------------send notification add new Offer ----------------------

          die;

    }

    public function offer_expiry()
    {

        $offers = Offers::where('deactivation', '=', Date('y-m-d', strtotime('+7 days')))->get();
        if(!empty($offers))
        {
            //dd($offers);

            foreach($offers as $offer)
            {
                $deactivation = $offer->deactivation;
                $get_userDetails=  User::select("device_token")->where("id",$offer->user_id)->first();
               if($get_userDetails->device_token)
               {
                $title="Your Offer Expiry ";
                $message = "Your offer is going to expire on ".$deactivation;
                $deviceToken = $get_userDetails->device_token;
                $this->sendNotification($title,$message,$deviceToken,$offer->user_id,"");
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
