<?php

namespace App\Http\Middleware;

use App\Models\MasterRs;
use Closure;
use Illuminate\Support\Facades\Hash;

class ApiRsAuth
{
    public function handle($request, Closure $next)
    {
        $username = (string) $request->header('X-API-Username');
        $password = (string) $request->header('X-API-Password');

        $rs = $username !== '' ? MasterRs::where('username', $username)->first() : null;


        $hash = $rs->password ?? '$2y$10$usesomesillystringforsaltandhashedpasswordxxxxxxxxxxxxxxx';

        if (!Hash::check($password, $hash) || !$rs) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->attributes->set('rs', $rs);

        return $next($request);
    }
}
