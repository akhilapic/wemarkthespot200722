<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function testData()
    {
        $data =DB::table('users')->select('id','image','business_images')->where('image','!=','')->get();
        foreach($data as $d)
        {
            echo substr($d->image,22);

          //  print_r(explode("/",$d->image));
            echo "<br>";
        }

        echo  $url = "https://appicdevelopment.online";
    
      echo  $previous_url = "https://builtenance.com/development/wemarkthespot/public/images/5ed7f22faf5a8314ed121fa7053d4dd7.png";
        
     echo   $res = substr($previous_url, 10);
        exit;
        $res = "image.png";
        
        echo $new_link = $$url . $res;

    }
    public function index()
    {
        return view('home');
    }

     public function homepage()
   {
    $data['active']="home";
    return view('wemarkthespot.webhome',$data);
   }

   public function testtoken()
   {
       return view('testtoken');
   }
   public function storetoken(Request $request)
   {
    dd($request->all());
   }
   public function sendnotification(Request $request)
   {
    dd($request->all());
   }
}
