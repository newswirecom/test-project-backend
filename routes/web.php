<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\JobsController;

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

Route::get('/', function() {
    return redirect()->route('jobs');
});

Route::get('jobs', [ JobsController::class, 'index' ])->name('jobs');

// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================
// ===============================================

// Quick way to switch between workers
Route::get('workers', function() {
    $workers = \App\Models\Worker::all();
    return view('workers')->with('workers', $workers);
})->name('workers');

// Quick way to switch between workers
Route::get('switch-worker', function() {
    session()->put('worker', request()->input('id'));
    return redirect()->route('jobs');
})->name('workers');
