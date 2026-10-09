<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P2;

use App\Models\ProductModel;
use App\Models\TevesBranchModel;

use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class CashiersReportLubeAndCarCareController extends Controller
{
	
	/*Lube and Car Care Products*/
	public function save_lube_car_care(Request $request){	

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
			
			$lube_car_care_id 				= $request->lube_car_care_id;

					/*Check if Price is From Manual Price*/
					if($product_manual_price!=0){
						
						$product_price = $request->product_manual_price;
						
					}else{
	
						/*Product Details*/
						$raw_query_product = "SELECT a.product_id, ifnull(b.branch_price,a.product_price) AS product_price FROM teves_product_table AS a
						LEFT JOIN teves_product_branch_price_table b ON b.product_idx = a.product_id LEFT JOIN teves_branch_table c ON c.branch_id = b.branch_idx
						WHERE b.branch_idx = ? and b.product_idx = ?";			
						$product_info = DB::select("$raw_query_product", [$request->branch_idx,$request->product_idx]);		
						
						$product_price = $product_info[0]->product_price;

					}
						
								$peso_sales = ($order_quantity * $product_price);
								
								if($lube_car_care_id=='' || $lube_car_care_id ==0){	
								
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
									$CashiersReportModel_P2 = CashiersReportModel_P2::find($lube_car_care_id);
									
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
	
	public function get_cashiers_report_lube_car_care(Request $request){		
	
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
	

	public function cashiers_report_lube_car_care_info(Request $request){

		$lube_car_care_id = $request->lube_car_care_id;
		
		$data =  CashiersReportModel_P2::where('cashiers_report_p2_id', $lube_car_care_id)
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
	
	public function delete_cashiers_report_lube_car_care(Request $request){		
			
		$lube_car_care_id = $request->lube_car_care_id;
		CashiersReportModel_P2::find($lube_car_care_id)->delete();
		return 'Deleted';
		
	}

}