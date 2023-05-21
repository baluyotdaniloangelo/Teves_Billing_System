<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\ProductModel;
use Session;
use Validator;
use DataTables;

class CashiersReportController extends Controller
{
	
	/*Load client Interface*/
	public function cashierReport(){
		
		$title = "Cashier' Report";
		$data = array();
		if(Session::has('loginID')){
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
		}
		return view("pages.cashiers_report", compact('data','title'));
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getCashierReport(Request $request)
    {
		
		$list = CashiersReportModel::get();
		if ($request->ajax()) {
			
			    	$data = CashiersReportModel::join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
              		->get([				
					'teves_cashiers_report.cashiers_report_id',
					'user_tb.user_real_name',
					'teves_cashiers_report.teves_branch',
					'teves_cashiers_report.forecourt_attendant',
					'teves_cashiers_report.report_date',
					'teves_cashiers_report.shift',
					'teves_cashiers_report.created_at',
					'teves_cashiers_report.updated_at']);
			
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){
						$actionBtn = '
						<div align="center" class="action_table_menu_client">
						<a href="cashiers_report_form/'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editCashiersReport"></a>
						<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteCashiersReport"></a>
						</div>';
						return $actionBtn;
					})
					->rawColumns(['action'])
					->make(true);
		}
		
    }

	public function create_cashier_report_post(Request $request){
		
		$request->validate([
			'report_date'   => 'required'
        ], 
        [
			'report_date.required' => 'Report Date is required'
        ]
		);
		
			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate->user_idx 				= Session::get('loginID');
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->shift 				= $request->shift;
			$result = $CashiersReportCreate->save();
			
			/*Get Last ID*/
			$last_transaction_id = $CashiersReportCreate->cashiers_report_id;
			
			
			if($result){
				return response()->json(array('success' => "Cashier's Report Information Successfully Created!", 'cashiers_report_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	public function update_cashier_report_post(Request $request){
		
		$request->validate([
			'report_date'   => 'required'
        ], 
        [
			'report_date.required' => 'Report Date is required'
        ]
		);
		
			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate = CashiersReportModel::find($request->CashiersReportId);
			$CashiersReportCreate->user_idx 				= Session::get('loginID');
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->shift 					= $request->shift;
			$result = $CashiersReportCreate->update();
			
			/*Get Last ID
			$last_transaction_id = $CashiersReportCreate->cashiers_report_id;*/
			
			
			if($result){
				//return response()->json(['success'=>"Cashier's Report Information Successfully Created!"]);
				return response()->json(array('success' => "Cashier's Report Information Successfully Updated!", 'cashiers_report_id' => $request->CashiersReportId), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	/*Fetch client Information*/
	public function cashiers_report_info(Request $request){
		
		$CashiersReportID = $request->CashiersReportID;
		
		$data = CashiersReportModel::where('cashiers_report_id', $request->CashiersReportID)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
		
		return response()->json($data);
					
	}

	/*Fetch client Information*/
	public function cashiers_report_form($CashiersReportId){
		
		$data = array();
		if(Session::has('loginID')){
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
		}
		
		$title = "Cashier' Report";
		$product_data = ProductModel::all();
		$CashiersReportData = CashiersReportModel::where('cashiers_report_id', $CashiersReportId)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
			
		return view("pages.cashiers_report_form", compact('data','title','CashiersReportData','product_data','CashiersReportId'));	
		
	}

	/*Delete client Information*/
	public function delete_client_confirmed(Request $request){
		$clientID = $request->clientID;
		ClientModel::find($clientID)->delete();
		return 'Deleted';
	} 


	public function update_client_post(Request $request){
		$request->validate([
          'client_name'      		=> 'required|unique:teves_client_table,client_name,'.$request->clientID.',client_id',
		  'client_address'      	=> 'required',
		  'client_tin'      	=> 'required'
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
			$client = new ClientModel();
			$client = ClientModel::find($request->clientID);
			$client->client_name 		= $request->client_name;
			$client->client_address 	= $request->client_address;
			$client->client_tin 		= $request->client_tin;
			$result = $client->update();
			if($result){
				return response()->json(['success'=>'Client Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update client Information']);
			}
	}
	
	public function save_product_chashiers_report(Request $request){	

		$request->validate([
			'product_idx'  	=> 'required',
			
        ], 
        [
			'product_idx.required' 	=> 'Product is Required',
        ]
		);
			
			/*Get Last ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$beginning_reading 			= $request->beginning_reading;
			$closing_reading 			= $request->closing_reading;
			$calibration 				= $request->calibration;
			$product_manual_price 		= $request->product_manual_price;
			$component_id 				= $request->component_id;
				
				for($count = 0; $count < count($product_idx); $count++)
				{
					
						$product_idx_item 				= $product_idx[$count];
						$beginning_reading_item 		= $beginning_reading[$count];
						$closing_reading_item 			= $closing_reading[$count];
						$calibration_item 				= $calibration[$count];
						$product_manual_price_item 		= $product_manual_price[$count];
						$component_id_item 				= $component_id[$count];
					
					if($product_idx_item!=0){
						
								/*Product Details*/
								$product_info = ProductModel::find($product_idx_item, ['product_price']);					
								
								/*Check if Price is From Manual Price*/
								if($product_manual_price_item!=0){
									$product_price = $product_manual_price_item;
								}else{
									$product_price = $product_info->product_price;
								}
						
								$order_quantity = ($closing_reading_item - $beginning_reading_item) - $calibration_item;
								$peso_sales = ($order_quantity * $product_price);
								
							if($component_id_item==0){
									
								$CashiersReportModel_P1 = new CashiersReportModel_P1();
								
								$CashiersReportModel_P1->user_idx 					= Session::get('loginID');
								$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
								$CashiersReportModel_P1->product_idx 				= $product_idx_item;
								$CashiersReportModel_P1->beginning_reading 			= $beginning_reading_item;
								$CashiersReportModel_P1->closing_reading 			= $closing_reading_item;
								$CashiersReportModel_P1->calibration 				= $calibration_item;
								$CashiersReportModel_P1->order_quantity 			= $order_quantity;
								$CashiersReportModel_P1->product_price 				= $product_price;
								$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
								
								$CashiersReportModel_P1->save();
								
							}else{
								
								$CashiersReportModel_P1 = new CashiersReportModel_P1();
								$CashiersReportModel_P1 = CashiersReportModel_P1::find($component_id_item);
								
								$CashiersReportModel_P1->user_idx 					= Session::get('loginID');
								$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
								$CashiersReportModel_P1->product_idx 				= $product_idx_item;
								$CashiersReportModel_P1->beginning_reading 			= $beginning_reading_item;
								$CashiersReportModel_P1->closing_reading 			= $closing_reading_item;
								$CashiersReportModel_P1->calibration 				= $calibration_item;
								$CashiersReportModel_P1->order_quantity 			= $order_quantity;
								$CashiersReportModel_P1->product_price 				= $product_price;
								$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
								
								$CashiersReportModel_P1->update();
								
							}		
					}							
					
				}		
			
	}	
	
	public function get_cashiers_report_product_p1(Request $request){		
	
			$data =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
				->orderBy('cashiers_report_p1_id', 'asc')
              	->get([
					'cashiers_report_p1_id',
					'cashiers_report_id',
					'product_idx',
					'beginning_reading',
					'closing_reading',
					'calibration',
					'order_quantity',
					'product_price',
					'order_total_amount'
					]);
		
			return response()->json($data);
	}
	
	
	public function delete_cashiers_report_product_p1(Request $request){		
			
		$component_id = $request->component_id;
		CashiersReportModel_P1::find($component_id)->delete();
		return 'Deleted';
		
	}
}
