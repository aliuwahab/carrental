<?php

use App\Livewire\HomePage;
use App\Livewire\VehicleListing;
use App\Livewire\VehicleDetail;
use App\Livewire\CreateBooking;
use App\Livewire\BookingHistory;
use App\Livewire\Dashboard;
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

// Debug route for admin access
Route::get('/debug-admin', function () {
    if (!auth()->check()) {
        return 'Not authenticated';
    }
    
    $user = auth()->user();
    return [
        'authenticated' => true,
        'user_id' => $user->id,
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_role' => $user->role,
        'is_admin' => $user->isAdmin(),
        'session_id' => session()->getId(),
    ];
})->middleware('auth');

// Debug route for admin middleware
Route::get('/debug-admin-middleware', function () {
    return 'Admin middleware passed! User: ' . auth()->user()->name;
})->middleware(['auth', 'admin']);

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
