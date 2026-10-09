<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_LPG_AR;
use App\Models\BankModel;

use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
/*PDF*/
use PDF;
use Illuminate\Validation\Rule;

use Carbon\Carbon;

class CashiersReport_LPG_AR_Controller extends Controller
{


	public function get_lpg_ar_list(Request $request)
	{
		$data = CashiersReportModel_LPG_AR::query()
			->leftJoin(
				'teves_client_table',
				'teves_client_table.client_id',
				'=',
				'teves_cashiers_report_lpg_ar.client_idx'
			)
			->where(
				'teves_cashiers_report_lpg_ar.cashiers_report_id',
				$request->CashiersReportId
			)
			->whereNull('teves_cashiers_report_lpg_ar.deleted_at')
			->orderBy(
				'teves_cashiers_report_lpg_ar.cashiers_report_lpg_ar_id',
				'asc'
			)
			->get([
				'teves_cashiers_report_lpg_ar.cashiers_report_lpg_ar_id',
				'teves_cashiers_report_lpg_ar.cashiers_report_id',
				'teves_cashiers_report_lpg_ar.ar_date',
				'teves_cashiers_report_lpg_ar.client_idx',
				'teves_client_table.client_name',
				'teves_cashiers_report_lpg_ar.dr_number',
				'teves_cashiers_report_lpg_ar.remarks',
				'teves_cashiers_report_lpg_ar.amount_received',
			]);

		return response()->json($data);
	}

	public function cashiers_report_lpg_ar_info(Request $request)
	{
		$arId = $request->cashiers_report_lpg_ar_id;

		$data = CashiersReportModel_LPG_AR::query()
			->leftJoin(
				'teves_client_table',
				'teves_client_table.client_id',
				'=',
				'teves_cashiers_report_lpg_ar.client_idx'
			)
			->where(
				'teves_cashiers_report_lpg_ar.cashiers_report_lpg_ar_id',
				$arId
			)
			->whereNull('teves_cashiers_report_lpg_ar.deleted_at')
			->get([
				'teves_cashiers_report_lpg_ar.cashiers_report_lpg_ar_id',
				'teves_cashiers_report_lpg_ar.cashiers_report_id',
				'teves_cashiers_report_lpg_ar.ar_date',
				'teves_cashiers_report_lpg_ar.client_idx',
				'teves_client_table.client_name',
				'teves_cashiers_report_lpg_ar.dr_number',
				'teves_cashiers_report_lpg_ar.remarks',
				'teves_cashiers_report_lpg_ar.amount_received',
			]);

		return response()->json($data);
	}
		
	
	public function save_lpg_ar(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'cashiers_report_id' => ['required', 'integer'],
			'ar_date'            => ['nullable', 'date'],
			'client_idx'         => ['required', 'integer', 'exists:teves_client_table,client_id'],
			'dr_number'          => ['required', 'string', 'max:100'],
			'remarks'            => ['nullable', 'string'],
			'amount_received'    => ['required', 'numeric', 'min:0'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'status' => false,
				'errors' => $validator->errors(),
			], 422);
		}

		$data = $validator->validated();
		$now = now();
		$userId = Session::get('loginID', 0);

		$ar = new CashiersReportModel_LPG_AR();
		$ar->cashiers_report_id = $data['cashiers_report_id'];
		//$ar->ar_date = $data['ar_date'];
		$ar->ar_date = now();
		$ar->client_idx = $data['client_idx'];
		$ar->dr_number = $data['dr_number'];
		$ar->remarks = $data['remarks'] ?? null;
		$ar->amount_received = $data['amount_received'];
		$ar->created_at = $now;
		$ar->created_by_user_idx = $userId;
		$ar->updated_at = $now;
		$ar->updated_by_user_idx = $userId;
		$ar->save();

		return response()->json([
			'status' => true,
			'message' => 'AR collection saved successfully.',
			'data' => $ar,
		]);
	}

	public function update_lpg_ar(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'cashiers_report_lpg_ar_id' => ['required', 'integer'],
			'cashiers_report_id'        => ['required', 'integer'],
			'ar_date'                   => ['nullable', 'date'],
			'client_idx'                => ['required', 'integer', 'exists:teves_client_table,client_id'],
			'dr_number'                 => ['required', 'string', 'max:100'],
			'remarks'                   => ['nullable', 'string'],
			'amount_received'           => ['required', 'numeric', 'min:0'],
		]);

		if ($validator->fails()) {
			return response()->json([
				'status' => false,
				'errors' => $validator->errors(),
			], 422);
		}

		$data = $validator->validated();

		$ar = CashiersReportModel_LPG_AR::where(
				'cashiers_report_lpg_ar_id',
				$data['cashiers_report_lpg_ar_id']
			)
			->where('cashiers_report_id', $data['cashiers_report_id'])
			->firstOrFail();
		
		$ar->client_idx = $data['client_idx'];
		$ar->dr_number = $data['dr_number'];
		$ar->remarks = $data['remarks'] ?? null;
		$ar->amount_received = $data['amount_received'];
		$ar->updated_at = now();
		$ar->updated_by_user_idx = Session::get('loginID', 0);
		$ar->save();

		return response()->json([
			'status' => true,
			'message' => 'AR collection updated successfully.',
			'data' => $ar,
		]);
	}	
	
	
	
	public function delete_lpg_ar (Request $request){		
			
		$arId = $request->cashiers_report_lpg_ar_id;

		/*Delete from Cashiers Report*/
		CashiersReportModel_LPG_AR::find($arId)->delete();
					
		return 'Deleted';
		
	}
											
}