<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BillingTransactionModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;

class BillingTransactionController extends Controller
{
	
	/*Load Site Interface*/
	public function billing(){
		
		$title = 'Billing Transaction List';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('id', '=', Session::get('loginID'))->first();
			
			$product_data = ProductModel::all();
			
			$client_data = ClientModel::all();
		
		}

		return view("pages.billing", compact('data','title','product_data','client_data'));
		
	}   
	
	/*Fetch Site List using Datatable*/
	public function getBillingTransactionList(Request $request)
    {

		$list = BillingTransactionModel::get();
		if ($request->ajax()) {

    	$data = BillingTransactionModel::join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
              		->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_billing_table.client_idx')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_billing_table.product_idx',
					'teves_product_table.product_name',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_billing_table.client_idx',
					'teves_client_table.client_name',
					'teves_billing_table.order_date',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time',
					'teves_billing_table.updated_at']);
		

		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
                    
					$last_log_update = $row->last_log_update;
					
						/*FROM LOGS*/
						$_date_format = strtotime($last_log_update);
						$date_format = date('Y-m-d H:i:s',$_date_format);		
										
					$actionBtn = '
					<div align="center" class="action_table_menu_site">
					<a href="#" data-id="'.$row->id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editSite"></a>
					<a href="#" data-id="'.$row->id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSite"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		
		}
		
		
    }

	/*Fetch Site Information*/
	public function site_info(Request $request){

		$siteID = $request->siteID;
		$data = BillingTransactionModel::find($siteID);
		return response()->json($data);
		
	}

	/*Delete Site Information*/
	public function delete_site_confirmed(Request $request){

		$siteID = $request->siteID;
		BillingTransactionModel::find($siteID)->delete();
		return 'Deleted';
		
	} 

	public function create_site_post(Request $request){
		
		
		$request->validate([
          'business_entity'      	=> 'required',
		  'site_code'      			=> 'required|unique:meter_site,site_code',
		  'site_description'      	=> 'required|unique:meter_site,site_name',
        ], 
        [
			'business_entity.required' => 'Business Entity is required',
			'site_code.required' => 'Site Code is Required',
			'site_description.required' => 'Site Description is Required'
        ]
		);

			$data = $request->all();
			#insert
					
			$site = new BillingTransactionModel();
			$site->business_entity = $request->business_entity;
			$site->site_code = $request->site_code;
			$site->site_name = $request->site_description;
			$site->building_type = $request->building_type;
			//$site->site_cut_off = $request->site_cut_off;
			$site->device_ip_range = $request->device_ip_range;
			$site->ip_network = $request->ip_network;
			$site->ip_netmask = $request->ip_netmask;
			$site->ip_gateway = $request->ip_gateway;
			
			$result = $site->save();
			
			if($result){
				return response()->json(['success'=>'Site Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Site Information']);
			}
	}

	public function update_site_post(Request $request){
		
		
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
					
			$site = new BillingTransactionModel();
			$site = BillingTransactionModel::find($request->SiteID);
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

	
}
