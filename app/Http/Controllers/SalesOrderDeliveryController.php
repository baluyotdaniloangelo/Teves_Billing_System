<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\TevesBranchModel;
use App\Models\SalesOrderComponentModel;
use App\Models\SalesOrderDeliveryModel;
use App\Models\ReceivablesModel;
use App\Models\ReceivablesPaymentModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class SalesOrderDeliveryController extends Controller
{
	
		/*Get List Of Product to Deliver*/
	public function get_sales_order_product_list_delivery(Request $request){		
	
			$raw_query_sales_order_component = "SELECT `teves_sales_order_delivery_details`.`sales_order_delivery_details_id`,
						`teves_product_table`.`product_name`,
						`teves_product_table`.`product_unit_measurement`,
						`teves_sales_order_delivery_details`.`sales_order_component_idx`,
						`teves_sales_order_delivery_details`.`sales_order_component_product_idx`, 
						`teves_sales_order_delivery_details`.`sales_order_delivery_quantity`,
						`teves_sales_order_delivery_details`.`sales_order_dispatch_date`,
						`teves_sales_order_delivery_details`.`sales_order_departure_date`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_withdrawal_reference`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_hauler_details`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_ship_to_account`,
						`teves_sales_order_delivery_details`.`sales_order_branch_delivery`
						
						from `teves_sales_order_delivery_details`  left join `teves_product_table` on	 
						`teves_product_table`.`product_id` = `teves_sales_order_delivery_details`.`sales_order_component_product_idx` where `teves_sales_order_delivery_details`.`sales_order_idx` = ?		  
						order by `teves_sales_order_delivery_details`.`sales_order_delivery_details_id` asc";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_id]);		
			
			return response()->json(array('productlist_delivery'=>$data));	

	}
	
	/*ROUTES:SalesOrderDeliveryInfo*/
	/*Fetch Product Delivery Information*/
	public function sales_order_component_delivery_info(Request $request){
		
			$raw_query_sales_order_component = "SELECT `teves_sales_order_delivery_details`.`sales_order_delivery_details_id`,
						`teves_product_table`.`product_name`,
						`teves_product_table`.`product_unit_measurement`,
						`teves_sales_order_delivery_details`.`sales_order_component_idx`,
						`teves_sales_order_delivery_details`.`sales_order_component_product_idx`, 
						`teves_sales_order_delivery_details`.`sales_order_delivery_quantity`,
						`teves_sales_order_delivery_details`.`sales_order_dispatch_date`,
						`teves_sales_order_delivery_details`.`sales_order_departure_date`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_withdrawal_reference`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_hauler_details`,
						`teves_sales_order_delivery_details`.`sales_order_delivery_ship_to_account`,
						`teves_sales_order_delivery_details`.`sales_order_branch_delivery`,
						`teves_branch_table`.`branch_name`
						from `teves_sales_order_delivery_details`  left join `teves_product_table` on	 
						`teves_product_table`.`product_id` = `teves_sales_order_delivery_details`.`sales_order_component_product_idx` 
						
						left join `teves_branch_table` on	 
						`teves_branch_table`.`branch_id` = `teves_sales_order_delivery_details`.`sales_order_branch_delivery` 
						
						where `teves_sales_order_delivery_details`.`sales_order_delivery_details_id` = ?		  
						order by `teves_sales_order_delivery_details`.`sales_order_delivery_details_id` asc";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_delivery_details_id]);	
			
			return response()->json($data);
		
	}	
	
	/*Delete Product Information*/
	public function delete_sales_order_product_delivery_confirmed(Request $request){

		/*Delete on Sales Order Table*/
		$sales_order_delivery_details_id = $request->sales_order_delivery_details_id;
		SalesOrderDeliveryModel::find($sales_order_delivery_details_id)->delete();
		
		return 'Deleted';
		
	} 	

	public function sales_order_component_delivery_compose(Request $request){

		$request->validate([
		  'sales_order_component_product_idx'   => 'required',
		  'sales_order_delivery_quantity'     => 'required',
        ], 
        [
			'sales_order_component_product_idx.required' 	=> 'Product is Required',
			'sales_order_delivery_quantity.required' 		=> 'Quantity is Required'
        ]
		);
			
					if($request->sales_order_delivery_details_id==0){
						
						/*SAVE*/
						$SalesOrderDeliveryComponent = new SalesOrderDeliveryModel();
						$SalesOrderDeliveryComponent->sales_order_idx 								= $request->sales_order_id;
						$SalesOrderDeliveryComponent->receivable_idx 								= $request->receivable_id;
						
						$SalesOrderDeliveryComponent->sales_order_component_idx 					= $request->sales_order_component_idx+0;
						$SalesOrderDeliveryComponent->sales_order_component_product_idx 			= $request->sales_order_component_product_idx+0;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_quantity 				= $request->sales_order_delivery_quantity;
						
						$SalesOrderDeliveryComponent->sales_order_dispatch_date 					= $request->sales_order_dispatch_date;
						$SalesOrderDeliveryComponent->sales_order_departure_date 					= $request->sales_order_departure_date;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_withdrawal_reference 	= $request->sales_order_delivery_withdrawal_reference;
						$SalesOrderDeliveryComponent->sales_order_delivery_hauler_details 			= $request->sales_order_delivery_hauler_details;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_ship_to_account 			= $request->sales_order_delivery_ship_to_account;
						$SalesOrderDeliveryComponent->sales_order_branch_delivery 					= $request->sales_order_branch_delivery;
						
						$result = $SalesOrderDeliveryComponent->save();	
				
						$action_taken = "Created";						
						
					}else{
						
						/*UPDATE*/
						$SalesOrderDeliveryComponent = new SalesOrderDeliveryModel();
						$SalesOrderDeliveryComponent = SalesOrderDeliveryModel::find($request->sales_order_delivery_details_id);
						$SalesOrderDeliveryComponent->sales_order_component_idx 					= $request->sales_order_component_idx+0;
						$SalesOrderDeliveryComponent->sales_order_component_product_idx 			= $request->sales_order_component_product_idx;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_quantity 				= $request->sales_order_delivery_quantity;
						
						$SalesOrderDeliveryComponent->sales_order_dispatch_date 					= $request->sales_order_dispatch_date;
						$SalesOrderDeliveryComponent->sales_order_departure_date 					= $request->sales_order_departure_date;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_withdrawal_reference 	= $request->sales_order_delivery_withdrawal_reference;
						$SalesOrderDeliveryComponent->sales_order_delivery_hauler_details 			= $request->sales_order_delivery_hauler_details;
						
						$SalesOrderDeliveryComponent->sales_order_delivery_ship_to_account 			= $request->sales_order_delivery_ship_to_account;
						$SalesOrderDeliveryComponent->sales_order_branch_delivery 					= $request->sales_order_branch_delivery;
						
						$result = $SalesOrderDeliveryComponent->update();
				
						$action_taken = "Updated";
							
					}
				
				  /*Get Total Quantity*/
					
				  /*Update Sales Order to Delivered*/
				  $salesOrderUpdate_status = new SalesOrderModel();
				  $salesOrderUpdate_status = SalesOrderModel::find($sales_order_id->sales_order_idx);
				  $salesOrderUpdate_status->sales_order_payment_status = $Receivablestatus;
				  $salesOrderUpdate_status->update();
					

									
						/*Response*/
						if($result){
							return response()->json(array('success' => "Product Information Successfully $action_taken!"), 200);
						}
						else{
							return response()->json(['success'=>'Error on $action_taken Product Information']);
						}		
		
	}		
	
	/*Delete Product Information*/
	public function delete_sales_order_component_confirmed(Request $request){

		/*Delete on Sales Order Table*/
		$sales_order_component_id = $request->sales_order_component_id;
		
		/*Delete on Sales Order Product Component*/	
		SalesOrderComponentModel::where('sales_order_component_id', $sales_order_component_id)->delete();
		
				$order_total_amount = SalesOrderComponentModel::where('sales_order_idx', $request->sales_order_id)
				->sum('order_total_amount');
					
				$gross_amount = $order_total_amount;
				
				$net_in_percentage 				= $request->sales_order_net_percentage;/*1.12*/
				$less_in_percentage 			= $request->sales_order_less_percentage/100;
							
				if($request->sales_order_net_percentage==0){
							$sales_order_net_amount 		= 0;
							$sales_order_total_due 			=  number_format($gross_amount,2,".","");
				}else{
							$sales_order_net_amount 		=  number_format($gross_amount/$net_in_percentage,2,".","");
							$sales_order_total_due 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
				}
				
				/*Update Sales Order*/
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
				$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
				$SalesOrderUpdate->update();
				
				$receivable_withholding_tax = $sales_order_net_amount*$request->sales_order_less_percentage/100;
				
				/*Update Receivable Amount*/
				$Receivables_ACTION = new ReceivablesModel();
				$Receivables_ACTION = ReceivablesModel::find($request->receivable_id);
				$Receivables_ACTION->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');
				$Receivables_ACTION->receivable_withholding_tax 	= number_format($receivable_withholding_tax,2, '.', '');				
				$Receivables_ACTION->receivable_amount 				= number_format($sales_order_total_due,2, '.', '');
				$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
				$Receivables_ACTION->receivable_status 				= 'Pending';		
				$Receivables_ACTION->update();
	
				return 'Deleted';
		
	} 

}
