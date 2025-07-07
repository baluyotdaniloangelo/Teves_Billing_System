<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductModel;
use App\Models\TevesBranchModel;
use App\Models\ProductSellingPriceModel;
use App\Models\ProductSellingPriceHistoryModel;

use Session;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;

class ProductSellingPriceController extends Controller
{


	public function get_selling_price_list(Request $request){		

		if ($request->ajax()) {
 
    	$data =  ProductModel::leftJoin('teves_product_selling_price_table', 'teves_product_table.product_id', '=', 'teves_product_selling_price_table.product_idx')
			->leftJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_selling_price_table.branch_idx')
			->leftJoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_product_selling_price_table.client_idx')
			->select(
				'teves_product_selling_price_table.selling_price_id',
				'teves_product_table.product_id',
				'teves_client_table.client_name',
				'teves_product_table.product_id',
				'teves_product_table.product_name',
				'teves_branch_table.branch_code',
				DB::raw('IFNULL(teves_product_selling_price_table.selling_price, teves_product_table.product_price) AS product_price')
			)
			->where('teves_product_selling_price_table.product_idx', $request->productID)
			->get();
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->selling_price_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="ProductSellingPrice_Edit" title="Edit Product Branch Tank"></a>
					<a href="#" data-id="'.$row->selling_price_id.'" class="btn-warning btn-circle btn-sm bi bi-trash3-fill btn_icon_table btn_icon_table_delete" id="ProductSellingPrice_delete" title="Edit Product Branch Tank"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		}	
	}


	public function create_product_selling_price_post(Request $request){
	
		$selling_price_id = $request->selling_price_id;
		
		$request->validate([
          'product_idx'      	=> ['required',Rule::unique('teves_product_selling_price_table')->where( 
									fn ($query) =>$query
										->where('product_idx', $request->product_idx)
										->where('client_idx', $request->client_idx)
										->where('branch_idx', $request->branch_idx) 
										->where('selling_price_id', '<>',  $selling_price_id )
									)],
		  'selling_price'   	=> 'required',
		  'branch_idx'   	=> 'required',
		  'client_idx'   	=> 'required'
        ], 
        [
			'product_idx.required' => 'Product is required',
			'client_idx.required' => 'Client is required',
			'selling_price.required' => 'Price is Required',
			'branch_idx.required' => 'Branch is Required',
        ]
		);			
		
		if($selling_price_id==0){
		
			$ProductsellingPrice = new ProductSellingPriceModel();
			$ProductsellingPrice->product_idx		= $request->product_idx;
			$ProductsellingPrice->client_idx 		= $request->client_idx;
			$ProductsellingPrice->selling_price 		= $request->selling_price;
			$ProductsellingPrice->branch_idx 		= $request->branch_idx;
			$ProductsellingPrice->created_by_user_idx = Session::get('loginID');
			$result = $ProductsellingPrice->save();
			
			$selling_price_idx = $ProductsellingPrice->selling_price_id;
			
			$ProductsellingPriceHistory = new ProductSellingPriceHistoryModel();
			$ProductsellingPriceHistory->selling_price_idx		= $selling_price_idx;
			$ProductsellingPriceHistory->product_idx				= $request->product_idx;
			$ProductsellingPriceHistory->branch_idx 				= $request->branch_idx;
			$ProductsellingPriceHistory->client_idx 			= $request->client_idx;
			$ProductsellingPriceHistory->selling_price 			= $request->selling_price;
			$ProductsellingPriceHistory->created_by_user_idx 		= Session::get('loginID');
			$ProductsellingPriceHistory->save();
			
			$status = 'Created';
			
		}else{		
		
			$ProductsellingPrice = ProductSellingPriceModel::find($selling_price_id);
			$ProductsellingPrice->product_idx			= $request->product_idx;
			$ProductsellingPrice->client_idx 			= $request->client_idx;
			$ProductsellingPrice->selling_price 			= $request->selling_price;
			$ProductsellingPrice->branch_idx 			= $request->branch_idx;
			$ProductsellingPrice->modified_by_user_idx = Session::get('loginID');
			$result = $ProductsellingPrice->save();
			
			$ProductsellingPriceHistory = new ProductSellingPriceHistoryModel();
			$ProductsellingPriceHistory->selling_price_idx		= $selling_price_id;
			$ProductsellingPriceHistory->product_idx				= $request->product_idx;
			$ProductsellingPriceHistory->branch_idx 				= $request->branch_idx;
			$ProductsellingPriceHistory->client_idx 			= $request->client_idx;
			$ProductsellingPriceHistory->selling_price 			= $request->selling_price;
			$ProductsellingPriceHistory->modified_by_user_idx 	= Session::get('loginID');
			$ProductsellingPriceHistory->save();
			
			$status = 'Updated';
			
		}
			if($result){
				return response()->json(['success'=>"Selling Price Information Successfully $status!"]);
			}
			else{
				return response()->json(['success'=>'Error on Insert selling Price Information']);
			}
			
	}
	
	public function product_selling_price_info(Request $request){

		$selling_price_id = $request->selling_price_id;
		
		$data =  ProductModel::leftJoin('teves_product_selling_price_table', 'teves_product_table.product_id', '=', 'teves_product_selling_price_table.product_idx')
		->leftJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_selling_price_table.branch_idx')
		->leftJoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_product_selling_price_table.client_idx')
		->select(
			'teves_product_selling_price_table.selling_price_id',
			'teves_product_table.product_id',
			'teves_product_selling_price_table.client_idx',
			'teves_client_table.client_name',
			'teves_product_table.product_id',
			'teves_product_table.product_name',
			'teves_product_selling_price_table.branch_idx',
			'teves_branch_table.branch_code',
			DB::raw('IFNULL(teves_product_selling_price_table.selling_price, teves_product_table.product_price) AS product_price')
			)
		->where('teves_product_selling_price_table.selling_price_id', $selling_price_id)
		->get();
			
		return response()->json($data);
		
	}

	/*Delete Information*/
	public function delete_selling_price_info_confirmed(Request $request){

		$selling_price_id = $request->selling_price_id;
		ProductSellingPriceModel::find($selling_price_id)->delete();
		return 'Deleted';
		
	} 
 	
}
