<?php

namespace App\Http\Controllers\Judge;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Score;
use App\Http\Requests\StoreScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Models\User;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request, $competitionId, $contestantId)
    {
        $competition = Competition::findOrFail($competitionId);
        $contestant = User::findOrFail($contestantId);

        foreach ($request->scores as $criteriaId => $scoreValue) {
            Score::updateOrCreate([
                'user_id' => $contestant->id,
                'competition_id' => $competition->id,
                'criteria_id' => $criteriaId,
                'judge_id' => auth()->id()
            ], [
                'score' => $scoreValue
            ]);
        }

        return redirect()->route('judge.competitions.show', $competition->id)
            ->with('success', 'Score successfully submitted.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Score $score)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Score $score)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateScoreRequest $request, Score $score)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Score $score)
    {
        //
    }

    public function evaluate($competitionId, $contestantId)
    {
        $competition = Competition::findOrFail($competitionId);
        $contestant = User::findOrFail($contestantId);
        $criteria = $competition->criteria;

        // Check if the judge already scored
        $scores = Score::where('competition_id', $competition->id)
            ->where('user_id', $contestant->id)
            ->where('judge_id', auth()->id())
            ->get();

        return view('judge.competitions.evaluate', compact('competition', 'contestant', 'criteria', 'scores'));
    }


    public function viewScores($competitionId, $contestantId)
    {
        $competition = Competition::findOrFail($competitionId);
        $contestant = User::findOrFail($contestantId);

        $scores = Score::where('competition_id', $competition->id)
            ->where('user_id', $contestant->id)
            ->where('judge_id', auth()->id())
            ->get();

        return view('judge.competitions.view-scores', compact('competition', 'contestant', 'scores'));
    }

}
