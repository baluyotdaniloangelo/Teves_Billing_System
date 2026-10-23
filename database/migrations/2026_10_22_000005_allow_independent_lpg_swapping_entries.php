<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Use MySQL directly so changing nullability does not require Doctrine DBAL.
        DB::statement('ALTER TABLE `teves_cashiers_report_lpg_swapping` MODIFY `received_product_idx` INT UNSIGNED NULL, MODIFY `replaced_product_idx` INT UNSIGNED NULL');
    }

    public function down(): void
    {
        if (DB::table('teves_cashiers_report_lpg_swapping')->whereNull('received_product_idx')->orWhereNull('replaced_product_idx')->exists()) {
            throw new RuntimeException('Cannot restore required swapping sides while independent received/issued entries exist.');
        }
        DB::statement('ALTER TABLE `teves_cashiers_report_lpg_swapping` MODIFY `received_product_idx` INT UNSIGNED NOT NULL, MODIFY `replaced_product_idx` INT UNSIGNED NOT NULL');
    }
};
