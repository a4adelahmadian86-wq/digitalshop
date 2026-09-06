<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
class AdminNewsletterController extends Controller{public function index(){ $subscribers=DB::table('newsletter_subscribers')->orderByDesc('created_at')->paginate(30);return view('admin.newsletter.index',compact('subscribers'));}}
