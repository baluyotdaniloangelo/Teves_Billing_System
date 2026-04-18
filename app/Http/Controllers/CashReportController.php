<?php
namespace App\Http\Controllers;

ini_set('memory_limit', '1000M');

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;

use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P3;
use App\Models\CashiersReportModel_P2;
use App\Models\CashiersReportModel_P8;

use App\Models\ClientModel;
use Session;
use Validator;
//use DataTables;
use Illuminate\Support\Facades\DB;
/*Excel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

/*PDF*/
use PDF;
use DataTables;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CashReportController extends Controller
{
	
	/*Load Report Interface*/
	public function cash_report_page(){
		
		if(Session::has('loginID')){
			
			$title = 'Cash Report';
			$data = array();
			if(Session::has('loginID')){
				
				$data = User::where('user_id', '=', Session::get('loginID'))->first();
				
				if($data->user_branch_access_type=='ALL'){
					
					$teves_branch = TevesBranchModel::all();
				
				}else{
					
					$userID = Session::get('loginID');
					
					$teves_branch = TevesBranchModel::leftJoin('teves_user_branch_access', function($q) use ($userID)
					{
						$q->on('teves_branch_table.branch_id', '=', 'teves_user_branch_access.branch_idx');
					})
								
								->where('teves_user_branch_access.user_idx', '=', $userID)
								->get([
								'teves_branch_table.branch_id',
								'teves_user_branch_access.user_idx',
								'teves_user_branch_access.branch_idx',
								'teves_branch_table.branch_code',
								'teves_branch_table.branch_name'
								]);
					
				}	
				
			}

			return view("pages.non-cash_report", compact('data','title','teves_branch'));
		
		}
	}  	
	
	public function generate_non_cash_payment(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;
		$payment_type   = $request->payment_type;

		\DB::statement("SET SQL_MODE=''");

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS
			 =============================== */
			->where(function ($q) use ($current_user) {
				if (Session::get('user_branch_access_type') === "BYBRANCH") {
					$q->whereRaw(
						"teves_cashiers_report.teves_branch 
						 IN (SELECT branch_idx 
							 FROM teves_user_branch_access 
							 WHERE user_idx = ?)",
						[$current_user]
					);
				}
			})

			/* ===============================
			 | DATE FILTER (IMPORTANT FIX)
			 =============================== */
			->where('report_date', '>=', $start_date)
			->where('report_date', '<=', $end_date)
			
			/* ===============================
			 | BRANCH FILTER (ALL OPTION)
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			->join( // 🔥 INNER JOIN ON P8 (non-cash only)
				'teves_cashiers_report_p8',
				'teves_cashiers_report_p8.cashiers_report_idx',
				'=',
				'teves_cashiers_report.cashiers_report_id'
			)

			/* ===============================
			 | PAYMENT TYPE FILTER
			 =============================== */
			->when(($payment_type !== 'All'), function ($q) use ($payment_type) {
				$q->where('teves_cashiers_report_p8.payment_type', $payment_type);
			})

			/* ===============================
			 | GROUPING
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report_p8.payment_type'
			)

			/* ===============================
			 | SELECT
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				teves_cashiers_report_p8.payment_type,
				teves_cashiers_report_p8.payer_name,
				teves_cashiers_report_p8.payer_number,
				teves_cashiers_report_p8.reference_number,
				SUM(teves_cashiers_report_p8.payment_amount) AS total_amount
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();

		return DataTables::of($data)
			->addIndexColumn()
			->make(true);

	}	
	
	public function generate_non_cash_payment_report_pdf(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;
		$payment_type   = $request->payment_type;

		\DB::statement("SET SQL_MODE=''");

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS
			 =============================== */
			->where(function ($q) use ($current_user) {
				if (Session::get('user_branch_access_type') === "BYBRANCH") {
					$q->whereRaw(
						"teves_cashiers_report.teves_branch 
						 IN (SELECT branch_idx 
							 FROM teves_user_branch_access 
							 WHERE user_idx = ?)",
						[$current_user]
					);
				}
			})

			/* ===============================
			 | DATE FILTER (IMPORTANT FIX)
			 =============================== */
			//->whereDate('teves_cashiers_report.report_date', '>=', $start_date)
			//->whereDate('teves_cashiers_report.report_date', '<=', $end_date)
			->where('report_date', '>=', $start_date)
			->where('report_date', '<=', $end_date)
			
			/* ===============================
			 | BRANCH FILTER (ALL OPTION)
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			->join( // 🔥 INNER JOIN ON P8 (non-cash only)
				'teves_cashiers_report_p8',
				'teves_cashiers_report_p8.cashiers_report_idx',
				'=',
				'teves_cashiers_report.cashiers_report_id'
			)

			/* ===============================
			 | PAYMENT TYPE FILTER
			 =============================== */
			->when(($payment_type !== 'All'), function ($q) use ($payment_type) {
				$q->where('teves_cashiers_report_p8.payment_type', $payment_type);
			})

			/* ===============================
			 | GROUPING
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report_p8.payment_type'
			)

			/* ===============================
			 | SELECT
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				teves_cashiers_report_p8.payment_type,
				teves_cashiers_report_p8.payer_name,
				teves_cashiers_report_p8.payer_number,
				teves_cashiers_report_p8.reference_number,
				SUM(teves_cashiers_report_p8.payment_amount) AS total_amount
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();
			
		if($company_header !== 'All'){		
				$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}else{
				$receivable_header = TevesBranchModel::find(1, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Non Cash Payment';
		  
        $pdf = PDF::loadView('printables.report_non_cash_payment_report_pdf', compact('title', 'data', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('Legal', 'Portrait');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_NON_CASH_PAYMENT.pdf");
		
	}		

	public function generate_non_cash_payment_report_excel(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;
		$payment_type   = $request->payment_type;

		\DB::statement("SET SQL_MODE=''");

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS
			 =============================== */
			->where(function ($q) use ($current_user) {
				if (Session::get('user_branch_access_type') === "BYBRANCH") {
					$q->whereRaw(
						"teves_cashiers_report.teves_branch 
						 IN (SELECT branch_idx 
							 FROM teves_user_branch_access 
							 WHERE user_idx = ?)",
						[$current_user]
					);
				}
			})

			/* ===============================
			 | DATE FILTER (IMPORTANT FIX)
			 =============================== */
			->where('report_date', '>=', $start_date)
			->where('report_date', '<=', $end_date)
			
			/* ===============================
			 | BRANCH FILTER (ALL OPTION)
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			->join( // 🔥 INNER JOIN ON P8 (non-cash only)
				'teves_cashiers_report_p8',
				'teves_cashiers_report_p8.cashiers_report_idx',
				'=',
				'teves_cashiers_report.cashiers_report_id'
			)

			/* ===============================
			 | PAYMENT TYPE FILTER
			 =============================== */
			->when(($payment_type !== 'All'), function ($q) use ($payment_type) {
				$q->where('teves_cashiers_report_p8.payment_type', $payment_type);
			})

			/* ===============================
			 | GROUPING
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_cashiers_report_p8.payment_type'
			)

			/* ===============================
			 | SELECT
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				teves_cashiers_report_p8.payment_type,
				teves_cashiers_report_p8.payer_name,
				teves_cashiers_report_p8.payer_number,
				teves_cashiers_report_p8.reference_number,
				SUM(teves_cashiers_report_p8.payment_amount) AS total_amount
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();
		
		if($company_header !== 'All'){		
				$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}else{
				$receivable_header = TevesBranchModel::find(1, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Non Cash Payment';
		  
	   ini_set('max_execution_time', 0);
       // ini_set('memory_limit', '500M');
       try {
		    
			ob_start();
            $spreadSheet = new Spreadsheet();
           
            $spreadSheet = IOFactory::load(public_path('/template/Non-Cash_Template_1.xlsx'));
			
			$sheet = $spreadSheet->getActiveSheet();
		
			$logo = $receivable_header['branch_logo'];
			$branch_name = $receivable_header['branch_name'];
			$branch_address = $receivable_header['branch_address'];
			$branch_tin = $receivable_header['branch_tin'];
			$branch_owner = $receivable_header['branch_owner'];
			$branch_owner_title = $receivable_header['branch_owner_title'];
			
			$_period_start_date=date_create("$start_date");
			$period_start_date = strtoupper(date_format($_period_start_date,"M/d/Y"));
			
			$_period_end_date=date_create("$end_date");
			$period_end_date = strtoupper(date_format($_period_end_date,"M/d/Y"));

			$date_generated = strtoupper(date('M/d/Y'));
			
			$spreadSheet->getActiveSheet()
						->setCellValue('B7', $period_start_date." - ".$period_end_date);
			
			$spreadSheet->getActiveSheet()
						->setCellValue('B8', $date_generated);
			
			// full path ng image
			$logoPath = public_path('client_logo/' . $logo);
	
			$spreadSheet->getActiveSheet()
						->setCellValue('C1', $branch_name)
						->setCellValue('C2', $branch_address)
						->setCellValue('C3', 'VAT REG. TIN : '.$branch_tin)
						->setCellValue('C4', $branch_owner." - ".$branch_owner_title);
						
			// make sure merged
			$sheet->mergeCells('B1:B4');

			// set exact sizes
			$sheet->getColumnDimension('B')->setWidth(20);

			$totalHeight =
				$sheet->getRowDimension(1)->getRowHeight() +
				$sheet->getRowDimension(2)->getRowHeight() +
				$sheet->getRowDimension(3)->getRowHeight() +
				$sheet->getRowDimension(4)->getRowHeight();

			if (file_exists($logoPath)) {

				$drawing = new Drawing();
				$drawing->setPath($logoPath);

				$drawing->setCoordinates('B1');

				// 🔥 CRITICAL FIXES
				$drawing->setOffsetX(0);
				$drawing->setOffsetY(0);

				// 🔥 MATCH HEIGHT ONLY
				$drawing->setHeight($totalHeight);

				$drawing->setResizeProportional(true);

				$drawing->setWorksheet($sheet);
				
			}
			
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
		
				$style_RIGHT = array(
				'alignment' => array(
					'horizontal' => Alignment::HORIZONTAL_RIGHT,));			
					
					$no_excl = 11;
					$n = 1;

				$styleBorder = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => ['rgb' => '000000'],
						],
					],
				];
				
			foreach ($data as $non_cash_data_column){

					$_report_date			= date_create($non_cash_data_column['report_date']);
					$report_date 			= strtoupper(date_format($_report_date,"M/d/Y"));
	 
					$shift 					= $non_cash_data_column['shift'];
					$branch_initial 		= $non_cash_data_column['branch_initial']; 
					$cashiers_name 			= $non_cash_data_column['cashiers_name'];
					$forecourt_attendant 	= $non_cash_data_column['forecourt_attendant'];
					$encoder_name 			= $non_cash_data_column['encoder_name'];
					
					$payment_type 			= $non_cash_data_column['payment_type'];
					
					$payer_name 			= $non_cash_data_column['payer_name'];
					$payer_number 			= $non_cash_data_column['payer_number'];
					$reference_number 		= $non_cash_data_column['reference_number'];
					
					$total_amount 			= $non_cash_data_column['total_amount'];					
							
					$spreadSheet->getActiveSheet()
						->setCellValue('A'.$no_excl, $no_excl)
						->setCellValue('B'.$no_excl, $report_date)
						->setCellValue('C'.$no_excl, $branch_initial)
						->setCellValue('D'.$no_excl, $shift)
						->setCellValue('G'.$no_excl, $payment_type)
						->setCellValue('H'.$no_excl, $payer_name)
						->setCellValue('I'.$no_excl, $payer_number)
						->setCellValue('J'.$no_excl, $reference_number);
					
					// merge cells
					$sheet->mergeCells('E'.$no_excl.':F'.$no_excl);
					$sheet->mergeCells('K'.$no_excl.':L'.$no_excl);

					$spreadSheet->getActiveSheet()
						->setCellValue('E'.$no_excl, $cashiers_name)
						->setCellValue('K'.$no_excl, $total_amount);
						
					$sheet->getStyle('K'.$no_excl)
					->getNumberFormat()
					->setFormatCode('#,##0.00');
	
					$sheet = $spreadSheet->getActiveSheet();

					// CENTER (B, C, D)
					$sheet->getStyle("B$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$sheet->getStyle("C$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$sheet->getStyle("D$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					// LEFT (E:F merged - cashier name)
					$sheet->getStyle("E$no_excl:F$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

					// LEFT (F specifically if needed override)
					$sheet->getStyle("F$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

					// RIGHT (G:H merged - amount)
					$sheet->getStyle("G$no_excl:H$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

					// OPTIONAL: vertical center for all
					$sheet->getStyle("B$no_excl:H$no_excl")
						->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
						
					$sheet->getStyle("A$no_excl:L$no_excl")->applyFromArray($styleBorder);
					
				/*Increment*/
				$no_excl++;
				$n++;
				
			} 
		
					$startRow = 11; // first data row
					$endRow = $no_excl - 1; // last data row
					$totalRow = $no_excl; // next row for TOTAL
					$signatureRow = $totalRow + 6; // 6 rows after total
					
					//$sheet->setAutoFilter("A10:H10");
					//$sheet->setAutoFilter("A$startRow:H$endRow");
					
					$sheet = $spreadSheet->getActiveSheet();
					$sheet->setCellValue("J$totalRow", 'Total:');
					
					$sheet->getStyle("J$totalRow")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
					
					$sheet->mergeCells("K$totalRow".':'."L$totalRow");
					
					$sheet = $spreadSheet->getActiveSheet();
					$sheet->setCellValue("K$totalRow", "=SUM(K$startRow:K$endRow)");	
					
					$sheet->getStyle("K$totalRow")
					
					->getNumberFormat()
					->setFormatCode('#,##0.00');
					
					$sheet->setCellValue("A$signatureRow", "Prepared by:");
					
					// merge again
					$sheet->mergeCells("B$signatureRow:C$signatureRow");
					
					$sheet->setCellValue("B$signatureRow", $user_data->user_real_name);
					
					$sheet->getStyle("B$signatureRow:C$signatureRow")->applyFromArray([
						'borders' => [
							'bottom' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								'color' => ['rgb' => '000000'],
							],
						],
					]);
			
					$positionRow = $signatureRow + 1;

					// merge again
					$sheet->mergeCells("B$positionRow:C$positionRow");

					// set position
					$sheet->setCellValue("B$positionRow", $user_data->user_job_title);

					// center name
					$sheet->getStyle("B$signatureRow:C$signatureRow")
						->getAlignment()
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					// center position
					$sheet->getStyle("B$positionRow:C$positionRow")
						->getAlignment()
						->setHorizontal(Alignment::HORIZONTAL_CENTER);
	
			// AFTER LOOP (dito mo ilalagay)
			$sheet = $spreadSheet->getActiveSheet();

			/* ===============================
			 | LOCK ALL CELLS
			 =============================== */
			$sheet->getStyle($sheet->calculateWorksheetDimension())
				->getProtection()
				->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

			/* ===============================
			 | ENABLE SHEET PROTECTION
			 =============================== */
			$sheet->getProtection()->setSheet(true);
			
			$sheet->getProtection()->setFormatColumns(true); // allow resize
			$sheet->getProtection()->setAutoFilter(true);    // allow filter
			
			$sheet->getProtection()->setSelectLockedCells(true);
			$sheet->getProtection()->setSelectUnlockedCells(true);

			$spreadSheet->getActiveSheet()->setShowGridlines(true);
			
			/* ===============================
			 | ALLOW COLUMN RESIZE ONLY
			 =============================== */
			$sheet->getProtection()->setSort(true);
			$sheet->getProtection()->setInsertRows(false);
			$sheet->getProtection()->setFormatCells(false);
			$sheet->getProtection()->setDeleteRows(false);

			// 🔥 allow resize column
			//$sheet->getProtection()->setFormatColumns(true);

			 // 🔥 THIS is what locks the logo
			$sheet->getProtection()->setObjects(true);

			/* ===============================
			 | OPTIONAL PASSWORD
			 =============================== */

			$sheet->getProtection()->setPassword('1234yyUU');	
		
			$Excel_writer = new Xlsx($spreadSheet);
		  
			$_server_time	=	date('Y_m_d_H_i_s');
			
			$report_name = "Non-cash Payment";
			$_report_name = str_replace(' ','%20',$report_name);
		  
		   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header("Content-Disposition: attachment;filename=$_report_name.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
           exit();
       
	   } catch (Exception $e) {
           return;
       }
		  
		
	}		

	public function generate_cash_drop(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS (OPTIMIZED)
			 =============================== */
			->when(Session::get('user_branch_access_type') === "BYBRANCH", function ($q) use ($current_user) {
				$q->whereIn('teves_cashiers_report.teves_branch', function ($sub) use ($current_user) {
					$sub->select('branch_idx')
						->from('teves_user_branch_access')
						->where('user_idx', $current_user);
				});
			})

			/* ===============================
			 | DATE FILTER (INDEX FRIENDLY)
			 =============================== */
			->whereBetween('teves_cashiers_report.report_date', [$start_date, $end_date])

			/* ===============================
			 | BRANCH FILTER
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS (KEEP ONLY NECESSARY)
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			/* ===============================
			 | FILTER (NO HAVING NEEDED)
			 =============================== */
			->where('teves_cashiers_report.cash_drop', '>', 0)

			/* ===============================
			 | GROUPING (MINIMAL)
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_branch_table.branch_initial',
				'teves_cashiers_report.cashiers_name',
				'teves_cashiers_report.forecourt_attendant',
				'user_tb.user_real_name'
			)

			/* ===============================
			 | SELECT (NO P5 JOIN!)
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				SUM(teves_cashiers_report.cash_drop) AS total_cash_drop
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();


			return DataTables::of($data)
				->addIndexColumn()
				->make(true);

	}	
	
	public function generate_cashdrop_report_pdf(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS (OPTIMIZED)
			 =============================== */
			->when(Session::get('user_branch_access_type') === "BYBRANCH", function ($q) use ($current_user) {
				$q->whereIn('teves_cashiers_report.teves_branch', function ($sub) use ($current_user) {
					$sub->select('branch_idx')
						->from('teves_user_branch_access')
						->where('user_idx', $current_user);
				});
			})

			/* ===============================
			 | DATE FILTER (INDEX FRIENDLY)
			 =============================== */
			->whereBetween('teves_cashiers_report.report_date', [$start_date, $end_date])

			/* ===============================
			 | BRANCH FILTER
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS (KEEP ONLY NECESSARY)
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			/* ===============================
			 | FILTER (NO HAVING NEEDED)
			 =============================== */
			->where('teves_cashiers_report.cash_drop', '>', 0)

			/* ===============================
			 | GROUPING (MINIMAL)
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_branch_table.branch_initial',
				'teves_cashiers_report.cashiers_name',
				'teves_cashiers_report.forecourt_attendant',
				'user_tb.user_real_name'
			)

			/* ===============================
			 | SELECT (NO P5 JOIN!)
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				SUM(teves_cashiers_report.cash_drop) AS total_cash_drop
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();
		
		if($company_header !== 'All'){		
				$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}else{
				$receivable_header = TevesBranchModel::find(1, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Cash Drop';
		  
        $pdf = PDF::loadView('printables.report_cash_drop_report_pdf', compact('title', 'data', 'user_data','receivable_header','start_date','end_date'));
		
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('Legal', 'Portrait');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_CASHDROP.pdf");
		
	}		

	public function generate_cashdrop_report_excel(Request $request){

		$request->validate([
			'start_date' => 'required|date',
			'end_date'   => 'required|date',
		]);

		$current_user   = Session::get('loginID');
		$start_date     = $request->start_date;
		$end_date       = $request->end_date;
		$company_header = $request->company_header;

		$data = CashiersReportModel::query()

			/* ===============================
			 | BRANCH ACCESS (OPTIMIZED)
			 =============================== */
			->when(Session::get('user_branch_access_type') === "BYBRANCH", function ($q) use ($current_user) {
				$q->whereIn('teves_cashiers_report.teves_branch', function ($sub) use ($current_user) {
					$sub->select('branch_idx')
						->from('teves_user_branch_access')
						->where('user_idx', $current_user);
				});
			})

			/* ===============================
			 | DATE FILTER (INDEX FRIENDLY)
			 =============================== */
			->whereBetween('teves_cashiers_report.report_date', [$start_date, $end_date])

			/* ===============================
			 | BRANCH FILTER
			 =============================== */
			->when($company_header !== 'All', function ($q) use ($company_header) {
				$q->where('teves_cashiers_report.teves_branch', $company_header);
			})

			->whereNull('teves_cashiers_report.deleted_at')

			/* ===============================
			 | JOINS (KEEP ONLY NECESSARY)
			 =============================== */
			->leftJoin('user_tb', function ($join) {
				$join->on('user_tb.user_id', '=', 'teves_cashiers_report.user_idx')
					 ->whereNull('user_tb.deleted_at');
			})

			->leftJoin('teves_branch_table', function ($join) {
				$join->on('teves_branch_table.branch_id', '=', 'teves_cashiers_report.teves_branch')
					 ->whereNull('teves_branch_table.deleted_at');
			})

			/* ===============================
			 | FILTER (NO HAVING NEEDED)
			 =============================== */
			->where('teves_cashiers_report.cash_drop', '>', 0)

			/* ===============================
			 | GROUPING (MINIMAL)
			 =============================== */
			->groupBy(
				'teves_cashiers_report.report_date',
				'teves_cashiers_report.shift',
				'teves_branch_table.branch_initial',
				'teves_cashiers_report.cashiers_name',
				'teves_cashiers_report.forecourt_attendant',
				'user_tb.user_real_name'
			)

			/* ===============================
			 | SELECT (NO P5 JOIN!)
			 =============================== */
			->selectRaw('
				teves_cashiers_report.report_date,
				teves_branch_table.branch_initial,
				teves_cashiers_report.shift,
				teves_cashiers_report.cashiers_name,
				teves_cashiers_report.forecourt_attendant,
				user_tb.user_real_name as encoder_name,
				SUM(teves_cashiers_report.cash_drop) AS total_cash_drop
			')

			->orderBy('teves_cashiers_report.report_date')
			->orderBy('teves_cashiers_report.shift')

			->get();
		
		if($company_header !== 'All'){		
				$receivable_header = TevesBranchModel::find($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}else{
			echo $company_header;
				$receivable_header = TevesBranchModel::find(1, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);
		}
		
		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Cash Drop';
		  
	   ini_set('max_execution_time', 0);
       // ini_set('memory_limit', '500M');
       try {
		    
			ob_start();
            $spreadSheet = new Spreadsheet();
           
            $spreadSheet = IOFactory::load(public_path('/template/Cash_Drop_Template_1.xlsx'));
			
			$sheet = $spreadSheet->getActiveSheet();
		
			$logo = $receivable_header['branch_logo'];
			$branch_name = $receivable_header['branch_name'];
			$branch_address = $receivable_header['branch_address'];
			$branch_tin = $receivable_header['branch_tin'];
			$branch_owner = $receivable_header['branch_owner'];
			$branch_owner_title = $receivable_header['branch_owner_title'];
			
			$_period_start_date=date_create("$start_date");
			$period_start_date = strtoupper(date_format($_period_start_date,"M/d/Y"));
			
			$_period_end_date=date_create("$end_date");
			$period_end_date = strtoupper(date_format($_period_end_date,"M/d/Y"));

			$date_generated = strtoupper(date('M/d/Y'));
			
			$spreadSheet->getActiveSheet()
						->setCellValue('B7', $period_start_date." - ".$period_end_date);
			
			$spreadSheet->getActiveSheet()
						->setCellValue('B8', $date_generated);
			
			// full path ng image
			$logoPath = public_path('client_logo/' . $logo);
	
			$spreadSheet->getActiveSheet()
						->setCellValue('C1', $branch_name)
						->setCellValue('C2', $branch_address)
						->setCellValue('C3', 'VAT REG. TIN : '.$branch_tin)
						->setCellValue('C4', $branch_owner." - ".$branch_owner_title);
						
			// make sure merged
			$sheet->mergeCells('B1:B4');

			// set exact sizes
			$sheet->getColumnDimension('B')->setWidth(20);

			$totalHeight =
				$sheet->getRowDimension(1)->getRowHeight() +
				$sheet->getRowDimension(2)->getRowHeight() +
				$sheet->getRowDimension(3)->getRowHeight() +
				$sheet->getRowDimension(4)->getRowHeight();

			if (file_exists($logoPath)) {

				$drawing = new Drawing();
				$drawing->setPath($logoPath);

				$drawing->setCoordinates('B1');

				// 🔥 CRITICAL FIXES
				$drawing->setOffsetX(0);
				$drawing->setOffsetY(0);

				// 🔥 MATCH HEIGHT ONLY
				$drawing->setHeight($totalHeight);

				$drawing->setResizeProportional(true);

				$drawing->setWorksheet($sheet);
				
			}
			
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
		
				$style_RIGHT = array(
				'alignment' => array(
					'horizontal' => Alignment::HORIZONTAL_RIGHT,));			
					
					$no_excl = 11;
					$n = 1;

				$styleBorder = [
					'borders' => [
						'allBorders' => [
							'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
							'color' => ['rgb' => '000000'],
						],
					],
				];
				
			foreach ($data as $cash_drop_data_column){

					$_report_date			= date_create($cash_drop_data_column['report_date']);
					$report_date 			= strtoupper(date_format($_report_date,"M/d/Y"));
	 
					$shift 					= $cash_drop_data_column['shift'];
					$branch_initial 		= $cash_drop_data_column['branch_initial']; 
					$cashiers_name 			= $cash_drop_data_column['cashiers_name'];
					$forecourt_attendant 	= $cash_drop_data_column['forecourt_attendant'];
					$encoder_name 			= $cash_drop_data_column['encoder_name'];
					$total_cash_drop 		= $cash_drop_data_column['total_cash_drop'];
							
					$spreadSheet->getActiveSheet()
						->setCellValue('A'.$no_excl, $no_excl)
						->setCellValue('B'.$no_excl, $report_date)
						->setCellValue('C'.$no_excl, $branch_initial)
						->setCellValue('D'.$no_excl, $shift);
					
					// merge cells
					$sheet->mergeCells('E'.$no_excl.':F'.$no_excl);
					$sheet->mergeCells('G'.$no_excl.':H'.$no_excl);

					$spreadSheet->getActiveSheet()
						->setCellValue('E'.$no_excl, $cashiers_name)
						->setCellValue('G'.$no_excl, $total_cash_drop);
						
					$sheet->getStyle('G'.$no_excl)
					->getNumberFormat()
					->setFormatCode('#,##0.00');
	
					$sheet = $spreadSheet->getActiveSheet();

					// CENTER (B, C, D)
					$sheet->getStyle("B$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$sheet->getStyle("C$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					$sheet->getStyle("D$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

					// LEFT (E:F merged - cashier name)
					$sheet->getStyle("E$no_excl:F$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

					// LEFT (F specifically if needed override)
					$sheet->getStyle("F$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

					// RIGHT (G:H merged - amount)
					$sheet->getStyle("G$no_excl:H$no_excl")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

					// OPTIONAL: vertical center for all
					$sheet->getStyle("B$no_excl:H$no_excl")
						->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
						
					$sheet->getStyle("A$no_excl:H$no_excl")->applyFromArray($styleBorder);
					
				/*Increment*/
				$no_excl++;
				$n++;
				
			} 
		
					$startRow = 11; // first data row
					$endRow = $no_excl - 1; // last data row
					$totalRow = $no_excl; // next row for TOTAL
					$signatureRow = $totalRow + 6; // 6 rows after total
					
					//$sheet->setAutoFilter("A10:H10");
					//$sheet->setAutoFilter("A$startRow:H$endRow");
					
					$sheet = $spreadSheet->getActiveSheet();
					$sheet->setCellValue("F$totalRow", 'Total:');
					
					$sheet->getStyle("F$totalRow")
						->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
					
					$sheet->mergeCells("G$totalRow".':'."H$totalRow");
					
					$sheet = $spreadSheet->getActiveSheet();
					$sheet->setCellValue("G$totalRow", "=SUM(G$startRow:G$endRow)");	
					
					$sheet->getStyle("G$totalRow")
					
					->getNumberFormat()
					->setFormatCode('#,##0.00');
					
					$sheet->setCellValue("A$signatureRow", "Prepared by:");
					
					// merge again
					$sheet->mergeCells("B$signatureRow:C$signatureRow");
					
					$sheet->setCellValue("B$signatureRow", $user_data->user_real_name);
					
					$sheet->getStyle("B$signatureRow:C$signatureRow")->applyFromArray([
						'borders' => [
							'bottom' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								'color' => ['rgb' => '000000'],
							],
						],
					]);
			
					$positionRow = $signatureRow + 1;

					// merge again
					$sheet->mergeCells("B$positionRow:C$positionRow");

					// set position
					$sheet->setCellValue("B$positionRow", $user_data->user_job_title);

					// center name
					$sheet->getStyle("B$signatureRow:C$signatureRow")
						->getAlignment()
						->setHorizontal(Alignment::HORIZONTAL_CENTER);

					// center position
					$sheet->getStyle("B$positionRow:C$positionRow")
						->getAlignment()
						->setHorizontal(Alignment::HORIZONTAL_CENTER);
	
			// AFTER LOOP (dito mo ilalagay)
			$sheet = $spreadSheet->getActiveSheet();

			/* ===============================
			 | LOCK ALL CELLS
			 =============================== */
			$sheet->getStyle($sheet->calculateWorksheetDimension())
				->getProtection()
				->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_PROTECTED);

			/* ===============================
			 | ENABLE SHEET PROTECTION
			 =============================== */
			$sheet->getProtection()->setSheet(true);
			
			$sheet->getProtection()->setFormatColumns(true); // allow resize
			$sheet->getProtection()->setAutoFilter(true);    // allow filter

			/* ===============================
			 | ALLOW COLUMN RESIZE ONLY
			 =============================== */
			$sheet->getProtection()->setSort(true);
			$sheet->getProtection()->setInsertRows(false);
			$sheet->getProtection()->setFormatCells(false);
			$sheet->getProtection()->setDeleteRows(false);

			// 🔥 allow resize column
			//$sheet->getProtection()->setFormatColumns(true);

			 // 🔥 THIS is what locks the logo
			$sheet->getProtection()->setObjects(true);

			/* ===============================
			 | OPTIONAL PASSWORD
			 =============================== */

			$sheet->getProtection()->setPassword('1234yyUU');	
		
			$Excel_writer = new Xlsx($spreadSheet);
		  
			$_server_time	=	date('Y_m_d_H_i_s');
			
			$report_name = "Cash Drop";
			$_report_name = str_replace(' ','%20',$report_name);
		  
		   header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
           header("Content-Disposition: attachment;filename=$_report_name.xlsx");
           header('Cache-Control: max-age=0');
           ob_end_clean();
           $Excel_writer->save('php://output');
		   exit();
       
	   } catch (Exception $e) {
           return;
       }
		  
		
	}		

}