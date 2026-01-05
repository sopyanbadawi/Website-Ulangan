<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UjianModel;
use App\Models\UjianIpWhitelist;

class CheckUjianIp
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Ambil ID ujian dari route, misal: /ujian/{id}/start
        $ujianId = $request->route('id'); // sesuaikan nama param route

        if (!$ujianId) {
            return abort(404, 'Ujian tidak ditemukan.');
        }

        $ip = $request->ip(); // Ambil IP user saat ini

        // Cek apakah ujian ada
        $ujian = UjianModel::find($ujianId);
        if (!$ujian) {
            return abort(404, 'Ujian tidak ditemukan.');
        }

        // Update status otomatis (draft → aktif → selesai)
        $ujian->updateStatusIfNeeded();

        // Cek whitelist IP
        if (!UjianIpWhitelist::isAllowed($ujianId, $ip)) {
            return abort(403, 'Akses ujian ditolak: IP Anda tidak diizinkan.');
        }

        return $next($request);
    }
}
