<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use DB;
use Str;
use Carbon\Carbon;
use Hash;
use App\Models\User;


use App\Models\Categorys;
use App\Models\SubCategorys;
use App\Models\FitnessTrainers;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Validator;
use App\Models\Quoates;
use App\Models\BusinessReviews;
use App\Models\BuinessReports;
use App\Models\Contactus;
use App\Models\Hotspots;
use App\Models\Businessreviewlikedislike;
use App\Models\Faqs;
use App\Models\Aboutus;
use App\Models\Subscriptions;
use App\Models\payments;
use App\Models\Giweaway;
use App\Models\OfferTypes;
use App\Models\Offers;

use App\Models\Promocodes;
//For Stripe Payment
use Stripe;

class FitnessTrainerController extends Controller
{
    public function index()
    {
        date_default_timezone_set("Asia/Kolkata");
        $data['active'] = "beaTrainer";
        return view('BeaTrainer', $data);
    }
    public function add_quotes()
    { // create category
        return view('Pages.add_quotes');
    }

    public function getdata()
    {
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        $fitnesstrainer  =  User::where("role", 99)->where('id', '!=', 312)->orderBy('id', 'DESC')->get();
        return view('Pages.fitnesstrainers.manager_firness_trainers', compact('fitnesstrainer'));
    }
    public function getdatacategory()
    {
         if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        $categorys  =  Categorys::orderBy('id', 'DESC')->get();
        //    dd($categorys);

        return view('Pages.fitnesstrainers.manager_category', compact('categorys'));
    }
    
    //======Add New payments code ()====================================================================================
    //Payments //RR
    public function getPaymentDetails(){
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        $paymants = Payments::orderBy('id', 'DESC')->get();  //table
        return view('Pages.admin_payment_details',compact('paymants'));   
    }

    public function adminAddPlans(){

        return view('Pages.admin_add_plans');
    }

//============================================================================================
    
    public function create()
    { // create category
        return view('Pages.add_category');
    }


