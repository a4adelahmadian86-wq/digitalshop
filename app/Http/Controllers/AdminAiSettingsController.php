<?php
namespace App\Http\Controllers;
use App\Services\AI\AiSettingsStore;use Illuminate\Http\Request;
class AdminAiSettingsController extends Controller
{
 public function edit(){abort_unless(auth()->user()?->hasRole('admin'),403);return view('admin.ai.settings');}
 public function update(Request $request,AiSettingsStore $store){abort_unless(auth()->user()?->hasRole('admin'),403);$data=$request->validate(['provider'=>'required|in:gemini','gemini_key'=>'nullable|string|max:500','fallback_provider'=>'nullable|in:gapgpt','fallback_key'=>'nullable|string|max:500','fallback_endpoint'=>'nullable|url|max:500','fallback_model'=>'nullable|string|max:120','timeout'=>'required|integer|min:5|max:120']);$store->put('provider',$data['provider']);$store->put('key',$data['gemini_key']?:null,true);$store->put('fallback_provider',$data['fallback_provider']?:null);$store->put('fallback_key',$data['fallback_key']?:null,true);$store->put('fallback_endpoint',$data['fallback_endpoint']?:null);$store->put('fallback_model',$data['fallback_model']?:null);$store->put('timeout',(string)$data['timeout']);return back()->with('success','تنظیمات هوش مصنوعی با موفقیت ذخیره شد.');}
}