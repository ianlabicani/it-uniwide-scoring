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

    // public function show($id)
    // {
    //     $competition = Competition::with('criteria', 'judges', 'contestants')->findOrFail($id);
    //     return view('admin.competitions.show', compact('competition'));
    // }

    public function create()
    {
        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get();

        $contestants = User::whereHas('roles', function ($query) {
            $query->where('name', 'contestant');
        })->get();

        return view('admin.competitions.create', compact('judges', 'contestants'));
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
        $competition->contestants()->attach($request->contestants);


        return redirect()->route('admin.competitions.index')->with('success', 'Competition created successfully');
    }


    public function edit($id)
    {
        $competition = Competition::with('criteria', 'judges')->findOrFail($id);
        $judges = User::whereHas('roles', function ($query) {
            $query->where('name', 'judge');
        })->get();
        $contestants = User::whereHas('roles', function ($query) {
            $query->where('name', 'contestant');
        })->get();

        return view('admin.competitions.edit', compact('competition', 'judges', 'contestants'));
    }


    public function update(Request $request, Competition $competition)
    {
        $competition->update($request->only('name', 'date', 'location', 'description'));

        // Sync judges
        $competition->judges()->sync($request->judges);

        // Sync contestants
        $competition->contestants()->sync($request->contestants);

        // Update or create criteria
        foreach ($request->criteria as $criterionData) {
            if (isset($criterionData['id'])) {
                // Update existing criteria
                $criterion = Criteria::find($criterionData['id']);
                $criterion->update($criterionData);
            } else {
                // Create new criteria
                $competition->criteria()->create($criterionData);
            }
        }

        return redirect()->route('admin.competitions.index')->with('success', 'Competition updated successfully.');
    }



    public function destroy(Competition $competition)
    {
        $competition->delete();
        return redirect()->route('admin.competitions.index')->with('success', 'Competition deleted successfully');
    }
}
