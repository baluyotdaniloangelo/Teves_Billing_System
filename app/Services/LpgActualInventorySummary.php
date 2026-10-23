<?php

namespace App\Services;

use Illuminate\Support\Collection;

class LpgActualInventorySummary
{
    public function rows(int $reportId, Collection $rows): Collection
    {
        return $rows->map(function ($row) {
            $theoretical = $row['ending_quantity'] ?? null;
            $actual = $theoretical !== null ? (float) $theoretical : null;
            $row['theoretical_ending_quantity'] = $theoretical;
            $row['actual_quantity'] = $actual;
            $row['variance_quantity'] = $actual !== null && $theoretical !== null ? round($actual - (float) $theoretical, 2) : null;
            return $row;
        })->values();
    }
}
