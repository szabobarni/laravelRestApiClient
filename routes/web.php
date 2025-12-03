<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;

//Route::post('/users/login', [UsersController::class, 'login']); 

Route::get("/artists", [ArtistController::class, "index"])->name('artists.index');
Route::get("/artists/{id}", [ArtistController::class, "show"])->name('artists.show');
Route::post("/artists/{id}", [ArtistController::class, "edit"])->name('artists.edit');
Route::post('/artists', [ArtistController::class, 'store'])->name('artists.store');
Route::put("/artists/{id}", [ArtistController::class, "update"])->name('artists.update');
Route::delete("/artists/{id}", [ArtistController::class, "destroy"])->name('artists.destroy');

require __DIR__.'/auth.php';
