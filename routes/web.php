<?php

use App\Http\Controllers\ProfileController;
use App\Models\Competition;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Get the role ID for judges
    $judgeRole = Role::where('name', 'judge')->first();

    // Get all judges from the role_user pivot table
    $judgeIds = \DB::table('role_user')
        ->where('role_id', $judgeRole->id)
        ->pluck('user_id')
        ->toArray();

    // Only get competitions where ALL judges have evaluated all contestants
    $completedCompetitions = Competition::whereHas('scores', function ($query) use ($judgeIds) {
        $query->select('user_id')
            ->groupBy('user_id')
            ->havingRaw('COUNT(DISTINCT judge_id) = ?', [count($judgeIds)]);
    })->get();

    return view('welcome', compact('completedCompetitions'));
})->name('home');



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/judge.php';

