<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Get competitions where the logged-in user is a judge
        $competitions = Competition::whereHas('judges', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['contestants', 'criteria', 'scores'])->get();

        // Pass competitions to the view
        return view('judge.dashboard', compact('competitions'));
    }
}
