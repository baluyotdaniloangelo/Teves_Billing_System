<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P8;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

class CashiersReport_Payment_Controller extends Controller
{
    /* ================= SAVE / UPDATE ================= */
    public function save_cash_payment_cashiers_report_p8(Request $request)
    {
        $request->validate(
            [
                'payment_type'   => 'required|string',
                'payment_amount' => 'required|numeric|min:0'
            ],
            [
                'payment_type.required'   => 'Payment Type is required',
                'payment_amount.required' => 'Payment Amount is required'
            ]
        );

        $CashiersReportId = $request->CashiersReportId;
        $CRPH8_ID         = $request->CRPH8_ID;

        /* ============ CREATE ============ */
        if (empty($CRPH8_ID) || $CRPH8_ID == 0) {

            $CashiersReportModel_P8 = new CashiersReportModel_P8();

            $CashiersReportModel_P8->user_idx               = Session::get('loginID');
            $CashiersReportModel_P8->cashiers_report_idx    = $CashiersReportId;
            $CashiersReportModel_P8->payment_type           = $request->payment_type;
            $CashiersReportModel_P8->payment_amount         = $request->payment_amount;
            $CashiersReportModel_P8->created_by_user_id     = Session::get('loginID');

            $result = $CashiersReportModel_P8->save();

            return $result
                ? response()->json(['success' => 'Cash Payment Successfully Created!'])
                : response()->json(['error' => 'Error creating Cash Payment'], 500);
        }

        /* ============ UPDATE ============ */
        $CashiersReportModel_P8 = CashiersReportModel_P8::findOrFail($CRPH8_ID);

        $CashiersReportModel_P8->payment_type       = $request->payment_type;
        $CashiersReportModel_P8->payment_amount     = $request->payment_amount;
        $CashiersReportModel_P8->updated_by_user_id = Session::get('loginID');

        $result = $CashiersReportModel_P8->update();

        return $result
            ? response()->json(['success' => 'Cash Payment Successfully Updated!'])
            : response()->json(['error' => 'Error updating Cash Payment'], 500);
    }

    /* ================= LIST ================= 
    public function get_cash_payment_inventory_list(Request $request)
    {
        $data = CashiersReportModel_P8::where(
                'cashiers_report_idx',
                $request->CashiersReportId
            )
            ->orderBy('cashiers_report_p8_id', 'asc')
            ->get([
                'cashiers_report_p8_id',
                'payment_type',
                'payment_amount'
            ]);

        return response()->json($data);
    }*/
//use Yajra\DataTables\DataTables;

public function get_cash_payment_inventory_list(Request $request)
{
    if ($request->ajax()) {

        $data = CashiersReportModel_P8::where(
                'cashiers_report_idx',
                $request->CashiersReportId
            )
            ->select(
                'cashiers_report_p8_id',
                'payment_type',
                'payment_amount'
            );

        return DataTables::of($data)
            ->addIndexColumn()

            ->editColumn('payment_type', function ($row) {
                return strtoupper(str_replace('_', ' ', $row->payment_type));
            })

            ->editColumn('payment_amount', function ($row) {
                return $row->payment_amount;
            })

            ->addColumn('action', function ($row) {
                return '
                    <div align="center" class="action_table_menu_Product">
                        <a href="#" 
                           data-id="'.$row->cashiers_report_p8_id.'" 
                           class="btn-danger btn-circle btn-sm bi-pencil-fill btn_icon_table btn_icon_table_edit"
                           id="CHPH8_Edit"></a>

                        <a href="#" 
                           data-id="'.$row->cashiers_report_p8_id.'" 
                           class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete"
                           id="CHPH8_Delete"></a>
                    </div>
                ';
            })

            ->rawColumns(['action'])
            ->make(true);
    }
}

    /* ================= INFO ================= */
    public function cashiers_report_p8_info(Request $request)
    {
        $CRPH8_ID = $request->CRPH8_ID;

        $data = CashiersReportModel_P8::where(
                'cashiers_report_p8_id',
                $CRPH8_ID
            )
            ->get([
                'payment_type',
                'payment_amount'
            ]);

        return response()->json($data);
    }

    /* ================= DELETE ================= */
    public function delete_cash_payment_report(Request $request)
    {
        $CRPH8_ID = $request->CRPH8_ID;

        CashiersReportModel_P8::findOrFail($CRPH8_ID)->delete();

        return response()->json(['success' => 'Deleted']);
    }
}
