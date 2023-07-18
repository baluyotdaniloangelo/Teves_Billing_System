<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersReportModel_P4 extends Model
{
	
	protected $table = 'teves_cashiers_report_p4';

	protected $fillable = [
        'user_idx',
		'cashiers_report_id',
		'description_p4',
		'amount_p4',
		'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_p4_id';
	
}
