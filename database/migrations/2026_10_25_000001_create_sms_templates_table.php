<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teves_sms_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_key', 80)->unique();
            $table->string('name', 100);
            $table->string('scope', 20);
            $table->string('situation', 255)->nullable();
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('version')->default(1);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
        });
        $now = now();
        foreach (config('sms.templates', []) as $key => $template) {
            DB::table('teves_sms_templates')->insert([
                'template_key' => $key, 'name' => $template['name'], 'scope' => $template['scope'],
                'situation' => $template['situation'], 'message' => $template['message'],
                'is_active' => true, 'version' => 1, 'created_at' => $now, 'updated_at' => $now,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_sms_templates');
    }
};
