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
		$title = 'Client';
		$data = array();
		if(Session::has('loginID')){
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$client_data = ClientModel::all();		
		}
		return view("pages.client", compact('data','title'));
	}   
	
	/*Fetch client List using Datatable*/
	public function getClientList(Request $request)
    {
		$list = ClientModel::get();
		if ($request->ajax()) {
			$data = ClientModel::select(
			'client_id',
			'client_name',
			'client_address',
			'client_tin');
			return DataTables::of($data)
					->addIndexColumn()
					->addColumn('action', function($row){
						$actionBtn = '
						<div align="center" class="action_table_menu_client">
						<a href="#" data-id="'.$row->client_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editclient"></a>
						<a href="#" data-id="'.$row->client_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteclient"></a>
						</div>';
						return $actionBtn;
					})
					->rawColumns(['action'])
					->make(true);
		}
    }

	/*Fetch client Information*/
	public function client_info(Request $request){
		$clientID = $request->clientID;
		$data = ClientModel::find($clientID, ['client_name','client_address','client_tin']);
		return response()->json($data);
	}

	/*Delete client Information*/
	public function delete_client_confirmed(Request $request){
		$clientID = $request->clientID;
		ClientModel::find($clientID)->delete();
		return 'Deleted';
	} 

	public function create_client_post(Request $request){
		$request->validate([
          'client_name'      => 'required|unique:teves_client_table,client_name',
		  'client_address'   => 'required',
		  'client_tin'    => 'required'
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
			$client = new ClientModel();
			$client->client_name 			= $request->client_name;
			$client->client_address 		= $request->client_address;
			$client->client_tin 		= $request->client_tin;
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
		  'client_tin'      	=> 'required'
        ], 
        [
			'client_name.required' => 'Client Name is required',
			'client_address.required' => 'Address is Required',
			'client_tin.required' => 'TIN is Required'
        ]
		);
			$client = new ClientModel();
			$client = ClientModel::find($request->clientID);
			$client->client_name 		= $request->client_name;
			$client->client_address 	= $request->client_address;
			$client->client_tin 		= $request->client_tin;
			$result = $client->update();
			if($result){
				return response()->json(['success'=>'Client Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update client Information']);
			}
	}
}
