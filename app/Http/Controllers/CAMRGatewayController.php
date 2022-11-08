<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GatewayModel;
use Session;
use Validator;
use DataTables;

class CAMRGatewayController extends Controller
{

	/*Fetch Gateway List using Datatable*/
	public function getGateway(Request $request)
    {

		$siteID = $request->siteID;
		$gateway = GatewayModel::get();
		if ($request->ajax()) {	
		
		$data= GatewayModel::select(
		'id',
		'rtu_sn_number',
        'mac_addr',
        'phone_no_or_ip_address',
		'rtu_physical_location',
		'update_rtu',
        'update_rtu_location',
		'update_rtu_ssh',
		'update_rtu_force_lp',
        'idf_number',
        'switch_name',
        'idf_port',
        'last_log_update',
        'soft_rev'
		)->where('rtu_site_id', $siteID);
		
		return DataTables::of($data)
				->addIndexColumn()
				
				->addColumn('update', function($row){
                    
					$update_rtu = $row->update_rtu;
					$update_rtu_location = $row->update_rtu_location;
					$update_rtu_ssh = $row->update_rtu_ssh;
					$update_rtu_force_lp = $row->update_rtu_force_lp;
					
					if($update_rtu==1){
						$csv_update_input_value = '<input class="form-check-input" type="checkbox" id="disablecsvUpdate" data-id="'.$row->id.'" checked>';
					}else{
						$csv_update_input_value = '<input class="form-check-input" type="checkbox" id="enablecsvUpdate" data-id="'.$row->id.'">';
					}

					if($update_rtu_location==1){
						$location_update_input_value = '<input class="form-check-input" type="checkbox" id="disablesitecodeUpdate" data-id="'.$row->id.'" checked>';
					}else{
						$location_update_input_value = '<input class="form-check-input" type="checkbox" id="enablesitecodeUpdate" data-id="'.$row->id.'">';
					}
					
					/* if($update_rtu_ssh==1){
						$ssh_input_value = '<input class="form-check-input" type="checkbox" id="disableSSH" data-id="'.$row->id.'" checked>';
					}else{
						$ssh_input_value = '<input class="form-check-input" type="checkbox" id="enableSSH" data-id="'.$row->id.'">';
					}		
					
					if($update_rtu_force_lp==1){
						$force_lp_input_value = '<input class="form-check-input" type="checkbox" id="disableLP" data-id="'.$row->id.'" checked>';
					}else{
						$force_lp_input_value = '<input class="form-check-input" type="checkbox" id="enableLP" data-id="'.$row->id.'">';
					}	 */				
					
					/*Add this option for Client that uses 3g/4g/5g Gateway or those with cloud server that DEC has access*/
					/* $otherOptionsBtn = '
							<div class="form-check form-switch">
								'.$ssh_input_value.'
								<label class="form-check-label" for="flexSwitchCheckChecked" title="Click to Enable Remote SSH">SSH</label>
							</div>
							<div class="form-check form-switch">
								'.$force_lp_input_value.'
								<label class="form-check-label" for="flexSwitchCheckChecked" title="Force to Download Load Profile">Load Profile</label>
							</div>'; */
					
					$updateBtn = '
					<div class="row mb-0">
						<div class="col-sm-12">
							<div class="form-check form-switch">
								'.$csv_update_input_value.'
								<label class="form-check-label" for="flexSwitchCheckDefault" title="Click to Update Gateway Site Code">CSV</label>
							</div>
							<div class="form-check form-switch">
								'.$location_update_input_value.'
								<label class="form-check-label" for="flexSwitchCheckChecked" title="Click to Update Gateway Site Code">Site Code</label>
							</div>
							
						</div>
					</div>';
                    return $updateBtn;
					
                })				
				
                ->addColumn('action', function($row){
                    
					$last_log_update = $row->last_log_update;
					
						/*FROM LOGS*/
						$_date_format = strtotime($last_log_update);
						$date_format = date('Y-m-d H:i:s',$_date_format);		
										
					$actionBtn = '
					<div align="center" class="action_table_menu_gateway">
					<a href="' . url('site_overview/'.$row->id) .'" class="btn-info btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_view"></a>
					<a href="' . url('edit_gateway/'.$row->id) .'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit"></a>
					<a href="#" data-id="'.$row->id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSite"></a>
					</div>';
                    return $actionBtn;
                })
				
				->addColumn('status', function($row){
                    
					$last_log_update = $row->last_log_update;
					
						/*FROM LOGS*/
						$_date_format = strtotime($last_log_update);
						$date_format = date('Y-m-d H:i:s',$_date_format);
						
						/*SERVER DATETIME*/
						$_server_time=date('Y-m-d H:i:s');
						$server_time_current = strtotime($_server_time);
						
						$date1=date_create("$_server_time");
						$date2=date_create("$date_format");
								
						$diff					= date_diff($date1,$date2);
						$days_last_active 		= $diff->format("%a");
						
						if($last_log_update == "0000/00/00 00:00"){
							$statusBtn = '<div style="color:black; font-weight:bold; text-align:center;" title="No Meter Connected on the Gateway/Spare">No Data</div>';
						}
						else if($diff->format("%a")<=0){
							$statusBtn = '<a href="#" class="btn-circle btn-sm bi bi-cloud-check-fill btn_icon_table btn_icon_table_status_online" title="Last Status Update : '.$last_log_update.'"></a>';
						}else{
							$statusBtn = '<a href="#" class="btn-circle btn-sm bi bi-cloud-slash-fill btn_icon_table btn_icon_table_status_offline" title="Offline Since : '.$last_log_update.'"></a>';
						}		
										
					$actionBtn = '
					<div align="center" class="row_status_table_gateway">
					'.$statusBtn.'
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['update','status','action'])
                ->make(true);
		
		}	
		
    }


	/*Fetch Site Information*/
	public function gateway_info(Request $request){

		$gatewayID = $request->gatewayID;
		$data = GatewayModel::find($gatewayID);
		return response()->json($data);
		
	}

	/*Delete Site Information*/
	public function delete_gateway_confirmed(Request $request){

		$gatewayID = $request->gatewayID;
		GatewayModel::find($gatewayID)->delete();
		return 'Deleted';
		
	} 
 
	public function create_gateway_post(Request $request){
		
		$request->validate([
          'gateway_sn'      	=> 'required|unique:meter_rtu,rtu_sn_number',
		  'gateway_mac'      	=> 'required|unique:meter_rtu,mac_addr',
		  'gateway_ip'      	=> 'required|unique:meter_rtu,phone_no_or_ip_address',
		  'physical_location'   => 'required',
        ], 
        [
			'gateway_sn.required' => 'Gateway Serial Number is required',
			'gateway_mac.required' => 'MAC Address is Required',
			'gateway_ip.required' => 'IP Address/Sim # is Required',
			'physical_location.required' => 'Physical Location is Required'
        ]
		);

			$data = $request->all();
			#insert Gateway
					 
			$gateway = new GatewayModel();
			$gateway->makeHidden(['update_rtu','update_rtu_location','update_rtu_ssh','update_rtu_force_lp']);
			$gateway->rtu_site_id = $request->siteID;
			$gateway->rtu_site_code = $request->siteCode;
			$gateway->rtu_sn_number = $request->gateway_sn;
			$gateway->mac_addr = $request->gateway_mac;
			$gateway->phone_no_or_ip_address = $request->gateway_ip;
			$gateway->idf_number = $request->idf_number;
			$gateway->switch_name = $request->idf_switch;
			$gateway->idf_port = $request->idf_port;
			$gateway->rtu_physical_location = $request->physical_location;
			$gateway->gateway_description = $request->gateway_description;
			$gateway->connection_type = $request->connection_type;
			
			$result = $gateway->save();
			
			if($result){
				return response()->json(['success'=>'Gateway Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Gateway Information']);
			}
	}

	public function update_gateway_post(Request $request){
		
		
		$request->validate([
          'business_entity'      	=> 'required',
		  'site_code'      			=> 'required|unique:meter_site,site_code,'.$request->SiteID,
		  'site_description'      	=> 'required|unique:meter_site,site_name,'.$request->SiteID,
        ], 
        [
			'business_entity.required' => 'Business Entity is required',
			'site_code.required' => 'Site Code is Required',
			'site_description.required' => 'Site Description is Required'
        ]
		);

			$data = $request->all();
			#insert
					
			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->business_entity = $request->business_entity;
			$site->site_code = $request->site_code;
			$site->site_name = $request->site_description;
			$site->building_type = $request->building_type;
			//$site->site_cut_off = $request->site_cut_off;
			$site->device_ip_range = $request->device_ip_range;
			$site->ip_network = $request->ip_network;
			$site->ip_netmask = $request->ip_netmask;
			$site->ip_gateway = $request->ip_gateway;
			
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'Site Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Site Information']);
			}
	}


	/*Enable CSV Update for Gateway*/
	public function enablecsvUpdate(Request $request){
		
			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu = 1;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'CSV Enabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Enabling CSV Update']);
			}
	}

	/*Disable CSV Update for Gateway*/
	public function disablecsvUpdate(Request $request){
		

			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu = 0;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'CSV Disabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Disabling CSV Update']);
			}
	}

	/*Enable CSV Update for Gateway*/
	public function enablesitecodeUpdate(Request $request){
		
			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_location = 1;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'Site Code Update Enabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Enabling Site Code Update']);
			}
	}

	/*Disable CSV Update for Gateway*/
	public function disablesitecodeUpdate(Request $request){		

			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_location = 0;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'Site Code Update Disabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Disabling Site Code Update']);
			}
			
	}
	
	/*Enable SSH for Gateway*/
	public function enableSSH(Request $request){
		
			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_ssh = 1;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'SSH Enabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Enabling SSH']);
			}
			
	}

	/*Disable SSH for Gateway*/
	public function disableSSH(Request $request){		

			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_ssh = 0;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'SSH Disabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Disabling SSH']);
			}
			
	}	
	
	/*Enable Force Load Profile*/
	public function enableLP(Request $request){
		
			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_force_lp = 1;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'Force Load Profile Enabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Enabling Force Load Profile']);
			}
			
	}

	/*Disable Force Load Profile*/
	public function disableLP(Request $request){		

			$site = new GatewayModel();
			$site = GatewayModel::find($request->gatewayID);
			$site->update_rtu_force_lp = 0;
			$result = $site->update();
			
			if($result){
				return response()->json(['success'=>'Force Load Profile Disabled!']);
			}
			else{
				return response()->json(['success'=>'Error on Disabling Force Load Profile']);
			}
			
	}	
	
	
	
	
	
	
	
	
	
	
	/*End of Controller*/
}