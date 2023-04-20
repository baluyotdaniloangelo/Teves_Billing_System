<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReceivablesModel;
use App\Models\BillingTransactionModel;
use App\Models\ReceivablesPaymentModel;
use Session;
use Validator;
use DataTables;

class ReceivablesController extends Controller
{
	
	/*Load Product Interface*/
	public function receivables(){
		
		$title = 'Receivable';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		}

		return view("pages.receivables", compact('data','title'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getReceivablesList(Request $request)
    {
		$list = ReceivablesModel::get();
		if ($request->ajax()) {

    	$data = ReceivablesModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
              		->get([
					'teves_receivable_table.receivable_id',
					'teves_receivable_table.billing_date',
					'teves_client_table.client_name',
					'teves_receivable_table.control_number',		
					'teves_receivable_table.receivable_description',
					'teves_receivable_table.receivable_amount',
					'teves_receivable_table.receivable_status']);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){	
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle btn-sm bi bi-subtract btn_icon_table btn_icon_table_view" id="payReceivables"></a>
					<a href="#" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editReceivables"></a>
					<a href="#" data-id="'.$row->receivable_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteReceivables"></a>
					</div>';
                    return $actionBtn;
                })
				
				->addColumn('action_print', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<select class="receivable_print_'.$row->receivable_id.'" name="receivable_print_'.$row->receivable_id.'" id="receivable_print_'.$row->receivable_id.'" onchange="receivable_print('.$row->receivable_id.')">	
						<option disabled="" selected value="">Choose...</option>
						<option value="PrintStatement" title="Statement of Account">SOA</option>
						<option value="PrintBilling">Billing</option>
						<option value="PrintReceivables">Receivable</option>
						</select>
					</div>';
                    return $actionBtn;
                })
				
				
				->rawColumns(['action','action_print'])
                ->make(true);
		}		
    }

	public function get_receivable_payment_list(Request $request){		
	
			$data =  ReceivablesPaymentModel::where('teves_receivable_payment.receivable_idx', $request->receivable_id)
				->orderBy('receivable_payment_id', 'asc')
              	->get([
					'teves_receivable_payment.receivable_payment_id',
					'teves_receivable_payment.receivable_date_of_payment',
					'teves_receivable_payment.receivable_mode_of_payment',
					'teves_receivable_payment.receivable_reference',
					'teves_receivable_payment.receivable_payment_amount',
					]);
		
			return response()->json($data);
			
	}
	
	public function delete_receivable_payment_item(Request $request){		
			
		$paymentitemID = $request->paymentitemID;
		ReceivablesPaymentModel::find($paymentitemID)->delete();
		return 'Deleted';
		
	}
	
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
						$payment_amount_item 	= $payment_amount[$count];
						$payment_id_item 		= $payment_id[$count];
				//echo "payment_id_item";
					if($payment_id_item==0){
							
						$ReceivablePaymentComponent = new ReceivablesPaymentModel();
						
						$ReceivablePaymentComponent->receivable_idx 				= $receivable_id;
						$ReceivablePaymentComponent->receivable_mode_of_payment 	= $mode_of_payment_item;
						$ReceivablePaymentComponent->receivable_date_of_payment 	= $date_of_payment_item;
						$ReceivablePaymentComponent->receivable_reference 			= $reference_no_item;
						$ReceivablePaymentComponent->receivable_payment_amount 		= $payment_amount_item;
						
						$ReceivablePaymentComponent->save();
						//echo "ffff";
						
					}else{
						
						$ReceivablePaymentComponent = new ReceivablesPaymentModel();
						
						$ReceivablePaymentComponent = ReceivablesPaymentModel::find($payment_id_item);
						
						$ReceivablePaymentComponent->receivable_mode_of_payment 	= $mode_of_payment_item;
						$ReceivablePaymentComponent->receivable_date_of_payment 	= $date_of_payment_item;
						$ReceivablePaymentComponent->receivable_reference 			= $reference_no_item;
						$ReceivablePaymentComponent->receivable_payment_amount 		= $payment_amount_item;
						
						$ReceivablePaymentComponent->update();
	
					}
				}		
				return response()->json(array('success' => "Receivable Payment Successfully Updated!"), 200);
			}
		
	}
	
	/*Fetch Product Information*/
	public function receivable_info(Request $request){

					$receivable_id = $request->receivable_id;
					$data = ReceivablesModel::where('receivable_id', $request->receivable_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
              		->get([
					'teves_receivable_table.receivable_id',
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
					'teves_receivable_table.less_per_liter']);
					return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_receivable_confirmed(Request $request){

		$receivableID = $request->receivable_id;
		ReceivablesModel::find($receivableID)->delete();
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
		
			$receivable_amount = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');
					
			$receivable_total_liter = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
                ->where('teves_product_table.product_unit_measurement', '=', 'L')
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
				->groupBy('teves_billing_table.client_idx')
				->groupBy('teves_product_table.product_unit_measurement')
				->sum('order_quantity');

			$Receivables = new ReceivablesModel();
			$Receivables->client_idx 				= $request->client_idx;
			$Receivables->control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Receivables->billing_date 				= date('Y-m-d');
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_amount 		= $receivable_amount-($receivable_total_liter*$request->less_per_liter);
			$Receivables->receivable_status 		= $request->receivable_status;
			
			$Receivables->billing_period_start 		= $request->start_date;
			$Receivables->billing_period_end 		= $request->end_date;
			
			$Receivables->less_per_liter 		= $request->less_per_liter;
			
			$result = $Receivables->save();
			
			if($result){
				//return response()->json(['success'=>'Receivables Information Successfully Created!']);
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
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');
					
			$receivable_total_liter = BillingTransactionModel::where('client_idx', $client_idx[0]['client_idx'])
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
                ->where('teves_product_table.product_unit_measurement', '=', 'L')
				->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
				->groupBy('teves_billing_table.client_idx')
				->groupBy('teves_product_table.product_unit_measurement')
				->sum('order_quantity');
				
			//echo "$receivable_amount $receivable_total_liter";
				
			$Receivables = new ReceivablesModel();
			$Receivables = ReceivablesModel::find($request->ReceivableID);
			$Receivables->billing_date 				= $request->billing_date;
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_status 		= $request->receivable_status;
			$Receivables->receivable_amount 		= $receivable_amount-($receivable_total_liter*$request->less_per_liter);
			$Receivables->billing_period_start 		= $request->start_date;
			$Receivables->billing_period_end 		= $request->end_date;
			
			$Receivables->less_per_liter 			= $request->less_per_liter;
			
			$result = $Receivables->update();
			
			if($result){
				return response()->json(['success'=>'Receivables Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Receivables Information']);
			}
	}
}
