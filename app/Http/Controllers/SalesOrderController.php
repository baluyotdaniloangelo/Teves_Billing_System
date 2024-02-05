<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\TevesBranchModel;
use App\Models\SalesOrderComponentModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
{
	
	/*Load Product Interface*/
	public function salesorder(){
		
		$title = 'Sales Order';
		$data = array();
		if(Session::has('loginID')){
			
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$teves_branch = TevesBranchModel::all();
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
		
		}

		return view("pages.salesorder_2", compact('data','title','client_data','product_data','sales_order_delivered_to','sales_order_delivered_to_address','teves_branch'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getSalesOrderList(Request $request)
    {
		$list = SalesOrderModel::get();
		if ($request->ajax()) {

		if(Session::get('UserType')=="Admin"){

			$data = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
					
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_less_percentage',
					'teves_sales_order_table.sales_order_control_number',			
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_status']);
					
		}else{
			
			$data = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
              		->whereDate('teves_sales_order_table.created_at', date('Y-m-d'))
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',
					'teves_sales_order_table.sales_order_control_number',			
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_status']);	
			
		}			
	
		return DataTables::of($data)
				->addIndexColumn()
				
				->addColumn('delivery_status', function($row){
					
									
				if($row->sales_order_status=='Pending'){
					$sales_status_selected = '<div align="center" class="action_table_menu_Product">
												<select class="sales_order_status_'.$row->sales_order_id.'" name="sales_order_status_'.$row->sales_order_id.'" id="sales_order_status_'.$row->sales_order_id.'" onchange="sales_order_status('.$row->sales_order_id.')">	
													<option disabled="" value="">Choose...</option><option selected value="Pending">Pending</option><option value="Delivered">Delivered</option>
												</select>
											  </div>';
				}
				else{
					$sales_status_selected = 'Delivered';
				}
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
						'.$sales_status_selected.'
					</div>';
                    return $actionBtn;
                })
				
                ->addColumn('action', function($row){
					
					if($row->sales_order_status=='Pending'){
						/*
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
						
						*/
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						<a href="sales_order_form/'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
						</div>';
					
					}else{	
					
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
					
					}
					
                    return $actionBtn;
                })
				->rawColumns(['action','delivery_status'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function sales_order_info(Request $request){
		
					$data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')	
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_client_table.client_tin',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',		
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_delivered_to',
					'teves_sales_order_table.sales_order_delivered_to_address',
					'teves_sales_order_table.sales_order_delivery_method',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_hauler',
					'teves_sales_order_table.sales_order_required_date',
					'teves_sales_order_table.sales_order_instructions',
					'teves_sales_order_table.sales_order_note',
					'teves_sales_order_table.sales_order_mode_of_payment',
					'teves_sales_order_table.sales_order_date_of_payment',
					'teves_sales_order_table.sales_order_reference_no',
					'teves_sales_order_table.sales_order_payment_amount',
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_less_percentage']);
					return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_sales_order_confirmed(Request $request){

		/*Delete on Sales Order Table*/
		$sales_order_id = $request->sales_order_id;
		SalesOrderModel::find($sales_order_id)->delete();
		
		/*Delete on Sales Order Product Component*/	
		SalesOrderComponentModel::where('sales_order_idx', $sales_order_id)->delete();
		
		return 'Deleted';
		
	} 

	public function create_sales_order_post(Request $request){

		$request->validate([
			'sales_order_date'  => 'required',
			'client_idx'  		=> 'required'
        ], 
        [
			'sales_order_date.required' 	=> 'Sales Order Date is Required',
			'client_idx.required' 			=> 'Client is Required'
        ]
		);

			@$last_id = SalesOrderModel::latest()->first()->sales_order_id;

			$client_idx = $request->client_idx;
			
			$Salesorder = new SalesOrderModel();
			$Salesorder->company_header 						= $request->company_header;
			$Salesorder->sales_order_date 						= $request->sales_order_date;
			$Salesorder->sales_order_client_idx 				= $request->client_idx;
			$Salesorder->sales_order_control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			
			$Salesorder->sales_order_payment_term 				= $request->payment_term;
			$Salesorder->sales_order_net_percentage 			= $request->sales_order_net_percentage;
			$Salesorder->sales_order_less_percentage 			= $request->sales_order_less_percentage;
			
			$result = $Salesorder->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;
			
			if($result){
				return response()->json(array('success' => "Sales Order Successfully Created!", 'sales_order_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Sales Order Information']);
			}
	}

	public function update_sales_order_post(Request $request){	

		$request->validate([
			'client_idx'  	=> 'required',
        ], 
        [
			'client_idx.required' 	=> 'Client is Required',
        ]
		);
			
			$Salesorder = new SalesOrderModel();
			$Salesorder = SalesOrderModel::find($request->sales_order_id);
			$Salesorder->sales_order_client_idx 				= $request->client_idx;
			$Salesorder->company_header 						= $request->company_header;
			$Salesorder->sales_order_date 						= $request->sales_order_date;
			$Salesorder->sales_order_delivered_to 				= $request->delivered_to;
			$Salesorder->sales_order_delivered_to_address 		= $request->delivered_to_address;
			$Salesorder->sales_order_dr_number 					= $request->dr_number;
			$Salesorder->sales_order_or_number 					= $request->or_number;
			$Salesorder->sales_order_payment_term 				= $request->payment_term;
			$Salesorder->sales_order_delivery_method 			= $request->delivery_method;
			$Salesorder->sales_order_hauler 					= $request->hauler;
			$Salesorder->sales_order_required_date 				= $request->required_date;
			$Salesorder->sales_order_instructions 				= $request->instructions;
			$Salesorder->sales_order_note 						= $request->note;
			
			$Salesorder->sales_order_net_percentage 			= $request->sales_order_net_percentage;
			$Salesorder->sales_order_less_percentage 			= $request->sales_order_less_percentage;
			
			$result = $Salesorder->update();
			
			/*Get Last ID*/
			$last_transaction_id = $request->sales_order_id;
			
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
			
			$SalesOrderUpdate = new SalesOrderModel();
			$SalesOrderUpdate = SalesOrderModel::find($last_transaction_id);
			$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
			$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
			$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
			$SalesOrderUpdate->update();
			
			if($result){
				return response()->json(array('success' => "Sales Order Successfully Updated!"), 200);
			}
			else{
				return response()->json(['success'=>'Error on Update Sales Order Information']);
			}
	}
	
	public function get_sales_order_product_list(Request $request){		
	
			$raw_query_sales_order_component = "SELECT `teves_sales_order_component_table`.`sales_order_component_id`,
						IFNULL(`teves_product_table`.`product_name`,`teves_sales_order_component_table`.item_description) as product_name,
						IFNULL(`teves_product_table`.`product_unit_measurement`,'PC') as product_unit_measurement,
						`teves_sales_order_component_table`.`product_idx`, `teves_sales_order_component_table`.`product_price`, `teves_sales_order_component_table`.`order_quantity`,
						`teves_sales_order_component_table`.`order_total_amount`
						from `teves_sales_order_component_table`  left join `teves_product_table` on	 
						`teves_product_table`.`product_id` = `teves_sales_order_component_table`.`product_idx` where `sales_order_idx` = ?		  
						order by `sales_order_component_id` asc";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_id]);		
		
			return response()->json($data);
	}

	public function delete_sales_order_item(Request $request){		
			
		$productitemID = $request->productitemID;
		SalesOrderComponentModel::find($productitemID)->delete();
		return 'Deleted';
		
	}

	public function update_sales_status(Request $request){		
			
		$sales_order_id = $request->sales_order_id;
		$sales_status = $request->sales_status;
				
				$salesOrderUpdate = new SalesOrderModel();
				$salesOrderUpdate = SalesOrderModel::find($sales_order_id);
				$salesOrderUpdate->sales_order_status = $sales_status;
				$salesOrderUpdate->update();
		
	}
	
	public function update_sales_order_delivery_status(Request $request){		
			
		$sales_order_id = $request->sales_order_id;
		$sales_order_delivery_status = $request->sales_order_delivery_status;
				
				$salesOrderUpdate = new SalesOrderModel();
				$salesOrderUpdate = SalesOrderModel::find($sales_order_id);
				$salesOrderUpdate->sales_order_delivery_status = $sales_order_delivery_status;
				$salesOrderUpdate->update();
		
	}
	
	/*Load Site Interface*/
	public function sales_order_form($SalesOrderID){
		
		$title = 'Sales Order';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();/*User Data*/
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			
			$teves_branch = TevesBranchModel::all();
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
			
			$sales_order_data = SalesOrderModel::where('sales_order_id', $SalesOrderID)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')	
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_client_table.client_tin',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',		
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_delivered_to',
					'teves_sales_order_table.sales_order_delivered_to_address',
					'teves_sales_order_table.sales_order_delivery_method',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_hauler',
					'teves_sales_order_table.sales_order_required_date',
					'teves_sales_order_table.sales_order_instructions',
					'teves_sales_order_table.sales_order_note',
					'teves_sales_order_table.sales_order_mode_of_payment',
					'teves_sales_order_table.sales_order_date_of_payment',
					'teves_sales_order_table.sales_order_reference_no',
					'teves_sales_order_table.sales_order_payment_amount',
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_less_percentage']);
					
		return view("pages.sales_order_form", compact('data','title','product_data','client_data','teves_branch', 'sales_order_delivered_to','sales_order_delivered_to_address', 'SalesOrderID','sales_order_data'));
		}
		
	}  	


	public function sales_order_component_info(Request $request){

			$raw_query_sales_order_component = "select IFNULL(`teves_product_table`.`product_name`,`teves_sales_order_component_table`.item_description) as product_name,
			IFNULL(`teves_product_table`.`product_unit_measurement`,'PC') as product_unit_measurement,
			 `teves_sales_order_component_table`.`product_price`,
			  `teves_sales_order_component_table`.`order_quantity`,
			   `teves_sales_order_component_table`.`order_total_amount`
				 from `teves_sales_order_component_table` left join `teves_product_table`
				  on `teves_product_table`.`product_id` = `teves_sales_order_component_table`.`product_idx`
				   where `sales_order_component_id` = ?";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_component_id]);		

		return response()->json($data);
		
	}

	public function sales_order_component_compose(Request $request){

		$request->validate([
		  'item_description'      	=> 'required',
		  'order_quantity'      	=> 'required',
        ], 
        [
			'item_description.required' => 'Item Description or Product is Required',
			'order_quantity.required' => 'Quantity is Required'
        ]
		);
				
					/*Check if Price is From Manual Price*/
					if($request->product_manual_price!=0){
						
						$product_price = $request->product_manual_price;
						
					}else{
	
						/*Product Details*/
						$raw_query_product = "SELECT a.product_id, ifnull(b.branch_price,a.product_price) AS product_price FROM teves_product_table AS a
						LEFT JOIN teves_product_branch_price_table b ON b.product_idx = a.product_id LEFT JOIN teves_branch_table c ON c.branch_id = b.branch_idx
						WHERE b.branch_idx = ? and b.product_idx = ?";			
						$product_info = DB::select("$raw_query_product", [$request->branch_idx,$request->product_idx]);		
						
						$product_price = $product_info[0]->product_price;
					}
					
					$order_total_amount = $request->order_quantity * $product_price;	
					
					$sales_order_data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx']);
					
					$sales_order_date = $sales_order_data[0]->sales_order_date;
					$client_idx = $sales_order_data[0]->sales_order_client_idx;
					
					if(($request->product_idx+0)==0){
						$item_description = $request->item_description;
					}else{
						$item_description = '';
					}
					
					if($request->sales_order_component_id==0){
						
						/*SAVE*/
						$SalesOrderComponent = new SalesOrderComponentModel();
						$SalesOrderComponent->sales_order_idx 		= $request->sales_order_id;
						$SalesOrderComponent->product_idx 			= $request->product_idx+0;
						$SalesOrderComponent->item_description 		= $item_description;
						$SalesOrderComponent->product_price 		= $product_price;
						$SalesOrderComponent->order_quantity 		= $request->order_quantity;
						$SalesOrderComponent->sales_order_date 		= $sales_order_date;
						$SalesOrderComponent->client_idx 			= $client_idx;
						$SalesOrderComponent->order_total_amount 	= $order_total_amount;
						
						$result = $SalesOrderComponent->save();
				
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
				
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
				$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
				$SalesOrderUpdate->update();				
				
						if($result){
							return response()->json(array('success' => "Product Information Successfully Created!"), 200);
						}
						else{
							return response()->json(['success'=>'Error on Creation Product Information']);
						}				
						
					}else{
						
						/*UPDATE*/
						$SalesOrderComponent = new SalesOrderComponentModel();
						$SalesOrderComponent = SalesOrderComponentModel::find($request->sales_order_component_id);
						$SalesOrderComponent->product_idx 			= $request->product_idx+0;
						$SalesOrderComponent->item_description 		= $item_description;
						$SalesOrderComponent->product_price 		= $product_price;
						$SalesOrderComponent->order_quantity 		= $request->order_quantity;
						$SalesOrderComponent->sales_order_date 		= $sales_order_date;
						$SalesOrderComponent->client_idx 			= $client_idx;
						$SalesOrderComponent->order_total_amount 	= $order_total_amount;
						
						$result = $SalesOrderComponent->update();
				
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
				
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
				$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
				$SalesOrderUpdate->update();				
				
						if($result){
							return response()->json(array('success' => "Product Information Successfully Updated!"), 200);
						}
						else{
							return response()->json(['success'=>'Error on Update Product Information']);
						}				
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
				
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
				$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
				$SalesOrderUpdate->update();
	
		return 'Deleted';
		
	} 
	
	
}
