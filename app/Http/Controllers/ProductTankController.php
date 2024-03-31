<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductTankModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;

class ProductTankController extends Controller
{
	
	/*Pricing List*/
	public function get_product_tank(Request $request){		

			$data =  ProductTankModel::RightJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_tank_table.branch_idx')
					->where('teves_product_tank_table.product_idx', $request->productID)
					->orderBy('teves_product_tank_table.tank_id', 'asc')
					->get([
						'teves_product_tank_table.tank_id',
						'teves_product_tank_table.branch_idx',
						'teves_product_tank_table.tank_name',
						'teves_product_tank_table.tank_capacity',
						'teves_branch_table.branch_name'
					]);
		
			return response()->json($data);			
	}

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
						
						if(Session::get('UserType')=="Admin"){
								
							DB::table('teves_product_branch_price_table')
							->where('branch_price_id', $branch_price_id_item)
							->update(['branch_price' => $branch_price_item]);
							
						}					
				}		
			
			return response()->json(array('success' => "Receivable Payment Successfully Updated!"), 200);
			
			}							
	}		
	
	/*Pricing List*/
	public function get_product_list_pricing_per_branch(Request $request){		

			$raw_query_product = "SELECT a.product_id,a.product_name,ifnull(b.branch_price,a.product_price) AS product_price ,c.branch_code FROM teves_product_table AS a
			LEFT JOIN teves_product_branch_price_table b ON b.product_idx = a.product_id LEFT JOIN teves_branch_table c ON c.branch_id = b.branch_idx
			WHERE c.branch_id = ?";			
			$product_data = DB::select("$raw_query_product", [$request->branch_idx]);

			return response()->json($product_data);			
	}
	
	/*Load Form Form Interface*/
	public function update_product_information(Request $request){
		
		if(Session::has('loginID')){
			
			$productID = $request->productID;
			$tab = $request->tab;
			
			$title = 'Update Product Information';
			$data = array();
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();/*User Data*/
			
			$teves_branch = TevesBranchModel::all();
			
			return view("pages.update_product_information_form", compact('data','title','teves_branch', 'tab', 'productID'));
		
		}
		
	}  		
	
}
