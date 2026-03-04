<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    // ✅ DISPLAY ALL WORKERS
    public function index()
    {
        $workers = Worker::latest()->get();
        return view('workers.index', compact('workers'));
    }

    // ✅ SHOW CREATE FORM
    public function create()
    {
        return view('workers.create');
    }

    // ✅ STORE NEW WORKER
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'gender'         => 'nullable|string|max:20',
            'birth_date'     => 'nullable|date',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'position'       => 'required|string|max:255',
            'department'     => 'nullable|string|max:255',
            'date_started'   => 'nullable|date',
            'term_start'     => 'nullable|date',
            'term_end'       => 'nullable|date',
            'status'         => 'required|string|max:50',
        ]);

        Worker::create($validated);

        return redirect()->route('workers.index')
                         ->with('success', 'Worker added successfully.');
    }

    // ✅ EDIT FORM (Route Model Binding)
    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    // ✅ UPDATE WORKER
    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'full_name'      => 'required|string|max:255',
            'gender'         => 'nullable|string|max:20',
            'birth_date'     => 'nullable|date',
            'contact_number' => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'position'       => 'required|string|max:255',
            'department'     => 'nullable|string|max:255',
            'date_started'   => 'nullable|date',
            'term_start'     => 'nullable|date',
            'term_end'       => 'nullable|date',
            'status'         => 'required|string|max:50',
        ]);

        $worker->update($validated);

        return redirect()->route('workers.index')
                         ->with('success', 'Worker updated successfully.');
    }

    // ✅ DELETE WORKER
    public function destroy(Worker $worker)
    {
        $worker->delete();

        return redirect()->route('workers.index')
                         ->with('success', 'Worker deleted successfully.');
    }
}