<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BillingTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReceivablesController;
use App\Http\Controllers\SalesOrderController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\CashiersReportController;

use App\Http\Controllers\SalesSummaryController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*SAMPLE EXCEL
Route::get('/sample1', [CAMRSampleExcel::class,'sample1'])->name('site')->middleware('isLoggedIn');
*/

/*Login Page*/
Route::get('/',[UserAuthController::class,'login'])->middleware('alreadyLoggedIn');
Route::post('login-user', [UserAuthController::class,'loginUser'])->name('login-user');
/*Logout*/
Route::get('logout', [UserAuthController::class,'logout']);

/*Load Billing Transaction*/
Route::get('/billing', [BillingTransactionController::class,'billing'])->name('billing')->middleware('isLoggedIn');
Route::get('billing/list', [BillingTransactionController::class, 'getBillingTransactionList'])->name('getBillingTransactionList')->middleware('isLoggedIn');
/*Create Bill*/
Route::post('/create_bill_post', [BillingTransactionController::class,'create_bill_post'])->name('create_bill_post')->middleware('isLoggedIn');
/*Update Bill*/
Route::post('/update_bill_post', [BillingTransactionController::class,'update_bill_post'])->name('update_bill_post')->middleware('isLoggedIn');
/*GET Bill Info*/
Route::post('/bill_info', [BillingTransactionController::class, 'bill_info'])->name('bill_info')->middleware('isLoggedIn');
/*Confirm Delete Bill*/
Route::post('/delete_bill_confirmed', [BillingTransactionController::class, 'delete_bill_confirmed'])->name('delete_bill_confirmed')->middleware('isLoggedIn');

/*Dev Date Nov 30 2022*/
/*Load Product List*/
Route::get('/product', [ProductController::class,'product'])->name('product')->middleware('isLoggedIn');
Route::get('product/list', [ProductController::class, 'getProductList'])->name('getProductList')->middleware('isLoggedIn');
/*Create Product*/
Route::post('/create_product_post', [ProductController::class,'create_product_post'])->name('create_product_post')->middleware('isLoggedIn');
/*GET Product Info*/
Route::post('/product_info', [ProductController::class, 'product_info'])->name('product_info')->middleware('isLoggedIn');
/*Update Product*/
Route::post('/update_product_post', [ProductController::class,'update_product_post'])->name('update_product_post')->middleware('isLoggedIn');
/*Confirm Delete Product*/
Route::post('/delete_product_confirmed', [ProductController::class, 'delete_product_confirmed'])->name('delete_product_confirmed')->middleware('isLoggedIn');

/*Dev Date Nov 30 2022*/
/*Load Client List*/
Route::get('/client', [ClientController::class,'client'])->name('client')->middleware('isLoggedIn');
Route::get('client/list', [ClientController::class, 'getClientList'])->name('getClientList')->middleware('isLoggedIn');
/*Create Product*/
Route::post('/create_client_post', [ClientController::class,'create_client_post'])->name('create_client_post')->middleware('isLoggedIn');
/*GET Product Info*/
Route::post('/client_info', [ClientController::class, 'client_info'])->name('client_info')->middleware('isLoggedIn');
/*Update Product*/
Route::post('/update_client_post', [ClientController::class,'update_client_post'])->name('update_client_post')->middleware('isLoggedIn');
/*Confirm Delete Product*/
Route::post('/delete_client_confirmed', [ClientController::class, 'delete_client_confirmed'])->name('delete_client_confirmed')->middleware('isLoggedIn');


/*Load Billing Report History Interface*/
Route::get('/billing_history', [ReportController::class,'billing_history'])->name('report')->middleware('isLoggedIn');
/*Generate via Web Page View - For receivable*/
Route::post('/generate_report_recievable', [ReportController::class,'generate_report_recievable'])->name('generate_report_recievable')->middleware('isLoggedIn');
/*Generate via Web Page View - For receivable*/
Route::post('/generate_report_recievable_after_saved', [ReportController::class,'generate_report_recievable_after_saved'])->name('generate_report_recievable_after_saved')->middleware('isLoggedIn');

