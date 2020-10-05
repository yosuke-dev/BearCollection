<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RedmineProjectController;
use App\Http\Controllers\RedmineVersionController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('/projects/index', [ProjectController::class, 'index'])->name('projects/index');

Route::middleware(['auth:sanctum', 'verified'])->get('/redmine/projects', [RedmineProjectController::class, 'all'])->name('redmine/projects');
Route::middleware(['auth:sanctum', 'verified'])->get('/redmine/versions/{id}', [RedmineVersionController::class, 'all'])->name('redmine/versions/{id}');