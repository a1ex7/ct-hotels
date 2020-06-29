<?php

    use Illuminate\Support\Facades\Route;


    Route::prefix('v1')->group(function () {

        Route::apiResource('hotels', 'HotelsController');
        Route::apiResource('rooms', 'RoomsController');
        Route::apiResource('reservations', 'ReservationsController');

    });
