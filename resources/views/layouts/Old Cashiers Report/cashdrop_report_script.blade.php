<script type="text/javascript">

	setMaxonEndDate_cash_drop();
	
	function setMaxonEndDate_cash_drop(){
	
		let start_date_cash_drop 			= $("input[name=start_date_cash_drop]").val();
		
		var myDate = new Date(start_date_cash_drop);
		var result1 = myDate.setMonth(myDate.getMonth()+12);
		
		const date_new = new Date(result1);
		
		const max_date = document.getElementById('end_date_cash_drop');
		
		document.getElementById("end_date_cash_drop").min = start_date_cash_drop;
		document.getElementById("end_date_cash_drop").max = date_new.toISOString("en-US").substring(0, 10);
		
		document.getElementById("end_date_cash_drop").value = start_date_cash_drop;
		
	}
	
	function CheckEndDateValidity_cash_drop(){
		
		let start_date_cash_drop 			= $("input[name=start_date_cash_drop]").val();
		let end_date_cash_drop 			= $("input[name=end_date_cash_drop]").val();
		
		let end_date_cash_drop_max 		= document.getElementById("end_date_cash_drop").max;
		
		const x = new Date(start_date_cash_drop);
		const y = new Date(end_date_cash_drop);
		
		const edt = new Date(end_date_cash_drop_max);
		
			if(x > y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_cash_drop").value = start_date_cash_drop;
				
			}
			else if(edt < y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_cash_drop").value = start_date_cash_drop;
					
			}else{
					$('#end_date_cash_dropError').html('');
					document.getElementById('end_date_cash_dropError').className = "valid-feedback";
			}
	
	}

	<!--Load Table-->
	var branch_description_chart = [];
	$("#generate_report_cash_drop").click(function(event){
		
			event.preventDefault();
			branch_description_chart.pop();	
			document.querySelector("#chartarea_cashdrop").innerHTML = '<canvas id="chart_cashdrop" style="max-height: 400px; display: block; box-sizing: border-box; height: 393px; width: 787px;" width="787" height="393"></canvas>';

					/*Reset Warnings*/
					$('#client_idxError').text('');
					$('#start_date_cash_dropError').text('');
					$('#end_date_cash_dropError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#table_cashdrop tbody").html("");					
					
			document.getElementById('generate_report_cash_drop_form').className = "g-3 needs-validation was-validated";

			let start_date 		= $("input[name=start_date_cash_drop]").val();
			let end_date 		= $("input[name=end_date_cash_drop]").val();
			let company_header 	= $("#company_header_cash_drop").val();	
			 
			  $.ajax({
				url: "/generate_cash_drop",
				type:"POST",
				data:{
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CashDropReportModal').modal('toggle');
				
				var report_mode = 'cash_drop';
				
				get_branch_details(report_mode);
							
				  console.log(response);
				  if(response!='') {
					
					$('#client_idxError').text('');
					$('#start_date_cash_dropError').text('');
					$('#end_date_cash_dropError').text('');	
				
						var grand_total_cash_drop = 0;

						var data = response.data;
						var len = data.length;

						for (var i = 0; i < len; i++) {

							var row = data[i];

							var date_shift = row.report_date;

							var total_cash_drop = parseFloat(row.total_cash_drop) || 0;

							grand_total_cash_drop += total_cash_drop;

							var data_count = i + 1;

							addData(date_shift, total_cash_drop, data_count);
						}			
						
						LoadCashDropData.clear().draw();
						LoadCashDropData.rows.add(response.data).draw();	
							
							$('#grand_total_cash_drop').text(grand_total_cash_drop.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
									
							var start_date_cash_drop_new  = new Date(start_date);
							start_date_cash_drop_new_format = (start_date_cash_drop_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_cash_drop_new  = new Date(end_date);
							end_date_cash_drop_new_format = (end_date_cash_drop_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#date_range_info_cashdrop').text(start_date_cash_drop_new_format + ' - ' +end_date_cash_drop_new_format);	
							
						
							branch_description_chart.push('Total Cash Drop');
							
							$("#download_options_cashdrop").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_cashdrop_report_pdf()"> PDF</button>'+
							'</div>');
							
				  }else{
							/*Close Form*/
							$('#CashDropReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_non_cash').text('');
							

							$('#branch_name_report_cashdrop').text('');
							$('#date_range_info_cashdrop').text('');
							
							$("#table_cashdrop tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
							$("#download_options_cashdrop").html(''); 
				
					}
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("generate_report_cash_drop").disabled = true;
					/*Show Status*/
					$('#loading_data_cash_drop').show();
					
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("generate_report_cash_drop").disabled = false;
					/*Hide Status*/
					$('#loading_data_cash_drop').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#start_date_cash_dropError').text(error.responseJSON.errors.start_date_cash_drop);
				  document.getElementById('start_date_cash_dropError').className = "invalid-feedback";		

				  $('#end_date_cash_dropError').text(error.responseJSON.errors.end_date_cash_drop);
				  document.getElementById('end_date_cash_dropError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
		/*chart Setup*/		
		var canvas = document.getElementById("chart_cashdrop");
		var ctx = canvas.getContext('2d');
		var chartType = 'bar';
		var myBarChart;
			  
		var data = {
		  labels: [],
		  datasets: [{
			label: branch_description_chart,
			fill: false,
			lineTension: 0.1,
			backgroundColor: "#ffab11",
			borderColor: "#ffab11", // The main line color
			borderCapStyle: 'square',
			pointBorderColor: "white",
			pointBackgroundColor: "#ffab11",
			pointBorderWidth: 1,
			pointHoverRadius: 4,
			pointHoverBackgroundColor: "green",
			pointHoverBorderColor: "rgba(33, 124, 83, 0.8)",
			pointHoverBorderWidth: 9,
			pointRadius: 2,
			pointHitRadius: 5,
			data: [],
			spanGaps: true,
		  }]
		};
				
		var options = {
		  /*scales: {
			yAxes: [{
			  ticks: {
				beginAtZero: true
			  }
			}]
		  },*/
		  title: {
			fontSize: 18,
			display: true,
			text: '',
			position: 'bottom'
		  }
		};

		init();

		function init() {
		  // Chart declaration:
		  myBarChart = new Chart(ctx, {
			type: chartType,
			data: data,
			options: options
		  });
		}

		function removeData(myBarChart) {
			myBarChart.data.labels.pop();
			myBarChart.data.datasets.forEach((dataset) => {
				dataset.data.pop();
			});
			myBarChart.update();
		}

		function addData(datetime,kwh_total,data_count) {
			  myBarChart.data.labels[data_count] =datetime;
			  myBarChart.data.datasets[0].data[data_count] = kwh_total;
			  myBarChart.update();
		}
	
	});

		/*Load to Datatables*/	
		let LoadCashDropData = $('#table_cashdrop').DataTable({
				"language": {
						"emptyTable": "No Result Found",
						"infoEmpty": "No entries to show"
			    }, 
				processing: true,
				serverSide: false,
				//stateSave: true,/*Remember Searches*/
				responsive: true,
				paging: true,
				searching: false,
				info: false,
				data: [],
				scrollCollapse: true,
				scrollY: '500px',
				scrollx: true,
				"columns": [
				 	{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false, className: "text-center",},  
				 	{data: 'report_date', className: "text-center", orderable: false },
				 	{data: 'branch_initial', className: "text-left", orderable: false },				
				 	{data: 'shift', className: "text-center", orderable: false },
				 	{data: 'cashiers_name', className: "text-left", orderable: false },
				 	{data: 'total_cash_drop', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				],
				
		});
		
		
	autoAdjustColumns(LoadCashDropData);

		 /*Adjust Table Column*/
		 function autoAdjustColumns(table) {
			 var container = table.table().container();
			 var resizeObserver = new ResizeObserver(function () {
				 table.columns.adjust();
			 });
			 resizeObserver.observe(container);
		 }

		function download_cashdrop_report_pdf(){

			let start_date     = $("input[name=start_date_cash_drop]").val();
			let end_date       = $("input[name=end_date_cash_drop]").val();
			let company_header = $("#company_header_cash_drop").val();

			// 🔥 convert to Date
			let start = new Date(start_date);
			let end   = new Date(end_date);

			// compute difference in days
			let diffTime = Math.abs(end - start);
			let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

			var query = {
				start_date: start_date,
				end_date: end_date,
				company_header: company_header,
				_token: "{{ csrf_token() }}"
			};

			// 🔥 SWITCH LOGIC
			if (diffDays > 31) {

				// Excel
				let url = "{{URL::to('generate_cashdrop_report_excel')}}?" + $.param(query);

				alert("Large data detected. Export switched to Excel.");

				window.open(url);

			} else {

				// PDF
				let url = "{{URL::to('generate_cashdrop_report_pdf')}}?" + $.param(query);

				window.open(url);
			}
		}

</script>
