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
		<!--
          <div class="col-md-3">
            <div class="card info-card sales-card">
              <div class="card-body">
                <h5 class="card-title">Total Sales</h5>
                <h6>₱{{ number_format($total_sales,2) }}</h6>
              </div>
            </div>
          </div>
		-->

			<div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>-->

                <div class="card-body">
                  <h5 class="card-title">Total Sales <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-cart"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ number_format($total_sales,2) }}</h6>
                      <!--<span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>-->

                    </div>
                  </div>
                </div>

              </div>
            </div>

			<div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>-->

                <div class="card-body">
                  <h5 class="card-title">Cash on Hand <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      ₱
                    </div>
                    <div class="ps-3">
                      <h6>{{ number_format($total_cash,2) }}</h6>
                      <!--<span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>-->

                    </div>
                  </div>
                </div>

              </div>
            </div>
		
			<div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>-->

                <div class="card-body">
                  <h5 class="card-title">Non-Cash Payment <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      <i class="bi bi-credit-card-2-back-fill"></i>
                    </div>
                    <div class="ps-3">
                      <h6>{{ number_format($total_non_cash,2) }}</h6>
                      <!--<span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>-->

                    </div>
                  </div>
                </div>

              </div>
            </div>

		
			<div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">

                <!--<div class="filter">
                  <a class="icon" href="#" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots"></i></a>
                  <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="">
                    <li class="dropdown-header text-start">
                      <h6>Filter</h6>
                    </li>

                    <li><a class="dropdown-item" href="#">Today</a></li>
                    <li><a class="dropdown-item" href="#">This Month</a></li>
                    <li><a class="dropdown-item" href="#">This Year</a></li>
                  </ul>
                </div>-->

                <div class="card-body">
                  <h5 class="card-title">Short / Over <span></span></h5>

                  <div class="d-flex align-items-center">
                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                      ₱
                    </div>
                    <div class="ps-3">
                      <h6 class="{{ $total_short_over < 0 ? 'text-danger' : 'text-success' }}">
					  {{ number_format($total_short_over,2) }}
					</h6>
                      <!--<span class="text-success small pt-1 fw-bold">12%</span> <span class="text-muted small pt-2 ps-1">increase</span>-->

                    </div>
                  </div>
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
					<th align="right">Total Sales</th>
					<th align="right">Cash on Hand</th>
					<th align="right">Non-Cash</th>
					<th align="right">Short/Over</th>
                </tr>
              </thead>
              <tbody>
                @forelse($result as $row)
                <tr>
                  <td>{{ \Carbon\Carbon::parse($row->report_date)->format('M d, Y') }}</td>
				  <td>{{ $row->branch_code }} - {{ $row->branch_name }}</td>
				  <td align="right"><strong>₱ {{ number_format($row->total_sales,2) }}</strong></td>
				  <td align="right">₱ {{ number_format($row->total_cash_sales,2) }}</td>
				  <td align="right">₱ {{ number_format($row->non_cash_payment,2) }}</td>
				  <td  align="right" class="{{ $row->short_over < 0 ? 'text-danger' : 'text-success' }}">
					₱ {{ number_format($row->short_over,2) }}
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
<!-- APEXCHART -->
<script>
document.addEventListener("DOMContentLoaded", () => {

    let rawData = @json($result);


    // get unique dates
    let dates = [...new Set(rawData.map(item => item.report_date))];

    // get unique branches
	let branches = [...new Set(rawData.map(item => item.branch_code))];
	
	// 🔥 RANDOM COLORS
    function getRandomColor() {
        return '#' + Math.floor(Math.random()*16777215).toString(16);
    }

	function getSafeColor() {
    let r = Math.floor(Math.random() * 156) + 50; // 50–205
    let g = Math.floor(Math.random() * 156) + 50;
    let b = Math.floor(Math.random() * 156) + 50;

    return `rgb(${r}, ${g}, ${b})`;
	}

    let colors = branches.map(() => getSafeColor());
	
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
		colors: colors, // 🔥 APPLY COLORS
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
<script>
$(document).ready(function() {
    $('#salesTable').DataTable({
        pageLength: 10,
        ordering: true,
        responsive: true,
        order: [[0, 'desc']], // sort by date desc

        columnDefs: [
            { targets: [2,3,4,5], className: 'text-end' } // align amounts right
        ]
    });
});
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
@endsection