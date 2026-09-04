<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReaderSubscriptionController;
use App\Http\Controllers\AdminPanelController;
Route::middleware('auth')->prefix('account/reader')->group(function(){Route::get('/',[ReaderSubscriptionController::class,'index'])->name('account.reader');Route::post('/subscribe',[ReaderSubscriptionController::class,'purchase'])->name('account.reader.purchase');});
Route::middleware(['auth','role:admin'])->prefix('admin')->group(function(){
foreach(['users','products','orders','finance','subscriptions','assets','reader','loyalty','content','marketing','ai','support','notifications','reports','storage','search','sellers','security','settings','system','newsletter','sms'] as $module){Route::get('/'.$module.'/overview',[AdminPanelController::class,'module'])->defaults('module',$module)->name('admin.module.'.$module);Route::get('/'.$module.'/{sub}',[AdminPanelController::class,'module'])->defaults('module',$module)->name('admin.module.'.$module.'.sub');}
Route::get('/'.$module.'/overview',[AdminPanelController::class,'module'])->defaults('module','system');
});