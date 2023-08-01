<?php
namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashiersReportModel extends Model
{
    //use HasFactory;
	protected $table = 'teves_cashiers_report';

	protected $fillable = [
		'cashiers_report_id',
        'user_idx',
		'teves_branch',
        'cashiers_name',
		'forecourt_attendant',
        'report_date',
        'shift',
        'created_at',
		'updated_at'
    ];
    protected $primaryKey = 'cashiers_report_id';
}
