<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GamesController;

Route::get('/', [GamesController::class, 'index']); 
Route::get('/digimon', function () {
    return view('digimon');
});