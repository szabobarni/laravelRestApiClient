<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;

//Route::post('/users/login', [UsersController::class, 'login']); 

Route::get("/artists", [ArtistController::class, "index"])->name('artists.index');
Route::get("/artist/create", [ArtistController::class, "create"])->name('artists.create');
Route::post('/artists', [ArtistController::class, 'store'])->name('artists.store');
Route::get("/artist/{id}", [ArtistController::class, "show"])->name('artists.show');
Route::get("/artist/{id}/edit", [ArtistController::class, "edit"])->name('artists.edit');
Route::put("/artist/{id}", [ArtistController::class, "update"])->name('artists.update');
Route::delete("/artist/{id}", [ArtistController::class, "destroy"])->name('artists.destroy');

require __DIR__.'/auth.php';
