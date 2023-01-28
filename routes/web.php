<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuthController;
use App\Http\Controllers\BillingTransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReceivablesController;
use App\Http\Controllers\SalesOrderController;

use App\Http\Controllers\PurchaseOrderController;
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

/*Load Report Interface*/
Route::get('/report', [ReportController::class,'report'])->name('report')->middleware('isLoggedIn');
/*Generate via Web Page View*/
Route::post('/generate_report', [ReportController::class,'generate_report'])->name('generate_report')->middleware('isLoggedIn');
/*Download Directly via Excel*/
Route::get('/generate_report_excel', [ReportController::class,'generate_report_excel'])->name('generate_report_excel')->middleware('isLoggedIn');
/*Download via PDF*/
Route::get('/generate_report_pdf', [ReportController::class,'generate_report_pdf'])->name('generate_report_pdf')->middleware('isLoggedIn');

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
/*Download Sales Order via PDF*/
Route::get('/generate_sales_order_pdf', [ReportController::class,'generate_sales_order_pdf'])->name('generate_sales_order_pdf')->middleware('isLoggedIn');

/*Sales Order*/
/*January 24, 2023*/
Route::get('/purchaseorder', [PurchaseOrderController::class,'purchaseorder'])->name('purchaseorder')->middleware('isLoggedIn');
Route::get('purchaseorder/list', [PurchaseOrderController::class, 'getPurchaseOrderList'])->name('getPurchaseOrderList')->middleware('isLoggedIn');
/*GET Sales Order Info*/
Route::post('/purchase_order_info', [PurchaseOrderController::class, 'purchase_order_info'])->name('purchase_order_info')->middleware('isLoggedIn');
/*Confirm Delete Sales Order*/
Route::post('/delete_purchase_order_confirmed', [PurchaseOrderController::class, 'delete_purchase_order_confirmed'])->name('delete_purchase_order_confirmed')->middleware('isLoggedIn');
/*Create Sales Order*/
Route::post('/create_purchase_order_post', [PurchaseOrderController::class,'create_purchase_order_post'])->name('create_purchase_order_post')->middleware('isLoggedIn');
/*Update Sales Order*/
Route::post('/update_purchase_order_post', [PurchaseOrderController::class,'update_purchase_order_post'])->name('update_purchase_order_post')->middleware('isLoggedIn');
/*Get Sales Order Product Item*/
Route::post('/get_purchase_order_product_list', [PurchaseOrderController::class,'get_purchase_order_product_list'])->name('get_purchase_order_product_list')->middleware('isLoggedIn');
/*Delete Sales Order Product Item*/
Route::post('/delete_purchase_order_item', [PurchaseOrderController::class,'delete_purchase_order_item'])->name('delete_purchase_order_item')->middleware('isLoggedIn');


/*Download Sales Order via PDF*/
Route::get('/generate_sales_order_pdf', [ReportController::class,'generate_sales_order_pdf'])->name('generate_sales_order_pdf')->middleware('isLoggedIn');
/*Download via PDF*/
Route::get('/generate_receivable_pdf', [ReportController::class,'generate_receivable_pdf'])->name('generate_receivable_pdf')->middleware('isLoggedIn');

