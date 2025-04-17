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
				
				$billing_date = strtoupper(date("M/d/Y"));
				
				$logo = $receivable_header['branch_logo'];
			?>
		<tr>
			<td nowrap style="horizontal-align:top;text-align:left;" align="center" colspan="1" rowspan="4" width="10%">
			<img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:112px;">
			</td>
			<td colspan="6" width="30%" style="horizontal-align:center;text-align:left;"><b style="font-size:18px;"><?=$receivable_header['branch_name'];?></b></td>
			<td colspan="3" nowrap align="center" width="60%" style="font-size:12px; background-color: skyblue; text-align:center; font-weight:bold; color:#000; border-top-left-radius:30px;border-bottom-left-radius:30px; width:50px"><b>{{ $title }}</b></td>
		</tr>
		
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$receivable_header['branch_address'];?></div>
			</td>		
			<td colspan="2" align="left" width="20%" style="font-size:12px; font-weight:bold;;"><b>DATE</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon"><?=$billing_date;?></td>
		</tr>		
		<?php
				
				$_po_start_date=date_create("$start_date");
				$po_start_date = strtoupper(date_format($_po_start_date,"M/d/Y"));
				
				$_po_end_date=date_create("$end_date");
				$po_end_date = strtoupper(date_format($_po_end_date,"M/d/Y"));	
		?>
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;">VAT REG. TIN : <?=$receivable_header['branch_tin'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>PERIOD</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon"><?php echo "$po_start_date - $po_end_date"; ?></td>
		</tr>

		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$receivable_header['branch_owner'];?> - <?=$receivable_header['branch_owner_title'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style=""><b></b></td>
			<td colspan="3" align="left" width="25%" style="" class=""></td>
		</tr>
		
		<tr>
			<td colspan="5"  width="50%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="2" align="left" width="25%" style=""></td>
			<td colspan="3" align="left" width="25%" style=""></td>
		</tr>
		
		<tr>
			<td colspan="5"  width="50%" style="horizontal-align:center;text-align:left;"></td>
			<td colspan="2" align="left" width="25%" style=""></td>
			<td colspan="3" align="left" width="25%" style=""></td>
		</tr>
		

		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		

		</table>
		<br>

		
		<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
	
		
		<tr>
			<td colspan="12" style="border-left:0px solid #000;border-right:0px solid #000;border-bottom:0px solid #000;">&nbsp;</td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="12" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:10px !important; "></td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td align="center" style="border-right:1px solid skyblue;  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px;" width="2%">#</td>
			<th style="border-right:1px solid skyblue;  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px;">Date</th>
			<th style="border-right:1px solid skyblue;  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px;">Control Number</th>
			<th style="border-right:1px solid skyblue;  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px; "width="20%">Supplier</th>
			<th align="left" style="border-right:1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Sales Order #</th>
			<th align="left" style="border-right:1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Sales Invoice #</th>
			<th align="right" style="border-right:1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Total Sale</th>
			<th align="right" style="border-right:1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Withholding Tax</th>
			<th align="right" style="border-right1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Net Amount</th>
			<th align="right" style="border-right:1px solid skyblue; background-color: #c6e0b4; font-weight:bold; padding:10px;">Total Payable</th>
			<th style="border-right:1px solid skyblue;  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px;">Delivery Status</th>
			<th style="  background-color: #c6e0b4; font-weight:bold; height:25px !important; padding:10px;">Payment Status</th>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="12" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:10px !important; "></td>
		</tr>
		<?php 
			$no = 1;
			$total_gross_amount = 0;
			$total_withholding_tax = 0;
			$total_net_amount = 0;
			$total_amount_due = 0;
			?>
		@foreach ($purchase_order_data as $purchase_order_data_data_cols)
			<?php
				$_purchase_order_date=date_create("$purchase_order_data_data_cols->purchase_order_date");
				$purchase_order_date = strtoupper(date_format($_purchase_order_date,"M/d/Y"));
									
				$purchase_order_less_percentage = $purchase_order_data_data_cols['purchase_order_net_amount'] * $purchase_order_data_data_cols['purchase_order_less_percentage']/100;
				
				$total_gross_amount 	+= $purchase_order_data_data_cols['purchase_order_gross_amount'];
				$total_withholding_tax 	+= $purchase_order_less_percentage;
				$total_net_amount 		+= $purchase_order_data_data_cols['purchase_order_net_amount'];
				$total_amount_due 		+= $purchase_order_data_data_cols['purchase_order_total_payable'];
			?>
		<tr style="font-size:12px;">
			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=$no;?></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=$purchase_order_date;?></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;">{{ $purchase_order_data_data_cols['purchase_order_control_number'] }}</td>
			<td colspan="1" align="left"  style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;">{{ $purchase_order_data_data_cols['supplier_name'] }}</td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=$purchase_order_data_data_cols['purchase_order_sales_order_number'];?></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=$purchase_order_data_data_cols['purchase_order_official_receipt_no'];?></td>	
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($purchase_order_data_data_cols['purchase_order_gross_amount'],4);?></td>		

			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($purchase_order_data_data_cols['purchase_order_net_amount'],4);?></td>				
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format(($purchase_order_less_percentage),4);?></td>					
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($purchase_order_data_data_cols['purchase_order_total_payable'],4);?></td>			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=$purchase_order_data_data_cols['purchase_order_delivery_status'];?></td>			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=$purchase_order_data_data_cols['purchase_status'];?></td>			
			
		</tr>
		
			<?php
				$no++; 
			?>
			
		@endforeach
		<tr style="font-size:12px;">
			<td colspan="1" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"></td>
			<td colspan="1" align="left" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;">Total:</td>	
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_gross_amount,2);?></td>				
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_net_amount,2);?></td>			
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_withholding_tax);?></td>		
			<td colspan="1" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_amount_due,2);?></td>			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"></td>			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"></td>		
	
		</tr>
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>
		
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>	
		
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">PREPARED BY:</td>
				<td align="center" colspan="3" style=""></td>
				<td align="left" colspan="7"></td>
		</tr>
		
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>
		
		<tr>
			<td colspan="12" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="6"></td>
				<td align="left" colspan="2"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="3" style=" ">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="6"></td>
				<td align="left" colspan="2"></td>
		</tr>
		
		<tr>
			<td colspan="11" style="height:5.66px !important;"></td>
		</tr>	
	
		<tr>
			<td colspan="11" style="height:5.66px !important;"></td>
		</tr>
	
		
		</table>
		
</body>
</html>