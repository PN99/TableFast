<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\RestaurantSettings;
use App\Livewire\Admin\TableManager;
use App\Livewire\Admin\ZoneManager;
use App\Livewire\AdminPinPad;
use App\Livewire\ReservationWizard;
use App\Livewire\UserDashboard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

// Locale switcher – available to all (guest and auth)
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, ['cs', 'en'])) {
        if (Auth::check()) {
            Auth::user()->update(['locale' => $locale]);
        }
        session(['locale' => $locale]);
    }
    return redirect()->back()->withHeaders(['Cache-Control' => 'no-store']);
})->name('locale.set');

// Rezervační wizard – přístupný všem, auth se řeší uvnitř
Route::get('/rezervace', ReservationWizard::class)->name('reservations.create');

Route::get('dashboard', UserDashboard::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin PIN login
Route::get('adm', AdminPinPad::class)
    ->name('admin.login');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('settings', RestaurantSettings::class)->name('settings');
    Route::get('zones', ZoneManager::class)->name('zones');
    Route::get('zones/{zone}/tables', TableManager::class)->name('zones.tables');
});

require __DIR__.'/auth.php';
