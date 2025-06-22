<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // Hiển thị form đăng ký
    public function showRegister() {
        return view('pages.user.register');
    }

    // Xử lý đăng ký
    public function register(Request $request) {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|unique:tbl_customers,customer_email',
            'customer_phone' => 'required|string|max:20',
            'customer_password' => 'required|string|min:6',
        ]);

        $data = [
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_password' => md5($request->customer_password), // hoặc bcrypt nếu muốn bảo mật hơn
        ];

        $customer_id = DB::table('tbl_customers')->insertGetId($data);
        Session::put('customer_id', $customer_id);
        Session::put('customer_name', $request->customer_name);

        return redirect('/')->with('message', 'Đăng ký thành công!');
    }

    // Hiển thị form đăng nhập
    public function showLogin() {
        return view('pages.user.login');
    }

    // Xử lý đăng nhập
    public function login(Request $request) {
        $request->validate([
            'customer_email' => 'required|email',
            'customer_password' => 'required|string',
        ]);

        $user = DB::table('tbl_customers')->where('customer_email', $request->customer_email)->first();

        if ($user && $user->customer_password == md5($request->customer_password)) {
            Session::put('customer_id', $user->customer_id);
            Session::put('customer_name', $user->customer_name);
            return redirect('/')->with('message', 'Đăng nhập thành công!');
        } else {
            return back()->with('error', 'Email hoặc mật khẩu không đúng!');
        }
    }

    // Đăng xuất
    public function logout() {
        Session::flush();
        return redirect('/login');
    }
}