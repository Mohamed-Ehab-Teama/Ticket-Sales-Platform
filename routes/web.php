<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventDateController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Auth\RegisteredUserController;

Route::get('/', function () {
    return view('index');
});

// ===================================  Breeze
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// ===================================  Breeze




// ===================================  Admin Routes
Route::middleware(['auth', 'can:admin'])
    ->prefix('admin')->name('admin.')->group(function () {

        Route::view('/', 'index')->name('dashboard');

        Route::resource('events', EventController::class)
            ->except(['create', 'show']);


        Route::prefix('events/{event}')->group(function () {

            Route::resource('dates', EventDateController::class)
                ->except(['create', 'show']);

            Route::resource('dates.time-slots', TimeSlotController::class)
                ->except(['create', 'show']);     // New Learned

            Route::resource('ticket-type', TicketTypeController::class)
                ->parameters(['ticket-type' => 'ticketType'])
                ->except(['create', 'show']);
        });


        Route::get('/inventories', [InventoryController::class, 'index'])
            ->name('inventories.index');
        Route::patch('inventories/{inventory}/update-quantity', [InventoryController::class, 'updateQuantity'])
            ->name('inventories.updateQuantity');




        // 
    });
// ===================================  Admin Routes





require __DIR__ . '/auth.php';
