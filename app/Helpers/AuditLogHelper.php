<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuditLogHelper
{
    public static function log($accion, $detalles = null)
    {
        $userId = Auth::check() ? Auth::id() : null;
        $ip = request()->ip();
        $userAgent = request()->userAgent();

        DB::table('audit_logs')->insert([
            'id_usuario' => $userId,
            'accion' => $accion,
            'ip_address' => $ip,
            'user_agent' => $userAgent,
            'created_at' => now(),
        ]);
    }
}