<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersReportModel_P5 extends Model
{
	
	protected $table = 'teves_cashiers_report_p5';

	protected $fillable = [
        'user_idx',
		'cashiers_report_id',
		'one_thousand_deno',
		'five_hundred_deno',
		'two_hundred_deno',
		'one_hundred_deno',
		'fifty_deno',
		'twenty_deno',
		'ten_deno',
		'five_deno',
		'one_deno',
		'twenty_five_cent_deno',
		'cash_drop',
		'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_p5_id';
	
}
