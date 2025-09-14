<!DOCTYPE html>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title_soa }}</title>
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
			left:-20px;
		}
</style>
</head>
<body>
    
		<table class="" width="100%" cellspacing="0" cellpadding="1">
		
			<?php
				$_billing_date=date_create($receivable_data[0]['billing_date']);
				
				$billing_date = strtoupper(date_format($_billing_date,"M/d/Y"));
				$logo = $branch_header['branch_logo'];
			?>
			
		<tr>
			<td nowrap style="horizontal-align:top;text-align:left;" align="center" colspan="1" rowspan="4" width="10%">
				<img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:112px;">
			</td>
			<td colspan="6" width="30%" style="horizontal-align:center;text-align:left;"><b style="font-size:18px;"><?=$branch_header['branch_name'];?></b></td>
			<td colspan="3" nowrap align="center" width="60%" style="font-size:12px; background-color: purple; text-align:center; font-weight:bold; color:#fff; border-top-left-radius:30px;border-bottom-left-radius:30px; width:50px"><b>{{ $title_soa }}</b></td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;"><?=$branch_header['branch_address'];?></div>
			</td>
			<td colspan="3" align="left" width="20%" style="font-size:11px; font-weight:bold;;"><b>BILLING DATE</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:11px; border-bottom:solid 1px gray;" class="td_colon"><?=$billing_date;?></td>
		</tr>		
		<?php
				$_print_date=date_create(date('Y-m-d'));
				$print_date = strtoupper(date_format($_print_date,"M/d/Y"));
		?>
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;">VAT REG. TIN : <?=$branch_header['branch_tin'];?></div>
			</td>
			<td colspan="3" align="left" width="25%" style="font-size:11px; font-weight:bold;"><b>DATE PRINTED</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:11px; border-bottom:solid 1px gray;" class="td_colon"><?=$print_date;?></td>
		</tr>
		
		<tr>
			<td colspan="3"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:10px;"><?=$branch_header['branch_owner'];?> - <?=$branch_header['branch_owner_title'];?></div>
			</td>
			<td colspan="3" align="left" width="25%" style="font-size:11px; font-weight:bold;"><b>AR REFERENCE</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:11px; border-bottom:solid 1px gray;" class="td_colon">{{ $receivable_data[0]['control_number'] }}</td>
		</tr>
		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		
		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		

		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		
		</table>

		<br>
		<table class="" width="100%" cellspacing="0" cellpadding="1" >	
		<?php
				$_billing_date=date_create($receivable_data[0]['billing_date']);
				$billing_date = strtoupper(date_format($_billing_date,"M/d/Y"));
		?>
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="10%"><b>ACCOUNT NUMBER</b></td>	
			<td colspan="9" align="left" width="90%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_account_number'] }}</td>		
		</tr>
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="10%"><b>ACCOUNT NAME</b></td>	
			<td colspan="9" align="left" width="90%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_name'] }}</td>		
		</tr>
		<?php
				$_print_date=date_create(date('Y-m-d'));
				$print_date = strtoupper(date_format($_print_date,"M/d/Y"));
		?>
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="10%"><b>TIN</b></td>	
			<td colspan="9" align="left" width="90%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_tin'] }}</td>
		</tr>
		
		<tr style="font-size:12px;">		
			<td colspan="1" align="left" width="20%"><b>ADDRESS</b></td>	
			<td colspan="9" align="left" width="80%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_address'] }}</td>			
		</tr>

		<tr style="font-size:12px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		</table>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
				
		
		<tr style="font-size:12px;border:1 solid #afadad;">
			<td colspan="1" align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold; height:25px !important;">Billing Date</td>	
			<td colspan="3" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Description</td>
			<td colspan="1" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Gross Amount</td>
			<td colspan="1" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">WTax</td>
			<td colspan="1" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Net Payable</td>
		</tr>
			
		<?php
			$_po_start_date=date_create($receivable_data[0]['billing_period_start']);
			$po_start_date = strtoupper(date_format($_po_start_date,"M/d/Y"));
			
			$_po_end_date=date_create($receivable_data[0]['billing_period_end']);
			$po_end_date = strtoupper(date_format($_po_end_date,"M/d/Y"));
			?>
		<tr style="font-size:12px;border:1 solid #afadad;">
			
			<td colspan="1" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; height: 20px; padding:10px;"><?=$billing_date;?></td>
			<?php
			if($receivable_data[0]['sales_order_idx']==0){
				?>
				<td colspan="3" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;"><?=$po_start_date;?> - <?=$po_end_date;?></td>
				<?php
			}else{
				?>
				<td colspan="3" align="left" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;">Sales Order:<?=$receivable_data[0]['receivable_name'];?><br/>{{ $receivable_data[0]['receivable_description'] }}</td>
				<?php
			}
			?>
			<td colspan="1" align="right" style="border-left:1px solid #afadad; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($receivable_data[0]['receivable_gross_amount'],2);?></td>			
			<td colspan="1" align="right" style="border-left:1px solid #afadad; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($receivable_data[0]['receivable_withholding_tax'],2);?></td>			
			<td colspan="1" align="right" style="border-left:1px solid #afadad; border-right:1px solid #gray; border-bottom:solid 1px gray;"><?=number_format($receivable_data[0]['receivable_amount'],2);?></td>			
		
		</tr>

		<tr>
			<td colspan="7" style="border-left:0px solid #000;border-right:0px solid #000;border-bottom:0px solid #000;">&nbsp;</td>
		</tr>
		
		</table>
		<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
		<tr>
			<td colspan="16" style="font-size:12px; border:1px solid #afadad;  background-color: #e8e8e8; font-weight:bold; height:25px !important; text-align: center;">PAYMENT</td>
		</tr>
		
		<tr style="font-size:12px;border:1 solid #afadad;">
			<td colspan="1" align="center" style="border-right:1px solid #afadad;  background-color: #e8e8e8; font-weight:bold; height:25px !important;">#</td>		
			<td colspan="2" align="center" style="border-right:1px solid #afadad;  background-color: #e8e8e8; font-weight:bold; height:25px !important;">Transaction Date</td>		
			<td colspan="1" align="center" style="border-right:1px solid #afadad;  background-color: #e8e8e8; font-weight:bold; height:25px !important;">Time</td>		
			<td colspan="3" align="center" style="border-right:1px solid #afadad;  background-color: #e8e8e8; font-weight:bold; height:25px !important;">Description</td>		
			<td colspan="3" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Mode of Payment</td>	
			<td colspan="3" nowrap align="center" style="border-right:1px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Amount</td>	
			<td colspan="3" nowrap align="center" style="border:0px solid #afadad; background-color: #e8e8e8; font-weight:bold;">Current Balance</td>
		</tr>
	
	<?php 
			$no = 1;
			$total_payment = 0;
			//$outstanding_balance = 0;
			$receivable_amount = $receivable_data[0]['receivable_amount'];
			?>
		@foreach ($receivable_payment_data as $receivable_payment_data_cols)
			<?php
				$_payment_date=date_create("$receivable_payment_data_cols->receivable_date_of_payment");
				$payment_date = strtoupper(date_format($_payment_date,"M/d/Y"));
				
				$_payment_time=date_create("$receivable_payment_data_cols->receivable_time_of_payment");
				$payment_time = strtoupper(date_format($_payment_time,"H:i"));
				
				$receivable_mode_of_payment = $receivable_payment_data_cols['receivable_mode_of_payment'];
				
				if($receivable_mode_of_payment=='Post-Dated Check'){
				
					$outstanding_balance = $receivable_amount - $total_payment - 0;
					$total_payment += 0;
					
					$post_dated_font = 'red';
					
				}else{
					
					$outstanding_balance = $receivable_amount - $total_payment - $receivable_payment_data_cols['receivable_payment_amount'];
					$total_payment += $receivable_payment_data_cols->receivable_payment_amount;
					
					$post_dated_font = 'black';
					
				}
				
			?>
		<tr style="font-size:12px;">
			
			<td colspan="1" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;"><?=$no;?></td>
			<td colspan="2" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;"><?=$payment_date;?></td>
			<td colspan="1" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;"><?=$payment_time;?></td>
			<td colspan="3" align="left" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;">{{ $receivable_payment_data_cols['receivable_reference'] }}</td>
			<td colspan="3" align="center" style="border-left:1px solid #afadad; border-bottom:solid 1px gray; padding:10px;">{{ $receivable_payment_data_cols['receivable_mode_of_payment'] }}</td>
			<td colspan="3" align="right" style="border-left:1px solid #afadad; border-right:0px solid #000; border-bottom:solid 1px gray; color:<?=$post_dated_font?>;"><?=number_format($receivable_payment_data_cols['receivable_payment_amount'],2);?></td>			
			<td colspan="3" align="right" style="border-left:1px solid #afadad; border-right:1px solid #afadad; border-bottom:solid 1px gray;"><?=number_format($outstanding_balance,2);?></td>			
		
		</tr>
			<?php
			
				
				
				$no++; 
				
			?>
			
		@endforeach
		<!--aS REQUESTED July 11 2025
		<tr style="font-size:12px;">
			
			<td colspan="5" align="right" style="border-left: 0px solid #000; border-bottom: 0px solid #000;"></td>
			<td colspan="7" align="center" style="background-color: #e8e8e8; font-weight:bold; height:25px !important; border-bottom: 0px solid #000;">REMAINING BALANCE </td>
			<td colspan="4" align="right" style="background-color: #e8e8e8; border-right: 0px solid #000;border-bottom: 1px solid #000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<//?=number_format($receivable_data[0]['receivable_amount']-$total_payment,2);?>
		
		</tr>		
		-->
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:12px;border:0px solid #000;font-style: italic;">
			<td colspan="16" align="left"style="border:0px solid #000; height:25px !important;">
			By Affixing your signature below, you hereby acknowledge to have a current pending payable as stated and do acknowledge that you received the original purchase order copy for re-checking purposes.</br>
			</td>
		</tr>
		
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="5">Prepared by:</td>
				<td align="center" colspan="5" style=""></td>
				<td align="left" colspan="6"></td>
		</tr>
		
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="5" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="5"></td>
				<td align="left" colspan="6"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="5" style=" ">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="5"></td>
				<td align="left" colspan="6"></td>
		</tr>
		
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="5">Received by:</td>
				<td align="center" colspan="5" style=""></td>
				<td align="left" colspan="6"></td>
		</tr>
		
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>
		
		<tr>
			<td colspan="16" style="height:5.66px !important;"></td>
		</tr>		
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="center" colspan="5" style="border-bottom:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="left" colspan="5"></td>
				<td align="left" colspan="6"></td>
		</tr>
		
		</table>
		
</body>
</html>