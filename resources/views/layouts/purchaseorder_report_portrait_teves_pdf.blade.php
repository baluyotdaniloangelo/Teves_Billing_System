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
			font-size: 12px;
		}
		.data_tr {
			padding: 5px;
		} 
		.td_colon:before{
			content:":";
			font-weight:bold;
			text-align:center;
			color:black;
			position:relative;
			left:-30px;
		}
</style>
</head>
<body>
    
	<table class="" width="100%" cellspacing="0" cellpadding="1">
		
			<?php
				$_purchase_order_date=date_create($purchase_order_data[0]['purchase_order_date']);
				$purchase_order_date = strtoupper(date_format($_purchase_order_date,"M/d/Y"));
			?>
			
		<tr>
			<td nowrap style="horizontal-align:top;text-align:left;" align="center" colspan="1" rowspan="4" width="10%"><img src="{{public_path('client_logo/logo.jpg')}}" style="width:112px;"></td>
			<td colspan="7" width="40%" style="horizontal-align:center;text-align:left;"><b style="font-size:18px;">TEVES GASOLINE STATION</b></td>
			<td colspan="2" nowrap align="left" width="50%" style="font-size:12px; background-color: yellowgreen; text-align:center; font-weight:bold; color:#000; border-top-left-radius:30px;border-bottom-left-radius:30px;"><b>{{ $title }}</b></td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;">San Juan, Madrid Surigao del Sur</div>
			</td>
			<td colspan="3" align="left" width="20%" style="font-size:12px; font-weight:bold; color:red;"><b>CONTROL NO.</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:12px; color:red; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_control_number'] }}</td>
		</tr>		
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;">VAT REG. TIN : 740-213-285-001</div>
			</td>
			<td colspan="3" align="left" width="20%" style="font-size:12px; font-weight:bold;;"><b>DATE</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon"><?=$purchase_order_date;?></td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;">GLEZA F. TEVES - Proprietress</div>
			</td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>SALES ORDER NO.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_sales_order_number'] }}</td>
		</tr>
		
		<tr>
			<td colspan="4"  width="50%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>COLLECTION RECEIPT NO.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_collection_receipt_no'] }}</td>
		</tr>
		
		<tr>
			<td colspan="4"  width="50%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>SALES INVOICE NO.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_official_receipt_no'] }}</td>
		</tr>

		<tr>
			<td colspan="4"  width="50%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>DELIVERY RECEIPT NO.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_delivery_receipt_no'] }}</td>
		</tr>
		
		</table>