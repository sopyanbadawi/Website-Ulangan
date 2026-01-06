<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianIpWhitelist;

class CheckUjianIp
{
    public function handle(Request $request, Closure $next)
    {
        $params = $request->route()->parameters();
        $ujianId = $params['id'] ?? $params['ujian'] ?? null;

        if (!$ujianId) {
            abort(404, 'Ujian tidak ditemukan');
        }

        $ip = $request->ip();

        $ujian = UjianModel::find($ujianId);
        if (!$ujian) {
            abort(404, 'Ujian tidak ditemukan');
        }

        // Update status ujian otomatis
        $ujian->updateStatusIfNeeded();

        // Cek IP whitelist
        if (!UjianIpWhitelist::isAllowed($ujian->id, $ip)) {
            abort(403, 'IP Anda tidak diizinkan untuk ujian ini');
        }

        return $next($request);
    }
}
