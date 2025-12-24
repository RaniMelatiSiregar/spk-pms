<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
    public function showLogin() { return view('auth.login'); }
    public function login(Request $req) {
        $req->validate(['email'=>'required|email','password'=>'required']);
        $admin = Admin::where('email',$req->email)->first();
        if($admin && Hash::check($req->password, $admin->password)) {
            session(['admin_id'=>$admin->id, 'admin_name'=>$admin->name]);
            return redirect()->route('dashboard');
        }
        return back()->withErrors(['msg'=>'Email atau password salah']);
    }
    public function logout() {
        session()->flush();
        return redirect()->route('login');
    }
}
