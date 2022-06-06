<?php

use App\Http\Controllers\Services\DoorbirdController;
use App\Http\Controllers\Services\NukiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/doorbird/trigger', [DoorbirdController::class, 'trigger'])->name('doorbird.trigger');
Route::post('/nuki/trigger', [NukiController::class, 'trigger'])->name('nuki.trigger');

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/nuki/{device}/on', [NukiController::class, 'on'])->name('nuki.on');
});
