<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
	
</head>
<body>
    <h1>{{ $title }}</h1>
    <p>{{ $client_data['client_name'] }}</p>
	<p>{{ $client_data['client_address'] }}</p>
    
	<table class="table table-bordered dataTable" width="100%" cellspacing="0">
											<thead>
												<tr>
													<th>#</th>
													<th>Date</th>
													<th>Driver's Name</th>
													<th>P.O No.</th>
													<th>Plate Number</th>
													<th>Product</th>
													<th>Quantity</th>
													<th>Price</th>
													<th>Amount</th>
													<th>Time</th>
												</tr>
											</thead>				
											
											<tbody>
	@foreach ($billing_data as $billing_data_cols)
									<tr>
									<td>{{$billing_data_cols->order_date}}</td>
									</tr>
	@endforeach
	

												
											</tbody>
										</table>
</body>
</html>