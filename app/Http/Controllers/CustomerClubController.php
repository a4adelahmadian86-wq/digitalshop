<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class CustomerClubController extends Controller
{
 public function index(Request $request){$user=$request->user();$points=(int)$user->club_points;$tier=$user->club_tier?:'member';$next=$tier==='member'?500:($tier==='silver'?1500:3000);return view('account.club',compact('user','points','tier','next'));}
}
