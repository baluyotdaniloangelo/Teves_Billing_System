<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;

class ClientController extends Controller
{
	
	/*Load client Interface*/
	public function client(){
		
		if(Session::has('loginID') && (Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin")){
		
			$title = 'Account';
			$data = array();

			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$client_data = ClientModel::all();		
			return view("pages.client.index", compact('data','title','client_data'));
	
		}
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getClientList(Request $request)
    {
		
		if ($request->ajax()) {
			
			$data = ClientModel::with('referrer')
			->select(
				'client_id',
				'client_name',
				'client_account_number',
				'client_address',
				'client_tin',
				'client_contact_number',
				'client_age',
				'default_less_percentage',
				'default_net_percentage',
				'default_vat_percentage',
				'default_withholding_tax_percentage',
				'default_payment_terms',
				'referred_by_idx'
			)
			->get();
			
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('referred_by_name', function($row){
						return $row->referrer->client_name ?? 'None';
					})
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
									   data-id="'.$row->client_id.'"
									   id="editClientDetails">
										<i class="bi bi-pencil-square text-warning me-2"></i>
										Edit Client Details
									</a>
								</li>
								<!-- DELETE -->
								<li>
									<a href="#"
									   class="dropdown-item text-danger"
									   data-id="'.$row->client_id.'"
									   id="deleteClientDetails">
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
	public function client_info(Request $request){
		
		$clientID = $request->clientID;

		$data = ClientModel::with('referrer')
			->find($clientID, [
				'client_id',
				'client_name',
				'client_account_number',
				'client_address',
				'client_tin',
				'client_contact_number',
				'client_age',
				'default_less_percentage',
				'default_net_percentage',
				'default_vat_percentage',
				'default_withholding_tax_percentage',
				'default_payment_terms',
				'referred_by_idx'
			]);

		return response()->json([
			'client_name' => $data->client_name,
			'client_account_number' => $data->client_account_number,
			'client_address' => $data->client_address,
			'client_tin' => $data->client_tin,
			'client_contact_number' => $data->client_contact_number,
			'client_age' => $data->client_age,
			'default_less_percentage' => $data->default_less_percentage,
			'default_net_percentage' => $data->default_net_percentage,
			'default_vat_percentage' => $data->default_vat_percentage,
			'default_withholding_tax_percentage' => $data->default_withholding_tax_percentage,
			'default_payment_terms' => $data->default_payment_terms,
			'referred_by_id' => $data->referred_by_idx,
			'referred_by_name' => $data->referrer->client_name ?? null,
			'referred_by_client_account_number' => $data->referrer->client_account_number ?? null
		]); 
		

	}

	/*Delete client Information*/
	public function delete_client_confirmed(Request $request){

		$clientID = $request->clientID;
		ClientModel::find($clientID)->delete();
		return 'Deleted';

	} 

	public function create_client_post(Request $request){
		
		$request->validate([
          'client_name'      		=> 'required|unique:teves_client_table,client_name',
		  'client_address'   		=> 'required',
		  'client_tin'    			=> 'required',
		  'client_contact_number' 	=> 'nullable|string|max:50',
		  'client_age'            	=> 'nullable|integer|min:1|max:120',
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
		
			@$last_id = ClientModel::latest()->first()->client_id;

			// Add 2345 first, then reverse
			$_computed = $last_id + 1 + 1135;
			$_reversed = strrev((string) $_computed);
				
				if($_reversed<1000){
					$reversed = $_reversed + 999;
				}else{
					$reversed = $_reversed;
				}

			// Ensure exactly 8 digits with leading zeros
			$client_account_number = str_pad($reversed, 8, "0", STR_PAD_LEFT);

			$client = new ClientModel();
			$client->client_name 						= $request->client_name;
			$client->client_account_number 				= $client_account_number;
			$client->client_address 					= $request->client_address;
			$client->client_tin 						= $request->client_tin;
			$client->client_contact_number 				= $request->client_contact_number;
			$client->client_age 						= $request->client_age;
			$client->default_less_percentage 			= $request->default_less_percentage;
			$client->default_net_percentage 			= $request->default_net_percentage;
			$client->default_vat_percentage 			= $request->default_vat_percentage;
			$client->default_withholding_tax_percentage = $request->default_withholding_tax_percentage;
			$client->default_payment_terms 				= $request->default_payment_terms;
			$client->referred_by_idx 					= $request->referred_by_idx;
			$client->created_by_user_idx 				= Session::has('loginID');
			
			$result = $client->save();
			if($result){
				return response()->json(['success'=>'Client Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert client Information']);
			}
	}

	public function update_client_post(Request $request){
		
		$request->validate([
          'client_name'      		=> 'required|unique:teves_client_table,client_name,'.$request->clientID.',client_id',
		  'client_address'      	=> 'required',
		  'client_tin'      		=> 'required',
		  'client_contact_number' 	=> 'nullable|string|max:50',
		  'client_age'            	=> 'nullable|integer|min:1|max:120',
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
			
			$client = new ClientModel();
			$client = ClientModel::find($request->clientID);
			$client->client_name 						= $request->client_name;
			$client->client_address 					= $request->client_address;
			$client->client_tin 						= $request->client_tin;
			$client->client_contact_number 				= $request->client_contact_number;
			$client->client_age 						= $request->client_age;
			$client->default_less_percentage 			= $request->default_less_percentage;
			$client->default_net_percentage 			= $request->default_net_percentage;
			$client->default_vat_percentage 			= $request->default_vat_percentage;
			$client->default_withholding_tax_percentage = $request->default_withholding_tax_percentage;
			$client->default_payment_terms 				= $request->default_payment_terms;
			$client->referred_by_idx 					= $request->referred_by_idx;
			$client->updated_by_user_idx 				= Session::has('loginID');
			
			$result = $client->update();
			if($result){
				return response()->json(['success'=>'Client Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update client Information']);
			}
	}
}