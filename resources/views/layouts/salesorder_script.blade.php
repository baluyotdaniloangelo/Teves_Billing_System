   <script type="text/javascript">

	<!--Load Table-->
	$(function () {

		var ReceivableListTable = $('#getSalesOrderList').DataTable({
			"language": {
						"lengthMenu":'<select class="form-select form-control form-control-sm">'+
			             '<option value="10">10</option>'+
			             '<option value="20">20</option>'+
			             '<option value="30">30</option>'+
			             '<option value="40">40</option>'+
			             '<option value="50">50</option>'+
			             '<option value="-1">All</option>'+
			             '</select> '
		    },
			/*processing: true,*/
			serverSide: true,
			stateSave: true,/*Remember Searches*/
			ajax: "{{ route('getSalesOrderList') }}",
			columns: [
					{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
					{data: 'sales_order_date'},
					{data: 'control_number'},
					{data: 'client_name'},   
					{data: 'dr_number'},
					{data: 'or_number'},
					{data: 'payment_term'},
					{data: 'total_due'},
					{data: 'action', name: 'action', orderable: false, searchable: false},
			],
			columnDefs: [
					{ className: 'text-center', targets: [0, 1, 2, 3, 4] },
			]
		});
				/**/
				$('<div class="btn-group" role="group" aria-label="Basic outlined example" style="margin-top: -50px; position: absolute;">'+
				'<a href="{{ route('createsalesorder') }}"><button type="button" class="btn btn-success new_item bi bi-plus-circle"></button></a>'+
				'</div>').appendTo('#sales_order_option');
				
	});
	
  </script>
	