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
	
		<tr>
			<td rowspan="4" align="center" colspan="3" width="30%">
			<img src="{{public_path('client_logo/logo.jpg')}}" style="width:160px;">
			</td>
			<td nowrap style="font-size:20px; font-weight:bold;" align="center" colspan="7" width="70%">TEVES GASOLINE STATION</td>
		</tr>
		
		<tr>
			<td nowrap style="font-size:12px;" align="center" colspan="7" width="70%">San Juan, Madrid Surigao del Sur</td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="7" width="70%">VAT REG. TIN : 740-213-285-001</td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="7" width="70%">GLEZA F. TEVES - Proprietress</td>
		</tr>
		<tr>
			<td colspan="10" style=" background-color: #c6e0b4; text-align:center; font-weight:bold; !important; padding:5px; height:15px !important;"> {{ $title }} </td>
		</tr>
	</table>
