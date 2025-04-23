<?php

use App\Http\Controllers\ParseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PharmacyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/fetch-pharmacies', [PharmacyController::class, 'fetchPharmacies']);

Route::prefix('api/pharmacy')->group(function () {
    Route::get('/parse/{config}', [ParseController::class, 'parse'])
        ->where('config', '[0-9]+')
        ->name('pharmacy.parse');

    Route::get('/parse-all', [ParseController::class, 'parseAllActive'])
        ->name('pharmacy.parse-all');
});
