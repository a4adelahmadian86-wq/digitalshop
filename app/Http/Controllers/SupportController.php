<?php
namespace App\Http\Controllers;
use App\Models\SupportTicket;
use App\Models\SupportMessage;
use Illuminate\Http\Request;
class SupportController extends Controller {
 public function index(){ $tickets=auth()->user()->supportTickets()->latest()->paginate(10); return view('support.index',compact('tickets')); }
 public function create(Request $request){ return view('support.create',['relatedType'=>$request->query('related_type'),'relatedId'=>$request->query('related_id'),'subject'=>$request->query('subject')]); }
 public function store(Request $request){
  $data=$request->validate(['subject'=>'required|string|max:180','category'=>'required|in:general,product,technical,order,payment,refund,complaint,account,other','body'=>'required|string|max:10000','related_type'=>'nullable|string|max:80','related_id'=>'nullable|integer']);
  $sensitive=in_array($data['category'],['technical','payment','refund','complaint'],true);
  $ticket=SupportTicket::create(['user_id'=>auth()->id(),'subject'=>$data['subject'],'category'=>$data['category'],'related_type'=>$data['related_type']??null,'related_id'=>$data['related_id']??null,'status'=>'open','priority'=>$sensitive?'high':'normal']);
  SupportMessage::create(['ticket_id'=>$ticket->id,'user_id'=>auth()->id(),'sender_type'=>'user','body'=>$data['body']]);
  if(!$sensitive){$reply=$this->aiReply($data['body']);SupportMessage::create(['ticket_id'=>$ticket->id,'user_id'=>null,'sender_type'=>'ai','body'=>$reply,'is_ai'=>true]);$ticket->update(['ai_handled'=>true,'status'=>'answered']);}
  return redirect()->route('support.show',$ticket)->with('success','درخواست شما ثبت شد.');
 }
 public function show(SupportTicket $ticket){abort_unless($ticket->user_id===auth()->id(),403);$ticket->load('messages');return view('support.show',compact('ticket'));}
 public function message(Request $request,SupportTicket $ticket){abort_unless($ticket->user_id===auth()->id(),403);$data=$request->validate(['body'=>'required|string|max:10000']);SupportMessage::create(['ticket_id'=>$ticket->id,'user_id'=>auth()->id(),'sender_type'=>'user','body'=>$data['body']]);$ticket->update(['status'=>'open']);return back()->with('success','پیام شما ثبت شد.');}
 public function human(Request $request,SupportTicket $ticket){abort_unless($ticket->user_id===auth()->id(),403);$ticket->update(['human_requested'=>true,'human_requested_at'=>now(),'status'=>'human']);SupportMessage::create(['ticket_id'=>$ticket->id,'user_id'=>auth()->id(),'sender_type'=>'system','body'=>'کاربر درخواست کرده پاسخ توسط اپراتور انسانی انجام شود.']);return back()->with('success','درخواست شما به اپراتور انسانی ارجاع شد. پاسخ انسانی ممکن است زمان بیشتری نیاز داشته باشد.');}
 private function aiReply(string $body):string { $text=mb_strtolower($body); if(preg_match('/(رمز|هک|خطاى فنی|خطای فنی|باگ|امنیت|پرداخت|پول|بازگشت|عودت|اعتراض|شکایت)/u',$text)) return 'این درخواست برای بررسی دقیق به اپراتور انسانی نیاز دارد. لطفاً در صورت تمایل از گزینه «درخواست پاسخ انسانی» استفاده کنید.'; return 'پاسخ توسط هوش مصنوعی: درخواست شما دریافت شد. برای کمک دقیق‌تر، اطلاعات مربوط به محصول، سفارش یا مشکل را در همین تیکت بنویسید. اگر پاسخ نیازمند بررسی انسانی است، می‌توانید گزینه «درخواست پاسخ انسانی» را انتخاب کنید.'; }
}
