<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class RoleMiddleware
{
 public function handle(Request $request,Closure $next,...$roles):Response{$user=$request->user();if(!$user)return redirect()->route('login');if(!$user->is_active)abort(403,'حساب کاربری شما فعال نیست.');foreach($roles as $role){if(method_exists($user,'hasRole')&&$user->hasRole($role))return $next($request);if(($user->role??null)===$role)return $next($request);}abort(403,'شما اجازه دسترسی به این بخش را ندارید.');}
}