@extends('layouts.layout')  
@section('content')  

<main id="main" class="main">	

		
<section class="section">
      <div class="row">

        <div class="col-lg-12">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Monthly</h5>

              <!-- Line Chart -->
              <canvas id="MonthlyChart" style="max-height: 520px;"></canvas>
              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new Chart(document.querySelector('#MonthlyChart'), {
                    type: 'line',
                    data: {
                      labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
                      datasets: [{
                        label: 'Monthly Sales',
                        data: [65, 59, 80, 81, 56, 55, 40],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                      }]
                    },
                    options: {
                      scales: {
                        y: {
                          beginAtZero: true
                        }
                      }
                    }
                  });
                });
              </script>
              <!-- End Line CHart -->
				SELECT
				  DATE_FORMAT(created_at, '%m-%Y') AS production_month,
				  IFNULL(SUM(order_total_amount),0) AS count
				FROM teves_sales_order_component_table
				WHERE created_at<='2023-04-30 08:54:55'
				GROUP BY
				  MONTH(created_at),
				  YEAR(created_at);
				  
				  Query must be starting from the 1st day of the year to last
            </div>
          </div>
        </div>
                   
            </div>
          </div>
