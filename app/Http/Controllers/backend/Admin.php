<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use App\Models\payments;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
class Admin extends Controller
{
    public function index()
    {
        return view('Pages.login');
    }  
      
      public function forget_password()
    {
        return view("Pages.forgetpsdadmin");
    }
      public function customLogin(Request $request)
    {
        if(!empty($request->all()))
        {


            $this->validate($request, [
                'email' => 'required',
                'password' => 'required',
            ]);
        
        
            $remember_me = $request->has('remember') ? true : false; 
         

        
            if (auth()->attempt(['email' => $request->input('email'), 'password' => $request->input('password'),'role'=>98], $remember_me))
            {
                
                $user = auth()->user();
            
                    
                $request->session()->put('adminid',$user->id);
                $request->session()->put('adminname',$user->name);
                $request->session()->put('adminemail',$user->email);
                $request->session()->put('adminuser_id',$user->id);
                $request->session()->put('adminrole',$user->role);
                $request->session()->put('adminuse_image',$user->use_image);
                $request->session()->put('adminphone',$user->phone);
                $request->session()->put('adminimage',$user->image);
                $user = Auth::getProvider()->retrieveByCredentials(['email' => $request->input('email'), 'password' => $request->input('password')]);

                
                if($remember_me==true)
                {
                    $minutes = 14400;
                    $response = new Response();
                    $cooky=(cookie('remember_me', $user->remember_token, $minutes));
                    return redirect()->to('/dashboard') ->withSuccess('Signed in')->withCookie($cooky);
                }else{
                    $minutes = 0;
                    return redirect()->to('/dashboard') ->withSuccess('Signed in')->withCookie(cookie('remember_me','', $minutes));
                }
              
              
            }else{
                return redirect('admin_login')->with('msg', 'Please enter valid login credentials.');  
            }
          }
          else
          {
            return redirect('admin_login');
          }
    }

    public function customLoginold(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
   
        $credentials = $request->only('email', 'password');
        // print_r($credentials);
        // exit;
        if (Auth::attempt($credentials)) {
            $user = User::where("email",$request->email)->first();
          //  dd( $user->name);
             $request->session()->put('id',$user->id);
            $request->session()->put('name',$user->name);
            $request->session()->put('email',$user->email);
            $request->session()->put('user_id',$user->id);
            $request->session()->put('role',$user->role);
            $request->session()->put('use_image',$user->use_image);
            $request->session()->put('phone',$user->phone);
               $request->session()->put('image',$user->image);

          return redirect()->to('/dashboard') ->withSuccess('Signed in');
          
          //  return redirect()->intended('dashboard')
            //            ->withSuccess('Signed in');
        }
    return redirect()->back()->with('msg', 'Please enter valid login credentials.');  
        //return redirect("login")->with('','');
    }


     public function dashboard()
    {
        //dd('jjj');
        //if(Auth::check()){
             $total_user = User::where('role',97)->count();
             $total_buiness = User::where(array('role'=>99,'status'=>2))->count();
            
             $total_subscribed = Payments::count();
             $revenue = Payments::get();
            $sum = 0;
             foreach($revenue as $r)
             {
                $sum= $sum+trim($r->plan_price, "$");
             }
             $total_revenue = $sum;
            return view('Pages.dashboard',compact('total_user','total_buiness','total_subscribed','total_revenue'));
        //}
  
        //return redirect("login")->withSuccess('You are not allowed to access');
    }
    

    public function signOut() {
        Session::flush();
        //request()->session()->regenerateToken();
        Session::forget('adminid');
        Session::forget('adminname');
        Session::forget('adminemail');
        Session::forget('adminuser_id');
        Session::forget('adminrole');
        Session::forget('adminuse_image');
        Session::forget('adminphone');
        Session::forget('adminimage');
        Auth::logout();
        return Redirect('admin_login'); //redirect back to login
    }
    
    
}
