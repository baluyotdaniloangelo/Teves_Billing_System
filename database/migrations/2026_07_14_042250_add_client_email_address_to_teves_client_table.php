<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->string('client_email_address', 255)
                  ->nullable()
                  ->after('client_contact_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teves_client_table', function (Blueprint $table) {
            $table->dropColumn('client_email_address');
        });
    }
};