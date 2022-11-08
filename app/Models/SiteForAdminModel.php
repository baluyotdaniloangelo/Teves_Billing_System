<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteForAdminModel extends Model
{
    //use HasFactory;
	protected $table = 'meter_site';
	
	protected $fillable = [
        'business_entity',
        'site_code',
        'site_name',
		'building_type',
		'site_cut_off',
        'device_ip_range',
        'ip_network',
        'ip_netmask',
        'ip_gateway'
    ];
    
}
