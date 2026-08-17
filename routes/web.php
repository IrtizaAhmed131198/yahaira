<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TeamUserController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\SalesClosingController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MatchmakingController;
use App\Http\Controllers\ReportingController;
use App\Http\Controllers\FinancialController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/global-search', [DashboardController::class, 'globalSearch'])->name('global.search');
    Route::get('/dashboard/client-profile/{id}', [ClientController::class, 'show'])->name('client-profile');

    Route::middleware('role:admin|billing')->group(function () {
        Route::get('/dashboard/financial', [FinancialController::class, 'index'])->name('financial');
    });

    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/reporting', [ReportingController::class, 'index'])->name('reporting');

        Route::get('/dashboard/packages', [PackageController::class, 'index'])->name('packages');
        Route::get('/dashboard/packages/data', [PackageController::class, 'getPackages'])->name('packages.data');
        Route::post('/dashboard/packages', [PackageController::class, 'store'])->name('packages.store');
        Route::get('/dashboard/packages/{id}/edit', [PackageController::class, 'edit'])->name('packages.edit');
        Route::put('/dashboard/packages/{id}', [PackageController::class, 'update'])->name('packages.update');
        Route::delete('/dashboard/packages/{id}', [PackageController::class, 'destroy'])->name('packages.destroy');

        Route::get('/dashboard/team-and-user-management', [TeamUserController::class, 'index'])->name('team-and-user-management');
        Route::get('/dashboard/team-and-user-management/data', [TeamUserController::class, 'getUsers'])->name('team-users.data');
        Route::post('/dashboard/team-and-user-management', [TeamUserController::class, 'store'])->name('team-and-user-management.store');
        Route::get('/dashboard/team-and-user-management/{id}/edit', [TeamUserController::class, 'edit'])->name('team-and-user-management.edit');
        Route::put('/dashboard/team-and-user-management/{id}', [TeamUserController::class, 'update'])->name('team-and-user-management.update');
        Route::delete('/dashboard/team-and-user-management/{id}', [TeamUserController::class, 'destroy'])->name('team-and-user-management.destroy');
    });

    Route::middleware('role:admin|setter|closer')->group(function () {
        Route::get('/dashboard/lead-management', [LeadController::class, 'index'])->name('lead-management');
        Route::get('/dashboard/lead-management/data', [LeadController::class, 'getLeads'])->name('lead-management.data');
        Route::post('/dashboard/lead-management', [LeadController::class, 'store'])->name('lead-management.store');
        Route::get('/dashboard/lead-management/{id}', [LeadController::class, 'show'])->name('lead-management.show');
        Route::put('/dashboard/lead-management/{id}', [LeadController::class, 'update'])->name('lead-management.update');
        Route::post('/dashboard/lead-management/{id}/notes', [LeadController::class, 'addNote'])->name('lead-management.notes');
        Route::get('/dashboard/lead-management/{id}/notes-data', [LeadController::class, 'getNotesData'])->name('lead-management.notes-data');
    });
    Route::middleware('role:admin|closer')->group(function () {
        Route::get('/dashboard/client-intake-application', [ClientController::class, 'index'])->name('client-intake-application');
        Route::get('/dashboard/client-intake-application/data', [ClientController::class, 'getClients'])->name('client-intake-application.data');
        Route::get('/dashboard/client-intake-application/{id}/edit', [ClientController::class, 'edit'])->name('client-intake-application.edit');
        Route::put('/dashboard/client-intake-application/{id}', [ClientController::class, 'update'])->name('client-intake-application.update');
        Route::delete('/dashboard/client-intake-application/photo/{id}', [ClientController::class, 'deletePhoto'])->name('client-intake-application.delete-photo');

        Route::get('/dashboard/sales-closing', [SalesClosingController::class, 'index'])->name('sales-closing');
        Route::get('/dashboard/sales-closing/data', [SalesClosingController::class, 'getDeals'])->name('sales-closing.data');
        Route::get('/dashboard/sales-closing/{id}', [SalesClosingController::class, 'show'])->name('sales-closing.show');
        Route::put('/dashboard/sales-closing/{id}', [SalesClosingController::class, 'update'])->name('sales-closing.update');
    });

    Route::middleware('role:admin|closer|billing')->group(function () {
        Route::get('/dashboard/payments', [PaymentController::class, 'index'])->name('payments');
        Route::get('/dashboard/payments/data', [PaymentController::class, 'getPayments'])->name('payments.data');
        Route::get('/dashboard/payments/{id}', [PaymentController::class, 'show'])->name('payments.show');
        Route::put('/dashboard/payments/{id}', [PaymentController::class, 'update'])->name('payments.update');
    });

    Route::middleware('role:admin|matchmaker')->group(function () {
        Route::get('/dashboard/matchmaking', [MatchmakingController::class, 'index'])->name('matchmaking');
        Route::get('/dashboard/matchmaking/data', [MatchmakingController::class, 'getMatches'])->name('matchmaking.data');
        Route::post('/dashboard/matchmaking', [MatchmakingController::class, 'store'])->name('matchmaking.store');
        Route::get('/dashboard/matchmaking/{id}/edit', [MatchmakingController::class, 'edit'])->name('matchmaking.edit');
        Route::put('/dashboard/matchmaking/{id}', [MatchmakingController::class, 'update'])->name('matchmaking.update');
    });
});
