<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Household;
use Illuminate\Http\Request;

class HouseholdController extends Controller
{
    public function index()
    {
        $households = Household::latest()->get();
        return view('households.index', compact('households'));
    }

    public function create()
    {
        return view('households.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'household_number' => 'required|unique:households',
            'head_last_name'   => 'required',
            'head_first_name'  => 'required',
            'sitio'            => 'required',
        ]);

        $household = Household::create($request->all());
        ActivityLog::log('created', 'Household', "Added household: #{$household->household_number}");

        return redirect()->route('households.index')
            ->with('success', 'Household added successfully.');
    }

    public function show($id)
    {
        $household = Household::findOrFail($id);
        return view('households.show', compact('household'));
    }

    public function edit($id)
    {
        $household = Household::findOrFail($id);
        return view('households.edit', compact('household'));
    }

    public function update(Request $request, $id)
    {
        $household = Household::findOrFail($id);

        $request->validate([
            'household_number' => 'required|unique:households,household_number,' . $id,
            'head_last_name'   => 'required',
            'head_first_name'  => 'required',
            'sitio'            => 'required',
        ]);

        $household->update($request->all());
        ActivityLog::log('updated', 'Household', "Updated household: #{$household->household_number}");

        return redirect()->route('households.index')
            ->with('success', 'Household updated successfully.');
    }

    public function destroy($id)
    {
        $household = Household::findOrFail($id);
        ActivityLog::log('deleted', 'Household', "Deleted household: #{$household->household_number}");
        $household->delete();

        return redirect()->route('households.index')
            ->with('success', 'Household deleted successfully.');
    }
    public function map()
{
    $households = \App\Models\Household::whereNotNull('latitude')
        ->whereNotNull('longitude')
        ->get();

    return view('households.map', compact('households'));
}
}