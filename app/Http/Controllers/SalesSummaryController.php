<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Models\SalesSummaryChart;
use App\Charts\SalesLineChart;
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
		$users = SalesSummaryChart::select(\DB::raw("sum(monthly_sales_total) as count"))
                    ->whereYear('sales_month_year', $year)
                    ->groupBy(\DB::raw("Month(sales_month_year)"))
                    ->pluck('count');
  
        $chart = new SalesLineChart;
  
        $chart->dataset('Monthly Sales', 'line', $users)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ]);
  
        return $chart->api();
		
    }
}
