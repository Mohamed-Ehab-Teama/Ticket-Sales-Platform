<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\EventDateController;
use App\Http\Controllers\Admin\InventoryController;
use App\Http\Controllers\Admin\TicketTypeController;
use App\Http\Controllers\Admin\TimeSlotController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Customer\BookingController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Customer\CheckoutController;
use App\Http\Controllers\Paypal\PayPalController;
use App\Http\Controllers\VerifyTicketController;

// Route::get('/', function () {
//     return view('customer.index');
// });
Route::get('/', function () {
    return view('welcome');
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



// ===================================  Customer Routes
// Route::prefix('guest')->group(function () {
// });

Route::middleware(['auth', 'can:customer'])
    ->prefix('book')->name('book.')->group(function () {

        Route::get('/', [BookingController::class, 'index'])->name('index'); // main page

        // AJAX endpoints
        Route::get('/events', [BookingController::class, 'getEvents'])->name('events');
        Route::get('/events/{event}/dates', [BookingController::class, 'getDates'])->name('dates');
        Route::get('/dates/{date}/timeslots', [BookingController::class, 'getTimeSlots'])->name('timeslots');
        Route::get('/timeslots/{timeslot}/tickets', [BookingController::class, 'getTickets'])->name('tickets');
    });

Route::middleware(['auth', 'can:customer'])
    ->prefix('cart')->group(function () {

        Route::get('/', [CartController::class, 'index'])->name('cart.index');

        Route::post('/add', [CartController::class, 'add'])->name('cart.add');

        Route::post('/update', [CartController::class, 'update'])->name('cart.update');

        Route::delete('/remove/{item}', [CartController::class, 'remove'])->name('cart.remove');
    });
// ===================================  Customer Routes



// ===================================  Paypal Routes

Route::middleware(['auth'])->group(function () {

    // Checkout page
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // PayPal routes
    Route::get('/paypal/create/{order}', [PayPalController::class, 'create'])->name('paypal.create');
    Route::get('/paypal/success/{order}', [PayPalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel/{order}', [PayPalController::class, 'cancel'])->name('paypal.cancel');


    // Route::get('/checkout/paypal/{order}', [PayPalController::class, 'create'])
    //     ->name('paypal.create');
    // Route::get('/paypal/callback/success/{order}', [PayPalController::class, 'success'])
    //     ->name('paypal.success');
    // Route::get('/paypal/callback/cancel/{order}', [PayPalController::class, 'cancel'])
    //     ->name('paypal.cancel');
});
// ===================================  Paypal Routes


// QR Routes
Route::get('/tickets/verify/{code}', [VerifyTicketController::class, 'verify'])
    ->name('tickets.verify');




require __DIR__ . '/auth.php';
