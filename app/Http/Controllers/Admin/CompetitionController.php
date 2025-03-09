<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
use App\Models\Criteria;
use App\Models\User;
use Illuminate\Http\Request;

class CompetitionController extends Controller
{
    public function index()
    {
        $competitions = Competition::with('criteria')->get();
        return view('admin.competitions.index', compact('competitions'));
    }

    public function create()
    {
        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get();

        return view('admin.competitions.create', compact('judges'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
            'description' => 'nullable',
            'criteria.*.name' => 'required',
            'criteria.*.percentage' => 'required|numeric|min:1|max:100',
            'judges' => 'required|array',
        ]);

        // Create the competition
        $competition = Competition::create($request->only('name', 'date', 'location', 'description'));

        // Create the criteria
        foreach ($request->criteria as $criterion) {
            $competition->criteria()->create($criterion);
        }

        // Attach the judges to the competition
        $competition->judges()->attach($request->judges);

        return redirect()->route('admin.competitions.index')->with('success', 'Competition created successfully');
    }


    public function edit($id)
    {
        $competition = Competition::with('criteria', 'judges')->findOrFail($id);
        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get();

        return view('admin.competitions.edit', compact('competition', 'judges'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
            'description' => 'nullable',
            'criteria.*.name' => 'required',
            'criteria.*.percentage' => 'required|numeric|min:1|max:100',
            'judges' => 'required|array',
        ]);

        $competition = Competition::findOrFail($id);
        $competition->update($request->only('name', 'date', 'location', 'description'));

        // Sync judges
        $competition->judges()->sync($request->judges);

        // Handle criteria
        foreach ($request->criteria as $criterionData) {
            if (isset($criterionData['id']) && $criterionData['id'] != '') {
                // Update existing criteria
                Criteria::where('id', $criterionData['id'])
                    ->update([
                        'name' => $criterionData['name'],
                        'percentage' => $criterionData['percentage']
                    ]);
            } else {
                // Add new criteria
                $competition->criteria()->create($criterionData);
            }
        }

        // Delete criteria that were removed
        $existingCriteriaIds = collect($request->criteria)->pluck('id')->filter();
        Criteria::where('competition_id', $competition->id)
            ->whereNotIn('id', $existingCriteriaIds)
            ->delete();

        return redirect()->route('admin.competitions.edit', $competition->id)->with('success', 'Competition updated successfully');
    }


    public function destroy(Competition $competition)
    {
        $competition->delete();
        return redirect()->route('admin.competitions.index')->with('success', 'Competition deleted successfully');
    }
}
