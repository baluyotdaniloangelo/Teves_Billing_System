<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BillingTransactionModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;

//use Spatie\Activitylog\Models\Activity;

class BillingTransactionController extends Controller
{
	
	/*Load Site Interface*/
	public function billing(){
		
		$title = 'Billing Transaction';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$product_data = ProductModel::all();
			
			$client_data = ClientModel::all();
			
			$drivers_name = BillingTransactionModel::select('drivers_name')->distinct()->get();
			$plate_no = BillingTransactionModel::select('plate_no')->distinct()->get();
		
		}

		return view("pages.billing", compact('data','title','product_data','client_data','drivers_name','plate_no'));
		
	}   
	
	/*Fetch Site List using Datatable*/
	public function getBillingTransactionList(Request $request)
    {

		$list = BillingTransactionModel::get();
		if ($request->ajax()) {

    	$data = BillingTransactionModel::join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
              		->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_billing_table.client_idx')
					->leftJoin('teves_receivable_table', 'teves_receivable_table.receivable_id', '=', 'teves_billing_table.receivable_idx')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.receivable_idx',
					'teves_receivable_table.control_number',
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_client_table.client_name',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time',
					'teves_billing_table.created_at',]);
		
		return DataTables::of($data)
				
				->addIndexColumn()				
                ->addColumn('action', function($row){
						
					if($row->receivable_idx==0){
						
						if(Session::get('UserType')=="Admin"){
							$actionBtn = '
							<div align="center" class="action_table_menu_site">
							<a href="#" data-id="'.$row->billing_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editBill"></a>
							<a href="#" data-id="'.$row->billing_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteBill"></a>
							</div>';
						}
						else{
							
							if($numberDays>=1){
								$actionBtn = '
								<div align="center" class="action_table_menu_site">
								<a href="#" data-id="'.$row->billing_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_edit" id="viewBill"></a>
								</div>';
							}else{
								$actionBtn = '
								<div align="center" class="action_table_menu_site">
								<a href="#" data-id="'.$row->billing_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editBill"></a>
								<a href="#" data-id="'.$row->billing_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteBill"></a>
								</div>';
							}

						}

					}else{
						
						$actionBtn = '
						<div align="center" class="action_table_menu_site">
						<a href="#" data-id="'.$row->billing_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_edit" id="viewBill"></a>
						</div>';
					
					}
                    return $actionBtn;
                })
				
				 ->addColumn('quantity_measurement', function($row){
                 											
					return  $row->order_quantity." ".$row->product_unit_measurement;
                    //return $actionBtn;
                })
				
				->rawColumns(['action','quantity_measurement'])
                ->make(true);		
		}
    }

	/*Fetch Site Information*/
	public function bill_info(Request $request){

		$billID = $request->billID;
		$data = BillingTransactionModel::where('billing_id', $request->billID)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
              		->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_billing_table.client_idx')	
              		->get([
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_client_table.client_name',
					'teves_client_table.client_id as client_idx',
					'teves_billing_table.order_date',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time']);
		return response()->json($data);
		
	}

	/*Delete Site Information*/
	public function delete_bill_confirmed(Request $request){

		$billID = $request->billID;
		BillingTransactionModel::find($billID)->delete();
		return 'Deleted';
		
	} 

	public function create_bill_post(Request $request){

		$request->validate([
          'order_date'      		=> 'required',
		  'order_time'      		=> 'required',
		  'order_po_number'      	=> 'required',
		  'client_idx'      		=> 'required',
		  'plate_no'      			=> 'required',
		  'drivers_name'      		=> 'required',
		  'product_idx'      		=> 'required',
		  'order_quantity'      	=> 'required',
        ], 
        [
			'order_date.required' => 'Order Date is required',
			'order_time.required' => 'Order Time is Required',
			'order_po_number.required' => 'SO is Required',
			'client_idx.required' => 'Client is Required',
			'plate_no.required' => 'Plate Number is Required',
			'drivers_name.required' => "Driver's Name is Required",
			'product_idx.required' => 'Product is Required',
			'order_quantity.required' => 'Quantity is Required'
        ]
		);
			
			$so_count_check = BillingTransactionModel::where('order_po_number', '=', $request->order_po_number)->get();
			$so_total_count = $so_count_check->count();
			
			if($so_total_count == 6){
				
					return response()->json(array('so_error'=>true), 200);
					
			}else{
				
					/*Product Details*/
					$product_info = ProductModel::find($request->product_idx, ['product_price']);					
					
					/*Check if Price is From Manual Price*/
					if($request->product_manual_price!=0){
						$product_price = $request->product_manual_price;
					}else{
						$product_price = $product_info->product_price;
					}
					
					$order_total_amount = $request->order_quantity * $product_price;	
					
					/*insert*/
					$Billing = new BillingTransactionModel();
					
					$Billing->order_date 			= $request->order_date;
					$Billing->order_time 			= $request->order_time;
					$Billing->order_po_number 		= $request->order_po_number;	
					$Billing->client_idx 			= $request->client_idx;
					$Billing->plate_no 				= $request->plate_no;
					$Billing->drivers_name 			= $request->drivers_name;
					$Billing->product_idx 			= $request->product_idx;
					$Billing->product_price 		= $product_price;
					$Billing->order_quantity 		= $request->order_quantity;
					$Billing->order_total_amount 	= $order_total_amount;
					
					$result = $Billing->save();
												
					/*
					$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
					var_dump($user_data);
					activity() 
					->performedOn($Billing)
					->causedBy($user_data->user_id)
				   // ->withProperties(['business_name' => $request->order_quantity])
					->log('Business <span class="text-green">Updated</span>  - '.$request->order_quantity);
					*/
			
					if($result){
						return response()->json(array('success' => "Bill Information Successfully Created!", 'so_error' => false), 200);
					}
					else{
						return response()->json(['success'=>'Error on Insert Bill Information','so_error'=>false]);
					}	

			}				
	}

	public function update_bill_post(Request $request){
			
		$request->validate([
          'order_date'      		=> 'required',
		  'order_time'      		=> 'required',
		  'order_po_number'      	=> 'required',
		  'client_idx'      		=> 'required',
		  'plate_no'      			=> 'required',
		  'drivers_name'      		=> 'required',
		  'product_idx'      		=> 'required',
		  'order_quantity'      	=> 'required',
        ], 
        [
			'order_date.required' => 'Order Date is required',
			'order_time.required' => 'Order Time is Required',
			'order_po_number.required' => 'SO is Required',
			'client_idx.required' => 'Client is Required',
			'plate_no.required' => 'Plate Number is Required',
			'drivers_name.required' => "Driver's Name is Required",
			'product_idx.required' => 'Product is Required',
			'order_quantity.required' => 'Quantity is Required'
        ]
		);

			//$data = $request->all();
			
			$so_count_check = BillingTransactionModel::where('order_po_number', '=', $request->order_po_number)->get();
			$so_total_count = $so_count_check->count();
			
			if($so_total_count == 6){
				
					return response()->json(array('so_error'=>true), 200);
					
			}else{
					
					/*Product Details*/
					$product_info = ProductModel::find($request->product_idx, ['product_price']);		

					/*Check if Price is From Manual Price*/
					if($request->product_manual_price!=0){
						$product_price = $request->product_manual_price;
					}else{
						$product_price = $product_info->product_price;
					}
					
					$order_total_amount = $request->order_quantity * $product_price;
					
					$Billing = new BillingTransactionModel();
					$Billing = BillingTransactionModel::find($request->billID);
					
					$Billing->order_date 			= $request->order_date;
					$Billing->order_time 			= $request->order_time;
					$Billing->order_po_number 		= $request->order_po_number;	
					$Billing->client_idx 			= $request->client_idx;
					$Billing->plate_no 				= $request->plate_no;
					$Billing->drivers_name 			= $request->drivers_name;
					$Billing->product_idx 			= $request->product_idx;
					$Billing->product_price 		= $product_price;
					$Billing->order_quantity 		= $request->order_quantity;
					$Billing->order_total_amount 	= $order_total_amount;
										
					$result = $Billing->update();

					$lastActivity = Activity::all()->last(); //returns the last logged activity
					$lastActivity->causer;
					$lastActivity->getExtraProperty('key'); //returns 'value'
					$lastActivity->where('properties->key', 'value')->get(); // get all activity where the `key` custom property is 'value'
				

					if($result){
						return response()->json(array('success' => "Bill Information Successfully Updated!", 'so_error' => false), 200);
					}
					else{
						return response()->json(['success'=>'Error on Update Bill Information','so_error'=>false]);
					}	
			}
	}

	public function count_so_number(Request $request){		
			
		/*If Reached to 6 Item Block and Clear Form input for SO*/	
			
		//$paymentitemID = $request->paymentitemID;
		//PurchaseOrderPaymentModel::find($paymentitemID)->delete();
		//return 'Deleted';
		
		
	}
	
}
