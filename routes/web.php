<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StrukController;

Route::get('/geutanyo_cell', [StrukController::class, 'c_geutanyo_cell'])->name('geutanyo_cell.create');
Route::post('/geutanyo_cell', [StrukController::class, 's_geutanyo_cell'])->name('geutanyo_cell.store');
Route::get('/dana_pay', [StrukController::class, 'createDanaPay'])->name('dana_pay.create');
Route::post('/dana_pay', [StrukController::class, 'storeDanaPay'])->name('dana_pay.store');
Route::get('/payfazz', [StrukController::class, 'createPayfazz'])->name('payfazz.create');
Route::post('/payfazz', [StrukController::class, 'storePayfazz'])->name('payfazz.store');
Route::get('/gold_cell', [StrukController::class, 'createGoldCell'])->name('gold_cell.create');
Route::post('/gold_cell', [StrukController::class, 'storeGoldCell'])->name('gold_cell.store');
Route::get('/asa_cellular', [StrukController::class, 'formAsaCellular'])->name('asa_cellular.form');
Route::post('/asa_cellular', [StrukController::class, 'storeAsaCellular'])->name('asa_cellular.store');
Route::view('/pesan', 'struk.telkomsel')->name('pesan.form');
Route::post('/pesan', [StrukController::class, 'storePesanTelkomsel'])->name('pesan.store');


Route::get('/', function () {
    return view('welcome');
});
