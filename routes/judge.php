<?php
use App\Http\Controllers\Judge\CompetitionController;
use Illuminate\Support\Facades\Route;


Route::prefix('judge')->name('judge.')->middleware(['auth', 'role:judge'])->group(function () {

    Route::get('dashboard', function () {

        // get the competitions to be judged by the authenticated judge
        return view('judge.dashboard');
    })->name('dashboard');


    Route::resource('competitions', CompetitionController::class)->only(['index', 'show']);

});

