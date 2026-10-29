<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            // NULL means the sale predates snapshots; [] means no components at sale time.
            $table->longText('set_components_snapshot')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('teves_cashiers_report_lpg_ar_so', function (Blueprint $table) {
            $table->dropColumn('set_components_snapshot');
        });
    }
};
