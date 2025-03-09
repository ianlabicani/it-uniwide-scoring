<?php
use App\Http\Controllers\Admin\CompetitionController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->middleware(['auth',])->group(function () {
    // Route::apiResource('users', AdminUserController::class)->only(['index', 'show', 'update']);

    Route::get('dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::post('/users/add-judge', [UserController::class, 'addJudge'])->name('users.addJudge');
    Route::resource('users', UserController::class);
    Route::resource('competitions', CompetitionController::class);

});

