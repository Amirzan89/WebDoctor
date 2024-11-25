<?php
namespace App\Http\Middleware;
use Illuminate\Http\Request;
use Closure;
class Authorization
{
    // private $roleAdmin = ['admin'];
    public function handle(Request $request, Closure $next){
        // $userAuth = $request->input('user_auth');
        // $path = '/'.$request->path();
        return $next($request);
    }
}