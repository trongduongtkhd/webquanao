<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Support\Facades\Session;
class CheckUserLogin
{
    public function handle($request, Closure $next)
    {
        if (!Session::get('customer_id')) {
            return redirect('/login');
        }
        return $next($request);
    }
}