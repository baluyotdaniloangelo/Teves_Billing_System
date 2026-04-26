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


			<div class="col-xxl-3 col-md-3">
              <div class="card info-card sales-card">

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

            <table class="table table-bordered table-striped" id="salesTable">
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

    // ✅ UNIQUE DATES
    let dates = [...new Set(rawData.map(item => item.report_date))];

    // ✅ UNIQUE BRANCH LABEL
    let branches = [...new Set(
        rawData.map(item => item.branch_code)
    )];

    // ✅ SAFE COLOR GENERATOR (NO DARK COLORS)
    function stringToColor(str) {
        let hash = 0;
        for (let i = 0; i < str.length; i++) {
            hash = str.charCodeAt(i) + ((hash << 5) - hash);
        }

        let r = (hash >> 0) & 0xFF;
        let g = (hash >> 8) & 0xFF;
        let b = (hash >> 16) & 0xFF;

        // normalize brightness
        r = (r % 156) + 50;
        g = (g % 156) + 50;
        b = (b % 156) + 50;

        return `rgb(${r}, ${g}, ${b})`;
    }

    let colors = branches.map(branch => stringToColor(branch));

    // ✅ BUILD SERIES
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

    // ✅ FORMAT DATES
    let formattedDates = dates.map(d => {
        let dt = new Date(d);
        return dt.toLocaleDateString('en-US', {
            month: 'short',
            day: '2-digit'
        });
    });

    // ✅ CHART
    new ApexCharts(document.querySelector("#reportsChart"), {
        series: series,
        chart: {
            height: 350,
            type: 'area'
        },
        colors: colors,
        stroke: {
            curve: 'smooth',
            width: 2
        },
        fill: {
            type: 'gradient',
            gradient: {
                opacityFrom: 0.4,
                opacityTo: 0.1
            }
        },
        xaxis: {
            categories: formattedDates
        },
        yaxis: {
            labels: {
                formatter: val => "₱" + val.toLocaleString()
            }
        },

        // 🔥 SORTED TOOLTIP (PER HOVER)
        tooltip: {
            shared: true,
            intersect: false,
            custom: function({ series, dataPointIndex, w }) {

                let data = w.config.series.map((s, i) => {
                    return {
                        name: s.name,
                        value: s.data[dataPointIndex],
                        color: w.globals.colors[i]
                    };
                });

                // 🔥 SORT HIGHEST → LOWEST
                data.sort((a, b) => b.value - a.value);

                let dateLabel = w.globals.categoryLabels[dataPointIndex];

                let html = `
                    <div style="padding:10px; min-width:200px;">
                        <strong>${dateLabel}</strong>
                `;

                data.forEach(d => {
                    if (d.value > 0) {
                        html += `
                            <div style="margin:4px 0;">
                                <span style="color:${d.color}; font-size:14px;">●</span>
                                ${d.name}: 
                                <b>₱${d.value.toLocaleString(undefined, {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                })}</b>
                            </div>
                        `;
                    }
                });

                html += `</div>`;
                return html;
            }
        }

    }).render();

});
</script>


@endsection