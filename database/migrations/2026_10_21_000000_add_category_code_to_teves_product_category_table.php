<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teves_product_category', function (Blueprint $table) {
            $table->string('category_code', 3)->nullable()->after('category_name');
        });

        $usedCodes = [];
        $categories = DB::table('teves_product_category')
            ->orderBy('category_id')
            ->get(['category_id', 'category_name']);

        foreach ($categories as $category) {
            $letters = preg_replace('/[^A-Z]/', '', strtoupper((string) $category->category_name));
            $code = substr(str_pad($letters, 3, 'X'), 0, 3);

            if (isset($usedCodes[$code])) {
                for ($sequence = 0; $sequence < 17576; $sequence++) {
                    $code = $this->codeFromSequence($sequence);
                    if (!isset($usedCodes[$code])) {
                        break;
                    }
                }
            }

            if (isset($usedCodes[$code])) {
                throw new RuntimeException('Unable to assign unique three-letter product category codes.');
            }

            $usedCodes[$code] = true;
            DB::table('teves_product_category')
                ->where('category_id', $category->category_id)
                ->update(['category_code' => $code]);
        }

        Schema::table('teves_product_category', function (Blueprint $table) {
            $table->unique('category_code');
        });
    }

    public function down(): void
    {
        Schema::table('teves_product_category', function (Blueprint $table) {
            $table->dropUnique('teves_product_category_category_code_unique');
            $table->dropColumn('category_code');
        });
    }

    private function codeFromSequence(int $sequence): string
    {
        $code = '';
        for ($position = 0; $position < 3; $position++) {
            $code = chr(65 + ($sequence % 26)) . $code;
            $sequence = intdiv($sequence, 26);
        }
        return $code;
    }
};