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

	public function createsalesorder(){
		
		$title = 'Sales Order';
		$data = array();
		if(Session::has('loginID')){
			
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		}

		return view("pages.createsalesorder", compact('data','title','client_data','product_data'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getSalesOrderList(Request $request)
    {
		$list = SalesOrderModel::get();
		if ($request->ajax()) {

    	$data = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.client_idx')
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					'teves_sales_order_table.control_number',
					'teves_sales_order_table.dr_number',
					'teves_sales_order_table.or_number',					
					'teves_sales_order_table.payment_term',
					'teves_sales_order_table.total_due']);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintReceivables""></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editReceivables"></a>
					<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteReceivables"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function sales_order_info(Request $request){

					//$sales_order_id = $request->sales_order_id;
					$data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.client_idx')
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.billing_date',
					'teves_client_table.client_address',
					'teves_client_table.client_name',
					'teves_sales_order_table.control_number',
					'teves_client_table.client_tin',
					'teves_sales_order_table.or_number',				
					'teves_sales_order_table.payment_term',
					'teves_sales_order_table.receivable_amount',
					'teves_sales_order_table.receivable_status']);
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
			'client_idx'  	=> 'required'
        ], 
        [
			'client_idx.required' 	=> 'Client is Required'
        ]
		);

			@$last_id = SalesOrderModel::latest()->first()->sales_order_id;

			$client_idx = $request->client_idx;
			
			/*
						client_idx
						sales_order_date
						dr_number
						or_number
						payment_term
						delivery_method
						hauler
						required_date
						instructions
						note
						mode_of_payment
						date_of_payment
						reference_no
						payment_amount
						
						
						 product_idx:product_idx,
				  order_quantity:order_quantity,
				  product_manual_price:product_manual_price,
			*/
			
			$Salesorder = new SalesOrderModel();
			$Salesorder->client_idx 				= $request->client_idx;
			$Salesorder->control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Salesorder->sales_order_date 			= $request->sales_order_date;
			$Salesorder->delivered_to 				= $request->delivered_to;
			$Salesorder->delivered_to_address 		= $request->delivered_to_address;
			$Salesorder->dr_number 					= $request->dr_number;
			$Salesorder->or_number 					= $request->or_number;
			$Salesorder->payment_term 				= $request->payment_term;
			$Salesorder->delivery_method 			= $request->delivery_method;
			$Salesorder->hauler 					= $request->hauler;
			$Salesorder->required_date 				= $request->required_date;
			$Salesorder->instructions 				= $request->instructions;
			$Salesorder->note 						= $request->note;
			$Salesorder->mode_of_payment 			= $request->mode_of_payment;
			$Salesorder->date_of_payment 			= $request->date_of_payment;
			$Salesorder->reference_no 				= $request->reference_no;
			$Salesorder->payment_amount 			= $request->payment_amount;
			
			$result = $Salesorder->save();
			
			$product_idx 			= $request->product_idx;
			$order_quantity 		= $request->order_quantity;
			$product_manual_price 	= $request->product_manual_price;
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;
					
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
				
				/*Save to teves_sales_order_component_table(SalesOrderComponentModel)*/
				$SalesOrderComponentModel = new SalesOrderComponentModel();
				
				$SalesOrderComponentModel->sales_order_idx 			= $last_transaction_id;
				$SalesOrderComponentModel->product_idx 				= $sales_order_item_product_id;
				$SalesOrderComponentModel->client_idx 				= $request->client_idx;
				$SalesOrderComponentModel->order_quantity 			= $sales_order_item_order_quantity;
				$SalesOrderComponentModel->product_price 			= $product_price;
				$SalesOrderComponentModel->order_total_amount 		= $order_total_amount;
				
				$result = $SalesOrderComponentModel->save();
				
			 }
			
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
