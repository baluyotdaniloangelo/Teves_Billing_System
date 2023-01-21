<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BillingTransactionModel;
use App\Models\ReceivablesModel;
/*use App\Models\ProductModel;*/
use App\Models\SalesOrderModel;
use App\Models\SalesOrderComponentModel;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;
/*Excel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

/*PDF*/
use PDF;

class ReportController extends Controller
{
	/*Load Report Interface*/
	public function report(){
		
		$title = 'Billing Statement';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$client_data = ClientModel::all();
		
		}

		return view("pages.report", compact('data','title','client_data'));
		
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
		
		$data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
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
		$client_address = $client_data['client_address'];
		
		
		$_po_start_date=date_create("$start_date");
		$po_start_date = date_format($_po_start_date,"m/d/y");
			
		$_po_end_date=date_create("$end_date");
		$po_end_date = date_format($_po_end_date,"m/d/y");
			
	   ini_set('max_execution_time', 0);
       ini_set('memory_limit', '4000M');
       try {
		   
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
							'color' => array('argb' => '000000'),
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
					
			/*Increment*/
			$no_excl++;
			$n++;
			} 
			
			$spreadSheet->getActiveSheet()->getStyle("J".$no_excl.":K".$no_excl)->getFont()->setBold(true);
			$spreadSheet->getActiveSheet()
					->setCellValue('J'.$no_excl, "Total Payable:")
					->setCellValue('K'.$no_excl, "=SUM(K11:K".($no_excl-1).")");
			
			/*USER INFO*/
			$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
			$spreadSheet->getActiveSheet()
					->setCellValue('A'.($no_excl+4), "Prepared by:")
					->setCellValue('B'.($no_excl+4), $user_data['user_real_name']);
			$spreadSheet->getActiveSheet()->getStyle('B'.($no_excl+4))->applyFromArray($styleBorder_prepared);
			
			$spreadSheet->getActiveSheet()
					->setCellValue('B'.($no_excl+5), $user_data['user_job_title']);
			
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
			->getStyle("H11:H$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("I11:I$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("J11:J$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			
			$spreadSheet->getActiveSheet()
			->getStyle("K11:K$no_excl")
			->getAlignment()
			->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			/*
			foreach(range('A','K') as $columnID) {
				$spreadSheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			*/
		  $Excel_writer = new Xlsx($spreadSheet);
           header('Content-Type: application/vnd.ms-excel');
           header("Content-Disposition: attachment;filename=".$client_name." - Billing Statement.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       
	   } catch (Exception $e) {
           return;
       }
	   
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
		
		$billing_data = BillingTransactionModel::where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
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
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		/*Client Information*/
		$client_data = ClientModel::find($client_idx, ['client_name','client_address']);
          
		$title = 'Billing Statement';
		  
        $pdf = PDF::loadView('pages.report_billing_pdf', compact('title', 'client_data', 'user_data', 'billing_data', 'start_date', 'end_date', 'less_per_liter'));
		
		/*Download Directly*/
        /*return $pdf->download($client_data['client_name'].".pdf");*/
		/*Stream for Saving/Printing*/
		$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		$pdf->render();
		return $pdf->stream($client_data['client_name'].".pdf");
		//return view('pages.report_billing_pdf', compact('title', 'client_data', 'user_data', 'billing_data', 'start_date', 'end_date'));
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
					'teves_receivable_table.payment_term',
					'teves_receivable_table.receivable_description',
					'teves_receivable_table.receivable_amount'
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
					'teves_sales_order_table.sales_order_mode_of_payment',
					'teves_sales_order_table.sales_order_date_of_payment',
					'teves_sales_order_table.sales_order_reference_no',
					'teves_sales_order_table.sales_order_payment_amount'
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
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		//$pdf->setPaper('A4', 'landscape');/*Set to Landscape*/
		return $pdf->stream($sales_order_data[0]['client_name']."_SALES_ORDER.pdf");
		//return view('pages.report_sales_order_pdf', compact('title', 'sales_order_data', 'user_data', 'amount_in_words', 'sales_order_component'));
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