@extends('layouts.app')

{{-- Page Title --}}
@section('page-title', 'Worker Information Page')

@section('content')

{{-- ===============================
    PAGE HEADER
================================ --}}
<h2 class="text-xl font-semibold mb-6">Workers List</h2>


{{-- ===============================
    SUCCESS MESSAGE
================================ --}}
@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif


{{-- ===============================
    ACTION BAR (ADD + SEARCH)
================================ --}}
<div class="flex justify-between items-center mb-4">

    {{-- Add Worker Button --}}
    <a href="{{ route('workers.create') }}"
       class="bg-slate-900 text-white px-4 py-2 rounded">
        Add Worker
    </a>

    {{-- Search Worker --}}
    <input type="text"
           id="workerSearch"
           placeholder="Search worker..."
           class="border rounded px-3 py-2 w-64">

</div>


{{-- ===============================
    WORKERS TABLE
================================ --}}
<table class="w-full bg-white shadow rounded">

    {{-- TABLE HEADER --}}
    <thead class="bg-slate-200 text-sm">
        <tr>
            <th class="p-3 text-left">Photo</th>
            <th class="p-3 text-left">Name</th>
            <th class="p-3 text-left">Position</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Actions</th>
        </tr>
    </thead>

    {{-- TABLE BODY --}}
    <tbody>

    @forelse($workers as $worker)

    <tr class="border-t hover:bg-slate-50">

        {{-- WORKER PHOTO --}}
        <td class="p-3">
            <img
                src="{{ $worker->photo ? asset('storage/'.$worker->photo) : 'https://via.placeholder.com/40' }}"
                class="w-10 h-10 rounded-full object-cover"
            >
        </td>

        {{-- WORKER FULL NAME --}}
        <td class="p-3">
            {{ $worker->first_name }}
            {{ $worker->middle_name }}
            {{ $worker->last_name }}
        </td>

        {{-- POSITION --}}
        <td class="p-3">
            {{ $worker->position }}
        </td>


        {{-- ===============================
            EMPLOYMENT STATUS BADGE
        ================================= --}}
        <td class="p-3">

            @if($worker->employment_status == 'Regular')

                <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs">
                    Regular
                </span>

            @elseif($worker->employment_status == 'Job Order')

                <span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs">
                    Job Order
                </span>

            @elseif($worker->employment_status == 'Volunteer')

                <span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs">
                    Volunteer
                </span>

            @else

                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs">
                    N/A
                </span>

            @endif

        </td>


        {{-- ===============================
            ACTION BUTTONS
        ================================= --}}
        <td class="p-3 space-x-2">

            {{-- VIEW BUTTON --}}
            <button
                onclick='openModal(@json($worker))'
                class="bg-green-500 text-white px-2 py-1 rounded text-xs">
                View
            </button>


            {{-- ADMIN ONLY ACTIONS --}}
            @if(auth()->user()->role == 'admin')

                {{-- EDIT --}}
                <a href="{{ route('workers.edit', $worker->id) }}"
                   class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                    Edit
                </a>

                {{-- DELETE --}}
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

            @endif

        </td>

    </tr>

    @empty

        {{-- NO DATA MESSAGE --}}
        <tr>
            <td colspan="5" class="p-3 text-center text-gray-500">
                No workers found.
            </td>
        </tr>

    @endforelse

    </tbody>

</table>



{{-- =========================================================
    VIEW WORKER MODAL
========================================================= --}}
<div id="viewModal"
     class="fixed inset-0 hidden items-center justify-center bg-black/30">

    <div class="bg-white w-[500px] p-6 rounded shadow-xl border">

        <h2 class="text-xl font-bold mb-4 text-center">
            Worker Information
        </h2>

        <div class="space-y-3">

            {{-- FULL NAME --}}
            <div>
                <label class="text-sm font-medium">Full Name</label>
                <input type="text" id="v_name"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- BIRTHDATE --}}
            <div>
                <label class="text-sm font-medium">Birthdate</label>
                <input type="text" id="v_birthdate"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- GENDER --}}
            <div>
                <label class="text-sm font-medium">Gender</label>
                <input type="text" id="v_gender"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- CIVIL STATUS --}}
            <div>
                <label class="text-sm font-medium">Civil Status</label>
                <input type="text" id="v_civil_status"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- CONTACT --}}
            <div>
                <label class="text-sm font-medium">Contact</label>
                <input type="text" id="v_contact"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="text-sm font-medium">Email</label>
                <input type="text" id="v_email"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- ADDRESS --}}
            <div>
                <label class="text-sm font-medium">Address</label>
                <input type="text" id="v_address"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- POSITION --}}
            <div>
                <label class="text-sm font-medium">Position</label>
                <input type="text" id="v_position"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- DATE HIRED --}}
            <div>
                <label class="text-sm font-medium">Date Hired</label>
                <input type="text" id="v_date_hired"
                       class="w-full border rounded p-2" readonly>
            </div>

            {{-- EMPLOYMENT STATUS --}}
            <div>
                <label class="text-sm font-medium">Employment Status</label>
                <input type="text" id="v_status"
                       class="w-full border rounded p-2" readonly>
            </div>

        </div>


        {{-- MODAL FOOTER --}}
        <div class="mt-5 text-center">
            <button onclick="closeModal()"
                    class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">
                Close
            </button>
        </div>

    </div>

</div>



{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
<script>

{{-- ===============================
    SEARCH WORKER FUNCTION
================================ --}}
document.getElementById("workerSearch").addEventListener("keyup", function() {

    let value = this.value.toLowerCase();
    let rows = document.querySelectorAll("tbody tr");

    rows.forEach(row => {

        let text = row.innerText.toLowerCase();

        row.style.display = text.includes(value) ? "" : "none";

    });

});



{{-- ===============================
    OPEN MODAL
================================ --}}
function openModal(worker){

    document.getElementById('viewModal').classList.remove('hidden');
    document.getElementById('viewModal').classList.add('flex');

    document.getElementById('v_name').value =
        (worker.first_name ?? '') + ' ' +
        (worker.middle_name ?? '') + ' ' +
        (worker.last_name ?? '');

    document.getElementById('v_birthdate').value = worker.birthdate ?? '';
    document.getElementById('v_gender').value = worker.gender ?? '';
    document.getElementById('v_civil_status').value = worker.civil_status ?? '';
    document.getElementById('v_contact').value = worker.contact_number ?? '';
    document.getElementById('v_email').value = worker.email ?? '';
    document.getElementById('v_address').value = worker.address ?? '';
    document.getElementById('v_position').value = worker.position ?? '';
    document.getElementById('v_date_hired').value = worker.date_hired ?? '';
    document.getElementById('v_status').value = worker.employment_status ?? '';

}



{{-- ===============================
    CLOSE MODAL
================================ --}}
function closeModal(){

    document.getElementById('viewModal').classList.remove('flex');
    document.getElementById('viewModal').classList.add('hidden');

}

</script>

@endsection