<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesOrderModel;
use App\Models\ProductModel;
use App\Models\ClientModel;
use App\Models\TevesBranchModel;
use App\Models\SalesOrderComponentModel;
use App\Models\SalesOrderDeliveryModel;
use App\Models\ReceivablesModel;
use App\Models\ReceivablesPaymentModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

use App\Models\ProductCategoryModel;

/*PDF*/
use PDF;
class SalesOrderController extends Controller
{
	
	/*Load Product Interface*/
	public function salesorder(){
		
		$title = 'Ship To';
		$data = array();
		if(Session::has('loginID')){
			
			$client_data = ClientModel::all();
			$product_data = ProductModel::all();
			$product_category_data = ProductCategoryModel::all();
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
			
			$salesOrderScope = SalesOrderModel::query()->whereNull('deleted_at');
			if ($data->user_branch_access_type !== 'ALL') {
				$salesOrderScope->whereIn('company_header', function ($access) {
					$access->select('branch_idx')
						->from('teves_user_branch_access')
						->where('user_idx', Session::get('loginID'));
				});
			}
			$salesOrderCounts = [
				'orders' => (clone $salesOrderScope)->where('sales_order_delivery_status', 'Pending')
					->where(function ($query) {
						$query->where('sales_order_quotation', '0')->orWhereNull('sales_order_quotation');
					})->count(),
				'quotations' => (clone $salesOrderScope)->where('sales_order_quotation', '<>', '0')->count(),
				'delivered' => (clone $salesOrderScope)->where('sales_order_delivery_status', '<>', 'Pending')->count(),
			];
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
		
		}

		return view("pages.sales_order_v2", compact('data','title','client_data','product_data','sales_order_delivered_to','sales_order_delivered_to_address','teves_branch','product_category_data','salesOrderCounts'));
		
	}   
	
