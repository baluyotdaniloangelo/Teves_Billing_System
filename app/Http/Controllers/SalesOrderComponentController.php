<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\SalesOrderComponentModel;
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
		
		}

		return view("pages.salesorder", compact('data','title','client_data','product_data'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getSalesOrderList(Request $request)
    {
		$list = SalesOrderModel::get();
		if ($request->ajax()) {

    	$data = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',					
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due']);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder"></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function sales_order_info(Request $request){
		
					$data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
						
					->get([
					'teves_sales_order_table.sales_order_id',
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
					'teves_sales_order_table.sales_order_payment_amount']);
					return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_sales_order_confirmed(Request $request){

		$sales_order_id = $request->sales_order_id;
		SalesOrderModel::find($sales_order_id)->delete();
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
			$Salesorder->sales_order_mode_of_payment 			= $request->mode_of_payment;
			$Salesorder->sales_order_date_of_payment 			= $request->date_of_payment;
			$Salesorder->sales_order_reference_no 				= $request->reference_no;
			$Salesorder->sales_order_payment_amount 			= $request->payment_amount;
			
			$result = $Salesorder->save();
			
			$product_idx 			= $request->product_idx;
			$order_quantity 		= $request->order_quantity;
			$product_manual_price 	= $request->product_manual_price;
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;
			
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
				$SalesOrderComponentModel->client_idx 				= $request->client_idx;
				$SalesOrderComponentModel->order_quantity 			= $sales_order_item_order_quantity;
				$SalesOrderComponentModel->product_price 			= $product_price;
				$SalesOrderComponentModel->order_total_amount 		= $order_total_amount;
				
				$SalesOrderComponentModel->save();
				
			 }
			
			/*Update Sales Order Gross, Net and Total Due*/
			/*
			Gross amount total ng product
			Net amount = gross divide 1.12
			Less 1% = net * 0.1

			Total Due = gross amount - less 1%
			*/
			$SalesOrderUpdate = new SalesOrderModel();
			$SalesOrderUpdate = SalesOrderModel::find($last_transaction_id);
			$SalesOrderUpdate->sales_order_gross_amount = $gross_amount;
			$SalesOrderUpdate->sales_order_net_amount = $gross_amount/1.12;
			$SalesOrderUpdate->sales_order_total_due = $gross_amount - (($gross_amount/1.12)*0.1);
			$SalesOrderUpdate->update();
			
			if($result){
				return response()->json(array('success' => "Sales Order Successfully Created!"), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Sales Order Information']);
			}
	}

	public function update_sales_order_post(Request $request){		
		
	$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);
					
			$Receivables = new SalesOrderModel();
			$Receivables = SalesOrderModel::find($request->sales_order_id);
			$Receivables->billing_date 				= $request->billing_date;
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_status 		=  $request->receivable_status;
			
			$result = $Receivables->update();
			
			if($result){
				return response()->json(['success'=>'Receivables Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Receivables Information']);
			}
	}
	
}
