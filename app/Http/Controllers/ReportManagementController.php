<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessReviews;
use App\Models\BusinessVisits;
use App\Models\CheckInOut;
class ReportManagementController extends Controller
{
    public function index()
    {
        $business_id = session()->get('id');
    
        $totalCheckin=    CheckInOut::where(["business_id"=>$business_id])->count();
        $OverallRating = number_format(BusinessReviews::where(["business_id"=>$business_id])->avg('ratting'),2);

        $BusinessVisits = BusinessVisits::where(["business_id"=>$business_id])->sum('visit_count');

        return view('wemarkthespot.report-management',compact('totalCheckin','OverallRating','BusinessVisits'));
    }
}
