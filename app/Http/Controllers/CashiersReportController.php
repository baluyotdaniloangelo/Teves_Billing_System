<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\ProductModel;
use Session;
use Validator;
use DataTables;

class CashiersReportController extends Controller
{
	
	/*Load client Interface*/
	public function cashierReport(){
		
		$title = "Cashier' Report";
		$data = array();
		if(Session::has('loginID')){
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
		}
		return view("pages.cashiers_report", compact('data','title'));
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getCashierReport(Request $request)
    {
		
		$list = CashiersReportModel::get();
		if ($request->ajax()) {
			
			    	$data = CashiersReportModel::join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
              		->get([				
					'teves_cashiers_report.cashiers_report_id',
					'user_tb.user_real_name',
					'teves_cashiers_report.teves_branch',
					'teves_cashiers_report.forecourt_attendant',
					'teves_cashiers_report.report_date',
					'teves_cashiers_report.shift',
					'teves_cashiers_report.created_at',
					'teves_cashiers_report.updated_at']);
			
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){
						$actionBtn = '
						<div align="center" class="action_table_menu_client">
						<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editCashiersReport"></a>
						<a href="#" data-id="'.$row->cashiers_report_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteCashiersReport"></a>
						</div>';
						return $actionBtn;
					})
					->rawColumns(['action'])
					->make(true);
		}
		
    }

	public function create_cashier_report_post(Request $request){
		
		$request->validate([
			'report_date'   => 'required'
        ], 
        [
			'report_date.required' => 'Report Date is required'
        ]
		);
		
			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate->user_idx 				= Session::get('loginID');
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->shift 				= $request->shift;
			$result = $CashiersReportCreate->save();
			
			/*Get Last ID*/
			$last_transaction_id = $CashiersReportCreate->cashiers_report_id;
			
			
			if($result){
				//return response()->json(['success'=>"Cashier's Report Information Successfully Created!"]);
				return response()->json(array('success' => "Cashier's Report Information Successfully Created!", 'cashiers_report_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	public function update_cashier_report_post(Request $request){
		
		$request->validate([
			'report_date'   => 'required'
        ], 
        [
			'report_date.required' => 'Report Date is required'
        ]
		);
		
			$CashiersReportCreate = new CashiersReportModel();
			$CashiersReportCreate = CashiersReportModel::find($request->CashiersReportId);
			$CashiersReportCreate->user_idx 				= Session::get('loginID');
			$CashiersReportCreate->teves_branch 			= $request->teves_branch;
			$CashiersReportCreate->forecourt_attendant 		= $request->forecourt_attendant;
			$CashiersReportCreate->report_date 				= $request->report_date;
			$CashiersReportCreate->shift 					= $request->shift;
			$result = $CashiersReportCreate->update();
			
			/*Get Last ID
			$last_transaction_id = $CashiersReportCreate->cashiers_report_id;*/
			
			
			if($result){
				//return response()->json(['success'=>"Cashier's Report Information Successfully Created!"]);
				return response()->json(array('success' => "Cashier's Report Information Successfully Updated!", 'cashiers_report_id' => $request->CashiersReportId), 200);
			}
			else{
				return response()->json(['success'=>"Error on Insert Cashier's Report Information"]);
			}
			
	}

	/*Fetch client Information*/
	public function cashiers_report_info(Request $request){
		
		$CashiersReportID = $request->CashiersReportID;
		
		$data = CashiersReportModel::where('cashiers_report_id', $request->CashiersReportID)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
		
		return response()->json($data);
					
	}

	/*Fetch client Information*/
	public function cashiers_report_form($CashiersReportId){
		
		$data = array();
		if(Session::has('loginID')){
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
		}
		
		$title = "Cashier' Report";
		$product_data = ProductModel::all();
		$CashiersReportData = CashiersReportModel::where('cashiers_report_id', $CashiersReportId)
			->join('user_tb', 'user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
            ->get([				
			'teves_cashiers_report.cashiers_report_id',
			'user_tb.user_real_name',
			'teves_cashiers_report.teves_branch',
			'teves_cashiers_report.forecourt_attendant',
			'teves_cashiers_report.report_date',
			'teves_cashiers_report.shift',
			'teves_cashiers_report.created_at',
			'teves_cashiers_report.updated_at']);
			
		return view("pages.cashiers_report_form", compact('data','title','CashiersReportData','product_data','CashiersReportId'));	
		
	}

	/*Delete client Information*/
	public function delete_client_confirmed(Request $request){
		$clientID = $request->clientID;
		ClientModel::find($clientID)->delete();
		return 'Deleted';
	} 


	public function update_client_post(Request $request){
		$request->validate([
          'client_name'      		=> 'required|unique:teves_client_table,client_name,'.$request->clientID.',client_id',
		  'client_address'      	=> 'required',
		  'client_tin'      	=> 'required'
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
			$client = new ClientModel();
			$client = ClientModel::find($request->clientID);
			$client->client_name 		= $request->client_name;
			$client->client_address 	= $request->client_address;
			$client->client_tin 		= $request->client_tin;
			$result = $client->update();
			if($result){
				return response()->json(['success'=>'Client Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update client Information']);
			}
	}
}
