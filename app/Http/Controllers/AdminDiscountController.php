<?php
namespace App\Http\Controllers;
use App\Models\DiscountCode;use App\Models\User;use Illuminate\Http\Request;
class AdminDiscountController extends Controller
{
 public function index(){return view('admin.discounts.index',['codes'=>DiscountCode::with('targetUser')->latest()->get(),'customers'=>User::where('is_active',true)->orderBy('first_name')->limit(100)->get()]);}
 public function create(){return view('admin.discounts.create');}
 public function store(Request $request){$data=$this->validateData($request);DiscountCode::create(['code'=>strtoupper(trim($data['code'])),'amount'=>$data['amount'],'is_percent'=>$request->boolean('is_percent'),'max_uses'=>$data['max_uses']??null,'used_count'=>0,'is_active'=>$request->boolean('is_active',true),'starts_at'=>$data['starts_at']??null,'expires_at'=>$data['expires_at']??null]);return redirect()->route('admin.discounts.index')->with('success','کد تخفیف ایجاد شد.');}
 public function edit(DiscountCode $discount){return view('admin.discounts.edit',compact('discount'));}
 public function update(Request $request,DiscountCode $discount){$data=$this->validateData($request,$discount);$discount->update(['code'=>strtoupper(trim($data['code'])),'amount'=>$data['amount'],'is_percent'=>$request->boolean('is_percent'),'max_uses'=>$data['max_uses']??null,'is_active'=>$request->boolean('is_active'),'starts_at'=>$data['starts_at']??null,'expires_at'=>$data['expires_at']??null]);return redirect()->route('admin.discounts.index')->with('success','کد تخفیف ویرایش شد.');}
 public function toggle(DiscountCode $discount){$discount->update(['is_active'=>!$discount->is_active]);return back()->with('success',$discount->is_active?'کد تخفیف فعال شد.':'کد تخفیف غیرفعال شد.');}
 public function destroy(DiscountCode $discount){$discount->delete();return back()->with('success','کد تخفیف حذف شد.');}
 private function validateData(Request $request,?DiscountCode $discount=null):array{$unique='unique:discount_codes,code'.($discount?','.$discount->id:'');$data=$request->validate(['code'=>['required','string','max:50','regex:/^[A-Za-z0-9_-]+$/',$unique],'amount'=>['required','numeric','min:0'],'max_uses'=>['nullable','integer','min:1'],'starts_at'=>['nullable','date'],'expires_at'=>['nullable','date','after_or_equal:starts_at']]);if($request->boolean('is_percent')&&$data['amount']>100)abort(redirect()->back()->withErrors(['amount'=>'درصد تخفیف نمی‌تواند بیشتر از 100 باشد.'])->withInput());return $data;}
}
