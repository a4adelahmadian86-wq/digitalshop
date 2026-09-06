<?php
namespace App\Http\Controllers;
use App\Services\CustomerPlusService;
use Illuminate\Http\Request;
class CustomerClubController extends Controller
{
 public function index(Request $request,CustomerPlusService $plusService){$user=$request->user();$points=(int)$user->club_points;$tier=$user->club_tier?:'member';$next=$tier==='member'?500:($tier==='silver'?1500:3000);$plus=$plusService->status($user);return view('account.club',compact('user','points','tier','next','plus'));}
}
