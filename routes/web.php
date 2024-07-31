<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoodController;

Route::get('/', [GoodController::class, 'index'])->name('goods');

Route::get('/add-goods', [GoodController::class, 'createForm'])->name('goods.add.form');
Route::post('/add-goods', [GoodController::class, 'create'])->name('goods.add');

Route::get('/goods/{good}', [GoodController::class, 'show'])->name('goods.show');