/*Generate via Web Page View*/
Route::post('/generate_report', [ReportController::class,'generate_report'])->name('generate_report')->middleware('isLoggedIn');
/*Download Directly via Excel*/
Route::get('/generate_report_excel', [ReportController::class,'generate_report_excel'])->name('generate_report_excel')->middleware('isLoggedIn');
/*Download via PDF*/
Route::get('/generate_report_pdf', [ReportController::class,'generate_report_pdf'])->name('generate_report_pdf')->middleware('isLoggedIn');

/*Download via PDF*/
Route::get('/generate_receivable_covered_bill_pdf', [ReportController::class,'generate_receivable_covered_bill_pdf'])->name('generate_receivable_covered_bill_pdf')->middleware('isLoggedIn');

/*Load User Account List for Admin Only*/
Route::get('/user', [UserController::class,'user'])->name('user')->middleware('isLoggedIn');
/*Get List of User*/
Route::post('user_list', [UserController::class, 'getUserList'])->name('UserList')->middleware('isLoggedIn');
/*Create User*/
Route::post('/create_user_post', [UserController::class,'create_user_post'])->name('create_user_post')->middleware('isLoggedIn');

/*GET User Info*/
Route::post('/user_info', [UserController::class, 'user_info'])->name('user_info')->middleware('isLoggedIn');
/*Update User*/
Route::post('/update_user_post', [UserController::class,'update_user_post'])->name('update_user_post')->middleware('isLoggedIn');
/*Confirm Delete Switch*/
Route::post('/delete_user_confirmed', [UserController::class, 'delete_user_confirmed'])->name('delete_user_confirmed')->middleware('isLoggedIn');
/*Update User Account*/
Route::post('/user_account_post', [UserController::class,'user_account_post'])->name('user_account_post')->middleware('isLoggedIn');


/*Receivables*/
/*December 17, 2022*/
/*Load Create Receivable Interface June 18, 2023*/
Route::get('/create_recievable', [ReceivablesController::class,'create_recievable'])->name('create_recievable')->middleware('isLoggedIn');
/*Receivables List*/
Route::get('/receivables', [ReceivablesController::class,'receivables'])->name('receivables')->middleware('isLoggedIn');
Route::get('receivables/list', [ReceivablesController::class, 'getReceivablesList'])->name('getReceivablesList')->middleware('isLoggedIn');
/*GET receivables Info*/
Route::post('/receivable_info', [ReceivablesController::class, 'receivable_info'])->name('receivable_info')->middleware('isLoggedIn');
/*Confirm Delete receivables*/
Route::post('/delete_receivable_confirmed', [ReceivablesController::class, 'delete_receivable_confirmed'])->name('delete_receivable_confirmed')->middleware('isLoggedIn');
/*Create receivables*/
Route::post('/create_receivables_post', [ReceivablesController::class,'create_receivables_post'])->name('create_receivables_post')->middleware('isLoggedIn');
/*Create receivables*/
Route::post('/update_receivables_post', [ReceivablesController::class,'update_receivables_post'])->name('update_receivables_post')->middleware('isLoggedIn');
/*Get Receivables Payment*/
Route::post('/get_receivable_payment_list', [ReceivablesController::class,'get_receivable_payment_list'])->name('get_receivable_payment_list')->middleware('isLoggedIn');
/*Delete Receivables Payment*/
Route::post('/delete_receivable_payment_item', [ReceivablesController::class,'delete_receivable_payment_item'])->name('delete_receivable_payment_item')->middleware('isLoggedIn');
/*Save Receivables Payment*/
Route::post('/save_receivable_payment_post', [ReceivablesController::class,'save_receivable_payment_post'])->name('save_receivable_payment_post')->middleware('isLoggedIn');

