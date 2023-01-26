<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\PurchaseOrderComponentModel;
use Session;
use Validator;
use DataTables;

class PurchaseOrderController extends Controller
{
	
	/*Load Product Interface*/
	public function Purchaseorder(){
		
		$title = 'Purchase Order';
		$data = array();
		if(Session::has('loginID')){
			
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		}

		return view("pages.Purchaseorder", compact('data','title','client_data','product_data'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getPurchaseOrderList(Request $request)
    {
		$list = PurchaseOrderModel::get();
		if ($request->ajax()) {

    	$data = PurchaseOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_Purchase_order_table.Purchase_order_client_idx')
              		->get([
					'teves_Purchase_order_table.Purchase_order_id',
					'teves_Purchase_order_table.Purchase_order_date',
					'teves_client_table.client_name',
					'teves_Purchase_order_table.Purchase_order_control_number',
					'teves_Purchase_order_table.Purchase_order_dr_number',
					'teves_Purchase_order_table.Purchase_order_or_number',					
					'teves_Purchase_order_table.Purchase_order_payment_term',
					'teves_Purchase_order_table.Purchase_order_total_due']);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->Purchase_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintPurchaseOrder""></a>
					<a href="#" data-id="'.$row->Purchase_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditPurchaseOrder"></a>
					<a href="#" data-id="'.$row->Purchase_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deletePurchaseOrder"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function Purchase_order_info(Request $request){
		
					$data = PurchaseOrderModel::where('Purchase_order_id', $request->Purchase_order_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_Purchase_order_table.Purchase_order_client_idx')
						
					->get([
					'teves_Purchase_order_table.Purchase_order_id',
					'teves_Purchase_order_table.Purchase_order_date',
					'teves_Purchase_order_table.Purchase_order_client_idx',
					'teves_client_table.client_name',
					'teves_Purchase_order_table.Purchase_order_control_number',
					'teves_Purchase_order_table.Purchase_order_dr_number',
					'teves_Purchase_order_table.Purchase_order_or_number',		
					'teves_Purchase_order_table.Purchase_order_payment_term',
					'teves_Purchase_order_table.Purchase_order_delivered_to',
					'teves_Purchase_order_table.Purchase_order_delivered_to_address',
					'teves_Purchase_order_table.Purchase_order_delivery_method',
					'teves_Purchase_order_table.Purchase_order_total_due',
					'teves_Purchase_order_table.Purchase_order_hauler',
					'teves_Purchase_order_table.Purchase_order_required_date',
					'teves_Purchase_order_table.Purchase_order_instructions',
					'teves_Purchase_order_table.Purchase_order_note',
					'teves_Purchase_order_table.Purchase_order_mode_of_payment',
					'teves_Purchase_order_table.Purchase_order_date_of_payment',
					'teves_Purchase_order_table.Purchase_order_reference_no',
					'teves_Purchase_order_table.Purchase_order_payment_amount']);
					return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_Purchase_order_confirmed(Request $request){

		$Purchase_order_id = $request->Purchase_order_id;
		PurchaseOrderModel::find($Purchase_order_id)->delete();
		return 'Deleted';
		
	} 

	public function create_Purchase_order_post(Request $request){

		$request->validate([
			'client_idx'  	=> 'required',
			'product_idx'  	=> 'required'
        ], 
        [
			'client_idx.required' 	=> 'Client is Required',
			'product_idx.required' 	=> 'Product is Required'
        ]
		);

			@$last_id = PurchaseOrderModel::latest()->first()->Purchase_order_id;

			$client_idx = $request->client_idx;
			
			$Purchaseorder = new PurchaseOrderModel();
			$Purchaseorder->Purchase_order_client_idx 				= $request->client_idx;
			$Purchaseorder->Purchase_order_control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Purchaseorder->Purchase_order_date 						= $request->Purchase_order_date;
			$Purchaseorder->Purchase_order_delivered_to 				= $request->delivered_to;
			$Purchaseorder->Purchase_order_delivered_to_address 		= $request->delivered_to_address;
			$Purchaseorder->Purchase_order_dr_number 					= $request->dr_number;
			$Purchaseorder->Purchase_order_or_number 					= $request->or_number;
			$Purchaseorder->Purchase_order_payment_term 				= $request->payment_term;
			$Purchaseorder->Purchase_order_delivery_method 			= $request->delivery_method;
			$Purchaseorder->Purchase_order_hauler 					= $request->hauler;
			$Purchaseorder->Purchase_order_required_date 				= $request->required_date;
			$Purchaseorder->Purchase_order_instructions 				= $request->instructions;
			$Purchaseorder->Purchase_order_note 						= $request->note;
			$Purchaseorder->Purchase_order_mode_of_payment 			= $request->mode_of_payment;
			$Purchaseorder->Purchase_order_date_of_payment 			= $request->date_of_payment;
			$Purchaseorder->Purchase_order_reference_no 				= $request->reference_no;
			$Purchaseorder->Purchase_order_payment_amount 			= $request->payment_amount;
			
			$result = $Purchaseorder->save();
			
			$product_idx 			= $request->product_idx;
			$order_quantity 		= $request->order_quantity;
			$product_manual_price 	= $request->product_manual_price;
			
			/*Get Last ID*/
			$last_transaction_id = $Purchaseorder->Purchase_order_id;
			
			$gross_amount = 0;
			
			for($count = 0; $count < count($product_idx); $count++)
			 {
				
					$Purchase_order_item_product_id 			= $product_idx[$count];
					$Purchase_order_item_order_quantity 		= $order_quantity[$count];
					$Purchase_order_item_product_manual_price 	= $product_manual_price[$count];

				/*Product Details*/
				$product_info = ProductModel::find($Purchase_order_item_product_id, ['product_price']);					
				
				/*Check if Price is From Manual Price*/
				if($Purchase_order_item_product_manual_price!=0){
					$product_price = $Purchase_order_item_product_manual_price;
				}else{
					$product_price = $product_info->product_price;
				}
				
				$order_total_amount = $Purchase_order_item_order_quantity * $product_price;
				
				$gross_amount += $order_total_amount;
				
				/*Save to teves_Purchase_order_component_table(PurchaseOrderComponentModel)*/
				$PurchaseOrderComponentModel = new PurchaseOrderComponentModel();
				
				$PurchaseOrderComponentModel->Purchase_order_idx 			= $last_transaction_id;
				$PurchaseOrderComponentModel->product_idx 				= $Purchase_order_item_product_id;
				$PurchaseOrderComponentModel->client_idx 				= $request->client_idx;
				$PurchaseOrderComponentModel->order_quantity 			= $Purchase_order_item_order_quantity;
				$PurchaseOrderComponentModel->product_price 			= $product_price;
				$PurchaseOrderComponentModel->order_total_amount 		= $order_total_amount;
				
				$PurchaseOrderComponentModel->save();
				
			 }
			
			/*Update Purchase Order Gross, Net and Total Due*/
			/*
			Gross amount total ng product
			Net amount = gross divide 1.12
			Less 1% = net * 0.1

			Total Due = gross amount - less 1%
			*/
			$PurchaseOrderUpdate = new PurchaseOrderModel();
			$PurchaseOrderUpdate = PurchaseOrderModel::find($last_transaction_id);
			$PurchaseOrderUpdate->Purchase_order_gross_amount = $gross_amount;
			$PurchaseOrderUpdate->Purchase_order_net_amount = $gross_amount/1.12;
			$PurchaseOrderUpdate->Purchase_order_total_due = $gross_amount - (($gross_amount/1.12)*0.1);
			$PurchaseOrderUpdate->update();
			
			if($result){
				return response()->json(array('success' => "Purchase Order Successfully Created!"), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Purchase Order Information']);
			}
	}

	public function update_Purchase_order_post(Request $request){		
		
	$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);
					
			$Receivables = new PurchaseOrderModel();
			$Receivables = PurchaseOrderModel::find($request->Purchase_order_id);
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
