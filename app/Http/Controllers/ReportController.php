<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BillingTransactionModel;
/*use App\Models\ProductModel;*/
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;
/*Excel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReportController extends Controller
{
	/*Load Report Interface*/
	public function report(){
		
		$title = 'Billing Statement';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('id', '=', Session::get('loginID'))->first();
			
			/*$product_data = ProductModel::all();*/
			
			$client_data = ClientModel::all();
		
		}

		return view("pages.report", compact('data','title','product_data','client_data'));
		
	}   
	
	public function generate_report(Request $request){

		$request->validate([
          'client_idx'      		=> 'required',
		  'start_date'      		=> 'required',
		  'end_date'      			=> 'required'
        ], 
        [
			'client_idx.required' 	=> 'Please select a Client',
			'start_date.required' 	=> 'Please select a Start Date',
			'end_date.required' 	=> 'Please select a End Date'
        ]
		);


		$client_idx = $request->client_idx;
		
		$data = BillingTransactionModel::where('client_idx', $client_idx)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
              		->get([
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_name',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_billing_table.order_date',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time']);
		
		return response()->json($data);
		
	}	
	
	public function sample1(Request $request){

	
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('A1', 'Hello World !');

	$writer = new Xlsx($spreadsheet);
	$writer->save('hello world.xlsx');
	}	
	
}