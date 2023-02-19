<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PurchaseOrderModel;
use App\Models\ProductModel;
use App\Models\PurchaseOrderComponentModel;
use App\Models\PurchaseOrderPaymentModel;
use Session;
use Validator;
use DataTables;

class PurchaseOrderController extends Controller
{
	
	/*Load Product Interface*/
	public function purchaseorder(){
		
		$title = 'Purchase Order';
		$data = array();
		if(Session::has('loginID')){
			
			$product_data = ProductModel::all();
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$purchase_data_suggestion = PurchaseOrderModel::select('purchase_supplier_name','purchase_supplier_tin','purchase_supplier_address','purchase_order_bank','purchase_loading_terminal','hauler_operator','lorry_driver','plate_number','contact_number','purchase_destination','purchase_destination_address')->distinct()->get();
		
		}

		return view("pages.purchaseorder", compact('data','title','product_data','purchase_data_suggestion'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getPurchaseOrderList(Request $request)
    {
		$list = PurchaseOrderModel::get();
		if ($request->ajax()) {

		
    	$data = PurchaseOrderModel::select(
		'purchase_order_id',
		'purchase_order_date',
		'purchase_order_control_number',
		'purchase_supplier_name',
		'purchase_order_total_payable',
		'purchase_status');

		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('status', function($row){
					
							
		if($row->purchase_status=='Pending'){
			$purchase_status_selected = '<option disabled="" value="">Choose...</option><option selected value="Pending">Pending</option><option value="Paid">Paid</option>';
		}else if($row->purchase_status=='Paid'){
			$purchase_status_selected = '<option disabled="" value="">Choose...</option><option value="Pending">Pending</option><option selected value="Paid">Paid</option>';
		}else{
			$purchase_status_selected = '<option disabled="" selected value="">Choose...</option><option value="Pending">Pending</option><option value="Paid">Paid</option>';
		}
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<select class="purchase_order_status_'.$row->purchase_order_id.'" name="purchase_order_status_'.$row->purchase_order_id.'" id="purchase_order_status_'.$row->purchase_order_id.'" onchange="purchase_update_status('.$row->purchase_order_id.')">
						
						'.$purchase_status_selected.'
						</select>
					</div>';
                    return $actionBtn;
                })
				->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->purchase_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintPurchaseOrder""></a>
					<a href="#" data-id="'.$row->purchase_order_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditPurchaseOrder"></a>
					<a href="#" data-id="'.$row->purchase_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deletePurchaseOrder"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action','status'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function purchase_order_info(Request $request){
		
					$data = PurchaseOrderModel::find($request->purchase_order_id, [
						'purchase_supplier_name',
						'purchase_supplier_tin',
						'purchase_supplier_address',
						'purchase_order_control_number',
						'purchase_order_date',
						'purchase_order_sales_order_number',
						'purchase_order_collection_receipt_no',
						'purchase_order_official_receipt_no',
						'purchase_order_delivery_receipt_no',
						'purchase_order_delivery_method',
						'purchase_loading_terminal',
						'purchase_order_gross_amount',
						'purchase_order_total_liters',
						'purchase_order_net_percentage', 
						'purchase_order_net_amount',
						'purchase_order_less_percentage',
						'purchase_order_total_payable',
						'hauler_operator',
						'lorry_driver',
						'plate_number',
						'contact_number',
						'purchase_destination',
						'purchase_destination_address',
						'purchase_date_of_departure',
						'purchase_date_of_arrival',
						'purchase_order_instructions',
						'purchase_order_note']);
					return response()->json($data);	
		
	}

	/*Delete Product Information*/
	public function delete_purchase_order_confirmed(Request $request){

		/*Delete on Purchase Order Table*/
		$purchase_order_id = $request->purchase_order_id;
		PurchaseOrderModel::find($purchase_order_id)->delete();
		
		/*Delete on Purchase Order Product Component*/
		PurchaseOrderComponentModel::where('purchase_order_idx', $purchase_order_id)->delete();
		
		/*Delete on Purchase Order Payment Component*/
		PurchaseOrderPaymentModel::where('purchase_order_idx', $purchase_order_id)->delete();

		return 'Deleted';
		
	} 

	public function create_purchase_order_post(Request $request){

		$request->validate([
			'purchase_supplier_name'  	=> 'required',
			'product_idx'  	=> 'required'
        ], 
        [
			'purchase_supplier_name.required' 	=> "Supplier's Name is Required",
			'product_idx.required'  	=> 'Product is Required'
        ]
		);

			@$last_id = PurchaseOrderModel::latest()->first()->purchase_order_id;
			
			$Purchaseorder = new PurchaseOrderModel();
			$Purchaseorder->purchase_order_control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Purchaseorder->purchase_supplier_name 					= $request->purchase_supplier_name;
			
			$Purchaseorder->purchase_order_date 					= $request->purchase_order_date;
			$Purchaseorder->purchase_supplier_tin					= $request->purchase_supplier_tin;
			$Purchaseorder->purchase_supplier_name					= $request->purchase_supplier_name;
			$Purchaseorder->purchase_supplier_address				= $request->purchase_supplier_address;
					
			$Purchaseorder->purchase_order_sales_order_number		= $request->purchase_order_sales_order_number;
			$Purchaseorder->purchase_order_collection_receipt_no	= $request->purchase_order_collection_receipt_no;
			$Purchaseorder->purchase_order_official_receipt_no		= $request->purchase_order_official_receipt_no;
			$Purchaseorder->purchase_order_delivery_receipt_no		= $request->purchase_order_delivery_receipt_no;
				
			$Purchaseorder->purchase_order_delivery_method			= $request->purchase_order_delivery_method;
			$Purchaseorder->purchase_loading_terminal					= $request->purchase_loading_terminal;
			//$Purchaseorder->purchase_order_date_of_pickup			= $request->purchase_order_date_of_pickup;
			//$Purchaseorder->purchase_order_date_of_arrival			= $request->purchase_order_date_of_arrival;
					
			$Purchaseorder->purchase_order_net_percentage			= $request->purchase_order_net_percentage;
			$Purchaseorder->purchase_order_less_percentage			= $request->purchase_order_less_percentage;
			
			$Purchaseorder->hauler_operator							= $request->hauler_operator;
			$Purchaseorder->lorry_driver					= $request->lorry_driver;			
			
			$Purchaseorder->plate_number				= $request->plate_number;
			$Purchaseorder->contact_number				= $request->contact_number;
					
			$Purchaseorder->purchase_destination					= $request->purchase_destination;
			$Purchaseorder->purchase_destination_address			= $request->purchase_destination_address;
			$Purchaseorder->purchase_date_of_departure				= $request->purchase_date_of_departure;
			$Purchaseorder->purchase_date_of_arrival				= $request->purchase_date_of_arrival;
									
			$Purchaseorder->purchase_order_instructions				= $request->purchase_order_instructions;
			$Purchaseorder->purchase_order_note						= $request->purchase_order_note;
			
			
			$result = $Purchaseorder->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Purchaseorder->purchase_order_id;
			
			/*Payment Option*/
			$purchase_order_bank 			= $request->purchase_order_bank;
			$purchase_order_date_of_payment = $request->purchase_order_date_of_payment;
			$purchase_order_reference_no 	= $request->purchase_order_reference_no;
			$purchase_order_payment_amount 	= $request->purchase_order_payment_amount;
			
			for($count = 0; $count < count($purchase_order_bank); $count++)
			{
				
					$purchase_order_bank_item 				= $purchase_order_bank[$count];
					$purchase_order_date_of_payment_item 	= $purchase_order_date_of_payment[$count];
					$purchase_order_reference_no_item 		= $purchase_order_reference_no[$count];
					$purchase_order_payment_amount_item 	= $purchase_order_payment_amount[$count];
				
				/*Save to teves_sales_order_component_table(SalesOrderComponentModel)*/
				$PurchaseOrderPaymentComponent = new PurchaseOrderPaymentModel();
				
				$PurchaseOrderPaymentComponent->purchase_order_idx 		= $last_transaction_id;
				$PurchaseOrderPaymentComponent->purchase_order_bank 			= $purchase_order_bank_item;
				$PurchaseOrderPaymentComponent->purchase_order_date_of_payment 	= $purchase_order_date_of_payment_item;
				$PurchaseOrderPaymentComponent->purchase_order_reference_no 	= $purchase_order_reference_no_item;
				$PurchaseOrderPaymentComponent->purchase_order_payment_amount 	= $purchase_order_payment_amount_item;
				
				$PurchaseOrderPaymentComponent->save();
				
			}
			
			
			$product_idx 			= $request->product_idx;
			$order_quantity 		= $request->order_quantity;
			$product_manual_price 	= $request->product_manual_price;
			
			$gross_amount = 0;
			
			for($count = 0; $count < count($product_idx); $count++)
			{
				
					$puchase_order_item_product_id 			= $product_idx[$count];
					$puchase_order_item_order_quantity 		= $order_quantity[$count];
					$puchase_order_item_product_manual_price 	= $product_manual_price[$count];

				
				/*Product Details*/
				$product_info = ProductModel::find($puchase_order_item_product_id, ['product_price']);					
				
				/*Check if Price is From Manual Price*/
				if($puchase_order_item_product_manual_price!=0){
					$product_price = $puchase_order_item_product_manual_price;
				}else{
					$product_price = $product_info->product_price;
				}
				
				$order_total_amount = $puchase_order_item_order_quantity * $product_price;
				
				$gross_amount += $order_total_amount;
				
				/*Save to teves_sales_order_component_table(SalesOrderComponentModel)*/
				$PurchaseOrderComponent = new PurchaseOrderComponentModel();
				
				$PurchaseOrderComponent->purchase_order_idx 		= $last_transaction_id;
				$PurchaseOrderComponent->product_idx 				= $puchase_order_item_product_id;
				$PurchaseOrderComponent->order_quantity 			= $puchase_order_item_order_quantity;
				$PurchaseOrderComponent->product_price 				= $product_price;
				$PurchaseOrderComponent->order_total_amount 		= $order_total_amount;
				
				$PurchaseOrderComponent->save();
				
			 }

			$net_in_percentage 				= $request->purchase_order_net_percentage;/*1.12*/
			$less_in_percentage 			= $request->purchase_order_less_percentage/100;

					if($request->purchase_order_net_percentage==0){
						$purchase_order_net_amount 				= 0;
						$purchase_order_total_payable 			=  number_format($gross_amount,2,".","");
					}else{
						$purchase_order_net_amount 				=  number_format($gross_amount/$net_in_percentage,2,".","");
						$purchase_order_total_payable 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
					}
				
				$PurchaseOrderUpdate = new PurchaseOrderModel();
				$PurchaseOrderUpdate = PurchaseOrderModel::find($last_transaction_id);
				$PurchaseOrderUpdate->purchase_order_gross_amount = number_format($gross_amount,2,".","");
				$PurchaseOrderUpdate->purchase_order_net_amount = $purchase_order_net_amount;
				$PurchaseOrderUpdate->purchase_order_total_payable = $purchase_order_total_payable;
				$PurchaseOrderUpdate->update();

			if($result){
				return response()->json(array('success' => "Purchase Order Successfully Created!", 'purchase_order_id' => @$last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Purchase Order Information']);
			}
	}

	public function update_purchase_order_post(Request $request){

		$request->validate([
			'purchase_supplier_name'  	=> 'required',
			'product_idx'  	=> 'required'
        ], 
        [
			'purchase_supplier_name.required' 	=> "Supplier's Name is Required",
			'product_idx.required'  	=> 'Product is Required'
        ]
		);

			$Purchaseorder = new PurchaseOrderModel();
			$Purchaseorder = PurchaseOrderModel::find($request->purchase_order_id);
			$Purchaseorder->purchase_supplier_name 					= $request->purchase_supplier_name;
			
			$Purchaseorder->purchase_order_date 					= $request->purchase_order_date;
			$Purchaseorder->purchase_supplier_tin					= $request->purchase_supplier_tin;
			$Purchaseorder->purchase_supplier_name					= $request->purchase_supplier_name;
			$Purchaseorder->purchase_supplier_address				= $request->purchase_supplier_address;
					
			$Purchaseorder->purchase_order_sales_order_number		= $request->purchase_order_sales_order_number;
			$Purchaseorder->purchase_order_collection_receipt_no	= $request->purchase_order_collection_receipt_no;
			$Purchaseorder->purchase_order_official_receipt_no		= $request->purchase_order_official_receipt_no;
			$Purchaseorder->purchase_order_delivery_receipt_no		= $request->purchase_order_delivery_receipt_no;
				
			$Purchaseorder->purchase_order_delivery_method			= $request->purchase_order_delivery_method;
			$Purchaseorder->purchase_loading_terminal					= $request->purchase_loading_terminal;
			//$Purchaseorder->purchase_order_date_of_pickup			= $request->purchase_order_date_of_pickup;
			//$Purchaseorder->purchase_order_date_of_arrival			= $request->purchase_order_date_of_arrival;
					
			$Purchaseorder->purchase_order_net_percentage			= $request->purchase_order_net_percentage;
			$Purchaseorder->purchase_order_less_percentage			= $request->purchase_order_less_percentage;
			
			$Purchaseorder->hauler_operator							= $request->hauler_operator;
			$Purchaseorder->lorry_driver					= $request->lorry_driver;			
			
			$Purchaseorder->plate_number				= $request->plate_number;
			$Purchaseorder->contact_number				= $request->contact_number;
					
			$Purchaseorder->purchase_destination					= $request->purchase_destination;
			$Purchaseorder->purchase_destination_address			= $request->purchase_destination_address;
			$Purchaseorder->purchase_date_of_departure				= $request->purchase_date_of_departure;
			$Purchaseorder->purchase_date_of_arrival				= $request->purchase_date_of_arrival;
									
			$Purchaseorder->purchase_order_instructions				= $request->purchase_order_instructions;
			$Purchaseorder->purchase_order_note						= $request->purchase_order_note;
					
			$result = $Purchaseorder->update();
			
			$product_idx 						= $request->product_idx;
			$order_quantity 					= $request->order_quantity;
			$product_manual_price 				= $request->product_manual_price;
			$purchase_order_item_ids 			= $request->purchase_order_item_ids;
		
			/*Get Last ID*/
			$last_transaction_id = $Purchaseorder->purchase_order_id;
			
			/*Payment Option*/
			$purchase_order_bank 			= $request->purchase_order_bank;
			$purchase_order_date_of_payment = $request->purchase_order_date_of_payment;
			$purchase_order_reference_no 	= $request->purchase_order_reference_no;
			$purchase_order_payment_amount 	= $request->purchase_order_payment_amount;
			$purchase_order_payment_item_id 	= $request->purchase_order_payment_item_id;
			
			for($count = 0; $count < count($purchase_order_bank); $count++)
			{
				
					$purchase_order_payment_item_id_item 				= $purchase_order_payment_item_id[$count];
					$purchase_order_bank_item 				= $purchase_order_bank[$count];
					$purchase_order_date_of_payment_item 	= $purchase_order_date_of_payment[$count];
					$purchase_order_reference_no_item 		= $purchase_order_reference_no[$count];
					$purchase_order_payment_amount_item 	= $purchase_order_payment_amount[$count];
				
				if($purchase_order_payment_item_id_item==0){
						
					$PurchaseOrderPaymentComponent = new PurchaseOrderPaymentModel();
					
					$PurchaseOrderPaymentComponent->purchase_order_idx 				= $last_transaction_id;
					$PurchaseOrderPaymentComponent->purchase_order_bank 			= $purchase_order_bank_item;
					$PurchaseOrderPaymentComponent->purchase_order_date_of_payment 	= $purchase_order_date_of_payment_item;
					$PurchaseOrderPaymentComponent->purchase_order_reference_no 	= $purchase_order_reference_no_item;
					$PurchaseOrderPaymentComponent->purchase_order_payment_amount 	= $purchase_order_payment_amount_item;
					
					$PurchaseOrderPaymentComponent->save();
				
				}
				else{
					
					$PurchaseOrderPaymentComponent = new PurchaseOrderPaymentModel();
					
					$PurchaseOrderPaymentComponent = PurchaseOrderPaymentModel::find($purchase_order_payment_item_id_item);
					
					$PurchaseOrderPaymentComponent->purchase_order_bank 						= $purchase_order_bank_item;
					$PurchaseOrderPaymentComponent->purchase_order_date_of_payment 				= $purchase_order_date_of_payment_item;
					$PurchaseOrderPaymentComponent->purchase_order_reference_no 				= $purchase_order_reference_no_item;
					$PurchaseOrderPaymentComponent->purchase_order_payment_amount 				= $purchase_order_payment_amount_item;
					
					$PurchaseOrderPaymentComponent->update();

				}
				
			}
			
					
			$gross_amount = 0;
			
			for($count = 0; $count < count($product_idx); $count++)
			 {
				
					$puchase_order_item_product_id 				= $product_idx[$count];
					$puchase_order_item_order_quantity 			= $order_quantity[$count];
					$puchase_order_item_product_manual_price 	= $product_manual_price[$count];
					$purchase_order_item_id 					= $purchase_order_item_ids[$count];
				
				/*Product Details*/
				$product_info = ProductModel::find($puchase_order_item_product_id, ['product_price']);					
				
				/*Check if Price is From Manual Price*/
				if($puchase_order_item_product_manual_price!=0){
					$product_price = $puchase_order_item_product_manual_price;
				}else{
					$product_price = $product_info->product_price;
				}
				
				$order_total_amount = $puchase_order_item_order_quantity * $product_price;
				
				$gross_amount += $order_total_amount;
				
				if($purchase_order_item_id==0){
				
				$PurchaseOrderComponent = new PurchaseOrderComponentModel();
				
				$PurchaseOrderComponent->purchase_order_idx 		= $last_transaction_id;
				$PurchaseOrderComponent->product_idx 				= $puchase_order_item_product_id;
				$PurchaseOrderComponent->order_quantity 			= $puchase_order_item_order_quantity;
				$PurchaseOrderComponent->product_price 				= $product_price;
				$PurchaseOrderComponent->order_total_amount 		= $order_total_amount;
				
				$PurchaseOrderComponent->save();
				
				}else{
				
				/*Update*/
				
				$PurchaseOrderComponent = new PurchaseOrderComponentModel();
				
				$PurchaseOrderComponent = PurchaseOrderComponentModel::find($purchase_order_item_id);
				
				$PurchaseOrderComponent->product_idx 				= $puchase_order_item_product_id;
				$PurchaseOrderComponent->order_quantity 			= $puchase_order_item_order_quantity;
				$PurchaseOrderComponent->product_price 				= $product_price;
				$PurchaseOrderComponent->order_total_amount 		= $order_total_amount;
						
				$PurchaseOrderComponent->update();
			
				}
				
			 }
			
			$net_in_percentage 				= $request->purchase_order_net_percentage;/*1.12*/
				$less_in_percentage 			= $request->purchase_order_less_percentage/100;

					if($request->purchase_order_net_percentage==0){
						$purchase_order_net_amount 				= 0;
						$purchase_order_total_payable 			=  number_format($gross_amount,2,".","");
					}else{
						$purchase_order_net_amount 				=  number_format($gross_amount/$net_in_percentage,2,".","");
						$purchase_order_total_payable 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
					}
				
				$PurchaseOrderUpdate = new PurchaseOrderModel();
				$PurchaseOrderUpdate = PurchaseOrderModel::find($last_transaction_id);
				$PurchaseOrderUpdate->purchase_order_gross_amount = number_format($gross_amount,2,".","");
				$PurchaseOrderUpdate->purchase_order_net_amount = $purchase_order_net_amount;
				$PurchaseOrderUpdate->purchase_order_total_payable = $purchase_order_total_payable;
				$PurchaseOrderUpdate->update();
			
			if($result){
				return response()->json(array('success' => "Purchase Order Successfully Updated!", 'purchase_order_id' => @$last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Update Purchase Order Information']);
			}
	}
	
	public function get_purchase_order_product_list(Request $request){		
	
			$data = PurchaseOrderComponentModel::where('purchase_order_idx', $request->purchase_order_id)
					->orderBy('purchase_order_component_id', 'asc')
              		->get([
					'purchase_order_component_id',
					'product_idx',
					'product_price',
					'order_quantity']);
		
			return response()->json($data);
	}
	
	public function get_purchase_order_payment_list(Request $request){		
	
			$data =  PurchaseOrderPaymentModel::where('teves_purchase_order_payment_details.purchase_order_idx', $request->purchase_order_id)
				->orderBy('purchase_order_payment_details_id', 'asc')
              	->get([
					'teves_purchase_order_payment_details.purchase_order_payment_details_id',
					'teves_purchase_order_payment_details.purchase_order_bank',
					'teves_purchase_order_payment_details.purchase_order_date_of_payment',
					'teves_purchase_order_payment_details.purchase_order_reference_no',
					'teves_purchase_order_payment_details.purchase_order_payment_amount',
					]);
		
			return response()->json($data);
	}

	public function delete_purchase_order_item(Request $request){		
			
		$productitemID = $request->productitemID;
		PurchaseOrderComponentModel::find($productitemID)->delete();
		return 'Deleted';
		
	}
	
	public function delete_purchase_order_payment_item(Request $request){		
			
		$paymentitemID = $request->paymentitemID;
		PurchaseOrderPaymentModel::find($paymentitemID)->delete();
		return 'Deleted';
		
	}
	
	public function update_purchase_status(Request $request){		
			
		$purchase_order_id = $request->purchase_order_id;
		$purchase_status = $request->purchase_status;
				
				$PurchaseOrderUpdate = new PurchaseOrderModel();
				$PurchaseOrderUpdate = PurchaseOrderModel::find($purchase_order_id);
				$PurchaseOrderUpdate->purchase_status = $purchase_status;
				$PurchaseOrderUpdate->update();
		
	}
}
