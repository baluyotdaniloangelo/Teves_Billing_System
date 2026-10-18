<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductModel;
use App\Models\ProductMainModel;
use App\Models\TevesBranchModel;
use App\Models\ProductPricePerBranchModel;
use App\Models\ProductPricePerBranchHistoryModel;
use App\Models\SupplierModel;/*June 9, 2025*/
use App\Models\ClientModel;
use App\Models\ProductCategoryModel;
use Session;
use Validator;
use DataTables;

class ProductController extends Controller
{
	
	/*Load Product Interface*/
	public function product(Request $request){
		
		if(Session::has('loginID') && (Session::get('UserType')=="Admin" || Session::get('UserType')=="SUAdmin")){
			
			$title = 'Product';
			$data = array();
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
			
			$product_category = ProductCategoryModel::all();/*May 23, 2026*/
			$teves_branch = TevesBranchModel::all();
			$supplier_data = SupplierModel::all();
			$client_data = ClientModel::all();
			$productID = $request->query('productID');
			$tab = $request->query('tab', 'product-info');
			
			return view("pages.product_information.index", compact(
				'data', 'title', 'product_category', 'teves_branch', 'supplier_data', 'client_data', 'productID', 'tab'
			));
		
		}
		
	}   
	
	/*Fetch Product List using Datatable*/
	public function getProductList(Request $request)
    {
		
		if ($request->ajax()) {

			$variantCounts = DB::table('teves_product_table')
				->select('product_main_idx', DB::raw('COUNT(*) as variants_count'))
				->whereNull('deleted_at')
				->whereNotNull('product_main_idx')
				->groupBy('product_main_idx');

	    	$data = DB::table('teves_product_main_table as main')
				->leftJoin('teves_product_category as category', 'main.category_idx', '=', 'category.category_id')
				->leftJoinSub($variantCounts, 'variant_counts', function ($join) {
					$join->on('main.product_main_id', '=', 'variant_counts.product_main_idx');
				})
				->whereNull('main.deleted_at')
				->select(
					'main.product_main_id',
					'main.product_name',
					'main.category_idx',
					'category.category_name',
					DB::raw('COALESCE(variant_counts.variants_count, 0) as variants_count')
				);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$mainId = (int) $row->product_main_id;
					$hasVariants = (int) $row->variants_count > 0;
					$deleteState = $hasVariants ? ' disabled title="Remove its variants before deleting"' : ' title="Delete main product"';
					$actionBtn = '<div class="d-flex justify-content-center gap-2 product-list-actions">
					<button type="button" data-id="'.$mainId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border manage-main-product" title="Manage variants" aria-label="Manage variants"><i class="bi bi-boxes" aria-hidden="true"></i></button>
					<button type="button" data-id="'.$mainId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border edit-main-product" title="Edit main product" aria-label="Edit main product"><i class="bi bi-pencil-square" aria-hidden="true"></i></button>
					<button type="button" data-id="'.$mainId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border delete-main-product"'.$deleteState.' aria-label="Delete main product"><i class="bi bi-trash3" aria-hidden="true"></i></button>
					</div>';
					
                    return $actionBtn;

                })
				
