<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request; 
use App\Http\Requests;

use Illuminate\Support\Facades\Redirect;
session_start();
class AdminController extends Controller
{
  
    public function index(){
        return view('admin_login');
    }
    public function show_dashboard(){
      
        return view('admin.admin_dashboard');
    }
    public function dashboard(Request $request){
        $admin_email = $request->admin_email;
        $password =md5($request->password);
        $result = DB::table('tbl_admin')
        ->where('admin_email', $admin_email)
        ->where('password', $password)
        ->first();
        if($result){
            Session::put('admin_name', $result->admin_name);
            Session::put('admin_id', $result->admin_id);
            return Redirect::to('/admin_dashboard');
        }else{
             Session::put('message','Mật khẩu hoặc tài khoản không đúng. Vui lòng thử lại!');
            return Redirect::to('/admin/dashboard');
        }
    }
     public function logout(){       
            Session::flush();
            return Redirect::to('/admin');
    }


}