<?php
namespace App\Http\Controllers;
use App\Models\SiteSetting;use Illuminate\Http\Request;
class AdminSiteSettingsController extends Controller
{
 public function edit(){abort_unless(auth()->user()?->hasPermission('settings.manage'),403);$settings=json_decode((string)SiteSetting::getValue('trust_badges','[]'),true)?:[];return view('admin.settings.general',compact('settings'));}
 public function update(Request $request){abort_unless(auth()->user()?->hasPermission('settings.manage'),403);$data=$request->validate(['badges'=>'array','badges.*.title'=>'nullable|string|max:100','badges.*.url'=>'nullable|url|max:500','badges.*.image'=>'nullable|url|max:500']);SiteSetting::putValue('trust_badges',json_encode(array_values($data['badges']??[]),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));return back()->with('success','اطلاعات نمادها و اعتماد فروشگاه ذخیره شد.');}
}