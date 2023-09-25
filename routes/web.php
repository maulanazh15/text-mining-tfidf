<?php

use App\Models\Dokumen;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TFIDFController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\VectorModelController;

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
    return redirect('dokumen');
});

Route::get('/tfidf', [DokumenController::class, 'showTFIDF']);
Route::get('/vectormodel', [DokumenController::class, 'showVectorModel']);
Route::get('/calculate-tfidf', [TFIDFController::class, 'calculateTFIDF'])->name('calculate.tfidf');
Route::get('/calculate-vector-models', [VectorModelController::class, 'calculateVectorModels'])
    ->name('calculate.vector.models');
Route::get('/calculate-cosine-similarity/{queryDocumentId}', [VectorModelController::class, 'calculateCosineSimilarity'])
        ->name('calculate.cosine.similarity');
    

Route::resource('dokumen', DokumenController::class);


