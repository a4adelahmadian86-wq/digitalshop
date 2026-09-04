<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReaderSubscriptionController;
use App\Http\Controllers\AdminPanelController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AdminAiSettingsController;
Route::middleware('auth')->prefix('account/reader')->group(function(){Route::get('/',[ReaderSubscriptionController::class,'index'])->name('account.reader');Route::post('/subscribe',[ReaderSubscriptionController::class,'purchase'])->name('account.reader.purchase');});
Route::post('/newsletter/subscribe',[NewsletterController::class,'subscribe'])->name('newsletter.subscribe');
Route::middleware(['auth','role:admin','admin.permission'])->prefix('admin')->group(function(){
 Route::get('/ai/settings',[AdminAiSettingsController::class,'edit'])->name('admin.ai.settings');Route::put('/ai/settings',[AdminAiSettingsController::class,'update'])->name('admin.ai.settings.update');
 foreach(['users','products','orders','finance','subscriptions','assets','reader','loyalty','content','marketing','ai','support','notifications','reports','storage','search','sellers','workflow','tasks','security','integrations','settings','system','developer','newsletter','sms'] as $module){Route::get('/'.$module.'/overview',[AdminPanelController::class,'module'])->defaults('module',$module)->name('admin.module.'.$module);Route::get('/'.$module.'/{sub}',[AdminPanelController::class,'module'])->defaults('module',$module)->name('admin.module.'.$module.'.sub');}
});