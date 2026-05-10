<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\BankModel;
use Session;
use Validator;
use DataTables;

class BankController extends Controller
{
	
	/*Load bank Interface*/
	public function bank(){

		
		if(Session::has('loginID') && (Session::get('UserType')=="SUAdmin")){
			
			$title = 'Bank';
			$data = array();
		
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$client_data = BankModel::all();		
			
			return view("pages.bank.index", compact('data','title'));
		}
		

	}   

/*==================================================
FETCH BANK LIST USING DATATABLE
==================================================*/

public function getbankList(Request $request)
{
    if ($request->ajax())
    {
        $data = BankModel::select(
                    'bank_id',
                    'bank_account_number',
                    'bank_name',
                    'bank_branch'
                )
                ->orderBy('bank_name', 'asc');

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
                               data-id="'.$row->bank_id.'"
                               id="editBankDetails">

                                <i class="bi bi-pencil-square text-warning me-2"></i>

                                Edit Bank Details

                            </a>

                        </li>

                        <!-- DELETE -->
                        <li>

                            <a href="#"
                               class="dropdown-item text-danger"
                               data-id="'.$row->bank_id.'"
                               id="deleteBankDetails">

                                <i class="bi bi-trash3-fill me-2"></i>

                                Delete Bank Details

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
	/*Fetch bank Information*/
	public function bank_info(Request $request){
		
		$bankID = $request->bank_id;
		$data = BankModel::find($bankID, ['bank_id',  'bank_name', 'bank_account_number', 'bank_branch']);
		return response()->json($data);

	}

	/*Delete bank Information*/
	public function delete_bank_confirmed(Request $request){

		$bankID = $request->bank_id;
		BankModel::find($bankID)->delete();
		
		return 'Deleted';

	} 
	
	/*Create bank Information*/
	public function create_bank_post(Request $request){
		
		$request->validate([
          'bank_account_number'     => 'required|unique:teves_bank_table,bank_account_number',
		  'bank_name'   			=> 'required',
		  'bank_branch'   			=> 'required'
        ], 
        [
			'bank_account_number.required' 	=> 'Bank Account Number is required',
			'bank_name.required' 			=> 'Bank Name is required',
			'bank_branch.required' 		=> 'Bank Branh is Required'
        ]
		);
		
			$bank = new BankModel();
			$bank->bank_account_number 	= $request->bank_account_number;
			$bank->bank_name 			= $request->bank_name;
			$bank->bank_branch 			= $request->bank_branch;
			$bank->created_by_user_idx 	= Session::get('loginID');
			
			$result = $bank->save();
			
			
			if($result){
				return response()->json(['success'=>'Bank Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Bank Information']);
			}
			
	}

	/*Update bank Information*/
	public function update_bank_post(Request $request){
		
		$request->validate([
          'bank_account_number'     => 'required|unique:teves_bank_table,bank_account_number,'.$request->bank_id.',bank_id',
		  'bank_name'   			=> 'required',
		  'bank_branch'   			=> 'required'
        ], 
        [
			'bank_account_number.required' 	=> 'Bank Account Number is required',
			'bank_name.required' 			=> 'Bank Name is required',
			'bank_branch.required' 		=> 'Bank Branh is Required'
        ]
		);
			
			$bank = new BankModel();
			$bank = BankModel::find($request->bank_id);
			$bank->bank_account_number 	= $request->bank_account_number;
			$bank->bank_name 			= $request->bank_name;
			$bank->bank_branch 			= $request->bank_branch;
			$bank->updated_by_user_idx 	= Session::get('loginID');
			
			$result = $bank->update();
			
			if($result){
				return response()->json(['success'=>'Bank Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update bank Information']);
			}
	}
}
