<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BillingTransactionModel;
use App\Models\ReceivablesModel;
use App\Models\ReceivablesPaymentModel;
use App\Models\ProductModel;

use App\Models\SalesOrderModel;
use App\Models\SalesOrderComponentModel;
use App\Models\SalesOrderPaymentModel;

use App\Models\PurchaseOrderModel;
use App\Models\PurchaseOrderComponentModel;
use App\Models\PurchaseOrderPaymentModel;

use App\Models\ClientModel;
use Session;
use Validator;
//use DataTables;

/*Excel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/*PDF*/
use PDF;

class ReportController extends Controller
{
	
	/*Load Billing History Report Interface*/
	public function billing_history(){
		
		$title = 'Billing History';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$client_data = ClientModel::all();
			
			$product_data = ProductModel::all();
			
			$drivers_name = BillingTransactionModel::select('drivers_name')->distinct()->get();
			$plate_no = BillingTransactionModel::select('plate_no')->distinct()->get();
		
		}

		return view("pages.billing_history", compact('data','title','client_data','drivers_name','plate_no','product_data'));
		
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
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.receivable_idx',
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
		
		return response()->json($data);
		
	}	
	
	/*Generated for receivable but not save*/
	public function generate_report_recievable(Request $request){

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
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->where('teves_billing_table.receivable_idx', '=', 0)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.receivable_idx',
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
		
		return response()->json($data);
		
	}	
	
	/*Generated for receivable after saved*/
	public function generate_report_recievable_after_saved(Request $request){

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
		
		$receivable_id = $request->receivable_id;
		$client_idx = $request->client_idx;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		
		$data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->where('teves_billing_table.receivable_idx', '=', $receivable_id)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.receivable_idx',
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
		$less_per_liter = $request->less_per_liter;

		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address']);
		
		$client_name = $client_data['client_name'];
		$client_address = $client_data['client_address'];
		
		
		$_po_start_date=date_create("$start_date");
		$po_start_date = date_format($_po_start_date,"m/d/y");
			
		$_po_end_date=date_create("$end_date");
		$po_end_date = date_format($_po_end_date,"m/d/y");
			
	   ini_set('max_execution_time', 0);
       ini_set('memory_limit', '4000M');
       try {
		   ob_start();
           $spreadSheet = new Spreadsheet();
           
           $spreadSheet = IOFactory::load(public_path('/template/Billing Statement.xlsx'));

		    $spreadSheet->getActiveSheet()->setCellValue('B7', $client_name);
			$spreadSheet->getActiveSheet()->setCellValue('B8', $client_address);
			
			$spreadSheet->getActiveSheet()->setCellValue('J7', "$po_start_date - $po_end_date");
			$spreadSheet->getActiveSheet()->setCellValue('J8', date('m/d/Y')); 
				
				
				$styleBorder_prepared = array(
					'borders' => array(
						'bottom' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => array('rgb' => '000000'),
						),
					),
				);
				
				$styleBorder = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
			
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
					
			$total_liters = 0;						
			$total_payable = 0;
			
			foreach ($billing_data as $billing_data_column){
			
				$spreadSheet->getActiveSheet()
					->setCellValue('A'.$no_excl, $n)
					->setCellValue('B'.$no_excl, $billing_data_column['order_date'])
					->setCellValue('C'.$no_excl, $billing_data_column['order_time'])
					->setCellValue('D'.$no_excl, $billing_data_column['drivers_name'])
					->setCellValue('E'.$no_excl, $billing_data_column['order_po_number'])
					->setCellValue('F'.$no_excl, $billing_data_column['plate_no'])
					->setCellValue('G'.$no_excl, $billing_data_column['product_name'])
					->setCellValue('H'.$no_excl, $billing_data_column['product_price'])
					->setCellValue('I'.$no_excl, $billing_data_column['order_quantity'])
					->setCellValue('J'.$no_excl, $billing_data_column['product_unit_measurement'])
					->setCellValue('K'.$no_excl, $billing_data_column['order_total_amount']);
					
					$spreadSheet->getActiveSheet()->getStyle("A$no_excl:K$no_excl")->applyFromArray($styleBorder);
					
					$total_payable+= $billing_data_column['order_total_amount'];
					
					if($billing_data_column['product_unit_measurement']=='L'){
						$total_liters += $billing_data_column['order_quantity'];
					}else{
						$total_liters += 0;
					}
			/*Increment*/
			$no_excl++;
			$n++;
			} 
			
			$spreadSheet->getActiveSheet()->getStyle("H".$no_excl.":I".$no_excl)->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('H'.$no_excl, 'Total Volume:')
					->setCellValue('I'.$no_excl, $total_liters);
					
			$spreadSheet->getActiveSheet()->getStyle('H'.($no_excl+1).":I".($no_excl+1))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('H'.($no_excl+1), 'Less per liter:')
					->setCellValue('I'.($no_excl+1), number_format(($less_per_liter*$total_liters),2));		
			
			$spreadSheet->getActiveSheet()->getStyle("J".$no_excl.":K".$no_excl)->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl), 'Total Due:')
					->setCellValue('K'.($no_excl), number_format(($total_payable),2));
			
			$spreadSheet->getActiveSheet()->getStyle("J".($no_excl+1).":K".($no_excl+1))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl+1), '')
					->setCellValue('K'.($no_excl+1), number_format(($less_per_liter*$total_liters),2));			
			
			$spreadSheet->getActiveSheet()->getStyle("J".($no_excl+2).":K".($no_excl+2))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl+2), 'Total Payable:')
					->setCellValue('K'.($no_excl+2), number_format($total_payable-($less_per_liter*$total_liters),2));
			
			/*USER INFO*/
			$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
			$spreadSheet->getActiveSheet()
					->setCellValue('A'.($no_excl+6), 'Prepared by:')
					->setCellValue('B'.($no_excl+6), $user_data['user_real_name']);
			$spreadSheet->getActiveSheet()->getStyle('B'.($no_excl+6))->applyFromArray($styleBorder_prepared);
			
			$spreadSheet->getActiveSheet()
					->setCellValue('B'.($no_excl+7), $user_data['user_job_title']);
			
			$spreadSheet->getActiveSheet()
			->getStyle("A11:A$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$spreadSheet->getActiveSheet()
			->getStyle("B11:B$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("C11:C$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$spreadSheet->getActiveSheet()
			->getStyle("H11:H".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("I11:I".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("J11:J".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("K11:K".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
		  $Excel_writer = new Xlsx($spreadSheet);
		  
		   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header("Content-Disposition: attachment;filename=".$client_name." - Billing Statement.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       
	   } catch (Exception $e) {
           return;
       }
	   
	}	

	public function generate_report_excel_covered_bill_pdf(Request $request){

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
		$less_per_liter = $request->less_per_liter;
		
		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address']);
		
		$client_name = $client_data['client_name'];
		$client_address = $client_data['client_address'];
		
		
		$_po_start_date=date_create("$start_date");
		$po_start_date = date_format($_po_start_date,"m/d/y");
			
		$_po_end_date=date_create("$end_date");
		$po_end_date = date_format($_po_end_date,"m/d/y");
			
	   ini_set('max_execution_time', 0);
       ini_set('memory_limit', '4000M');
       try {
		   ob_start();
           $spreadSheet = new Spreadsheet();
           
           $spreadSheet = IOFactory::load(public_path('/template/Billing Statement.xlsx'));

		    $spreadSheet->getActiveSheet()->setCellValue('B7', $client_name);
			$spreadSheet->getActiveSheet()->setCellValue('B8', $client_address);
			
			$spreadSheet->getActiveSheet()->setCellValue('J7', "$po_start_date - $po_end_date");
			$spreadSheet->getActiveSheet()->setCellValue('J8', date('m/d/Y')); 
				
				
				$styleBorder_prepared = array(
					'borders' => array(
						'bottom' => array(
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => array('rgb' => '000000'),
						),
					),
				);
				
				$styleBorder = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
			
			$no_excl = 11;
			$n = 1;
			
			$billing_data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('order_date', '>=', $start_date)
                    ->where('order_date', '<=', $end_date)
					->where('teves_billing_table.receivable_idx', '=', $receivable_id)
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
					
			$total_liters = 0;						
			$total_payable = 0;
			
			foreach ($billing_data as $billing_data_column){
			
				$spreadSheet->getActiveSheet()
					->setCellValue('A'.$no_excl, $n)
					->setCellValue('B'.$no_excl, $billing_data_column['order_date'])
					->setCellValue('C'.$no_excl, $billing_data_column['order_time'])
					->setCellValue('D'.$no_excl, $billing_data_column['drivers_name'])
					->setCellValue('E'.$no_excl, $billing_data_column['order_po_number'])
					->setCellValue('F'.$no_excl, $billing_data_column['plate_no'])
					->setCellValue('G'.$no_excl, $billing_data_column['product_name'])
					->setCellValue('H'.$no_excl, $billing_data_column['product_price'])
					->setCellValue('I'.$no_excl, $billing_data_column['order_quantity'])
					->setCellValue('J'.$no_excl, $billing_data_column['product_unit_measurement'])
					->setCellValue('K'.$no_excl, $billing_data_column['order_total_amount']);
					
					$spreadSheet->getActiveSheet()->getStyle("A$no_excl:K$no_excl")->applyFromArray($styleBorder);
					
					$total_payable+= $billing_data_column['order_total_amount'];
					
					if($billing_data_column['product_unit_measurement']=='L'){
						$total_liters += $billing_data_column['order_quantity'];
					}else{
						$total_liters += 0;
					}
			/*Increment*/
			$no_excl++;
			$n++;
			} 
			
			$spreadSheet->getActiveSheet()->getStyle("H".$no_excl.":I".$no_excl)->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('H'.$no_excl, 'Total Volume:')
					->setCellValue('I'.$no_excl, $total_liters);
					
			$spreadSheet->getActiveSheet()->getStyle('H'.($no_excl+1).":I".($no_excl+1))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('H'.($no_excl+1), 'Less per liter:')
					->setCellValue('I'.($no_excl+1), number_format(($less_per_liter*$total_liters),2));		
			
			$spreadSheet->getActiveSheet()->getStyle("J".$no_excl.":K".$no_excl)->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl), 'Total Due:')
					->setCellValue('K'.($no_excl), number_format(($total_payable),2));
			
			$spreadSheet->getActiveSheet()->getStyle("J".($no_excl+1).":K".($no_excl+1))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl+1), '')
					->setCellValue('K'.($no_excl+1), number_format(($less_per_liter*$total_liters),2));			
			
			$spreadSheet->getActiveSheet()->getStyle("J".($no_excl+2).":K".($no_excl+2))->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.($no_excl+2), 'Total Payable:')
					->setCellValue('K'.($no_excl+2), number_format($total_payable-($less_per_liter*$total_liters),2));
			
			/*USER INFO*/
			$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
			$spreadSheet->getActiveSheet()
					->setCellValue('A'.($no_excl+6), 'Prepared by:')
					->setCellValue('B'.($no_excl+6), $user_data['user_real_name']);
			$spreadSheet->getActiveSheet()->getStyle('B'.($no_excl+6))->applyFromArray($styleBorder_prepared);
			
			$spreadSheet->getActiveSheet()
					->setCellValue('B'.($no_excl+7), $user_data['user_job_title']);
			
			$spreadSheet->getActiveSheet()
			->getStyle("A11:A$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$spreadSheet->getActiveSheet()
			->getStyle("B11:B$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("C11:C$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$spreadSheet->getActiveSheet()
			->getStyle("H11:H".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("I11:I".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("J11:J".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("K11:K".($no_excl+2))
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
		  $Excel_writer = new Xlsx($spreadSheet);
		  
		   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header("Content-Disposition: attachment;filename=".$client_name." - Billing Statement.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       
	   } catch (Exception $e) {
           return;
       }
	   
	}	

	public function generate_receivable_covered_bill_pdf(Request $request){

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
		
		$receivable_id = $request->receivable_id;
		$client_idx = $request->client_idx;
		$start_date = $request->start_date;
		$end_date = $request->end_date;
		$less_per_liter = $request->less_per_liter;
		$company_header = $request->company_header;
		
		$withholding_tax_percentage = $request->withholding_tax_percentage;
		$net_value_percentage = $request->net_value_percentage;
		$vat_value_percentage = $request->vat_value_percentage;
		
		$billing_data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->where('teves_billing_table.receivable_idx', '=', $receivable_id)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time']);	
					
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		/*Recievable Data*/
				
		$receivable_data = ReceivablesModel::find($receivable_id, ['payment_term','sales_order_idx','billing_date','ar_reference','or_number','control_number']);
		
		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address','client_tin']);
          
		$title = 'BILLING STATEMENT';
		  
        $pdf = PDF::loadView('pages.report_billing_receivable_pdf', compact('title', 'client_data', 'user_data', 'billing_data', 'start_date', 'end_date', 'less_per_liter', 'company_header', 'receivable_data','withholding_tax_percentage','net_value_percentage','vat_value_percentage'));
		
		/*Download Directly*/
		/*Stream for Saving/Printing*/
		$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		$pdf->render();
		return $pdf->stream($client_data['client_name'].".pdf");
	}

	
	public function generate_report_pdf(Request $request){
		
		/**/
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
		$less_per_liter = $request->less_per_liter;
		$company_header = $request->company_header;
		
		$withholding_tax_percentage = $request->withholding_tax_percentage;
		$net_value_percentage = $request->net_value_percentage;
		$vat_value_percentage = $request->vat_value_percentage;
		
		$billing_data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
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
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		/*Recievable Data*/
		$receivable_id = $request->receivable_id;		
		$receivable_data = ReceivablesModel::find($receivable_id, ['payment_term']);
		
		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address','client_tin']);
          
		$title = 'Billing History';
		  
        $pdf = PDF::loadView('pages.report_billing_pdf', compact('title', 'client_data', 'user_data', 'billing_data', 'start_date', 'end_date', 'less_per_liter', 'company_header', 'receivable_data','withholding_tax_percentage','net_value_percentage','vat_value_percentage'));
		
		/*Download Directly*/
        /*return $pdf->download($client_data['client_name'].".pdf");*/
		/*Stream for Saving/Printing*/
		$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		$pdf->render();
		return $pdf->stream($client_data['client_name'].".pdf");
		//return view('pages.report_billing_pdf', compact('title', 'client_data', 'user_data', 'billing_data', 'start_date', 'end_date', 'less_per_liter', 'company_header', 'receivable_data','withholding_tax_percentage','net_value_percentage','vat_value_percentage'));
	}
	
	public function generate_receivable_pdf(Request $request){

		$request->validate([
			'receivable_id'      		=> 'required'
        ], 
        [
			'receivable_id.required' 	=> 'Please select a receivable_id'
        ]
		);

		$receivable_id = $request->receivable_id;
					
				$receivable_data = ReceivablesModel::where('receivable_id', $request->receivable_id)
				->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
              	->get([
					'teves_receivable_table.receivable_id',
					'teves_receivable_table.billing_date',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_receivable_table.control_number',
					'teves_client_table.client_tin',
					'teves_receivable_table.or_number',
					'teves_receivable_table.ar_reference',					
					'teves_receivable_table.payment_term',
					'teves_receivable_table.receivable_description',
					'teves_receivable_table.receivable_amount',
					'teves_receivable_table.company_header',
					'billing_period_start',
					'billing_period_end'
				]);
		
		$receivable_amount_amt =  number_format($receivable_data[0]['receivable_amount'],2,".","");
		
		@$amount_split_whole_to_decimal = explode('.',$receivable_amount_amt);
		
		$amount_in_word_whole = $this->numberToWord($amount_split_whole_to_decimal[0]) ." Pesos";
		
		if(@$amount_split_whole_to_decimal[1]==0){
			$amount_in_word_decimal = "";
		}else{
			$amount_in_word_decimal = " and ".$this->numberToWord( $amount_split_whole_to_decimal[1] ) ." Centavos";
		}
		
		$amount_in_words = $amount_in_word_whole."".$amount_in_word_decimal;		
	
		
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'RECEIVABLE';
		  
        $pdf = PDF::loadView('pages.report_receivables_pdf', compact('title', 'receivable_data', 'user_data', 'amount_in_words'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		//$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_data[0]['client_name']."_RECEIVABLE.pdf");
		
		//return view('pages.report_receivables_pdf', compact('title', 'receivable_data', 'user_data', 'amount_in_words'));
		
	}
	
	public function generate_receivable_soa_pdf(Request $request){

		$request->validate([
			'receivable_id'      		=> 'required'
        ], 
        [
			'receivable_id.required' 	=> 'Please select a receivable_id'
        ]
		);

		$receivable_id = $request->receivable_id;
					
				$receivable_data = ReceivablesModel::where('receivable_id', $request->receivable_id)
				->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_receivable_table.client_idx')
              	->get([
					'teves_receivable_table.receivable_id',
					'teves_receivable_table.sales_order_idx',
					'teves_receivable_table.billing_date',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_receivable_table.control_number',
					'teves_client_table.client_tin',
					'teves_receivable_table.or_number',
					'teves_receivable_table.ar_reference',
					'teves_receivable_table.payment_term',
					'teves_receivable_table.receivable_description',
					'teves_receivable_table.receivable_amount',
					'billing_period_start',
					'billing_period_end',
					'company_header'
				]);
		
		$receivable_payment_data =  ReceivablesPaymentModel::where('teves_receivable_payment.receivable_idx', $request->receivable_id)
				->orderBy('receivable_payment_id', 'asc')
              	->get([
					'teves_receivable_payment.receivable_payment_id',
					'teves_receivable_payment.receivable_date_of_payment',
					'teves_receivable_payment.receivable_mode_of_payment',
					'teves_receivable_payment.receivable_reference',
					'teves_receivable_payment.receivable_payment_amount',
					]);
		
		
		$receivable_amount_amt =  number_format($receivable_data[0]['receivable_amount'],2,".","");
		
		@$amount_split_whole_to_decimal = explode('.',$receivable_amount_amt);
		
		$amount_in_word_whole = $this->numberToWord($amount_split_whole_to_decimal[0]) ." Pesos";
		
		if(@$amount_split_whole_to_decimal[1]==0){
			$amount_in_word_decimal = "";
		}else{
			$amount_in_word_decimal = " and ".$this->numberToWord( $amount_split_whole_to_decimal[1] ) ." Centavos";
		}
		
		$amount_in_words = $amount_in_word_whole."".$amount_in_word_decimal;		
	
		
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'STATEMENT OF ACCOUNT';
		  
        $pdf = PDF::loadView('pages.report_receivables_soa_pdf', compact('title', 'receivable_data', 'user_data', 'amount_in_words', 'receivable_payment_data'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		//$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_data[0]['client_name']."_RECEIVABLE_SOA.pdf");
	}

	public function generate_sales_order_pdf(Request $request){

		$request->validate([
			'sales_order_id'      		=> 'required'
        ], 
        [
			'sales_order_id.required' 	=> 'Please select a receivable_id'
        ]
		);

		$sales_order_id = $request->sales_order_id;
					
				$sales_order_data = SalesOrderModel::where('teves_sales_order_table.sales_order_id', $sales_order_id)
				->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
              	->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_client_table.client_tin',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',		
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_delivered_to',
					'teves_sales_order_table.sales_order_delivered_to_address',
					'teves_sales_order_table.sales_order_delivery_method',
					'teves_sales_order_table.sales_order_gross_amount',
					'teves_sales_order_table.sales_order_net_amount',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_hauler',
					'teves_sales_order_table.sales_order_required_date',
					'teves_sales_order_table.sales_order_instructions',
					'teves_sales_order_table.sales_order_note',
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_less_percentage',
					'teves_sales_order_table.company_header'
				]);
				
		$sales_order_amt =  number_format($sales_order_data[0]['sales_order_total_due'],2,".","");
		
		@$amount_split_whole_to_decimal = explode('.',$sales_order_amt);
		
		$amount_in_word_whole = $this->numberToWord($amount_split_whole_to_decimal[0]) ." Pesos";
		
		if(@$amount_split_whole_to_decimal[1]==0){
			$amount_in_word_decimal = "";
		}else{
			$amount_in_word_decimal = " and ".$this->numberToWord( $amount_split_whole_to_decimal[1] ) ." Centavos";
		}
		
		$amount_in_words = $amount_in_word_whole."".$amount_in_word_decimal;
		
		$sales_order_component = SalesOrderComponentModel::where('teves_sales_order_component_table.sales_order_idx', $sales_order_id)
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_sales_order_component_table.product_idx')	
			->orderBy('sales_order_component_id', 'asc')
              	->get([
					'teves_sales_order_component_table.sales_order_component_id',
					'teves_sales_order_component_table.product_idx',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_sales_order_component_table.product_price',
					'teves_sales_order_component_table.order_quantity',
					'teves_sales_order_component_table.order_total_amount'
					]);
		
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'SALES ORDER';
		  
        $pdf = PDF::loadView('pages.report_sales_order_pdf', compact('title', 'sales_order_data', 'user_data', 'amount_in_words', 'sales_order_component'));
		//return view('pages.report_sales_order_pdf', compact('title', 'sales_order_data', 'user_data', 'amount_in_words', 'sales_order_component'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		//$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($sales_order_data[0]['client_name']."_SALES_ORDER.pdf");
	}

	public function generate_purchase_order_pdf(Request $request){

		$purchase_order_id = $request->purchase_order_id;
				
				$purchase_order_data = PurchaseOrderModel::where('teves_purchase_order_table.purchase_order_id', $request->purchase_order_id)
				->join('teves_supplier_table', 'teves_supplier_table.supplier_id', '=', 'teves_purchase_order_table.purchase_order_supplier_idx')
              	->get([
						'teves_supplier_table.supplier_name',
						'teves_supplier_table.supplier_tin',
						'teves_supplier_table.supplier_address',
						'teves_purchase_order_table.purchase_order_control_number',
						'teves_purchase_order_table.purchase_order_date',
						'teves_purchase_order_table.purchase_order_sales_order_number',
						'teves_purchase_order_table.purchase_order_collection_receipt_no',
						'teves_purchase_order_table.purchase_order_official_receipt_no',
						'teves_purchase_order_table.purchase_order_delivery_receipt_no',
						'teves_purchase_order_table.purchase_order_bank',
						'teves_purchase_order_table.purchase_order_date_of_payment',
						'teves_purchase_order_table.purchase_order_reference_no',
						'teves_purchase_order_table.purchase_order_payment_amount',
						'teves_purchase_order_table.purchase_order_delivery_method',
						'teves_purchase_order_table.purchase_loading_terminal',
						'teves_purchase_order_table.purchase_order_date_of_pickup',
						'teves_purchase_order_table.purchase_order_date_of_arrival',
						'teves_purchase_order_table.purchase_order_gross_amount',
						'teves_purchase_order_table.purchase_order_total_liters',
						'teves_purchase_order_table.purchase_order_net_percentage', 
						'teves_purchase_order_table.purchase_order_net_amount',
						'teves_purchase_order_table.purchase_order_less_percentage',
						'teves_purchase_order_table.purchase_order_total_payable',
						'teves_purchase_order_table.hauler_operator',
						'teves_purchase_order_table.lorry_driver',
						'teves_purchase_order_table.plate_number',
						'teves_purchase_order_table.contact_number',
						'teves_purchase_order_table.purchase_destination',
						'teves_purchase_order_table.purchase_destination_address',
						'teves_purchase_order_table.purchase_date_of_departure',
						'teves_purchase_order_table.purchase_date_of_arrival',
						'teves_purchase_order_table.purchase_order_instructions',
						'teves_purchase_order_table.purchase_order_note',
						'teves_purchase_order_table.company_header'
				]);

		$purchase_order_amt =  number_format($purchase_order_data[0]['purchase_order_total_payable'],2,".","");
		
		@$amount_split_whole_to_decimal = explode('.',$purchase_order_amt);
		
		$amount_in_word_whole = $this->numberToWord($amount_split_whole_to_decimal[0]) ." Pesos";
		
		if(@$amount_split_whole_to_decimal[1]==0){
			$amount_in_word_decimal = "";
		}else{
			$amount_in_word_decimal = " and ".$this->numberToWord( $amount_split_whole_to_decimal[1] ) ." Centavos";
		}
		
		$amount_in_words = $amount_in_word_whole."".$amount_in_word_decimal;
		
		$purchase_order_component = PurchaseOrderComponentModel::where('teves_purchase_order_component_table.purchase_order_idx', $purchase_order_id)	
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_purchase_order_component_table.product_idx')	
			->orderBy('purchase_order_component_id', 'asc')
              	->get([
					'teves_purchase_order_component_table.purchase_order_component_id',
					'teves_purchase_order_component_table.product_idx',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_purchase_order_component_table.product_price',
					'teves_purchase_order_component_table.order_quantity',
					'teves_purchase_order_component_table.order_total_amount'
					]);

		$purchase_payment_component = PurchaseOrderPaymentModel::where('teves_purchase_order_payment_details.purchase_order_idx', $purchase_order_id)
			->orderBy('purchase_order_payment_details_id', 'asc')
              	->get([
					'teves_purchase_order_payment_details.purchase_order_bank',
					'teves_purchase_order_payment_details.purchase_order_date_of_payment',
					'teves_purchase_order_payment_details.purchase_order_reference_no',
					'teves_purchase_order_payment_details.purchase_order_payment_amount',
					]);
					
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'PURCHASE ORDER';
		  
        $pdf = PDF::loadView('pages.report_purchase_order_pdf', compact('title', 'purchase_order_data', 'user_data', 'amount_in_words', 'purchase_order_component', 'purchase_payment_component'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		//$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($purchase_order_data[0]['supplier_name']."_PURCHASE_ORDER.pdf");
		//return view('pages.report_purchase_order_pdf', compact('title', 'purchase_order_data', 'user_data', 'amount_in_words', 'purchase_order_component', 'purchase_payment_component'));
	}
		
	public function numberToWord($num = '')
    {
        $num    = ( string ) ( ( int ) $num );
        
        if( ( int ) ( $num ) && ctype_digit( $num ) )
        {
            $words  = array( );
             
            $num    = str_replace( array( ',' , ' ' ) , '' , trim( $num ) );
             
            $list1  = array('','one','two','three','four','five','six','seven',
                'eight','nine','ten','eleven','twelve','thirteen','fourteen',
                'fifteen','sixteen','seventeen','eighteen','nineteen');
             
            $list2  = array('','ten','twenty','thirty','forty','fifty','sixty',
                'seventy','eighty','ninety','hundred');
             
            $list3  = array('','thousand','million','billion','trillion',
                'quadrillion','quintillion','sextillion','septillion',
                'octillion','nonillion','decillion','undecillion',
                'duodecillion','tredecillion','quattuordecillion',
                'quindecillion','sexdecillion','septendecillion',
                'octodecillion','novemdecillion','vigintillion');
             
            $num_length = strlen( $num );
            $levels = ( int ) ( ( $num_length + 2 ) / 3 );
            $max_length = $levels * 3;
            $num    = substr( '00'.$num , -$max_length );
            $num_levels = str_split( $num , 3 );
             
            foreach( $num_levels as $num_part )
            {
                $levels--;
                $hundreds   = ( int ) ( $num_part / 100 );
				//$hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : 's' ) . ' ' : '' );
                $hundreds   = ( $hundreds ? ' ' . $list1[$hundreds] . ' Hundred' . ( $hundreds == 1 ? '' : '' ) . ' ' : '' );
                $tens       = ( int ) ( $num_part % 100 );
                $singles    = '';
                 
                if( $tens < 20 ) { $tens = ( $tens ? ' ' . $list1[$tens] . ' ' : '' ); } else { $tens = ( int ) ( $tens / 10 ); $tens = ' ' . $list2[$tens] . ' '; $singles = ( int ) ( $num_part % 10 ); $singles = ' ' . $list1[$singles] . ' '; } $words[] = $hundreds . $tens . $singles . ( ( $levels && ( int ) ( $num_part ) ) ? ' ' . $list3[$levels] . ' ' : '' ); } $commas = count( $words ); if( $commas > 1 )
            {
                $commas = $commas - 1;
            }
             
            $words  = implode( ', ' , $words );
             
            $words  = trim( str_replace( ' ,' , ',' , ucwords( $words ) )  , ', ' );
            if( $commas )
            {
                //$words  = str_replace( ',' , ' and' , $words );
				$words  = str_replace( ',' , ' ' , $words );
            }
             
            return $words;
        }
        else if( ! ( ( int ) $num ) )
        {
            return 'Zero';
        }
        return '';
    }
}