				->rawColumns(['action'])
                ->make(true);
		}
    }

	public function getUnassignedProductList(Request $request)
	{
		if (!$request->ajax()) return response()->json(['data' => []]);

		$data = ProductModel::query()
			->leftJoin('teves_product_category', 'teves_product_table.category_idx', '=', 'teves_product_category.category_id')
			->whereNull('teves_product_table.product_main_idx')
			->select(
				'teves_product_table.product_id',
				'teves_product_table.product_name',
				'teves_product_table.category_idx',
				'teves_product_table.product_price',
				'teves_product_table.product_unit_measurement',
				'teves_product_category.category_name'
			);

		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('action', function ($row) {
				$productId = (int) $row->product_id;
				return '<div class="d-flex justify-content-center gap-2 product-list-actions">
					<button type="button" data-id="'.$productId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border select-product" title="Manage variant details" aria-label="Manage variant details"><i class="bi bi-pencil-square" aria-hidden="true"></i></button>
					<button type="button" data-id="'.$productId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border assign-product-variant" title="Assign to main product" aria-label="Assign to main product"><i class="bi bi-link-45deg" aria-hidden="true"></i></button>
					<button type="button" data-id="'.$productId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border delete-product" title="Delete product variant" aria-label="Delete product variant"><i class="bi bi-trash3" aria-hidden="true"></i></button>
				</div>';
			})
			->rawColumns(['action'])
			->make(true);
	}

	public function getMainProductVariants(Request $request)
	{
		$request->validate(['product_main_idx' => 'required|integer|exists:teves_product_main_table,product_main_id']);

		$data = ProductModel::query()
			->leftJoin('teves_product_category', 'teves_product_table.category_idx', '=', 'teves_product_category.category_id')
			->where('teves_product_table.product_main_idx', $request->product_main_idx)
			->select(
				'teves_product_table.product_id',
				'teves_product_table.product_name',
				'teves_product_table.product_price',
				'teves_product_table.product_unit_measurement',
				'teves_product_category.category_name'
			);

		return DataTables::of($data)
			->addIndexColumn()
			->addColumn('action', function ($row) {
				$productId = (int) $row->product_id;
				return '<div class="d-flex justify-content-center gap-2 product-list-actions">
					<button type="button" data-id="'.$productId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border select-product" title="Manage variant details" aria-label="Manage variant details"><i class="bi bi-pencil-square" aria-hidden="true"></i></button>
					<button type="button" data-id="'.$productId.'" class="btn btn-light btn-sm rounded-3 shadow-sm border unlink-product-variant" title="Remove from main product" aria-label="Remove from main product"><i class="bi bi-link-45deg" aria-hidden="true"></i></button>
				</div>';
			})
			->rawColumns(['action'])
			->make(true);
	}

	public function getMainProductOptions()
	{
		$options = ProductMainModel::query()
			->leftJoin('teves_product_category', 'teves_product_main_table.category_idx', '=', 'teves_product_category.category_id')
			->orderBy('teves_product_main_table.product_name')
			->get([
				'teves_product_main_table.product_main_id',
				'teves_product_main_table.product_name',
				'teves_product_main_table.category_idx',
				'teves_product_category.category_name',
			]);

		return response()->json($options);
	}

	public function create_product_main_post(Request $request)
	{
		$request->validate([
			'product_name' => 'required|string|max:255',
			'category_idx' => 'required|integer|exists:teves_product_category,category_id',
		]);

		$mainProduct = ProductMainModel::create([
			'product_name' => trim($request->product_name),
			'category_idx' => $request->category_idx,
			'created_by_user_idx' => Session::get('loginID'),
			'updated_by_user_idx' => Session::get('loginID'),
		]);

		return response()->json(['success' => 'Main product created successfully.', 'productMainID' => $mainProduct->product_main_id]);
	}

	public function update_product_main_post(Request $request)
	{
		$request->validate([
			'product_main_id' => 'required|integer|exists:teves_product_main_table,product_main_id',
			'product_name' => 'required|string|max:255',
			'category_idx' => 'required|integer|exists:teves_product_category,category_id',
		]);

		$mainProduct = ProductMainModel::findOrFail($request->product_main_id);
		if ((int) $mainProduct->category_idx !== (int) $request->category_idx && $mainProduct->variants()->exists()) {
			return response()->json(['errors' => ['category_idx' => ['Unlink its variants before changing the main product category.']]], 422);
		}

		$mainProduct->product_name = trim($request->product_name);
		$mainProduct->category_idx = $request->category_idx;
		$mainProduct->updated_by_user_idx = Session::get('loginID');
		$mainProduct->save();

		return response()->json(['success' => 'Main product updated successfully.']);
	}

	public function delete_product_main_confirmed(Request $request)
	{
		$request->validate(['product_main_id' => 'required|integer|exists:teves_product_main_table,product_main_id']);
		$mainProduct = ProductMainModel::findOrFail($request->product_main_id);

		if ($mainProduct->variants()->exists()) {
			return response()->json(['message' => 'Remove or assign its variants before deleting this main product.'], 422);
		}

		$mainProduct->delete();
		return response()->json(['success' => 'Main product deleted successfully.']);
	}

	public function assign_product_variant(Request $request)
	{
		$request->validate([
			'product_id' => 'required|integer|exists:teves_product_table,product_id',
			'product_main_idx' => 'required|integer|exists:teves_product_main_table,product_main_id',
		]);

		$variant = ProductModel::findOrFail($request->product_id);
		$mainProduct = ProductMainModel::findOrFail($request->product_main_idx);

		if ($variant->product_main_idx !== null) {
			return response()->json(['message' => 'This variant is already assigned to a main product. Remove its current link before assigning it again.'], 422);
		}

		if ((int) $variant->category_idx !== (int) $mainProduct->category_idx) {
			return response()->json(['message' => 'The variant category must match the main product category.'], 422);
		}

		$variant->product_main_idx = $mainProduct->product_main_id;
		$variant->updated_by_user_idx = Session::get('loginID');
		$variant->save();

		return response()->json(['success' => 'Product variant assigned successfully.']);
	}

	public function unlink_product_variant(Request $request)
	{
		$request->validate([
			'product_id' => 'required|integer|exists:teves_product_table,product_id',
			'product_main_idx' => 'required|integer|exists:teves_product_main_table,product_main_id',
		]);

		$variant = ProductModel::where('product_main_idx', $request->product_main_idx)->findOrFail($request->product_id);
		$variant->product_main_idx = null;
		$variant->updated_by_user_idx = Session::get('loginID');
		$variant->save();

		return response()->json(['success' => 'Product variant removed from the main product.']);
	}

	/*Fetch Product Information*/
	public function product_info(Request $request){

		$productID = $request->productID;
		$data = ProductModel::find($productID, ['product_name','product_price','product_unit_measurement','category_idx','product_main_idx']);
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
		  'product_name'      		=> 'required|string|max:255|unique:teves_product_table,product_name',
		  'category_idx'      		=> 'required|integer|exists:teves_product_category,category_id',
		  'product_price'      		=> 'required|numeric|min:0',
		  'product_unit_measurement' => 'required|string|max:50',
		  'product_main_idx'  		=> 'nullable|integer|exists:teves_product_main_table,product_main_id',
        ], 
        [
			'product_name.required' => 'Product Name is required',
			'category_idx.required' => 'Product category is required',
			'product_price.required' => 'Price is Required'
        ]
		);

		if ($request->filled('product_main_idx')) {
			$mainProduct = ProductMainModel::findOrFail($request->product_main_idx);
			if ((int) $mainProduct->category_idx !== (int) $request->category_idx) {
				return response()->json(['errors' => ['category_idx' => ['The variant category must match the main product category.']]], 422);
			}
		}

			$Product = new ProductModel();
			
			$Product->product_main_idx 					= $request->product_main_idx ?: null;
			$Product->product_name 						= trim($request->product_name);
			$Product->category_idx 						= $request->category_idx;
			$Product->product_price 					= $request->product_price;
			$Product->product_unit_measurement 			= $request->product_unit_measurement;
			$Product->created_by_user_idx 				= Session::get('loginID');
			
			$result = $Product->save();
			
			/*Get Last ID*/
			$last_transaction_id = $Product->product_id;
			
			/*Create Entry at Product Price Per Branch*/
			
			DB::insert("INSERT INTO teves_product_branch_price_table (product_idx,branch_idx,branch_price)
			SELECT b.product_id,a.branch_id,b.product_price FROM teves_branch_table AS a,teves_product_table AS b
			WHERE b.product_id=?",[$last_transaction_id]);
			
			if($result){
				return response()->json(array('success' => "Product Information Successfully Created!, System will proceed to Update the Pricing per Branch", 'productID' => $last_transaction_id, 'productMainID' => $Product->product_main_idx), 200);
		
				return response()->json(['success'=>'Product Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Product Information']);
			}
	}

	public function update_product_post(Request $request){
		
		$request->validate([
          'productID'           	=> 'required|integer|exists:teves_product_table,product_id',
          'product_name'      		=> 'required|string|max:255|unique:teves_product_table,product_name,'.$request->productID.',product_id',
		  'category_idx'          	=> 'required|integer|exists:teves_product_category,category_id',
		  'product_price'      		=> 'required|numeric|min:0',
		  'product_unit_measurement' => 'required|string|max:50'
        ], 
        [
			'product_name.required' => 'Product Name is required',
			'product_price.required' => 'Price is Required'
        ]
		);
	
			$Product = new ProductModel();
			$Product = ProductModel::find($request->productID);
			if (!$Product) {
				return response()->json(['message' => 'Product not found.'], 404);
			}
			if ($Product->product_main_idx) {
				$mainProduct = ProductMainModel::find($Product->product_main_idx);
				if ($mainProduct && (int) $mainProduct->category_idx !== (int) $request->category_idx) {
					return response()->json(['errors' => ['category_idx' => ['The variant category must match its main product category.']]], 422);
				}
			}
			
			$Product->product_name 						= $request->product_name;
			$Product->category_idx 						= $request->category_idx;
			$Product->product_price 					= $request->product_price;
			$Product->product_unit_measurement 			= $request->product_unit_measurement;
			$Product->updated_by_user_idx 				= Session::get('loginID');
						
			$result = $Product->update();
			
			if($result){
				return response()->json(['success'=>'Product Information Successfully Updated!']);
			}
			else{
				return response()->json(['success'=>'Error on Update Product Information']);
			}
	}
	
	/*Pricing List*/
	public function get_product_pricing_per_branch_old(Request $request){		

			$data =  ProductModel::Join('teves_product_branch_price_table', 'teves_product_table.product_id', '=', 'teves_product_branch_price_table.product_idx')
              		->Join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_branch_price_table.branch_idx')
					->where('teves_product_table.product_id', $request->productID)
					->orderBy('teves_product_branch_price_table.branch_price_id', 'asc')
					->get([
						'teves_product_branch_price_table.branch_price_id',
						'teves_branch_table.branch_code',
						'teves_product_branch_price_table.branch_price',
					]);
		
			return response()->json($data);			
	}
	
	public function get_product_pricing_per_branch(Request $request){		

		if ($request->ajax()) {

    	$data =  ProductPricePerBranchModel::Join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_branch_price_table.branch_idx')
					->where('teves_product_branch_price_table.product_idx', $request->productID)
					->orderBy('teves_product_branch_price_table.branch_price_id', 'asc')
					->get([
						'teves_product_branch_price_table.branch_price_id',
						'teves_branch_table.branch_code',
						'teves_product_branch_price_table.branch_price',
						'teves_product_branch_price_table.buying_price',
						'teves_product_branch_price_table.profit_margin_type',
						'teves_product_branch_price_table.profit_margin',
						'teves_product_branch_price_table.profit_margin_in_peso',
					]);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->branch_price_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditProductPriceperBranch" title="Edit Product Branch Price"></a>
					<a href="#" data-id="'.$row->branch_price_id.'" class="btn-warning btn-circle btn-sm bi bi-clock-fill btn_icon_table btn_icon_table_view" id="ViewProductPriceperBranchHistory" title="View Product Branch Price History"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		}	
		
	}
	
	public function get_product_pricing_per_branch_history(Request $request){		

		if ($request->ajax()) {

    	$data =  ProductPricePerBranchHistoryModel::Join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_branch_price_table_history.branch_idx')
					->where('teves_product_branch_price_table_history.branch_price_idx', $request->branch_price_id)
					->orderBy('teves_product_branch_price_table_history.date_of_changes', 'desc')
					->get([
						'teves_branch_table.branch_code',
						'teves_product_branch_price_table_history.date_of_changes',
						'teves_product_branch_price_table_history.time_of_changes',
						'teves_product_branch_price_table_history.branch_price',
						'teves_product_branch_price_table_history.buying_price',
						'teves_product_branch_price_table_history.profit_margin_type',
						'teves_product_branch_price_table_history.profit_margin',
						'teves_product_branch_price_table_history.profit_margin_in_peso',
					]);
		
		return DataTables::of($data)
				->addIndexColumn()
                /*->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->branch_price_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="EditProductPriceperBranch" title="Edit Product Branch Price"></a>
					<a href="#" data-id="'.$row->branch_price_id.'" class="btn-warning btn-circle btn-sm bi bi-clock-fill btn_icon_table btn_icon_table_view" id="ViewProductPriceperBranchHistory" title="Wiew Product Branch Price History"></a>
					</div>';
                    return $actionBtn;
                })*/
				
				//->rawColumns(['action'])
                ->make(true);
		}	
		
	}	
	
	
	/*Old way to save product price per branch*/
	public function save_branches_product_pricing_post(Request $request){		
			
			$request->validate([
			'branch_price'  	=> 'required'
			], 
			[
				'branch_price.required' 	=> 'Branch Price is Required'
			]
			);
			
			$branch_price_id 	= $request->branch_price_id;
			$branch_price 		= $request->branch_price;
			
			if($branch_price!=''){
				for($count = 0; $count < count($branch_price_id); $count++)
				{
					
						$branch_price_id_item 		= $branch_price_id[$count];
						$branch_price_item 			= number_format($branch_price[$count],2, '.', '');
						
						if(Session::get('UserType')=="Admin"||Session::get('UserType')=="SUAdmin"){
								
							DB::table('teves_product_branch_price_table')
							->where('branch_price_id', $branch_price_id_item)
							->update(['branch_price' => $branch_price_item]);
							
						}					
				}		
			
			return response()->json(array('success' => "Receivable Payment Successfully Updated!"), 200);
			
			}							
	}		
	
	public function product_price_per_branch_info(Request $request){		
		
		if(Session::has('loginID')){	
			
			$branch_price_id = $request->branch_price_id;
			
			$data =  ProductModel::Join('teves_product_branch_price_table', 'teves_product_table.product_id', '=', 'teves_product_branch_price_table.product_idx')
              		->Join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_branch_price_table.branch_idx')
					->where('teves_product_branch_price_table.branch_price_id', $branch_price_id)
					->get([
						'teves_product_branch_price_table.branch_price_id',
						'teves_product_table.product_name',
						'teves_branch_table.branch_name',
						'teves_branch_table.branch_code',
						'teves_product_branch_price_table.buying_price',
						'teves_product_branch_price_table.profit_margin',
						'teves_product_branch_price_table.profit_margin_type',
						'teves_product_branch_price_table.branch_price',
					]);	
				return response()->json($data);		
		}
	
	}	
	//update_product_price_per_branch_post
	public function update_product_price_per_branch_post(Request $request){		
	
			$request->validate([
				'buying_price'  	=> 'required',
				'profit_margin'  	=> 'required'
			], 
			[
				'buying_price.required' 	=> 'Buying Price is Required',
				'profit_margin.required' 	=> 'Profit Margin is Required'
			]
			);
			
			$productID					= $request->productID;
			$branch_price_id 			= $request->branch_price_id;
			$buying_price 				= $request->buying_price;
			$profit_margin 				= $request->profit_margin;
			$profit_margin_type 		= $request->profit_margin_type;
			
			if($profit_margin_type=='Percentage'){
				$branch_price = $buying_price + ($buying_price * ($profit_margin/100));
				$profit_margin_in_peso = ($buying_price * ($profit_margin/100));
			}else{
				$branch_price = $buying_price + $profit_margin;
				$profit_margin_in_peso = $profit_margin;
			}


						if(Session::get('UserType')=="Admin"||Session::get('UserType')=="SUAdmin"){
									
						$ProductBranchPrice = new ProductPricePerBranchModel();
						$ProductBranchPrice = ProductPricePerBranchModel::find($branch_price_id);
						
						$ProductBranchPrice->branch_price 			= $branch_price;
						$ProductBranchPrice->buying_price 			= $buying_price;
						$ProductBranchPrice->profit_margin 			= $profit_margin;
						$ProductBranchPrice->profit_margin_type 	= $profit_margin_type;
						$ProductBranchPrice->profit_margin_in_peso 	= $profit_margin_in_peso;
						$ProductBranchPrice->modified_by_user_idx 				= Session::get('loginID');
									
						$ProductBranchPrice->update();						

						/*History*/
						/*Get Branch Info*/
						$ProductBranchPrice_Info =  ProductModel::Join('teves_product_branch_price_table', 'teves_product_table.product_id', '=', 'teves_product_branch_price_table.product_idx')
						->Join('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_branch_price_table.branch_idx')
						->where('teves_product_branch_price_table.branch_price_id', $branch_price_id)
						->get([
							'teves_product_branch_price_table.branch_idx'
						]);	
						
						$branch_idx = $ProductBranchPrice_Info[0]['branch_idx'];
					
						$ProductBranchPrice_history = new ProductPricePerBranchHistoryModel();
						$ProductBranchPrice_history->branch_price_idx 		= $branch_price_id;
						$ProductBranchPrice_history->product_idx 			= $productID;
						$ProductBranchPrice_history->branch_idx 			= $branch_idx;
						$ProductBranchPrice_history->branch_price 			= $branch_price;
						$ProductBranchPrice_history->buying_price 			= $buying_price;
						$ProductBranchPrice_history->profit_margin 			= $profit_margin;
						$ProductBranchPrice_history->profit_margin_type 	= $profit_margin_type;
						$ProductBranchPrice_history->profit_margin_in_peso 	= $profit_margin_in_peso;
						$ProductBranchPrice_history->created_by_user_idx 	= Session::get('loginID');			
						$ProductBranchPrice_history->save();		
						
						return response()->json(array('success' => "Branch Price Successfully Updated!"), 200);
			
			}							
	}			
	
	/*Pricing List*/
	public function get_product_list_pricing_per_branch_OLD(Request $request){		

			$raw_query_product = "SELECT a.product_id,a.product_name,ifnull(b.branch_price,a.product_price) AS product_price ,c.branch_code FROM teves_product_table AS a
			LEFT JOIN teves_product_branch_price_table b ON b.product_idx = a.product_id LEFT JOIN teves_branch_table c ON c.branch_id = b.branch_idx
			WHERE c.branch_id = ?";			
			$product_data = DB::select("$raw_query_product", [$request->branch_idx]);

			return response()->json($product_data);			
	}
	
	public function get_product_list_pricing_per_branch(Request $request)
	{
		$userId = Session::get('loginID');

		$raw_query_product = "
			SELECT
				a.product_id,
				a.product_name,
				IFNULL(b.branch_price, a.product_price) AS product_price,
				c.branch_code
			FROM teves_product_table AS a

			INNER JOIN teves_user_product_category_access upca
				ON upca.category_idx = a.category_idx
			   AND upca.user_idx = ?

			LEFT JOIN teves_product_branch_price_table b
				ON b.product_idx = a.product_id

			LEFT JOIN teves_branch_table c
				ON c.branch_id = b.branch_idx

			WHERE c.branch_id = ?
			  AND a.deleted_at IS NULL

			ORDER BY a.category_idx
		";

		$product_data = DB::select($raw_query_product, [
			$userId,
			$request->branch_idx
		]);

		return response()->json($product_data);
	}
	
	/*Load Form Form Interface*/
	public function update_product_information(Request $request){
		return redirect()->route('product', [
			'productID' => $request->query('productID'),
			'tab' => $request->query('tab', 'product-info'),
		]);
	}  		
	
}
