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
use PhpOffice\PhpSpreadsheet\IOFactory;
//use PhpOffice\PhpSpreadsheet\PHPExcel_IOFactory;
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
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		$data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('order_date', '>=', $start_date)
                    ->where('order_date', '<=', $end_date)
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
	
	public function generate_report_excel(Request $request){

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
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		$data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('order_date', '>=', $start_date)
                    ->where('order_date', '<=', $end_date)
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

		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address']);
		
		$client_name = $client_data['client_name'];
		
	   ini_set('max_execution_time', 0);
       ini_set('memory_limit', '4000M');
       try {
		   
           $spreadSheet = new Spreadsheet();
           
           $spreadSheet = IOFactory::load(public_path('/template/Billing-Statement-form.xlsx'));
		   /*
		   $spreadSheet->getActiveSheet()->setCellValue('A1', 'Billing Statement');
           
		   $spreadSheet->getActiveSheet()->mergeCells('B3:C3');
		   $spreadSheet->getActiveSheet()->setCellValue('A3', "CLIENT'S NAME:");
		   $spreadSheet->getActiveSheet()->setCellValue('B3', $client_name);
		   
		   $spreadSheet->getActiveSheet()->mergeCells('H3:I3');
		   $spreadSheet->getActiveSheet()->setCellValue('H3', 'P.O PERIOD:');
		   $spreadSheet->getActiveSheet()->mergeCells('J3:K3');
		   $spreadSheet->getActiveSheet()->setCellValue('J3', 'p.o?');
		   
		   $spreadSheet->getActiveSheet()->mergeCells('H4:I4');
		   $spreadSheet->getActiveSheet()->setCellValue('H4', 'BILLING DATE:');
		   $spreadSheet->getActiveSheet()->mergeCells('J4:K4');
		   $spreadSheet->getActiveSheet()->setCellValue('J4', date('Y-m-d'));
		   
		   //DATE	DRIVER'S NAME	P.O. No.	PLATE NUMBER	PRODUCT 	QUANTITY	UOM PRICE	AMOUNT	TIME
		   $spreadSheet->getActiveSheet()->setCellValue('A6', "ITEM #");
		   $spreadSheet->getActiveSheet()->setCellValue('B6', "DATE");
		   $spreadSheet->getActiveSheet()->setCellValue('C6', "DRIVER'S NAME");
		   $spreadSheet->getActiveSheet()->setCellValue('D6', "P.O. No.");
		   $spreadSheet->getActiveSheet()->setCellValue('E6', "PLATE NUMBER");
		   $spreadSheet->getActiveSheet()->setCellValue('F6', "PRODUCT");
		   $spreadSheet->getActiveSheet()->setCellValue('G6', "QUANTITY");
		   $spreadSheet->getActiveSheet()->setCellValue('H6', "UOM");
		   $spreadSheet->getActiveSheet()->setCellValue('I6', "PRICE");
		   $spreadSheet->getActiveSheet()->setCellValue('J6', "AMOUNT");
		   $spreadSheet->getActiveSheet()->setCellValue('K6', "TIME");
		   */
		   
		    $spreadSheet->getActiveSheet()->setCellValue('B7', $client_name);
			$spreadSheet->getActiveSheet()->setCellValue('J7', 'p.o?');
			$spreadSheet->getActiveSheet()->setCellValue('J8', date('Y-m-d')); 
			
			$no_excl = 11;
			$n = 1;
			
			$billing_data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('order_date', '>=', $start_date)
                    ->where('order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
              		->get([
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_billing_table.order_date',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time']);
			
			
			foreach ($billing_data as $billing_data_column){
			
				$spreadSheet->getActiveSheet()
					->setCellValue('A'.$no_excl, $n)
					->setCellValue('B'.$no_excl, $billing_data_column['order_date'])
					->setCellValue('C'.$no_excl, $billing_data_column['drivers_name'])
					->setCellValue('D'.$no_excl, $billing_data_column['order_po_number'])
					->setCellValue('E'.$no_excl, $billing_data_column['plate_no'])
					->setCellValue('F'.$no_excl, $billing_data_column['product_name'])
					->setCellValue('G'.$no_excl, $billing_data_column['order_quantity'])
					->setCellValue('H'.$no_excl, $billing_data_column['product_unit_measurement'])
					->setCellValue('I'.$no_excl, $billing_data_column['product_price'])
					->setCellValue('J'.$no_excl, $billing_data_column['order_total_amount'])
					->setCellValue('K'.$no_excl, $billing_data_column['order_time']);

			/*Increment*/
			$no_excl++;
			$n++;
			} 
		   
		   $Excel_writer = new Xlsx($spreadSheet);
           header('Content-Type: application/vnd.ms-excel');
           header("Content-Disposition: attachment;filename=".$client_name."Billing Statement.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       
	   } catch (Exception $e) {
           return;
       }
	   
	}	
	
	
	public function test_draw(Request $request){

	$objPHPExcel = IOFactory::load(public_path('/template/Billing-Statement-form.xlsx'));
	$objPHPExcel->getActiveSheet()
                            ->setCellValue('A12', "No")
                            ->setCellValue('B12', "Name")
                            ->setCellValue('C12', "Email")
                            ->setCellValue('D12', "Phone")
                            ->setCellValue('E12', "Address");
							
							$Excel_writer = new Xlsx($objPHPExcel);
           header('Content-Type: application/vnd.ms-excel');
           header("Content-Disposition: attachment;filename=ooo.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
	}
}