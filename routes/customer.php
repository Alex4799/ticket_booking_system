<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MoviePlanController;
use App\Http\Controllers\PromotionController;
use Illuminate\Support\Facades\Route;

Route::get('/',[CustomerController::class,'home'])->name('customer#home');

Route::prefix('movie')->group(function () {
    Route::get('/',[CustomerController::class,'movieList'])->name('customer#movieList');
    Route::get('/{id}',[CustomerController::class,'movie_view'])->name('customer#movieView');
    Route::get('/book/{id}',[CustomerController::class,'movie_book'])->name('customer#movieBook');
    Route::get('/select/seat/{id}',[CustomerController::class,'movie_select_seat'])->name('customer#movieSelectSeat');
    });

Route::prefix('order/customer')->group(function () {
    Route::get('/check/out',[CustomerController::class,'movie_checkout'])->name('customer#movieCheckout');
    Route::get('/verify',[CustomerController::class,'vreify'])->name('customer#verify');
    Route::post('/manual',[CustomerController::class,'manual_order'])->name('customer#manualOrder');
    Route::post('/',[CustomerController::class,'order'])->name('customer#order');
    Route::post('/book',[CustomerController::class,'book'])->name('customer#book');
    Route::get('/summary/{id}',[CustomerController::class,'summary'])->name('customer#orderSummary');
    Route::post('/finish',[CustomerController::class,'finish'])->name('customer#orderFinish');
    Route::get('/check',[CustomerController::class,'orderCheckPage_customer'])->name('customer#checkOrderPage');
    Route::post('/check',[CustomerController::class,'orderCheck_customer'])->name('customer#checkOrder');

});

Route::prefix('time/table')->group(function () {
    Route::get('/',[MoviePlanController::class,'cinemaList_customer'])->name('customer#timeTableCinema');
    Route::get('/{id}',[MoviePlanController::class,'timeTable_customer'])->name('customer#timeTable');
});

Route::prefix('promotion/customer')->group(function () {
    Route::get('/',[PromotionController::class,'list_customer'])->name('customer#promotionList');
    Route::get('/{id}',[PromotionController::class,'view_customer'])->name('customer#promotionView');
});

Route::prefix('about')->group(function () {
    Route::get('/',[CustomerController::class,'about'])->name('customer#aboutUs');
});

Route::prefix('contact')->group(function () {
    Route::get('/',[ContactController::class,'contact'])->name('customer#contactPage');
    Route::post('send',[ContactController::class,'send'])->name('customer#contactSend');
    Route::get('cinema/{id}',[ContactController::class,'cinemaView'])->name('customer#cinemaView');
    Route::post('review/send',[ContactController::class,'reviewSend_customer'])->name('customer#reviewSend');
});
