<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\TevesBranchModel;
use App\Models\SalesOrderComponentModel;
use App\Models\ReceivablesModel;
use App\Models\ReceivablesPaymentModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

		return view("pages.sales_order", compact('data','title','client_data','product_data','sales_order_delivered_to','sales_order_delivered_to_address','teves_branch'));
		
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
									
				/*if($row->sales_order_status=='Pending'){
					
					$sales_status_selected = '<div align="center" class="action_table_menu_Product">
												<select class="sales_order_status_'.$row->sales_order_id.'" name="sales_order_status_'.$row->sales_order_id.'" id="sales_order_status_'.$row->sales_order_id.'" onchange="sales_order_status('.$row->sales_order_id.')">	
													<option disabled="" value="">Choose...</option><option selected value="Pending">Pending</option><option value="Delivered">Delivered</option>
												</select>
											  </div>';
											  
				}
				else{*/
					
					$sales_status_selected = $row->sales_order_status;
					
				/*}*/
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
						'.$sales_status_selected.'
					</div>';
					
                    return $actionBtn;
					
                })
				
                ->addColumn('action', function($row){
					
					if($row->sales_order_status=='Pending'){
				
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						<a href="sales_order_form?sales_order_id='.$row->sales_order_id.'&tab=product" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
						</div>';
					
					}else{	
					
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						<a href="sales_order_form?sales_order_id='.$row->sales_order_id.'&tab=product" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
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
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_less_percentage',
					'teves_sales_order_table.sales_order_payment_type']);
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
			
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $request->company_header)->first();			
			$control_number = $BranchInfo->branch_initial."-SO-".$last_id+1;
			
			$Salesorder = new SalesOrderModel();
			$Salesorder->company_header 					= $request->company_header;
			$Salesorder->sales_order_date 					= $request->sales_order_date;
			$Salesorder->sales_order_client_idx 			= $request->client_idx;
			$Salesorder->sales_order_control_number 		= $control_number;
			
			$Salesorder->sales_order_payment_term 			= $request->payment_term;
			$Salesorder->sales_order_net_percentage 		= $request->sales_order_net_percentage;
			$Salesorder->sales_order_less_percentage 		= $request->sales_order_less_percentage;
			
			$Salesorder->sales_order_payment_type 			= $request->sales_order_payment_type;
			
			$result = $Salesorder->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;			
			
			$receivable_last_id = ReceivablesModel::latest()->first()->receivable_id;
						
			$receivable_control_number = $BranchInfo->branch_initial."-AR-".$receivable_last_id+1;	
				
				/*Create Receivable*/
				$Receivables = new ReceivablesModel();
				$Receivables->client_idx 						= $request->client_idx;
				$Receivables->control_number 					= $receivable_control_number;
				$Receivables->sales_order_idx 					= $last_transaction_id;
				$Receivables->billing_date 						= $request->sales_order_date;
				$Receivables->payment_term 						= $request->payment_term;
				$Receivables->receivable_name 					= $control_number;
				$Receivables->receivable_status 				= 'Pending';
				$Receivables->receivable_description 			= $receivable_control_number;
				$Receivables->company_header 					= $request->company_header;
				$Receivables->created_by_user_id 				= Session::get('loginID');
				$result = $Receivables->save();

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
		
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $request->company_header)->first();			
			$control_number = $BranchInfo->branch_initial."-SO-".$request->sales_order_id+1;
			
			$Salesorder = new SalesOrderModel();
			$Salesorder = SalesOrderModel::find($request->sales_order_id);
			$Salesorder->sales_order_client_idx 				= $request->client_idx;
			$Salesorder->sales_order_control_number 			= $control_number;
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
			
			$Salesorder->sales_order_payment_type 				= $request->sales_order_payment_type;
			
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
			
			/*Update Control Number on Receivable*/
			/*Get Receivable ID*/
				
			$receivable_data = ReceivablesModel::where('sales_order_idx', $request->sales_order_id)
				->get(['receivable_id']);
					
			$receivable_id = $receivable_data[0]->receivable_id;
						
			/*Recievable Control Number*/			
			$receivable_control_number = $BranchInfo->branch_initial."-AR-".$receivable_id;	
			
			$Receivables = new ReceivablesModel();
			$Receivables = ReceivablesModel::find($receivable_id);
			$Receivables->control_number 				= $receivable_control_number;
			$Receivables->company_header 				= $request->company_header;
			$Receivables->update();
			
			//return response()->json(array('productlist'=>$data,'paymentcount'=>$paymentcount));	
			if($result){
				return response()->json(array('success' => "Sales Order Successfully Updated!",'sales_order_control_number'=>$control_number), 200);
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
			
			$paymentlist = ReceivablesPaymentModel::where('receivable_idx', '=', $request->receivable_idx)->get();
			$paymentcount = $paymentlist->count();
		
			return response()->json(array('productlist'=>$data,'paymentcount'=>$paymentcount));			
		
			//return response()->json($data);
			
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
	public function sales_order_form(Request $request){
		
		$SalesOrderID = $request->sales_order_id;
		$tab = $request->tab;
		
		$title = 'Sales Order Form';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();/*User Data*/
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			
			$teves_branch = TevesBranchModel::all();
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
			
			$receivables_payment_suggestion = ReceivablesPaymentModel::select('receivable_mode_of_payment')->distinct()->get();
			
			$receivables_details = ReceivablesModel::where('sales_order_idx', '=', $SalesOrderID)->first();

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
					'teves_sales_order_table.sales_order_less_percentage',
					'teves_sales_order_table.sales_order_payment_type']);
				
				if($receivables_details==NULL){
					
					$BranchInfo = TevesBranchModel::where('branch_id', '=', $sales_order_data[0]->company_header)->first();	
					/*Get Last ID*/
					$last_transaction_id = $sales_order_data[0]->sales_order_id;			
					
					$receivable_last_id = ReceivablesModel::latest()->first()->receivable_id;
								
					$receivable_control_number = $BranchInfo->branch_initial."-AR-".$receivable_last_id+1;	
					
					/*Automatic Create the Receivable Entry*/
						
						/*Create Receivable*/
						$Receivables = new ReceivablesModel();
						$Receivables->client_idx 						= $sales_order_data[0]->sales_order_client_idx;
						$Receivables->control_number 					= $receivable_control_number;
						$Receivables->sales_order_idx 					= $last_transaction_id;
						$Receivables->billing_date 						= $sales_order_data[0]->sales_order_date;
						$Receivables->payment_term 						= $sales_order_data[0]->sales_order_payment_term;
						$Receivables->receivable_name 					= $receivable_control_number;
						$Receivables->receivable_status 				= 'Pending';
						$Receivables->receivable_description 			= $sales_order_data[0]->sales_order_control_number;
						$Receivables->company_header 					= $sales_order_data[0]->company_header;
						$Receivables->created_by_user_id 				= Session::get('loginID');
						$result = $Receivables->save();
					
				}	
				
				$receivables_details = ReceivablesModel::where('sales_order_idx', '=', $SalesOrderID)->first();	
					
		return view("pages.sales_order_form", compact('data','title','product_data','client_data','teves_branch', 'sales_order_delivered_to','sales_order_delivered_to_address', 'SalesOrderID','sales_order_data','receivables_payment_suggestion','receivables_details','tab'));
		
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
				
						$action_taken = "Created";						
						
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
				
						$action_taken = "Updated";
							
					}
					
						/*Update Sales Order sales_order_gross_amount,sales_order_net_amount,sales_order_total_due and */
						
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
						$SalesOrderUpdate->sales_order_status = 'Pending';
						$SalesOrderUpdate->update();			
						
						
						/*Update Receivable Amount*/
						$Receivables_ACTION = new ReceivablesModel();
						$Receivables_ACTION = ReceivablesModel::find($request->receivable_id);
						
						$Receivables_ACTION->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');				
						$Receivables_ACTION->receivable_amount 			= number_format($sales_order_total_due,2, '.', '');
						$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
						$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
						$Receivables_ACTION->receivable_status 		= 'Pending';
						$Receivables_ACTION->update();
									
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
				
				/*Update Receivable Amount*/
				$Receivables_ACTION = new ReceivablesModel();
				$Receivables_ACTION = ReceivablesModel::find($request->receivable_id);
				$Receivables_ACTION->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');				
				$Receivables_ACTION->receivable_amount 				= number_format($sales_order_total_due,2, '.', '');
				$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
				$Receivables_ACTION->receivable_status 				= 'Pending';		
				$Receivables_ACTION->update();
	
				return 'Deleted';
		
	} 

	public function save_sales_order_payment(Request $request){
        	 
		  $request->validate([
			//$validator = \Validator::make($request->all(),[
				'payment_image_reference'			=>'image|mimes:jpg,png,jpeg,svg|max:10048',
				'receivable_mode_of_payment'      	=> 'required',
				'receivable_date_of_payment'      	=> 'required',
				'receivable_reference'      		=> ['required',Rule::unique('teves_receivable_payment')->where( 
													fn ($query) =>$query
														->where('receivable_idx', $request->receivable_idx_payment)
														->where('receivable_reference', $request->receivable_reference)
														->where('receivable_payment_id', '<>',  $request->receivable_payment_id )
													)],
				'receivable_payment_amount'       	=> 'required',
           ],[
				'receivable_mode_of_payment.required' 	=> 'Bank Details is Required',
				'receivable_date_of_payment.required' 	=> 'Date of Payment is Required',
				'receivable_reference.required' 		=> 'Reference Number Required',
				'receivable_payment_amount.required' 	=> 'Payment Amount is Required'
           ]);

          // if(!$validator->passes()){
              
			  //return response()->json(['error'=>0,'error'=>$validator->errors()->toArray()]);
         
		  // }else{
			   
			   if ($request->hasFile('payment_image_reference')) {
				   
					   $path = 'files/';
					   $file = $request->file('payment_image_reference');
					   @$file_name = time().'_'.@$file->getClientOriginalName();

						$upload = $file->storeAs($path, $file_name, 'public');
						
						$path = $request->file('payment_image_reference')->getRealPath();    
						$logo = file_get_contents($path);
						$image_blob = base64_encode($logo);
				
							$receivable_idx = $request->receivable_idx_payment;
							$receivable_payment_id = $request->receivable_payment_id;
							
							if($receivable_payment_id==0){
								
								$PaymentComponent = new ReceivablesPaymentModel();
								
								$PaymentComponent->receivable_idx 				= $receivable_idx;
								$PaymentComponent->receivable_mode_of_payment 	= $request->receivable_mode_of_payment;
								$PaymentComponent->receivable_date_of_payment 	= $request->receivable_date_of_payment;
								$PaymentComponent->receivable_reference 		= $request->receivable_reference;
								$PaymentComponent->receivable_payment_amount 	= $request->receivable_payment_amount;
								$PaymentComponent->image_reference 				= $image_blob;
								$result = $PaymentComponent->save();
								$result_type = 'Saved';
							
							}
							else{
							
								$PaymentComponent = new ReceivablesPaymentModel();
								
								$PaymentComponent = ReceivablesPaymentModel::find($receivable_payment_id);
								$PaymentComponent->receivable_mode_of_payment 	= $request->receivable_mode_of_payment;
								$PaymentComponent->receivable_date_of_payment 	= $request->receivable_date_of_payment;
								$PaymentComponent->receivable_reference 		= $request->receivable_reference;
								$PaymentComponent->receivable_payment_amount 	= $request->receivable_payment_amount;
								$PaymentComponent->image_reference 				= $image_blob;
								$result = $PaymentComponent->update();
								$result_type = 'Updated';

							}
					
			   }else{	
				
							$receivable_idx = $request->receivable_idx_payment;
							$receivable_payment_id = $request->receivable_payment_id;
							
							if($receivable_payment_id==0){
								
								$PurchaseOrderPaymentComponent = new ReceivablesPaymentModel();
								
								$PurchaseOrderPaymentComponent->receivable_idx 				= $receivable_idx;
								$PurchaseOrderPaymentComponent->receivable_mode_of_payment 	= $request->receivable_mode_of_payment;
								$PurchaseOrderPaymentComponent->receivable_date_of_payment 	= $request->receivable_date_of_payment;
								$PurchaseOrderPaymentComponent->receivable_reference 		= $request->receivable_reference;
								$PurchaseOrderPaymentComponent->receivable_payment_amount 	= $request->receivable_payment_amount;
								
								$result = $PurchaseOrderPaymentComponent->save();
								$result_type = 'Saved';
							
							}
							else{
							
								$PurchaseOrderPaymentComponent = new ReceivablesPaymentModel();
								//echo "$receivable_payment_id";
								$PurchaseOrderPaymentComponent = ReceivablesPaymentModel::find($receivable_payment_id);
								$PurchaseOrderPaymentComponent->receivable_mode_of_payment 	= $request->receivable_mode_of_payment;
								$PurchaseOrderPaymentComponent->receivable_date_of_payment 	= $request->receivable_date_of_payment;
								$PurchaseOrderPaymentComponent->receivable_reference 		= $request->receivable_reference;
								$PurchaseOrderPaymentComponent->receivable_payment_amount 	= $request->receivable_payment_amount;
								
								$result = $PurchaseOrderPaymentComponent->update();
								$result_type = 'Updated';

							}
							
			   }

				/*Get Sales Order ID from Receivable*/
				$sales_order_id =  ReceivablesModel::find($receivable_idx, ['sales_order_idx']);

				/*Update Sales Order to Delivered*/
				$salesOrderUpdate_status = new SalesOrderModel();
				$salesOrderUpdate_status = SalesOrderModel::find($sales_order_id->sales_order_idx);
				$salesOrderUpdate_status->sales_order_status = 'Delivered';
				$salesOrderUpdate_status->update();
				
				
				
				/*Get Recivable Details [receivable_amount]*/
				$receivable_details = ReceivablesModel::find($receivable_idx, ['receivable_amount']);							
				$receivable_amount = $receivable_details->receivable_amount;
				
				/*Get Payment Details*/
				$receivable_total_payment_amount =  ReceivablesPaymentModel::where('receivable_idx', $receivable_idx)
					->sum('receivable_payment_amount');
				
				/*Compute Balance and Status Creation*/
							$remaining_balance = number_format($receivable_amount - $receivable_total_payment_amount+0,2, '.', '');
							$_paid_percentage = ($receivable_total_payment_amount / $receivable_amount) * 100;
						
							$paid_percentage = number_format($_paid_percentage,2,".","");
						
							/*IF Fully Paid Automatically Update the Status to Paid*/
							if($paid_percentage >= 0.01 && $paid_percentage <= 99)
							{		
								
								$Receivablestatus = "$paid_percentage% Paid";
								
							}else if($paid_percentage >= 100)
							{	
								
								$Receivablestatus = "Paid";
								
							}else{
								
								$Receivablestatus = "Pending";
								
							}
				
						/*Update Receivable Status and Remaining Balance*/
						$Receivables_ACTION = new ReceivablesModel();
						$Receivables_ACTION = ReceivablesModel::find($receivable_idx);		
						$Receivables_ACTION->receivable_remaining_balance 	= number_format($remaining_balance,2, '.', '');
						$Receivables_ACTION->receivable_status 		= $Receivablestatus;		
						$Receivables_ACTION->update();
				
						if($result){
							return response()->json(array('success' => "Payment Information Successfully $result_type!"), 200);
						}
						else{
							return response()->json(['success'=>'Error on Payment Information']);
						}	
               //}
           }	
		
	public function get_sales_order_payment_list(Request $request){		
	
			$data =  ReceivablesPaymentModel::where('receivable_idx', $request->receivable_idx)
				->orderBy('receivable_payment_id', 'asc')
              	->get([
					'receivable_payment_id',
					'receivable_date_of_payment',
					'receivable_mode_of_payment',
					'receivable_reference',
					'receivable_payment_amount',
					'image_reference'
					]);
		
			return response()->json($data);
	}
		
	/*Fetch Receivable Information*/
	public function sales_order_payment_info(Request $request){
					
					$data = ReceivablesPaymentModel::where('receivable_payment_id', $request->receivable_payment_id)
              		->get([
					'receivable_payment_id',
					'receivable_idx',
					'receivable_date_of_payment',
					'receivable_mode_of_payment',
					'receivable_reference',
					'receivable_payment_amount',
					'image_reference'
					]);
					return response()->json($data);
		
	}

	public function sales_order_delete_payment(Request $request){		
			
		$receivable_payment_id = $request->paymentitemID;
		$receivable_idx = $request->receivable_idx;
		
		ReceivablesPaymentModel::find($receivable_payment_id)->delete();
		
				/*Get Sales Order ID from Receivable*/
				$sales_order_id =  ReceivablesModel::find($receivable_idx, ['sales_order_idx']);

				
				
				/*Get Recivable Details [receivable_amount]*/
				$receivable_details = ReceivablesModel::find($receivable_idx, ['receivable_amount']);							
				$receivable_amount = $receivable_details->receivable_amount;
				
				/*Get Payment Details*/
				$receivable_total_payment_amount =  ReceivablesPaymentModel::where('receivable_idx', $receivable_idx)
					->sum('receivable_payment_amount');
				
				
				/*Compute Balance and Status Creation*/
							$remaining_balance = number_format($receivable_amount - $receivable_total_payment_amount+0,2, '.', '');
							$_paid_percentage = ($receivable_total_payment_amount / $receivable_amount) * 100;
						
							$paid_percentage = number_format($_paid_percentage,2,".","");
						
							/*IF Fully Paid Automatically Update the Status to Paid*/
							if($paid_percentage >= 0.01 && $paid_percentage <= 99)
							{		
								
								$Receivablestatus = "$paid_percentage% Paid";
								
							}else if($paid_percentage >= 100)
							{	
								
								$Receivablestatus = "Paid";
								
							}else{
								
								$Receivablestatus = "Pending";
								
							}
				
				
						/*Update Sales Order to Delivered Pending by checking the count of Payment if payment is Zero then update the status to Pending*/
						//$paymentlist = ReceivablesPaymentModel::where('receivable_idx', '=', $receivable_idx)->get();
						//$paymentcount = $paymentlist->count();
						
						if($receivable_total_payment_amount==0){
							
							$salesOrderUpdate_status = new SalesOrderModel();
							$salesOrderUpdate_status = SalesOrderModel::find($sales_order_id->sales_order_idx);
							$salesOrderUpdate_status->sales_order_status = 'Pending';
							$salesOrderUpdate_status->update();
							
						}else{
						
							$salesOrderUpdate_status = new SalesOrderModel();
							$salesOrderUpdate_status = SalesOrderModel::find($sales_order_id->sales_order_idx);
							$salesOrderUpdate_status->sales_order_status = 'Delivered';
							$salesOrderUpdate_status->update();
						
						}
				
						/*Update Receivable Status and Remaining Balance*/
						$Receivables_ACTION = new ReceivablesModel();
						$Receivables_ACTION = ReceivablesModel::find($receivable_idx);		
						$Receivables_ACTION->receivable_remaining_balance 	= number_format($remaining_balance,2, '.', '');
						$Receivables_ACTION->receivable_status 		= $Receivablestatus;		
						$Receivables_ACTION->update();		
		
		return 'Deleted';
		
	}
			
}
