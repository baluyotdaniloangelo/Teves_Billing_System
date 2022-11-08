<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Request;

//use Illuminate\Http\Request;
//use App\Models\User;
//use App\Models\SiteForAdminModel;
//use Session;
//use Validator;
//use DataTables;
//use App\Models\MeterReadingModel;

class CAMRGatewayDeviceController extends Controller
{
	/*Check Server Time*/
	public function check_time(Request $request){

    $server_time=date('Y-m-d H:i:s');
	return $server_time;
		
	}	 

	/*Save Reading of Meters*/
	public function http_post_server(Request $request){
		
    $server_time=date('Y-m-d H:i:s');
	echo "OK, $server_time";
	
	}
    
}
