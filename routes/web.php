<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EarnedScheduleController;
use App\Http\Controllers\EarnedScheduleCostController;
use App\Http\Controllers\IntegrationServiceController;
use App\Http\Controllers\IntegrationSettingController;
use App\Http\Controllers\IntegrationRedmineSettingController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia\Inertia::render('Dashboard');
})->name('dashboard');

// Route::middleware(['auth:sanctum', 'verified'])->get('/projects/index', function () {
//     return Inertia\Inertia::render('Projects/Index');
// })->name('projects/index');

Route::middleware(['auth:sanctum', 'verified'])->get('/earnedschedule/index', [EarnedScheduleController::class, 'index'])->name('earnedschedule.index');
Route::middleware(['auth:sanctum', 'verified'])->get('/earnedschedulecost/{milestone_id}', [EarnedScheduleCostController::class, 'index'])->name('earnedschedulecost.index');
Route::middleware(['auth:sanctum', 'verified'])->put('/earnedschedulecost/{milestone_id}', [EarnedScheduleCostController::class, 'update'])->name('earnedschedulecost.put');

Route::middleware(['auth:sanctum', 'verified'])->get('/integrationservices/index', [IntegrationServiceController::class, 'index'])->name('integrationservices.index');

Route::middleware(['auth:sanctum', 'verified'])->get('/integrationsettings/index', [IntegrationSettingController::class, 'index'])->name('integrationsettings.index');
Route::middleware(['auth:sanctum', 'verified'])->post('/integrationsettings', [IntegrationSettingController::class, 'store'])->name('integrationsettings.store');
Route::middleware(['auth:sanctum', 'verified'])->put('/integrationsettings/{id}', [IntegrationSettingController::class, 'update'])->name('integrationsettings.put');
Route::middleware(['auth:sanctum', 'verified'])->delete('/integrationsettings/{id}', [IntegrationSettingController::class, 'destroy'])->name('integrationsettings.delete');

Route::middleware(['auth:sanctum', 'verified'])->get('/integrationredminesettings/{id}', [IntegrationRedmineSettingController::class, 'show'])->name('integrationredminesettings.show');
Route::middleware(['auth:sanctum', 'verified'])->post('/integrationredminesettings', [IntegrationRedmineSettingController::class, 'store'])->name('integrationredminesettings.store');
Route::middleware(['auth:sanctum', 'verified'])->put('/integrationredminesettings/{id}', [IntegrationRedmineSettingController::class, 'update'])->name('integrationredminesettings.put');
