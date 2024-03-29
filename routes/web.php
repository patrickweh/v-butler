<?php

use App\Livewire\Dashboard;
use App\Livewire\DeviceDetail;
use App\Livewire\Devices;
use App\Livewire\EditDevice;
use App\Livewire\EditRoom;
use App\Livewire\Profile;
use App\Livewire\Rooms;
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
    Route::get('/rooms/edit/{roomModel?}', EditRoom::class)->name('rooms.edit.id?');
    Route::get('/devices/{room?}', Devices::class)->name('devices');
    Route::get('/device/{device}', DeviceDetail::class)->name('device.id');
    Route::get('/device/edit/{id?}', EditDevice::class)->name('devices.edit.id?');
    Route::get('/profile', Profile::class)->name('profile');
});
