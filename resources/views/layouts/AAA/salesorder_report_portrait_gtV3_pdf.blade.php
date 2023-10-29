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
		}
	</style>
	
</head>
<body>
    
	<table class="" width="100%" cellspacing="0" cellpadding="1">
		
			<?php
				$_sales_order_date=date_create($sales_order_data[0]['sales_order_date']);
				$sales_order_date = strtoupper(date_format($_sales_order_date,"M/d/Y"));
			?>
		
		<tr>
			<td nowrap style="vertical-align:top;text-align:left;" align="center" colspan="1" rowspan="6" width="10%"><img src="{{public_path('client_logo/logo-2.jpg')}}" style="width:110px;"></td>
			<td colspan="7" width="40%" style="horizontal-align:center;text-align:left;">
				<b style="font-size:18px;">G-T PETROLEUM PRODUCTS RETAILING</b><br>
			</td>
			<td colspan="2" align="left" width="50%" style="font-size:14px; background-color: orange; text-align:center; font-weight:bold; color:#000; border-top-left-radius:30px;border-bottom-left-radius:30px;"><b>{{ $title }}</b></td>

			
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:11px;">San Juan, Madrid Surigao del Sur</div>
			</td>
			<td colspan="1" align="left" width="19%" style="font-size:12px; font-weight:bold; color:red;"><b>CONTROL NO.</b></td>
			<td colspan="1" align="left" width="1%" style="font-size:12px; font-weight:bold; color:red;"><b> : </b></td>
			<td colspan="4" align="left" width="30%" style="font-size:12px; color:red; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_control_number'] }}</td>

		</tr>		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:11px;">VAT REG. TIN : 740-213-285-000</div>
			</td>
			<td colspan="1" align="left" width="19%" style="font-size:12px; font-weight:bold;"><b>DATE GENERATED</b></td>
			<td colspan="1" align="left" width="1%" style="font-size:12px; font-weight:bold; color:red;"><b> : </b></td>
			<td colspan="4" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;"><?=$sales_order_date;?></td>
		</tr>
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:11px;">GLEZA F. TEVES - Proprietress</div>
			</td>
			<td colspan="1" align="left" width="19%" style="font-size:12px; font-weight:bold;"><b>D.R. NO.</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="4" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_dr_number'] }}</td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="1" align="left" width="19%" style="font-size:12px; font-weight:bold;"><b>P.O. NO.</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="4" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_or_number'] }}</td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="1" align="left" width="19%" style="font-size:12px; font-weight:bold;"><b>PAYMENT TERM</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="4" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_payment_term'] }}</td>
		</tr>

		
		</table>