    public function categorydata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        //dd($request->input());
        if ($request->input()) {
            $image_url = url('public/images/userimage.png');
            $Validation = Validator::make($request->all(), [
                'name' => 'unique:categorys',
            ]);
            if ($Validation->fails()) {
                $result = array('status' => false, 'message' => 'categorys name Already exists.', 'error' => $Validation->errors());
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
                $date = date("Y-m-d h:i:s", time());
                $detail_Information = $request->detail_information;

                $data = ['name' => $request->name, 'short_information' => $request->short_information, 'detail_information' => $detail_Information, 'image' => $image_url, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];

                $insertRecord =    Categorys::create($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Category Added  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Profile Update  Failed.');
                }
            }
           
        }
        else {
                    $result = array('status' => false, 'message' => 'Something Went Wrong.');
                }
                 echo json_encode($result);
    }
    /*
    public function fitness_trainer_dataold(Request $request)
    {
         $fitnesstrainer  =  new FitnessTrainers();
        if(!empty($request->input()))
        {
            $mobile_number =  $fitnesstrainer->where("mobile_number",$request->mobile_number)->first();
            $checkemail =  $fitnesstrainer->where("email",$request->email)->first();
            
            if(!is_null($mobile_number))
            {
               $result=array('statusmobile_number'=> false,'message'=> 'Mobile Number is allready taken.');
            }
            else if(!is_null($checkemail))
            {
               $result=array('statusemail'=> false,'message'=> 'Email is allready taken.');
            }
            else if(is_null($checkemail) && is_null($mobile_number))
            {
                $fitnesstrainer->name = $request->name;
                $fitnesstrainer->email = $request->email;
                $fitnesstrainer->country_code = $request->country_code;
          
                $fitnesstrainer->mobile_number = $request->mobile_number;
                $fitnesstrainer->gender = $request->gender;
                $fitnesstrainer->specialization =  implode(",",$request->specialization);
                $fileimage="";
                $image_url='';
             
                if($request->hasfile('upload_doc'))
                {
                    $file_image=$request->file('upload_doc');
                    $fileimage=$file_image->getClientOriginalName();
                    $fitnesstrainer->upload_doc = $fileimage;
                    $destination=public_path("upload_doc");
                    $file_image->move($destination,$fileimage);
                }   
             
                $fitnesstrainer->address = $request->address;
                $fitnesstrainer->updated_at = date("Y-m-d h:i:s");
                $fitnesstrainer->created_at = date("Y-m-d h:i:s");
                $fitnesstrainer->status = 1;
             
                $fitnesstrainers =  $fitnesstrainer->save();
             
                if($fitnesstrainers){
                    $result=array('status'=>true,'message'=> 'Your Request Send  Successfully.');
                }
                else{
                    $result=array('status'=>false,'message'=> 'Your Request Send Not Successfully.');
                } 
            }
        }
        echo json_encode($result);
    }*/

    public function fitness_trainer_data(Request $request)
    {
        if(!empty($request->input()))
        {
            $fitnesstrainer  =  new User();
            if (!empty($request->input())) {
                $mobile_number =  $fitnesstrainer->where("phone", $request->mobile_number)->first();
                $checkemail =  $fitnesstrainer->where("email", $request->email)->first();

                if (!is_null($mobile_number)) {
                    $result = array('statusmobile_number' => false, 'message' => 'Mobile Number is allready taken.');
                } else if (!is_null($checkemail)) {
                    $result = array('statusemail' => false, 'message' => 'Email is allready taken.');
                } else if (is_null($checkemail) && is_null($mobile_number)) {
                    $fitnesstrainer->name = $request->name;
                    $fitnesstrainer->email = $request->email;
                    $fitnesstrainer->country_code = $request->country_code;

                    $fitnesstrainer->phone = $request->mobile_number;
                    $fitnesstrainer->gender = $request->gender;
                    $fitnesstrainer->specialization =  implode(",", $request->specialization);
                    $fileimage = "";
                    $image_url = '';

                    // if($request->hasfile('upload_doc'))
                    // {
                    //     $file_image=$request->file('upload_doc');
                    //     $fileimage=$file_image->getClientOriginalName();
                    //     $fitnesstrainer->upload_doc = $fileimage;
                    //     $destination=public_path("upload_doc");
                    //     $file_image->move($destination,$fileimage);


                    // }   

                    if ($request->hasfile('upload_doc')) {
                        foreach ($request->file('upload_doc') as $file) {
                            $fileimage = $file->getClientOriginalName();
                            $file->move(public_path() . '/upload_doc/', $fileimage);
                            $imagdata[] = $fileimage;
                        }
                    }
                    // $fitnesstrainer->upload_doc = implode(",",$fileimage); 
                    $fitnesstrainer->address = $request->address;
                    $fitnesstrainer->updated_at = date("Y-m-d h:i:s");
                    $fitnesstrainer->created_at = date("Y-m-d h:i:s");
                    $fitnesstrainer->upload_doc = json_encode($imagdata);
                    $fitnesstrainer->status = 1;
                    $fitnesstrainer->role = "97";

                    $fitnesstrainers =  $fitnesstrainer->save();

                    if ($fitnesstrainers) {
                        $result = array('status' => true, 'message' => 'Your Request Send  Successfully.');
                    } else {
                        $result = array('status' => false, 'message' => 'Your Request Send Not Successfully.');
                    }
                }
            }
        }
        else
        {
            $request = array("status"=>false,"message"=>"Server Error");
        }
        echo json_encode($result);
    }
    public function set_password_fitness_trainerold(Request $request)
    {
        $fitnesstrainer  =  new User();

        if (!empty($request->input())) {

            if (!empty($request->status)) {
                $id = $request->id;

                if ($request->status == '2') {
                    $data['password']  = $request->password;
                    $data['status'] = 2;
                } else {
                    $data['status'] = $request->status;
                }
                $data['updated_at'] = date("Y-m-d h:i:s");
                $update = $fitnesstrainer->where('id', $id)->update($data);
                if ($update) {
                    if ($request->status == '1') {
                        $result = array('status' => true, 'message' => 'Your Request Is Pending Id :  ' . $id);
                    } else if ($request->status == '3') {
                        $result = array('status' => true, 'message' => 'Your Request is Rejected Id :  ' . $id);
                    } else if ($request->status == '2') {
                        $subject = "Verify Your Account Email";
                        $message = url('/signin') . "Your Business account on We Mark the Spot has been Approved. You can now signin to your account.
                                    Click here .";
                        if ($this->sendMail($request->email, $subject, $message)) {
                            $result = array('status' => true, 'message' => "Verify Your Account Email");
                        }
                        $result = array('status' => true, 'message' => 'Your Request Approved Successfully');
                    }
                } else {
                    $result = array('status' => false, 'message' => 'Set Password Fails.');
                }
            } else {
                echo "reject pending";
            }

            echo json_encode($result);
        }
    }


    public function set_password_fitness_trainer(Request $request)
    {
        $fitnesstrainer  =  new User();
        if (!empty($request->input())) {
            if (!empty($request->status)) {
                $id = $request->id;
                $userdata = User::where('id', $id)->first();
                ///  dd($userdata->device_token);
                $device_token = $userdata->device_token;

                if ($request->status == '2') {
                    $data['status'] = 2;
                    $subject = "Your Request";
                    $message = "Your registration on We Mark the Spot platform has been Approved by Admin .";
                    $this->sendMail($userdata->email, $subject, $message);
                    if (!empty($device_token)) {

                        $message1= "Your account is approved successfully. Login now";
                        $this->sendNotification($subject, $message1, $device_token,"","","");
                    }
                } else {
                    if ($request->status == 3) {
                        $subject = "Your Request";
                        $message = "Your registration on We Mark the Spot platform has been rejected<br>" . "Reason:  " . $request->reason;
                        $this->sendMail($userdata->email, $subject, $message);
                        if (!empty($device_token)) {
                        $rejectedmessage = "Your account is rejected by admin due to this  " . "Reason:" . $request->reason;
                        $this->sendNotification($subject, $rejectedmessage, $device_token,"","","");
                        }
                    } else {
                        $subject = "Your Reject";
                        $message = $request->reason;
                        if (!empty($device_token)) {
                            $this->sendNotification($subject, $message, $device_token,"","","");
                        }
                    }
                    $data['reason'] = $request->reason;
                    $data['status'] = $request->status;
                }
                $data['updated_at'] = date("Y-m-d h:i:s");
                $update = $fitnesstrainer->where('id', $id)->update($data);
                if ($update) {
                    if ($request->status == 2) {
                        $result = array('status' => true, 'message' => 'Status changed to Approved.');
                    } else if ($request->status == 3) {
                        $result = array('status' => true, 'message' => 'Status changed to Rejected.');
                    } else {
                        $result = array('status' => true, 'message' => 'Status changed to Pending.');
                    }
                    //   $result=array('status'=>true,'message'=> 'Your Request Approved Successfully');
                }
            } else {
                $result = array('status' => false, 'message' => ' Fails.');
            }
        } else {
            $result = array('status' => false, 'message' => ' reject pending.');
            echo "reject pending";
        }

        echo json_encode($result);
    }

    public function fitness_trainer_edit(Request $request, $id)
    {
        $fitnesstrainer  =  User::where("id", $id)->first();
        //    dd($fitnesstrainer);
        $country_code = $fitnesstrainer->country_code;
        $mobile_number = $fitnesstrainer->phone;

        $fitnesstrainer->phone = preg_replace("/^\+?{$country_code}/", '', $mobile_number);
        //dd($fitnesstrainer);
        return view('Pages.fitnesstrainers.fitness_trainer_edit', compact('fitnesstrainer'));
    }


    public function deleteAll(Request $request)
    {
        if(!empty($request->all()))
        {
            $ids = $request->ids;
            // dd($ids);
            $FitnessTrainers = new User();
            //    dd(explode(",",$ids));
            $ar = explode(",", $ids);
            if (!is_null($ar)) {
                foreach ($ar as $key => $value) {
                    $getdata = $FitnessTrainers->select("upload_doc")->where("id", $value)->first();
                    if (!is_null($getdata->upload_doc)) {
                        if (file_exists(public_path('upload_doc/' . $getdata->upload_doc))) {
                            unlink(public_path('upload_doc/' . $getdata->upload_doc));
                        }
                    }
                }
                $FitnessTrainers->whereIn("id", explode(",", $ids))->delete();
                return response()->json(['success' => "Business data successfully deleted."]);
            }    
        }
        else
        {
             return response()->json(['false' => "Business data Failed deleted."]);
        }
        
    }


    public function  delete(Request $request, $id)
    {

        $Categorys = new Categorys();
        $getdata = $Categorys->select("image")->where("id", $id)->first();
        if (!is_null($getdata->image)) {
            if (file_exists(public_path('images/' . $getdata->image))) {
                unlink(public_path('images/' . $getdata->image));
            }
        }
        $Categorys->where("id", $id)->delete();
        return redirect('/manager_category');
    }
    public function  manage_business_del(Request $request, $id)
    {

        $Categorys = new User();
        $getdata = $Categorys->select("image")->where("id", $id)->first();
        // dd($getdata);
        if (!is_null($getdata->image)) {
            if (file_exists(public_path('images/' . $getdata->image))) {
                unlink(public_path('images/' . $getdata->image));
            }
        }
        $Categorys->where("id", $id)->delete();
        return redirect('/manager_business');
    }

    public function fitness_trainer_view(Request $request, $id)
    {
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        $fitnesstrainer  =  user::where("id", $id)->first();
        $hotspots = Hotspots::join("users", "users.id", "=", "hotspots.user_id")
            ->where('hotspots.user_id', $id)->select(
                "users.name",
                "users.image",
                "hotspots.*",
            )
            ->get();
                $business_categortArray = explode(",",$fitnesstrainer->business_category);
                $business_sub_categortArray = explode(",",$fitnesstrainer->business_sub_category);
         //        dd($business_sub_categortArray);
                
        $category = Categorys::select("name")->wherein("id", $business_categortArray)->get();
        $subcategory = SubCategorys::select("name")->wherein("id", $business_sub_categortArray)->get();
     //   dd($subcategory);
        $category_name ="";  
        $subcategory_name ="";  
        if(!empty($category))
        {
            foreach($category as $cat)
            {
                $category_name.=$cat->name.",";
            }
        }
        if(!empty($subcategory))
        {
            foreach($subcategory as $scat)
            {
                $subcategory_name.=$scat->name.",";
            }
        }
        //dd($subcategory_name);

        $fitnesstrainer->category_name = isset($category_name) ? rtrim($category_name,",") : '';
        $fitnesstrainer->subcategory_name = isset($subcategory_name) ? rtrim($subcategory_name,",") : '';
         //  dd($fitnesstrainer);

        $reviewratting = BusinessReviews::join('users', "users.id", "=", "business_reviews.user_id")
            ->where("business_reviews.user_id", $id)
            ->select(
                "business_reviews.*",
                "users.image",
                "users.name",
                "users.business_name as business_name",
                "users.business_images"
            )
            ->orderby('id', "desc")
            ->get();

        if (count($reviewratting) > 0) {
            $avg = number_format(BusinessReviews::where('business_id', $reviewratting[0]['business_id'])->avg('ratting'), 2);
            $avgRatting = isset($avg) ? $avg : "0.0";
            foreach ($reviewratting as $i => $review) {
                $reviewratting[$i]->overallratings = BusinessReviews::where('business_id', $review->business_id)->avg('ratting');
            }
        } else {
            $avgRatting = 0.0;
        }

        //   dd($reviewratting);
        $subscriptions = payments::where('user_id', $id)->get();
        // $offers = Offers::join('categorys', "categorys.id", "=", "offers.category_id")
        //     ->select(['categorys.name as categorys_name', 'offers.*'])
        //     ->where('offers.user_id', $id)
        //     ->get();

        $offers = Offers::where('offers.user_id', $id)
        ->get();
      
        foreach ($offers as $of) {
            $business_categortArrays = explode(",",$of->category_id);
            $categorys = Categorys::select("name")->wherein("id", $business_categortArrays)->get();
            
            $categorys_name = "";
            if(!empty($categorys)){
                foreach($categorys as $c){
                    $categorys_name .=$c->name.",";
                }
            }
            $of->categorys_name = isset($categorys_name) ? rtrim($categorys_name,",") : "";
            if ($of->offer_type == 1) {
                $offertyps = OfferTypes::where('id', $of->offer_type)->select("name")->first();
                $of->offer_type = $offertyps->name;
                //   dd($offertyps);
            } else {
                $of->offer_type = "Not select Offer Type";
            }
        }
       
        return view('Pages.fitnesstrainers.fitness_trainer_view', compact('fitnesstrainer', 'hotspots', 'reviewratting', 'subscriptions', 'offers', 'avgRatting'));
    }

    public function firness_trainer_update(Request $request)
    {
        if(!empty($request->all()))
        {


        $fitnesstrainer  =  new User();
        if (!empty($request->input())) {
            $data = array();
            $id = $request->id;
            // $old_password = $request->old_password;
            // $old_upload_doc = $request->old_upload_doc;

            $fitnesstrainer  =  $fitnesstrainer->where('id', $id)->first();

            // if(!empty($old_password))
            // {
            //     if($fitnesstrainer->password==$old_password)
            //     {
            //         $fitnesstrainer->passowrd = $request->password;
            //     }
            // }
            // $fileimage="";
            //     $image_url='';

            //     if($request->hasfile('upload_doc'))
            //     {
            //         if(!is_null($old_upload_doc))
            //         {
            //             if(file_exists(public_path('upload_doc/'.$old_upload_doc)))
            //             {
            //                 unlink(public_path('upload_doc/'.$old_upload_doc));
            //             }
            //         }
            //         $file_image=$request->file('upload_doc');
            //         $fileimage=$file_image->getClientOriginalName();
            //         //$fitnesstrainer->upload_doc = $fileimage;
            //         $data['upload_doc'] =$fileimage;
            //         $destination=public_path("upload_doc");
            //         $file_image->move($destination,$fileimage);
            //     }   
            // else
            // {
            //     $data['upload_doc'] =($fileimage)? $fileimage:$fitnesstrainer->upload_doc;
            // }
            $data['name'] = ($request->name) ? $request->name : $fitnesstrainer->name;
            $data['email'] = ($request->email) ? $request->email : $fitnesstrainer->email;
            // $data['password']=($request->password)? $request->password:$fitnesstrainer->password;
            // $data['phone']=($request->mobile_number)? $request->country_code.$request->mobile_number:$fitnesstrainer->phone;
            $data['business_type'] = ($request->business_type) ? $request->business_type : $fitnesstrainer->business_type;
            // $data['dob']=($request->dob)? $request->dob:$fitnesstrainer->dob;
            // $data['specialization']=($request->specialization)? implode(",",$request->specialization):$fitnesstrainer->specialization;
            // $data['address']=($request->adaddressd)? $request->address:$fitnesstrainer->address;
            // $data['education'] =($request->education)? $request->education:$fitnesstrainer->education;
            // $data['bio'] =($request->bio)? $request->bio:$fitnesstrainer->bio;

            $data['updated_at'] = date("Y-m-d h:i:s");
            $update = $fitnesstrainer->where('id', $id)->update($data);
            if ($update) {
                $result = array('status' => true, 'message' => 'Your Trainer Record Update Successfully');
            } else {
                $result = array('status' => false, 'message' => 'Your Trainer Record Update Not Successfully.');
            }

        }
        }
        else
        {
             $result = array('status' => false, 'message' => 'Your Trainer Record Update Not Failed.');
        }
                    echo json_encode($result);
    }

    public function fitness_trainer_filter(Request $request)
    {
        //   dd($request->status);
        if ($request->status == 1) {
            $status = 0;
        } else {
            $status = $request->status;
        }
        $fitnesstrainer  =  User::where(['status' => $status, 'role' => "99"])->where('id', '!=', 312)->orderBy('id', 'DESC')->get();
        // $fitnesstrainer  =  User::where(['status'=>$request->status,'role'=>"99"])->get();
        //  dd(count($fitnesstrainer));
        if (!is_null($fitnesstrainer)) {
            $result = array('status' => true, 'data' => $fitnesstrainer);
        } else {
            $result = array('status' => false, 'data' => 'null');
        }
        echo json_encode($result);
    }

    public function checklogin(Request $request)
    {
        if ($request->input()) {

            $fitnesstrainer  =  User::where('email', $request->email)->first();

            if (!is_null($fitnesstrainer)) {
                $fitnesstrainer  =  User::where('password', $request->password)->first();
                if (is_null($fitnesstrainer)) {
                    $result = array('status' => false, 'statuspsd' => 'Invalid Password', 'check' => "password");
                } else {
                    $where = array('email' => $request->email, 'password' => $request->password);
                    $fitnesstrainer  =  User::where($where)->first();

                    $request->session()->put('fitness_tranner_id', $fitnesstrainer->id);
                    $request->session()->put('name', $fitnesstrainer->name);

                    $request->session()->put('email', $fitnesstrainer->email);
                    $request->session()->put('phone', $fitnesstrainer->phone);
                    $request->session()->put('upload_doc', $fitnesstrainer->upload_doc);
                    $request->session()->put('address', $fitnesstrainer->address);

                    //  dd($fitnesstrainer);
                    return redirect()->to('/fitness-trainer-login-my-profile');
                }
            } else {
                $result = array('status' => false, 'statusemail' => 'Invalid Email address', 'check' => "email");
            }
            
        }
        else
        {
            $result = array("status"=>false,"message"=>"Something Went Wrong");
        }
        echo json_encode($result);
    }
    public function fitness_trainer_login_my_profile()
    {

        $result = array('status' => true, 'url' => 'fitness_trainer_login_my_profile_view');
        echo json_encode($result);
    }

    public function fitness_trainer_login_my_profile_view()
    {
        $where = array('id' => session()->get('fitness_tranner_id'));
        $fitnesstrainer  =  User::where($where)->first();
        if (is_null($fitnesstrainer->image)) {
            $fitnesstrainer->image = "1.png";
        }

        return view('Pages.fitnesstrainers.fitness_trainer_login_my_profile', compact('fitnesstrainer'));
    }

    public function fitness_trainer_logout()
    {
        $request->session()->forget(['fitness_tranner_id', 'name', 'email', 'mobile_number', 'upload_doc', 'address']);
        return redirect()->to('/home');
    }

    public function trainer_publishe_profile($id)
    {
        if(!empty($id))
        {


        $where = array('id' => $id);
        $fitnesstrainer  =  User::where($where)->first();
        if (is_null($fitnesstrainer->image)) {
            $fitnesstrainer->image = "1.png";
        }
        dd($fitnesstrainer);
        return view('Pages.fitnesstrainers.trainer_publishe_profile', compact('fitnesstrainer'));
    }
    }

    public function trainer_edit_profile($id)
    {
        $where = array('id' => $id);
        $fitnesstrainer  =  User::where($where)->first();
        if (is_null($fitnesstrainer->image)) {
            $fitnesstrainer->image = "1.png";
        }

        return view('Pages.fitnesstrainers.trainer_edit_profile', compact('fitnesstrainer'));
    }

    public function trainer_forgot_password()
    {
        return view('Pages.fitnesstrainers.trainer_forgot_password');
    }

    public function firness_forget_passwordcheck(Request $request)
    {
        if ($request->input()) {
            $fitnesstrainer  =  User::where('email', $request->email)->first();
            if (is_null($fitnesstrainer)) {
                $result = array('status' => false, 'statusemail' => 'Invalid Email address');
            } else {
                $token = Str::random(16);
                //    $pass= new Password_reset();
                //     $pass->email=$request->email;
                //   $pass->timestamps=false;
                // $pass->token=$token;
                // $pass->created_at=Carbon::now();
                //    $created_at=Carbon::now();
                //  $pass->save();
                $c = Carbon::now();
                $data = array('email' => $request->email, 'token' => $token, 'created_at' => $c);

                //   dd($data);
                DB::table('password_resets')->insert($data);
                // exit;
                $msg = "";
                $subject = "Password Reset Email";

                $test = "<h1>Reset Your Password</h1>
                    <center>Hii " . $fitnesstrainer->name . "<br />We're sending you this email because you requested a password reset. Click on this link to create a new password <br /><br /><br />";

                $message = url('') . "/reset-password/" . $token;
                $msg .= $test;
                $msg .= "<br><a  href='" . $message . "'>Set a new password</a><br /><br /><br />if you didn't request a password reset, you can ignore this email. Your password will not be changed</center>";

                if ($this->sendMail($request->email, $subject, $msg)) {
                    //if(1){  
                    //    $result=array('status' => true,'message' =>"Enter your email address associated with your account 
                    //				we'll send you a link to reset your password.","url"=>"home");
                    $result = array('status' => true, 'message' => "Enter your email address associated with your account 
            //              we'll send you a link to reset your password.", "url" => "home");
                } else {
                    $result = array('status' => false, 'message' => "Something Went Wrong");
                }
            }
            echo json_encode($result);
        }
    }

    public function trainer_reset_password($token)
    {

        return view('Pages.fitnesstrainers.trainer_reset_password', compact('token'));
    }

    public function  trainer_updatepassword(Request $request)
    {

        $token_status = DB::table('password_resets')->where('token', $request->email_token)->first();
        if (is_null($token_status)) {
            $result = array('status' => false, 'message' => "Token Does Not Exist");
        } else {
            //$data['password']=Hash::make($request->password);
            $data['password'] = $request->password;

            DB::table('users')->where('email', $token_status->email)->update($data);
            DB::table('password_resets')->where('email', $token_status->email)->delete();
            $result = array('status' => true, 'message' => 'Password Reset Success', 'url' => 'signin');
        }
        echo json_encode($result);
    }

  

    public function website_trainer_edit(Request $request)
    {

        $fitnesstrainer  =  new User();
        if (!empty($request->input())) {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required',
                'gender' => 'required',
                'mobile_number' => 'required|min:10',
                'confirm_password' => 'same:new_password',
                'specialization' => 'required',
                'address' => 'required',
                'bio' => 'required'
            ], [
                'name.required' => 'Name is required',
                'new_password.required' => 'Password is required',
                'mobile_number' => 'Please Enter Mobile Number',
                'specialization' => 'Please Enter Specialization',
                'address' => "please Enter Address",
                'bio' => "please Enter Bio"
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {
                $old_password = $request->old_password;
                if (!empty($request->input())) {
                    $id = $request->id;
                    $fitnesstrainer  =  $fitnesstrainer->where('id', $id)->first();
                    if (!empty($old_password)) {
                        if ($fitnesstrainer->password == $old_password) {
                            if (!empty($old_password)) {
                                if ($fitnesstrainer->password == $old_password) {
                                    $fitnesstrainer->passowrd = $request->new_password;
                                    $data = array();
                                    $id = $request->id;
                                    $old_password = $request->old_password;
                                    $old_upload_doc = $request->old_upload_doc;

                                    $fitnesstrainer  =  $fitnesstrainer->where('id', $id)->first();



                                    $data['upload_doc'] = $fitnesstrainer->upload_doc;
                                    $data['name'] = ($request->name) ? $request->name : $fitnesstrainer->name;
                                    $data['email'] = ($request->email) ? $request->email : $fitnesstrainer->email;
                                    $data['password'] = ($request->password) ? $request->password : $fitnesstrainer->password;

                                    $data['country_code'] = ($request->country_code) ? $request->country_coder : $fitnesstrainer->country_code;

                                    $data['phone'] = ($request->mobile_number) ? $request->country_code . $request->mobile_number : $fitnesstrainer->phone;
                                    $data['gender'] = ($request->gender) ? $request->gender : $fitnesstrainer->gender;
                                    $data['dob'] = ($request->dob) ? $request->dob : $fitnesstrainer->dob;
                                    $data['specialization'] = ($request->specialization) ? implode(",", $request->specialization) : $fitnesstrainer->specialization;
                                    $data['address'] = ($request->adaddressd) ? $request->address : $fitnesstrainer->address;
                                    $data['education'] = ($request->education) ? $request->education : $fitnesstrainer->education;
                                    $data['bio'] = ($request->bio) ? $request->bio : $fitnesstrainer->bio;

                                    $data['updated_at'] = date("Y-m-d h:i:s");
                                }
                            }
                        }
                    } else {
                        $data = array();
                        $id = $request->id;
                        $old_password = $request->old_password;
                        $old_upload_doc = $request->old_upload_doc;

                        $fitnesstrainer  =  $fitnesstrainer->where('id', $id)->first();



                        $data['upload_doc'] = $fitnesstrainer->upload_doc;
                        $data['name'] = ($request->name) ? $request->name : $fitnesstrainer->name;
                        $data['email'] = ($request->email) ? $request->email : $fitnesstrainer->email;
                        $data['password'] = ($request->password) ? $request->password : $fitnesstrainer->password;
                        $data['country_code'] = ($request->country_code) ? $request->country_coder : $fitnesstrainer->country_code;

                        $data['phone'] = ($request->mobile_number) ? $request->mobile_number : $fitnesstrainer->phone;
                        $data['gender'] = ($request->gender) ? $request->gender : $fitnesstrainer->gender;
                        $data['dob'] = ($request->dob) ? $request->dob : $fitnesstrainer->dob;
                        $data['specialization'] = ($request->specialization) ? implode(",", $request->specialization) : $fitnesstrainer->specialization;
                        $data['address'] = ($request->adaddressd) ? $request->address : $fitnesstrainer->address;
                        $data['education'] = ($request->education) ? $request->education : $fitnesstrainer->education;
                        $data['bio'] = ($request->bio) ? $request->bio : $fitnesstrainer->bio;

                        $data['updated_at'] = date("Y-m-d h:i:s");
                    }


                    $update = $fitnesstrainer->where('id', $id)->update($data);
                    if ($update) {
                        return redirect()->to("/fitness_trainer_login_my_profile_view")

                            ->with('message', 'Your Trainer Record Update Successfully');
                    }
                }
            }
        }
    }

    public function website_trainer_change_password(Request $request)
    {
        $fitnesstrainer  =  new User();
        if (!empty($request->input())) {
            $validator = Validator::make($request->all(), [
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password|min:6',
                'id' => 'required',
            ], [
                'old_password.required' => 'Current Password is required',
                'new_password.required' => 'New Password is required',
                'old_password.min' => 'Current Password is Minimum of 6 digits',
                'new_password.min' => 'New Password is Minimum of 6 digits',
                'confirm_password.required' => 'Confirm Password is same as New Password',
                'confirm_password.min' => 'Confirm Password is Minimum of 6 digits',
            ]);
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            } else {

                $id = $request->id;
                $fitnesstrainer  =  $fitnesstrainer->where('id', $id)->where('password', $request->old_password)->first();
                if ($fitnesstrainer) {
                    $data = array();
                    $data['password'] = ($request->new_password) ? $request->new_password : $fitnesstrainer->new_password;
                    $data['updated_at'] = date("Y-m-d h:i:s");
                    $update = $fitnesstrainer->where('id', $id)->update($data);
                    if ($update) {
                        return redirect()->back()

                            ->with('message', 'Your Password Record Update Successfully');
                    }
                } else {
                    return redirect()->back()

                        ->with('message', 'Your Old Password Is not Match');
                }
            }
        }
    }


    function sendVerifyEmail(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        if ($validate->fails()) {
            $result = array('status' => 'Failed', "success" => false, 'message' => 'Validation failed', 'error' => $validate->errors());
        } else {
            $email_status = User::where('email', $request->email)->first();
            if (is_null($email_status)) {
                $result = array('status' => false, 'status' => false, 'message' => "Email does not exist");
            } else {

                $subject = "Verify Your Account Email";
                $message = url('signin') . "Your Business account on We Mark the Spot has been Approved. You can now signin to your account.
                                    Click here .";
                if ($this->sendMail($request->email, $subject, $message)) {
                    $result = array('status' => true, 'message' => "Password reset link send to your email address");
                } else {
                    $result = array('status' => false, 'message' => "Something Went Wrong");
                }
            }
        }

        echo json_encode($result);
    }

    public function category_status(Request $request)
    {

        if(!empty($request->all())){

            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];

            $update =  Categorys::where('id', $id)->update($data);
            if ($update) {

                $result = array("status" => true, "message" => "status updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
    }
    else
    {
        $result = array("status" => false, "message" => "Something Went Wrong");
        
    }
    echo json_encode($result);
    }

    public function edit($id)
    {
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }

        $categorys =  Categorys::where('id', $id)->first();
        if (!empty($categorys)) {
            //    dd($categorys);
            return view('Pages.edit_category', compact('categorys'));
        }
    }
    public function categoryupdate(Request $request)
    {
        if (!empty($request->id)) {
            $categorysData = Categorys::where('id', $request->id)->first();
            $fileimage = "";
            $image_url = '';
            if ($request->hasfile('image')) {
                $file_image = $request->file('image');
                $fileimage = md5(date("Y-m-d h:i:s", time())) . "." . $file_image->getClientOriginalExtension();
                $destination = public_path("images");
                $file_image->move($destination, $fileimage);
                $image_url = url('public/images') . '/' . $fileimage;
            } else {
                $image_url = $categorysData->image;
            }
            // echo $image_url;
            // exit;
            $updateData = array(
                'name' => isset($request->name) ? $request->name : $categorysData->name,
                'short_information' => isset($request->short_information) ? $request->short_information : $categorysData->short_information,
                'detail_information' => isset($request->detail_information) ? $request->detail_information : $categorysData->detail_information,
                'image' => $image_url,
                'updated_at' => date("Y-m-d h:i:s", time())
            );
            $updateRecord = Categorys::where('id', $categorysData->id)->update($updateData);
            if ($updateRecord) {
                $result = array('status' => true, 'message' => 'Category Update  successfully.');
            } else {
                $result = array('status' => false, 'message' => 'Category Update  Failed.');
            }
        } else {
            $result = array('status' => false, 'message' => 'No Record Found');
        }
        echo json_encode($result);
    }
    public function categoryview($id)
    {
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }

        $categorys = Categorys::where('id', $id)->first();
        return view('Pages.category_view', compact('categorys'));
    }

    public function getdatasubcategory()
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $subcategorys = SubCategorys::join('categorys', 'categorys.id', '=', 'sub_categorys.category_id')
            ->get(['sub_categorys.*', 'categorys.name as category_name']);
        //      dd($subcategorys);
        return view('Pages.fitnesstrainers.manage_subcategory', compact('subcategorys'));
    }
    public function add_sub_category()
    {
        $categorylist = Categorys::where('status', 0)->get();
        //    dd($categorylist);
        return view("Pages.add_sub_category", compact('categorylist'));
    }

    public function subcategorydata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->input()) {

            $name = $request->name;
            $res = SubCategorys::where('name', $name)->where('category_id', $request->category_id)->first();

            $image_url = url('public/images/userimage.png');

            if (!empty($res)) {
                $result = array('status' => false, 'message' => 'sub categorys name Already exists.');
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
                $date = date("Y-m-d h:i:s", time());
                $detail_Information = $request->detail_information;

                $data = ['category_id' => $request->category_id, 'name' => $request->name, 'short_information' => $request->short_information, 'detail_information' => $detail_Information, 'image' => $image_url, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
                //  dd($data);
                $insertRecord =    SubCategorys::create($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Sub Category Added  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Sub Category Added  Failed.');
                }
            }
            echo json_encode($result);
        }
    }
    public function subcategoryview($id)
    {
        //        $subcategorys = SubCategorys::where('id', $id)->first();

        // $subcategorys = SubCategorys::join('categorys', 'categorys.id', '=', 'sub_categorys.category_id')
        //       ->get(['sub_categorys.*', 'categorys.name as category_name'])->where('id',$id);

      if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        $subcategorys = SubCategorys::where('id', $id)->first();
        $categorys = Categorys::where('id', $subcategorys->category_id)->first();
        $subcategorys->category_name = $categorys->name;
        // dd($subcategorys);

        return view('Pages.subcategory_view', compact('subcategorys'));
    }

    public function subcategory_delete($id)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $Categorys = new SubCategorys();
        $getdata = SubCategorys::where("id", $id)->first();
        //    dd($getdata->image);
        if (!is_null($getdata->image)) {
            if (file_exists(public_path('images/' . $getdata->image))) {
                unlink(public_path('images/' . $getdata->image));
            }
        }
        $Categorys->where("id", $id)->delete();
        return redirect('/manage_sub_category');
    }

    public function subcategory_edit($id)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        //dd(date("Y-m-d h:i:s"));
        $subcategorys = SubCategorys::where('id', $id)->first();
        $categorylist = Categorys::where('status', 0)->get();
        //    dd($subcategorys->category_id);
        // foreach($categorylist as $list)
        // {
        //     if($list->id == $subcategorys->category_id)
        //     {
        //         print_r($list->name);
        //     }
        //     else{
        //         print_r($list->name);
        //     }
        // }
        // exit;
        return view('Pages.edit_subcategory', compact('subcategorys', 'categorylist'));
    }
    public function subcategoryupdate(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if (!empty($request->id)) {
            $subcategorysData = SubCategorys::where('id', $request->id)->first();
            $fileimage = "";
            $image_url = '';
            if ($request->hasfile('image')) {
                $file_image = $request->file('image');
                $fileimage = md5(date("Y-m-d h:i:s", time())) . "." . $file_image->getClientOriginalExtension();
                $destination = public_path("images");
                $file_image->move($destination, $fileimage);
                $image_url = url('public/images') . '/' . $fileimage;
            } else {
                $image_url = $subcategorysData->image;
            }
            date_default_timezone_set("Asia/Kolkata");
            $updateData = array(

                'category_id' => isset($request->category_id) ? $request->category_id : $subcategorysData->category_id,
                'name' => isset($request->name) ? $request->name : $subcategorysData->name,
                'short_information' => isset($request->short_information) ? $request->short_information : $subcategorysData->short_information,
                'detail_information' => isset($request->detail_information) ? $request->detail_information : $subcategorysData->detail_information,
                'image' => $image_url,
                'updated_at' => date("Y-m-d h:i:s", time())
            );
            //    dd($updateData);
            $updateRecord = SubCategorys::where('id', $request->id)->update($updateData);
            if ($updateRecord) {
                $result = array('status' => true, 'message' => 'SubCategory Update  successfully.');
            } else {
                $result = array('status' => false, 'message' => 'SubCategory Update  Failed.');
            }
        } else {
            $result = array('status' => false, 'message' => 'No Record Found');
        }
        echo json_encode($result);
    }
    public function subcategory_status(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if(!empty($request->all()))
        {
            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];
            $update =  SubCategorys::where('id', $id)->update($data);
            if ($update) {
                $result = array("status" => true, "message" => "status  updated successfully");
            } else {
                $result = array("status" => false, "message" => "not status  updated successfully");
            }
        }
        else
        {
         $result = array("status" => false, "message" => "Something Went Wrong");   
        }
        echo json_encode($result);
    }

    public function sub_category_by_category_id(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if(!empty($request->all()))
        {


        $categoryArray =explode(",",rtrim($request->category_id,","));
       // dd($categoryArray);
        $catid = "";
        if(!empty($categoryArray))
        {
            foreach($categoryArray as $c)
            {
                if(!empty($c))
                {
                    $catid.="'".$c."'".",";
                }
            }
        }
        
      //  $where = array('status' => 0, 'category_id' => $request->category_id);
     

         $s='SELECT * FROM `sub_categorys` WHERE status=0 and  category_id in ('.rtrim($catid,",").')';
       
         $category = DB::select($s);
       // $category =  SubCategorys::where($where)->whereIn("category_id",rtrim($catid,","))->get();
        //    dd($category);
        if ($category) {
            $result = array("status" => true, "message" => "update status", 'data' => $category);
        } else {
            $result = array("status" => false, "message" => "not update status");
        }
    }
    else
    {
        $result = array("status"=>false,"message"=>"Something Went Wrong");
    }
    echo json_encode($result);
    }

    // public function checkemail(Request $request)
    // {
    //     dd($request->input());
    // }

    public function quoates_managements()
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $quoates  =  Quoates::orderBy('id', 'DESC')->get();
        if (!empty($quoates)) {
            foreach ($quoates as $q) {
                $target_file = $q->image;
                // Select file type
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // Valid file extensions
                $extensions_arr = array("jpg", "jpeg", "png", "gif");
                if (in_array($imageFileType, $extensions_arr)) {
                    $q->imagevideocheck = 1; //image
                } else {
                    $q->imagevideocheck = 0; //video
                }
            }
        }
        return view('Pages.fitnesstrainers.quoates_managements', compact('quoates'));
    }

    public function quoteview($id)
    {
            if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        $quoates  =  Quoates::where('id', $id)->first();
        $target_file = $quoates->image;
        // Select file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Valid file extensions
        $extensions_arr = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $extensions_arr)) {
            $quoates->imagevideocheck = 1; //image
        } else {
            $quoates->imagevideocheck = 0; //video
        }

        return view('Pages.quote_view', compact('quoates'));
    }
    public function quote_edit($id)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        $quoates  =  Quoates::where('id', $id)->first();
        // dd($quoates);
        // exit;
        $target_file = $quoates->image;
        // Select file type
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Valid file extensions
        $extensions_arr = array("jpg", "jpeg", "png", "gif");
        if (in_array($imageFileType, $extensions_arr)) {
            $quoates->imagevideocheck = 1; //image
        } else {
            $quoates->imagevideocheck = 0; //video
        }

        return view('Pages.edit_quote', compact('quoates'));
    }
    public function quotes_edit(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if (!empty($request->id)) {
            $quote_video_image_status = 0;
            $categorysData = Quoates::where('id', $request->id)->first();
            $fileimage = "";
            $image_url = '';
            if ($request->hasfile('image')) {
                $old_image = $request->old_image;
                $file_image = $request->file('image');
                $check = explode("/", $file_image->getClientMimeType());
                if ($check[0] == "video") {
                    $quote_video_image_status = 1;
                } else {
                    $quote_video_image_status = 0;
                }


                $fileimage = md5(date("Y-m-d h:i:s", time())) . "." . $file_image->getClientOriginalExtension();
                $destination = public_path("images");
                $file_image->move($destination, $fileimage);
                $image_url = url('public/images') . '/' . $fileimage;

                if (!is_null($old_image)) {
                    if (file_exists(public_path('images/' . $old_image))) {
                        unlink(public_path('images/' . $old_image));
                    }
                }
            } else {
                $quote_video_image_status = $categorysData->quote_video_image_status;

                $image_url = $categorysData->image;
            }

            $updateData = array(
                'quote_video_image_status' => $quote_video_image_status,
                "video" => isset($request->video) ? $request->video : $categorysData->video,
                'name' => isset($request->name) ? $request->name : '',//$categorysData->name,
                'short_information' => isset($request->short_information) ? $request->short_information :'',// $categorysData->short_information,
                'detail_information' => isset($request->detail_information) ? $request->detail_information : '',//$categorysData->detail_information,
                'image' => $image_url,
                'updated_at' => date("Y-m-d h:i:s", time())
            );
            $updateRecord = Quoates::where('id', $categorysData->id)->update($updateData);
            if ($updateRecord) {
                $result = array('status' => true, 'message' => 'Quotes Update  successfully.');
            } else {
                $result = array('status' => false, 'message' => 'Quotes Update  Failed.');
            }
        } else {
            $result = array('status' => false, 'message' => 'No Record Found');
        }
        echo json_encode($result);
    }
    public function quotesdata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
        }

        //  dd($request->input());
        if ($request->input()) {
            $image_url = url('public/images/userimage.png');
            $Validation = Validator::make($request->all(), [
                'name' => 'unique:quoates_managements',
            ]);
            if ($Validation->fails()) {
                $result = array('status' => false, 'message' => 'Quoates name Already exists.', 'error' => $Validation->errors());
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
                $date = date("Y-m-d h:i:s", time());
                $detail_Information = $request->detail_information;

                $data = ['name' => $request->name, 'short_information' => $request->short_information, 'detail_information' => $detail_Information, 'image' => $image_url, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];

                $insertRecord =    Quoates::create($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Quoates Added  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Quoates added  Failed.');
                }
            }
           
        }
        else
        {
             $result = array('status' => false, 'message' => 'Something Went Wrong .');
        }
         echo json_encode($result);
    }

    public function business_report()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $buinessReports = BuinessReports::join('users', 'users.id', '=', 'business_reports.user_id')
        
            ->join('business_reviews', 'business_reviews.id', "=", "business_reports.review_id")
            ->join('users as business_user', 'business_user.id', "=", "business_reports.business_id")
            ->select(
                [
                    'business_reports.*',
                    'users.name as user_name',
                    'users.image as user_image',
                    'business_reviews.id as business_reviews_id',
                    'business_reviews.image_video_status',
                    'business_reviews.review as comment',
                    'business_reviews.created_at as post_date',
                    'business_reviews.image as business_reviews_image',
                    'business_reviews.ratting as business_reviews_ratting',
                    'business_user.name as business_username',
                    'business_user.id as business_user_id',
                    'business_reviews.status as business_reviews_status',
                    'business_reports.created_at as business_reports_created_date'
                ]
            )
            ->get();
            
        //            dd($buinessReports);
        foreach ($buinessReports  as $b) {

            $target_file = rtrim($b->business_reviews_image, ",");

            // Select file type
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            // Valid file extensions

            $b->business_reviews_image = $target_file;
            $extensions_arr = array("jpg", "jpeg", "png", "gif");
            if (in_array($imageFileType, $extensions_arr)) {
                $b->imagevideocheck = 1; //image
            } else {
                $b->imagevideocheck = 0; //video
            }
        }
        //   dd($buinessReports);
        return view('Pages.fitnesstrainers.manage_business_report', compact('buinessReports'));
    }


    public function report_status(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->all()) {
            //dd($request->all());
            $business_report_id  = $request->business_report_id;
            $business_id  = $request->business_id;
            $user_id  = $request->user_id;
            $review_id = $request->review_id;
            $report_status_value = $request->report_status_value;

            $userData = User::where('id', $user_id)->first();
            if ($report_status_value == 1) //send mail
            {

                //   dd($userData);
                $subject = "Report Review";
                $message = "Not Good Comment";

                if ($this->sendMail($userData->email, $subject, $message)) {
                    BuinessReports::where('id', $business_report_id)->update(array('report_status' => 1));
                    $result = array('status' => true, 'message' => "Mail Send");
                }
            } else if ($report_status_value == 2) // remove comment
            {
                $subject = "Report Review";
                $message = "Remove Comment";


                $this->sendMail($userData->email, $subject, $message);
                BuinessReports::where('id', $business_report_id)->update(array('report_status' => 2));
                BusinessReviews::where('id', $review_id)->update(array('status' => 2));
                $result = array('status' => true, 'message' => "Mail Send and Remove Comment");
            }
           
        }
        else
        {
             $result = array('status' => true, 'message' => "Something Went Wrong");
        }
         echo json_encode($result);
    }

    public function ReviewandRattingManagement(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $buinessReports = User::join('business_reviews', 'business_reviews.user_id', '=', 'users.id')
            ->join('users as business_user', 'business_user.id', "=", "business_reviews.business_id")
            ->where('business_reviews.status', 0)
            ->orderBy('business_reviews.id', 'desc')
            ->select('users.id as user_id', 'users.name as user_name', 'users.image as user_image', 'business_reviews.ratting', 'business_reviews.status', 'business_reviews.business_id as business_id', 'business_reviews.id as business_reviews_id', 'business_reviews.review', 'business_reviews.image_video_status', 'business_reviews.image as business_review_image', 'business_user.name as business_owner_name', 'business_user.business_name as business_name', 'business_user.image as business_owner_image', 'business_user.business_images as business_image', 'business_reviews.created_at as post_date')->get();
            // ->paginate(100);

        // ->get(
        //     [ 'users.id as user_id', 'users.name as user_name', 'users.image as user_image', 'business_reviews.ratting', 'business_reviews.status', 'business_reviews.business_id as business_id', 'business_reviews.id as business_reviews_id', 'business_reviews.review', 'business_reviews.image_video_status', 'business_reviews.image as business_review_image', 'business_user.name as business_owner_name', 'business_user.business_name as business_name', 'business_user.image as business_owner_image', 'business_user.business_images as business_image', 'business_reviews.created_at as post_date' ]
        // );
            if(!empty($buinessReports))
            {

            foreach ($buinessReports  as $b) {
                $business_avg =   BusinessReviews::where('business_id', $b->business_id)->avg('ratting');
                $target_file = rtrim($b->business_review_image, ","); //$b->business_review_image;
                $b->business_review_image = $target_file;
                $all = explode(',', $b->business_review_image);

                // Select file type
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                //  dd($imageFileType);
                // Valid file extensions
                $extensions_arr = array("jpg", "jpeg", "png", "gif", "PNG");
                if (in_array($imageFileType, $extensions_arr)) {
                    $b->imagevideocheck = 1; //image
                } else {
                    $b->imagevideocheck = 0; //video
                }
                $b->overall_rating_of_business = number_format($business_avg, 2);
            }
        }
        //  dd($buinessReports);
        return view('Pages.fitnesstrainers.management_review_ratting', compact('buinessReports'));
    }

    public function business_review__remove(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->all()) {
            $business_id  = $request->business_id;
            $user_id  = $request->user_id;
            $review_id = $request->review_id;

            $response = BusinessReviews::where(['id' => $review_id, 'user_id' => $user_id, 'business_id' => $business_id])->update(array('status' => 2));
            if ($response) {
                $result = array('status' => true, 'message' => "Ratting And Review Remove Successfully");
            } else {
                $result = array('status' => true, 'message' => "Ratting And Review Remove Failed");
            }
        }
        else
        {
            $result = array('status' => true, 'message' => "Something Went Wrong ");
        }
            echo json_encode($result);
    }

    public function getcontact()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $contactus = Contactus::orderBy('created_at',"desc")->get();
        return view('Pages.fitnesstrainers.manager_contactus', compact('contactus'));
    }

    public function contact_status(Request $request)
    {
        if(!empty($request->all()))
        {
            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];
            $update =  Contactus::where('id', $id)->update($data);
            if ($update) {
                $result = array("status" => true, "message" => "Status  change successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
        }
        else
        {
            $result = array("status" => false, "message" => "not updated status");
        }
        echo json_encode($result);
    }

    public function likedislikeweb(Request $request)
    {
        if($request->all())
        {


            $Validation = Validator::make($request->all(), [
                'business_id' => 'required',
                'businessreview_id' => 'required',
                'likedislike' => 'required',
            ]);


            if ($Validation->fails()) {
                $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
            } else {
            $user_id = $request->session()->get('id');

            $checkResult = Businessreviewlikedislike::where(array('user_id' => $user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id))->count();
            if ($checkResult > 0) {
                $data =  Businessreviewlikedislike::where(array('user_id' => $user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id))->update(array('likedislike' => $request->likedislike));
            } else {
                $data = Businessreviewlikedislike::create(array('user_id' => $user_id, 'business_id' => $request->business_id, 'businessreview_id' => $request->businessreview_id, 'likedislike' => $request->likedislike));
            }
            if ($data) {
                $result  = array("status" => true, "message" => 'Like dislike Added Successfully');
            } else {
                $result  = array("status" => False, "message" => 'Like dislike  Added Failed');
            }
        }
        }
        else
        {
            $result  = array("status" => False, "message" => 'Something Went Wrong');
        }
        echo json_encode($result);
    }

    public function getfaq()
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $faq  =  Faqs::orderBy('id', 'DESC')->get();

        return view('Pages.fitnesstrainers.faq', compact('faq'));
    }

    public function add_Faq()
    { // create add_Faq
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        return view('Pages.add_Faq');
    }

    public function Faq_data(Request $request)
    {
            if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if ($request->input()) {

            $Validation = Validator::make($request->all(), [
                'question' => 'required',
                'answers' => 'required',
            ]);
            if ($Validation->fails()) {
                $result = array('status' => false, 'message' => 'Please enter question or answers.', 'error' => $Validation->errors());
            } else {
                $date = date("Y-m-d h:i:s", time());
                $data = ['answers' => $request->answers, 'question' => $request->question, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
                $insertRecord =    Faqs::create($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Faq Added  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Faq Added  Failed.');
                }
            }
            echo json_encode($result);
        }
        else
        {
            $result = array('status' => false, 'message' => 'Something Went Wrong .');
        }
        echo json_encode($result);
    }

    public function faq_edit($id)
    {
            if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
                return redirect('/');
            }
        $faq =  Faqs::where('id', $id)->first();
        if (!empty($faq)) {
            //   dd($faq);
            return view('Pages.edit_faq', compact('faq'));
        }
    }

    public function Faq_update(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if(!empty($request->all()))
        {
            $id = $request->id;
            $result = Faqs::where('id', $id)->first();

            $updateArray = array(
                'question' => isset($request->question) ? $request->question : $result->question,
                'answers' => isset($request->answers) ? $request->answers : $result->answers
            );


            $update =  Faqs::where('id', $id)->update($updateArray);
            if ($update) {

                $result = array("status" => true, "message" => "Faq  updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated Faq");
            }
         }
         else
         {
            $result = array("status" => false, "message" => "Something Went Wrong ");
         }
        echo json_encode($result);
    }

    public function Faq_status(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
        }

        if(!empty($request->all()))
        {
            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];

            $update =  Faqs::where('id', $id)->update($data);
            if ($update) {

                $result = array("status" => true, "message" => "status  updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
        }else
        {
            $result = array("status" => false, "message" => "Something Went Wrong");
        }
        echo json_encode($result);
    }
    public function Faq_delete($id)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        Faqs::where("id", $id)->delete();
        return redirect('/faq');
    }

    public function manage_aboutus()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $aboutus = Aboutus::first();

        return view('Pages.Aboutus', compact('aboutus'));
    }

    public function manage_terms_conditions()
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $terms_conditions = DB::table('terms_conditions')->first();
        return view('Pages.terms_conditions', compact('terms_conditions'));
    }
    public function aboutdata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if ($request->input()) {

            $Validation = Validator::make($request->all(), [
                'description' => 'required',
            ]);
            if ($Validation->fails()) {
                $result = array('status' => false, 'message' => 'Please enter description', 'error' => $Validation->errors());
            } else {
                // dd($request);
                $id = $request->id;
                if (isset($id)) {
                    $date = date("Y-m-d h:i:s", time());
                    $data = ['description' => $request->description, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
                  
                    $insertRecord = Aboutus::where('id', $id)->update($data);
                    if ($insertRecord) {
                        $result = array('status' => true, 'message' => 'About  details updated  successfully.');
                    } else {
                        $result = array('status' => false, 'message' => 'Faq Added  Failed.');
                    }
                } else {
                    $date = date("Y-m-d h:i:s", time());
                    $data = ['description' => $request->description, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
                    $insertRecord =    Aboutus::create($data);
                    if ($insertRecord) {
                        $result = array('status' => true, 'message' => 'About  details added  successfully.');
                    } else {
                        $result = array('status' => false, 'message' => 'Faq Added  Failed.');
                    }
                }
            }
          
        }
        else
        {
            $result = array('status' => false, 'message' => 'Something Went Wrong .');
        }
          echo json_encode($result);
    }

    public function manageterms_conditions(Request $request)
    {
        if(!empty($request->all()))
        {


        $Validation = Validator::make($request->all(), [
            'description' => 'required',
        ]);
        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'Please enter description', 'error' => $Validation->errors());
        } else {
            // dd($request);
            $id = $request->id;
            if (isset($id)) {
                $date = date("Y-m-d h:i:s", time());
                $data = ['description' => $request->description, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
              
                $insertRecord = DB::table('terms_conditions')->where('id', $id)->update($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Terms conditions  details updated  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Faq Added  Failed.');
                }
            } else {
                $date = date("Y-m-d h:i:s", time());
                $data = ['description' => $request->description, 'updated_at' => date("Y-m-d h:i:s", time()), 'created_at' => date("Y-m-d h:i:s", time())];
                $insertRecord =    DB::table('terms_conditions')->create($data);
                if ($insertRecord) {
                    $result = array('status' => true, 'message' => 'Terms conditions details added  successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Faq Added  Failed.');
                }
            }
        }
         }
         else
         {
         $result = array('status' => false, 'message' => 'Something  Went Wrong.');
         }
        echo json_encode($result);
    }
    

    public function getdataSubscriptions()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
        return redirect('/');
        }
        $subscriptions  =  Subscriptions::orderBy('id', 'DESC')->get();  //table
        return view('Pages.admin_subscriptions', compact('subscriptions'));
    }

    public function editSubscriptions($id)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
        }
        $subscriptions =  Subscriptions::where('id', $id)->first();
        if (!empty($subscriptions)) {
            //dd($subscriptions);
            return view('Pages.edit_subscriptions', compact('subscriptions'));
        }
    }

    public function subscriptionsUpdate(Request $request)
    {
        if(!empty($request->all()))
        {

            if (!empty($request->id) && $request->id == 1) {
                $updateData = array(
                    'weekBusiness' => $request->BusinessoftheWeek,
                    'featuredBusiness' => $request->FeaturedBusiness,
                    'weekAndFeatured' => $request->WeekAndFeaturedBusiness,
                    'updated_at' => date("Y-m-d h:i:s", time())
                );

                $updateRecord = Subscriptions::where('id', $request->id)->update($updateData);

                if ($updateRecord) {
                    $result = array('status' => true, 'message' => 'Subscription Update successfully.');
                } else {
                    $result = array('status' => false, 'message' => 'Subscription Update Failed.');
                }
            } else {
                $result = array('status' => false, 'message' => 'Something Went Wrong!');
            }
        }
        else
        {
            $result = array('status' => false, 'message' => 'Something Went Wrong!');
        }

        echo json_encode($result);
    }

    public function business_giveaways()
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        $business_giweaway = Giweaway::leftJoin('users', 'users.id', '=', 'giweaways.user_id')
        ->get(['users.business_name', 'giweaways.name as giweaways_name', 'giweaways.id as giweaways_id', 'giweaways.description as giweaways_description', 'giweaways.created_at', 'giweaways.status as giweaways_status']);
        // dd($business_giweaway);
        return view('Pages.business_giweaway', compact('business_giweaway'));
    }

    public function business_giveaways_status(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
            return redirect('/');
        }
        if(!empty($request->all()))
        {
            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];
            $update =  Giweaway::where('id', $id)->update($data);
            if ($update) {
                $result = array("status" => true, "message" => "Giveaway status  change successfully ");
            } else {
                $result = array("status" => false, "message" => "not update status");
            }
        }
        else
        {
            $result = array("status" => false, "message" => "Something Went Wrong");
        }
        echo json_encode($result);
    }

    public function edit_giweaways($id)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $business_namelist = User::whereNotNull('business_name')->where('role', 99)->select(['business_name', 'id'])->get();
        $giweaway =  Giweaway::where('id', $id)->first();
        return view('Pages.edit_giweaway', compact('giweaway', 'business_namelist'));
    }

    public function giweaway_update(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if(!empty($request->all()))
        {
            $id = $request->input('id');
            $description = $request->input('description');
            $user_id = $request->input('user_id');

            $give = Giweaway::where('id', $id)->first();
            if ($give) {
                $image_url = $give->image;
                $name = $request->input('name');  
                if ($request->hasfile('image')) {

                    $file = $request->file('image');



                    $fileimage = md5(date("Y-m-d h:i:s", time())) .  "." . $file->getClientOriginalExtension();
                    $destination = public_path("images");
                    $file->move($destination, $fileimage);
                    $image_url = url('public/images') . '/' . $fileimage;
                }

                $data = ['description' => $description, 'name' => $name, 'user_id' => $user_id, 'image' => $image_url];
                $update =  Giweaway::where('id', $id)->update($data);
                if ($update) {
                    $user=DB::table("users")->select("id","device_token")->where("role",97)->get();
                    
                    if(!empty($user))
                    {
                        foreach($user as $u){
                            if(!empty($u->device_token)){
                            
                                $title="Admin add a new giveaway";
                                $body="Admin has successfully updated a new giveaway check it now";
                                 $device_token = $u->device_token;
                            $this->sendNotification($title,$body,$device_token,"","giveaway","");
                            }
                        }
                    }
                    $result = array("status" => true, "message" => "Giveaway update successfully ");
                } else {
                    $result = array("status" => false, "message" => "not update status");
                }
            }else{
                $result = array("status" => false, "message" => "not update status");
            }
        }
        else
        {
            $result = array("status" => false, "message" => "Something Went Wrong ");
        }

        echo json_encode($result);
    }

    public function getdataoffer_type()
    {
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        $OfferTypes  =  OfferTypes::orderBy('id', 'DESC')->get();
        foreach ($OfferTypes as $k => $of) {
            $business_category = Categorys::select('name')->where('id', $of->category_id)->first();
            if (!empty($business_category)) {
                $OfferTypes[$k]['category_name'] = $business_category->name;
            } else {
                $OfferTypes[$k]['category_name'] = "Not Select Category";
            }
        }
        return view('Pages.fitnesstrainers.offer_type', compact('OfferTypes'));
    }

    public function add_offertype()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $category = Categorys::where("status", 0)->get();
        $OfferTypes  =  OfferTypes::orderBy('id', 'DESC')->get();

        return view('Pages.add_offertype', compact('OfferTypes', 'category'));
    }

    public function offerypedata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->input()) {
            $name = $request->name;
         //   $OfferTypes  =  OfferTypes::where("name", $name)->count();
         //   if ($OfferTypes > 0) {
         //       $result = array("status" => false, "message" => "Offers name already exists");
         //   } else {
                $data = array(
                    'name' => $request->name,
                    'category_id' => $request->category_id,
                    'created_at' => date("Y-m-d h:i:s", time()),
                    'updated_at' => date("Y-m-d h:i:s", time()),
                    'short_information' => isset($request->short_information)? $request->short_information :''
                );

                //    dd($data);
                //  exit;
                $insertData = OfferTypes::create($data);
                if ($insertData) {
                    $result = array("status" => true, "message" => "Offers type added successfully ");
                } else {
                    $result = array("status" => false, "message" => "not added status");
                }
               
          //  }
        }
        else {
                    $result = array("status" => false, "message" => "Something Went Wrong");
                }
                 echo json_encode($result);
    }

    public function offertypestatus(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $id = $request->id;
        $status = $request->status;
        $data = ['status' => $status];

        $update =  OfferTypes::where('id', $id)->update($data);
        if ($update) {

            $result = array("status" => true, "message" => "status  updated successfully");
        } else {
            $result = array("status" => false, "message" => "not updated status");
        }
        echo json_encode($result);
    }

    public function offerstype_edit($id)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $category = Categorys::where("status", 0)->get();
        $OfferTypes =  OfferTypes::where('id', $id)->first();
        return view('Pages.edit_offertype', compact('OfferTypes', 'category'));
    }

    public function editoffertypedata(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->all()) {
            $id = $request->id;
            $OfferTypes =  OfferTypes::where('id', $id)->first();
            $updateOffertype = array(
                'name' => isset($request->name) ? $request->name : $OfferTypes->name,
                'category_id' => isset($request->category_id) ? $request->category_id : $OfferTypes->category_id,
                'short_information' => isset($request->short_information) ? $request->short_information : $OfferTypes->short_information,
            );
            $update =  OfferTypes::where('id', $id)->update($updateOffertype);
            if ($update) {

                $result = array("status" => true, "message" => "Offers type updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated Offers");
            }
            
        }
        else 
        {
            $result = array("status" => false, "message" => "Something Went Wrong");
        }
            echo json_encode($result);
    }

    public function Introductory_Video()
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $quoates  =  Quoates::orderBy('id', 'DESC')->get();
        if (!empty($quoates)) {
            foreach ($quoates as $q) {
                $target_file = $q->image;
                // Select file type
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                // Valid file extensions
                $extensions_arr = array("jpg", "jpeg", "png", "gif");
                if (in_array($imageFileType, $extensions_arr)) {
                    $q->imagevideocheck = 1; //image
                } else {
                    $q->imagevideocheck = 0; //video
                }
            }
        }
        return view('Pages.fitnesstrainers.Introductory_Video_managements', compact('quoates'));
    }

    public function Introductory_video_edit($id)
    {
        $quoates  =  Quoates::where('id', $id)->first();
        return view('Pages.edit_introductory_video', compact('quoates'));
    }

    public function IntroductorVideoedit(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->input()) {
            $id = $request->id;
            $quoates  =  Quoates::where('id', $id)->first();
            $fileimage = "";
            $image_url = '';
            if ($request->hasfile('video')) {
                $file_image = $request->file('video');
                $fileimage = md5(date("Y-m-d h:i:s", time())) . "." . $file_image->getClientOriginalExtension();
                $destination = public_path("images");
                $file_image->move($destination, $fileimage);
                $image_url = url('public/images') . '/' . $fileimage;
            } else {
                $image_url   = $quoates->video;
            }
            $updateData = array('video' => $image_url);
            $update = Quoates::where('id', $id)->update($updateData);
            if ($update) {
                $result = array("status" => true, "message" => "Introductory video updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }

        }
        else {
                $result = array("status" => false, "message" => "Something Went Wrong");
            }
            echo json_encode($result);
    }

    public function report_business_status(Request $request)
    {
              if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if ($request->input()) {

            //    dd($request);

            $business_review_id = $request->business_review_id;
            $updatedata = array('status' => $request->status); // 1 for deactive
            $update = BusinessReviews::where('id', $business_review_id)->update($updatedata);
            if ($update) {
                $result = array("status" => true, "message" => "status updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
           
        }
        else
        {
             $result = array("status" => false, "message" => "Something Went Wrong");
        }
         echo json_encode($result);
    }

    public function Introductory_video_status(Request $request)
    {
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if ($request->input()) {

            $id = $request->id;
            $updatedata = array('video_status' => $request->status); // 1 for deactive
            $update = Quoates::where('id', $id)->update($updatedata);
            if ($update) {
                $result = array("status" => true, "message" => "status updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
        }
        else
        {
            $result = array("status" => false, "message" => "Something Went Wrong");
        }
        echo json_encode($result);
    }

    public function ContactReply(Request $request){
          if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }

        if($request->all()){
             $id= $request->id;
             $message= $request->message;
             $contactDeteails = Contactus::where("id",$id)->first();
            if(!empty($contactDeteails))
            {

                $userDetails = DB::table("users")->select("device_token")->where("id",$contactDeteails->user_id)->first();

                if(!empty($userDetails)){
                    $title= "Mail sent by the admin";
                    $body="Admin has sent a mail to you, please check";
                    $device_token = $userDetails->device_token;
                    $this->sendNotification($title,$body,$device_token,"","sendmail","");
                }
                Contactus::where("id",$id)->update(array("reply"=>$message));
                $subject = "Reply Your Query";
                $message = $request->message;

              
                if ($this->sendMail($contactDeteails->email, $subject, $message)) {
                $result = array("status"=>true,"message"=>"Send Query Reply Successfully");
                }
                else
                {
                    $result = array("status"=>false,"message"=>"Send Query Reply Fail");
                }
            }
            else
            {
                $result = array("status"=>false,"message"=>"Invalid Email Address");
            }
        }
        else
            {
                $result = array("status"=>false,"message"=>"Something Went Wrong ");
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
            $mail->setFrom("wemarkspot@gmail.com");
            $mail->FromName = "Wemark The Spot";

            if ($mail->send()) {
                return 1;
            } else {
                return 0;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    public function privacypolicy(){
         if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
       $privacypolicyDetails= DB::table("privacypolicy")->first();

        return view("Pages.fitnesstrainers.privacypolicy",compact('privacypolicyDetails'));
    }

    public function privacypolicystore(Request $request){
            if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        if($request->all()){
             //   dd($request->all());
                $testedit1 = $request->description;
            //    dd($testedit1);
               $privacypolicy= DB::table("privacypolicy")->first();
               $check = false;
               if(!empty($privacypolicy)){
                DB::table("privacypolicy")->update(array("description"=>$testedit1));
                $check = true;   
            }
               else
               {
                DB::table("privacypolicy")->insert(array("description"=>$testedit1));
                $check = false;  
            }
            if($check = true){
                $result = array("status"=>true,"message"=>"Privacy Policy Updated Successfully");
            }
            else
            {
                $result = array("status"=>true,"message"=>"Privacy Policy Updated Failed");
            }
        }
        else
        {
            $result = array("status"=>true,"message"=>"Something Went Wrong ");
        }
         echo json_encode($result);
    }
    public function sendNotification($title, $body, $deviceToken,$businessreview_id,$type,$reply_id)
    {
        $sendNotification= User::where("device_token",$deviceToken)->where("notification_status",1)->first();
           
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
                'businessreview_id'=>$businessreview_id,
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


    public function strippaymentcheck(Request $request){
      //  dd($request->all());
        if($request->all()){
            $user_id = $request->user_id;
            $site_stripe_public_key ="pk_test_51IpYXxKeN71b4QWVbYXSvbckLBrNiI3d9qKivGyQVP22uvDOwL2eLjqghsSdXYpaixEDz8vmb9zslbhswbsjxeS700rJvTgFXG"; //'pk_test_npAflnOwoQOckN49eDWjH6xi00LMruqQfZ';

            $site_stripe_secret_key ="sk_test_51IpYXxKeN71b4QWVfusSgbjNc9xoNjdKNe3jjB2F4jXZkBr0SRPjjOaUB1wgBJh8yms6I1NPc0wVkfL6RIupbvUI008YMnqx5K"; // 'sk_test_oAYEKnsUJPZ9Zyda9Xj8LqPb00lM6C6LwP';
    
            if (!empty($request->stripeToken))
            {
                $order_id = $request->order_id;

                $token = $request->stripeToken;
    
                $customer_name = $request->name;
    
                $customer_email = $request->email;
    
                $card_num = $request->card_num;
    
                $card_cvc = $request->cvc;
    
                $card_exp_month = $request->exp_month;
    
                $card_exp_year = $request->exp_year;

                 //include Stripe PHP library
             
                 
          //  require_once APPPATH . "third_party/stripe/init.php";
            //set api key
            $stripe = array(

                "secret_key" => $site_stripe_secret_key,

                "publishable_key" => $site_stripe_public_key

            );
        //   dd($stripe);

            \Stripe\Stripe::setApiKey($stripe['secret_key']);
             //add customer to stripe
             $customer = \Stripe\Customer::create(array(

                'email' => $customer_email,

                'source' => $token

            ));

                        //item information
            
                        $final_paid_total = (Int)$request->amount;
             //   $finalCost = $request->amount;
                        $final_paid_total = number_format($final_paid_total, 2, '.', '');
                      //  $stripeFee = (5 / 100) * $final_paid_total;
                     //   $finalCost=$finalCost+$stripeFee;
            
                        $finalCost = $final_paid_total * 100;
            
                        $itemName = "Donation";
            
                         $itemNumber = 'Payment Of Order Id '.$order_id;
            
                        $itemPrice = $finalCost;
            
                        $currency = 'USD';
                        $orderID = $order_id;
                        //charge a credit or a debit card
          
                        $charge = \Stripe\Charge::create(array(

                'customer' => $customer->id,

                'amount' => $itemPrice,

                'currency' => $currency,

                'description' => $itemNumber,

                'metadata' => array(

                    'item_id' => $itemNumber

                )

            ));
            //retrieve charge details
            $chargeJson = $charge->jsonSerialize();
        //    dd($chargeJson);

            if ($chargeJson['amount_refunded'] == 0 && empty($chargeJson['failure_code']) && $chargeJson['paid'] == 1 && $chargeJson['captured'] == 1)
            {

                $datas = array(
                    'plan_id'=>0,
                    'plan_name'=>"",
                    'plan_price'=>$request->amount,//$amount,                                              
                    'startDate'=>"",
                    'endDate'=>"",

                    'customer_name'=>$customer_name,
                    'billing_email'=>$customer_email,
                    'billing_address'=>"",
                    'country'=>"",
                    'city'=>"",
                    'zip_code'=>"",
                    'status'=>1,
                    'order_id' => $order_id,
                    'transaction_id' => $chargeJson['id'],
                    'payment_status' => $chargeJson['status'],
                    'amount_captured' => $chargeJson['amount_captured'],
                    'currency'       => $chargeJson['currency'],
                    'seller_message' => $chargeJson['outcome']['seller_message'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                    "user_id"=>$user_id
                );
              //  dd($datas);
                $payment = payments::create($datas)->id;  //insert table 
            //    dd($payment);
                if($payment){

                        $email="admin1@yopmail.com";
                        $subject="Donation: We mark the spot";
                        $message ="You have received the donation of $".$request->amount." from ".$customer_name;

                        
                    $this->sendMail($email, $subject, $message);
                   $paymentDetails=payments::where("id",$payment)->first();
                  //  return redirect("/payment_success");
               //   dd($paymentDetails);
                    return view("payment_success",compact('paymentDetails'));
                }

            }
            else
            {
                return view("payment_failed");
               //  return    redirect('/payment_failed');
            }

            }
    
        }
    }

    public function payment_failed(){
        return view("payment_failed");
    }

    public function payment($id,$amount){
        $userDetails = DB::table("users")->where("id",$id)->first();
    
        $result['user_id'] = $id;
        $result['name'] = $userDetails->name;
        $result['email'] = $userDetails->email;
        $result['amount'] = $amount;
        return view("payment2",compact('result'));
    }

    public function manager_donationhistory(){
            if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
             return redirect('/');
            }
        $donationhistory =payments::join("users","users.id","=","payments.user_id")->where("payments.plan_id",0)->get(["users.business_name","payments.*"]);
        return view("Pages.donationhistory",compact("donationhistory"));
    }

    public function add_new_business_owner(){
    if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
        return redirect('/');
        }
          $country_codedata =DB::table('country_codes')->get();
        return view("Pages.add_new_business_owner",compact("country_codedata"));
    }

    public function add_new_business_by_admin(Request $request){
        if($request->all())
        {
            $Validation = Validator::make($request->all(),[
                'name' => 'required',
                'business_name'=>'required',
                'password' => 'required|min:5',
                'location'=>'required',
                'cpassword'=>'required',  
                'email' => 'required',
                'business_type'=>'required',
              //  'upload_doc'=>'required',

                //'termsconditions'=>'required'
            ], [
                'business_name'=>'Business Name is required',
                'name.required' => 'Business Owner Name is required',
                'password.required' => 'Password is required',
                'location.required'=>"location is required",
                'cpassword.required'=>"Conform Password is required",
                'business_type.required'=>"Please Select business type",
             //   'upload_doc'=>"Upload Commercial License",
                 // 'termsconditions'=>"Accepted Terms & Conditions"
            ]);

        if($Validation->fails())
        {
            return redirect('/add_new_business_owner')->withErrors($Validation)->withInput();    
        }
        else
        {
        
                $emailexitc = User::where('email',$request->email)->count();
             
                if($emailexitc>0)
                {
                          $result= array("status"=>false,"email"=>false, "message"=>" User already registered");
                }
                else
                {
                   $fileimage="";
                   $image_url='';
                   if($request->hasfile('image'))
                    {
                        $file_image=$request->file('image');
                        $fileimage=md5(date("Y-m-d h:i:s", time())).".".$file_image->getClientOriginalExtension();
                        $destination=public_path("images");
                        $file_image->move($destination,$fileimage);
                        $image_url=url('public/images').'/'.$fileimage;
                     
                    }
                    else
                      {
                          $image_url=url('public/images/userimage.png');
                      }

                  $fileimage2="";
                   $image_urlBusiness_image='';
                   if($request->hasfile('business_images'))
                  {
                    $file_image2=$request->file('business_images');
                 
                    $fileimage2=md5(date("Y-m-d h:i:s", time())).".".$file_image2->getClientOriginalExtension();
                 
                    $destination2=public_path("images");
                 

                    $file_image2->move($destination2,$fileimage2);
                    $image_urlBusiness_image=url('public/images').'/'.$fileimage2;
                 
                  }
                  else
                  {
                      $image_urlBusiness_image=url('public/images/userimage.png');
                  }

                    // upload_doc
                  
                   $upload_doc ="";
                  if($request->hasfile('upload_doc'))
                  {
                    $file_image=$request->file('upload_doc');
                    $fileimage=md5(date("Y-m-d h:i:s", time())).".".$file_image->getClientOriginalExtension();
                    $destination=public_path("upload_doc");
                    $file_image->move($destination,$fileimage);
                    $upload_doc=url('public/upload_doc').'/'.$fileimage;
                  }
                  else
                  {
                    $upload_doc= "";
                  }
                

                  $data = array(
                    'business_name'=>isset($request->business_name) ? $request->business_name  :'',
                    'business_images' => isset($image_urlBusiness_image) ? $image_urlBusiness_image  :'',
                    'device_token'=>isset($request->device_token) ? $request->device_token :'',
                    'name'=>$request->name,
                    'email'=>$request->email,
                    'phone'=>isset($request->phone)? $request->phone : '',
                    'country_code'=>isset($request->phone)? $request->country_code : '',
                    'location'=>isset($request->location) ? $request->location :'',
                    'lat'=>isset($request->lat) ? $request->lat :'',
                    'long'=>isset($request->long) ? $request->long :'',
                    'password'=>hash::make($request->password),
                    'business_type'=>isset($request->business_type)? $request->business_type : 1,
                      'image'=>$image_url,
                      'upload_doc'=>$upload_doc,
                     'created_at'=>date("Y-m-d h:i:s", time()),
                      'updated_at'=>date("Y-m-d h:i:s", time()),
                      'email_verified'=>0,
                      'role'=>99,
                      'status'=>$request->status,
                      'approved'=>0,
                    );
               //   dd($data);

                    $insertRecord =   User::create($data);
                    if($insertRecord)
                    {
                        $subject="Login Details";
                        $message = "Email Address ". $request->email ."<br/>";
                        $message .= "Password: ". $request->password;
                        if($this->sendMail($request->email,$subject,$message)){
                            $result=array('status' => true,'message' =>"New Business Owner Added Successfully");
                        }else{
                        $result=array('status' => false,'message' =>"Something Went Wrong");
                        }
                    }
                    else
                    {
                        $result=array('status' => false,'message' =>"Something Went Wrong");
                    }
                    
                }
          echo json_encode($result);
        }
       
        }
    }

    public function getpromocode(){
         if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
           $promocodelist =  Promocodes::join("categorys","categorys.id","=","promo_codes.business_category_id")
            ->get(['promo_codes.*',"categorys.name as category_name"]);

            return view("Pages.promocode_list",compact("promocodelist"));


    }
    public function add_promocode()
    {
         if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
           $categorylist =  Categorys::where('status',0)->get();

            return view("Pages.add_promocode",compact("categorylist"));
    }

    public function promocodedata(Request $request){
         if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        if(!empty($request->all()))
        {
            $business_category_id = $request->business_category_id;
            $name = $request->name;
            $amount = $request->amount;
            $promotion_type = $request->promotion_type;
            $start_valid_date= $request->start_valid_date;
            $end_valid_date= $request->end_valid_date;

            $created_at = date("Y-m-d h:i:s", time());
            $updated_at=date("Y-m-d h:i:s", time());
            $type = $request->type;
            $getCategory = categorys::select("name")->where("id",$business_category_id)->first();
             //  dd($getCategory); 
            $category_name = isset($getCategory->name) ? $getCategory->name :'';
            $promotion_type_name = "";
            if($promotion_type==1)
            {
                $promotion_type_name="Flat";
            }
            else if($promotion_type==2)
            {
                $promotion_type_name="Percentage";
            }
            if($type=="add")
            {
               $checkPromoCodeexist =  Promocodes::where("business_category_id",$business_category_id)->where("name","Like",    '%'.$name."%")->count();
               if($checkPromoCodeexist > 1)
               {

                 $result = array("status"=>false,"message"=>"Promocodes allready exist");
               }
               else
               {
                $getBusinessDetails = User::select("email")->where("business_category",$business_category_id)->where("role",99)->get();
             
                  $insertData =   Promocodes::create(array(
                        "business_category_id"=>$business_category_id,
                        "name"=>$name,
                        "amount"=>$amount,
                        "promotion_type"=>$promotion_type,
                        "start_valid_date"=>$start_valid_date,
                        "end_valid_date"=>$end_valid_date,
                        'created_at'=>$created_at,
                        'updated_at'=>$updated_at)
                        );

                    if(!empty($getBusinessDetails))
                    {
                        $this->business_user_send_mail_promocode($getBusinessDetails,$name,$promotion_type_name,$start_valid_date,$end_valid_date,$category_name);
                    }
                    if($insertData)
                    {
                        $result = array("status"=>true,"message"=>"Promocodes added successfully");

                          
                    }
                    else
                    {
                        $result = array("status"=>false,"message"=>"Promocodes added  failed");
                       
                    }
                }
            }
            else if($type=="edit")
            {
                 $updateData =   Promocodes::where("id",$request->id)->update(array(
                    "business_category_id"=>$business_category_id,
                    "name"=>$name,
                    "amount"=>$amount,
                    "promotion_type"=>$promotion_type,
                    "start_valid_date"=>$start_valid_date,
                    "end_valid_date"=>$end_valid_date,
                    'created_at'=>$created_at,
                    'updated_at'=>$updated_at)
                    );
                  if($updateData)
                {
                    $result = array("status"=>true,"message"=>"Promocodes updated successfully");

                      
                }
                else
                {
                    $result = array("status"=>false,"message"=>"Promocodes updated  failed");
                   
                }

            }

        }
        else
        {
            $result = array("status"=>false,"message"=>"Something Went Wrong");
        
        }
      echo json_encode($result);
    }

    
    public function promocode_status(Request $request)
    {

        if(!empty($request->all())){

            $id = $request->id;
            $status = $request->status;
            $data = ['status' => $status];

            $update =  Promocodes::where('id', $id)->update($data);
            if ($update) {

                $result = array("status" => true, "message" => "status updated successfully");
            } else {
                $result = array("status" => false, "message" => "not updated status");
            }
    }
    else
    {
        $result = array("status" => false, "message" => "Something Went Wrong");
        
    }
    echo json_encode($result);
    }
    public function promocode_delete($id){
        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }
        Promocodes::where("id", $id)->delete();
        return redirect('/promocode_list');
      
    }

   public function promocode_edit($id){

        if(empty(session('adminid')) && empty(session('user_id')) && empty(session('adminid'))){
         return redirect('/');
        }

         $promocodes=Promocodes::where("id", $id)->first();

          $categorylist =  Categorys::where('status',0)->get();

            return view("Pages.edit_promocode",compact("categorylist","promocodes"));


   }


    public function business_user_send_mail_promocode($getBusinessDetails,$name,$promotion_type_name,$start_valid_date,$end_valid_date,$category_name)
    {
        foreach($getBusinessDetails as $details)
        {
            $subject="Promocode Details";
            $message = "Business Category : ". $category_name."<br/>";
            $message = "Promo Name : ". $name ."<br/>";
            $message .= "Promotion type: ". $promotion_type_name."<br/>";
            $message.="Start From :" .$start_valid_date."<br/>";
            $message.="End From :" .$end_valid_date."<br/>";
            $this->sendMail($details->email,$subject,$message);
        }
    }
}
