<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use Illuminate\Http\Request;

class WorkerController extends Controller
{
    public function index()
    {
        $workers = Worker::latest()->get();
        return view('workers.index', compact('workers'));
    }

    public function create()
    {
        return view('workers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required',
            'position' => 'required',
        ]);

        Worker::create($request->all());

        return redirect()->route('workers.index')
                         ->with('success', 'Worker added successfully.');
    }
}