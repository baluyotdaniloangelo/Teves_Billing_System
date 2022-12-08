<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ProductModel;
use Session;
use Validator;
use DataTables;

class ProductController extends Controller
{
	
	/*Load Product Interface*/
	public function product(){
		
		$title = 'Product';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$product_data = ProductModel::all();
		
		}

		return view("pages.product", compact('data','title'));
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getProductList(Request $request)
    {

		$list = ProductModel::get();
		if ($request->ajax()) {

    	$data = ProductModel::select(
		'product_id',
		'product_name',
		'product_price',
		'product_unit_measurement');
		

		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					
					$actionBtn = '
					<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->product_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="editProduct"></a>
					<a href="#" data-id="'.$row->product_id.'" class="btn-danger btn-circle btn-sm bi-trash3-fill btn_icon_table btn_icon_table_delete" id="deleteProduct"></a>
					</div>';
                    return $actionBtn;

                })
				
				->rawColumns(['action'])
                ->make(true);
		
		}
		
		
    }

	/*Fetch Product Information*/
	public function product_info(Request $request){

		$productID = $request->productID;
		$data = ProductModel::find($productID, ['product_name','product_price','product_unit_measurement']);
		return response()->json($data);
		
	}

	/*Delete Product Information*/
	public function delete_product_confirmed(Request $request){

		$productID = $request->productID;
		ProductModel::find($productID)->delete();
		return 'Deleted';
		
	} 

	public function create_product_post(Request $request){

		$request->validate([
          'product_name'      		=> 'required|unique:teves_product_table,product_name',
		  'product_price'      		=> 'required'
        ], 
        [
			'product_name.required' => 'Product Name is required',
			'product_price.required' => 'Price is Required'
        ]
		);

			$Product = new ProductModel();
			
			$Product->product_name 						= $request->product_name;
			$Product->product_price 					= $request->product_price;
			$Product->product_unit_measurement 			= $request->product_unit_measurement;
			
			$result = $Product->save();
			
			if($result){
				return response()->json(['success'=>'Product Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Product Information']);
			}
	}

	public function update_product_post(Request $request){
		
		
		$request->validate([
          'product_name'      		=> 'required|unique:teves_product_table,product_name,'.$request->productID.',product_id',
		  'product_price'      		=> 'required'
        ], 
        [
			'product_name.required' => 'Product Name is required',
			'product_price.required' => 'Price is Required'
        ]
		);
	
			$Product = new ProductModel();
			$Product = ProductModel::find($request->productID);
			
			$Product->product_name 			= $request->product_name;
			$Product->product_price 		= $request->product_price;
			$Product->product_unit_measurement 			= $request->product_unit_measurement;
						
			$result = $Product->update();
			
			if($result){
				return response()->json(['success'=>'Product Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Product Information']);
			}
	}

	
}
