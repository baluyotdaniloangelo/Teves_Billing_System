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
                  <th>Total Sales</th>
                  <th>Cash</th>
                  <th>Non-Cash</th>
                  <th>Fuel Sales</th>
                  <th>Discount</th>
                  <th>Short/Over</th>
                </tr>
              </thead>
              <tbody>
                @forelse($result as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->report_date)->format('F d, Y') }}</td>
                  <td><strong>₱{{ number_format($row->total_sales,2) }}</strong></td>
                  <td>₱{{ number_format($row->total_cash_sales,2) }}</td>
                  <td>₱{{ number_format($row->non_cash_payment,2) }}</td>
                  <td>₱{{ number_format($row->fuel_sales,2) }}</td>
                  <td>₱{{ number_format($row->discount,2) }}</td>
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

let dates = rawData.map(item => {
    let d = new Date(item.report_date);
    return d.toLocaleDateString('en-US', {
        month: 'long',
        day: '2-digit',
        year: 'numeric'
    });
});
let sales = rawData.map(item => parseFloat(item.total_sales));
//let cash = rawData.map(item => parseFloat(item.total_cash_sales));
//let non_cash = rawData.map(item => parseFloat(item.non_cash_payment));

    console.log(dates, sales); // DEBUG

    if (sales.length === 1) {
        dates.push(dates[0]);
        sales.push(sales[0]);
        cash.push(cash[0]);
        non_cash.push(non_cash[0]);
    }

    new ApexCharts(document.querySelector("#reportsChart"), {
        series: [
            { name: 'Total Sales', data: sales },
           // { name: 'Cash', data: cash },
           // { name: 'Non-Cash', data: non_cash }
        ],
        chart: {
            height: 350,
            type: 'area'
        },
        stroke: {
            curve: 'smooth'
        },
        xaxis: {
            categories: dates
        }
    }).render();

});
</script>

@endsection