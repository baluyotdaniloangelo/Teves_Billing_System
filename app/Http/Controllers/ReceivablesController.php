<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ReceivablesModel;
use App\Models\BillingTransactionModel;
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

    	$data = ReceivablesModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable.client_idx')
              		->get([
					'teves_receivable.receivable_id',
					'teves_receivable.billing_date',
					'teves_client_table.client_name',
					'teves_receivable.control_number',
					'teves_receivable.or_number',				
					'teves_receivable.payment_term',
					'teves_receivable.receivable_description',
					'teves_receivable.receivable_amount']);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->receivable_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editReceivables"></a>
					<a href="#" data-id="'.$row->receivable_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteReceivables"></a>
					</div>';
                    return $actionBtn;
                })
				->rawColumns(['action'])
                ->make(true);
		}		
    }

	/*Fetch Product Information*/
	public function receivables_info(Request $request){

					$receivable_id = $request->receivable_id;
					$data = ReceivablesModel::where('receivable_id', $request->receivable_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable.client_idx')
              		->get([
					'teves_receivable.receivable_id',
					'teves_receivable.billing_date',
					'teves_client_table.client_name',
					'teves_receivable.control_number',
					'teves_client_table.client_tin',
					'teves_receivable.or_number',				
					'teves_receivable.payment_term',
					'teves_receivable.receivable_description',
					'teves_receivable.receivable_amount']);
					return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_receivables_confirmed(Request $request){

		$receivableID = $request->receivable_id;
		ReceivablesModel::find($receivableID)->delete();
		return 'Deleted';
		
	} 

	public function create_receivables_post(Request $request){

		$request->validate([
			'or_number'      			=> 'required',
			'payment_term'      		=> 'required',
			'receivable_description'  	=> 'required'
        ], 
        [
			'or_number.required' 				=> 'O.R Number is required',
			'payment_term.required' 			=> 'Payment Term is Required',
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

			$Receivables = new ReceivablesModel();
			$Receivables->client_idx 				= $request->client_idx;
			$Receivables->control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Receivables->billing_date 				= date('Y-m-d');
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_amount 		= $receivable_amount;
			
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
          'product_name'      		=> 'required|unique:teves_product_table,product_name,'.$request->productID.',product_id',
		  'product_price'      		=> 'required'
        ], 
        [
			'product_name.required' => 'Product Name is required',
			'product_price.required' => 'Price is Required'
        ]
		);
		
		$request->validate([
			'tin_number'      			=> 'required',
			'or_number'      			=> 'required',
			'payment_term'      		=> 'required',
			'receivable_description'  	=> 'required'
        ], 
        [
			'tin_number.required' 				=> 'TIN Number is required',
			'or_number.required' 				=> 'O.R Number is required',
			'payment_term.required' 			=> 'Payment Term is Required',
			'receivable_description.required' 	=> 'Description is Required'
        ]
		);
		
			//@$last_id = ReceivablesModel::latest()->first()->receivable_id;

			$client_idx = $request->client_idx;
			$start_date = $request->start_date;
			$end_date = $request->end_date;
		
			$receivable_amount = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');

			$Receivables = new ReceivablesModel();
			$Receivables->client_idx 				= $request->client_idx;
			//$Receivables->control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Receivables->billing_date 				= date('Y-m-d');
			$Receivables->tin_number 				= $request->tin_number;
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_amount 		= $receivable_amount;
			
			$result = $Receivables->update();
			
			if($result){
				return response()->json(['success'=>'Receivables Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Receivables Information']);
			}
	}
}