/*Sales Order*/
/*January 04, 2023*/
Route::get('/salesorder', [SalesOrderController::class,'salesorder'])->name('salesorder')->middleware('isLoggedIn');
Route::get('salesorder/list', [SalesOrderController::class, 'getSalesOrderList'])->name('getSalesOrderList')->middleware('isLoggedIn');
/*GET Sales Order Info*/
Route::post('/sales_order_info', [SalesOrderController::class, 'sales_order_info'])->name('sales_order_info')->middleware('isLoggedIn');
/*Confirm Delete Sales Order*/
Route::post('/delete_sales_order_confirmed', [SalesOrderController::class, 'delete_sales_order_confirmed'])->name('delete_sales_order_confirmed')->middleware('isLoggedIn');
/*Create Sales Order*/
Route::post('/create_sales_order_post', [SalesOrderController::class,'create_sales_order_post'])->name('create_sales_order_post')->middleware('isLoggedIn');
/*Update Sales Order*/
Route::post('/update_sales_order_post', [SalesOrderController::class,'update_sales_order_post'])->name('update_sales_order_post')->middleware('isLoggedIn');
/*Get Sales Order Product Item*/
Route::post('/get_sales_order_product_list', [SalesOrderController::class,'get_sales_order_product_list'])->name('get_sales_order_product_list')->middleware('isLoggedIn');
/*Delete Sales Order Product Item*/
Route::post('/delete_sales_order_item', [SalesOrderController::class,'delete_sales_order_item'])->name('delete_sales_order_item')->middleware('isLoggedIn');
/*Update Sales Order Status*/
Route::post('/update_sales_status', [SalesOrderController::class,'update_sales_status'])->name('update_sales_status')->middleware('isLoggedIn');
/*Update Sales Order Delivery Status*/
Route::post('/update_sales_order_delivery_status', [SalesOrderController::class,'update_sales_order_delivery_status'])->name('update_sales_order_delivery_status')->middleware('isLoggedIn');
/*Download Sales Order via PDF*/
Route::get('/generate_sales_order_pdf', [ReportController::class,'generate_sales_order_pdf'])->name('generate_sales_order_pdf')->middleware('isLoggedIn');

/*Get Sales Order Payment Item*/
Route::post('/get_sales_order_payment_list', [SalesOrderController::class,'get_sales_order_payment_list'])->name('get_sales_order_payment_list')->middleware('isLoggedIn');

/*Delete Purchase Order Payment Item*/
Route::post('/delete_sales_order_payment_item', [SalesOrderController::class,'delete_sales_order_payment_item'])->name('delete_sales_order_payment_item')->middleware('isLoggedIn');

/*Purchase Order*/
/*January 24, 2023*/
Route::get('/purchaseorder', [PurchaseOrderController::class,'purchaseorder'])->name('purchaseorder')->middleware('isLoggedIn');
Route::get('purchaseorder/list', [PurchaseOrderController::class, 'getPurchaseOrderList'])->name('getPurchaseOrderList')->middleware('isLoggedIn');
/*GET Purchase Order Info*/
Route::post('/purchase_order_info', [PurchaseOrderController::class, 'purchase_order_info'])->name('purchase_order_info')->middleware('isLoggedIn');
/*Confirm Delete Purchase Order*/
Route::post('/delete_purchase_order_confirmed', [PurchaseOrderController::class, 'delete_purchase_order_confirmed'])->name('delete_purchase_order_confirmed')->middleware('isLoggedIn');
/*Create Purchase Order*/
Route::post('/create_purchase_order_post', [PurchaseOrderController::class,'create_purchase_order_post'])->name('create_purchase_order_post')->middleware('isLoggedIn');
/*Update Purchase Order*/
Route::post('/update_purchase_order_post', [PurchaseOrderController::class,'update_purchase_order_post'])->name('update_purchase_order_post')->middleware('isLoggedIn');
/*Get Purchase Order Product Item*/
Route::post('/get_purchase_order_product_list', [PurchaseOrderController::class,'get_purchase_order_product_list'])->name('get_purchase_order_product_list')->middleware('isLoggedIn');
/*Get Purchase Order Product Item*/
Route::post('/get_purchase_order_product_list', [PurchaseOrderController::class,'get_purchase_order_product_list'])->name('get_purchase_order_product_list')->middleware('isLoggedIn');
/*Delete Purchase Order Product Item*/
Route::post('/delete_purchase_order_item', [PurchaseOrderController::class,'delete_purchase_order_item'])->name('delete_purchase_order_item')->middleware('isLoggedIn');
/*Get Purchase Order Payment Item*/
Route::post('/get_purchase_order_payment_list', [PurchaseOrderController::class,'get_purchase_order_payment_list'])->name('get_purchase_order_payment_list')->middleware('isLoggedIn');
/*Delete Purchase Order Payment Item*/
Route::post('/delete_purchase_order_payment_item', [PurchaseOrderController::class,'delete_purchase_order_payment_item'])->name('delete_purchase_order_payment_item')->middleware('isLoggedIn');
/*Update Purchase Status*/
Route::post('/update_purchase_status', [PurchaseOrderController::class,'update_purchase_status'])->name('update_purchase_status')->middleware('isLoggedIn');

