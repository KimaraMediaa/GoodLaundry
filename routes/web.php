<?php

use App\Models\outlet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('home');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/jobList', function () {
    return view('admins.joblist');
})->name('joblist');

Route::resources([
    'area' => App\Http\Controllers\areaController::class,
    'outlet' => App\Http\Controllers\outletController::class,
]);

Route::get('/mapsOutlet', function () {
    $data = outlet::where('vOutlet', 1)->get();
    return response()->json($data);
})->name('mapsOutlet');
