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
	$("#generate_sales_report_fuel").click(function(event){
		
			event.preventDefault();
			
					/*Reset Warnings*/
					$('#client_idxError').text('');
					$('#start_dateError').text('');
					$('#end_dateError').text('');		
					
					/*Reset Table Upon Resubmit form*/					
					$("#sale_sorder_summary_table tbody").html("");					
					
			document.getElementById('generate_report_form').className = "g-3 needs-validation was-validated";

			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();	
			//let client_idx 			= $('#client_name option[value="' + $('#client_id').val() + '"]').attr('data-id');
			
			/*Call Function to Get the Grand Total Ammount, PO Range*/  
			
			  $.ajax({
				url: "/generate_sales_report",
				type:"POST",
				data:{
				  start_date:start_date,
				  end_date:end_date,
				  company_header:company_header,
				 // client_idx:client_idx,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				
				/*Close Form*/
				$('#CreateReportModal').modal('toggle');
				
				get_branch_details();
							
				  console.log(response);
				  
				    if (response && response.data && response.data.length > 0) {
						
						renderSalesTable(response.data);

						$("#download_options").html('<div class="btn-group" role="group" aria-label="Basic outlined example" style="">'+
							'<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_sales_report_pdf()"> Sales Report</button>'+
							'<!--<button type="button" class="btn btn-outline-primary btn-sm bi-file-earmark-pdf" onclick="download_cumulative_report_pdf()"> Cumulative Report</button>-->'+
							'</div>');
							
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');

					} 
					else{
							/*Close Form*/
							$('#CreateReportModal').modal('toggle');
							/*No Result Found*/
							$('#total_sales').text('');
							$('#total_short_over').text('');

							$('#branch_name_report').text('');
							$('#date_range_info').text('');
							
							$("#sale_sorder_summary_table tbody").append("<tr><td colspan='10' align='center'>No Result Found</td></tr>");
							$("#download_options").html(''); 
				
					}
				},
				beforeSend:function()
				{
					
					/*Disable Submit Button*/
					document.getElementById("generate_sales_report_fuel").disabled = true;
					/*Show Status*/
					$('#loading_data').show();
					
				},
				complete: function(){
					
					/*Enable Submit Button*/
					document.getElementById("generate_sales_report_fuel").disabled = false;
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

	});

function renderSalesTable(data) {
    const tableId = '#sale_sorder_summary_table';

    // ✅ Destroy old table if it exists
    if ($.fn.DataTable.isDataTable(tableId)) {
        $(tableId).DataTable().clear().destroy();
        $(tableId + ' thead').empty();
        $(tableId + ' tbody').empty();
    }

		// ✅ Extract dynamic keys (exclude DT_RowIndex and report_date)
		let keys = Object.keys(data[0]).filter(k => k !== 'DT_RowIndex' && k !== 'report_date');

		// ✅ Start with fixed columns
		let cols = [
			{ data: 'DT_RowIndex', title: '#' },
			{ 
				data: 'report_date', 
				title: 'Report Date',
				render: function(data, type, row) {
					if (!data) return '';
					let d = new Date(data);
					return d.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
				}
			}
		];

		// ✅ Add dynamic tank/product columns with 3 decimal places
		keys.forEach(k => {
			cols.push({
				data: k,
				title: k.replace(/_/g, ' '),
				orderable: false, //
				render: function(data, type, row) {
					if (data == null || data === '') return '';
					let num = parseFloat(data);
					return isNaN(num) ? data : num.toFixed(2);
				},
				className: "dt-right" // align numbers to the right
			});
		});

		// ✅ Build <thead>
		let theadHtml = '<tr>';
		cols.forEach(c => {
			theadHtml += `<th>${c.title}&nbsp;</th>`;
		});
		theadHtml += '</tr>';
		$(tableId + ' thead').append(theadHtml);

		// ✅ Initialize DataTable
		$(tableId).DataTable({
			data: data,
			columns: cols,
			scrollX: true,
			autoWidth: false,
			destroy: true,
			searching: false,
		});
	}

	function renderSalesTable_old(data) {
		const tableId = '#sale_sorder_summary_table';

		// ✅ Destroy old table if it exists
		if ($.fn.DataTable.isDataTable(tableId)) {
			$(tableId).DataTable().clear().destroy();
			$(tableId + ' thead').empty();
			$(tableId + ' tbody').empty();
		}

		// ✅ Dynamically extract keys from the first object
		let keys = Object.keys(data[0]);

		// ✅ Build DataTables "columns" config
		let cols = keys.map(k => ({
			data: k,
			title: k.replace(/_/g, ' ') // nicer titles
		}));

		// ✅ Build <thead>
		let theadHtml = '<tr>';
		cols.forEach(c => {
			theadHtml += `<th>${c.title}</th>`;
		});
		theadHtml += '</tr>';
		$(tableId + ' thead').append(theadHtml);

		// ✅ Initialize DataTable
		$(tableId).DataTable({
			data: data,
			columns: cols,
			scrollX: true,
			autoWidth: false,
			destroy: true,
		});
	}

	function download_sales_report_pdf(){
			
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			let company_header 		= $("#company_header").val();
			
			var query = {
				start_date:start_date,
				end_date:end_date,
				company_header:company_header,
				_token: "{{ csrf_token() }}"
			}

			var url = "{{URL::to('generate_sales_report_pdf')}}?" + $.param(query)

			window.open(url);
	  
	}


	<!--Select Branch For Update-->
	function get_branch_details(){
			
			event.preventDefault();
			let branchID 		= $("#company_header").val();
			let start_date 			= $("input[name=start_date]").val();
			let end_date 			= $("input[name=end_date]").val();
			
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

							var start_date_new  = new Date(start_date);
							start_date_new_format = (start_date_new.toLocaleDateString("en-PH")); // 9/17/2016
							
							var end_date_new  = new Date(end_date);
							end_date_new_format = (end_date_new.toLocaleDateString("en-PH")); // 9/17/2016

							$('#date_range_info').text(start_date_new_format + ' - ' +end_date_new_format);						
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	  }
	    
</script>
