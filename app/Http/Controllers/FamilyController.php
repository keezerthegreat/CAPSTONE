<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Household;
use Illuminate\Http\Request;

class FamilyController extends Controller
{
    public function index()
    {
        $families = Family::with('household')->get();
        return view('families.index', compact('families'));
    }

    public function create()
    {
        $households = Household::orderBy('head_last_name')->get();
        return view('families.create', compact('households'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name'      => 'required|string|max:255',
            'head_last_name'   => 'required|string|max:255',
            'head_first_name'  => 'required|string|max:255',
            'head_middle_name' => 'nullable|string|max:255',
            'household_id'     => 'nullable|exists:households,id',
            'member_count'     => 'required|integer|min:1',
            'notes'            => 'nullable|string',
        ]);
        Family::create($validated);
        return redirect()->route('families.index')->with('success', 'Family added.');
    }

    public function show(Family $family)
    {
        $family->load('household');
        return view('families.show', compact('family'));
    }

    public function edit(Family $family)
    {
        $households = Household::orderBy('head_last_name')->get();
        return view('families.edit', compact('family', 'households'));
    }

    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'family_name'      => 'required|string|max:255',
            'head_last_name'   => 'required|string|max:255',
            'head_first_name'  => 'required|string|max:255',
            'head_middle_name' => 'nullable|string|max:255',
            'household_id'     => 'nullable|exists:households,id',
            'member_count'     => 'required|integer|min:1',
            'notes'            => 'nullable|string',
        ]);
        $family->update($validated);
        return redirect()->route('families.index')->with('success', 'Family updated.');
    }

    public function destroy(Family $family)
    {
        $family->delete();
        return redirect()->route('families.index')->with('success', 'Family deleted.');
    }
}