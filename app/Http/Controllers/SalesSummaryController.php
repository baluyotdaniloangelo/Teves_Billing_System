<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Models\SalesSummaryChart;
use App\Charts\SalesLineChart;

use App\Models\BillingTransactionModel;
use App\Models\SalesOrderComponentModel;

use Session;
use Validator;
use DataTables;

class SalesSummaryController extends Controller
{
	
	/*Load Monthly Sale Summary Interface*/
	public function MonthlySalesSummary(){
		
		$title = 'Monthly Sales';
		$data = array();
		if(Session::has('loginID')){
			
			$data = User::where('user_id', '=', Session::get('loginID'))->first();
		}
		
		$api = url('/monthly-chart-line-ajax');
   
        $chart = new SalesLineChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])->load($api);

		return view("pages.monthly_sales_summary", compact('data','title','chart'));
		
	}   
	
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function MonthlySaleschartLineAjax(Request $request)
    {
				
        $year = $request->has('year') ? $request->year : date('Y');
		
		/**/$users = SalesSummaryChart::select(\DB::raw("sum(monthly_sales_total) as count"))
                    ->whereYear('sales_month_year', $year)
                    ->groupBy(\DB::raw("Month(sales_month_year)"))
                    ->pluck('count');
					
		/*Query on Billing (teves_billing_table)
							$data_billing = BillingTransactionModel::select(\DB::raw("sum(order_total_amount) as monthly_billing_total"))
							->whereYear('order_date', $year_month_day)
							->whereMonth('order_date', $year_month_day)
							->groupBy(\DB::raw("Month(order_datex)"))
							->groupBy(\DB::raw("Year(order_datex)"));
							var_dump($data_billing);
							
		$users = BillingTransactionModel::select(\DB::raw("sum(order_total_amount) as count"))
                    ->whereYear('order_date', $year)
					->whereMonth('order_date', 02)
                    ->groupBy(\DB::raw("Month(order_date)"))
                    ->pluck('count');*/
					
        $chart = new SalesLineChart;
  
        $chart->dataset('Monthly Sales', 'line', $users)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ]);
  
        return $chart->api();
		
    }
	
	/*Refresh or Update Data of Monthly Summary*/
	public function ReloadMonthlySales(Request $request)
    {
		
		$year = $request->year;
		$current_year = date("Y");
		//foreach(range(2021, $current_year) as $year_cols){
					
				foreach(range(1, 12) as $month){
					
						$year_month_day = date('Y-m-d', strtotime('Last day of ' . date('F', strtotime($year . '-' . $month . '-01')) . $year));
						$year_r = date('Y', strtotime('Last day of ' . date('F', strtotime($year . '-' . $month . '-01')) . $year));
						$month_r = date('m', strtotime('Last day of ' . date('F', strtotime($year . '-' . $month . '-01')) . $year));
						
						/*Check if Exist*/
						if (SalesSummaryChart::where('sales_month_year', '=', $year_month_day)->exists()) {
						   /*Do Nothing*/
						   
						   
						   $data_billing = [];
						   $data_sales_order = [];
						   
						   /*Query on Billing (teves_billing_table)*/
						   $data_billing = BillingTransactionModel::select(\DB::raw("COALESCE(sum(order_total_amount),0) as total"))
							->whereYear('order_date', $year_r)
							->whereMonth('order_date',$month_r)
							->groupBy(\DB::raw("Month(order_date)"))
							->pluck('total');
							
							/*Query on Sales Order (teves_sales_order_component_table)*/	
							$data_sales_order = SalesOrderComponentModel::select(\DB::raw("COALESCE(sum(order_total_amount),0) as total"))
							->whereYear('sales_order_date', $year_r)
							->whereMonth('sales_order_date',$month_r)
							->groupBy(\DB::raw("Month(sales_order_date)"))
							->pluck('total');
							
							//var_dump($data_sales_order);
							
							/*Update teves_sales_summary*/
							$update_monthy_sales = new SalesSummaryChart();
							$update_monthy_sales = SalesSummaryChart::where('sales_month_year','=', $year_month_day)
							->update(array('billing_total_sales' => $data_billing[0],
							'sales_order_total_sales' => @$data_sales_order[0]));
							
						}else{
						
							/*Insert To teves_sales_summary*/
							$save_month = new SalesSummaryChart();
							$save_month->sales_month_year 			= $year_month_day;
							@$result_month = $save_month->save();
						
							/*Query on Billing (teves_billing_table)


							
							/*Query on Sales Order (teves_sales_order_component_table)*/	
							
							/*Update teves_sales_summary*/
						
						}

				}
					
				/*Query on Billing (teves_billing_table)*/
				/*
				
				SELECT
				DATE_FORMAT(order_date, '%Y-%m-01') AS production_month,
				IFNULL(SUM(order_total_amount),0) AS count
				FROM teves_billing_table
				where year(`order_date`) = 2023teves_sales_summary
				GROUP BY
				MONTH(order_date),
				YEAR(order_date)
				
				where('client_idx', $client_idx)
					->where('teves_billing_table.order_date', '>=', $start_date)
                    ->where('teves_billing_table.order_date', '<=', $end_date)
					->join('teves_product_table', 'teves_product_table.product_id', '=', 'teves_billing_table.product_idx')
					->orderBy('teves_billing_table.order_date', 'asc')
              		->get([
					'teves_billing_table.billing_id',
					'teves_billing_table.drivers_name',
					'teves_billing_table.plate_no',
					'teves_product_table.product_name',
					'teves_product_table.product_unit_measurement',
					'teves_billing_table.product_price',
					'teves_billing_table.order_quantity',					
					'teves_billing_table.order_total_amount',
					'teves_billing_table.order_po_number',
					'teves_billing_table.order_date',
					'teves_billing_table.order_date',
					'teves_billing_table.order_time']);
				$data = BillingTransactionModel::select(\DB::raw("sum(monthly_sales_total) as count"))
                    ->whereYear('sales_month_year', $year)
					->whereMonth('sales_month_year', $year)
                    ->groupBy(\DB::raw("Month(sales_month_year)"))
                    ->pluck('count');*/
				
				/*Query on Sales Order (teves_sales_order_component_table)*/				
							
		//}
		
		
	}
}
