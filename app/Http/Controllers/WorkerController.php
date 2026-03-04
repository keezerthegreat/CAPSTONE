<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    // ===============================
    // DISPLAY ALL WORKERS
    // ===============================
    public function index()
    {
        $workers = Worker::orderBy('created_at', 'desc')->get();
        return view('workers.index', compact('workers'));
    }

    // ===============================
    // SHOW CREATE FORM
    // ===============================
    public function create()
    {
        return view('workers.create');
    }

    // ===============================
    // STORE NEW WORKER
    // ===============================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'birthdate'         => 'nullable|date',
            'gender'            => 'nullable|string|max:20',
            'civil_status'      => 'nullable|string|max:50',
            'address'           => 'nullable|string|max:255',
            'contact_number'    => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'position'          => 'required|string|max:255',
            'date_hired'        => 'nullable|date',
            'employment_status' => 'nullable|string|max:50',
        ]);

        Worker::create($validated);

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker added successfully!');
    }

    // ===============================
    // SHOW EDIT FORM
    // ===============================
    public function edit(Worker $worker)
    {
        return view('workers.edit', compact('worker'));
    }

    // ===============================
    // UPDATE WORKER
    // ===============================
    public function update(Request $request, Worker $worker)
    {
        $validated = $request->validate([
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'middle_name'       => 'nullable|string|max:255',
            'birthdate'         => 'nullable|date',
            'gender'            => 'nullable|string|max:20',
            'civil_status'      => 'nullable|string|max:50',
            'address'           => 'nullable|string|max:255',
            'contact_number'    => 'nullable|string|max:20',
            'email'             => 'nullable|email|max:255',
            'position'          => 'required|string|max:255',
            'date_hired'        => 'nullable|date',
            'employment_status' => 'nullable|string|max:50',
        ]);

        $worker->update($validated);

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker updated successfully!');
    }

    // ===============================
    // DELETE WORKER
    // ===============================
    public function destroy(Worker $worker)
    {
        $worker->delete();

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker deleted successfully!');
    }
}