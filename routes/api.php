<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API VERSION 1 ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::prefix('orders')->group(function () {
        require __DIR__ . '/v1/orders/consolidated-orders.php';
    });

});
