<?php

use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

Route::prefix('seller')->middleware(['auth','login_fail_check'])->group(function () {
    Route::prefix('ticket')->group(function () {
        Route::get('movie',[SellerController::class,'movie'])->name('seller#movie');
        Route::get('plan/{id}',[SellerController::class,'moviePlan'])->name('seller#moviePlan');
        Route::get('select/seat/{id}',[SellerController::class,'selectSeat'])->name('seller#selectSeat');
        Route::get('check/out',[SellerController::class,'checkOut'])->name('seller#checkOut');
        Route::post('order',[SellerController::class,'order'])->name('seller#order');
        Route::post('hall/rental',[SellerController::class,'orderHallRental'])->name('seller#orderHallRental');
    });

    Route::prefix('product')->group(function () {
        Route::get('cart',[SellerController::class,'cart'])->name('seller#cart');
        Route::post('check/out',[SellerController::class,'checkOutProduct'])->name('seller#checkOutProduct');
        Route::post('order',[SellerController::class,'orderProduct'])->name('seller#orderProduct');
   });
});
