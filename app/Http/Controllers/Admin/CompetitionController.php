<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Competition;
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
        return view('admin.competitions.create');
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
        ]);

        $competition = Competition::create($request->only('name', 'date', 'location', 'description'));

        foreach ($request->criteria as $criterion) {
            $competition->criteria()->create($criterion);
        }

        return redirect()->route('admin.competitions.index')->with('success', 'Competition created successfully');
    }

    public function edit(Competition $competition)
    {
        return view('admin.competitions.edit', compact('competition'));
    }

    public function update(Request $request, Competition $competition)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
            'description' => 'nullable',
            'criteria.*.name' => 'required',
            'criteria.*.percentage' => 'required|numeric|min:1|max:100',
        ]);

        $competition->update($request->only('name', 'date', 'location', 'description'));
        $competition->criteria()->delete();

        foreach ($request->criteria as $criterion) {
            $competition->criteria()->create($criterion);
        }

        return redirect()->route('admin.competitions.index')->with('success', 'Competition updated successfully');
    }

    public function destroy(Competition $competition)
    {
        $competition->delete();
        return redirect()->route('admin.competitions.index')->with('success', 'Competition deleted successfully');
    }
}
