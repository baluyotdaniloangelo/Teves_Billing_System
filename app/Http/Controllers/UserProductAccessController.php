<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserAccountModel;
use App\Models\UserProductAccessModel;
use App\Models\ProductCategoryModel;
use Hash;
use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\Storage;

class UserProductAccessController extends Controller
{

	/*Fetch Site List using Datatable*/
	public function user_product_access(Request $request)
    {
		
		$userID = $request->UserID;
		
		if ($request->ajax()) {
		
		$user_site_access_data = ProductCategoryModel::leftJoin('teves_user_product_category_access', function($q) use ($userID)
        {
            $q->on('teves_product_category.category_id', '=', 'teves_user_product_category_access.category_idx')
				->where('teves_user_product_category_access.user_idx', '=', $userID);
        })
              		->get([
					'teves_product_category.category_id',
					'teves_user_product_category_access.user_idx',
					'teves_user_product_category_access.category_idx',
					'teves_product_category.category_name'
					]);

		return DataTables::of($user_site_access_data)
				->addIndexColumn()
                ->addColumn('action', function($row){
                    
				     $user_id 			= $row->user_idx;
					 $category_id 		= $row->category_id;
					 $access_verified 	= $row->category_idx;
										
							if($access_verified != NULL){
								
								$chk_status = "checked='checked'";
								
							}else{
								
								$chk_status = "";
								
							}
					
					$actionBtn = "<input type='checkbox' name='category_checklist' value='".$category_id."' id='CheckboxGroup1_".$category_id."' ".$chk_status."/>";
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
				
		}
		
    }

	public function add_user_product_access_post(Request $request){
		
			$userID = $request->userID;
			$category_items = $request->category_items;
	
			$category_list_ids = $category_items;
			@$category_list_arr = explode(",", $category_list_ids);

			/*RESET*/
			UserProductAccessModel::where('user_idx', $userID)->delete();

			if($category_list_ids!=''){
				
			/*LIST OF SITE ID's*/		
			foreach ($category_list_arr as $category_list_ids_row):

				@$site_id = $category_list_ids_row; 
				
				/*Re Insert Updated List*/
			
				$NewUserSiteAccess = new UserProductAccessModel();
				$NewUserSiteAccess->makeHidden(['user_name']);
				$NewUserSiteAccess->user_idx 				= $userID;
				$NewUserSiteAccess->category_idx 				= $site_id;
				$NewUserSiteAccess->created_by_user_idx 	= Session::get('loginID');
				$result = $NewUserSiteAccess->save();
			
			endforeach; 
	
				if($result){
					return response()->json(['success'=>'User Product Access Updated!']);
				}
				else{
					return response()->json(['success'=>'User Product Access Information']);
				}
			
			}
			else{
				
				return response()->json(['success'=>'User Product Access Removed!']);
			
			}
	
	}

}
