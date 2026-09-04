<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReaderSubscriptionController;
Route::middleware('auth')->prefix('account/reader')->group(function(){Route::get('/',[ReaderSubscriptionController::class,'index'])->name('account.reader');Route::post('/subscribe',[ReaderSubscriptionController::class,'purchase'])->name('account.reader.purchase');});