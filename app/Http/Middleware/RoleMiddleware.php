<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class RoleMiddleware
{
 public function handle(Request $request,Closure $next,...$roles):Response
 {
  $user=$request->user();
  if(!$user)return redirect()->route('login');
  if(!$user->is_active)abort(403,'حساب کاربری شما فعال نیست.');
  $allowed=false;
  foreach($roles as $role){if(method_exists($user,'hasRole')&&$user->hasRole($role)){$allowed=true;break;}if(($user->role??null)===$role){$allowed=true;break;}}
  abort_unless($allowed,403,'شما اجازه دسترسی به این بخش را ندارید.');
  if($request->is('admin/*'))return app(AdminPermissionMiddleware::class)->handle($request,$next);
  return $next($request);
 }
}
