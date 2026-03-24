<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Household;
use App\Models\PasswordResetRequest;
use App\Models\Resident;
use App\Models\ResidentPendingEdit;

class PollController extends Controller
{
    public function counts(): \Illuminate\Http\JsonResponse
    {
        $living = Resident::where('is_deceased', false);

        return response()->json([
            'pending_residents' => Resident::where('status', 'pending')->count(),
            'pending_edits' => ResidentPendingEdit::count(),
            'password_requests' => PasswordResetRequest::where('status', 'pending')->count(),
            'total_residents' => $living->count(),
            'total_households' => Household::count(),
            'total_families' => Family::count(),
        ]);
    }
}
