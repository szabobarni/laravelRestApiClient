<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArtistController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// --- Artist Routes ---
Route::get("/artist/create", [ArtistController::class, "create"])->name('artists.create');
Route::get("/artists", [ArtistController::class, "index"])->name('artists.index');
Route::get("/artist/{id}", [ArtistController::class, "show"])->name('artists.show');
Route::get("/artist/{id}/albums", [ArtistController::class, "showAlbums"])->name('artists.albums');
Route::get("/artist/{artist_id}/album/{id}/songs", [ArtistController::class, "showSongs"])->name('artists.songs');
Route::get("/artist/{id}/edit", [ArtistController::class, "edit"])->name('artists.edit');
Route::get("/artist/{id}/export/{format}", [ArtistController::class, "exportAlbumsSongs"])->name('artists.export');
Route::patch("/artist/{id}", [ArtistController::class, "update"])->name('artists.update');
Route::post('/artist', [ArtistController::class, 'store'])->name('artists.store');
Route::delete("/artist/{id}", [ArtistController::class, "destroy"])->name('artists.destroy');

require __DIR__.'/auth.php';
