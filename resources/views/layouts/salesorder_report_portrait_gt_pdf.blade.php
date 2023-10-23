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
			<td rowspan="6" align="center" colspan="1" width="15%">
			<img src="{{public_path('client_logo/logo-2.jpg')}}" style="width:105px;">
			</td>
			<td nowrap style="font-size:16px; font-weight:bold;" align="center" colspan="5" width="55%">G-T PETROLEUM PRODUCTS RETAILING</td>
			<td colspan="4" align="left" width="15%" style="font-size:14px; background-color: #c6e0b4; text-align:center; font-weight:bold; color:#000"><b>{{ $title }}</b></td>
		</tr>
		
		<tr>
			<td nowrap style="font-size:12px;" align="center" colspan="5" width="55%">San Juan, Madrid Surigao del Sur</td>
			<td colspan="1" align="left" width="17%" style="font-size:12px; font-weight:bold; color:red;"><b>CONTROL NO.</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold; color:red;"><b> : </b></td>
			<td colspan="2" align="left" width="12%" style="font-size:12px; color:red; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_control_number'] }}</td>
		</tr>
		
		<tr>
			<td nowrap style="font-size:12px;" align="center" colspan="5" width="55%">VAT REG. TIN : 740-213-285-000</td>
			<td colspan="1" align="left" width="17%" style="font-size:12px; font-weight:bold;"><b>DATE GENERATED</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="2" align="left" width="12%" style="font-size:12px; border-bottom:solid 1px gray;"><?=$sales_order_date;?></td>
		</tr>
		<tr>
			<td nowrap style="font-size:12px;" align="center" colspan="5" width="55%">GLEZA F. TEVES - Proprietress</td>
			<td colspan="1" align="left" width="17%" style="font-size:12px; font-weight:bold;"><b>D.R. NO.</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="2" align="left" width="12%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_dr_number'] }}</td>
		</tr>

		<tr>
			<td nowrap style="font-size:12px;" align="left" colspan="5" width="55%">&nbsp;</td>
			<td colspan="1" align="left" width="17%" style="font-size:12px; font-weight:bold;"><b>P.O. NO.</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="2" align="left" width="12%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_or_number'] }}</td>
		</tr>		
		
		<tr>
			<td nowrap style="font-size:12px;" align="left" colspan="5" width="55%">&nbsp;</td>
			<td colspan="1" align="left" width="17%" style="font-size:12px; font-weight:bold;"><b>PAYMENT TERM</b></td>
			<td colspan="1" align="left" width="01%" style="font-size:12px; font-weight:bold;"><b> : </b></td>
			<td colspan="2" align="left" width="12%" style="font-size:12px; border-bottom:solid 1px gray;">{{ $sales_order_data[0]['sales_order_payment_term'] }}</td>
		</tr>	
		
		</table>