/*Download Sales Order via PDF*/
Route::get('/generate_sales_order_pdf', [ReportController::class,'generate_sales_order_pdf'])->name('generate_sales_order_pdf')->middleware('isLoggedIn');
/*Download Purchase Order via PDF*/
Route::get('/generate_purchase_order_pdf', [ReportController::class,'generate_purchase_order_pdf'])->name('generate_purchase_order_pdf')->middleware('isLoggedIn');
/*Download via PDF*/
Route::get('/generate_receivable_pdf', [ReportController::class,'generate_receivable_pdf'])->name('generate_receivable_pdf')->middleware('isLoggedIn');
Route::get('/generate_receivable_soa_pdf', [ReportController::class,'generate_receivable_soa_pdf'])->name('generate_receivable_soa_pdf')->middleware('isLoggedIn');

/*Dev Date Mar 27 2023*/
/*Load Supplier List*/
Route::get('/supplier', [SupplierController::class,'supplier'])->name('supplier')->middleware('isLoggedIn');
Route::get('supplier/list', [SupplierController::class, 'getSupplierList'])->name('getSupplierList')->middleware('isLoggedIn');
/*Create Product*/
Route::post('/create_supplier_post', [SupplierController::class,'create_supplier_post'])->name('create_supplier_post')->middleware('isLoggedIn');
/*GET Product Info*/
Route::post('/supplier_info', [SupplierController::class, 'supplier_info'])->name('supplier_info')->middleware('isLoggedIn');
/*Update Product*/
Route::post('/update_supplier_post', [SupplierController::class,'update_supplier_post'])->name('update_supplier_post')->middleware('isLoggedIn');
/*Confirm Delete Product*/
Route::post('/delete_supplier_confirmed', [SupplierController::class, 'delete_supplier_confirmed'])->name('delete_supplier_confirmed')->middleware('isLoggedIn');

/*Load Cashier's Report List*/
/*Dev Date May 10, 2023*/
Route::get('/cashier_report', [CashiersReportController::class,'cashierReport'])->name('cashierReport')->middleware('isLoggedIn');
Route::get('cashier_report/list', [CashiersReportController::class, 'getCashierReport'])->name('getCashierReport')->middleware('isLoggedIn');
/*Create Cashier's Report Primary Information*/
Route::post('/create_cashier_report_post', [CashiersReportController::class,'create_cashier_report_post'])->name('create_cashier_report_post')->middleware('isLoggedIn');
/*Update Cashier's Report Primary Information*/
Route::post('/update_cashier_report_post', [CashiersReportController::class,'update_cashier_report_post'])->name('update_cashier_report_post')->middleware('isLoggedIn');
/*GET Cashier's Report Primary Information*/
Route::post('/cashiers_report_info', [CashiersReportController::class, 'cashiers_report_info'])->name('cashiers_report_info')->middleware('isLoggedIn');
/**/
Route::post('/delete_cashiers_report_info', [CashiersReportController::class, 'delete_cashiers_report_info'])->name('delete_cashiers_report_info')->middleware('isLoggedIn');


/*Cashiers Report Part 1*/
Route::get('/cashiers_report_form/{id}', [CashiersReportController::class, 'cashiers_report_form'])->name('cashiers_report_form')->middleware('isLoggedIn');
/*Save Cashier's Report Product*/
Route::post('/save_product_cashiers_report_p1', [CashiersReportController::class,'save_product_cashiers_report_p1'])->name('SAVE_CHR_PH1')->middleware('isLoggedIn');
/* Load Product P1 */
Route::post('/get_cashiers_report_product_p1', [CashiersReportController::class,'get_cashiers_report_product_p1'])->name('GetCashiersProductP1')->middleware('isLoggedIn');
/* Delete Product P1 */
Route::post('/delete_cashiers_report_product_p1', [CashiersReportController::class,'delete_cashiers_report_product_p1'])->name('DeleteCashiersProductP1')->middleware('isLoggedIn');
/*GET Cashiers report product P1*/
Route::post('/cashiers_report_p1_info', [CashiersReportController::class, 'cashiers_report_p1_info'])->name('CRP1_info')->middleware('isLoggedIn');


