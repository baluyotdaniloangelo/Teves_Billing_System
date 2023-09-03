<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\SalesOrderComponentModel;
use App\Models\SalesOrderPaymentModel;
use Session;
use Validator;
use DataTables;

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
			
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
			$sales_order_mode_of_payment_suggestion = SalesOrderPaymentModel::select('sales_order_mode_of_payment')->distinct()->get();
		
		}

		return view("pages.salesorder", compact('data','title','client_data','product_data','sales_order_delivered_to','sales_order_delivered_to_address','sales_order_mode_of_payment_suggestion'));
		
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
					'teves_sales_order_table.sales_order_status',
					'teves_sales_order_table.sales_order_delivery_status']);
					
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
					'teves_sales_order_table.sales_order_status',
					'teves_sales_order_table.sales_order_delivery_status']);	
			
		}			
	
		return DataTables::of($data)
				->addIndexColumn()
				
				->addColumn('delivery_status', function($row){
					
									
				if($row->sales_order_delivery_status=='Pending'){
					$sales_status_selected = '<option disabled="" value="">Choose...</option><option selected value="Pending">Pending</option><option value="Delivered">Delivered</option>';
				}else if($row->sales_order_delivery_status=='Delivered'){
					$sales_status_selected = '<option disabled="" value="">Choose...</option><option value="Pending">Pending</option><option selected value="Delivered">Delivered</option>';
				}else{
					$sales_status_selected = '<option disabled="" selected value="">Choose...</option><option value="Pending">Pending</option><option value="Delivered">Delivered</option>';
				}
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<select class="sales_order_delivery_status_'.$row->sales_order_id.'" name="sales_order_delivery_status_'.$row->sales_order_id.'" id="sales_order_delivery_status_'.$row->sales_order_id.'" onchange="sales_order_delivery_status('.$row->sales_order_id.')">	
						'.$sales_status_selected.'
						</select>
					</div>';
                    return $actionBtn;
                })
				
				->addColumn('payment_status', function($row){
				
				if($row->sales_order_status=='Pending'){
					$sales_status_selected = '<option disabled="" value="">Choose...</option><option selected value="Pending">Pending</option><option value="Paid">Paid</option>';
				}else if($row->sales_order_status=='Paid'){
					$sales_status_selected = '<option disabled="" value="">Choose...</option><option value="Pending">Pending</option><option selected value="Paid">Paid</option>';
				}else{
					$sales_status_selected = '<option disabled="" selected value="">Choose...</option><option value="Pending">Pending</option><option value="Paid">Paid</option>';
				}
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<select class="sales_order_status_'.$row->sales_order_id.'" name="sales_order_status_'.$row->sales_order_id.'" id="sales_order_status_'.$row->sales_order_id.'" onchange="sales_update_status('.$row->sales_order_id.')">	
						'.$sales_status_selected.'
						</select>
					</div>';
                    return $actionBtn;
                })
				
                ->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action','delivery_status','payment_status'])
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
		
		/*Delete on Sales Order Payment Component*/	
		SalesOrderPaymentModel::where('sales_order_idx', $sales_order_id)->delete();
		
		return 'Deleted';
		
	} 

	public function create_sales_order_post(Request $request){

		$request->validate([
			'client_idx'  	=> 'required',
			'product_idx'  	=> 'required'
        ], 
        [
			'client_idx.required' 	=> 'Client is Required',
			'product_idx.required' 	=> 'Product is Required'
        ]
		);

			@$last_id = SalesOrderModel::latest()->first()->sales_order_id;

			$client_idx = $request->client_idx;
			
			$Salesorder = new SalesOrderModel();
			$Salesorder->sales_order_client_idx 				= $request->client_idx;
			$Salesorder->company_header 						= $request->company_header;
			$Salesorder->sales_order_control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
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
			
			$result = $Salesorder->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;
			
			/*Payment Option*/
			$mode_of_payment 			= $request->mode_of_payment;
			$date_of_payment 			= $request->date_of_payment;
			$reference_no 				= $request->reference_no;
			$payment_amount 			= $request->payment_amount;
			
			if($payment_amount!=''){
			for($count = 0; $count < count($mode_of_payment); $count++)
			{
				
					$mode_of_payment_item 	= $mode_of_payment[$count];
					$date_of_payment_item 	= $date_of_payment[$count];
					$reference_no_item 		= $reference_no[$count];
					$payment_amount_item 	= $payment_amount[$count];
				
				$SalesOrderPaymentComponent = new SalesOrderPaymentModel();
				
				$SalesOrderPaymentComponent->sales_order_idx 				= $last_transaction_id;
				$SalesOrderPaymentComponent->sales_order_mode_of_payment 	= $mode_of_payment_item;
				$SalesOrderPaymentComponent->sales_order_date_of_payment 	= $date_of_payment_item;
				$SalesOrderPaymentComponent->sales_order_reference_no 		= $reference_no_item;
				$SalesOrderPaymentComponent->sales_order_payment_amount 	= $payment_amount_item;
				
				$SalesOrderPaymentComponent->save();
				
			}			
			}
			
			$product_idx 			= $request->product_idx;
			$order_quantity 		= $request->order_quantity;
			$product_manual_price 	= $request->product_manual_price;

			$gross_amount = 0;
			
			for($count = 0; $count < count($product_idx); $count++)
			{
				
					$sales_order_item_product_id 			= $product_idx[$count];
					$sales_order_item_order_quantity 		= $order_quantity[$count];
					$sales_order_item_product_manual_price 	= $product_manual_price[$count];

				/*Product Details*/
				$product_info = ProductModel::find($sales_order_item_product_id, ['product_price']);					
				
				/*Check if Price is From Manual Price*/
				if($sales_order_item_product_manual_price!=0){
					$product_price = $sales_order_item_product_manual_price;
				}else{
					$product_price = $product_info->product_price;
				}
				
				$order_total_amount = $sales_order_item_order_quantity * $product_price;
				
				$gross_amount += $order_total_amount;
				
				/*Save to teves_sales_order_component_table(SalesOrderComponentModel)*/
				$SalesOrderComponentModel = new SalesOrderComponentModel();
				
				$SalesOrderComponentModel->sales_order_idx 			= $last_transaction_id;
				$SalesOrderComponentModel->product_idx 				= $sales_order_item_product_id;
				$SalesOrderComponentModel->sales_order_date 		= $request->sales_order_date;
				$SalesOrderComponentModel->client_idx 				= $request->client_idx;
				$SalesOrderComponentModel->order_quantity 			= $sales_order_item_order_quantity;
				$SalesOrderComponentModel->product_price 			= $product_price;
				$SalesOrderComponentModel->order_total_amount 		= $order_total_amount;
				
				$SalesOrderComponentModel->save();
				
			}

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
				return response()->json(array('success' => "Sales Order Successfully Created!", 'sales_order_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Sales Order Information']);
			}
	}

	public function update_sales_order_post(Request $request){	

		$request->validate([
			'client_idx'  	=> 'required',
			'product_idx'  	=> 'required'
        ], 
        [
			'client_idx.required' 	=> 'Client is Required',
			'product_idx.required' 	=> 'Product is Required'
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
			$Salesorder->sales_order_mode_of_payment 			= $request->mode_of_payment;
			$Salesorder->sales_order_date_of_payment 			= $request->date_of_payment;
			$Salesorder->sales_order_reference_no 				= $request->reference_no;
			$Salesorder->sales_order_payment_amount 			= $request->payment_amount;
			
			$Salesorder->sales_order_net_percentage 			= $request->sales_order_net_percentage;
			$Salesorder->sales_order_less_percentage 			= $request->sales_order_less_percentage;
			
			$result = $Salesorder->update();
			
			/*Get Last ID*/
			$last_transaction_id = $request->sales_order_id;
			
			/*Payment Option*/
			$payment_id					= $request->payment_item_id;
			$mode_of_payment 			= $request->mode_of_payment;
			$date_of_payment 			= $request->date_of_payment;
			$reference_no 				= $request->reference_no;
			$payment_amount 			= $request->payment_amount;
			
			if($payment_amount!=''){
			for($count = 0; $count < count($mode_of_payment); $count++)
			{
				
					$mode_of_payment_item 	= $mode_of_payment[$count];
					$date_of_payment_item 	= $date_of_payment[$count];
					$reference_no_item 		= $reference_no[$count];
					$payment_amount_item 	= $payment_amount[$count];
					$payment_id_item 		= $payment_id[$count];
			
				if($payment_id_item==0){
						
					$SalesOrderPaymentComponent = new SalesOrderPaymentModel();
					
					$SalesOrderPaymentComponent->sales_order_idx 				= $last_transaction_id;
					$SalesOrderPaymentComponent->sales_order_mode_of_payment 	= $mode_of_payment_item;
					$SalesOrderPaymentComponent->sales_order_date_of_payment 	= $date_of_payment_item;
					$SalesOrderPaymentComponent->sales_order_reference_no 		= $reference_no_item;
					$SalesOrderPaymentComponent->sales_order_payment_amount 	= $payment_amount_item;
					
					$SalesOrderPaymentComponent->save();
					
				}else{
					
					$SalesOrderPaymentComponent = new SalesOrderPaymentModel();
					
					$SalesOrderPaymentComponent = SalesOrderPaymentModel::find($payment_id_item);
					
					$SalesOrderPaymentComponent->sales_order_mode_of_payment 	= $mode_of_payment_item;
					$SalesOrderPaymentComponent->sales_order_date_of_payment 	= $date_of_payment_item;
					$SalesOrderPaymentComponent->sales_order_reference_no 		= $reference_no_item;
					$SalesOrderPaymentComponent->sales_order_payment_amount 	= $payment_amount_item;
					
					$SalesOrderPaymentComponent->update();
					
				}
			
			}		
			}
			
			$product_idx 					= $request->product_idx;
			$order_quantity 				= $request->order_quantity;
			$product_manual_price 			= $request->product_manual_price;
			$sales_order_product_item_ids 	= $request->sales_order_product_item_ids;
			
			$gross_amount = 0;
			
			for($count = 0; $count < count($product_idx); $count++)
			{
				
					$sales_order_item_product_id 			= $product_idx[$count];
					$sales_order_item_order_quantity 		= $order_quantity[$count];
					$sales_order_item_product_manual_price 	= $product_manual_price[$count];
					/*Check if Already Exist, if exist update using the id, if not insert new item*/
					$sales_order_product_item_id 			= $sales_order_product_item_ids[$count];
					
				/*Product Details*/
				$product_info = ProductModel::find($sales_order_item_product_id, ['product_price']);					
				
				/*Check if Price is From Manual Price*/
				if($sales_order_item_product_manual_price!=0){
					$product_price = $sales_order_item_product_manual_price;
				}else{
					$product_price = $product_info->product_price;
				}
				
				$order_total_amount = $sales_order_item_order_quantity * $product_price;
				
				$gross_amount += $order_total_amount;
				
				if($sales_order_product_item_id==0){
				
				/*Save to teves_sales_order_component_table(SalesOrderComponentModel)*/
				$SalesOrderComponentModel = new SalesOrderComponentModel();
				
				$SalesOrderComponentModel->sales_order_idx 			= $last_transaction_id;
				$SalesOrderComponentModel->sales_order_date 		= $request->sales_order_date;
				$SalesOrderComponentModel->product_idx 				= $sales_order_item_product_id;
				$SalesOrderComponentModel->client_idx 				= $request->client_idx;
				$SalesOrderComponentModel->order_quantity 			= $sales_order_item_order_quantity;
				$SalesOrderComponentModel->product_price 			= $product_price;
				$SalesOrderComponentModel->order_total_amount 		= $order_total_amount;
				
				$SalesOrderComponentModel->save();
				
				}else{
				
				/*Update*/
				$SalesOrderComponentModel_update = new SalesOrderComponentModel();
				$SalesOrderComponentModel_update = SalesOrderComponentModel::find($sales_order_product_item_id);
				
				$SalesOrderComponentModel_update->product_idx 				= $sales_order_item_product_id;
				$SalesOrderComponentModel_update->sales_order_date 			= $request->sales_order_date;
				$SalesOrderComponentModel_update->client_idx 				= $request->client_idx;
				$SalesOrderComponentModel_update->order_quantity 			= $sales_order_item_order_quantity;
				$SalesOrderComponentModel_update->product_price 			= $product_price;
				$SalesOrderComponentModel_update->order_total_amount 		= $order_total_amount;
				
				$SalesOrderComponentModel_update->update();
			
				}
			}
			
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
	
			$data = SalesOrderComponentModel::where('sales_order_idx', $request->sales_order_id)
					->orderBy('sales_order_component_id', 'asc')
              		->get([
					'sales_order_component_id',
					'product_idx',
					'product_price',
					'order_quantity']);
		
			return response()->json($data);
	}

	public function delete_sales_order_item(Request $request){		
			
		$productitemID = $request->productitemID;
		SalesOrderComponentModel::find($productitemID)->delete();
		return 'Deleted';
		
	}
	
	public function get_sales_order_payment_list(Request $request){		
	
			$data =  SalesOrderPaymentModel::where('teves_sales_order_payment_details.sales_order_idx', $request->sales_order_id)
				->orderBy('sales_order_payment_details_id', 'asc')
              	->get([
					'teves_sales_order_payment_details.sales_order_payment_details_id',
					'teves_sales_order_payment_details.sales_order_mode_of_payment',
					'teves_sales_order_payment_details.sales_order_date_of_payment',
					'teves_sales_order_payment_details.sales_order_reference_no',
					'teves_sales_order_payment_details.sales_order_payment_amount',
					]);
		
			return response()->json($data);
	}

	public function delete_sales_order_payment_item(Request $request){		
			
		$paymentitemID = $request->paymentitemID;
		SalesOrderPaymentModel::find($paymentitemID)->delete();
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
}
