<?php
use App\Http\Controllers\Judge\CompetitionController;
use App\Http\Controllers\Judge\ScoreController;
use Illuminate\Support\Facades\Route;


Route::prefix('judge')->name('judge.')->middleware(['auth', 'role:judge'])->group(function () {

    Route::get('dashboard', function () {

        // get the competitions to be judged by the authenticated judge
        return view('judge.dashboard');
    })->name('dashboard');


    Route::resource('competitions', CompetitionController::class)->only(['index', 'show']);
    Route::get('competitions/{competition}/evaluate/{contestant}', [ScoreController::class, 'evaluate'])->name('competitions.evaluate');
    Route::post('competitions/{competition}/score/{contestant}', [ScoreController::class, 'store'])->name('competitions.score');
    Route::get('competitions/{competition}/view-scores/{contestant}', [ScoreController::class, 'viewScores'])
        ->name('competitions.view-scores');

});

