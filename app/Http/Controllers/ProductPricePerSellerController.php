<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductModel;
use App\Models\ProductTankModel;
use App\Models\TevesBranchModel;
use App\Models\ProductPricePerSellerModel;
use App\Models\ProductPricePerSellerHistoryModel;

use Session;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;

class ProductPricePerSellerController extends Controller
{


	public function get_product_price_per_seller(Request $request){		

		if ($request->ajax()) {
 
    	$data =  ProductModel::leftJoin('teves_product_seller_price_table', 'teves_product_table.product_id', '=', 'teves_product_seller_price_table.product_idx')
			->leftJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_seller_price_table.branch_idx')
			->leftJoin('teves_supplier_table', 'teves_supplier_table.supplier_id', '=', 'teves_product_seller_price_table.supplier_idx')
			->select(
				'teves_product_seller_price_table.seller_price_id',
				'teves_product_table.product_id',
				'teves_supplier_table.supplier_name',
				'teves_product_table.product_id',
				'teves_product_table.product_name',
				'teves_branch_table.branch_code',
				DB::raw('IFNULL(teves_product_seller_price_table.seller_price, teves_product_table.product_price) AS product_price')
			)
			->where('teves_product_seller_price_table.product_idx', $request->productID)
			->get();
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->seller_price_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="ProductSellerPrice_Edit" title="Edit Product Branch Tank"></a>
					<a href="#" data-id="'.$row->seller_price_id.'" class="btn-warning btn-circle btn-sm bi bi-trash3-fill btn_icon_table btn_icon_table_delete" id="ProductSellerPrice_delete" title="Edit Product Branch Tank"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		}	
	}


	public function create_product_seller_price_post(Request $request){
	
		$seller_price_id = $request->seller_price_id;
		
		$request->validate([
          'product_idx'      	=> ['required',Rule::unique('teves_product_seller_price_table')->where( 
									fn ($query) =>$query
										->where('product_idx', $request->product_idx)
										->where('supplier_idx', $request->supplier_idx)
										->where('branch_idx', $request->branch_idx) 
										->where('seller_price_id', '<>',  $seller_price_id )
									)],
		  'seller_price'   	=> 'required',
		  'branch_idx'   	=> 'required',
		  'supplier_idx'   	=> 'required'
        ], 
        [
			'product_idx.required' => 'Product is required',
			'supplier_idx.required' => 'Supplier is required',
			'seller_price.required' => 'Price is Required',
			'branch_idx.required' => 'Branch is Required',
        ]
		);			
		
		if($seller_price_id==0){
		
			$ProductSellerPrice = new ProductPricePerSellerModel();
			$ProductSellerPrice->product_idx		= $request->product_idx;
			$ProductSellerPrice->supplier_idx 		= $request->supplier_idx;
			$ProductSellerPrice->seller_price 		= $request->seller_price;
			$ProductSellerPrice->branch_idx 		= $request->branch_idx;
			$ProductSellerPrice->created_by_user_idx = Session::get('loginID');
			$result = $ProductSellerPrice->save();
			
			$seller_price_idx = $ProductSellerPrice->seller_price_id;
			
			$ProductSellerPriceHistory = new ProductPricePerSellerHistoryModel();
			$ProductSellerPriceHistory->seller_price_idx		= $seller_price_idx;
			$ProductSellerPriceHistory->product_idx				= $request->product_idx;
			$ProductSellerPriceHistory->branch_idx 				= $request->branch_idx;
			$ProductSellerPriceHistory->supplier_idx 			= $request->supplier_idx;
			$ProductSellerPriceHistory->seller_price 			= $request->seller_price;
			$ProductSellerPriceHistory->created_by_user_idx 		= Session::get('loginID');
			$ProductSellerPriceHistory->save();
			
			$status = 'Created';
			
		}else{		
		
			$ProductSellerPrice = ProductPricePerSellerModel::find($seller_price_id);
			$ProductSellerPrice->product_idx			= $request->product_idx;
			$ProductSellerPrice->supplier_idx 			= $request->supplier_idx;
			$ProductSellerPrice->seller_price 			= $request->seller_price;
			$ProductSellerPrice->branch_idx 			= $request->branch_idx;
			$ProductSellerPrice->modified_by_user_idx = Session::get('loginID');
			$result = $ProductSellerPrice->save();
			
			$ProductSellerPriceHistory = new ProductPricePerSellerHistoryModel();
			$ProductSellerPriceHistory->seller_price_idx		= $seller_price_id;
			$ProductSellerPriceHistory->product_idx				= $request->product_idx;
			$ProductSellerPriceHistory->branch_idx 				= $request->branch_idx;
			$ProductSellerPriceHistory->supplier_idx 			= $request->supplier_idx;
			$ProductSellerPriceHistory->seller_price 			= $request->seller_price;
			$ProductSellerPriceHistory->modified_by_user_idx 	= Session::get('loginID');
			$ProductSellerPriceHistory->save();
			
			$status = 'Updated';
			
		}
			if($result){
				return response()->json(['success'=>"Seller Price Information Successfully $status!"]);
			}
			else{
				return response()->json(['success'=>'Error on Insert Seller Price Information']);
			}
			
	}
	
	public function product_price_per_seller_info(Request $request){

		$seller_price_id = $request->seller_price_id;
		
		$data =  ProductModel::leftJoin('teves_product_seller_price_table', 'teves_product_table.product_id', '=', 'teves_product_seller_price_table.product_idx')
		->leftJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_seller_price_table.branch_idx')
		->leftJoin('teves_supplier_table', 'teves_supplier_table.supplier_id', '=', 'teves_product_seller_price_table.supplier_idx')
		->select(
			'teves_product_seller_price_table.seller_price_id',
			'teves_product_table.product_id',
			'teves_product_seller_price_table.supplier_idx',
			'teves_supplier_table.supplier_name',
			'teves_product_table.product_id',
			'teves_product_table.product_name',
			'teves_product_seller_price_table.branch_idx',
			'teves_branch_table.branch_code',
			DB::raw('IFNULL(teves_product_seller_price_table.seller_price, teves_product_table.product_price) AS product_price')
			)
		->where('teves_product_seller_price_table.seller_price_id', $seller_price_id)
		->get();
			
		return response()->json($data);
		
	}

	/*Delete Information*/
	public function delete_product_price_per_seller_info_confirmed(Request $request){

		$seller_price_id = $request->seller_price_id;
		ProductPricePerSellerModel::find($seller_price_id)->delete();
		return 'Deleted';
		
	} 
 	
}
