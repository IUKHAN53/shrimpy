<?php

use App\Http\Controllers\HomeController;
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

Route::get('/', function (){
    return redirect(route('home'));
});
Route::get('/home', [HomeController::class,'index'])->name('home');
Route::get('/my-coins', [HomeController::class,'myCoins'])->name('my-coins');
Route::get('/binance-coins', [HomeController::class,'binanceCoins'])->name('binance-coins');

//API CALLS
Route::get('/get-accounts',[HomeController::class,'getAccounts'])->name('get-accounts');
Route::get('/get-portfolios',[HomeController::class,'getPortfolios'])->name('get-portfolios');
Route::get('/update-portfolios',[HomeController::class,'updatePortfolios'])->name('update-portfolios');
Route::get('/auto-trades',[HomeController::class,'autoTrades'])->name('auto-trades');

//my-coin
Route::get('/add-coin',[HomeController::class,'addCoin'])->name('add-coin');
Route::post('/add-coin',[HomeController::class,'addCoin'])->name('add-coin');
Route::get('/delete-coin',[HomeController::class,'deleteCoin'])->name('delete-coin');
Route::get('/edit-coin',[HomeController::class,'editCoin'])->name('edit-coin');
Route::post('/edit-coin',[HomeController::class,'editCoin'])->name('edit-coin');

//SplitShrimpy
Route::get('/update-action',[HomeController::class,'updateAction'])->name('update-action');



//sync binance coins
Route::get('/sync-binance-coins',[HomeController::class,'syncBinanceCoins'])->name('sync-binance-coins');
