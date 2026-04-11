@extends('layouts.layout')  
@section('content')  

@php
$total_sales = $result->sum('total_sales');
$total_cash = $result->sum('total_cash_sales');
$total_non_cash = $result->sum('non_cash_payment');
$total_short_over = $result->sum('short_over');
@endphp

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div>

  <section class="section dashboard">
    <div class="row">

      <!-- SUMMARY CARDS -->
      <div class="col-12">
        <div class="row">

          <div class="col-md-3">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <h6>₱{{ number_format($total_sales,2) }}</h6>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card info-card revenue-card">
              <div class="card-body">
                <h5 class="card-title">Cash on Hand</h5>
                <h6>₱{{ number_format($total_cash,2) }}</h6>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card info-card customers-card">
              <div class="card-body">
                <h5 class="card-title">Non-Cash Payment</h5>
                <h6>₱{{ number_format($total_non_cash,2) }}</h6>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card info-card">
              <div class="card-body">
                <h5 class="card-title">Short / Over</h5>
                <h6 class="{{ $total_short_over < 0 ? 'text-danger' : 'text-success' }}">
                  ₱{{ number_format($total_short_over,2) }}
                </h6>
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- SALES TREND CHART -->
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">
              Sales Trend 
              <span>| {{ $start_date == $end_date ? 'Today' : $start_date . ' - ' . $end_date }}</span>
            </h5>

            <div id="reportsChart"></div>
          </div>
        </div>
      </div>

      <!-- DAILY TABLE -->
      <div class="col-12">
        <div class="card recent-sales overflow-auto">
          <div class="card-body">
            <h5 class="card-title">Daily Summary</h5>

            <table class="table table-bordered table-striped">
              <thead class="table-dark">
                <tr>
					<th>Date</th>
					<th>Branch</th>
					<th>Total Sales</th>
					<th>Cash on Hand</th>
					<th>Non-Cash</th>
					<th>Short/Over</th>
                </tr>
              </thead>
              <tbody>
                @forelse($result as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
				  <td>{{ $row->branch_code }} - {{ $row->branch_name }}</td>
				  <td><strong>₱{{ number_format($row->total_sales,2) }}</strong></td>
				  <td>₱{{ number_format($row->total_cash_sales,2) }}</td>
				  <td>₱{{ number_format($row->non_cash_payment,2) }}</td>
				  <td class="{{ $row->short_over < 0 ? 'text-danger' : 'text-success' }}">
					₱{{ number_format($row->short_over,2) }}
				  </td>
                </tr>
                @empty
                <tr>
                  <td colspan="7" class="text-center">No result available</td>
                </tr>
                @endforelse
              </tbody>
            </table>

          </div>
        </div>
      </div>

    </div>
  </section>

</main>


<!-- APEXCHART 
<script>
console.log(@json($result));
</script>-->
<script>
document.addEventListener("DOMContentLoaded", () => {

    let rawData = @json($result);

    // get unique dates
    let dates = [...new Set(rawData.map(item => item.report_date))];

    // get unique branches
let branches = [...new Set(rawData.map(item => item.branch_code))];

let series = branches.map(branch => {
    let branchData = dates.map(date => {
        let found = rawData.find(d => 
            d.report_date === date &&
            (d.branch_code) === branch
        );
        return found ? parseFloat(found.total_sales) : 0;
    });

    return {
        name: branch,
        data: branchData
    };
});

    // format dates
    let formattedDates = dates.map(d => {
        let dt = new Date(d);
        return dt.toLocaleDateString('en-US', {
            month: 'short',
            day: '2-digit'
        });
    });

    new ApexCharts(document.querySelector("#reportsChart"), {
        series: series,
        chart: {
            height: 350,
            type: 'area'
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: formattedDates
        },
        yaxis: {
            labels: {
                formatter: val => "₱" + val.toLocaleString()
            }
        },
        tooltip: {
            y: {
                formatter: val => "₱" + val.toLocaleString(undefined, {
                    minimumFractionDigits: 2
                })
            }
        }
    }).render();

});
</script>

@endsection