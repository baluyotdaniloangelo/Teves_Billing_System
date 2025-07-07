<?php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddSoftDeletesToAllTables extends Migration
{
    public function up()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    $tableBlueprint->softDeletes();
                });
            }
        }
    }

    public function down()
    {
        $tables = DB::connection()->getDoctrineSchemaManager()->listTableNames();

        foreach ($tables as $table) {
            if (Schema::hasColumn($table, 'deleted_at')) {
                Schema::table($table, function (Blueprint $tableBlueprint) use ($table) {
                    $tableBlueprint->dropSoftDeletes();
                });
            }
        }
    }
}