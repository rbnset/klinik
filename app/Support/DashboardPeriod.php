<?php

namespace App\Support;

use Carbon\Carbon;

class DashboardPeriod
{
    public static function resolve(array $filters): array
    {
        $mode = $filters['mode_periode'] ?? 'bulan_tahun';

        if ($mode === 'rentang_tanggal') {
            $start = filled($filters['tanggal_mulai'] ?? null)
                ? Carbon::parse($filters['tanggal_mulai'])->startOfDay()
                : now()->startOfMonth();
            $end = filled($filters['tanggal_akhir'] ?? null)
                ? Carbon::parse($filters['tanggal_akhir'])->endOfDay()
                : $start->copy()->endOfDay();

            if ($end->lt($start)) {
                [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
            }
        } else {
            $year = (int) ($filters['tahun'] ?? now()->year);
            $month = (int) ($filters['bulan'] ?? now()->month);
            $start = Carbon::create($year, $month, 1)->startOfDay();
            $end = $start->copy()->endOfMonth();
        }

        $days = $start->copy()->startOfDay()->diffInDays($end->copy()->startOfDay()) + 1;

        return [
            'start' => $start,
            'end' => $end,
            'days' => $days,
            'granularity' => $days <= 62 ? 'daily' : 'monthly',
        ];
    }
}
