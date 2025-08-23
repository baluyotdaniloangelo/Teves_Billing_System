   <!-- Page level plugins -->
   <script src="{{asset('Datatables/2.0.8/js/dataTables.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>
   <script src="{{asset('Datatables/responsive/3.0.2/js/responsive.dataTables.js')}}"></script>
   
   <script src="{{asset('template/assets/vendor/chart.js/chart.min.js')}}"></script>   
   
<script type="text/javascript">

	setMaxonEndDate();
	
	function setMaxonEndDate(){
	
		let start_date 			= $("input[name=start_date]").val();
		
		var myDate = new Date(start_date);
		var result1 = myDate.setMonth(myDate.getMonth()+12);
		
		const date_new = new Date(result1);
		
		const max_date = document.getElementById('end_date');
		
		document.getElementById("end_date").min = start_date;
		document.getElementById("end_date").max = date_new.toISOString("en-US").substring(0, 10);
		
		document.getElementById("end_date").value = start_date;
		
	}
	
	function CheckEndDateValidity(){
		
		let start_date 			= $("input[name=start_date]").val();
		let end_date 			= $("input[name=end_date]").val();
		
		let end_date_max 		= document.getElementById("end_date").max;
		
		const x = new Date(start_date);
		const y = new Date(end_date);
		
		const edt = new Date(end_date_max);
		
			if(x > y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date").value = start_date;
				
			}
			else if(edt < y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date").value = start_date;
					
			}else{
					$('#end_dateError').html('');
					document.getElementById('end_dateError').className = "valid-feedback";
			}
	
	}

	<!--Load Table-->
	var branch_description_chart = [];
	$("#generate_report").click(function(event){
		
			event.preventDefault();
			branch_description_chart.pop();	
			document.querySelector("#chartarea").innerHTML = '<canvas id="KWhChart" style="max-height: 400px; display: block; box-sizing: border-box; height: 393px; width: 787px;" width="787" height="393"></canvas>';

					/*Reset Warnings*/
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#billingstatementreport tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();	
			
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/generate_daily_sales",
				type:"POST",
				data:{
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CreateReportModal').modal('toggle');
				
				get_branch_details();
							
				  console.log(response);
				  if(response!='') {
					
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');	
				
						var total_fuel_sales = 0;
						var total_discount = 0;
						var total_cashout_other = 0;
						var total_other_sales = 0;
						var total_theoretical_sales = 0;
						
						var total_cash_tansaction = 0;
						var total_non_cash_payment = 0;
						var total_cash_sales = 0;
						
						var total_sales = 0;
						var total_short_over = 0;
						
						var len = response['data'].length;
						
						for(var i=0; i<len; i++){
							
							var date_shift 						= response['data'][i].date;
							var first_shift_total_sales 		= response['data'][i].first_shift_total_sales;
							var second_shift_total_sales 		= response['data'][i].second_shift_total_sales;
							var third_shift_total_sales 		= response['data'][i].third_shift_total_sales;
							var fourth_shift_total_sales 		= response['data'][i].fourth_shift_total_sales;
							var fifth_shift_total_sales 		= response['data'][i].fifth_shift_total_sales;
							var sixth_shift_total_sales 		= response['data'][i].sixth_shift_total_sales;
							
							total_fuel_sales 					+= response['data'][i].daily_fuel_sales;
							total_discount 						+= response['data'][i].daily_discount;
							total_cashout_other 				+= response['data'][i].daily_cashout_other;
							total_other_sales 					+= response['data'][i].daily_other_sales;
							total_theoretical_sales 			+= response['data'][i].daily_theoretical_sales;
							
							total_cash_tansaction 				+= response['data'][i].daily_cash_transaction;
							total_non_cash_payment 				+= response['data'][i].daily_non_cash_payment;
							total_cash_sales 					+= response['data'][i].daily_total_cash_sales;
	
							total_daily_sales = first_shift_total_sales + second_shift_total_sales + third_shift_total_sales + fourth_shift_total_sales + fifth_shift_total_sales + sixth_shift_total_sales;
		
							total_sales += first_shift_total_sales + second_shift_total_sales + third_shift_total_sales + fourth_shift_total_sales + fifth_shift_total_sales + sixth_shift_total_sales;					
							total_short_over += response['data'][i].daily_short_over;
							
							var data_count = i+1;
							addData(date_shift,total_daily_sales,data_count);
							
						}			
						
						LoadBillingHistoryData.clear().draw();
						LoadBillingHistoryData.rows.add(response.data).draw();	
							
							$('#total_fuel_sales').text(total_fuel_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_discount').text(total_discount.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_cashout_other').text(total_cashout_other.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_other_sales').text(total_other_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_theoretical_sales').text(total_theoretical_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_cash_tansaction').text(total_cash_tansaction.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_non_cash_payment').text(total_non_cash_payment.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							$('#total_cash_sales').text(total_cash_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_sales').text(total_sales.toLocaleString("en-PH", {maximumFractionDigits: 2}));
							
							$('#total_short_over').text(total_short_over.toLocaleString("en-PH", {maximumFractionDigits: 2}));
									
							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#date_range_info').text(start_date_new_format + ' - ' +end_date_new_format);	
						
							branch_description_chart.push('Daily Sales');
							
							$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_daily_sales_report_pdf()"> PDF</button>'+
							'</div>');
							
				  }else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_sales').text('');
							$('#total_short_over').text('');

							$('#branch_name_report').text('');
							$('#date_range_info').text('');
							
							$("#billingstatementreport tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
							$("#download_options").html(''); 
				
					}
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("generate_report").disabled = true;
					/*Show Status*/
					$('#loading_data').show();
					
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("generate_report").disabled = false;
					/*Hide Status*/
					$('#loading_data').hide();
					
				},
				error: function(error) {
				 console.log(error);	
				 
				  $('#start_dateError').text(error.responseJSON.errors.start_date);
				  document.getElementById('start_dateError').className = "invalid-feedback";		

				  $('#end_dateError').text(error.responseJSON.errors.end_date);
				  document.getElementById('end_dateError').className = "invalid-feedback";		
				
				$('#InvalidModal').modal('toggle');				  	  
				  
				}
			   });
		
		/*chart Setup*/		
		var canvas = document.getElementById("KWhChart");
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
		let LoadBillingHistoryData = $('#billingstatementreport').DataTable({
				"language": {
						"emptyTable": "No Result Found",
						"infoEmpty": "No entries to show"
			    }, 
				// processing: true,
				//serverSide: true,
				//stateSave: true,/*Remember Searches*/
				responsive: true,
				paging: false,
				searching: false,
				info: false,
				data: [],
				scrollCollapse: true,
				scrollY: '500px',
				scrollx: true,
				"columns": [
				/*0*/	{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false, className: "text-center",},  
				/*1*/	{data: 'date', className: "text-center", orderable: false },
				/*2*/	{data: 'first_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*3*/	{data: 'second_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*4*/	{data: 'third_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },	
				/*5*/	{data: 'fourth_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },	
				/*6*/	//{data: 'fifth_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*7*/	//{data: 'sixth_shift_total_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*8*/	{data: 'daily_fuel_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*9*/	{data: 'daily_other_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*10*/	{data: 'shift_total_sales_sum', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*11*/	{data: 'daily_discount', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*12*/	{data: 'daily_cashout_other', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*13*/	{data: 'daily_theoretical_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*14*/	{data: 'daily_cash_transaction', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*15*/	{data: 'daily_non_cash_payment', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*16*/	{data: 'daily_total_cash_sales', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				/*17*/	{data: 'daily_short_over', className: "text-right", orderable: true, render: $.fn.dataTable.render.number( ',', '.', 2, '' ) },
				],
				
		});
		
	autoAdjustColumns(LoadBillingHistoryData);

		 /*Adjust Table Column*/
		 function autoAdjustColumns(table) {
			 var container = table.table().container();
			 var resizeObserver = new ResizeObserver(function () {
				 table.columns.adjust();
			 });
			 resizeObserver.observe(container);
		 }

	function download_daily_sales_report_pdf(receivable_id){
			
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();
			
		var query = {
			start_date:start_date,
			end_date:end_date,
			company_header:company_header,
			_token: "{{ csrf_token() }}"
		}

		var url = "{{URL::to('generate_daily_sales_report_pdf')}}?" + $.param(query)
		window.open(url);
	  
	}


	<!--Select Branch For Update-->
	function get_branch_details(){
			
			event.preventDefault();
			let branchID 		= $("#company_header").val();
			
			  $.ajax({
				url: "{{ route('BranchInfo') }}",
				type:"POST",
				data:{
				  branchID:branchID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					
					$('#branch_name_report').text(response.branch_name);
					$('#branch_code_report').text(response.branch_code);
					$('#branch_address_report').text(response.branch_address);
					$('#branch_tin_report').text(response.branch_tin);						
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  }
	    
</script>
