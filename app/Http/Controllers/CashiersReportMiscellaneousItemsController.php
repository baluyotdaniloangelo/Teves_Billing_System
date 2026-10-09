<?php
namespace App\Http\Controllers;
//use Request;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\CashiersReportModel;
use App\Models\CashiersReportModel_P1;
use App\Models\CashiersReportModel_P3;

use App\Models\ProductModel;
use App\Models\TevesBranchModel;

use App\Models\SOBillingTransactionModel;
use App\Models\BillingTransactionModel;

use Session;
use Validator;
use DataTables;
use Illuminate\Support\Facades\DB;

use Illuminate\Validation\Rule;

use Carbon\Carbon;

class CashiersReportMiscellaneousItemsController extends Controller
{
	
	public function save_product_cashiers_report_PH3_OLD(Request $request){	

		$miscellaneous_items_type	= $request->miscellaneous_items_type;
		
		if($miscellaneous_items_type=='OTHERS' || $miscellaneous_items_type=='CASHOUT'){
			
			$request->validate([
				'item_description'      	=> 'required',
				'product_manual_price' 		=> 'required',	
			], 
			[
				'item_description'      	=> 'Item Description required',
				'product_manual_price.required' 	=> 'Amount Required',
			]
			);
			
		}
		else if($miscellaneous_items_type=='SALES_CREDIT'){
			
			$request->validate([
				'miscellaneous_items_type' 	=> 'required',
				'item_description'      	=> 'required',
				'order_quantity'  			=> 'required',
				'client_idx'				=> 'required'
			], 
			[
				'miscellaneous_items_type.required' 	=> 'Type is Required',
				'item_description'      	=> 'Item Description required',
				'order_quantity.required' 	=> 'Order Quantity is Required',
				'client_idx.required' 		=> 'Account Name is Required',
			]
			);	
			
		}
		else if($miscellaneous_items_type=='DISCOUNTS'){
			
			$request->validate([
				'item_description'      	=> 'required',
				'order_quantity'  			=> 'required',
				'client_idx'				=> 'required'
			], 
			[
				'item_description'      	=> 'Item Description required',
				'order_quantity.required' 	=> 'Order Quantity is Required',
				'client_idx.required' 		=> 'Account Name is Required',
			]
			);	
			
		}
		else{
			
			$request->validate([
				'miscellaneous_items_type' 	=> 'required',
				'item_description'      	=> 'required',
				'order_quantity'  			=> 'required'		
			], 
			[
				'miscellaneous_items_type.required' 	=> 'Type is Required',
				'item_description'      	=> 'Item Description required',
				'order_quantity.required' 	=> 'Order Quantity is Required',
			]
			);	
			
		}
			
			/*Get Cashier Report ID*/
			$CashiersReportId = $request->CashiersReportId;

            $reference_no				= $request->reference_no;

			$product_idx				= $request->product_idx;
			$order_quantity 			= $request->order_quantity;
			$product_manual_price 		= $request->product_manual_price + 0;
			
			$branch_idx 		= $request->branch_idx;
			$order_time 		= $request->order_time;
			
			
			$CHPH3_ID 			= $request->CHPH3_ID;
			$pump_price_data =  CashiersReportModel_P1::where('cashiers_report_id', $CashiersReportId)
				->where('product_idx', $product_idx)
				->skip(0)
				->take(1)
					->get([
						'teves_cashiers_report_p1.cashiers_report_p1_id',
						'teves_cashiers_report_p1.product_price'
						]);	
					
			$pump_price = @$pump_price_data[0]['product_price']+0;
			$cashiers_report_p1_id = @$pump_price_data[0]['cashiers_report_p1_id']+0;


            if($miscellaneous_items_type=='SALES_CREDIT'){

                    if($pump_price==0){
				
				        $discounted_price 	= 0;
				        $product_price 		= $product_manual_price;
					
					}else{
			
				        /*Check if Price is From Manual Price*/
				        if($product_manual_price!=0){
					        $product_price = $pump_price;
					        $discounted_price 	= $pump_price;
				        }else{
					        $discounted_price 	= 0;
					        $product_price = $pump_price;
				        }
						
			        }
					
					$peso_sales = ($order_quantity * $product_price);
					
					if($CHPH3_ID=='' || $CHPH3_ID ==0){	
					
					/*Insert New SO*/
					/*Save to SO and Billing ITEM*/
					/*insert SO*/
						
						$reference_no_id = $request->reference_no_id + 0;
						
						if($reference_no_id==0){
					
							$SOBilling = new SOBillingTransactionModel();
							$SOBilling->cashiers_report_idx	= $CashiersReportId;
							$SOBilling->branch_idx 			= $request->branch_idx;
							$SOBilling->order_date 			= $request->report_date;
							$SOBilling->order_time 			= '00:00';
							$SOBilling->so_number 			= $reference_no;	
							$SOBilling->client_idx 			= $request->client_idx;
							$SOBilling->plate_no 			= 'N/A';
							$SOBilling->drivers_name 		= 'N/A';
							$SOBilling->created_by_user_id 	= Session::get('loginID');
							$result_so = $SOBilling->save();
							
							$so_id 		= $SOBilling->so_id;
							
						}else{
							
							$so_id 		= $request->reference_no_id;
							
						}
						
						/*Insert Product SO*/	
						$Billing = new BillingTransactionModel();
						$Billing->so_idx 				= $so_id;
						$Billing->cashiers_report_idx 	= $CashiersReportId;
						$Billing->branch_idx 			= $request->branch_idx;
						$Billing->order_date 			= $request->report_date;
						$Billing->order_time 			= $order_time;
						$Billing->order_po_number 		= $reference_no;	
						$Billing->client_idx 			= $request->client_idx;
						$Billing->plate_no 				= 'N/A';
						$Billing->drivers_name 			= 'N/A';
						$Billing->product_idx 			= $request->product_idx;
						$Billing->product_price 		= $product_price;
						$Billing->order_quantity 		= $request->order_quantity;
						$Billing->order_total_amount 	= $peso_sales;
						$Billing->created_by_user_idx 	= Session::get('loginID');
						$result_Billing = $Billing->save();
						
						$billing_id 		= $Billing->billing_id;
					}
					else{
						
						$billing_id =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
								->get([
									'teves_cashiers_report_p3.billing_idx',
									]);	
									
						$reference_no_id 		= $request->reference_no_id + 0;
						
						if($reference_no_id==0){
					
							$SOBilling = new SOBillingTransactionModel();
							$SOBilling->cashiers_report_idx	= $CashiersReportId;
							$SOBilling->branch_idx 			= $request->branch_idx;
							$SOBilling->order_date 			= $request->report_date;
							$SOBilling->order_time 			= $order_time;
							$SOBilling->so_number 			= $reference_no;	
							$SOBilling->client_idx 			= $request->client_idx;
							$SOBilling->plate_no 			= 'N/A';
							$SOBilling->drivers_name 		= 'N/A';
							$SOBilling->created_by_user_id 	= Session::get('loginID');
							$result_so = $SOBilling->save();
							
							$so_id 		= $SOBilling->so_id;
							
						}else{
							
							$so_id 		= $request->reference_no_id;
							
						}
						
							/*UPDATE*/
							/*Update Product SO*/	
							$Billing = new BillingTransactionModel();
							$Billing = BillingTransactionModel::find($billing_id[0]['billing_idx']);
							$Billing->branch_idx 			= $request->branch_idx;
							$Billing->so_idx 				= $so_id;
							$Billing->order_date 			= $request->report_date;
							$Billing->order_po_number 		= $reference_no;	
							$Billing->client_idx 			= $request->client_idx;
						
							if($request->billing_update=="YES"){	
								$Billing->product_idx 			= $request->product_idx;
								$Billing->product_price 		= $product_price;
								$Billing->order_quantity 		= $request->order_quantity;
								$Billing->order_time 			= $request->order_time;
								$Billing->order_total_amount 	= $peso_sales;
							}	
							
							$Billing->updated_by_user_idx 	= Session::get('loginID');
							
							$result = $Billing->update();
						
					}
					
            }else if($miscellaneous_items_type=='DISCOUNTS'){

                    if($pump_price==0){
				
				        $discounted_price 	= 0;
				        $product_price 		= $product_manual_price;
				
			        }else{
			
				        /*Check if Price is From Manual Price*/
				        if($product_manual_price!=0){
					        $product_price = $pump_price - $product_manual_price;
					        $discounted_price 	= $pump_price - $product_manual_price;
				        }else{
					        $discounted_price 	= 0;
					        $product_price = $pump_price;
				        }
			
			        }
					$peso_sales = ($order_quantity * $product_price);
					$so_id = 0;

            }else if($miscellaneous_items_type=='OTHERS' || $miscellaneous_items_type=='CASHOUT'){

                   
				        $discounted_price 	= 0;
				        $product_price 		= $product_manual_price;
				
			       
					$peso_sales = ($order_quantity * $product_price);
					$so_id = 0;
            }else{

			        if($pump_price==0){
				
				        $discounted_price 	= 0;
				        $product_price 		= $product_manual_price;
				
			        }else{
			
				        /*Check if Price is From Manual Price*/
				        if($product_manual_price!=0){
					        $product_price = $pump_price - $product_manual_price;
					        $discounted_price 	= $pump_price - $product_manual_price;
				        }else{
					        $discounted_price 	= 0;
					        $product_price = $pump_price;
				        }
			
			        }
					 $peso_sales = ($product_price);
					 $so_id = 0;
             }
			 	
								if($CHPH3_ID=='' || $CHPH3_ID ==0){	
								
									$CashiersReportModel_P3 = new CashiersReportModel_P3();
									
									$CashiersReportModel_P3->user_idx 					= Session::get('loginID');
									$CashiersReportModel_P3->billing_idx 				= @$billing_id;
									$CashiersReportModel_P3->cashiers_report_id 		= $CashiersReportId;
                                    $CashiersReportModel_P3->miscellaneous_items_type 	= $miscellaneous_items_type;
                                    $CashiersReportModel_P3->so_idx 					= @$so_id;
									$CashiersReportModel_P3->reference_no 				= $reference_no;
									$CashiersReportModel_P3->order_time 				= $order_time;
									$CashiersReportModel_P3->client_idx		 			= $request->client_idx;
									
										if($miscellaneous_items_type!='OTHERS' || $miscellaneous_items_type!='CASHOUT'){
											$CashiersReportModel_P3->product_idx 				= $product_idx;
										}
										
									$CashiersReportModel_P3->item_description 			= $request->item_description;
									$CashiersReportModel_P3->order_quantity 			= $order_quantity;
                                    $CashiersReportModel_P3->pump_price 				= $pump_price;
									$CashiersReportModel_P3->unit_price 				= $product_manual_price;
									$CashiersReportModel_P3->discounted_price 			= $discounted_price;
									$CashiersReportModel_P3->order_total_amount 		= $peso_sales;
									$CashiersReportModel_P3->created_by_user_id 		= Session::get('loginID');
									$result = $CashiersReportModel_P3->save();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Created!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}else{
																	
									$CashiersReportModel_P3 = new CashiersReportModel_P3();
									$CashiersReportModel_P3 = CashiersReportModel_P3::find($CHPH3_ID);
                                    $CashiersReportModel_P3->miscellaneous_items_type 	= $miscellaneous_items_type;
                                    $CashiersReportModel_P3->so_idx 					= @$so_id;
									$CashiersReportModel_P3->reference_no 				= $reference_no;
									$CashiersReportModel_P3->order_time 				= $order_time;
									$CashiersReportModel_P3->client_idx		 			= $request->client_idx;
									
										if($miscellaneous_items_type!='OTHERS' || $miscellaneous_items_type!='CASHOUT'){
											$CashiersReportModel_P3->product_idx 				= $product_idx;
										}
										
									$CashiersReportModel_P3->item_description 			= $request->item_description;
									$CashiersReportModel_P3->order_quantity 			= $order_quantity;
                                    $CashiersReportModel_P3->pump_price 			    = $pump_price;
									$CashiersReportModel_P3->unit_price 				= $product_manual_price;
									$CashiersReportModel_P3->discounted_price 			= $discounted_price;
									$CashiersReportModel_P3->order_total_amount 		= $peso_sales;
									$CashiersReportModel_P3->updated_by_user_id 		= Session::get('loginID');
									$result = $CashiersReportModel_P3->update();
									
									if($result){
										return response()->json(['success'=>'Product Successfully Updated!']);
									}
									else{
										return response()->json(['success'=>'Error on Product Information']);
									}
									
								}
								
	}	

public function save_product_cashiers_report_PH3(Request $request)
{
    /*==================================================
    BASIC VARIABLES
    ==================================================*/

    $CHPH3_ID = $request->CHPH3_ID;

    $CashiersReportId = $request->CashiersReportId;

    $miscellaneous_items_type =
        $request->miscellaneous_items_type;


    /*==================================================
    VALIDATION
    ==================================================*/

    /*
     * IMPORTANT:
     *
     * SALES_CREDIT:
     * - Client is REQUIRED
     *
     * DISCOUNTS:
     * - Client is NOT REQUIRED
     *
     * OTHERS / CASHOUT:
     * - Client is NOT REQUIRED
     *
     * UPDATE:
     * - miscellaneous_items_type must NOT be changed
     */

    if ($CHPH3_ID && $CHPH3_ID != 0)
    {
        /*
         *==================================================
         * UPDATE VALIDATION
         *==================================================
         *
         * Do not require miscellaneous_items_type from
         * the request during update because the frontend
         * does not send it anymore.
         */

        $existingRecord =
            CashiersReportModel_P3::find($CHPH3_ID);

        if (!$existingRecord)
        {
            return response()->json([
                'message' => 'Miscellaneous Sales record not found.'
            ], 404);
        }

        /*
         * Use the EXISTING type from the database.
         */
        $miscellaneous_items_type =
            $existingRecord->miscellaneous_items_type;


        /*==================================================
        SALES CREDIT UPDATE
        Client is REQUIRED
        ==================================================*/

        if ($miscellaneous_items_type == 'SALES_CREDIT')
        {
            $request->validate(
                [
                    'item_description' => 'required',
                    'order_quantity'   => 'required',
                    'client_idx'       => 'required',
                ],
                [
                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',

                    'client_idx.required' =>
                        'Account Name is Required',
                ]
            );
        }


        /*==================================================
        DISCOUNTS UPDATE
        Client is NOT REQUIRED
        ==================================================*/

        else if ($miscellaneous_items_type == 'DISCOUNTS')
        {
            $request->validate(
                [
                    'item_description' => 'required',
                    'order_quantity'   => 'required',
                ],
                [
                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',
                ]
            );
        }


        /*==================================================
        OTHERS / CASHOUT UPDATE
        Client is NOT REQUIRED
        ==================================================*/

        else if (
            $miscellaneous_items_type == 'OTHERS' ||
            $miscellaneous_items_type == 'CASHOUT'
        )
        {
            $request->validate(
                [
                    'item_description'     => 'required',
                    'product_manual_price' => 'required',
                ],
                [
                    'item_description.required' =>
                        'Item Description required',

                    'product_manual_price.required' =>
                        'Amount Required',
                ]
            );
        }


        /*==================================================
        OTHER TYPES UPDATE
        ==================================================*/

        else
        {
            $request->validate(
                [
                    'item_description' => 'required',
                    'order_quantity'   => 'required',
                ],
                [
                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',
                ]
            );
        }
    }
    else
    {
        /*==================================================
        NEW RECORD VALIDATION
        ==================================================*/

        /*
         * Type is required ONLY when creating a new record.
         */

        if ($miscellaneous_items_type == 'SALES_CREDIT')
        {
            $request->validate(
                [
                    'miscellaneous_items_type' => 'required',
                    'item_description'        => 'required',
                    'order_quantity'          => 'required',
                    'client_idx'              => 'required',
                ],
                [
                    'miscellaneous_items_type.required' =>
                        'Type is Required',

                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',

                    'client_idx.required' =>
                        'Account Name is Required',
                ]
            );
        }

        else if ($miscellaneous_items_type == 'DISCOUNTS')
        {
            $request->validate(
                [
                    'miscellaneous_items_type' => 'required',
                    'item_description'        => 'required',
                    'order_quantity'          => 'required',
                ],
                [
                    'miscellaneous_items_type.required' =>
                        'Type is Required',

                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',
                ]
            );
        }

        else if (
            $miscellaneous_items_type == 'OTHERS' ||
            $miscellaneous_items_type == 'CASHOUT'
        )
        {
            $request->validate(
                [
                    'miscellaneous_items_type' => 'required',
                    'item_description'        => 'required',
                    'product_manual_price'    => 'required',
                ],
                [
                    'miscellaneous_items_type.required' =>
                        'Type is Required',

                    'item_description.required' =>
                        'Item Description required',

                    'product_manual_price.required' =>
                        'Amount Required',
                ]
            );
        }

        else
        {
            $request->validate(
                [
                    'miscellaneous_items_type' => 'required',
                    'item_description'        => 'required',
                    'order_quantity'          => 'required',
                ],
                [
                    'miscellaneous_items_type.required' =>
                        'Type is Required',

                    'item_description.required' =>
                        'Item Description required',

                    'order_quantity.required' =>
                        'Order Quantity is Required',
                ]
            );
        }
    }


    /*==================================================
    GET REQUEST DATA
    ==================================================*/

    $reference_no =
        $request->reference_no;

    $product_idx =
        $request->product_idx;

    $order_quantity =
        $request->order_quantity;

    $product_manual_price =
        ($request->product_manual_price ?? 0) + 0;

    $branch_idx =
        $request->branch_idx;

    $order_time =
        $request->order_time;


    /*==================================================
    GET PUMP PRICE
    ==================================================*/

    $pump_price_data =
        CashiersReportModel_P1::where(
            'cashiers_report_id',
            $CashiersReportId
        )
        ->where(
            'product_idx',
            $product_idx
        )
        ->skip(0)
        ->take(1)
        ->get([
            'teves_cashiers_report_p1.cashiers_report_p1_id',
            'teves_cashiers_report_p1.product_price'
        ]);


    $pump_price =
        @$pump_price_data[0]['product_price'] + 0;

    $cashiers_report_p1_id =
        @$pump_price_data[0]['cashiers_report_p1_id'] + 0;


    /*==================================================
    SALES CREDIT
    ==================================================*/

    if ($miscellaneous_items_type == 'SALES_CREDIT')
    {
        if ($pump_price == 0)
        {
            $discounted_price = 0;

            $product_price =
                $product_manual_price;
        }
        else
        {
            /*
             * Check if Price is From Manual Price
             */

            if ($product_manual_price != 0)
            {
                $product_price =
                    $pump_price;

                $discounted_price =
                    $pump_price;
            }
            else
            {
                $discounted_price = 0;

                $product_price =
                    $pump_price;
            }
        }


        $peso_sales =
            ($order_quantity * $product_price);


        /*==================================================
        NEW SALES CREDIT
        ==================================================*/

        if ($CHPH3_ID == '' || $CHPH3_ID == 0)
        {
            /*
             * Insert New SO
             */

            $reference_no_id =
                ($request->reference_no_id ?? 0) + 0;


            if ($reference_no_id == 0)
            {
                $SOBilling =
                    new SOBillingTransactionModel();

                $SOBilling->cashiers_report_idx =
                    $CashiersReportId;

                $SOBilling->branch_idx =
                    $request->branch_idx;

                $SOBilling->order_date =
                    $request->report_date;

                $SOBilling->order_time =
                    '00:00';

                $SOBilling->so_number =
                    $reference_no;

                $SOBilling->client_idx =
                    $request->client_idx;

                $SOBilling->plate_no =
                    'N/A';

                $SOBilling->drivers_name =
                    'N/A';

                $SOBilling->created_by_user_id =
                    Session::get('loginID');

                $result_so =
                    $SOBilling->save();

                $so_id =
                    $SOBilling->so_id;
            }
            else
            {
                $so_id =
                    $request->reference_no_id;
            }


            /*==================================================
            INSERT PRODUCT SO
            ==================================================*/

            $Billing =
                new BillingTransactionModel();

            $Billing->so_idx =
                $so_id;

            $Billing->cashiers_report_idx =
                $CashiersReportId;

            $Billing->branch_idx =
                $request->branch_idx;

            $Billing->order_date =
                $request->report_date;

            $Billing->order_time =
                $order_time;

            $Billing->order_po_number =
                $reference_no;

            $Billing->client_idx =
                $request->client_idx;

            $Billing->plate_no =
                'N/A';

            $Billing->drivers_name =
                'N/A';

            $Billing->product_idx =
                $request->product_idx;

            $Billing->product_price =
                $product_price;

            $Billing->order_quantity =
                $request->order_quantity;

            $Billing->order_total_amount =
                $peso_sales;

            $Billing->created_by_user_idx =
                Session::get('loginID');

            $result_Billing =
                $Billing->save();

            $billing_id =
                $Billing->billing_id;
        }
        else
        {
            /*==================================================
            GET EXISTING BILLING ID
            ==================================================*/

            $billing_id =
                CashiersReportModel_P3::where(
                    'cashiers_report_p3_id',
                    $CHPH3_ID
                )
                ->get([
                    'teves_cashiers_report_p3.billing_idx',
                ]);


            $reference_no_id =
                ($request->reference_no_id ?? 0) + 0;


            /*==================================================
            CREATE SO IF NEEDED
            ==================================================*/

            if ($reference_no_id == 0)
            {
                $SOBilling =
                    new SOBillingTransactionModel();

                $SOBilling->cashiers_report_idx =
                    $CashiersReportId;

                $SOBilling->branch_idx =
                    $request->branch_idx;

                $SOBilling->order_date =
                    $request->report_date;

                $SOBilling->order_time =
                    $order_time;

                $SOBilling->so_number =
                    $reference_no;

                $SOBilling->client_idx =
                    $request->client_idx;

                $SOBilling->plate_no =
                    'N/A';

                $SOBilling->drivers_name =
                    'N/A';

                $SOBilling->created_by_user_id =
                    Session::get('loginID');

                $result_so =
                    $SOBilling->save();

                $so_id =
                    $SOBilling->so_id;
            }
            else
            {
                $so_id =
                    $request->reference_no_id;
            }


            /*==================================================
            UPDATE BILLING
            ==================================================*/

            if (!empty($billing_id))
            {
                $Billing =
                    BillingTransactionModel::find(
                        $billing_id[0]['billing_idx']
                    );

                if ($Billing)
                {
                    $Billing->branch_idx =
                        $request->branch_idx;

                    $Billing->so_idx =
                        $so_id;

                    $Billing->order_date =
                        $request->report_date;

                    $Billing->order_po_number =
                        $reference_no;

                    $Billing->client_idx =
                        $request->client_idx;


                    if ($request->billing_update == "YES")
                    {
                        $Billing->product_idx =
                            $request->product_idx;

                        $Billing->product_price =
                            $product_price;

                        $Billing->order_quantity =
                            $request->order_quantity;

                        $Billing->order_time =
                            $request->order_time;

                        $Billing->order_total_amount =
                            $peso_sales;
                    }


                    $Billing->updated_by_user_idx =
                        Session::get('loginID');

                    $Billing->update();
                }
            }
        }
    }


    /*==================================================
    DISCOUNTS
    ==================================================*/

    else if ($miscellaneous_items_type == 'DISCOUNTS')
    {
        if ($pump_price == 0)
        {
            $discounted_price = 0;

            $product_price =
                $product_manual_price;
        }
        else
        {
            /*
             * Check if Price is From Manual Price
             */

            if ($product_manual_price != 0)
            {
                $product_price =
                    $pump_price -
                    $product_manual_price;

                $discounted_price =
                    $pump_price -
                    $product_manual_price;
            }
            else
            {
                $discounted_price = 0;

                $product_price =
                    $pump_price;
            }
        }


        $peso_sales =
            ($order_quantity * $product_price);

        $so_id = 0;
    }


    /*==================================================
    OTHERS / CASHOUT
    ==================================================*/

    else if (
        $miscellaneous_items_type == 'OTHERS' ||
        $miscellaneous_items_type == 'CASHOUT'
    )
    {
        $discounted_price = 0;

        $product_price =
            $product_manual_price;

        $peso_sales =
            ($order_quantity * $product_price);

        $so_id = 0;
    }


    /*==================================================
    OTHER TYPE
    ==================================================*/

    else
    {
        if ($pump_price == 0)
        {
            $discounted_price = 0;

            $product_price =
                $product_manual_price;
        }
        else
        {
            if ($product_manual_price != 0)
            {
                $product_price =
                    $pump_price -
                    $product_manual_price;

                $discounted_price =
                    $pump_price -
                    $product_manual_price;
            }
            else
            {
                $discounted_price = 0;

                $product_price =
                    $pump_price;
            }
        }


        $peso_sales =
            $product_price;

        $so_id = 0;
    }


    /*==================================================
    SAVE CASHIER REPORT P3
    ==================================================*/

    if ($CHPH3_ID == '' || $CHPH3_ID == 0)
    {
        /*==================================================
        INSERT
        ==================================================*/

        $CashiersReportModel_P3 =
            new CashiersReportModel_P3();

        $CashiersReportModel_P3->user_idx =
            Session::get('loginID');

        $CashiersReportModel_P3->billing_idx =
            @$billing_id;

        $CashiersReportModel_P3->cashiers_report_id =
            $CashiersReportId;

        $CashiersReportModel_P3->miscellaneous_items_type =
            $miscellaneous_items_type;

        $CashiersReportModel_P3->so_idx =
            @$so_id;

        $CashiersReportModel_P3->reference_no =
            $reference_no;

        $CashiersReportModel_P3->order_time =
            $order_time;

        /*
         * Client is only applicable to Sales Credit.
         *
         * For Discounts / Others / Cashout,
         * keep client_idx as NULL/empty.
         */
        if ($miscellaneous_items_type == 'SALES_CREDIT')
        {
            $CashiersReportModel_P3->client_idx =
                $request->client_idx;
        }
        else
        {
            $CashiersReportModel_P3->client_idx =
                null;
        }


        /*==================================================
        PRODUCT INDEX
        ==================================================*/

        /*
         * OTHERS and CASHOUT use item_description
         * instead of product_idx.
         */

        if (
            $miscellaneous_items_type != 'OTHERS' &&
            $miscellaneous_items_type != 'CASHOUT'
        )
        {
            $CashiersReportModel_P3->product_idx =
                $product_idx;
        }
        else
        {
            $CashiersReportModel_P3->product_idx =
                null;
        }


        $CashiersReportModel_P3->item_description =
            $request->item_description;

        $CashiersReportModel_P3->order_quantity =
            $order_quantity;

        $CashiersReportModel_P3->pump_price =
            $pump_price;

        $CashiersReportModel_P3->unit_price =
            $product_manual_price;

        $CashiersReportModel_P3->discounted_price =
            $discounted_price;

        $CashiersReportModel_P3->order_total_amount =
            $peso_sales;

        $CashiersReportModel_P3->created_by_user_id =
            Session::get('loginID');


        $result =
            $CashiersReportModel_P3->save();


        if ($result)
        {
            return response()->json([
                'success' =>
                    'Product Successfully Created!'
            ]);
        }
        else
        {
            return response()->json([
                'success' =>
                    'Error on Product Information'
            ]);
        }
    }


    /*==================================================
    UPDATE
    ==================================================*/

    else
    {
        $CashiersReportModel_P3 =
            CashiersReportModel_P3::find($CHPH3_ID);


        if (!$CashiersReportModel_P3)
        {
            return response()->json([
                'message' =>
                    'Miscellaneous Sales record not found.'
            ], 404);
        }


        /*
         *==================================================
         * IMPORTANT:
         *
         * DO NOT UPDATE:
         * miscellaneous_items_type
         *
         * The existing type from the database remains.
         *==================================================
         */


        $CashiersReportModel_P3->so_idx =
            @$so_id;

        $CashiersReportModel_P3->reference_no =
            $reference_no;

        $CashiersReportModel_P3->order_time =
            $order_time;


        /*==================================================
        CLIENT
        ==================================================*/

        if ($miscellaneous_items_type == 'SALES_CREDIT')
        {
            $CashiersReportModel_P3->client_idx =
                $request->client_idx;
        }
        else
        {
            $CashiersReportModel_P3->client_idx =
                null;
        }


        /*==================================================
        PRODUCT INDEX
        ==================================================*/

        if (
            $miscellaneous_items_type != 'OTHERS' &&
            $miscellaneous_items_type != 'CASHOUT'
        )
        {
            $CashiersReportModel_P3->product_idx =
                $product_idx;
        }
        else
        {
            $CashiersReportModel_P3->product_idx =
                null;
        }


        /*==================================================
        ITEM DESCRIPTION
        ==================================================*/

        $CashiersReportModel_P3->item_description =
            $request->item_description;


        /*==================================================
        QUANTITY
        ==================================================*/

        $CashiersReportModel_P3->order_quantity =
            $order_quantity;


        /*==================================================
        PRICE INFORMATION
        ==================================================*/

        $CashiersReportModel_P3->pump_price =
            $pump_price;

        $CashiersReportModel_P3->unit_price =
            $product_manual_price;

        $CashiersReportModel_P3->discounted_price =
            $discounted_price;

        $CashiersReportModel_P3->order_total_amount =
            $peso_sales;


        /*==================================================
        UPDATED BY
        ==================================================*/

        $CashiersReportModel_P3->updated_by_user_id =
            Session::get('loginID');


        $result =
            $CashiersReportModel_P3->update();


        if ($result)
        {
            return response()->json([
                'success' =>
                    'Product Successfully Updated!'
            ]);
        }
        else
        {
            return response()->json([
                'success' =>
                    'Error on Product Information'
            ]);
        }
    }
}
	
