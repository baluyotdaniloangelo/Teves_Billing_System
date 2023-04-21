<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ReceivablesModel extends Model
{

	protected $table = 'teves_receivable_table';
	
	protected $fillable = [
        'client_idx',
		'billing_date',
		'control_number',
		'or_number',
		'payment_term',
		'receivable_description',
		'receivable_amount',
		'receivable_remaining_balance',
    ];
	
	protected $primaryKey = 'receivable_id';
    
}
