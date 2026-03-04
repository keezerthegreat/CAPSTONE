@extends('layouts.app')

@section('content')

<h2 class="text-xl font-semibold mb-6">Workers List</h2>

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<a href="{{ route('workers.create') }}"
   class="bg-slate-900 text-white px-4 py-2 rounded mb-4 inline-block">
   Add Worker
</a>

<table class="w-full bg-white shadow rounded">
    <thead class="bg-slate-200 text-sm">
        <tr>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Position</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($workers as $worker)
        <tr class="border-t hover:bg-slate-50">
            <td class="p-3">{{ $worker->full_name }}</td>
            <td class="p-3">{{ $worker->position }}</td>
            <td class="p-3">{{ $worker->status }}</td>
            <td class="p-3 space-x-2">

                <!-- Edit Button -->
                <a href="{{ route('workers.edit', $worker->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                   Edit
                </a>

                <!-- Delete Button -->
                <form action="{{ route('workers.destroy', $worker->id) }}"
                      method="POST"
                      class="inline-block"
                      onsubmit="return confirm('Delete this worker?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                        Delete
                    </button>
                </form>

            </td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection