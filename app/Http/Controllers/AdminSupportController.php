<?php
namespace App\Http\Controllers;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
class AdminSupportController extends Controller {
 public function index(){return view('admin.support.index',['tickets'=>SupportTicket::with('user')->latest()->paginate(25)]);}
 public function show(SupportTicket $ticket){$ticket->load(['user','messages']);return view('admin.support.show',compact('ticket'));}
 public function message(Request $r,SupportTicket $ticket){$d=$r->validate(['body'=>'required|string|max:10000']);SupportMessage::create(['ticket_id'=>$ticket->id,'user_id'=>auth()->id(),'sender_type'=>'admin','body'=>$d['body'],'is_ai'=>false]);$ticket->update(['status'=>'answered','human_requested'=>false]);return back()->with('success','پاسخ ثبت شد.');}
 public function status(Request $r,SupportTicket $ticket){$d=$r->validate(['status'=>'required|in:open,answered,human,closed']);$ticket->update($d);return back()->with('success','وضعیت تیکت تغییر کرد.');}
}
