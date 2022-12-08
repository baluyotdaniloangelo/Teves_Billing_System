<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
	<style>
		body {
			font-family: "Open Sans", sans-serif;
		}
		.data_thead {
			background-color: #000000;
			color: #fff;
		}
		.data_th {
			padding: 5px;
			font-size: 10px;
		}
		.data_tr {
			padding: 5px;
			font-size: 10px;
		}
	</style>
	
</head>
<body>
	<div align="center">					
	<table width="100%" cellspacing="0" cellpadding="0">
		
		<tr>
			<td rowspan="3" align="right" colspan="5">
			<img src="{{public_path('client_logo/logo.png')}}" style="width:150px;"
			<!--<img src="{{asset('client_logo/logo.png')}}" style="width:125px;">-->
			</td>
			<td  colspan="5" style="font-size:16px; font-weight:bold;" align="center" colspan="2">TEVES GASOLINE STATION</td>
		</tr>
		
		<tr>
			<td  style="font-size:12px;" align="center" colspan="2">San Juan, Madrid Surigao del Sur</td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="2">Mobile: (+63) 948-199-0989 / (+63) 938-406-5237</td>
		</tr>
		
		<tr>
			<td colspan="10"><div align="center"><h5>{{ $title }}</h5></div></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="2" align="left">Client:</td>
			<td colspan="3" align="left" style="border-bottom:2px solid #000;">{{ $client_data['client_name'] }}</td>
			<td colspan="1" nowrap align="right">P.O Period:</td>
			<td colspan="4" nowrap align="left" style="border-bottom:2px solid #000;">&nbsp;{{ $start_date }} - {{ $end_date }}</td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="2" align="left">Address:</td>
			<td colspan="3" align="left" style="border-bottom:2px solid #000;">{{ $client_data['client_address'] }}</td>
			<td colspan="1" align="right">Billing Date:</td>
			<td colspan="4" align="left" style="border-bottom:2px solid #000;">&nbsp;<?php echo date('Y-m-d'); ?></td>
		</tr>
		
	</table>
	</div>
	
	
    <br/>
	
    
	<table class="" width="100%" cellspacing="0" cellpadding="1" >
		
		<thead class="data_thead">
			<tr>
				<th class="data_th" style="border:1px solid #000;">#</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Date</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Time</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Driver's Name</th>
				<th class="data_th" nowrap style="border:1px solid #000;">P.O No.</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Plate Number</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Product</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Price</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Quantity</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Amount</th>
			</tr>
		</thead>				
											
		<tbody>
			<?php 
			$no = 1;
			?>
			@foreach ($billing_data as $billing_data_cols)
			<tr class="data_tr" >
				<td align="center" nowrap style="border:1px solid #000;"><?=$no;?></td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_date}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_time}}</td>
				<td nowrap style="border:1px solid #000;">{{$billing_data_cols->drivers_name}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_po_number}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->plate_no}}</td>
				<td nowrap style="border:1px solid #000;">{{$billing_data_cols->product_name}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->product_price}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_quantity}} {{$billing_data_cols->product_unit_measurement}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_total_amount}}</td>
			</tr>
			<?php $no++; ?>
			@endforeach
		</tbody>
		</table>
		
</body>
</html>