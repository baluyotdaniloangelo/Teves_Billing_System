<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsCampaign extends Model
{
    use HasFactory;
	 protected $table = 'teves_sms_campaign_table';

protected $primaryKey = 'campaign_id';

protected $fillable = [

    'campaign_title',
    'customer_type',
    'sms_message',
    'total_recipients',
    'total_sent',
    'total_failed',
    'provider',
    'campaign_status',
    'created_by',
    'started_at',
    'completed_at'

];
}
