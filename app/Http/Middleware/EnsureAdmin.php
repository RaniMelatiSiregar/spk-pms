<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
class EnsureAdmin {
    public function handle(Request $request, Closure $next) {
        if(!$request->session()->has('admin_id')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
