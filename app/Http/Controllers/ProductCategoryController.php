<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductCategoryModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;

class ProductCategoryController extends Controller
{
	
	/*Load bank Interface*/
	public function product_category(){

		
		if(Session::has('loginID') && (Session::get('UserType')=="SUAdmin")){
			
			$title = 'Product Category';
			$data = array();
		
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			$client_data = ProductCategoryModel::all();		
			
			return view("pages.product_category.index", compact('data','title'));
		}
		

	}   

/*==================================================
FETCH BANK LIST USING DATATABLE
==================================================*/

public function getProductCategoryList(Request $request)
{
    if ($request->ajax())
    {
        $data = ProductCategoryModel::select(
                    'category_id',
                    'category_name'
                )
                ->orderBy('category_name', 'asc');

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
                               data-id="'.$row->category_id.'"
                               id="editProductCategoryDetails">

                                <i class="bi bi-pencil-square text-warning me-2"></i>

                                Edit Bank Details

                            </a>

                        </li>

                        <!-- DELETE -->
                        <li>

                            <a href="#"
                               class="dropdown-item text-danger"
                               data-id="'.$row->category_id.'"
                               id="deleteProductCategoryDetails">

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
	public function product_category_info(Request $request){
		
		$categoryID = $request->category_id;
		$data = ProductCategoryModel::find($categoryID, ['category_id', 'category_name']);
		return response()->json($data);

	}

	/*Delete bank Information*/
	public function delete_product_category_confirmed(Request $request){

		$categoryID = $request->category_id;
		ProductCategoryModel::find($categoryID)->delete();
		
		return 'Deleted';

	} 
	
	/*Create bank Information*/
	public function create_product_category_post(Request $request){

		$request->validate([
			'category_name' => [
				'required',
				Rule::unique('teves_product_category', 'category_name')
					->whereNull('deleted_at'),
			],
		], [
			'category_name.required' => 'Product Category Name is required',
			'category_name.unique'   => 'Product Category Name already exists',
		]);
				
			$bank = new ProductCategoryModel();
			$bank->category_name 	= $request->category_name;
			$bank->created_by_user_idx 	= Session::get('loginID');
			
			$result = $bank->save();
			
			
			if($result){
				return response()->json(['success'=>'Product Category Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Product Category Information']);
			}
			
	}

	/*Update bank Information*/
	public function update_product_category_post(Request $request){

		$request->validate([
			'category_name' => [
				'required',
				Rule::unique('teves_product_category', 'category_name')
					->ignore($request->category_id, 'category_id')
					->whereNull('deleted_at'),
			],
		], [
			'category_name.required' => 'Product Category Name is required',
			'category_name.unique'   => 'Product Category Name already exists',
		]);
			
			$bank = new ProductCategoryModel();
			$bank = ProductCategoryModel::find($request->category_id);
			$bank->category_name 	= $request->category_name;
			$bank->updated_by_user_idx 	= Session::get('loginID');
			
			$result = $bank->update();
			
			if($result){
				return response()->json(['success'=>'Product Category Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Product Category Information']);
			}
	}
}
