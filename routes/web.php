<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoadDepositController;
use App\Http\Controllers\ExtratoController;
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
/*
Route::get('/', function () {
    return view('auth.login');
});
*/
//Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->middleware('auth')->name('home');

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
/*
Route::get('/', function () {
    return view('home'); 
});

*/

//Route::post('/loadDeposit',  [LoadDepositController::class, 'getLoadDeposit']);

Route::post('/loadDeposit',  [LoadDepositController::class, 'getLoadDeposit']);

Route::post('/loadExtract', [ExtratoController::class, 'loadDeposit'])->name('loadExtract');

//Route::get('/extrato',  [LoadDepositController::class, 'getLoadDeposit']);

Route::get('/extrato', function () {
    return view('extrato'); 
});



