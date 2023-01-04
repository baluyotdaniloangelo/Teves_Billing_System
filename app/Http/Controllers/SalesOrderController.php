<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
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
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		}

		return view("pages.salesorder", compact('data','title','client_data'));
		
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
	public function receivable_info(Request $request){

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
	public function delete_receivable_confirmed(Request $request){

		$sales_order_id = $request->sales_order_id;
		SalesOrderModel::find($sales_order_id)->delete();
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

			@$last_id = SalesOrderModel::latest()->first()->sales_order_id;

			$client_idx = $request->client_idx;
			$start_date = $request->start_date;
			$end_date = $request->end_date;
		
			$receivable_amount = BillingTransactionModel::where('client_idx', $client_idx)
				->where('order_date', '>=', $start_date)
                ->where('order_date', '<=', $end_date)
				->groupBy('teves_billing_table.client_idx')
				->sum('order_total_amount');

			$Receivables = new SalesOrderModel();
			$Receivables->client_idx 				= $request->client_idx;
			$Receivables->control_number 			= str_pad(($last_id + 1), 8, "0", STR_PAD_LEFT);
			$Receivables->billing_date 				= date('Y-m-d');
			$Receivables->or_number 				= $request->or_number;
			$Receivables->payment_term 				= $request->payment_term;
			$Receivables->receivable_description 	= $request->receivable_description;
			$Receivables->receivable_amount 		= $receivable_amount;
			$Receivables->receivable_status 		=  $request->receivable_status;
			
			$result = $Receivables->save();
			
			if($result){
				//return response()->json(['success'=>'Receivables Information Successfully Created!']);
				return response()->json(array('success' => true, 'sales_order_id' => $Receivables->sales_order_id), 200);
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
