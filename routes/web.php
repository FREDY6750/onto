<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CoursEauController;
use App\Http\Controllers\BassinVersantController;
use App\Http\Controllers\LocaliteController;
use App\Http\Controllers\AffluentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TestNeo4jController;
use App\Http\Controllers\RdfImportController;
use App\Http\Controllers\RiviereController;
use App\Http\Controllers\FleuveController;
use App\Http\Controllers\SousBassinNationalController;
use App\Http\Controllers\SousBassinRegionalController;





Route::get('/', function () {
    return redirect()->route('cours.index'); // ou view('welcome') si tu veux afficher une page d'accueil
});

// Routes CRUD principales
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('cours', CoursEauController::class);
Route::resource('bassins', BassinVersantController::class);
Route::resource('localites', LocaliteController::class);
Route::resource('affluents', AffluentController::class);
Route::resource('fleuve', FleuveController::class);
Route::resource('rivieres', RiviereController::class);
Route::resource('sbvnationaux', SousBassinNationalController::class);
Route::resource('sbvregionaux', SousBassinRegionalController::class);



//Route::get('/cours-eau', [CoursEauController::class, 'index'])->name('cours_eau.index');

Route::get('/import-rdf', [RdfImportController::class, 'importRdf']);

// Test de connexion à Neo4j (pour debug)
Route::get('/test-neo4j', [TestNeo4jController::class, 'test']);



