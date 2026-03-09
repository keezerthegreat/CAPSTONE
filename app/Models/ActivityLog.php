<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'module',
        'description',
        'ip_address',
    ];

    /**
     * Log an activity. Call this from any controller.
     */
    public static function log(string $action, string $module, string $description): void
    {
        self::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name ?? 'System',
            'action'      => $action,
            'module'      => $module,
            'description' => $description,
            'ip_address'  => request()->ip(),
        ]);
    }
}
