<?php

use App\Http\Controllers\ConJobController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\MovieSeatController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\WebsiteInfoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('change/status',[MovieSeatController::class,'changeStatus'])->name('changeStatus');
Route::get('check/promotiton',[PromotionController::class,'checkPromotion'])->name('checkPromotion');

Route::prefix('conjob/check')->group(function () {
    Route::get('movie',[ConJobController::class,'chackMovie']);
    Route::get('movie_plan',[ConJobController::class,'checkMoviePlan']);
    Route::get('movie_seat',[ConJobController::class,'checkMovieSeat']);
    Route::get('order',[ConJobController::class,'checkOrder']);
    Route::get('promotion',[ConJobController::class,'checkPromotion']);
});

Route::get('cart/filter/product',[SellerController::class,'filterProduct'])->name('filterProduct');

Route::get('get/website',[WebsiteInfoController::class,'getWebsite'])->name('getWebsiteInfo');
Route::get('get/contact',[ContactController::class,'getContact'])->name('getContact');
