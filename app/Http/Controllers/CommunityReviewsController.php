<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessReviews;
use App\Models\Replies;
use App\Models\Businessreviewlikedislike;
use App\Models\User;
use App\Models\BuinessReports;
use Session;
use Validator;
use File;
class CommunityReviewsController extends Controller
{
    public function index()
    {
        $business_id =Session::get('id');  //304

        $BusinessReviews  = BusinessReviews::where('business_id',$business_id)->where('type','REVIEW')->orderby("id","desc")->paginate(10);
        
        // dd($BusinessReviews);
     
         $BusinessReview1=array();
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
             
        
            $like=0;
            $dislike=0;
            $BusinessReviewss  = array();
         foreach ($BusinessReviews as $key => $value) {
             
            $data = Replies::with('user:id,name,image')->where('review_id', $value->id)->where('type',"REVIEW")->orderby('reply_id','DESC')->get();
    
               if($data){
                  $value->replies = $tree($data);
                    
             //    dd($value->replies);  //test (All Set)
                  $userData = User::where('id',$value->user_id)->first(); 
                   
                 $value->user_name = isset($userData->name)? $userData->name : '';
                 $value->user_image = isset($userData->image)? $userData->image :'';
                 
                 $business_reviewlikeData = Businessreviewlikedislike::where('businessreview_id', $value->id)->orderby('id','DESC')->get();

                    $total_like = Businessreviewlikedislike::where([
                        'businessreview_id' => $value->id,
                        'likedislike' => 1, 'business_id' => $business_id
                    ])->count();

                    $total_dislike = Businessreviewlikedislike::where([
                        'businessreview_id' => $value->id,
                        'likedislike' => 2, 'business_id' => $business_id
                    ])->count();

                 $value->total_like = $total_like;//$tlike;
                 $value->total_dislike =$total_dislike;// $tdislike;
                        
                 $BuinessReports =BuinessReports::where(array('business_id'=>$value->business_id,'review_id'=>$value->id))->count();
                        if($BuinessReports>0)
                        {
                            $value->report =1;//1 for report
                        }
                        else
                        {
                            $value->report =0;//0 for not report
                        }
                   // array_push($BusinessReviewss, $value->replies);
                    array_push($BusinessReview1, $value);
               }
         }
     
         return view('wemarkthespot.community-reviews',compact('BusinessReview1','BusinessReviews','BusinessReviewss'));
    }

    public function index33()// 06-01 22 
    {
        $business_id =Session::get('id');
        $BusinessReviews  = BusinessReviews::where('business_id',$business_id)->where('type','REVIEW')->get();
     
         $BusinessReview1=array();
 
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
             
       //   dd($BusinessReviews);

         foreach ($BusinessReviews as $key => $value) {
               $data = Replies::with('user:id,name,image')->where('review_id', $value->id)->where('type',"REVIEW")->get();

               if(count($data)){
                  $value->replies = $tree($data);
                   $userData = User::where('id',$value->user_id)->first(); 
                 $value->user_name = $userData->name;
                 $value->user_image = $userData->image;
                 
                 $like=0;
                 $dislike=0;

                 
                 $business_reviewlikeData = Businessreviewlikedislike::where('businessreview_id', $value->id)->get();
                 
                //  $tlike = Businessreviewlikedislike::where('businessreview_id', $value->id)
                //  ->where('business_id',$value->business_id)
                //  ->where('likedislike',1)
                //  ->count();

                //  $tdislike = Businessreviewlikedislike::where('businessreview_id', $value->id)
                //  ->where('business_id',$value->business_id)
                //  ->where('likedislike',2)
                //  ->count();
            
                 $total_like = Businessreviewlikedislike::where([
                        'businessreview_id' => $value->id,
                        'likedislike' => 1, 'business_id' => $business_id
                    ])->count();

                    $total_dislike = Businessreviewlikedislike::where([
                        'businessreview_id' => $value->id,
                        'likedislike' => 2, 'business_id' => $business_id
                    ])->count();

                 $value->total_like = $total_like;//$tlike;
                 $value->total_dislike =$total_dislike;// $tdislike;

                   array_push($BusinessReview1, $value);
               }
         }
  //   dd($BusinessReview1);
         return view('wemarkthespot.community-reviews',compact('BusinessReview1'));
    }
    // public function index1()
    // {
    //      $business_id =Session::get('id');
    //     $BusinessReviews = BusinessReviews::where('business_id', $business_id)->get();
        
