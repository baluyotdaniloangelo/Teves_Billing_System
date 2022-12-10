<!DOCTYPE html>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
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


    
	<table class="" width="100%" cellspacing="0" cellpadding="1" >
	
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
			<td colspan="1" align="left">Client:</td>
			<td colspan="5" align="left" style="border-bottom:1px solid #000;">{{ $client_data['client_name'] }}</td>			
			<td colspan="1" nowrap align="right">P.O Period:</td>
			<?php
			$_po_start_date=date_create("$start_date");
			$po_start_date = date_format($_po_start_date,"m/d/y");
			
			$_po_end_date=date_create("$end_date");
			$po_end_date = date_format($_po_end_date,"m/d/y");
			?>
			<td colspan="3" nowrap align="left" style="border-bottom:1px solid #000;">&nbsp;<?=$po_start_date;?> - <?=$po_end_date;?></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="1" align="left">Address:</td>
			<td colspan="5" align="left" style="border-bottom:1px solid #000;">{{ $client_data['client_address'] }}</td>			
			<td colspan="1" align="right">Billing Date:</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000;">&nbsp;<?php echo date('m/d/y'); ?></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		
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
			$total_payable = 0;
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
				<td align="center" nowrap style="border:1px solid #000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$billing_data_cols->product_price}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_quantity}} {{$billing_data_cols->product_unit_measurement}}</td>
				<td align="center" nowrap style="border:1px solid #000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{$billing_data_cols->order_total_amount}}</td>
			</tr>
			<?php 
			$no++; 
			$total_payable+= $billing_data_cols->order_total_amount;
			?>
			
			@endforeach
			<tr class="data_tr" style="font-size:12px;">
				<td align="right" colspan="9">Total Payable:</td>
				<td align="center" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=$total_payable;?></td>
			</tr>
			
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">Prepared by:</td>
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="5"></td>
			</tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2"></td>
				<td align="center" colspan="3">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="5"></td>
			</tr>
			
		</tbody>
		</table>
		
</body>
</html>