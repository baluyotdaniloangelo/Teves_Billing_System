<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P2;
use App\Models\CashiersReportModel_P3;
use App\Models\CashiersReportModel_P4;
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

	public function delete_cashiers_report_info(Request $request){		
			
		$CashiersReportID = $request->CashiersReportID;
		CashiersReportModel::find($CashiersReportID)->delete();
		return 'Deleted';
		
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
			
		return view("pages.cashiers_report_form_main", compact('data','title','CashiersReportData','product_data','CashiersReportId'));	
		
	}

	public function cashiers_report_p1_info(Request $request){

		$CHPH1_ID = $request->CHPH1_ID;
		
		$data =  CashiersReportModel_P1::where('cashiers_report_p1_id', $CHPH1_ID)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p1.product_idx')
				->get([
					'teves_product_table.product_name',
					'teves_product_table.product_id',
					'teves_cashiers_report_p1.cashiers_report_p1_id',
					'teves_cashiers_report_p1.beginning_reading',
					'teves_cashiers_report_p1.closing_reading',
					'teves_cashiers_report_p1.calibration',
					'teves_cashiers_report_p1.order_quantity',
					'teves_cashiers_report_p1.product_price',
					'teves_cashiers_report_p1.order_total_amount'
					]);			
					
		return response()->json($data);
		
	}
	
	public function save_product_cashiers_report_p1(Request $request){	

		$request->validate([
			'product_idx'  			=> 'required',
			'beginning_reading'  	=> 'required',
			'closing_reading'  		=> 'required',		
        ], 
        [
			'product_idx.required' 	=> 'Product is Required',
			'beginning_reading.required' 	=> 'Beginning Reading is Required',
			'closing_reading.required' 	=> 'Closing Reading is Required',
        ]
		);
			
			/*Get Last ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$beginning_reading 			= $request->beginning_reading;
			$closing_reading 			= $request->closing_reading;
			$calibration 				= $request->calibration;
			$product_manual_price 		= $request->product_manual_price;
			
			$CHPH1_ID 				= $request->CHPH1_ID;

								/*Product Details*/
								$product_info = ProductModel::find($product_idx, ['product_price']);					
								
								/*Check if Price is From Manual Price*/
								if($product_manual_price!=0){
									$product_price = $product_manual_price;
								}else{
									$product_price = $product_info->product_price;
								}
						
								$order_quantity = ($closing_reading - $beginning_reading) - $calibration;
								$peso_sales = ($order_quantity * $product_price);
								
								if($CHPH1_ID=='' || $CHPH1_ID ==0){	
								
									$CashiersReportModel_P1 = new CashiersReportModel_P1();
									
									$CashiersReportModel_P1->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P1->product_idx 				= $product_idx;
									$CashiersReportModel_P1->beginning_reading 			= $beginning_reading;
									$CashiersReportModel_P1->closing_reading 			= $closing_reading;
									$CashiersReportModel_P1->calibration 				= $calibration;
									$CashiersReportModel_P1->order_quantity 			= $order_quantity;
									$CashiersReportModel_P1->product_price 				= $product_price;
									$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P1->save();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P1 = new CashiersReportModel_P1();
									$CashiersReportModel_P1 = CashiersReportModel_P1::find($CHPH1_ID);
									
									$CashiersReportModel_P1->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P1->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P1->product_idx 				= $product_idx;
									$CashiersReportModel_P1->beginning_reading 			= $beginning_reading;
									$CashiersReportModel_P1->closing_reading 			= $closing_reading;
									$CashiersReportModel_P1->calibration 				= $calibration;
									$CashiersReportModel_P1->order_quantity 			= $order_quantity;
									$CashiersReportModel_P1->product_price 				= $product_price;
									$CashiersReportModel_P1->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P1->update();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
	}	
	
	public function get_cashiers_report_product_p1(Request $request){		
	
			$data =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p1.product_idx')
				->orderBy('cashiers_report_p1_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_cashiers_report_p1.product_price',
					'teves_cashiers_report_p1.cashiers_report_p1_id',
					'teves_cashiers_report_p1.cashiers_report_id',
					'teves_cashiers_report_p1.beginning_reading',
					'teves_cashiers_report_p1.closing_reading',
					'teves_cashiers_report_p1.calibration',
					'teves_cashiers_report_p1.order_quantity',
					'teves_cashiers_report_p1.order_total_amount'
					]);
		
			return response()->json($data);
	}
	
	public function delete_cashiers_report_product_p1(Request $request){		
			
		$CHPH1_ID = $request->CHPH1_ID;
		CashiersReportModel_P1::find($CHPH1_ID)->delete();
		return 'Deleted';
		
	}
	
	
	/*Part Two (Other Reports)*/
	public function save_product_cashiers_report_PH2(Request $request){	

		$request->validate([
			'product_idx'  		=> 'required',
			'order_quantity'  	=> 'required'		
        ], 
        [
			'product_idx.required' 	=> 'Product is Required',
			'order_quantity.required' 	=> 'Order Quantity is Required',
        ]
		);
			
			/*Get Cashier Report ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$order_quantity 			= $request->order_quantity;
			$product_manual_price 		= $request->product_manual_price;
			
			$CHPH2_ID 				= $request->CHPH2_ID;

								/*Product Details*/
								$product_info = ProductModel::find($product_idx, ['product_price']);					
								
								/*Check if Price is From Manual Price*/
								if($product_manual_price!=0){
									$product_price = $product_manual_price;
								}else{
									$product_price = $product_info->product_price;
								}
						
								$peso_sales = ($order_quantity * $product_price);
								
								if($CHPH2_ID=='' || $CHPH2_ID ==0){	
								
									$CashiersReportModel_P2 = new CashiersReportModel_P2();
									
									$CashiersReportModel_P2->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P2->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P2->product_idx 				= $product_idx;
									$CashiersReportModel_P2->order_quantity 			= $order_quantity;
									$CashiersReportModel_P2->product_price 				= $product_price;
									$CashiersReportModel_P2->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P2->save();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P2 = new CashiersReportModel_P2();
									$CashiersReportModel_P2 = CashiersReportModel_P2::find($CHPH2_ID);
									
									//$CashiersReportModel_P2->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P2->product_idx 				= $product_idx;
									$CashiersReportModel_P2->order_quantity 			= $order_quantity;
									$CashiersReportModel_P2->product_price 				= $product_price;
									$CashiersReportModel_P2->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P2->update();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
	}	
	
	public function get_cashiers_report_product_p2(Request $request){		
	
			$data =  CashiersReportModel_P2::where('cashiers_report_id', $request->CashiersReportId)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p2.product_idx')
				->orderBy('cashiers_report_p2_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_cashiers_report_p2.product_price',
					'teves_cashiers_report_p2.cashiers_report_p2_id',
					'teves_cashiers_report_p2.cashiers_report_id',
					'teves_cashiers_report_p2.order_quantity',
					'teves_cashiers_report_p2.order_total_amount'
					]);
		
			return response()->json($data);
	}

	public function cashiers_report_p2_info(Request $request){

		$CHPH2_ID = $request->CHPH2_ID;
		
		$data =  CashiersReportModel_P2::where('cashiers_report_p2_id', $CHPH2_ID)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p2.product_idx')
				->get([
					'teves_product_table.product_name',
					'teves_product_table.product_id',
					'teves_cashiers_report_p2.cashiers_report_p2_id',
					'teves_cashiers_report_p2.order_quantity',
					'teves_cashiers_report_p2.product_price',
					'teves_cashiers_report_p2.order_total_amount'
					]);			
					
		return response()->json($data);
		
	}
	
	public function delete_cashiers_report_product_p2(Request $request){		
			
		$CHPH2_ID = $request->CHPH2_ID;
		CashiersReportModel_P2::find($CHPH2_ID)->delete();
		return 'Deleted';
		
	}

	/*Part Three (MSC Reports)*/
	public function save_product_cashiers_report_PH3(Request $request){	

		$request->validate([
			'product_idx'  		=> 'required',
			'order_quantity'  	=> 'required'		
        ], 
        [
			'product_idx.required' 	=> 'Product is Required',
			'order_quantity.required' 	=> 'Order Quantity is Required',
        ]
		);
			
			/*Get Cashier Report ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$order_quantity 			= $request->order_quantity;
			$product_manual_price 		= $request->product_manual_price;
			
			$CHPH3_ID 				= $request->CHPH3_ID;

								/*Product Details*/
								$product_info = ProductModel::find($product_idx, ['product_price']);					
								
								/*Check if Price is From Manual Price*/
								if($product_manual_price!=0){
									$product_price = $product_manual_price;
								}else{
									$product_price = $product_info->product_price;
								}
						
								$peso_sales = ($order_quantity * $product_price);
								
								if($CHPH3_ID=='' || $CHPH3_ID ==0){	
								
									$CashiersReportModel_P3 = new CashiersReportModel_P3();
									
									$CashiersReportModel_P3->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P3->cashiers_report_id 		= $CashiersReportId;
									$CashiersReportModel_P3->product_idx 				= $product_idx;
									$CashiersReportModel_P3->order_quantity 			= $order_quantity;
									$CashiersReportModel_P3->product_price 				= $product_price;
									$CashiersReportModel_P3->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P3->save();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P3 = new CashiersReportModel_P3();
									$CashiersReportModel_P3 = CashiersReportModel_P3::find($CHPH3_ID);
									
									//$CashiersReportModel_P2->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P3->product_idx 				= $product_idx;
									$CashiersReportModel_P3->order_quantity 			= $order_quantity;
									$CashiersReportModel_P3->product_price 				= $product_price;
									$CashiersReportModel_P3->order_total_amount 		= $peso_sales;
									
									$result = $CashiersReportModel_P3->update();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
	}	
	
	public function get_cashiers_report_product_p3(Request $request){		
	
			$data =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_cashiers_report_p3.product_price',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
					]);
		
			return response()->json($data);
	}

	public function cashiers_report_p3_info(Request $request){

		$CHPH3_ID = $request->CHPH3_ID;
		
		$data =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->get([
					'teves_product_table.product_name',
					'teves_product_table.product_id',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.product_price',
					'teves_cashiers_report_p3.order_total_amount'
					]);			
					
		return response()->json($data);
		
	}
	
	public function delete_cashiers_report_product_p3(Request $request){		
			
		$CHPH3_ID = $request->CHPH3_ID;
		CashiersReportModel_P3::find($CHPH3_ID)->delete();
		return 'Deleted';
		
	}


	/*Part Four (MSC Reports)*/
	public function save_cashiers_report_PH4(Request $request){	

		$request->validate([
			'description_p4'  	=> 'required',
			'amount_p4'  		=> 'required'		
        ], 
        [
			'description_p4.required' 	=> 'Description is Required',
			'amount_p4.required' 		=> 'Amount is Required',
        ]
		);
			
			/*Get Cashier Report ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$description_p4		= $request->description_p4;
			$amount_p4 			= $request->amount_p4;
			
			$CHPH4_ID 			= $request->CHPH4_ID;

								if($CHPH4_ID=='' || $CHPH4_ID ==0){	
								
									$CashiersReportModel_P4 = new CashiersReportModel_P4();
									
									$CashiersReportModel_P4->user_idx 				= Session::get('loginID');
									$CashiersReportModel_P4->cashiers_report_id 	= $CashiersReportId;
									$CashiersReportModel_P4->description_p4 		= $description_p4;
									$CashiersReportModel_P4->amount_p4 				= $amount_p4;
									
									$result = $CashiersReportModel_P4->save();
									
									if($result){
										return response()->json(['success'=>'Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Creation']);
									}
									
								}else{
																	
									$CashiersReportModel_P4 = new CashiersReportModel_P4();
									$CashiersReportModel_P4 = CashiersReportModel_P4::find($CHPH4_ID);
									$CashiersReportModel_P4->description_p4 		= $description_p4;
									$CashiersReportModel_P4->amount_p4 				= $amount_p4;
									
									$result = $CashiersReportModel_P4->update();
									
									if($result){
										return response()->json(['success'=>'Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Updated']);
									}
									
								}
								
	}	
	
	public function get_cashiers_report_p4(Request $request){		
	
			$data =  CashiersReportModel_P4::where('cashiers_report_id', $request->CashiersReportId)
				->orderBy('cashiers_report_p4_id', 'asc')
              	->get([
					'teves_cashiers_report_p4.cashiers_report_p4_id',
					'teves_cashiers_report_p4.description_p4',
					'teves_cashiers_report_p4.amount_p4'
					]);
		
			return response()->json($data);
	}

	public function cashiers_report_p4_info(Request $request){

		$CHPH4_ID = $request->CHPH4_ID;
		
		$data =  CashiersReportModel_P4::where('cashiers_report_p4_id', $CHPH4_ID)
				->get([
					'teves_cashiers_report_p4.cashiers_report_p4_id',
					'teves_cashiers_report_p4.description_p4',
					'teves_cashiers_report_p4.amount_p4'
					]);			
					
		return response()->json($data);
		
	}
	
	public function delete_cashiers_report_p4(Request $request){		
			
		$CHPH4_ID = $request->CHPH4_ID;
		CashiersReportModel_P4::find($CHPH4_ID)->delete();
		return 'Deleted';
		
	}

}
