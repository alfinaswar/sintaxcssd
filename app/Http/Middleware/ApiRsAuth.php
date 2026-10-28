<?php

namespace App\Http\Middleware;

use App\Models\MasterRs;
use Closure;
use Illuminate\Support\Facades\Hash;

class ApiRsAuth
{
    public function handle($request, Closure $next)
    {
        $username = trim((string) $request->header('X-API-Username'));
        $password = (string) $request->header('X-API-Password');

        $rs = $username !== '' ? MasterRs::where('username', $username)->first() : null;

        // Dummy hash agar waktu respons tidak membocorkan apakah username terdaftar.
        $hash = ($rs && is_string($rs->password) && $rs->password !== '')
            ? $rs->password
            : '$2y$10$usesomesillystringforsaltandhashedpasswordxxxxxxxxxxxxxxx';

        if (!$rs || $password === '' || !Hash::check($password, $hash)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->set('rs', $rs);

        return $next($request);
    }
}
