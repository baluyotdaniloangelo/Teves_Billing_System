
<script type="text/javascript">
	
	function setMaxonEndDate(form_type){
		
		let start_date 			= $("input[name=start_date_"+form_type+"]").val();
		
		var myDate = new Date(start_date);
		var result1 = myDate.setMonth(myDate.getMonth()+12);
		
		const date_new = new Date(result1);
		
		const max_date = document.getElementById('end_date_'+form_type);
		
		document.getElementById("end_date_"+form_type).min = start_date;
		document.getElementById("end_date_"+form_type).max = date_new.toISOString("en-US").substring(0, 10);
		
		document.getElementById("end_date_"+form_type).value = start_date;
		
	}
	
	function CheckEndDateValidity(form_type){
		
		let start_date 			= $("input[name=start_date_"+form_type+"]").val();
		let end_date 			= $("input[name=end_date_"+form_type+"]").val();
		
		let end_date_max 		= document.getElementById("end_date_"+form_type).max;
		
		const x = new Date(start_date);
		const y = new Date(end_date);
		
		const edt = new Date(end_date_max);
		
			if(x > y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_"+form_type).value = start_date;
				
			}
			else if(edt < y){
					
					/*Set The End Date same with Start Date*/
					document.getElementById("end_date_"+form_type).value = start_date;
					
			}else{
					$('#end_date_'+form_type+'_Error').html('');
					document.getElementById('end_date_'+form_type+'_Error').className = "valid-feedback";
			}
	
	}

	function setCurrentMonth(report_type) {

		let today = new Date();

		let firstDay = new Date(
			today.getFullYear(),
			today.getMonth(),
			1
		);

		let lastDay = new Date(
			today.getFullYear(),
			today.getMonth() + 1,
			0
		);

		document.getElementById('start_date_'+report_type).value =
			firstDay.toISOString().split('T')[0];

		document.getElementById('end_date_'+report_type).value =
			lastDay.toISOString().split('T')[0];
	}

	function setCurrentYear(report_type) {

		let today = new Date();

		let firstDay =
			today.getFullYear() + '-01-01';

		let lastDay =
			today.getFullYear() + '-12-31';

		document.getElementById('start_date_'+report_type).value =
			firstDay;

		document.getElementById('end_date_'+report_type).value =
			lastDay;
	}

	function setLast12Months(report_type) {

		let today = new Date();

		let startDate = new Date();
		startDate.setMonth(startDate.getMonth() - 11);
		startDate.setDate(1);

		let endDate = new Date(
			today.getFullYear(),
			today.getMonth() + 1,
			0
		);

		document.getElementById('start_date_'+report_type).value =
			startDate.toISOString().split('T')[0];

		document.getElementById('end_date_'+report_type).value =
			endDate.toISOString().split('T')[0];
	}	


	function get_branch_details(company_header,report_type){
			
			event.preventDefault();
			let branchID 		= company_header;
			
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
					
					$('#branch_name_'+report_type+'_report').text(response.branch_name);
					$('#branch_code_'+report_type+'_report').text(response.branch_code);
					$('#branch_address_'+report_type+'_report').text(response.branch_address);
					$('#branch_tin_'+report_type+'_report').text(response.branch_tin);						
				  
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}
	
	function get_supplier_details(supplier_idx,report_type){
		
			event.preventDefault();
			let supplierID = supplier_idx;
			
			  $.ajax({
				url: "/supplier_info",
				type:"POST",
				data:{
				  supplierID:supplierID,
				  _token: "{{ csrf_token() }}"
				},
				success:function(response){
				  console.log(response);
				  if(response) {
					  
					/*Set Details*/
					$('#supplier_name_'+report_type+'_report').text(response.supplier_name);
					$('#supplier_address_'+report_type+'_report').text(response.supplier_address);
					$('#supplier_tin_'+report_type+'_report').text(response.supplier_tin);		
					
				  }
				},
				error: function(error) {
				 console.log(error);
					alert(error);
				}
			   });	
	}
	  
	/*Adjust Table Column*/
	function autoAdjustColumns(table) {
		var container = table.table().container();
		var resizeObserver = new ResizeObserver(function () {
			table.columns.adjust();
		});
		resizeObserver.observe(container);
	}  

</script>
