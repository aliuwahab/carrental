<?php

use App\Livewire\HomePage;
use App\Livewire\VehicleListing;
use App\Livewire\VehicleDetail;
use App\Livewire\CreateBooking;
use App\Livewire\BookingHistory;
use App\Livewire\Dashboard;
use App\Livewire\RentalProperties;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

// Public routes
Route::get('/', HomePage::class)->name('home');
Route::get('/vehicles', VehicleListing::class)->name('vehicles.index');
Route::get('/vehicles/{vehicle:slug}', VehicleDetail::class)->name('vehicles.show');
Route::get('/book/{vehicle:slug}', CreateBooking::class)->name('booking.create');
Route::get('/rent-a-home', RentalProperties::class)->name('rental.properties');


Route::get('dashboard', Dashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/bookings', '/dashboard')->name('bookings.index');
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Log viewer - Dev only (aliuwahab@gmail.com)
Route::middleware(['auth', 'dev'])->group(function () {
    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->name('logs');
});
