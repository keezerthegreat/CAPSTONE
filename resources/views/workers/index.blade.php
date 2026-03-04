@extends('layouts.app')

@section('page-title', 'Worker Information Page')

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
        @forelse($workers as $worker)
        <tr class="border-t hover:bg-slate-50">
            <!-- FULL NAME -->
            <td class="p-3">
                {{ $worker->first_name }}
                {{ $worker->middle_name }}
                {{ $worker->last_name }}
            </td>

            <!-- POSITION -->
            <td class="p-3">{{ $worker->position }}</td>

            <!-- EMPLOYMENT STATUS -->
            <td class="p-3">
                {{ $worker->employment_status ?? 'N/A' }}
            </td>

            <!-- ACTIONS -->
            <td class="p-3 space-x-2">

                <!-- VIEW -->
                <button onclick='openModal(@json($worker))'
                    class="bg-green-700 text-white px-3 py-1 rounded text-sm hover:bg-green-800">
                    View
                </button>   

                <!-- EDIT -->
                <a href="{{ route('workers.edit', $worker->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                   Edit
                </a>

                <!-- DELETE -->
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
        @empty
        <tr>
            <td colspan="4" class="p-3 text-center text-gray-500">
                No workers found.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>


<!-- VIEW MODAL -->
<div id="viewModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/30">

    <div class="bg-white w-[500px] p-6 rounded shadow-xl border">

        <h2 class="text-xl font-bold mb-4 text-center">
            Worker Information
        </h2>

        <div class="space-y-3">

            <div>
                <label class="text-sm font-medium">Full Name</label>
                <input type="text" id="v_name"
                    class="w-full border rounded p-2"   
                    readonly>
            </div>

            <div>
                <label class="text-sm font-medium">Gender</label>
                <input type="text" id="v_gender"
                    class="w-full border rounded p-2"
                    readonly>
            </div>

            <div>
                <label class="text-sm font-medium">Contact</label>
                <input type="text" id="v_contact"
                    class="w-full border rounded p-2"
                    readonly>
            </div>

            <div>
                <label class="text-sm font-medium">Address</label>
                <input type="text" id="v_address"
                    class="w-full border rounded p-2"
                    readonly>
            </div>

            <div>
                <label class="text-sm font-medium">Position</label>
                <input type="text" id="v_position"
                    class="w-full border rounded p-2"
                    readonly>
            </div>

            <div>
                <label class="text-sm font-medium">Employment Status</label>
                <input type="text" id="v_status"
                    class="w-full border rounded p-2"
                    readonly>
            </div>

        </div>

        <div class="mt-5 text-center">
            <button onclick="closeModal()"
                class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Close
            </button>
        </div>

    </div>
</div>


<script>
function openModal(worker) {

    let fullName =
        (worker.first_name ?? '') + ' ' +
        (worker.middle_name ?? '') + ' ' +
        (worker.last_name ?? '');

    document.getElementById('v_name').value = fullName.trim();
    document.getElementById('v_gender').value = worker.gender ?? '';
    document.getElementById('v_contact').value = worker.contact_number ?? '';
    document.getElementById('v_address').value = worker.address ?? '';
    document.getElementById('v_position').value = worker.position ?? '';
    document.getElementById('v_status').value = worker.employment_status ?? '';

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');
}

function closeModal() {
    document.getElementById('viewModal').classList.add('hidden');
}
</script>

@endsection