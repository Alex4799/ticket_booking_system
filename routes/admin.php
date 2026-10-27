<?php

use App\Events\OrderAlertEvent;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MoviePlanController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RecycleController;
use App\Http\Controllers\ReduceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SeatPlanController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\StoreItemController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WebsiteInfoController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard',[AdminController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::prefix('admin')->middleware(['auth','login_fail_check'])->group(function () {

    Route::get('list',[AdminController::class,'list'])->name('admin#list');
    Route::post('add',[AdminController::class,'add'])->name('admin#add');
    Route::get('view/{id}',[AdminController::class,'view'])->name('admin#view');
    Route::patch('update',[AdminController::class,'updateAdmin'])->name('admin#update');
    Route::delete('delete',[AdminController::class,'delete'])->name('admin#delete')->middleware('verify_admin');

    Route::prefix('report')->group(function () {
        Route::get('ticket',[ReportController::class,'ticket'])->name('admin#reportTicket');
        Route::get('graph',[ReportController::class,'graph'])->name('admin#reportGraph');
        Route::get('product',[ReportController::class,'product'])->name('admin#reportProduct');
    });

    Route::prefix('profile')->group(function () {
        Route::get('/',[AdminController::class,'profile'])->name('admin#profile');
        Route::patch('update',[AdminController::class,'update'])->name('admin#profileUpdate');
        Route::post('change/password',[AdminController::class,'changePassword'])->name('admin#changePassword');
    });

    Route::prefix('cinema')->group(function () {
        Route::get('/',[CinemaController::class,'list'])->name('admin#cinemaList');
        Route::post('/',[CinemaController::class,'create'])->name('admin#cinemaCreate');
        Route::get('view/{id}',[CinemaController::class,'view'])->name('admin#cinemaView');
        Route::patch('/',[CinemaController::class,'update'])->name('admin#cinemaUpdate');
        Route::delete('/',[CinemaController::class,'delete'])->name('admin#cinemaDelete')->middleware('verify_admin');

        Route::prefix('image')->group(function () {
            Route::get('/{id}',[CinemaController::class,'image_edit'])->name('admin#cinemaImageEdit');
            Route::post('/',[CinemaController::class,'image_create'])->name('admin#cinemaImageCreate');
            Route::patch('/',[CinemaController::class,'image_update'])->name('admin#cinemaImageUpdate');
            Route::get('/{id}/{index}',[CinemaController::class,'image_delete'])->name('admin#cinemaImageDelete');
        });
    });

    Route::prefix('hall')->group(function () {
        Route::get('/',[HallController::class,'list'])->name('admin#hallList');
        Route::post('/',[HallController::class,'create'])->name('admin#hallCreate');
        Route::get('view/{id}',[HallController::class,'view'])->name('admin#hallView');
        Route::patch('/',[HallController::class,'update'])->name('admin#hallUpdate');
        Route::delete('/',[HallController::class,'delete'])->name('admin#hallDelete')->middleware('verify_admin');
        Route::post('/duplicate',[HallController::class,'duplicate'])->name('admin#hallDuplicate')->middleware('verify_admin');
        Route::post('replace/seat_plan',[HallController::class,'replaceSeatPlan'])->name('admin#replaceSeatPlan');

        Route::prefix('seat')->group(function () {
            Route::post('/',[SeatController::class,'create'])->name('admin#seatCreate');
            Route::get('/{id}',[SeatController::class,'view'])->name('admin#seatView');
            Route::patch('/',[SeatController::class,'update'])->name('admin#seatUpdate');
            Route::delete('/',[SeatController::class,'delete'])->name('admin#seatDelete')->middleware('verify_admin');
        });
    });

    Route::prefix('seat_plan')->group(function () {
        Route::get('/',[SeatPlanController::class,'list'])->name('admin#seatPlanList');
        Route::post('/',[SeatPlanController::class,'create'])->name('admin#seatPlanCreate');
        Route::get('view/{id}',[SeatPlanController::class,'view'])->name('admin#seatPlanView');
        Route::patch('/',[SeatPlanController::class,'update'])->name('admin#seatPlanUpdate');
        Route::delete('/',[SeatPlanController::class,'delete'])->name('admin#seatPlanDelete')->middleware('verify_admin');
    });

    Route::prefix('genre')->group(function () {
        Route::get('/',[GenreController::class,'list'])->name('admin#genreList');
        Route::post('/',[GenreController::class,'create'])->name('admin#genreCreate');
        Route::patch('/',[GenreController::class,'update'])->name('admin#genreUpdate');
        Route::delete('/',[GenreController::class,'delete'])->name('admin#genreDelete')->middleware('verify_admin');
    });

    Route::prefix('movie')->group(function () {
        Route::get('/',[MovieController::class,'list'])->name('admin#movieList');
        // Route::get('/',[MovieController::class,'createPage'])->name('admin#movieCreatePage');
        Route::post('/',[MovieController::class,'create'])->name('admin#movieCreate');
        Route::get('view/{id}',[MovieController::class,'view'])->name('admin#movieView');
        Route::patch('/',[MovieController::class,'update'])->name('admin#movieUpdate');
        Route::delete('/',[MovieController::class,'delete'])->name('admin#movieDelete')->middleware('verify_admin');

        Route::prefix('extra/edit')->group(function () {
            Route::get('/{id}',[MovieController::class,'extraEdit'])->name('admin#movieExtraEdit');

            Route::prefix('genre')->group(function () {
                Route::post('add',[MovieController::class,'genreAdd'])->name('admin#movieGenreAdd');
                Route::get('delete/{id}/{index}',[MovieController::class,'genreDelete'])->name('admin#movieGenreDelete');
            });

            Route::prefix('cast')->group(function () {
                Route::post('add',[MovieController::class,'castAdd'])->name('admin#movieCastAdd');
                Route::patch('update',[MovieController::class,'castUpdate'])->name('admin#movieCastUpdate');
                Route::get('delete/{id}/{index}',[MovieController::class,'castDelete'])->name('admin#movieCastdelete');
            });

            Route::prefix('cover')->group(function () {
                Route::post('add',[MovieController::class,'coverAdd'])->name('admin#movieCoverAdd');
                Route::patch('update',[MovieController::class,'coverUpdate'])->name('admin#movieCoverupdate');
                Route::get('delete/{id}/{index}',[MovieController::class,'coverDelete'])->name('admin#movieCoverDelete');
            });
        });
    });

    Route::prefix('movie_plan')->group(function () {
        Route::get('cinema',[MoviePlanController::class,'cinemaList'])->name('admin#moviePlanCinemaList');
        Route::get('hall/{id}',[MoviePlanController::class,'hallList'])->name('admin#moviePlanHallList');
        Route::get('/{id}',[MoviePlanController::class,'list'])->name('admin#moviePlanList');
        Route::post('/',[MoviePlanController::class,'create'])->name('admin#moviePlanCreate');
        Route::get('view/{id}',[MoviePlanController::class,'view'])->name('admin#moviePlanView');
        Route::patch('update',[MoviePlanController::class,'update'])->name('admin#moviePlanUpdate');
        Route::delete('delete',[MoviePlanController::class,'delete'])->name('admin#moviePlanDelete')->middleware('verify_admin');

        Route::prefix('movie/seat')->group(function () {
            Route::get('view/{id}',[MoviePlanController::class,'viewMovieSeat'])->name('admin#viewMovieSeat');
            Route::patch('update',[MoviePlanController::class,'updateMovieSeat'])->name('admin#updateMovieSeat');
            Route::post('replace/price',[MoviePlanController::class,'replacePrice'])->name('admin#replacePriceMovieSeat');
        });
    });

    Route::prefix('social')->group(function () {
        Route::get('/',[SocialController::class,'list'])->name('admin#socialList');
        Route::post('/',[SocialController::class,'create'])->name('admin#socialCreate');
        Route::patch('/',[SocialController::class,'update'])->name('admin#socialUpdate');
        Route::delete('/',[SocialController::class,'delete'])->name('admin#socialDelete')->middleware('verify_admin');
    });

    Route::prefix('promotion')->group(function () {
        Route::get('/',[PromotionController::class,'list'])->name('admin#promotionList');
        Route::post('/',[PromotionController::class,'create'])->name('admin#promotionCreate');
        Route::get('/{id}',[PromotionController::class,'view'])->name('admin#promotionView');
        Route::patch('/',[PromotionController::class,'update'])->name('admin#promotionUpdate');
        Route::delete('/',[PromotionController::class,'delete'])->name('admin#promotionDelete')->middleware('verify_admin');
    });

    Route::prefix('payment')->group(function () {
        Route::get('/',[PaymentController::class,'list'])->name('admin#paymentList');
        Route::post('/',[PaymentController::class,'create'])->name('admin#paymentCreate');
        Route::get('/{id}',[PaymentController::class,'view'])->name('admin#paymentView');
        Route::patch('/',[PaymentController::class,'update'])->name('admin#paymentUpdate');
        Route::delete('/',[PaymentController::class,'delete'])->name('admin#paymentDelete')->middleware('verify_admin');
    });

    Route::prefix('website')->group(function () {
        Route::get('/',[WebsiteInfoController::class,'view'])->name('admin#websiteView');
        Route::patch('update',[WebsiteInfoController::class,'update'])->name('admin#websiteUpdate');
        Route::get('extra/edit',[WebsiteInfoController::class,'extraEdit'])->name('admin#websiteExtraEdit');

        Route::prefix('cover')->group(function () {
            Route::post('add',[WebsiteInfoController::class,'addCover'])->name('admin#websiteCoverAdd');
            Route::patch('update',[WebsiteInfoController::class,'updateCover'])->name('admin#websiteCoverUpdate');
            Route::get('delete/{index}',[WebsiteInfoController::class,'deleteCover'])->name('admin#websiteCoverDelete');
        });

        Route::prefix('about_us')->group(function(){
            Route::post('add',[WebsiteInfoController::class,'addAbout'])->name('admin#websiteAboutAdd');
            Route::patch('update',[WebsiteInfoController::class,'updateAbout'])->name('admin#websiteAboutUpdate');
            Route::get('delete/{index}',[WebsiteInfoController::class,'deleteAbout'])->name('admin#websiteAboutDelete');
        });

    });

    Route::prefix('recycle_bin')->group(function () {
        Route::get('/',[RecycleController::class,'list'])->name('admin#recycle');
        Route::get('/view/{item}',[RecycleController::class,'view'])->name('admin#recycleView');

        Route::prefix('cinema')->group(function () {
            Route::get('/restore/{id}',[RecycleController::class,'cinemaRestore'])->name('admin#recycleCinemaRestore');
            Route::get('/delete/{id}',[RecycleController::class,'cinemaDelete'])->name('admin#recycleCinemaDelete');
        });

        Route::prefix('hall')->group(function () {
            Route::get('/restore/{id}',[RecycleController::class,'hallRestore'])->name('admin#recycleHallRestore');
            Route::get('/delete/{id}',[RecycleController::class,'hallDelete'])->name('admin#recycleHallDelete');
        });

        Route::prefix('genre')->group(function(){
            Route::get('/restore/{id}',[RecycleController::class,'genreRestore'])->name('admin#recycleGenreRestore');
            Route::get('delete/{id}',[RecycleController::class,'genreDelete'])->name('admin#recycleGenreDelete');
        });

        Route::prefix('movie')->group(function(){
            Route::get('/restore/{id}',[RecycleController::class,'movieRestore'])->name('admin#recycleMovieRestore');
            Route::get('/delete/{id}',[RecycleController::class,'movieDelete'])->name('admin#recycleMovieDelete');
        });

        Route::prefix('seat_plan')->group(function () {
            Route::get('restore/{id}',[RecycleController::class,'seatPlanRestore'])->name('admin#recycleSeatPlanRestore');
            Route::get('delete/{id}',[RecycleController::class,'seatPlanDelete'])->name('admin#recycleSeatPlanDelete');
        });

    });

    Route::prefix('order')->group(function () {
        Route::get('list',[OrderController::class,'list'])->name('admin#orderList');
        Route::get('view/{id}',[OrderController::class,'view'])->name('admin#orderView');
        Route::post('change/status',[OrderController::class,'changeStatus'])->name('admin#orderChangeStatus');
        Route::get('invoice/{id}',[OrderController::class,'invoice'])->name('admin#invoice');
        Route::get('get',[OrderController::class,'orderPendingGet_admin'])->name('admin#orderPendingGet');

        Route::prefix('product')->group(function () {
            Route::get('/',[OrderController::class,'list_product'])->name('admin#orderListProduct');
            Route::get('/{id}',[OrderController::class,'view_product'])->name('admin#orderViewProduct');
            Route::post('change/status',[OrderController::class,'changeStatus_product'])->name('admin#orderChangeStatusProduct');
            Route::get('invoice/{id}',[OrderController::class,'invoiceProduct'])->name('admin#invoiceProduct');
        });

        Route::prefix('log')->group(function () {
            Route::get('list',[OrderController::class,'orderLog'])->name('admin#orderLog');
        });
    });

    Route::prefix('product')->group(function () {
        Route::prefix('category')->group(function () {
            Route::get('/',[CategoryController::class,'list'])->name('admin#categoryList');
            Route::post('/',[CategoryController::class,'create'])->name('admin#categoryCreate');
            Route::patch('/',[CategoryController::class,'update'])->name('admin#categoryUpdate');
            Route::delete('/',[CategoryController::class,'delete'])->name('admin#categoryDelete')->middleware('verify_admin');
        });

        Route::get('/',[ProductController::class,'list'])->name('admin#productList');
        Route::post('/',[ProductController::class,'create'])->name('admin#productCreate');
        Route::get('/view/{id}',[ProductController::class,'view'])->name('admin#productView');
        Route::patch('/',[ProductController::class,'update'])->name('admin#productUpdate');
        Route::delete('/',[ProductController::class,'delete'])->name('admin#productDelete')->middleware('verify_admin');
    });

    Route::prefix('supplier')->group(function () {
        Route::get('/',[SupplierController::class,'list'])->name('admin#supplierList');
        Route::post('/',[SupplierController::class,'create'])->name('admin#supplierCreate');
        Route::patch('/',[SupplierController::class,'update'])->name('admin#supplierUpdate');
        Route::delete('/',[SupplierController::class,'delete'])->name('admin#supplierDelete')->middleware('verify_admin');
    });

    Route::prefix('store')->group(function () {
        Route::get('/',[StoreController::class,'list'])->name('admin#storeList');
        Route::post('/',[StoreController::class,'create'])->name('admin#storeCreate');
        Route::get('/view/{id}',[StoreController::class,'view'])->name('admin#storeView');
        Route::patch('/',[StoreController::class,'update'])->name('admin#storeUpdate');
        Route::delete('/',[StoreController::class,'delete'])->name('admin#storeDelete')->middleware('verify_admin');

        Route::prefix('item')->group(function(){
            Route::get('/{id}',[StoreItemController::class,'view'])->name('admin#storeItemView');
            Route::post('/',[StoreItemController::class,'create'])->name('admin#storeItemCreate');
            Route::patch('/',[StoreItemController::class,'update'])->name('admin#storeItemUpdate');
            Route::post('/reduce/damage',[StoreItemController::class,'damageReduce'])->name('admin#storeItemDamage');
            Route::post('/reduce',[StoreItemController::class,'reduce'])->name('admin#storeItemReduce');
        });
    });

    Route::prefix('purchase')->group(function () {
        Route::get('/',[PurchaseController::class,'list'])->name('admin#purchaseList');
        Route::post('/',[PurchaseController::class,'create'])->name('admin#purchaseCreate');
        Route::get('/view/{id}',[PurchaseController::class,'view'])->name('admin#purchaseView');
        Route::patch('/',[PurchaseController::class,'update'])->name('admin#purchaseUpdate');
        Route::delete('/',[PurchaseController::class,'delete'])->name('admin#purchaseDelete')->middleware('verify_admin');
        Route::get('change/status/{id}/{status}',[PurchaseController::class,'changeStatus'])->name('admin#purchaseChangeStatus');
    });

    Route::prefix('reduce')->group(function(){
        Route::get('/',[ReduceController::class,'list'])->name('admin#reduceList');
        Route::delete('/',[ReduceController::class,'delete'])->name('admin#reduceDelete');

        Route::prefix('damage')->group(function(){
            Route::get('/',[ReduceController::class,'list_damage'])->name('admin#reduceDamageList');
            Route::delete('/',[ReduceController::class,'delete_damage'])->name('admin#reduceDamageDelete');
        });
    });

    Route::prefix('inventory')->group(function () {
        Route::get('/',[InventoryController::class,'list'])->name('admin#inventoryList');
        Route::post('/',[InventoryController::class,'create'])->name('admin#inventoryCreate');
        Route::get('/view/{id}',[InventoryController::class,'view'])->name('admin#inventoryView');
        Route::get('/change/{id}/{status}',[InventoryController::class,'change'])->name('admin#inventoryChangeStatus');
        Route::delete('/',[InventoryController::class,'delete'])->name('admin#inventoryDelete');
    });

    Route::prefix('customer/report')->group(function () {
        Route::get('list',[ContactController::class,'list_admin'])->name('admin#customerReportList');
        Route::get('view/{id}',[ContactController::class,'view_admin'])->name('admin#customerReportView');
        Route::post('reply',[ContactController::class,'reply_admin'])->name('admin#customerReportReply');
    });

});

Route::get('test',function(){
    event(new OrderAlertEvent(2));
    dd('Success');
});