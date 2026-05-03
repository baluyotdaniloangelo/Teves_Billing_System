<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P9;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class CashiersReport_CashDeposit_Controller extends Controller
{
    /* ================= SAVE / UPDATE ================= */
	public function save_cash_deposit_cashiers_report_p9(Request $request)
	{
		$CRPH9_ID = $request->CRPH9_ID;

		/* ================= VALIDATION ================= */
		$rules = [
			'cash_deposit_bank'   => 'required|string',
			'cash_deposit_amount' => 'required|numeric|min:0',
			'cash_deposit_date'   => 'required|date',
			'cash_deposit_remarks'=> 'nullable|string',

			'cash_deposit_reference' => [
				'required',
				'string',
				Rule::unique('cashiers_report_p9', 'cash_deposit_reference')
					->ignore($CRPH9_ID, 'cashiers_report_p9_id')
			],
		];

		// Photo rules (required on create, optional on update)
		if (empty($CRPH9_ID) || $CRPH9_ID == 0) {
			$rules['cash_deposit_photo'] = 'required|image|mimes:jpg,jpeg,png|max:10048';
		} else {
			$rules['cash_deposit_photo'] = 'nullable|image|mimes:jpg,jpeg,png|max:10048';
		}

		$request->validate($rules, [
			'cash_deposit_bank.required'      => 'Bank is required',
			'cash_deposit_amount.required'    => 'Amount is required',
			'cash_deposit_date.required'      => 'Date is required',
			'cash_deposit_reference.required' => 'Reference is required',
			'cash_deposit_reference.unique'   => 'Reference already exists',
			'cash_deposit_photo.required'     => 'Photo is required',
		]);

		$CashiersReportId = $request->CashiersReportId;

		/* ================= CREATE ================= */
		if (empty($CRPH9_ID) || $CRPH9_ID == 0) {

			$data = new CashiersReportModel_P9();

			$data->cashiers_report_idx    = $CashiersReportId;
			$data->cash_deposit_bank      = $request->cash_deposit_bank;
			$data->cash_deposit_amount    = $request->cash_deposit_amount;
			$data->cash_deposit_date      = Carbon::parse($request->cash_deposit_date);
			$data->cash_deposit_remarks   = $request->cash_deposit_remarks;
			$data->cash_deposit_reference = $request->cash_deposit_reference;

			/* ===== FILE UPLOAD ===== */
			if ($request->hasFile('cash_deposit_photo')) {

				$file = $request->file('cash_deposit_photo');

				// sanitize reference
				$ref = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->cash_deposit_reference);

				$extension = $file->getClientOriginalExtension();

				$filename = 'deposit_' . $ref . '_' . now()->format('Ymd_His') . '_' . Str::random(4) . '.' . $extension;

				$path = $file->storeAs('cash_deposits', $filename, 'public');

				$data->cash_deposit_photo = $path;
			}

			$data->created_by_user_id = Session::get('loginID');

			$result = $data->save();

			return $result
				? response()->json(['success' => 'Cash Deposit Successfully Created!'])
				: response()->json(['error' => 'Error creating Cash Deposit'], 500);
		}

		/* ================= UPDATE ================= */
		$data = CashiersReportModel_P9::findOrFail($CRPH9_ID);

		$data->cash_deposit_bank       = $request->cash_deposit_bank;
		$data->cash_deposit_amount     = $request->cash_deposit_amount;
		$data->cash_deposit_date       = Carbon::parse($request->cash_deposit_date);
		$data->cash_deposit_remarks    = $request->cash_deposit_remarks;
		$data->cash_deposit_reference  = $request->cash_deposit_reference;

		/* ===== FILE REPLACEMENT (OPTIONAL) ===== */
		if ($request->hasFile('cash_deposit_photo')) {

			// delete old file
			if ($data->cash_deposit_photo && Storage::disk('public')->exists($data->cash_deposit_photo)) {
				Storage::disk('public')->delete($data->cash_deposit_photo);
			}

			$file = $request->file('cash_deposit_photo');

			$ref = preg_replace('/[^A-Za-z0-9\-]/', '_', $request->cash_deposit_reference);
			$extension = $file->getClientOriginalExtension();

			$filename = 'deposit_' . $ref . '_' . now()->format('Ymd_His') . '_' . Str::random(4) . '.' . $extension;

			$path = $file->storeAs('cash_deposits', $filename, 'public');

			$data->cash_deposit_photo = $path;
		}

		$data->updated_by_user_id = Session::get('loginID');

		$result = $data->save();

		return $result
			? response()->json(['success' => 'Cash Deposit Successfully Updated!'])
			: response()->json(['error' => 'Error updating Cash Deposit'], 500);
		
	}

	/* ================= LIST ================= */


	public function get_cash_deposit_list(Request $request)
	{
		if ($request->ajax()) {

			$data = CashiersReportModel_P9::where(
					'cashiers_report_idx',
					$request->CashiersReportId
				)
				->select(
					'cashiers_report_p9_id',
					'cash_deposit_bank',
					'cash_deposit_amount',
					'cash_deposit_date',
					'cash_deposit_remarks',
					'cash_deposit_reference',
					'cash_deposit_photo'
				);

			return DataTables::of($data)
				->addIndexColumn()

				->editColumn('cash_deposit_bank', function ($row) {
					return strtoupper(str_replace('_', ' ', $row->cash_deposit_bank));
				})

				->editColumn('cash_deposit_amount', function ($row) {
					return $row->cash_deposit_amount;
				})

				/*->addColumn('action', function ($row) {
					return '
						<div align="center" class="action_table_menu_Product">
							<a href="#" 
							   data-id="'.$row->cashiers_report_p9_id.'" 
							   class="btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit"
							   id="CRP9_Edit"></a>

							<a href="#" 
							   data-id="'.$row->cashiers_report_p9_id.'" 
							   class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete"
							   id="CRP9_Delete"></a>
						</div>
					';
				})*/
				->addColumn('photo', function ($row) {
					return $row->cash_deposit_photo;
				})
				->addColumn('action', function ($row) {
					return '
						<div align="center" class="action_table_menu_Product">

							<a href="#" 
							   data-id="'.$row->cashiers_report_p9_id.'"
							   data-photo="'.$row->cash_deposit_photo.'"
							   data-bank="'.$row->cash_deposit_bank.'"
							   data-amount="'.$row->cash_deposit_amount.'"
							   data-reference="'.$row->cash_deposit_reference.'"
							   data-date="'.$row->cash_deposit_date.'"
							   class="btn btn-primary btn-circle btn-sm bi-eye-fill CRP9_Preview btn_icon_table_preview"
							   id="CRP9_Preview"></a>

							<a href="#" 
							   data-id="'.$row->cashiers_report_p9_id.'" 
							   class="btn btn-warning btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit"
							   id="CRP9_Edit"></a>

							<a href="#" 
							   data-id="'.$row->cashiers_report_p9_id.'" 
							   class="btn btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete"
							   id="CRP9_Delete"></a>

						</div>
					';
				})

				->rawColumns(['action'])
				->make(true);
		}
	}

    /* ================= INFO ================= */
    public function cashiers_report_p9_info(Request $request)
    {
        $CRPH9_ID = $request->CRPH9_ID;

        $data = CashiersReportModel_P9::where(
                'cashiers_report_p9_id',
                $CRPH9_ID
            )
            ->get([
                'cash_deposit_bank',
                'cash_deposit_amount',
				'cash_deposit_date',
				'cash_deposit_remarks',
				'cash_deposit_reference',
				'cash_deposit_photo'
            ]);

        return response()->json($data);
    }

    /* ================= DELETE ================= */
	public function delete_cash_deposit_report(Request $request)
	{
		$CRPH9_ID = $request->CRPH9_ID;

		$data = CashiersReportModel_P9::findOrFail($CRPH9_ID);

		// 🔥 delete file if exists
		if ($data->cash_deposit_photo && 
			Storage::disk('public')->exists($data->cash_deposit_photo)) {

			Storage::disk('public')->delete($data->cash_deposit_photo);
		}

		// delete record
		$data->delete();

		return response()->json(['success' => 'Deleted']);
	}

}
