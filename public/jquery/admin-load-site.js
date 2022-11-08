/*This Script will load the List of Site for Admin*/
$(document).ready(function () {
		$('#siteList').DataTable({
			processing: true,
			serverSide: true,
			ajax: "{{ route('site.list') }}",
			columns: [
				{data: 'DT_RowIndex', name: 'DT_RowIndex' , orderable: false, searchable: false},
				{data: 'company_no'},            
				{data: 'business_entity'},
				{data: 'site_code'},
				{data: 'site_name'},
				{data: 'site_cut_off'},
				{data: 'action', name: 'action', orderable: false, searchable: false}
		],
		 order: [[1, 'ASC']]
		});
});