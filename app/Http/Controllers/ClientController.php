<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ClientModel;
use Session;
use Validator;
use DataTables;
use App\Models\SalesAgentModel;

class ClientController extends Controller
{
	
	/*Load client Interface*/
	public function client(){
		
		if(Session::has('loginID') && (Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin" || Session::get('UserType')=="Encoder")){
		
			$title = 'Account';
			$data = array();

			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$sales_agent_data = SalesAgentModel::all();		
			return view("pages.client.index", compact('data','title','sales_agent_data'));
	
		}
		
	}   
	
	/*Fetch client List using Datatable*/
	public function getClientList(Request $request)
    {
		
		if ($request->ajax()) {

			$query = ClientModel::with('referrer')
				->select(
					'client_id',
					'client_name',
					'customer_type',
					'client_account_number',
					'client_address',
					'client_tin',
					'client_contact_number',
					'client_email_address',

					// Owner Information
					'client_title',
					'client_gender',
					'client_first_name',
					'client_middle_name',
					'client_last_name',
					'client_name_extension',
					'client_birthday',

					// Tax & Payment
					'default_less_percentage',
					'default_net_percentage',
					'default_vat_percentage',
					'default_withholding_tax_percentage',
					'default_payment_terms',

					'sales_agent_idx',
					'created_by_user_idx',
					'created_at'
				);

			if (Session::get('UserType') == "Encoder") {
				$query->where('created_by_user_idx', Session::get('loginID'));
			}

			$data = $query->get();
			
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('referred_by_name', function($row){
						return $row->referrer->sales_agent_name ?? 'None';
					})
					->addColumn('action', function ($row) {

					$userType = Session::get('UserType');
					$loginID  = Session::get('loginID');

					$canEdit = true;
					$canDelete = false;

					if ($userType == "Encoder") {

						// Encoder can only edit their own clients
						if ($row->created_by_user_idx != $loginID) {
							$canEdit = false;
						}

						// Encoder can only edit within 24 hours
						if ($row->created_at && now()->diffInHours($row->created_at) >= 24) {
							$canEdit = false;
						}

						// Encoder cannot delete
						$canDelete = false;

					} elseif ($userType == "Admin" || $userType == "SUAdmin") {

						// Admin and SUAdmin can edit and delete
						$canEdit = true;
						$canDelete = true;
					}

					$menu = '
						<div class="dropdown dropstart text-center">
							<button class="btn btn-sm btn-light border rounded-3 shadow-sm"
									type="button"
									data-bs-toggle="dropdown"
									aria-expanded="false">
								<i class="bi bi-three-dots"></i>
							</button>

							<ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4">
					';

					// EDIT
					if ($canEdit) {
						$menu .= '
								<li>
									<a href="#"
									   class="dropdown-item"
									   data-id="'.$row->client_id.'"
									   id="editClientDetails">
										<i class="bi bi-pencil-square text-warning me-2"></i>
										Edit Client Details
									</a>
								</li>
						';
					}

					// DELETE
					if ($canDelete) {
						$menu .= '
								<li>
									<a href="#"
									   class="dropdown-item text-danger"
									   data-id="'.$row->client_id.'"
									   id="deleteClientDetails">
										<i class="bi bi-trash3-fill me-2"></i>
										Delete Client Details
									</a>
								</li>
						';
					}

					$menu .= '
							</ul>
						</div>
					';

					return $menu;
				})
					->rawColumns(['action'])
					->make(true);
		}
    }

	/*Fetch client Information*/
	public function client_info_OLD(Request $request){
		
		$clientID = $request->clientID;

		$data = ClientModel::with('referrer')
			->find($clientID, [
				'client_id',
				'client_name',
				'customer_type',
				'client_account_number',
				'client_address',
				'client_tin',
				'client_email_address',
				'client_contact_number',
				'client_birthday',
				'client_title',
				'client_gender',
				'default_less_percentage',
				'default_net_percentage',
				'default_vat_percentage',
				'default_withholding_tax_percentage',
				'default_payment_terms',
				'sales_agent_idx'
			]);

		return response()->json([
			'client_name' => $data->client_name,
			'customer_type' => $data->customer_type,
			'client_account_number' => $data->client_account_number,
			'client_address' => $data->client_address,
			'client_tin' => $data->client_tin,
			'client_email_address' => $data->client_email_address,
			'client_contact_number' => $data->client_contact_number,
			'client_birthday' => $data->client_birthday,
			'default_less_percentage' => $data->default_less_percentage,
			'default_net_percentage' => $data->default_net_percentage,
			'default_vat_percentage' => $data->default_vat_percentage,
			'default_withholding_tax_percentage' => $data->default_withholding_tax_percentage,
			'default_payment_terms' => $data->default_payment_terms,
			'sales_agent_idx' => $data->sales_agent_idx,
			'sales_agent_name' => $data->referrer->sales_agent_name ?? null
		]); 
		

	}
	
	public function client_info(Request $request)
	{
		$clientID = $request->clientID;

		$data = ClientModel::with('referrer')
			->find($clientID, [
				'client_id',
				'client_name',
				'customer_type',
				'client_account_number',
				'client_address',
				'client_tin',
				'client_email_address',
				'client_contact_number',

				// Owner Information
				'client_title',
				'client_gender',
				'client_first_name',
				'client_middle_name',
				'client_last_name',
				'client_name_extension',
				'client_birthday',

				// Tax & Payment
				'default_less_percentage',
				'default_net_percentage',
				'default_vat_percentage',
				'default_withholding_tax_percentage',
				'default_payment_terms',

				// Referral
				'sales_agent_idx'
			]);

		if (!$data) {
			return response()->json([
				'success' => false,
				'message' => 'Client not found.'
			], 404);
		}

		return response()->json([

			/*
			|--------------------------------------------------------------------------
			| ACCOUNT INFORMATION
			|--------------------------------------------------------------------------
			*/

			'client_name' =>
				$data->client_name,

			'customer_type' =>
				$data->customer_type,

			'client_account_number' =>
				$data->client_account_number,

			'client_address' =>
				$data->client_address,

			'client_email_address' =>
				$data->client_email_address,

			'client_contact_number' =>
				$data->client_contact_number,


			/*
			|--------------------------------------------------------------------------
			| OWNER INFORMATION
			|--------------------------------------------------------------------------
			*/

			'client_title' =>
				$data->client_title,
				
			'client_gender' =>
				$data->client_gender,

			'client_first_name' =>
				$data->client_first_name,

			'client_middle_name' =>
				$data->client_middle_name,

			'client_last_name' =>
				$data->client_last_name,

			'client_name_extension' =>
				$data->client_name_extension,

			'client_birthday' =>
				$data->client_birthday,


			/*
			|--------------------------------------------------------------------------
			| REFERRAL
			|--------------------------------------------------------------------------
			*/

			'sales_agent_idx' =>
				$data->sales_agent_idx,

			'sales_agent_name' =>
				$data->referrer->sales_agent_name ?? null,


			/*
			|--------------------------------------------------------------------------
			| TAX & PAYMENT SETTINGS
			|--------------------------------------------------------------------------
			*/

			'client_tin' =>
				$data->client_tin,

			'default_less_percentage' =>
				$data->default_less_percentage,

			'default_net_percentage' =>
				$data->default_net_percentage,

			'default_vat_percentage' =>
				$data->default_vat_percentage,

			'default_withholding_tax_percentage' =>
				$data->default_withholding_tax_percentage,

			'default_payment_terms' =>
				$data->default_payment_terms

		]);
	}	

	/*Delete client Information*/
	public function delete_client_confirmed(Request $request){

		$clientID = $request->clientID;
		ClientModel::find($clientID)->delete();
		return 'Deleted';

	} 

	public function create_client_post(Request $request)
	{
		$request->validate(
			[
				'client_name'           => 'required|unique:teves_client_table,client_name',
				'customer_type'         => 'required',
				
				'client_address'        => 'required',
				
				'client_house_number' => 'nullable|string|max:100',
				'client_street'       => 'nullable|string|max:255',
				'client_subdivision'  => 'nullable|string|max:255',
				'client_barangay'     => 'required|string|max:255',
				'client_city'         => 'required|string|max:255',
				'client_province'     => 'required|string|max:255',
				'client_country'      => 'required|string|max:100',
	
				'client_tin'            => 'nullable|string|max:100',
				'client_contact_number' => 'required|string|max:50',
				'client_email_address'  => 'nullable|email|max:255',

				// Owner Information
				'client_title'          => 'nullable|string|max:20',
				'client_gender'          => 'nullable|string|max:20',
				'client_first_name'     => 'required|string|max:100',
				'client_middle_name'    => 'nullable|string|max:100',
				'client_last_name'      => 'required|string|max:100',
				'client_name_extension' => 'nullable|string|max:20',
				'client_birthday'       => 'required|date',

				// Tax & Payment
				'default_less_percentage'            => 'nullable|numeric',
				'default_net_percentage'             => 'nullable|numeric',
				'default_vat_percentage'             => 'nullable|numeric',
				'default_withholding_tax_percentage'=> 'nullable|numeric',
				'default_payment_terms'              => 'nullable|string|max:255',
			],
			[
				'client_name.required'       => 'Company Name is required',
				'client_contact_number.required'       => 'Contact Number is required',
				
				'client_address.required'    => 'Address is Required',
				'client_barangay.required'   => 'Barangay is Required',
				'client_city.required'    	 => 'City is Required',
				'client_province.required'   => 'Province is Required',
				'client_country.required'    => 'Country is Required',
				
				'client_tin.required'        => 'TIN is Required',
				'client_email_address.email' => 'Please enter a valid email address.',
				'client_birthday.date'       => 'Please enter a valid birthday.',
			]
		);


		/*
		|--------------------------------------------------------------------------
		| GENERATE CLIENT ACCOUNT NUMBER
		|--------------------------------------------------------------------------
		*/

		$lastClient = ClientModel::latest('client_id')->first();

		$last_id = $lastClient ? $lastClient->client_id : 0;

		// Add 2345 first, then reverse
		$_computed = $last_id + 1 + 1135;

		$_reversed = strrev((string) $_computed);

		if ($_reversed < 1000) {
			$reversed = $_reversed + 999;
		} else {
			$reversed = $_reversed;
		}

		// Ensure exactly 8 digits with leading zeros
		$client_account_number = str_pad(
			$reversed,
			8,
			"0",
			STR_PAD_LEFT
		);


		/*
		|--------------------------------------------------------------------------
		| CREATE CLIENT
		|--------------------------------------------------------------------------
		*/

		$client = new ClientModel();

		/*
		|--------------------------------------------------------------------------
		| ACCOUNT INFORMATION
		|--------------------------------------------------------------------------
		*/

		$client->client_name =
			$request->client_name;

		$client->customer_type =
			$request->customer_type;

		$client->client_account_number =
			$client_account_number;

		$client->client_address =
			$request->client_address;

		$client->client_contact_number =
			$request->client_contact_number;

		$client->client_email_address =
			$request->client_email_address;


		/*
		|--------------------------------------------------------------------------
		| OWNER INFORMATION
		|--------------------------------------------------------------------------
		*/

		$client->client_title =
			$request->client_title;
			
		$client->client_gender =
			$request->client_gender;

		$client->client_first_name =
			$request->client_first_name;

		$client->client_middle_name =
			$request->client_middle_name;

		$client->client_last_name =
			$request->client_last_name;

		$client->client_name_extension =
			$request->client_name_extension;

		$client->client_birthday =
			$request->client_birthday;

		$client->sales_agent_idx =
			$request->sales_agent_idx;


		/*
		|--------------------------------------------------------------------------
		| TAX & PAYMENT SETTINGS
		|--------------------------------------------------------------------------
		*/

		$client->client_tin =
			$request->client_tin;

		$client->default_less_percentage =
			$request->default_less_percentage;

		$client->default_net_percentage =
			$request->default_net_percentage;

		$client->default_vat_percentage =
			$request->default_vat_percentage;

		$client->default_withholding_tax_percentage =
			$request->default_withholding_tax_percentage;

		$client->default_payment_terms =
			$request->default_payment_terms;


		/*
		|--------------------------------------------------------------------------
		| AUDIT
		|--------------------------------------------------------------------------
		*/

		$client->created_by_user_idx =
			Session::get('loginID');


		/*
		|--------------------------------------------------------------------------
		| SAVE
		|--------------------------------------------------------------------------
		*/

		$result = $client->save();


		if ($result) {

			return response()->json([
				'success' => 'Client Information Successfully Created!'
			]);

		} else {

			return response()->json([
				'success' => 'Error on Insert client Information'
			]);
		}
	}
	
	public function update_client_post(Request $request)
	{
		$request->validate(
			[
				'clientID'              => 'required|integer|exists:teves_client_table,client_id',
				'client_name'           => 'required|unique:teves_client_table,client_name,' . $request->clientID . ',client_id',
				'customer_type'         => 'required',
				
				'client_address'        => 'required',
						  				
				'client_house_number' => 'nullable|string|max:100',
				'client_street'       => 'nullable|string|max:255',
				'client_subdivision'  => 'nullable|string|max:255',
				'client_barangay'     => 'required|string|max:255',
				'client_city'         => 'required|string|max:255',
				'client_province'     => 'required|string|max:255',
				'client_country'      => 'required|string|max:100',
				
				'client_tin'            => 'nullable|string|max:100',

				'client_contact_number' => 'required',
				'client_email_address'  => 'nullable|email|max:255',

				// Owner Information
				'client_title'          => 'nullable|string|max:20',
				'client_gender'          => 'nullable|string|max:20',
				'client_first_name'     => 'required|string|max:100',
				'client_middle_name'    => 'nullable|string|max:100',
				'client_last_name'      => 'required|string|max:100',
				'client_name_extension' => 'nullable|string|max:20',
				'client_birthday'       => 'required|date',

				// Tax & Payment
				'default_less_percentage'             => 'nullable|numeric',
				'default_net_percentage'              => 'nullable|numeric',
				'default_vat_percentage'              => 'nullable|numeric',
				'default_withholding_tax_percentage' => 'nullable|numeric',
				'default_payment_terms'               => 'nullable|string|max:255',
			],
			[
				'client_name.required'       => 'Company Name is required',
				'client_contact_number.required'       => 'Contact Number is required',
				
				'client_address.required'    => 'Address is Required',
				'client_barangay.required'   => 'Barangay is Required',
				'client_city.required'    	 => 'City is Required',
				'client_province.required'   => 'Province is Required',
				'client_country.required'    => 'Country is Required',
				
				'client_tin.required'        => 'TIN is Required',
				'client_email_address.email' => 'Please enter a valid email address.',
				'client_birthday.date'       => 'Please enter a valid birthday.',
			]
		);


		/*
		|--------------------------------------------------------------------------
		| FIND CLIENT
		|--------------------------------------------------------------------------
		*/

		$client = ClientModel::find($request->clientID);

		if (!$client) {
			return response()->json([
				'success' => 'Client not found.'
			], 404);
		}


		/*
		|--------------------------------------------------------------------------
		| ENCODER RESTRICTION
		|--------------------------------------------------------------------------
		*/

		if (Session::get('UserType') == "Encoder") {

			/*
			|--------------------------------------------------------------------------
			| Encoder can only edit clients they created
			|--------------------------------------------------------------------------
			*/

			if ($client->created_by_user_idx != Session::get('loginID')) {

				return response()->json([
					'success' => 'You are not allowed to edit this client.'
				], 403);
			}


			/*
			|--------------------------------------------------------------------------
			| Encoder can only edit within 24 hours
			|--------------------------------------------------------------------------
			*/

			if ($client->created_at && now()->diffInHours($client->created_at) >= 24) {

				return response()->json([
					'success' => 'The 24-hour editing period for this client has expired.'
				], 403);
			}
		}


		/*
		|--------------------------------------------------------------------------
		| ACCOUNT INFORMATION
		|--------------------------------------------------------------------------
		*/

		$client->client_name =
			$request->client_name;

		$client->customer_type =
			$request->customer_type;

		$client->client_address =
			$request->client_address;

		$client->client_tin =
			$request->client_tin;

		$client->client_email_address =
			$request->client_email_address;

		$client->client_contact_number =
			$request->client_contact_number;


		/*
		|--------------------------------------------------------------------------
		| OWNER INFORMATION
		|--------------------------------------------------------------------------
		*/

		$client->client_title =
			$request->client_title;
			
		$client->client_gender =
			$request->client_gender;

		$client->client_first_name =
			$request->client_first_name;

		$client->client_middle_name =
			$request->client_middle_name;

		$client->client_last_name =
			$request->client_last_name;

		$client->client_name_extension =
			$request->client_name_extension;

		$client->client_birthday =
			$request->client_birthday;


		/*
		|--------------------------------------------------------------------------
		| TAX & PAYMENT SETTINGS
		|--------------------------------------------------------------------------
		*/

		$client->default_less_percentage =
			$request->default_less_percentage;

		$client->default_net_percentage =
			$request->default_net_percentage;

		$client->default_vat_percentage =
			$request->default_vat_percentage;

		$client->default_withholding_tax_percentage =
			$request->default_withholding_tax_percentage;

		$client->default_payment_terms =
			$request->default_payment_terms;


		/*
		|--------------------------------------------------------------------------
		| REFERRAL
		|--------------------------------------------------------------------------
		*/

		$client->sales_agent_idx =
			$request->sales_agent_idx;


		/*
		|--------------------------------------------------------------------------
		| AUDIT
		|--------------------------------------------------------------------------
		*/

		$client->updated_by_user_idx =
			Session::get('loginID');


		/*
		|--------------------------------------------------------------------------
		| SAVE
		|--------------------------------------------------------------------------
		*/

		$result = $client->save();


		if ($result) {

			return response()->json([
				'success' => 'Client Information Successfully Updated!'
			]);

		} else {

			return response()->json([
				'success' => 'Error on Update client Information'
			]);
		}
	}

}