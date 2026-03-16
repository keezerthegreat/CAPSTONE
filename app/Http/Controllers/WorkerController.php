<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Worker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'date_hired' => 'nullable|date',
            'employment_status' => 'nullable|string|max:50',

            // PHOTO VALIDATION
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // HANDLE PHOTO UPLOAD
        if ($request->hasFile('photo')) {

            $validated['photo'] = $request
                ->file('photo')
                ->store('workers', 'public');

        }

        $worker = Worker::create($validated);
        ActivityLog::log('created', 'Worker', "Added worker: {$worker->first_name} {$worker->last_name} ({$worker->position})");

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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'birthdate' => 'nullable|date',
            'gender' => 'nullable|string|max:20',
            'civil_status' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'contact_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'position' => 'required|string|max:255',
            'date_hired' => 'nullable|date',
            'employment_status' => 'nullable|string|max:50',

            // PHOTO VALIDATION
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // UPDATE PHOTO IF NEW ONE IS UPLOADED
        if ($request->hasFile('photo')) {
            if ($worker->photo) {
                Storage::disk('public')->delete($worker->photo);
            }

            $validated['photo'] = $request
                ->file('photo')
                ->store('workers', 'public');
        }

        $worker->update($validated);
        ActivityLog::log('updated', 'Worker', "Updated worker: {$worker->first_name} {$worker->last_name}");

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker updated successfully!');
    }

    // ===============================
    // DELETE WORKER
    // ===============================
    public function destroy(Worker $worker)
    {
        if ($worker->photo) {
            Storage::disk('public')->delete($worker->photo);
        }

        ActivityLog::log('deleted', 'Worker', "Deleted worker: {$worker->first_name} {$worker->last_name}");
        $worker->delete();

        return redirect()
            ->route('workers.index')
            ->with('success', 'Worker deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        if ($request->input('select_all')) {
            $workers = Worker::all();
        } else {
            $ids = $request->input('ids', []);
            if (empty($ids)) {
                return redirect()->back()->with('error', 'No workers selected.');
            }
            $workers = Worker::whereIn('id', $ids)->get();
        }

        $count = $workers->count();
        foreach ($workers as $worker) {
            if ($worker->photo) {
                Storage::disk('public')->delete($worker->photo);
            }
            ActivityLog::log('deleted', 'Worker', "Bulk deleted worker: {$worker->first_name} {$worker->last_name}");
            $worker->delete();
        }

        return redirect()->route('workers.index')
            ->with('success', $count.' worker(s) deleted successfully.');
    }
}
