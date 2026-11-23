<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teves_cash_deposits', function (Blueprint $table) {
            $table->bigIncrements('cash_deposit_id');
            $table->unsignedBigInteger('bank_idx')->nullable();
            $table->string('cash_deposit_bank')->nullable();
            $table->dateTime('cash_deposit_date')->nullable();
            $table->decimal('cash_deposit_amount', 15, 2);
            $table->string('cash_deposit_reference')->nullable();
            $table->text('cash_deposit_remarks')->nullable();
            $table->string('cash_deposit_photo')->nullable();
            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->unsignedBigInteger('updated_by_user_id')->nullable();
            $table->unsignedBigInteger('deleted_by_user_id')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->index(['bank_idx', 'cash_deposit_date']);
            $table->index(['created_by_user_id', 'created_at']);
        });

        if (Schema::hasTable('teves_cashiers_report_p9')) {
            DB::table('teves_cashiers_report_p9')->orderBy('cashiers_report_p9_id')
                ->chunkById(100, function ($rows) {
                    $entries = $rows->map(function ($row) {
                        return [
                            'cash_deposit_id' => $row->cashiers_report_p9_id,
                            'bank_idx' => $row->bank_idx,
                            'cash_deposit_bank' => $row->cash_deposit_bank,
                            'cash_deposit_date' => $row->cash_deposit_date,
                            'cash_deposit_amount' => $row->cash_deposit_amount,
                            'cash_deposit_reference' => $row->cash_deposit_reference,
                            'cash_deposit_remarks' => $row->cash_deposit_remarks,
                            'cash_deposit_photo' => $row->cash_deposit_photo,
                            'created_by_user_id' => $row->created_by_user_id,
                            'updated_by_user_id' => $row->updated_by_user_id,
                            'deleted_by_user_id' => $row->deleted_by_user_id,
                            'created_at' => $row->created_at,
                            'updated_at' => $row->updated_at,
                            'deleted_at' => $row->deleted_at,
                        ];
                    })->all();
                    DB::table('teves_cash_deposits')->insert($entries);
                }, 'cashiers_report_p9_id');
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('teves_cash_deposits');
    }
};
