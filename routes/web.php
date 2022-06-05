<?php

use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Devices;
use App\Http\Livewire\EditDevice;
use App\Http\Livewire\Profile;
use App\Http\Livewire\Rooms;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/rooms', Rooms::class)->name('rooms');
    Route::get('/devices', Devices::class)->name('devices');
    Route::get('/devices/edit/{id?}', EditDevice::class)->name('devices.edit.id?');
    Route::get('/profile', Profile::class)->name('profile');
});
