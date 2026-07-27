<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CinemaController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SeatPlanController;
use Illuminate\Support\Facades\Route;

Route::get('/dashboard',[AdminController::class,'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');
Route::prefix('admin')->middleware(['auth','login_fail_check'])->group(function () {

    Route::get('list',[AdminController::class,'list'])->name('admin#list');
    Route::post('add',[AdminController::class,'add'])->name('admin#add');
    Route::get('view/{id}',[AdminController::class,'view'])->name('admin#view');
    Route::patch('update',[AdminController::class,'updateAdmin'])->name('admin#update');
    Route::delete('delete',[AdminController::class,'delete'])->name('admin#delete')->middleware('verify_admin');

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
        Route::delete('/',[MovieController::class,'delete'])->name('admin#movieDelete');

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

});
