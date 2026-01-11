<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\DB;
use App\Models\UjianModel;

/*
|--------------------------------------------------------------------------
| Artisan Commands
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


/*
|--------------------------------------------------------------------------
| Scheduler (Laravel 11 / 12)
|--------------------------------------------------------------------------
| Auto update status ujian:
| - draft   → aktif   (jika waktu mulai tercapai)
| - aktif   → selesai (jika waktu selesai terlewati)
*/

Schedule::call(function () {

    UjianModel::whereIn('status', ['draft', 'aktif'])
        ->each(function ($ujian) {
            $ujian->updateStatusIfNeeded();
        });
})->everyMinute();