    //     foreach($BusinessReviews as $breview)
    //     {

    //     }
        

    //     $data = Replies::with('user:id,name,image')->where('review_id', $business_id)->where('type',"REVIEW")->get();
       


    //     $tree = function ($replies_reviews, $reply_id = 0) use (&$tree) {
    //         $branch = array();
    //         foreach ($replies_reviews as $element) {

    //             if ($element['reply_id'] == $reply_id) {

    //                 $children = $tree($replies_reviews, $element['id']);
    //                 if ($children) {
    //                     $element['children'] = $children;
    //                 } else {
    //                     $element['children'] = [];
    //                 }
    //                 $branch[] = $element;
    //             }
    //         }

    //         return $branch;
    //     };
    //     $like=0;
    //     $dislike=0;
    //     $tree = $tree($data);
    //     foreach($tree as $t)
    //     {
    //         $business_reviewlikeData = Businessreviewlikedislike::where('businessreview_id', $t->id)->get();
           
    //         if (isset($business_reviewlikeData)) {
    //             foreach ($business_reviewlikeData as $reviewlikedislike) {
                   
    //                 if ($reviewlikedislike->likedislike == 1) {
    //                     $like += 1;
    //                 } else {
    //                     $dislike += 1;
    //                 }
    //             }
    //         }
    //     }

    //     $tree[0]['total_like'] = $like;
    //     $tree[0]['total_dislike'] = $dislike;
    //     return view('wemarkthespot.community-reviews',compact('tree'));
    // }

    public function communutyReplies(Request $request)
    {
        date_default_timezone_set("Asia/Calcutta");
        $Validation = Validator::make($request->all(), [
          
            'message' => 'required'
        ]);

        if ($Validation->fails()) {
            $result = array('status' => false, 'message' => 'validate Failed.', 'error' => $Validation->errors());
        } else {
            $business_id =Session::get('id');
            if ($request->type == 'REVIEW') {
                $data = array(
                    'user_id' => $business_id,
                    'review_id' => $request->review_id,
                    'reply_id' => $request->reply_id,
                    'type' => 'REVIEW',
                    'message' => $request->message,
                    'updated_at' => date("Y-m-d H:i:s", time()),
                    'created_at' => date("Y-m-d H:i:s", time())
                );
              //  dd($data);
                $inserted = Replies::create($data);
                if ($inserted) {
                    $result = array("status" => true, 'message' => "Replies added successfully", 'record_count' => 1);
                } else {
                    $result = array("status" => false, 'message' => "Replies added Failed", 'record_count' => 0);
                }
            }  else {
                $result = array("status" => false, 'message' => "Something Went Wrong");
            }
        }
        echo json_encode($result);
    }

    public function community_reportweb($business_id,$review_id)
    {
        if((!empty($business_id) && (!empty($review_id))))
        {
            $business_id =Session::get('id');
            $data = BuinessReports::create(array('user_id' => $business_id, 'business_id' => $business_id, 'review_id' => $review_id));
            if($data)
            {
                return redirect()->to('community-reviews');
            }
        }

    }

    public function pagination(Request $request){
        // $limit = $request->limit;
        // $offset = $request->offset;
        // $BusinessReview1  = BusinessReviews::limit($limit)->offset($offset)->get();
        // // dd($BusinessReview1);
        $BusinessReview1  = BusinessReviews::paginate(10);
         return view('wemarkthespot.community-reviews',compact('BusinessReview1'));
    }

    public function fileexistsCheck(Request $request)
    {
        $img_url= $request->image;
       
            if (File::exists($img_url))
          //  if(file_exists($img_url))
            {
                $result  = array("status"=>true);
            }
            else
            {
                $result  = array("status"=>false);
            }
        
        
        echo \json_encode($result);
    }
}
