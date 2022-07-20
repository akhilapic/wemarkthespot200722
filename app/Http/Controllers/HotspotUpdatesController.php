<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\Hotspots;
use App\Models\BusinessReviews;
use App\Models\Replies;
use App\Models\User;
use App\Models\CheckInOut;
use DB;

class HotspotUpdatesController extends Controller
{
    public function index()
    {
        $id = Session::get('id');
        $hotspot = DB::table('hotspots')->where('business_id',$id)->orderBy('id','desc')->paginate(10);
        $total_checkin_count = CheckInOut::where('business_id',$id)->where('check_status',1)->count();
        $totalCheckIns = CheckInOut::where('business_id',$id)->count();
        $Hotspot = array();

     

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
        foreach ($hotspot as $key => $value) {
            $data = Replies::with('user:id,name,image')->where('review_id', $value->id)->where('type',"HOTSPOT")->paginate(2);
              $userData = User::where('id',$value->user_id)->first(); 
              $value->user_name = $userData->name;
              $value->user_image = $userData->image;
            if(count($data)){
              $value->replies = $tree($data);  //$tree = $tree1($data);
            
                array_push($Hotspot, $value);
            }
        }
       //dd($hotspot);
        return view('wemarkthespot.hotspot-updates',compact('hotspot','total_checkin_count','totalCheckIns'));
    }

    public function index3()// created by gopal sir
    {
        $id = Session::get('id');
        // $hotspot = Hotspots::where('business_id',$id)->paginate(10);
        //  $hotspot = Hotspots::where('business_id',$id)->orderBy('id', 'desc')->paginate(10);
        $hotspot = DB::table('hotspots')->where('business_id',$id)->orderBy('id','desc')->paginate(10);

        $total_checkin_count = CheckInOut::where('business_id',$id)->where('check_status',1)->count();
        $totalCheckIns = CheckInOut::where('business_id',$id)->count();
       // dd($hotspot);
        /*$hotspot=array();

         $tree1 = function ($replies_reviews, $reply_id = 0) use (&$tree1) {
                $branch = array();
                foreach ($replies_reviews as $element) {

                    if ($element['reply_id'] == $reply_id) {

                        $children = $tree1($replies_reviews, $element['id']);
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
      
        foreach ($Hotspots as $key => $value) {
              $data = Replies::with('user:id,name,image')->where('review_id', $value->id)->where('type',"HOTSPOT")->paginate(2);
              if(count($data)){
                $value->replies = $tree1($data);  //$tree = $tree1($data);
                $userData = User::where('id',$value->user_id)->first(); 
                $value->user_name = $userData->name;
                $value->user_image = $userData->image;
                  array_push($hotspot, $value);
              }
        }*/
    //  dd($hotspot);
        
        return view('wemarkthespot.hotspot-updates',compact('hotspot','total_checkin_count','totalCheckIns'));
    }

        function trees($replies_reviews, $reply_id = 0)  {
                $branch = array();
                foreach ($replies_reviews as $element) {

                    if ($element['reply_id'] == $reply_id) {

                        $children = $trees($replies_reviews, $element['id']);
                        if ($children) {
                            $element['children'] = $children;
                        } else {
                            $element['children'] = [];
                        }
                        $branch[] = $element;
                    }
                }

                return $branch;
            }
}
