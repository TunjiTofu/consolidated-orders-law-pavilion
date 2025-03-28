<?php

use App\Http\Controllers\ConsolidatedOrderController;
use Illuminate\Support\Facades\Route;

Route::controller(ConsolidatedOrderController::class)->group(function () {
    Route::post('import', 'importData');
    Route::get('export', 'exportData');
});
