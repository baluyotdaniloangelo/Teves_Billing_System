<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReceivablesModel;
use App\Models\SalesOrderModel;
use App\Models\BillingTransactionModel;
use App\Models\ReceivablesPaymentModel;
use App\Models\ClientModel;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;

class ReceivablesController extends Controller
{
	
	/*Load Product Interface*/
	public function receivables(){
		
		if(Session::has('loginID')){
			
			$title = 'Receivable';
			$data = array();
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$teves_branch = TevesBranchModel::all();
			
			return view("pages.receivables", compact('data','title','teves_branch'));
			
		}
		
	}   

	/*Load Report Interface*/
	public function create_recievable(){
		
		
		if(Session::has('loginID')){
			
			$title = 'Create Receivable';
			$data = array();
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$client_data = ClientModel::all();
			
			$product_data = ProductModel::all();
			$teves_branch = TevesBranchModel::all();
			
			$drivers_name = BillingTransactionModel::select('drivers_name')->distinct()->get();
			$plate_no = BillingTransactionModel::select('plate_no')->distinct()->get();
		
			return view("pages.create_recievable", compact('data','title','client_data','drivers_name','plate_no','product_data','teves_branch'));
		
		}
	
	}   
	
	/*Fetch Receivables List using Datatable*/
	/*Recievable from Billing*/
	public function getReceivablesList_billing(Request $request)
    {
		
		if(Session::has('loginID')){
			
			$list = ReceivablesModel::get();
			if ($request->ajax()) {

			$data = ReceivablesModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
						->WHERE('teves_receivable_table.sales_order_idx', '=', '0')
						->get([
						'teves_receivable_table.receivable_id',
						'teves_receivable_table.sales_order_idx',
						'teves_receivable_table.billing_date',
						'teves_client_table.client_name',
						'teves_receivable_table.control_number',		
						'teves_receivable_table.receivable_description',
						'teves_receivable_table.receivable_gross_amount',
						'teves_receivable_table.receivable_vatable_sales',
						'teves_receivable_table.receivable_vat_amount',
						'teves_receivable_table.receivable_withholding_tax',
						'teves_receivable_table.receivable_amount',
						'teves_receivable_table.receivable_remaining_balance',
						'teves_receivable_table.receivable_status']);
											
					return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){	
					
						if($row->sales_order_idx==0){
							$menu_for_update = 'editReceivables';
						}else{
							$menu_for_update = 'editReceivablesFromSalesOrder';
						}
					
							$actionBtn = '<div align="center" class="action_table_menu_Product">
										<!--<a href="#" class="btn-circle btn-sm bi bi-images btn_icon_table btn_icon_table_gallery" onclick="ViewGalery()" id="viewPaymentGalery"></a>-->
										<a href="#" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle bi bi-cash-stack btn_icon_table btn_icon_table_view" id="payReceivables" title="Add Payment"></a>
										<a href="#" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="'.$menu_for_update.'" title="Update"></a>
										<a href="#" data-id="'.$row->receivable_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteReceivables" title="Delete"></a>
									</div>';
					
							if($row->receivable_status == 'Paid' && Session::get('UserType')!="Admin"){
										return '';
							}else{
										return $actionBtn;
							}
						
					})
					
					->addColumn('action_print', function($row){
						
						if($row->sales_order_idx==0){
							$print_sales_or_billing = '<option value="PrintBilling">Billing</option>';
						}else{
							$print_sales_or_billing = '<option value="PrintSalesOrder">Sales Order</option>';
						}
						
						$action_print = '
						<div align="center" class="action_table_menu_Product">
						<select class="receivable_print_'.$row->receivable_id.'" name="receivable_print_'.$row->receivable_id.'" id="receivable_print_'.$row->receivable_id.'" onchange="receivable_print('.$row->receivable_id.')">	
							<option disabled="" selected value="">Choose...</option>
							<option value="PrintStatement" title="Statement of Account">SOA</option>
							'.$print_sales_or_billing.'
							<option value="PrintReceivables">Receivable</option>
							</select>
						</div>';
						return $action_print;
					})
					
					
					->rawColumns(['action','action_print'])
					->make(true);
			}	
		}			
    }
	/*Recievable from Billing*/
	public function getReceivablesList_sales_order(Request $request)
    {
		
		if(Session::has('loginID')){
			
			$list = ReceivablesModel::get();
			if ($request->ajax()) {

			$data = ReceivablesModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
						->WHERE('teves_receivable_table.sales_order_idx', '<>', '0')
						->get([
						'teves_receivable_table.receivable_id',
						'teves_receivable_table.sales_order_idx',
						'teves_receivable_table.billing_date',
						'teves_client_table.client_name',
						'teves_receivable_table.control_number',		
						'teves_receivable_table.receivable_description',
						'teves_receivable_table.receivable_gross_amount',
						'teves_receivable_table.receivable_vatable_sales',
						'teves_receivable_table.receivable_vat_amount',
						'teves_receivable_table.receivable_withholding_tax',
						'teves_receivable_table.receivable_amount',
						'teves_receivable_table.receivable_remaining_balance',
						'teves_receivable_table.receivable_status']);
											
					return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){	
				
							$menu_for_update = 'editReceivablesFromSalesOrder';

							$actionBtn = '<div align="center" class="action_table_menu_Product">
										<a href="#" class="btn-circle btn-sm bi bi-images btn_icon_table btn_icon_table_gallery" onclick="ViewGalery('.$row->receivable_id.')" id="viewPaymentGalery"></a>
										<a href="sales_order_form?sales_order_id='.$row->sales_order_idx.'&tab=payment" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle bi bi-cash-stack btn_icon_table btn_icon_table_view" title="Add Payment"></a>
										<a href="sales_order_form?sales_order_id='.$row->sales_order_idx.'&tab=receivable" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" title="Update"></a>
										<a href="#" data-id="'.$row->receivable_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteReceivables" title="Delete"></a>
									</div>';
					
							if($row->receivable_status == 'Paid' && Session::get('UserType')!="Admin"){
										return '';
							}else{
										return $actionBtn;
							}
						
					})
					
					->addColumn('action_print', function($row){
						
						if($row->sales_order_idx==0){
							$print_sales_or_billing = '<option value="PrintBilling">Billing</option>';
						}else{
							$print_sales_or_billing = '<option value="PrintSalesOrder">Sales Order</option>';
						}
						
						$action_print = '
						<div align="center" class="action_table_menu_Product">
						<select class="receivable_print_'.$row->receivable_id.'" name="receivable_print_'.$row->receivable_id.'" id="receivable_print_'.$row->receivable_id.'" onchange="receivable_print('.$row->receivable_id.')">	
							<option disabled="" selected value="">Choose...</option>
							<option value="PrintStatement" title="Statement of Account">SOA</option>
							'.$print_sales_or_billing.'
							<option value="PrintReceivables">Receivable</option>
							</select>
						</div>';
						return $action_print;
					})
					
					
					->rawColumns(['action','action_print'])
					->make(true);
			}	
		}			
    }

	/*Payment List*/
	public function get_receivable_payment_list(Request $request){		
	
		if(Session::has('loginID')){
			
				$data =  ReceivablesPaymentModel::where('teves_receivable_payment.receivable_idx', $request->receivable_id)
					->orderBy('receivable_payment_id', 'asc')
					->get([
						'teves_receivable_payment.receivable_payment_id',
						'teves_receivable_payment.receivable_date_of_payment',
						'teves_receivable_payment.receivable_mode_of_payment',
						'teves_receivable_payment.receivable_reference',
						'teves_receivable_payment.receivable_payment_amount',
						'teves_receivable_payment.image_reference',
						]);
			
				return response()->json($data);
				
		}
			
	}
	
	/*Delete Payment Item*/
	public function delete_receivable_payment_item(Request $request){		
		
		if(Session::has('loginID')){
			
			if(Session::get('UserType')=="Admin"){
				
				$paymentitemID = $request->paymentitemID;
				$receivableID = $request->receivable_id;
			
				ReceivablesPaymentModel::find($paymentitemID)->delete();
			
				/*Remaining Balance*/
				/*Get Receivable Details*/
				$receivable_details = ReceivablesModel::find($receivableID, ['receivable_amount']);							
				$receivable_amount = $receivable_details->receivable_amount;
				
				/*Get Receivable Payment Details*/
				$receivable_payment_amount =  ReceivablesPaymentModel::where('teves_receivable_payment.receivable_idx', $receivableID)
					->sum('receivable_payment_amount');
					
				$remaining_balance = number_format($receivable_amount - $receivable_payment_amount+0,2, '.', '');;
			
				/*IF Fully Paid Automatically Update the Status to Paid*/
				if($remaining_balance <= 0)
				{
					$receivable_status = 'Paid';
				}else{
					$receivable_status = 'Pending';
				}
				
				/*Update Recievable Table*/
				$Receivables_update = new ReceivablesModel();
				$Receivables_update = ReceivablesModel::find($receivableID);
				
				$Receivables_update->receivable_remaining_balance 	= $remaining_balance;
				$Receivables_update->receivable_status 				= $receivable_status;
				
				$result_update = $Receivables_update->update();

				return 'Deleted';
				
			}
			
		}
	}
	
	/*Save Payment Item*/
	public function save_receivable_payment_post(Request $request){		
			
			$request->validate([
			'payment_amount'  	=> 'required'
			], 
			[
				'payment_amount.required' 	=> 'Payment Account is Required'
			]
			);
			
			/*Payment Option*/
			$receivable_id 				= $request->receivable_id;
			$payment_id 				= $request->payment_id;
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
						$payment_amount_item 	= number_format($payment_amount[$count],2, '.', '');
						$payment_id_item 		= $payment_id[$count];
				
					if($payment_id_item==0){
							
						$ReceivablePaymentComponent = new ReceivablesPaymentModel();
						
						$ReceivablePaymentComponent->receivable_idx 				= $receivable_id;
						$ReceivablePaymentComponent->receivable_mode_of_payment 	= $mode_of_payment_item;
						$ReceivablePaymentComponent->receivable_date_of_payment 	= $date_of_payment_item;
						$ReceivablePaymentComponent->receivable_reference 			= $reference_no_item;
						$ReceivablePaymentComponent->receivable_payment_amount 		= $payment_amount_item;
						
						$ReceivablePaymentComponent->save();
						
					}else{
						
						if(Session::get('UserType')=="Admin"){
							
							$ReceivablePaymentComponent = new ReceivablesPaymentModel();
							
							$ReceivablePaymentComponent = ReceivablesPaymentModel::find($payment_id_item);
							
							$ReceivablePaymentComponent->receivable_mode_of_payment 	= $mode_of_payment_item;
							$ReceivablePaymentComponent->receivable_date_of_payment 	= $date_of_payment_item;
							$ReceivablePaymentComponent->receivable_reference 			= $reference_no_item;
							$ReceivablePaymentComponent->receivable_payment_amount 		= $payment_amount_item;
							
							$ReceivablePaymentComponent->update();
							
						}
						
					}
				}		
				
			/*Remaining Balance*/
			/*Get Receivable Details*/
			$receivable_details = ReceivablesModel::find($receivable_id, ['receivable_amount']);							
			$receivable_amount = $receivable_details->receivable_amount;
			
			/*Get Receivable Payment Details*/
			$receivable_payment_amount =  ReceivablesPaymentModel::where('teves_receivable_payment.receivable_idx', $receivable_id)
              	->sum('receivable_payment_amount');
			
			$remaining_balance = number_format($receivable_amount - $receivable_payment_amount,2, '.', '');
			
			/*IF Fully Paid Automatically Update the Status to Paid*/
			if($remaining_balance <= 0)
			{
				$receivable_status = 'Paid';
			}else{
				$receivable_status = 'Pending';
			}
			
			/*Update Recievable Table*/
			$Receivables_update = new ReceivablesModel();
			$Receivables_update = ReceivablesModel::find($receivable_id);
			
			$Receivables_update->receivable_remaining_balance 	= $remaining_balance;
			$Receivables_update->receivable_status 				= $receivable_status;
			
			$result_update = $Receivables_update->update();
			
			return response()->json(array('success' => "Receivable Payment Successfully Updated!"), 200);
			
			}
							
	}

	public function create_receivables_from_sale_order_post(Request $request){		

		$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);
			
			/*
			if need to use where
			$last_id = ReceivablesModel::where('control_number', '<>', '')->latest()->first()->receivable_id;
			*/
			
			$last_id = ReceivablesModel::latest()->first()->receivable_id;
			
			/*GET SALES ORDER*/
			$sales_order_idx = $request->sales_order_idx;
			
					$SalesOrderData = SalesOrderModel::where('sales_order_id', $sales_order_idx)
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_term']);	
			
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $SalesOrderData[0]->company_header)->first();				
			$control_number = $BranchInfo->branch_initial."-AR-".$last_id+1;			
			
			/*Save to Receivables*/
			$Receivables = new ReceivablesModel();
			$Receivables->client_idx 					= $SalesOrderData[0]->sales_order_client_idx;
			$Receivables->control_number 				= $control_number;
			$Receivables->sales_order_idx 				= $request->sales_order_idx;
			$Receivables->billing_date 					= $request->billing_date;
			$Receivables->payment_term 					= $SalesOrderData[0]->sales_order_payment_term;
			$Receivables->receivable_description 		= $request->receivable_description;
			$Receivables->receivable_status 			= 'Pending';			
			$Receivables->receivable_amount 			= $SalesOrderData[0]->sales_order_total_due;
			$Receivables->receivable_remaining_balance 	= $SalesOrderData[0]->sales_order_total_due;
			$Receivables->company_header 				= $SalesOrderData[0]->company_header;
			$Receivables->created_by_user_id 			= Session::get('loginID');
			$result = $Receivables->save();
			
			/*Update Sales Order to Delivered*/
			$salesOrderUpdate = new SalesOrderModel();
			$salesOrderUpdate = SalesOrderModel::find($sales_order_idx);
			$salesOrderUpdate->sales_order_status = 'Delivered';
			$salesOrderUpdate->update();

			if($result){
				return response()->json(array('success' => true, 'receivable_id' => $Receivables->receivable_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Receivables Information']);
			}
	}
	
	public function update_receivables_from_sale_order_post(Request $request){		

		$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);

			$ReceivableID = $request->ReceivableID;
			/*Update to Receivables*/
			$Receivables = new ReceivablesModel();
			$Receivables = ReceivablesModel::find($ReceivableID);
			$Receivables->billing_date 					= $request->billing_date;
			$Receivables->payment_term 					= $request->payment_term;
			$Receivables->receivable_description 		= $request->receivable_description;
			$Receivables->updated_by_user_id 			= Session::get('loginID');
			$result = $Receivables->update();

			if($result){
				return response()->json(array('success' => 'Receivable Information Successfully Updated!', 'receivable_id' => $request->ReceivableID), 200);
			}
			else{
				return response()->json(['success'=>'Error on Update Receivables Information']);
			}
	}
		
	/*Fetch Receivable Information*/
	public function receivable_info(Request $request){

					$receivable_id = $request->receivable_id;
					$data = ReceivablesModel::where('receivable_id', $request->receivable_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
              		->get([
					'teves_receivable_table.receivable_id',
					'teves_receivable_table.sales_order_idx',
					'teves_receivable_table.billing_date',
					'teves_client_table.client_address',
					'teves_client_table.client_name',
					'teves_client_table.client_id',
					'teves_receivable_table.control_number',
					'teves_client_table.client_tin',
					'teves_receivable_table.or_number',
					'teves_receivable_table.payment_term',
					'teves_receivable_table.receivable_description',
					'teves_receivable_table.receivable_amount',
					'teves_receivable_table.receivable_status',
					'teves_receivable_table.billing_period_start',
					'teves_receivable_table.billing_period_end',
					'teves_receivable_table.less_per_liter',
					'teves_receivable_table.receivable_net_value_percentage',
					'teves_receivable_table.receivable_vat_value_percentage',
					'teves_receivable_table.receivable_withholding_tax_percentage',
					'teves_receivable_table.company_header']);
					return response()->json($data);
		
	}

	/*Delete Receivable Information*/
	public function delete_receivable_confirmed(Request $request){

		$receivableID = $request->receivable_id;
		ReceivablesModel::find($receivableID)->delete();
		
		/*Delete Payment*/	
		ReceivablesPaymentModel::where('receivable_idx', $receivableID)->delete();
		
		$billing_update = BillingTransactionModel::where('receivable_idx', '=', $receivableID)
				->update(['receivable_idx' => 0]);
		
		return 'Deleted';
		
	} 

	public function create_receivables_post(Request $request){

		$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);

			@$last_id = ReceivablesModel::latest()->first()->receivable_id;

			$client_idx = $request->client_idx;
			$start_date = $request->start_date;
			$end_date = $request->end_date;
			
			$less_per_liter = $request->less_per_liter;
			$withholding_tax_percentage = $request->withholding_tax_percentage;
			$net_value_percentage = $request->net_value_percentage;
			$vat_value_percentage = $request->vat_value_percentage;
		
			$receivable_amount = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->where('receivable_idx', '=', 0)
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');
					
			$receivable_total_liter = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
                ->where('teves_product_table.product_unit_measurement', '=', 'L')
				->where('receivable_idx', '=', 0)
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
				->groupBy('teves_billing_table.client_idx')
				->groupBy('teves_product_table.product_unit_measurement')
				->sum('order_quantity');

			$gross_amount = $receivable_amount;
			$vatable_sales = $gross_amount / $net_value_percentage;
			$vatable_amount = ($gross_amount / $net_value_percentage) * $vat_value_percentage;
			$withholding_tax = $vatable_sales * $withholding_tax_percentage;
			
			$total_amount_due = $receivable_amount - (($receivable_total_liter*$less_per_liter) + ($withholding_tax));
					
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $request->company_header)->first();				
			$control_number = $BranchInfo->branch_initial."-AR-".$last_id+1;					
					
			$Receivables = new ReceivablesModel();
			$Receivables->client_idx 				= $request->client_idx;
			$Receivables->control_number 			= $control_number;
			$Receivables->billing_date 				= date('Y-m-d');
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			
			$Receivables->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');			
			$Receivables->receivable_vatable_sales 		= number_format($vatable_sales,2, '.', '');
			$Receivables->receivable_vat_amount 		= number_format($vatable_amount,2, '.', '');
			$Receivables->receivable_withholding_tax 	= number_format($withholding_tax,2, '.', '');			
			$Receivables->receivable_amount 			= number_format($total_amount_due,2, '.', '');
			$Receivables->receivable_remaining_balance 	= number_format($total_amount_due,2, '.', '');
			
			$Receivables->receivable_net_value_percentage 			=  number_format($net_value_percentage,2, '.', '');
			$Receivables->receivable_withholding_tax_percentage 	=  number_format($withholding_tax_percentage * 100,2, '.', '');
			$Receivables->receivable_vat_value_percentage 			=  number_format($vat_value_percentage * 100,2, '.', '');
					
			$Receivables->receivable_status 			= 'Pending';		
			$Receivables->billing_period_start 			= $start_date;
			$Receivables->billing_period_end 			= $end_date;
			
			$Receivables->less_per_liter 		= $request->less_per_liter;
			$Receivables->company_header 		= $request->company_header;
			$Receivables->created_by_user_id 			= Session::get('loginID');
			$result = $Receivables->save();
			
			/*Update Billing List Affected by the Receivable*/

			$billing_update = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->where('receivable_idx', '=', 0)
				->update(['receivable_idx' => $Receivables->receivable_id]);
			
			if($result){
				return response()->json(array('success' => true, 'receivable_id' => $Receivables->receivable_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Receivables Information']);
			}
	}

	public function update_receivables_post(Request $request){		
		
	$request->validate([
			'receivable_description'  	=> 'required'
        ], 
        [
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);
			
			/*Get Client ID from Receivable*/
			$client_idx = ReceivablesModel::where('receivable_id', $request->ReceivableID)
			  		->get([
					'teves_receivable_table.client_idx']);		

			$start_date = $request->start_date;
			$end_date = $request->end_date;
		
			$receivable_amount = BillingTransactionModel::where('client_idx', $client_idx[0]['client_idx'])
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->where('receivable_idx', '=', $request->ReceivableID)
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');
					
			$receivable_total_liter = BillingTransactionModel::where('client_idx', $client_idx[0]['client_idx'])
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->where('receivable_idx', '=', $request->ReceivableID)
                ->where('teves_product_table.product_unit_measurement', '=', 'L')
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
				->groupBy('teves_billing_table.client_idx')
				->groupBy('teves_product_table.product_unit_measurement')
				->sum('order_quantity');
			
			$less_per_liter = $request->less_per_liter;
			
			/*Sum Rendered Payment*/
			$_receivable_total_payment_amount = ReceivablesPaymentModel::where('receivable_idx', $request->ReceivableID)
				->sum('receivable_payment_amount');
			
			$withholding_tax_percentage = $request->withholding_tax_percentage;
			$net_value_percentage = $request->net_value_percentage;
			$vat_value_percentage = $request->vat_value_percentage;
			
			$gross_amount = $receivable_amount;
			$vatable_sales = $gross_amount / $net_value_percentage;
			$vatable_amount = ($gross_amount / $net_value_percentage) * $vat_value_percentage;
			$withholding_tax = $vatable_sales * $withholding_tax_percentage;
			
			$total_amount_due = $receivable_amount - (($receivable_total_liter*$less_per_liter) + ($withholding_tax));
		
			if($_receivable_total_payment_amount==0){
				$receivable_total_payment_amount = $total_amount_due;
			}else{
				$receivable_total_payment_amount = $total_amount_due - $_receivable_total_payment_amount;
			}
		
			$Receivables = new ReceivablesModel();
			$Receivables = ReceivablesModel::find($request->ReceivableID);
			$Receivables->billing_date 					= $request->billing_date;
			$Receivables->payment_term 					= $request->payment_term;
			$Receivables->receivable_description 		= $request->receivable_description;
			
			$Receivables->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');			
			$Receivables->receivable_vatable_sales 		= number_format($vatable_sales,2, '.', '');
			$Receivables->receivable_vat_amount 		= number_format($vatable_amount,2, '.', '');
			$Receivables->receivable_withholding_tax 	= number_format($withholding_tax,2, '.', '');			
			$Receivables->receivable_amount 			= number_format($total_amount_due,2, '.', '');
			
			$Receivables->receivable_remaining_balance 	= number_format($receivable_total_payment_amount,2, '.', '');
			
			$Receivables->receivable_net_value_percentage 			= number_format($net_value_percentage,2, '.', '');
			$Receivables->receivable_withholding_tax_percentage 	= number_format($withholding_tax_percentage * 100,2, '.', '');
			$Receivables->receivable_vat_value_percentage 			= number_format($vat_value_percentage * 100,2, '.', '');			
			
			$Receivables->billing_period_start 			= $request->start_date;
			$Receivables->billing_period_end 			= $request->end_date;
			
			$Receivables->less_per_liter 				= $request->less_per_liter;
			$Receivables->company_header 				= $request->company_header;
			
			$result = $Receivables->update();
			
			if($result){
				return response()->json(['success'=>'Receivables Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Receivables Information']);
			}
	}
}
