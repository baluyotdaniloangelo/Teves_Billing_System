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
    
	<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
	
		<tr>
			<td rowspan="4" align="center" colspan="4">
			<img src="{{public_path('client_logo/logo.jpg')}}" style="width:150px;">
			</td>
			<td nowrap style="font-size:20px; font-weight:bold;" align="center" colspan="3">TEVES GASOLINE STATION</td>
		</tr>
		
		<tr>
			<td nowrap style="font-size:12px;" align="center" colspan="3">San Juan, Madrid Surigao del Sur</td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="3">VAT REG. TIN : 496-617-672-000</td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="3">GLENN F. TEVES - Proprietor</td>
		</tr>
		
		<tr style="font-size:14px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:14px;">
			<td colspan="4" style="border:1px solid #000; background-color: #c6e0b4; text-align:center; font-weight:bold; font-size:16px !important; padding:5px;"> {{ $title }} </td>
			<td colspan="2"></td>
			<td colspan="2" nowrap align="left" style="color:red">CONTROL NO :</td>
			<td colspan="2"><div align="left" style="color:red">{{ $receivable_data[0]['control_number'] }}</td>
		</tr>
		<tr style="font-size:14px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:14px;">
			<td colspan="2" align="left">ACCOUNT NAME :</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000;">{{ $receivable_data[0]['client_name'] }}</td>			
			<td colspan="2" nowrap align="left">BILLING DATE :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;">{{ $receivable_data[0]['billing_date'] }}</td>
		</tr>
		
		<tr style="font-size:14px;">
			<td colspan="2" align="left">TIN:</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000;">{{ $receivable_data[0]['client_tin'] }}</td>			
			<td colspan="2" nowrap align="left">O.R. NO. :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;">{{ $receivable_data[0]['or_number'] }}</td>
		</tr>
		
		<tr style="font-size:14px;">
			<td colspan="2" align="left">ADDRESS :</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000; ">{{ $receivable_data[0]['client_address'] }}</td>			
			<td colspan="2" nowrap align="left">PAYMENT TERM :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $receivable_data[0]['payment_term'] }}</td>
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		
		<tr style="font-size:14px;border:1 solid #000;">
			<td colspan="6" align="center" style="border:1px solid #000;  background-color: #c6e0b4; font-weight:bold;">DESCRIPTION</td>		
			<td colspan="4" nowrap align="center" style="border:1px solid #000;    background-color: #c6e0b4; font-weight:bold;">AMOUNT</td>		
		</tr>									
		
		<tr style="font-size:14px;">
			
			<td colspan="6" align="left" style="border-left:1px solid #000;height: 200px; padding:10px;">{{ $receivable_data[0]['receivable_description'] }}</td>
			<td colspan="4" align="center" style="border-right:1px solid #000;height: 200px;border-bottom:solid 1px;">{{ $receivable_data[0]['receivable_amount'] }}</td>			
		
		</tr>
		
		<tr style="font-size:14px;">
			
			<td colspan="3" align="right" style="border-left: 1px solid #000;"></td>
			<td colspan="3" align="center" style="background-color: #c6e0b4; font-weight:bold;">TOTAL DUE </td>
			<td colspan="4" align="center" style="background-color: #c6e0b4; border-right: 1px solid #000; border-bottom:double;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> {{ $receivable_data[0]['receivable_amount'] }}</td>			
		
		</tr>		

		<tr>
			<td colspan="10" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">&nbsp;</td>
		</tr>
		
		<tr>
			<td colspan="10" style="">&nbsp;</td>
		</tr>
		
		<tr style="font-size:14px;border:1 solid #000;font-style: italic;">
			<td colspan="2" align="center" style="border:1px solid #000;">In Words</td>
			<td colspan="8" style="border:1px solid #000; text-align:center;">&nbsp;{{ $amount_in_words }}</td>
		</tr>

		<tr>
			<td colspan="10" style="">&nbsp;</td>
		</tr>
		
		<tr style="font-size:12px;border:0px solid #000;font-style: italic;">
			<td colspan="10" align="left"style="border:1px solid #000;">
			By signing below, you acknowledge to have a pending payable as stated above. And you receive the</br>
			<b>ORIGINAL ATTACHMENTS SUBJECT FOR RE-CHECKING</b>
			</td>
		</tr>
		
		<tr>
			<td colspan="10" style="">&nbsp;</td>
		</tr>	
		
		<tr style="font-size:14px;border:0 solid #000;">
			<td colspan="10" align="center" style="border:1px solid #000; font-weight:bold; background-color: #c6e0b4;">MODE OF PAYMENT</td>			
		</tr>		
		 
		<tr style="font-size:14px;border:0 solid #000;">
			<td colspan="5" align="center" style="border-bottom:1px solid #000; border-left: 1px solid #000; font-weight:bold;">Check / Bank Deposit</td>		
			<td colspan="5" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold;">Cash</td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000;">Check Name :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;">Denomination</td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;">Quantity</td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;">Amount</td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000;">Check Amount :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>	
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000;">Check Date :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>

		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000;">Check No. :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" align="left" style="border-left:1px solid #000;">Bank :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000;">Bank Address/Branch :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="1" align="left" style="border-left:1px solid #000;">&nbsp;</td>	
			<td colspan="4" align="center" style="border-bottom:0px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="5" align="left" style="border-left:1px solid #000;border-top:1px solid #000;">For withheld client :</td>	
			<td colspan="3" nowrap align="center" style="border-bottom:0px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold;">TOTAL CASH</td>			
			<td colspan="2" nowrap align="center" style="border-bottom:0px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>

		<tr style="font-size:14px;border:0 solid #000;">
			<td colspan="10" align="left" style="border:1px solid #000; font-weight:bold;">TOTAL PAYMENT :</td>		
		</tr>
		
		<tr>
			<td colspan="10" style="">&nbsp;</td>
		</tr>
	  
		<tr style="font-size:12px;">
			<td colspan="3" align="left" style="border-top:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000; font-style: italic;">&nbsp;Billing Prepared by:</td>	
			<td colspan="3" nowrap align="left" style="border-top:1px solid #000;border-right:1px solid #000; font-style: italic;">&nbsp;Billing Released by:</td>			
			<td colspan="4" nowrap align="left" style="border-top:1px solid #000; border-right:1px solid #000; font-style: italic;">&nbsp;Billing Received by:</td>			
		</tr>
			
		<tr>
			<td colspan="3" style="border-left:1px solid #000; border-right:1px solid #000;">&nbsp;</td>	
			<td colspan="3" style="border-right:1px solid #000;">&nbsp;</td>			
			<td colspan="4" style="border-right:1px solid #000;">&nbsp;</td>	
		</tr>
		
		<tr>
			<td colspan="3" style="border-left:1px solid #000; border-right:1px solid #000;">&nbsp;</td>	
			<td colspan="3" style="border-right:1px solid #000;">&nbsp;</td>			
			<td colspan="4" style="border-right:1px solid #000;">&nbsp;</td>	
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$user_data->user_real_name}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>	
			<td colspan="3" align="center" style="border-left:0px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>				
			<td colspan="4" align="center" style="border-left:0px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>	
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;">{{$user_data->user_job_title}}</td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;">Office Staff</td>			
			<td colspan="4" align="center" style="border-right:1px solid #000;">Signature over Printed Name</td>	
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;"></td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;"></td>			
			<td colspan="4" align="left" style="border-right:1px solid #000;">&nbsp;Date    :</td>	
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;border-bottom:1px solid #000;"></td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;"></td>			
			<td colspan="4" align="left" style="border-right:1px solid #000;border-bottom:1px solid #000;">&nbsp;Time    :</td>	
		</tr>
		
		</table>
		
</body>
</html>