	public function get_cashiers_report_product_p3_DISCOUNTS(Request $request){		
	
			$data =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
            ->where('miscellaneous_items_type','DISCOUNTS')
            ->whereNull('teves_cashiers_report_p3.deleted_at')
			->leftjoin('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
					]);
		
			return response()->json($data);
	}
	
    public function get_cashiers_report_product_p3_SALES_CREDIT(Request $request){		
	
			$data =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
            ->where('miscellaneous_items_type','SALES_CREDIT')
            ->whereNull('teves_cashiers_report_p3.deleted_at')
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
			->leftjoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_cashiers_report_p3.client_idx')
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_product_table.product_id as product_idx',
					'teves_product_table.product_name',
					'teves_client_table.client_name',
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
				])
				->map(function ($item) {
					$item->order_time_12 = $item->order_time 
						? Carbon::parse($item->order_time)->format('g:i A') 
						: null;
					return $item;
				});
		
			return response()->json($data);
	}

    public function get_cashiers_report_product_p3_OTHERS(Request $request){		
	
			$data =  CashiersReportModel_P3::where('cashiers_report_id', $request->CashiersReportId)
				->whereIn('miscellaneous_items_type', array('CASHOUT', 'OTHERS'))
            ->whereNull('teves_cashiers_report_p3.deleted_at')
				->orderBy('cashiers_report_p3_id', 'asc')
              	->get([
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.item_description',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.cashiers_report_id',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.order_total_amount'
					]);
		
			return response()->json($data);
	}

	public function cashiers_report_p3_info_SALES_CREDIT(Request $request){

		$CHPH3_ID = $request->CHPH3_ID;
		
		$data =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
		->where('miscellaneous_items_type','=','SALES_CREDIT')
			->join('teves_billing_so_table', 'teves_billing_so_table.so_id', '=', 'teves_cashiers_report_p3.product_idx')
			->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
			->leftjoin('teves_client_table', 'teves_client_table.client_id', '=', 'teves_cashiers_report_p3.client_idx')
				->get([
					'teves_product_table.product_name',
					'teves_client_table.client_name',
					'teves_cashiers_report_p3.client_idx',
					'teves_product_table.product_id',
					'teves_cashiers_report_p3.miscellaneous_items_type',
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.order_total_amount'
					]);			
					
		return response()->json($data);
		
	}
	
	public function cashiers_report_p3_info_DISCOUNT(Request $request){

		$CHPH3_ID = $request->CHPH3_ID;
		
		$data =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
			->leftJoin('teves_product_table', 'teves_product_table.product_id', '=', 'teves_cashiers_report_p3.product_idx')
				->get([
					'teves_product_table.product_name',
					'teves_product_table.product_id',
					'teves_cashiers_report_p3.reference_no',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.miscellaneous_items_type',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.pump_price',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.discounted_price',
					'teves_cashiers_report_p3.order_total_amount'
					]);			
					
		return response()->json($data);
		
	}
	
	public function cashiers_report_p3_info_OTHERS(Request $request){

		$CHPH3_ID = $request->CHPH3_ID;
		
		$data =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
				->get([
					'teves_cashiers_report_p3.miscellaneous_items_type',
					'teves_cashiers_report_p3.item_description',
					'teves_cashiers_report_p3.order_time',
					'teves_cashiers_report_p3.cashiers_report_p3_id',
					'teves_cashiers_report_p3.order_quantity',
					'teves_cashiers_report_p3.unit_price',
					'teves_cashiers_report_p3.reference_no'
					]);			
					
		return response()->json($data);
		
	}
	
	public function delete_cashiers_report_product_p3(Request $request){		
			
		$CHPH3_ID = $request->CHPH3_ID;

		/*Get Billing ID*/
		$billing_id =  CashiersReportModel_P3::where('cashiers_report_p3_id', $CHPH3_ID)
			->get([
					'teves_cashiers_report_p3.billing_idx',
					'teves_cashiers_report_p3.so_idx'
				]);	
		
		/*Delete from Cashiers Report*/
		CashiersReportModel_P3::find($CHPH3_ID)->delete();
		
		/*Delete from Billing*/
		BillingTransactionModel::where('billing_id', $billing_id[0]['billing_idx'])->delete();
		
		/*Re-count - if SO Number has no Product, Delete the SO*/
		$product_under_so_count =  BillingTransactionModel::where('so_idx', $billing_id[0]['so_idx'])
					->selectRaw('count(*) as product_under_so_count')
					->get();
		$product_under_so_count = $product_under_so_count[0]['product_under_so_count'];			
		
		if($product_under_so_count==0){
			
			SOBillingTransactionModel::where('so_id', $billing_id[0]['so_idx'])->delete();
				
		}
					
		return 'Deleted';
		
	}




}