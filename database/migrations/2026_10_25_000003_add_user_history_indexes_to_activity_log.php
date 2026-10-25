<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection(config('activitylog.database_connection'));
        $table = config('activitylog.table_name', 'activity_log');
        if (!$schema->hasTable($table)) throw new RuntimeException('Install the existing activity-log table before adding its history indexes.');
        $schema->table($table, function (Blueprint $table) {
            $table->index(['created_at', 'id'], 'user_activity_date_index');
            $table->index(['causer_id', 'created_at', 'id'], 'user_activity_actor_date_index');
        });
    }

    public function down(): void
    {
        Schema::connection(config('activitylog.database_connection'))->table(config('activitylog.table_name', 'activity_log'), function (Blueprint $table) {
            $table->dropIndex('user_activity_date_index');
            $table->dropIndex('user_activity_actor_date_index');
        });
    }
};
