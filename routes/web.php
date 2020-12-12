<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/coinsBox/{user}', [\App\Http\Controllers\CoinBoxController::class, 'index']);
Route::get('/buy', [\App\Http\Controllers\CoinBoxController::class, 'createBuy']);
Route::post('/buy', [\App\Http\Controllers\CoinBoxController::class, 'storeBuy']);
Route::post('/coin/sell/{id}', [\App\Http\Controllers\CoinBoxController::class, 'sellBuy']);
Route::post('/coin/sell/manual/{id}', [\App\Http\Controllers\CoinBoxController::class, 'sellManualBuy']);
Route::delete('/coin/delete/{id}', [\App\Http\Controllers\CoinBoxController::class, 'destroyBuy']);
Route::get('/coin/bot/box/dashboard', [\App\Http\Controllers\CoinsBotDefaultBoxController::class, 'dashboardBot']);
Route::get('/coin/bot/box', [\App\Http\Controllers\CoinsBotDefaultBoxController::class, 'index']);
Route::post('/coin/bot/sell/{id}', [\App\Http\Controllers\CoinsBotDefaultBoxController::class, 'sellManual']);
Route::get('/coin/bot/box/setting', function () {
    return view('buyCoinBotDefaultBox');
});
Route::post('/coin/bot/box', [\App\Http\Controllers\CoinsBotDefaultBoxController::class, 'storeSetting']);
