<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;
	
	protected $table = 'teves_sms_logs_table';

protected $primaryKey = 'sms_log_id';

protected $fillable = [

    'campaign_id',
    'client_id',
    'client_name',
    'customer_type',
    'mobile_number',
    'sms_message',
    'provider',
    'provider_message_id',
    'sms_status',
    'provider_response',
    'error_message',
    'sent_at',
    'delivered_at'

];
}