/*Cashiers Report Part 2*/
Route::post('/save_product_cashiers_report_PH2', [CashiersReportController::class,'save_product_cashiers_report_PH2'])->name('SAVE_CHR_PH2')->middleware('isLoggedIn');
/* Load Product P2 */
Route::post('/get_cashiers_report_product_p2', [CashiersReportController::class,'get_cashiers_report_product_p2'])->name('GetCashiersProductP2')->middleware('isLoggedIn');
/* Delete Product P2 */
Route::post('/delete_cashiers_report_product_p2', [CashiersReportController::class,'delete_cashiers_report_product_p2'])->name('DeleteCashiersProductP2')->middleware('isLoggedIn');
/*GET Cashiers report product P2*/
Route::post('/cashiers_report_p2_info', [CashiersReportController::class, 'cashiers_report_p2_info'])->name('CRP2_info')->middleware('isLoggedIn');

/*Cashiers Report Part 3*/
Route::post('/save_product_cashiers_report_PH3', [CashiersReportController::class,'save_product_cashiers_report_PH3'])->name('SAVE_CHR_PH3')->middleware('isLoggedIn');
/* Load Product P3 */
Route::post('/get_cashiers_report_product_p3', [CashiersReportController::class,'get_cashiers_report_product_p3'])->name('GetCashiersProductP3')->middleware('isLoggedIn');
/* Delete Product P3 */
Route::post('/delete_cashiers_report_product_p3', [CashiersReportController::class,'delete_cashiers_report_product_p3'])->name('DeleteCashiersProductP3')->middleware('isLoggedIn');
/*GET Cashiers report product P3*/
Route::post('/cashiers_report_p3_info', [CashiersReportController::class, 'cashiers_report_p3_info'])->name('CRP3_info')->middleware('isLoggedIn');

/*Cashiers Report Part 4*/
/*Save or Update*/
Route::post('/save_cashiers_report_PH4', [CashiersReportController::class,'save_cashiers_report_PH4'])->name('SAVE_CHR_PH4')->middleware('isLoggedIn');
/* Load P4 */
Route::post('/get_cashiers_report_p4', [CashiersReportController::class,'get_cashiers_report_p4'])->name('GetCashiersP4')->middleware('isLoggedIn');
/* Delete P4 */
Route::post('/delete_cashiers_report_p4', [CashiersReportController::class,'delete_cashiers_report_p4'])->name('DeleteCashiersP4')->middleware('isLoggedIn');
/*GET Cashiers report P4*/
Route::post('/cashiers_report_p4_info', [CashiersReportController::class, 'cashiers_report_p4_info'])->name('CRP4_info')->middleware('isLoggedIn');

/*Cashiers Report Part 5*/
/*Save or Update*/
Route::post('/save_cashiers_report_PH5', [CashiersReportController::class,'save_cashiers_report_PH5'])->name('SAVE_CHR_PH5')->middleware('isLoggedIn');
/*GET Cashiers report P5*/
Route::post('/cashiers_report_p5_info', [CashiersReportController::class, 'cashiers_report_p5_info'])->name('CRP5_info')->middleware('isLoggedIn');



/* Sales Summary */
Route::get('/monthly_sales', [SalesSummaryController::class,'MonthlySalesSummary'])->name('MonthlySalesSummary')->middleware('isLoggedIn');
Route::get('/monthly-chart-line-ajax', [SalesSummaryController::class,'MonthlySaleschartLineAjax'])->name('MonthlySaleschartLineAjax')->middleware('isLoggedIn');
Route::post('/reload_monthly_sales_per_year', [SalesSummaryController::class,'ReloadMonthlySales'])->name('ReloadMonthlySales')->middleware('isLoggedIn');

Route::get('monthly-chart-line-ajax', 'SalesSummaryController@MonthlySaleschartLineAjax');

/*CHARTS TEST

Route::get('chart-line', 'ChartController@chartLine');
Route::get('chart-line-ajax', 'ChartController@chartLineAjax');

*/