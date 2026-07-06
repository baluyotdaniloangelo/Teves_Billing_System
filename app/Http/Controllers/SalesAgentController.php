<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SalesAgentModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;

class SalesAgentController extends Controller
{
	
	/*Load client Interface*/
	public function salesagent(){
		
		if(Session::has('loginID') && (Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin")){
		
			$title = 'Sales Agent';
			$data = array();

			$data = User::where('user_id', '=', Session::get('loginID'))->first();
	
			return view("pages.sales_agent.index", compact('data','title'));
	
		}
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getSalesAgentList(Request $request)
    {
		
		if ($request->ajax()) {
			
			$data = SalesAgentModel::select(
				'sales_agent_id',
				'sales_agent_name',
				'sales_agent_contact_number',
				'sales_agent_email_address',
				'sales_agent_address',
				'sales_agent_status'
			)
			->get();
			
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function ($row)
					{
						return '
						<div class="dropdown dropstart text-center">
							<!-- BUTTON -->
							<button class="btn btn-sm btn-light border rounded-3 shadow-sm"
									type="button"
									data-bs-toggle="dropdown"
									aria-expanded="false">
								<i class="bi bi-three-dots"></i>
							</button>
							<!-- MENU -->
							<ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4">
								<!-- EDIT -->
								<li>
									<a href="#"
									   class="dropdown-item"
									   data-id="'.$row->sales_agent_id.'"
									   id="editSalesAgentDetails">
										<i class="bi bi-pencil-square text-warning me-2"></i>
										Edit Client Details
									</a>
								</li>
								<!-- DELETE -->
								<li>
									<a href="#"
									   class="dropdown-item text-danger"
									   data-id="'.$row->sales_agent_id.'"
									   id="deleteSalesAgentDetails">
										<i class="bi bi-trash3-fill me-2"></i>
										Delete Client Details
									</a>
								</li>
							</ul>
						</div>
						';
					})
					->rawColumns(['action'])
					->make(true);
		}
    }

	/*Fetch client Information*/
	public function sales_agent_info(Request $request){
		
		$SalesAgentID = $request->SalesAgentID;

		$data = SalesAgentModel::find($SalesAgentID, [
				'sales_agent_id',
				'sales_agent_name',
				'sales_agent_contact_number',
				'sales_agent_email_address',
				'sales_agent_address',
				'sales_agent_status'
			]);

		return response()->json([
			'sales_agent_name' => $data->sales_agent_name,
			'sales_agent_contact_number' => $data->sales_agent_contact_number,
			'sales_agent_email_address' => $data->sales_agent_email_address,
			'sales_agent_address' => $data->sales_agent_address,
			'sales_agent_status' => $data->sales_agent_status
		]); 
		

	}

	/*Delete client Information*/
	public function delete_sales_agent_confirmed(Request $request){

		$SalesAgentID = $request->SalesAgentID;
		SalesAgentModel::find($SalesAgentID)->delete();
		return 'Deleted';

	} 

	public function create_sales_agent_post(Request $request){
		
		$request->validate([
			'sales_agent_name' => [
				'required',
				Rule::unique('teves_sales_agent_table', 'sales_agent_name')
					->whereNull('deleted_at'),
			],
			'sales_agent_address'   		=> 'required',
			'sales_agent_contact_number' => [
				'required',
				Rule::unique('teves_sales_agent_table', 'sales_agent_contact_number')
					->whereNull('deleted_at'),
			],
        ], 
        [
			'sales_agent_name.required' => 'Sales Agent Name is required',
			'sales_agent_address.required' => 'Address is Required',
			'sales_agent_contact_number.required' => 'Contact Number is Required'
        ]
		);
		
			$SalesAgent = new SalesAgentModel();
			$SalesAgent->sales_agent_name 				= $request->sales_agent_name;
			$SalesAgent->sales_agent_contact_number 	= $request->sales_agent_contact_number;
			$SalesAgent->sales_agent_email_address 		= $request->sales_agent_email_address;
			$SalesAgent->sales_agent_address 			= $request->sales_agent_address;
			$SalesAgent->sales_agent_status 			= $request->sales_agent_status;
			$SalesAgent->created_by_user_idx 			= Session::has('loginID');
			
			$result = $SalesAgent->save();
			
			if($result){
				return response()->json(['success'=>'Sales Agent Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Sales Agent Information']);
			}
	}

	public function update_sales_agent_post(Request $request){
		
		$request->validate([
			'sales_agent_name' => [
				'required',
				Rule::unique('teves_sales_agent_table', 'sales_agent_name')
					->ignore($request->SalesAgentID, 'sales_agent_id')
					->whereNull('deleted_at'),
			],
			'sales_agent_address'   		=> 'required',
			'sales_agent_contact_number' => [
				'required',
				Rule::unique('teves_sales_agent_table', 'sales_agent_contact_number')
					->ignore($request->SalesAgentID, 'sales_agent_id')
					->whereNull('deleted_at'),
			],
        ], 
        [
			'sales_agent_name.required' => 'Sales Agent Name is required',
			'sales_agent_address.required' => 'Address is Required',
			'sales_agent_contact_number.required' => 'Contact Number is Required'
        ]
		);
			
			$SalesAgent = new SalesAgentModel();
			$SalesAgent = SalesAgentModel::find($request->SalesAgentID);
			$SalesAgent->sales_agent_name 				= $request->sales_agent_name;
			$SalesAgent->sales_agent_contact_number 	= $request->sales_agent_contact_number;
			$SalesAgent->sales_agent_email_address 		= $request->sales_agent_email_address;
			$SalesAgent->sales_agent_address 			= $request->sales_agent_address;
			$SalesAgent->sales_agent_status 			= $request->sales_agent_status;
			$SalesAgent->updated_by_user_idx 				= Session::has('loginID');
			
			$result = $SalesAgent->update();
			
			if($result){
				return response()->json(['success'=>'Sales Agent Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Sales Agent Information']);
			}
	}
}