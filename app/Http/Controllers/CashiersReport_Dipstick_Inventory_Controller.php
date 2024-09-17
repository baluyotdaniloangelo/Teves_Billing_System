<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_p7;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class CashiersReport_Dipstick_Inventory_Controller extends Controller
{
	
	public function save_product_cashiers_report_p7(Request $request){	

		$request->validate([
			'product_idx'  					=> 'required',
			'tank_idx'  					=> 'required',
			'beginning_inventory'  			=> 'required',
			'sales_in_liters_inventory'  	=> 'required',	
			'ugt_pumping_inventory'			=> 'required',	
			'delivery_inventory'  			=> 'required',
			'ending_inventory' 				=> 'required'
        ], 
        [
			'product_idx.required' 					=> 'Product is Required',
			'tank_idx.required' 					=> 'Tank is Required',
			'beginning_inventory.required' 			=> 'Beginning Inventory is Required',
			'sales_in_liters_inventory.required' 	=> 'Sales in Liters is Required',
			'ugt_pumping_inventory.required' 		=> 'UGT Pumping is Required',
			'delivery_inventory.required' 			=> 'Delivery is Required',
			'ending_inventory.required' 			=> 'Ending Inventory is Required',
        ]
		);
			
			/*Get Last ID*/
			$CashiersReportId = $request->CashiersReportId;
			
			$product_idx				= $request->product_idx;
			$tank_idx					= $request->tank_idx;
			$beginning_inventory 		= $request->beginning_inventory;
			$sales_in_liters_inventory 	= $request->sales_in_liters_inventory;
			$ugt_pumping_inventory 		= $request->ugt_pumping_inventory;
			$delivery_inventory 		= $request->delivery_inventory;
			$ending_inventory 			= $request->ending_inventory;
			
			
			$book_stock = ($beginning_inventory) - ($sales_in_liters_inventory + $ugt_pumping_inventory) + $delivery_inventory;
			$variance = $book_stock - $ending_inventory;
			
			$CRPH7_ID 				= $request->CRPH7_ID;
								
								if($CRPH7_ID=='' || $CRPH7_ID ==0){	
								
									$CashiersReportModel_p7 = new CashiersReportModel_p7();
									
									$CashiersReportModel_p7->user_idx 					= Session::get('loginID');
									$CashiersReportModel_p7->cashiers_report_idx 		= $CashiersReportId;
									$CashiersReportModel_p7->product_idx 				= $product_idx;
									$CashiersReportModel_p7->tank_idx	 				= $tank_idx;
									$CashiersReportModel_p7->beginning_inventory 		= $beginning_inventory;
									$CashiersReportModel_p7->sales_in_liters 			= $sales_in_liters_inventory;
									$CashiersReportModel_p7->ugt_pumping	 			= $ugt_pumping_inventory;
									$CashiersReportModel_p7->delivery 					= $delivery_inventory;
									$CashiersReportModel_p7->ending_inventory 			= $ending_inventory;
									$CashiersReportModel_p7->book_stock 				= $book_stock;
									$CashiersReportModel_p7->variance 					= $variance;
									$CashiersReportModel_p7->created_by_user_id 		= Session::get('loginID');
						
									$result = $CashiersReportModel_p7->save();
									
									if($result){
										return response()->json(['success'=>'Product Inventory Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Inventory Information']);
									}
									
								}else{
																	
									$CashiersReportModel_p7 = new CashiersReportModel_p7();
									$CashiersReportModel_p7 = CashiersReportModel_p7::find($CRPH7_ID);
									
									$CashiersReportModel_p7->cashiers_report_idx 		= $CashiersReportId;
									$CashiersReportModel_p7->product_idx 				= $product_idx;
									$CashiersReportModel_p7->tank_idx	 				= $tank_idx;
									$CashiersReportModel_p7->beginning_inventory 		= $beginning_inventory;
									$CashiersReportModel_p7->sales_in_liters 			= $sales_in_liters_inventory;
									$CashiersReportModel_p7->ugt_pumping	 			= $ugt_pumping_inventory;
									$CashiersReportModel_p7->delivery 					= $delivery_inventory;
									$CashiersReportModel_p7->ending_inventory 			= $ending_inventory;
									$CashiersReportModel_p7->book_stock 				= $book_stock;
									$CashiersReportModel_p7->variance 					= $variance;
									$CashiersReportModel_p7->updated_by_user_id 		= Session::get('loginID');
									$result = $CashiersReportModel_p7->update();
									
									if($result){
										return response()->json(['success'=>'Product Inventory Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Inventory Information']);
									}
									
								}
								
	}		
	
	/**/
	public function get_product_dipstick_inventory_list(Request $request){		

			$data =  CashiersReportModel_p7::Join('teves_product_tank_table', 'teves_product_tank_table.tank_id', '=', 'teves_cashiers_report_p7.tank_idx')
					->Join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p7.product_idx')
					->where('teves_cashiers_report_p7.cashiers_report_idx', $request->CashiersReportId)
					->orderBy('teves_cashiers_report_p7.product_idx', 'asc')
					->get([
						'teves_product_table.product_id',
						'teves_product_table.product_name',
						'teves_product_tank_table.tank_id',
						'teves_product_tank_table.tank_name',
						'teves_product_tank_table.tank_capacity',
						'teves_cashiers_report_p7.cashiers_report_p7_id',
						'teves_cashiers_report_p7.beginning_inventory',
						'teves_cashiers_report_p7.sales_in_liters',
						'teves_cashiers_report_p7.delivery',
						'teves_cashiers_report_p7.ending_inventory',
						'teves_cashiers_report_p7.book_stock',
						'teves_cashiers_report_p7.variance'
					]);
		
			return response()->json($data);			
	}

	
	public function cashiers_report_p7_info(Request $request){

		$CHPH7_ID = $request->CHPH7_ID;
		
		$data =  CashiersReportModel_p7::Join('teves_product_tank_table', 'teves_product_tank_table.tank_id', '=', 'teves_cashiers_report_p7.tank_idx')
					->Join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p7.product_idx')
					->where('teves_cashiers_report_p7.cashiers_report_p7_id', $CHPH7_ID)
					
					->get([
						'teves_product_table.product_id',
						'teves_product_table.product_name',
						'teves_product_tank_table.tank_id',
						'teves_product_tank_table.tank_name',
						'teves_product_tank_table.tank_capacity',
						'teves_cashiers_report_p7.cashiers_report_p7_id',
						'teves_cashiers_report_p7.beginning_inventory',
						'teves_cashiers_report_p7.sales_in_liters',
						'teves_cashiers_report_p7.delivery',
						'teves_cashiers_report_p7.ending_inventory',
						'teves_cashiers_report_p7.book_stock',
						'teves_cashiers_report_p7.variance'
					]);
					
		return response()->json($data);
		
	}
	
	public function delete_cashiers_report_p7(Request $request){		
			
		$CHPH7_ID = $request->CHPH7_ID;
		CashiersReportModel_p7::find($CHPH7_ID)->delete();
		return 'Deleted';
		
	}
	
	/*OLD cashiers_report_p7_info*/
	public function cashiers_report_summary_info(Request $request){

		$CashiersReportId = $request->CashiersReportId;
		
		$PH1_SUM =  CashiersReportModel_P1::where('teves_cashiers_report_p1.cashiers_report_id', $CashiersReportId)
        ->sum('order_total_amount');

		$PH2_SUM =  CashiersReportModel_P2::where('teves_cashiers_report_p2.cashiers_report_id', $CashiersReportId)
        ->sum('order_total_amount');
		
		$PH3_SUM =  CashiersReportModel_P3::where('teves_cashiers_report_p3.cashiers_report_id', $CashiersReportId)
        ->sum('order_total_amount');
		
		$PH4_SUM =  CashiersReportModel_P4::where('teves_cashiers_report_p4.cashiers_report_id', $CashiersReportId)
        ->sum('amount_p4');
		
		$_PH5_SUM = CashiersReportModel_P5::where('cashiers_report_id', $CashiersReportId)
            ->get([	
			'teves_cashiers_report_p5.cashiers_report_p5_id',			
			'teves_cashiers_report_p5.one_thousand_deno',
			'teves_cashiers_report_p5.five_hundred_deno',
			'teves_cashiers_report_p5.two_hundred_deno',
			'teves_cashiers_report_p5.one_hundred_deno',
			'teves_cashiers_report_p5.fifty_deno',
			'teves_cashiers_report_p5.twenty_deno',
			'teves_cashiers_report_p5.ten_deno',
			'teves_cashiers_report_p5.five_deno',
			'teves_cashiers_report_p5.one_deno',
			'teves_cashiers_report_p5.twenty_five_cent_deno',
			'teves_cashiers_report_p5.cash_drop'
			]);
		
		$one_thousand_deno 		= $_PH5_SUM[0]->one_thousand_deno * 1000;
		
		$five_hundred_deno 		= $_PH5_SUM[0]->five_hundred_deno * 500;
		$two_hundred_deno 		= $_PH5_SUM[0]->two_hundred_deno * 200;
		$one_hundred_deno 		= $_PH5_SUM[0]->one_hundred_deno * 100;
		
		$fifty_deno 			= $_PH5_SUM[0]->fifty_deno * 50;
		$twenty_deno 			= $_PH5_SUM[0]->twenty_deno * 20;
		$ten_deno 				= $_PH5_SUM[0]->ten_deno * 10;
		
		$five_deno 				= $_PH5_SUM[0]->five_deno * 5;
		$one_deno 				= $_PH5_SUM[0]->one_deno * 1;
		
		$twenty_five_cent_deno 	= $_PH5_SUM[0]->twenty_five_cent_deno * 0.25;
		
		$cash_drop 	= $_PH5_SUM[0]->cash_drop;
		
		$PH5_SUM = $cash_drop + $one_thousand_deno + $five_hundred_deno + $two_hundred_deno + $one_hundred_deno + $fifty_deno + $twenty_deno + $ten_deno + $five_deno + $one_deno + $twenty_five_cent_deno;
        		
		if($CashiersReportId!=0){
				return response()->json(array('success' => "Success", 'fuel_sales_total' => $PH1_SUM, 'other_sales_total' => $PH2_SUM, 'miscellaneous_total' => $PH3_SUM, 'theoretical_sales' => $PH4_SUM, 'cash_on_hand' => $PH5_SUM), 200);
		}
		else{
				return response()->json(['success'=>"Error on Load"]);
		}
			
	}
	
	
	/*Print Via PDF*/
	public function generate_cashier_report_pdf(Request $request){

		$request->validate([
		  'CashiersReportId'      			=> 'required'
        ], 
        [
			'CashiersReportId.required' 	=> 'Please select a Client',
        ]
		);
		$CashiersReportId = $request->CashiersReportId;
		
		$CashiersReportData = CashiersReportModel::where('cashiers_report_id', $CashiersReportId)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.cashiers_name',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
		
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
          
		$title = 'Cashier Report';
		  
		$data_P1_premium_95 =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
			->where('product_idx', 13)
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
					
		$data_P1_super_regular =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
			->where('product_idx', 11)
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

		$data_P1_diesel =  CashiersReportModel_P1::where('cashiers_report_id', $request->CashiersReportId)
			->where('product_idx', 12)
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

    	$data_P2 =  CashiersReportModel_P2::where('cashiers_report_id', $request->CashiersReportId)
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

		$data_SALES_CREDIT =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
			->where('miscellaneous_items_type','=','SALES_CREDIT')
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->get([
					'teves_product_table.product_name',
					'teves_product_table.product_id',
					'teves_cashiers_report_p3.miscellaneous_items_type',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.order_total_amount'
					]);		
					
		$data_DISCOUNTS =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
            ->where('miscellaneous_items_type','DISCOUNTS')
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
					]);
		
		$data_OTHERS =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
				->whereIn('miscellaneous_items_type', array('CASHOUT', 'OTHERS'))
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
					]);
		
		$data_theoretical_sales =  CashiersReportModel_P4::where('cashiers_report_id', $request->CashiersReportId)
			->orderBy('cashiers_report_p4_id', 'asc')
            ->get([
				'teves_cashiers_report_p4.cashiers_report_p4_id',
				'teves_cashiers_report_p4.description_p4',
				'teves_cashiers_report_p4.amount_p4'
				]);
		
		$data_Cash_on_hand = CashiersReportModel_P5::where('cashiers_report_id', $request->CashiersReportId)
            ->get([	
			'teves_cashiers_report_p5.cashiers_report_p5_id',			
			'teves_cashiers_report_p5.one_thousand_deno',
			'teves_cashiers_report_p5.five_hundred_deno',
			'teves_cashiers_report_p5.two_hundred_deno',
			'teves_cashiers_report_p5.one_hundred_deno',
			'teves_cashiers_report_p5.fifty_deno',
			'teves_cashiers_report_p5.twenty_deno',
			'teves_cashiers_report_p5.ten_deno',
			'teves_cashiers_report_p5.five_deno',
			'teves_cashiers_report_p5.one_deno',
			'teves_cashiers_report_p5.twenty_five_cent_deno',
			'teves_cashiers_report_p5.cash_drop'
			]);
		
		$data_PH7_inventory =  CashiersReportModel_p7::Join('teves_product_tank_table', 'teves_product_tank_table.tank_id', '=', 'teves_cashiers_report_p7.tank_idx')
					->Join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p7.product_idx')
					->where('teves_cashiers_report_p7.cashiers_report_idx', $request->CashiersReportId)
					->orderBy('teves_cashiers_report_p7.product_idx', 'asc')
					->get([
						'teves_product_table.product_id',
						'teves_product_table.product_name',
						'teves_product_tank_table.tank_id',
						'teves_product_tank_table.tank_name',
						'teves_product_tank_table.tank_capacity',
						'teves_cashiers_report_p7.cashiers_report_p7_id',
						'teves_cashiers_report_p7.beginning_inventory',
						'teves_cashiers_report_p7.sales_in_liters',
						'teves_cashiers_report_p7.delivery',
						'teves_cashiers_report_p7.ending_inventory',
						'teves_cashiers_report_p7.book_stock',
						'teves_cashiers_report_p7.variance'
					]);
		
		$branch_header = TevesBranchModel::find($CashiersReportData[0]['teves_branch'], ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		
        $pdf = PDF::loadView('printables.cashier_report_pdf', compact('title', 'CashiersReportData', 'data_P1_premium_95', 'data_P1_super_regular', 'data_P1_diesel', 'data_P2', 'data_SALES_CREDIT', 'data_DISCOUNTS', 'data_OTHERS', 'data_theoretical_sales', 'data_Cash_on_hand','branch_header','data_PH7_inventory'));
		
		/*Download Directly*/
		/*Stream for Saving/Printing*/
		$pdf->setPaper('A4', 'portrait');/*Set to Landscape*/
		$pdf->render();
		return $pdf->stream($CashiersReportData[0]['cashiers_name'].".pdf");

	}

}
