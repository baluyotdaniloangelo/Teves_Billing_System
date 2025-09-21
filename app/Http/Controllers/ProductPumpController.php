<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\ProductPumpModel;
use App\Models\TevesBranchModel;
use Session;
use Validator;
use DataTables;
use Illuminate\Validation\Rule;
class ProductPumpController extends Controller
{
	
	public function get_product_pump(Request $request){		

		if ($request->ajax()) {

    	$data =  ProductPumpModel::RightJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_pump_table.branch_idx')
					->RightJoin('teves_product_table', 'teves_product_table.product_id', '=', 'teves_product_pump_table.product_idx')
					->where('teves_product_pump_table.product_idx', $request->productID)
					->orderBy('teves_product_pump_table.pump_id', 'asc')
					->get([
						'teves_product_pump_table.pump_id',
						'teves_product_pump_table.branch_idx',
						'teves_product_pump_table.pump_name',
						'teves_product_pump_table.initial_reading',
						'teves_branch_table.branch_name',
						'teves_branch_table.branch_code',
						'teves_product_table.product_unit_measurement'
					]);
		
		return DataTables::of($data)
				->addIndexColumn()
                ->addColumn('action', function($row){
					$actionBtn = '<div align="center" class="action_table_menu_Product">
					<a href="#" data-id="'.$row->pump_id.'" class="btn-warning btn-circle btn-sm bi bi-pencil-fill btn_icon_table btn_icon_table_edit" id="ProductPump_Edit" title="Edit Product Branch Pump"></a>
					<a href="#" data-id="'.$row->pump_id.'" class="btn-warning btn-circle btn-sm bi bi-trash3-fill btn_icon_table btn_icon_table_delete" id="ProductPump_delete" title="Edit Product Branch Pump"></a>
					</div>';
                    return $actionBtn;
                })
				
				->rawColumns(['action'])
                ->make(true);
		}	
	}

	/*Pump List per Branch*/
	public function get_product_pump_per_branch(Request $request){		

			$data =  ProductPumpModel::RightJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_pump_table.branch_idx')
					->RightJoin('teves_product_table', 'teves_product_table.product_id', '=', 'teves_product_pump_table.product_idx')
					->where('teves_product_pump_table.product_idx', $request->productID)
					->where('teves_product_pump_table.branch_idx', $request->branchID)
					->orderBy('teves_product_pump_table.pump_id', 'asc')
					->get([
						'teves_product_pump_table.pump_id',
						'teves_product_pump_table.branch_idx',
						'teves_product_pump_table.pump_name',
						'teves_product_pump_table.initial_reading',
						'teves_branch_table.branch_name',
						'teves_branch_table.branch_code',
						'teves_product_table.product_unit_measurement'
					]);
		
			return response()->json($data);			
	}

	public function create_pump_post(Request $request){

		$request->validate([
          'pump_name'      	=> ['required',Rule::unique('teves_product_pump_table')->where( 
									fn ($query) =>$query
										->where('product_idx', $request->product_idx)
										->where('pump_name', $request->pump_name)
										->where('branch_idx', $request->branch_idx) 
									)],
		  'initial_reading'   	=> 'required',
		  'branch_idx'   	=> 'required'
        ], 
        [
			'pump_name.required' => 'Pump Name is required',
			'initial_reading.required' => 'Pump Capacity is Required',
			'branch_idx.required' => 'Branch is Required',
        ]
		);			

			$ProductPump = new ProductPumpModel();
			$ProductPump->product_idx						= $request->product_idx;
			$ProductPump->pump_name 						= $request->pump_name;
			$ProductPump->initial_reading 					= $request->initial_reading;
			$ProductPump->branch_idx 						= $request->branch_idx;
			
			$result = $ProductPump->save();
			
			if($result){
				return response()->json(['success'=>'Pump Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Pump Information']);
			}
	}
	
	public function product_pump_info(Request $request){

		$pumpID = $request->pumpID;
		//$data = ProductPumpModel::find($pumpID, ['branch_idx','pump_name','initial_reading']);
		
			$data =  ProductPumpModel::RightJoin('teves_branch_table', 'teves_branch_table.branch_id', '=', 'teves_product_pump_table.branch_idx')
					->RightJoin('teves_product_table', 'teves_product_table.product_id', '=', 'teves_product_pump_table.product_idx')
					->where('teves_product_pump_table.pump_id', $pumpID)
					//->where('teves_product_pump_table.branch_idx', $request->branchID)
					//->orderBy('teves_product_pump_table.pump_id', 'asc')
					->get([
						'teves_product_pump_table.pump_id',
						'teves_product_pump_table.branch_idx',
						'teves_product_pump_table.pump_name',
						'teves_product_pump_table.initial_reading',
						'teves_branch_table.branch_name',
						'teves_branch_table.branch_code',
						'teves_product_table.product_unit_measurement'
					]);
					
		return response()->json($data[0]);
		
	}

	public function update_pump_post(Request $request){

		$request->validate([
          'pump_name'      	=> ['required',Rule::unique('teves_product_pump_table')->where( 
									fn ($query) =>$query
										->where('product_idx', $request->product_idx)
										->where('pump_name', $request->pump_name)
										->where('branch_idx', $request->branch_idx) 
										->where('pump_id', '<>',  $request->pump_id )
									)],
		  'initial_reading'   	=> 'required',
		  'branch_idx'   	=> 'required'
        ], 
        [
			'pump_name.required' => 'Pump Name is required',
			'initial_reading.required' => 'Pump Capacity is Required',
			'branch_idx.required' => 'Branch is Required',
        ]
		);			

			$ProductPump = ProductPumpModel::find($request->pump_id);
			$ProductPump->product_idx						= $request->product_idx;
			$ProductPump->pump_name 						= $request->pump_name;
			$ProductPump->initial_reading 					= $request->initial_reading;
			$ProductPump->branch_idx 						= $request->branch_idx;
			
			$result = $ProductPump->update();
			
			if($result){
				return response()->json(['success'=>'Pump Information Successfully Created!']);
			}
			else{
				return response()->json(['success'=>'Error on Insert Pump Information']);
			}
	}

	/*Delete Meter Information*/
	public function delete_product_pump_confirmed(Request $request){

		$pump_id = $request->pumpID;
		ProductPumpModel::find($pump_id)->delete();
		return 'Deleted';
		
	} 
 	
}
