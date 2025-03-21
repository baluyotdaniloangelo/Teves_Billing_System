<?php
  
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Models\UserTest;
use App\Models\SalesSummaryChart;
use App\Charts\UserLineChart;
   
class ChartController extends Controller
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function chartLine()
    {
        $api = url('/chart-line-ajax');
   
        $chart = new UserLineChart;
        $chart->labels(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'])->load($api);
          
        return view('chartLine', compact('chart','api'));
    }
   
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function chartLineAjax(Request $request)
    {
        $year = $request->has('year') ? $request->year : date('Y');
      $users = SalesSummaryChart::select(\DB::raw("sum(monthly_sales_total) as count"))
                    ->whereYear('sales_month_year', $year)
                    ->groupBy(\DB::raw("Month(sales_month_year)"))
                    ->pluck('count');
        $chart = new UserLineChart;
  
        $chart->dataset('New User Register Chart', 'line', $users)->options([
                    'fill' => 'true',
                    'borderColor' => '#51C1C0'
                ]);
  
        return $chart->api();
    }
}