	/*Fetch Sales Order List using Datatable*/
	public function getSalesOrderList(Request $request)
    {
		
		if(Session::has('loginID')){
			
			$current_user = Session::get('loginID');
		
		if ($request->ajax()) {

			$data = SalesOrderModel::where('sales_order_delivery_status', '=', 'Pending')
					->where(function ($quotation) {
						$quotation->where('sales_order_quotation', '=', '0')
							->orWhereNull('sales_order_quotation');
					})
					->whereNull('teves_sales_order_table.deleted_at') 
					->join('teves_client_table', function ($join) {
							$join->on('teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
								 ->whereNull('teves_client_table.deleted_at'); // Filter soft-deleted products
						})
					->leftjoin('teves_receivable_table', function ($join) {
							$join->on('teves_receivable_table.sales_order_idx', '=', 'teves_sales_order_table.sales_order_id')
								 ->whereNull('teves_receivable_table.deleted_at'); // Filter soft-deleted products
						})
					->WHERE(function ($r) use($current_user) {
							if (Session::get('user_branch_access_type')=="BYBRANCH") {
									$r->whereRaw("teves_sales_order_table.company_header IN (SELECT branch_idx FROM teves_user_branch_access WHERE user_idx=?)", $current_user);
							}
						})	
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.category_idx',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					DB::raw("IFNULL(teves_receivable_table.receivable_lock_status, '00000') AS receivable_lock_status"),	
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_quotation_reference',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_status',
					'teves_sales_order_table.sales_order_delivery_status',
					'teves_sales_order_table.sales_order_quotation',
					'teves_sales_order_table.sales_order_quotation_status',
					'teves_sales_order_table.created_at']);

		return DataTables::of($data)
				->addIndexColumn()
				->addColumn('sales_order_type', function($row){
						return $row->referrer->category_name ?? 'None';
					})
                ->addColumn('action', function($row){
					
						$startTimeStamp = strtotime($row->created_at);
						$endTimeStamp = strtotime(date('y-m-d'));
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;  // 86400 seconds in one day
						// and you might want to convert to integer
						$numberDays = intval($numberDays);
					
						$receivable_lock_status = $row->receivable_lock_status;
						$receivable_lock_items = str_split((string)$receivable_lock_status);
						$lock_billing_information 		= $receivable_lock_items[0];
						
						if($lock_billing_information!=1){
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" data-control-number="'.$row->sales_order_control_number.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder" title="Edit Sales Order"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-info btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
						
						
						}else{
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product"><a href="#" data-id="'.$row->sales_order_id.'" class="btn-info btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';	
							
						}
						
							if(Session::get('UserType')=="SUAdmin"){
										return $actionBtn;
							}
							if(Session::get('UserType')=="Admin"){
										return $actionBtn;
							}
							else if(Session::get('UserType')=="Supervisor"){
										return $actionBtn_view_only;/*View and Print Only*/
							}
							else if(Session::get('UserType')=="Accounting_Staff"){
										
										/*Access within 24 Hrs*/
										if($numberDays>=1){
											return $actionBtn_view_only;
										}else{
											return $actionBtn;/*View and Print Only*/
										}
										
							}else{
										return '';/*View and Print Only*/
							}
					
                })
				->rawColumns(['action'])
                ->make(true);
		}		
		}
    }
	
	public function getSalesOrderList_quotation(Request $request)
    {
		
		if(Session::has('loginID')){
			
			$current_user = Session::get('loginID');
		
		if ($request->ajax()) {

			$data = SalesOrderModel::where('sales_order_quotation', '<>', '0')
					->whereNull('teves_sales_order_table.deleted_at') 
					->join('teves_client_table', function ($join) {
							$join->on('teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
								 ->whereNull('teves_client_table.deleted_at'); // Filter soft-deleted products
						})
					->leftjoin('teves_receivable_table', function ($join) {
							$join->on('teves_receivable_table.sales_order_idx', '=', 'teves_sales_order_table.sales_order_id')
								 ->whereNull('teves_receivable_table.deleted_at'); // Filter soft-deleted products
						})
					->WHERE(function ($r) use($current_user) {
							if (Session::get('user_branch_access_type')=="BYBRANCH") {
									$r->whereRaw("teves_sales_order_table.company_header IN (SELECT branch_idx FROM teves_user_branch_access WHERE user_idx=?)", $current_user);
							}
						})	
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.category_idx',
					'teves_client_table.client_name',
					DB::raw("IFNULL(teves_receivable_table.receivable_lock_status, '00000') AS receivable_lock_status"),	
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_quotation_reference',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_status',
					'teves_sales_order_table.sales_order_delivery_status',
					'teves_sales_order_table.sales_order_quotation',
					'teves_sales_order_table.sales_order_quotation_status',
					'teves_sales_order_table.created_at']);

		return DataTables::of($data)
				->addIndexColumn()
				->addColumn('sales_order_type', function($row){
						return $row->referrer->category_name ?? 'None';
					})
                ->addColumn('action', function($row){
					
						$startTimeStamp = strtotime($row->created_at);
						$endTimeStamp = strtotime(date('y-m-d'));
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;  // 86400 seconds in one day
						// and you might want to convert to integer
						$numberDays = intval($numberDays);
					
						$receivable_lock_status = $row->receivable_lock_status;
						$receivable_lock_items = str_split((string)$receivable_lock_status);
						$lock_billing_information 		= $receivable_lock_items[0];
						
						$quotationStatus = $row->sales_order_quotation_status ?: 'Pending';
						$decisionButtons = '<a href="#" data-id="'.$row->sales_order_id.'" class="so-action so-action-approve" id="approveSalesOrder" title="Approve quotation"><i class="bi bi-check2" aria-hidden="true"></i> Approve</a>';
						if ($quotationStatus !== 'Rejected') {
							$decisionButtons .= '<a href="#" data-id="'.$row->sales_order_id.'" class="so-action so-action-reject" id="disapproveSalesOrder" title="Reject quotation"><i class="bi bi-x-lg" aria-hidden="true"></i> Reject</a>';
						}
						
						
						if($lock_billing_information!=1){
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						'.$decisionButtons.'
						<a href="#" data-id="'.$row->sales_order_id.'" data-control-number="'.$row->sales_order_control_number.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder" title="Edit Sales Order"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder"></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
						
						
						}else{
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';	
							
						}
						
							if(Session::get('UserType')=="SUAdmin"){
										return $actionBtn;
							}
							if(Session::get('UserType')=="Admin"){
										return $actionBtn;
							}
							else if(Session::get('UserType')=="Supervisor"){
										return $actionBtn_view_only;/*View and Print Only*/
							}
							else if(Session::get('UserType')=="Accounting_Staff"){
										
										/*Access within 24 Hrs*/
										if($numberDays>=1){
											return $actionBtn_view_only;
										}else{
											return $actionBtn;/*View and Print Only*/
										}
										
							}else{
										return '';/*View and Print Only*/
							}
					
                })
				->rawColumns(['action'])
                ->make(true);
		}		
		}
    }

	/*Fetch Delivered Sales Order List using Datatable*/
	public function getSalesOrderList_delivered(Request $request)
    {
		
		if(Session::has('loginID')){
			
			$current_user = Session::get('loginID');
		
		if ($request->ajax()) {
			
			$data = SalesOrderModel::where('sales_order_delivery_status','<>', 'Pending')
					->whereNull('teves_sales_order_table.deleted_at') 
					->join('teves_client_table', function ($join) {
							$join->on('teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
								 ->whereNull('teves_client_table.deleted_at'); // Filter soft-deleted products
						})
					->leftjoin('teves_receivable_table', function ($join) {
							$join->on('teves_receivable_table.sales_order_idx', '=', 'teves_sales_order_table.sales_order_id')
								 ->whereNull('teves_receivable_table.deleted_at'); // Filter soft-deleted products
						})
					->WHERE(function ($r) use($current_user) {
							if (Session::get('user_branch_access_type')=="BYBRANCH") {
									$r->whereRaw("teves_sales_order_table.company_header IN (SELECT branch_idx FROM teves_user_branch_access WHERE user_idx=?)", $current_user);
							}
						})	
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.category_idx',
					'teves_client_table.client_name',
					DB::raw("IFNULL(teves_receivable_table.receivable_lock_status, '00000') AS receivable_lock_status"),	
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_quotation_reference',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_status',
					'teves_sales_order_table.sales_order_delivery_status',
					'teves_sales_order_table.created_at']);
	
		return DataTables::of($data)
				->addIndexColumn()
				->addColumn('sales_order_type', function($row){
						return $row->referrer->category_name ?? 'None';
					})
                ->addColumn('action', function($row){
					
						$startTimeStamp = strtotime($row->created_at);
						$endTimeStamp = strtotime(date('y-m-d'));
						$timeDiff = abs($endTimeStamp - $startTimeStamp);
						$numberDays = $timeDiff/86400;  // 86400 seconds in one day
						// and you might want to convert to integer
						$numberDays = intval($numberDays);
					
						$receivable_lock_status = $row->receivable_lock_status;
						$receivable_lock_items = str_split((string)$receivable_lock_status);
						$lock_billing_information 		= $receivable_lock_items[0];
						
						if($lock_billing_information!=1){
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						<a href="#" data-id="'.$row->sales_order_id.'" data-control-number="'.$row->sales_order_control_number.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditSalesOrder" title="Edit Sales Order"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteSalesOrder"></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product"><a href="#" data-id="'.$row->sales_order_id.'" class="btn-info btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
						
						
						}else{
							
						$actionBtn = '
						<div align="center" class="action_table_menu_Product">
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';
					
						$actionBtn_view_only = '
						<div align="center" class="action_table_menu_Product"><a href="#" data-id="'.$row->sales_order_id.'" class="btn-info btn-circle btn-sm bi bi-eye-fill btn_icon_table btn_icon_table_gallery" id="SalesOrderPreview"></a>
						<a href="#" data-id="'.$row->sales_order_id.'" class="btn-warning btn-circle btn-sm bi bi-printer-fill btn_icon_table btn_icon_table_view" id="PrintSalesOrder""></a>
						</div>';	
							
							
						}
						
					
							if(Session::get('UserType')=="SUAdmin"){
										return $actionBtn;
							}
							if(Session::get('UserType')=="Admin"){
										return $actionBtn;
							}
							else if(Session::get('UserType')=="Supervisor"){
										return $actionBtn_view_only;/*View and Print Only*/
							}
							else if(Session::get('UserType')=="Accounting_Staff"){
										
										/*Access within 24 Hrs*/
										if($numberDays>=1){
											return $actionBtn_view_only;
										}else{
											return $actionBtn;/*View and Print Only*/
										}
										
							}else{
										return '';/*View and Print Only*/
							}
					
                })
				->rawColumns(['action'])
                ->make(true);
		}		
		}
    }


	/*Fetch Product Information*/
	public function sales_order_info(Request $request){
		
					$data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')	
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.category_idx',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_client_table.client_tin',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',		
					'teves_sales_order_table.sales_order_po_number',
					'teves_sales_order_table.sales_order_charge_invoice',
					'teves_sales_order_table.sales_order_collection_receipt',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_invoice',
					'teves_sales_order_table.sales_order_delivered_to',
					'teves_sales_order_table.sales_order_delivered_to_address',
					'teves_sales_order_table.sales_order_delivery_method',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_hauler',
					'teves_sales_order_table.sales_order_required_date',
					'teves_sales_order_table.sales_order_instructions',
					'teves_sales_order_table.sales_order_note',
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_payment_type']);
					return response()->json($data);
		
	}

	/*Quotation Approval*/
	public function quotation_approved(Request $request){
		$request->validate(['sales_order_id' => 'required|integer']);
		$Salesorder = SalesOrderModel::findOrFail($request->sales_order_id);
		if ((int) $Salesorder->sales_order_quotation !== 1) {
			return response('Only quotations can be approved.', 422);
		}

		$Salesorder->sales_order_quotation_reference = $Salesorder->sales_order_quotation_reference
			?: $Salesorder->sales_order_control_number;
		$Salesorder->sales_order_quotation = 0;
		$Salesorder->sales_order_quotation_status = 'Approved';
		$Salesorder->updated_by_user_idx = Session::get('loginID');
		$Salesorder->save();

		return 'Approved';
	} 
	/*Quotation Disapproval*/
	public function quotation_disapproved(Request $request){
		$request->validate(['sales_order_id' => 'required|integer']);
		$Salesorder = SalesOrderModel::findOrFail($request->sales_order_id);
		if ((int) $Salesorder->sales_order_quotation !== 1) {
			return response('Only quotations can be rejected.', 422);
		}

		$Salesorder->sales_order_quotation_status = 'Rejected';
		$Salesorder->updated_by_user_idx = Session::get('loginID');
		$Salesorder->save();

		return 'Rejected';
	} 


	/*Delete Product Information*/
	public function delete_sales_order_confirmed(Request $request){

		/*Delete on Sales Order Table*/
		$sales_order_id = $request->sales_order_id;
		SalesOrderModel::find($sales_order_id)->delete();
		
		/*Delete on Sales Order Product Component*/	
		SalesOrderComponentModel::where('sales_order_idx', $sales_order_id)->delete();
		
		return 'Deleted';
		
	} 

	public function create_sales_order_post(Request $request){

		$request->validate([
			'sales_order_date'  => 'required',
			'sales_order_type'  => 'required',
			'client_idx'  		=> 'required'
        ], 
        [
			'sales_order_date.required' 	=> 'Sales Order Date is Required',
			'sales_order_type.required' 	=> 'Sales Order Type is Required',
			'client_idx.required' 			=> 'Client is Required'
        ]
		);
			
			$last_id = SalesOrderModel::latest()->first()->sales_order_id;
			
			$client_idx = $request->client_idx;
			
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $request->company_header)->first();			
			$control_number = $BranchInfo->branch_initial."-SO-".($last_id+2);
			
			$Salesorder = new SalesOrderModel();
			$Salesorder->company_header 					= $request->company_header;
			$Salesorder->sales_order_date 					= $request->sales_order_date;
			$Salesorder->sales_order_client_idx 			= $request->client_idx;
			$Salesorder->sales_order_control_number 		= $control_number;
			
			$Salesorder->sales_order_payment_term 			= $request->payment_term;
			$Salesorder->sales_order_net_percentage 		= $request->sales_order_net_percentage;
			
			if($request->sales_order_invoice==1){
				$Salesorder->sales_order_withholding_tax 		= $request->sales_order_withholding_tax;
			}else{
				$Salesorder->sales_order_withholding_tax 		= 0;
			}
			
			$Salesorder->sales_order_payment_type 			= $request->sales_order_payment_type;
			$Salesorder->category_idx 						= $request->sales_order_type;
			$Salesorder->sales_order_invoice 				= $request->sales_order_invoice;
			$Salesorder->sales_order_quotation				= $request->sales_order_quotation;/*7/26/2025*/
			$Salesorder->sales_order_quotation_status		= (int) $request->sales_order_quotation === 1 ? 'Pending' : null;
			$Salesorder->sales_order_quotation_hide_volume	= $request->sales_order_quotation_hide_volume;/*8/2/2025*/
			$Salesorder->created_by_user_idx 				= Session::get('loginID');
			
			$result = $Salesorder->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Salesorder->sales_order_id;			
			
			$receivable_last_id = ReceivablesModel::latest()->first()->receivable_id;
						
			$receivable_control_number = $BranchInfo->branch_initial."-AR-".($receivable_last_id+2);	
				
				/*Create Receivable*/
				$Receivables = new ReceivablesModel();
				$Receivables->client_idx 						= $request->client_idx;
				$Receivables->control_number 					= $receivable_control_number;
				$Receivables->sales_order_idx 					= $last_transaction_id;
				$Receivables->billing_date 						= $request->sales_order_date;
				$Receivables->payment_term 						= $request->payment_term;
				$Receivables->receivable_name 					= $control_number;
				$Receivables->receivable_amount 				= 0;
				$Receivables->receivable_unlock_expiration 		= null;
				$Receivables->receivable_status 				= 'Pending';
				$Receivables->receivable_description 			= $receivable_control_number;
				$Receivables->company_header 					= $request->company_header;
				$Receivables->created_by_user_id 				= Session::get('loginID');
				$result = $Receivables->save();

			if($result){
				return response()->json(array('success' => "Sales Order Successfully Created!", 'sales_order_id' => $last_transaction_id), 200);
			}
			else{
				return response()->json(['success'=>'Error on Insert Sales Order Information']);
			}
	}

	public function update_sales_order_post(Request $request){	

		$request->validate([
			'client_idx'  	=> 'required',
			'sales_order_type'  => 'required'
        ], 
        [
			'client_idx.required' 	=> 'Client is Required',
			'sales_order_type.required' 	=> 'Sales Order Type is Required',
        ]
		);
		
			$sales_order_data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
				->get(['created_at','sales_order_control_number']);
			
			$startTimeStamp = strtotime($sales_order_data[0]['created_at']);
			$endTimeStamp = strtotime(date('y-m-d'));
			$timeDiff = abs($endTimeStamp - $startTimeStamp);
			$numberDays = $timeDiff/86400;  // 86400 seconds in one day
			// and you might want to convert to integer
			$numberDays = intval($numberDays);

			if(Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin"){
				
			$sales_order_control_number = $sales_order_data[0]['sales_order_control_number'];
			$split_sales_order_control_number = explode("-", $sales_order_control_number);
				
			$BranchInfo = TevesBranchModel::where('branch_id', '=', $request->company_header)->first();			
			$control_number = $BranchInfo->branch_initial."-SO-".($split_sales_order_control_number[2]);
				
				$Salesorder = new SalesOrderModel();
				$Salesorder = SalesOrderModel::find($request->sales_order_id);
				$Salesorder->sales_order_client_idx 				= $request->client_idx;
				$Salesorder->sales_order_control_number 			= $control_number;
														
				$Salesorder->company_header 						= $request->company_header;
				$Salesorder->sales_order_date 						= $request->sales_order_date;
				$Salesorder->sales_order_delivered_to 				= $request->delivered_to;
				$Salesorder->sales_order_delivered_to_address 		= $request->delivered_to_address;
				$Salesorder->sales_order_dr_number 					= $request->dr_number;
			
				$Salesorder->sales_order_or_number 					= $request->sales_order_or_number;
				$Salesorder->sales_order_po_number 					= $request->sales_order_po_number;
				$Salesorder->sales_order_charge_invoice 			= $request->sales_order_charge_invoice;
				$Salesorder->sales_order_collection_receipt 		= $request->sales_order_collection_receipt;
				
				$Salesorder->sales_order_payment_term 				= $request->payment_term;
				$Salesorder->category_idx 							= $request->sales_order_type;
				$Salesorder->sales_order_delivery_method 			= $request->delivery_method;
				$Salesorder->sales_order_hauler 					= $request->hauler;
				$Salesorder->sales_order_required_date 				= $request->required_date;
				$Salesorder->sales_order_instructions 				= $request->instructions;
				$Salesorder->sales_order_note 						= $request->note;
				
				$Salesorder->sales_order_net_percentage 			= $request->sales_order_net_percentage;
				
				if($request->sales_order_invoice==1){
					$Salesorder->sales_order_withholding_tax 		= $request->sales_order_withholding_tax;
				}else{
					$Salesorder->sales_order_withholding_tax 		= 0;
				}
				
				$Salesorder->sales_order_payment_type 				= $request->sales_order_payment_type;
				$Salesorder->sales_order_invoice 					= $request->sales_order_invoice;
				$Salesorder->sales_order_quotation					= $request->sales_order_quotation;/*7/26/2025*/
				if ((int) $request->sales_order_quotation === 1) {
					$Salesorder->sales_order_quotation_status = 'Pending';
				}
				$Salesorder->sales_order_quotation_hide_volume		= $request->sales_order_quotation_hide_volume;/*8/2/2025*/
				$Salesorder->updated_by_user_idx 					= Session::get('loginID');
				$result = $Salesorder->update();
				
				/*Get Last ID*/
				$last_transaction_id = $request->sales_order_id;
				
				$order_total_amount = SalesOrderComponentModel::where('sales_order_idx', $request->sales_order_id)
						->sum('order_total_amount');
						
				$gross_amount = $order_total_amount;
				
				$net_in_percentage 									= $request->sales_order_net_percentage;/*1.12*/
				$less_in_percentage 								= $request->sales_order_withholding_tax/100;
							
				if($request->sales_order_net_percentage==0){
							$sales_order_net_amount 		= 0;
							$sales_order_total_due 			=  number_format($gross_amount,2,".","");
				}else{
							$sales_order_net_amount 		=  number_format($gross_amount/$net_in_percentage,2,".","");
							$sales_order_total_due 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
				}
				
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($last_transaction_id);
				$SalesOrderUpdate->sales_order_gross_amount 			= number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount 				= $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due 				= $sales_order_total_due;
				$SalesOrderUpdate->updated_by_user_idx 					= Session::get('loginID');
				$SalesOrderUpdate->update();
				
				/*Update Control Number on Receivable*/
				/*Get Receivable ID*/
					
				$receivable_data = ReceivablesModel::where('sales_order_idx', $request->sales_order_id)
					->get(['receivable_id','control_number']);
				$receivable_control_number = $receivable_data[0]->control_number;
				
				$split_receivable_control_number = explode("-", $receivable_control_number);

				$receivable_control_number = $BranchInfo->branch_initial."-AR-".($split_receivable_control_number[2]);		
				$receivable_id = $receivable_data[0]->receivable_id;
				$receivable_withholding_tax = $sales_order_net_amount*$request->sales_order_withholding_tax/100;
				
				$Receivables = new ReceivablesModel();
				$Receivables = ReceivablesModel::find($receivable_id);
				$Receivables->company_header 				= $request->company_header;
				$Receivables->control_number 				= $receivable_control_number;
				$Receivables->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');				
				$Receivables->receivable_withholding_tax 	= number_format($receivable_withholding_tax,2, '.', '');
				$Receivables->receivable_amount 			= number_format($sales_order_total_due,2, '.', '');
				$Receivables->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
				$Receivables->updated_by_user_id 			= Session::get('loginID');
				$Receivables->update();
				
				if($result){
					return response()->json(array('success' => "Sales Order Successfully Updated!"), 200);
				}
				else{
					return response()->json(['success'=>'Error on Update Sales Order Information']);
				}
			
			}else{
			
				if($numberDays>=3){
					return response()->json(['success'=>'You can no longer Edit this Sales Order item or Ask the Admin to Edit']);
				}
			
			}
			
			
	}
	
	public function get_sales_order_product_list(Request $request){		
	
			$raw_query_sales_order_component = "SELECT `teves_sales_order_component_table`.`sales_order_component_id`,
						IFNULL(`teves_product_table`.`product_name`,`teves_sales_order_component_table`.item_description) as product_name,
						IFNULL(`teves_product_table`.`product_unit_measurement`,'PC') as product_unit_measurement,
						`teves_sales_order_component_table`.`product_idx`, `teves_sales_order_component_table`.`product_price`, `teves_sales_order_component_table`.`order_quantity`,
						`teves_sales_order_component_table`.`order_total_amount`,
						`teves_sales_order_component_table`.`created_at`
						from `teves_sales_order_component_table`  left join `teves_product_table` on	 
						`teves_product_table`.`product_id` = `teves_sales_order_component_table`.`product_idx` where `sales_order_idx` = ?		  
						order by `sales_order_component_id` asc";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_id]);		
			
			$paymentlist = ReceivablesPaymentModel::where('receivable_idx', '=', $request->receivable_idx)->get();
			$paymentcount = $paymentlist->count();
		
			return response()->json(array('productlist'=>$data,'paymentcount'=>$paymentcount));			

	}


	public function get_sales_order_product_list_preview(Request $request){		
	
			$raw_query_sales_order_component = "SELECT `teves_sales_order_component_table`.`sales_order_component_id`,
						IFNULL(`teves_product_table`.`product_name`,`teves_sales_order_component_table`.item_description) as product_name,
						IFNULL(`teves_product_table`.`product_unit_measurement`,'PC') as product_unit_measurement,
						`teves_sales_order_component_table`.`product_idx`, `teves_sales_order_component_table`.`product_price`, `teves_sales_order_component_table`.`order_quantity`,
						`teves_sales_order_component_table`.`order_total_amount`,
						`teves_sales_order_component_table`.`created_at`
						from `teves_sales_order_component_table`  left join `teves_product_table` on	 
						`teves_product_table`.`product_id` = `teves_sales_order_component_table`.`product_idx` where `sales_order_idx` = ?		  
						order by `sales_order_component_id` asc";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_id]);		
		
			return response()->json(array('productlist'=>$data));			

	}
	
	public function delete_sales_order_item(Request $request){		
			
		$productitemID = $request->productitemID;
		SalesOrderComponentModel::find($productitemID)->delete();
		return 'Deleted';
		
	}

	public function update_sales_status(Request $request){		
			
		$sales_order_id = $request->sales_order_id;
		$sales_status = $request->sales_status;
				
				$salesOrderUpdate = new SalesOrderModel();
				$salesOrderUpdate = SalesOrderModel::find($sales_order_id);
				$salesOrderUpdate->sales_order_status = $sales_status;
				$salesOrderUpdate->update();
		
	}
	
	public function update_sales_order_delivery_status(Request $request){		
			
		$sales_order_id = $request->sales_order_id;
		$sales_order_delivery_status = $request->sales_order_delivery_status;
				
				$salesOrderUpdate = new SalesOrderModel();
				$salesOrderUpdate = SalesOrderModel::find($sales_order_id);
				$salesOrderUpdate->sales_order_delivery_status = $sales_order_delivery_status;
				$salesOrderUpdate->update();
		
	}
	
	/*Load Sales Order Form Interface*/
	public function sales_order_form(Request $request){
		
		
		if(Session::has('loginID')){

		$SalesOrderID = $request->sales_order_id;
		$tab = in_array($request->tab, ['sales_order_information', 'product', 'delivery', 'payment', 'receivable'], true)
			? $request->tab : 'product';
		
		$title = 'Sales Order Form';
		$data = array();
		
			$data = User::where('user_id', '=', Session::get('loginID'))->first();/*User Data*/
			$client_data = ClientModel::all();
			
			$product_category_data = ProductCategoryModel::all();
			
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
			
			$sales_order_delivered_to = SalesOrderModel::select('sales_order_delivered_to')->distinct()->get();
			$sales_order_delivered_to_address = SalesOrderModel::select('sales_order_delivered_to_address')->distinct()->get();
			
			$receivables_payment_suggestion = ReceivablesPaymentModel::select('receivable_mode_of_payment')->distinct()->get();
			

			$sales_order_data = SalesOrderModel::where('sales_order_id', $SalesOrderID)
					->join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')	
					->join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_sales_order_table.company_header')	
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.category_idx',
					'teves_sales_order_table.company_header',
					'teves_branch_table.branch_code',
					'teves_branch_table.branch_name',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx',
					'teves_client_table.client_name',
					'teves_client_table.client_address',
					'teves_client_table.client_tin',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_dr_number',
					'teves_sales_order_table.sales_order_or_number',			
					'teves_sales_order_table.sales_order_po_number',
					'teves_sales_order_table.sales_order_charge_invoice',
					'teves_sales_order_table.sales_order_collection_receipt',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_delivered_to',
					'teves_sales_order_table.sales_order_delivered_to_address',
					'teves_sales_order_table.sales_order_delivery_method',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_hauler',
					'teves_sales_order_table.sales_order_required_date',
					'teves_sales_order_table.sales_order_instructions',
					'teves_sales_order_table.sales_order_note',
					'teves_sales_order_table.sales_order_mode_of_payment',
					'teves_sales_order_table.sales_order_date_of_payment',
					'teves_sales_order_table.sales_order_reference_no',
					'teves_sales_order_table.sales_order_payment_amount',
					'teves_sales_order_table.sales_order_net_percentage',
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_payment_type',
					'teves_sales_order_table.sales_order_invoice',
					'teves_sales_order_table.sales_order_quotation',
					'teves_sales_order_table.sales_order_quotation_hide_volume']);
				
			//$product_data = ProductModel::all();
			$product_data = ProductModel::where('category_idx', $sales_order_data[0]->category_idx)->get();
			
			$receivables_details = ReceivablesModel::where('sales_order_idx', '=', $SalesOrderID)->first();
				
				if($receivables_details===NULL){
					
					$BranchInfo = TevesBranchModel::where('branch_id', '=', $sales_order_data[0]->company_header)->first();	
					/*Get Last ID*/
					$last_transaction_id = $sales_order_data[0]->sales_order_id;			
					
					$receivable_last_id = ReceivablesModel::latest()->first()->receivable_id;
								
					$receivable_control_number = $BranchInfo->branch_initial."-AR-".($receivable_last_id+1);	
					
					/*Automatic Create the Receivable Entry*/
						
						/*Create Receivable*/
						$Receivables = new ReceivablesModel();
						$Receivables->client_idx 						= $sales_order_data[0]->sales_order_client_idx;
						$Receivables->control_number 					= $receivable_control_number;
						$Receivables->sales_order_idx 					= $last_transaction_id;
						$Receivables->billing_date 						= $sales_order_data[0]->sales_order_date;
						$Receivables->payment_term 						= $sales_order_data[0]->sales_order_payment_term;
						$Receivables->receivable_name 					= $receivable_control_number;
						$Receivables->receivable_status 				= 'Pending';
						$Receivables->receivable_unlock_expiration 		= null;
						$Receivables->receivable_description 			= $sales_order_data[0]->sales_order_control_number;
						$Receivables->company_header 					= $sales_order_data[0]->company_header;
						$Receivables->created_by_user_id 				= Session::get('loginID');
						$result = $Receivables->save();
						$receivables_details = ReceivablesModel::where('sales_order_idx', '=', $SalesOrderID)->first();
					
				}	
		
		$category_idx = $sales_order_data[0]->category_idx;
		
		$formView = $request->boolean('modal')
			? 'pages.sales_order_v2.editor.content'
			: 'pages.sales_order_form';

		return view($formView, compact('data','title','product_data','client_data','teves_branch', 'sales_order_delivered_to','sales_order_delivered_to_address', 'SalesOrderID','category_idx','sales_order_data','receivables_payment_suggestion','receivables_details','tab','product_category_data'));
		
		}
		
	}  	

	public function sales_order_component_info(Request $request){

			$raw_query_sales_order_component = "select IFNULL(`teves_product_table`.`product_name`,`teves_sales_order_component_table`.item_description) as product_name,
			IFNULL(`teves_product_table`.`product_unit_measurement`,'PC') as product_unit_measurement,
			 `teves_sales_order_component_table`.`product_price`,
			  `teves_sales_order_component_table`.`order_quantity`,
			   `teves_sales_order_component_table`.`order_total_amount`
				 from `teves_sales_order_component_table` left join `teves_product_table`
				  on `teves_product_table`.`product_id` = `teves_sales_order_component_table`.`product_idx`
				   where `sales_order_component_id` = ?";	
						
			$data = DB::select("$raw_query_sales_order_component", [ $request->sales_order_component_id]);		

		return response()->json($data);
		
	}

	public function sales_order_component_compose(Request $request){		

		$request->validate([
		  'item_description'      	=> 'required',
		  'order_quantity'      	=> 'required',
        ], 
        [
			'item_description.required' => 'Item Description or Product is Required',
			'order_quantity.required' => 'Quantity is Required'
        ]
		);
				
					/*"Check if the price is from the manual price.*/
					if($request->product_manual_price!=0){
						
						$product_price = $request->product_manual_price;
						
					}else{
						
						/*Product Details*/
						$product_info = DB::table('teves_product_table as pt')
						->select([
							'pt.product_id as product_idx',
							'pt.product_name',
							'pt.product_unit_measurement',
							DB::raw('COALESCE(spt.selling_price, pt.product_price) AS product_price')
						])
						->leftJoin('teves_product_selling_price_table as spt', function($join) use ($request) {
							$join->on('pt.product_id', '=', 'spt.product_idx')
								 ->where('spt.branch_idx', '=', $request->branch_idx)
								 ->where('spt.client_idx', '=', $request->client_idx)
								 ->where('spt.product_idx', '=', $request->product_idx);
						})
						->get();				
				
						$product_price = $product_info[0]->product_price;
						
					}
					
					$order_total_amount = $request->order_quantity * $product_price;	
					
					$sales_order_data = SalesOrderModel::where('sales_order_id', $request->sales_order_id)
					->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.company_header',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_client_idx']);
					
					$sales_order_date = $sales_order_data[0]->sales_order_date;
					$client_idx = $sales_order_data[0]->sales_order_client_idx;
					
					if(($request->product_idx+0)==0){
						$item_description = $request->item_description;
					}else{
						$item_description = '';
					}
					
					if($request->sales_order_component_id==0){
						
						/*SAVE*/
						$SalesOrderComponent = new SalesOrderComponentModel();
						$SalesOrderComponent->sales_order_idx 		= $request->sales_order_id;
						$SalesOrderComponent->product_idx 			= $request->product_idx+0;
						$SalesOrderComponent->item_description 		= $item_description;
						$SalesOrderComponent->product_price 		= $product_price;
						$SalesOrderComponent->order_quantity 		= $request->order_quantity;
						$SalesOrderComponent->sales_order_date 		= $sales_order_date;
						$SalesOrderComponent->client_idx 			= $client_idx;
						$SalesOrderComponent->order_total_amount 	= $order_total_amount;
						
						$result = $SalesOrderComponent->save();	
				
						$action_taken = "Created";						
						
					}else{
						
						/*UPDATE*/
						$SalesOrderComponent = new SalesOrderComponentModel();
						$SalesOrderComponent = SalesOrderComponentModel::find($request->sales_order_component_id);
						$SalesOrderComponent->product_idx 			= $request->product_idx+0;
						$SalesOrderComponent->item_description 		= $item_description;
						$SalesOrderComponent->product_price 		= $product_price;
						$SalesOrderComponent->order_quantity 		= $request->order_quantity;
						$SalesOrderComponent->sales_order_date 		= $sales_order_date;
						$SalesOrderComponent->client_idx 			= $client_idx;
						$SalesOrderComponent->order_total_amount 	= $order_total_amount;
						
						$result = $SalesOrderComponent->update();
				
						$action_taken = "Updated";
							
					}
					
						/*Update Sales Order sales_order_gross_amount,sales_order_net_amount,sales_order_total_due and */
						
						$order_total_amount = SalesOrderComponentModel::where('sales_order_idx', $request->sales_order_id)
							->sum('order_total_amount');
							
						$gross_amount = $order_total_amount;
						
						$net_in_percentage 				= $request->sales_order_net_percentage;/*1.12*/
						$less_in_percentage 			= $request->sales_order_withholding_tax/100;
									
						if($request->sales_order_net_percentage==0){
									$sales_order_net_amount 		= 0;
									$sales_order_total_due 			=  number_format($gross_amount,2,".","");
						}else{
									$sales_order_net_amount 		=  number_format($gross_amount/$net_in_percentage,2,".","");
									$sales_order_total_due 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
						}
						
						$SalesOrderUpdate = new SalesOrderModel();
						$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
						$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
						$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
						$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
						$SalesOrderUpdate->update();			
						
						
						/*Update Receivable Amount*/
						$Receivables_ACTION = new ReceivablesModel();
						$Receivables_ACTION = ReceivablesModel::find($request->receivable_id);
						
						$receivable_withholding_tax = $sales_order_net_amount*$request->sales_order_withholding_tax/100;
						
						$Receivables_ACTION->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');				
						$Receivables_ACTION->receivable_withholding_tax 	= number_format($receivable_withholding_tax,2, '.', '');
						$Receivables_ACTION->receivable_amount			 	= number_format($sales_order_total_due,2, '.', '');
						$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
						
						$Receivables_ACTION->update();
									
						/*Response*/
						if($result){
							return response()->json(array('success' => "Product Information Successfully $action_taken!"), 200);
						}
						else{
							return response()->json(['success'=>'Error on $action_taken Product Information']);
						}		
		
	}		
	
	/*Delete Product Information*/
	public function delete_sales_order_component_confirmed(Request $request){

		/*Delete on Sales Order Table*/
		$sales_order_component_id = $request->sales_order_component_id;
		
		/*Delete on Sales Order Product Component*/	
		SalesOrderComponentModel::where('sales_order_component_id', $sales_order_component_id)->delete();
		
				$order_total_amount = SalesOrderComponentModel::where('sales_order_idx', $request->sales_order_id)
				->sum('order_total_amount');
					
				$gross_amount = $order_total_amount;
				
				$net_in_percentage 				= $request->sales_order_net_percentage;/*1.12*/
				$less_in_percentage 			= $request->sales_order_withholding_tax/100;
							
				if($request->sales_order_net_percentage==0){
							$sales_order_net_amount 		= 0;
							$sales_order_total_due 			=  number_format($gross_amount,2,".","");
				}else{
							$sales_order_net_amount 		=  number_format($gross_amount/$net_in_percentage,2,".","");
							$sales_order_total_due 			=  number_format($gross_amount - (($gross_amount/$net_in_percentage)*$less_in_percentage),2,".","");
				}
				
				/*Update Sales Order*/
				$SalesOrderUpdate = new SalesOrderModel();
				$SalesOrderUpdate = SalesOrderModel::find($request->sales_order_id);
				$SalesOrderUpdate->sales_order_gross_amount = number_format($gross_amount,2,".","");
				$SalesOrderUpdate->sales_order_net_amount = $sales_order_net_amount;
				$SalesOrderUpdate->sales_order_total_due = $sales_order_total_due;
				$SalesOrderUpdate->update();
				
				$receivable_withholding_tax = $sales_order_net_amount*$request->sales_order_withholding_tax/100;
				
				/*Update Receivable Amount*/
				$Receivables_ACTION = new ReceivablesModel();
				$Receivables_ACTION = ReceivablesModel::find($request->receivable_id);
				
				$Receivables_ACTION->receivable_gross_amount 		= number_format($gross_amount,2, '.', '');
				$Receivables_ACTION->receivable_withholding_tax 	= number_format($receivable_withholding_tax,2, '.', '');				
				$Receivables_ACTION->receivable_amount 				= number_format($sales_order_total_due,2, '.', '');
				$Receivables_ACTION->receivable_remaining_balance 	= number_format($sales_order_total_due,2, '.', '');
				
				$Receivables_ACTION->update();
	
				return 'Deleted';
		
	} 
	
	/*Load Product Interface*/
	    public function sales_order_summary()
    {
        $data = User::where('user_id', Session::get('loginID'))->firstOrFail();
        $title = 'Sales Order Summary';
        $client_data = ClientModel::orderBy('client_name')->get();
        $teves_branch = (new \App\Services\SalesOrderSummaryReport())->branches($data);
        return view('pages.sales_order_summary', compact('data', 'title', 'client_data', 'teves_branch'));
    }

    public function sales_order_summary_data(Request $request)
    {
        $user = User::where('user_id', Session::get('loginID'))->firstOrFail();
        $report = (new \App\Services\SalesOrderSummaryReport())->build($request, $user);
        // Keep the existing DataTables data key while returning the branch totals.
        return response()->json($report);
    }

    public function generate_sales_order_summary_report_pdf(Request $request){

        if ($validation = $this->validateLegacySalesOrderPdf($request)) {
            return $validation;
        }

		if(Session::has('loginID')){
		$current_user = Session::get('loginID');
		$company_header = $request->company_header;
		$start_date = $request->start_date;
		$end_date = $request->end_date;

	    $sales_order_data = SalesOrderModel::join('teves_client_table', 'teves_client_table.client_id', '=', 'teves_sales_order_table.sales_order_client_idx')
					->where('company_header',$company_header)
					->where('teves_sales_order_table.sales_order_quotation', '=', '0')
					->whereBetween('teves_sales_order_table.sales_order_date', ["$start_date", "$end_date"])
					->WHERE(function ($r) use($current_user) {
							if (Session::get('user_branch_access_type')=="BYBRANCH") {
									$r->whereRaw("teves_sales_order_table.company_header IN (SELECT branch_idx FROM teves_user_branch_access WHERE user_idx=?)", $current_user);
							}
						})
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_client_table.client_name',
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_status',
					'teves_sales_order_table.sales_order_delivery_status',
					'teves_sales_order_table.created_at']);			
					
		$receivable_header = TevesBranchModel::findOrFail($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);

		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		$title = 'Sales Order - Summary';
		 // sleep(60);
        $pdf = PDF::loadView('printables.report_sales_order_summary_pdf', compact('title', 'sales_order_data', 'user_data','receivable_header','start_date','end_date'));
		//return view("printables.report_sales_order_summary_soa_pdf", compact('title', 'sales_order_data', 'user_data','receivable_header','start_date','end_date'));
		/*Download Directly*/
        //return $pdf->download($client_data['client_name'].".pdf");
		/*Stream for Saving/Printing*/
		$pdf->setPaper('legal', 'landscape');/*Set to Landscape*/
		return $pdf->stream($receivable_header['branch_code']."_Sales_Order_Summary.pdf");
		
		}
	}		
	
		public function generate_sales_order_summary_report_per_client_pdf(Request $request){

        if ($validation = $this->validateLegacySalesOrderPdf($request, true)) {
            return $validation;
        }

		if(Session::has('loginID')){
		$current_user = Session::get('loginID');
		$company_header = $request->company_header;
		$client_idx = $request->client_idx;
		$start_date = $request->start_date;
		$end_date = $request->end_date;

	    $sales_order_data = SalesOrderModel::where('company_header',$company_header)
					->whereBetween('teves_sales_order_table.sales_order_date', ["$start_date", "$end_date"])
					->WHERE(function ($r) use($current_user) {
							if (Session::get('user_branch_access_type')=="BYBRANCH") {
									$r->whereRaw("teves_sales_order_table.company_header IN (SELECT branch_idx FROM teves_user_branch_access WHERE user_idx=?)", $current_user);
							}
						})
					->where('teves_sales_order_table.sales_order_quotation', '=', '0')
					->WHERE(function ($r) use($client_idx) {
							if ($client_idx!='All') {
									$r->where('sales_order_client_idx',$client_idx);
							}
						})
              		->get([
					'teves_sales_order_table.sales_order_id',
					'teves_sales_order_table.sales_order_date',
					'teves_sales_order_table.sales_order_payment_term',	
					'teves_sales_order_table.sales_order_gross_amount',	
					'teves_sales_order_table.sales_order_net_amount',	
					'teves_sales_order_table.sales_order_withholding_tax',
					'teves_sales_order_table.sales_order_control_number',
					'teves_sales_order_table.sales_order_payment_term',
					'teves_sales_order_table.sales_order_total_due',
					'teves_sales_order_table.sales_order_payment_status',
					'teves_sales_order_table.sales_order_delivery_status',
					'teves_sales_order_table.created_at']);			
					
		$receivable_header = TevesBranchModel::findOrFail($company_header, ['branch_code','branch_name','branch_tin','branch_address','branch_contact_number','branch_owner','branch_owner_title','branch_logo']);

		/*USER INFO*/
		$user_data = User::where('user_id', '=', Session::get('loginID'))->first();
		
		/*Client Information*/
		$client_data = ClientModel::findOrFail($client_idx, ['client_name','client_address','client_tin']);
		
		$title = 'Sales Order - Summary';
		
        $pdf = PDF::loadView('printables.report_sales_order_summary_per_client_pdf', compact('title', 'sales_order_data', 'user_data', 'receivable_header', 'client_data', 'start_date','end_date'));
		/*Download Directly*/
		/*Stream for Saving/Printing*/
		$pdf->setPaper('legal', 'landscape');/*Set to Landscape*/
		return $pdf->stream($client_data['client_name']."_Sales_Order_Summary.pdf");
		
		}
	}		
	
	
    public function generate_sales_order_summary_export_pdf(Request $request)
    {
        $user_data = User::where('user_id', Session::get('loginID'))->firstOrFail();
        try {
            $request->validate([
                'layout' => ['required', Rule::in(['combined', 'per_branch'])],
                'report_mode' => ['required', Rule::in(['summary', 'details'])],
            ]);
            $report = (new \App\Services\SalesOrderSummaryReport())->build($request, $user_data);
        } catch (\Illuminate\Validation\ValidationException $error) {
            return redirect()->route('salesordersummary')->with('sales_summary_error', $error->validator->errors()->first());
        }
        if (!$report['available_branch_count']) {
            return redirect()->route('salesordersummary')->with('sales_summary_error', 'No branches are available for PDF export.');
        }
        $title = 'Sales Order Summary';
        $report_mode = $request->report_mode;
        if ($request->layout === 'per_branch') {
            $pages = (new \App\Services\SalesOrderSummaryPdf())->branchPages($report, $user_data);
            $pdf = PDF::loadView('printables.report_sales_order_summary_by_branch_pdf', $pages);
            $filename = 'Sales_Order_Summary_Per_Branch.pdf';
        } else {
            // Match the existing Cash Report and Purchase Order All Branches logo.
            $printable_header = $report['branch_header'] ?: TevesBranchModel::find(1, ['branch_logo']);
            $pdf = PDF::loadView('printables.report_sales_order_summary_combined_pdf', compact('report', 'title', 'user_data', 'report_mode', 'printable_header'));
            $filename = 'Sales_Order_Summary_Combined.pdf';
        }
        return $pdf->setPaper('legal', 'landscape')->stream($filename);
    }

    private function validateLegacySalesOrderPdf(Request $request, $perClient = false)
    {
        if ($request->company_header === 'All') {
            return redirect()->route('salesordersummary')->with('sales_summary_error',
                'PDF export is available for a single branch. Select one branch in Report Filters and generate the report again.');
        }
        $user = User::where('user_id', Session::get('loginID'))->firstOrFail();
        $allowedBranches = (new \App\Services\SalesOrderSummaryReport())->branches($user)->pluck('branch_id')->all();
        $rules = [
            'company_header' => ['required', 'integer', Rule::in($allowedBranches)],
            'start_date' => ['required', 'date_format:Y-m-d'],
            'end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:start_date'],
        ];
        if ($perClient) {
            $rules['client_idx'] = ['required', 'integer', function ($attribute, $value, $fail) {
                if (!ClientModel::where('client_id', $value)->exists()) {
                    $fail('Please select a valid client for PDF export.');
                }
            }];
        }
        $validator = Validator::make($request->all(), $rules, [
            'company_header.required' => 'Select a branch and generate the report before exporting PDF.',
            'company_header.integer' => 'PDF export requires one selected branch.',
            'company_header.in' => 'The selected branch is unavailable or is outside your branch access.',
        ]);
        if ($validator->fails()) {
            return redirect()->route('salesordersummary')->with('sales_summary_error', $validator->errors()->first());
        }
        return null;
    }

	public function get_product_list_selling_price_OLD(Request $request){

		$clients_price_list = DB::table('teves_product_table as pt')
			->select([
				'pt.product_id as product_idx',
				'pt.product_code',
				'pt.product_name',
				'pt.product_unit_measurement',
				DB::raw('COALESCE(spt.selling_price, pt.product_price) AS product_price')
			])
			->leftJoin('teves_product_selling_price_table as spt', function ($join) use ($request) {
				$join->on('pt.product_id', '=', 'spt.product_idx')
					 ->where('spt.branch_idx', $request->branch_idx)
					 ->where('spt.client_idx', $request->client_idx);
			})

			->when($request->category_idx != 0, function ($query) use ($request) {
				return $query->where('pt.category_idx', $request->category_idx);
			})

			->whereNull('pt.deleted_at')
			->get();

		return response()->json([
			'clients_price_list' => $clients_price_list
		]);

	}
	
	public function get_product_list_selling_price(Request $request)
	{
		$userId = Session::get('loginID');

		$clients_price_list = DB::table('teves_product_table as pt')
			->select([
				'pt.product_id as product_idx',
				'pt.product_code',
				'pt.product_name',
				'pt.product_unit_measurement',
				DB::raw('COALESCE(spt.selling_price, bp.branch_price, pt.product_price) AS product_price')
			])

			->join('teves_user_product_category_access as upca', function ($join) use ($userId) {
				$join->on('pt.category_idx', '=', 'upca.category_idx')
					 ->where('upca.user_idx', $userId);
			})

			->leftJoin('teves_product_selling_price_table as spt', function ($join) use ($request) {
				$join->on('pt.product_id', '=', 'spt.product_idx')
					 ->where('spt.branch_idx', $request->branch_idx)
					 ->where('spt.client_idx', $request->client_idx);
			})

			->leftJoin('teves_product_branch_price_table as bp', function ($join) use ($request) {
				$join->on('pt.product_id', '=', 'bp.product_idx')
					 ->where('bp.branch_idx', $request->branch_idx);
			})

			->when($request->category_idx != 0, function ($query) use ($request) {
				return $query->where('pt.category_idx', $request->category_idx);
			})

			->whereNull('pt.deleted_at')
			->orderBy('pt.product_name')
			->get();

		return response()->json([
			'clients_price_list' => $clients_price_list
		]);
	}

	public function get_product_list_selling_price_per_branch(Request $request)
	{
		$userId = Session::get('loginID');

		$clients_price_list = DB::table('teves_product_table as pt')
			->select([
				'pt.product_id as product_idx',
				'pt.product_name',
				'pt.product_unit_measurement',
				DB::raw('COALESCE(spt.selling_price, pt.product_price) AS product_price')
			])

			->join('teves_user_product_category_access as upca', function ($join) use ($userId) {
				$join->on('pt.category_idx', '=', 'upca.category_idx')
					 ->where('upca.user_idx', $userId);
			})

			->leftJoin('teves_product_selling_price_table as spt', function ($join) use ($request) {
				$join->on('pt.product_id', '=', 'spt.product_idx')
					 ->where('spt.branch_idx', $request->branch_idx);
			})

			->when($request->category_idx != 0, function ($query) use ($request) {
				return $query->where('pt.category_idx', $request->category_idx);
			})

			->whereNull('pt.deleted_at')
			->orderBy('pt.product_name')
			->get();

		return response()->json([
			'clients_price_list' => $clients_price_list
		]);
	}	
	
}
