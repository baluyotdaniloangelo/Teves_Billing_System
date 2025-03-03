
		<!--For Delivery Status-->
<!DOCTYPE html>

<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $title }}</title>
	
	<style>
		body {
			font-family: Calibri, Candara, Sergoe, "Sergoe UI", Optima, Arial, sans-serif;
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
		.td_colon:before{
			content:":";
			font-weight:bold;
			text-align:center;
			color:black;
			position:relative;
			left:-10px;
		}
		.page-break {
		  page-break-after: always;
		}
		
	</style>
	
</head>
<body>
    		
	<table class="" width="100%" cellspacing="0" cellpadding="1">

			<?php
				$_purchase_order_date=date_create($purchase_order_data[0]['purchase_order_date']);
				$purchase_order_date = strtoupper(date_format($_purchase_order_date,"M/d/Y"));
				
				$logo = $branch_header['branch_logo'];
			?>
			
		<tr>
			<td nowrap style="horizontal-align:top;text-align:left;" align="center" colspan="1" rowspan="4" width="10%">
			<img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:112px;">
			</td>
			<td colspan="6" width="30%" style="horizontal-align:center;text-align:left;"><b style="font-size:18px;"><?=$branch_header['branch_name'];?></b></td>
			<td colspan="3" nowrap align="center" width="60%" style="font-size:12px; background-color: skyblue; text-align:center; font-weight:bold; color:#000; border-top-left-radius:30px;border-bottom-left-radius:30px; width:50px"><b>{{ $title }} - Withdrawal Status</b></td>
		</tr>
		
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$branch_header['branch_address'];?></div>
			</td>		
			<td colspan="2" align="left" width="20%" style="font-size:12px; font-weight:bold;;"><b>CONTROL NO.</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_control_number'] }}</td>
		</tr>		
		<?php
				$_print_date=date_create(date('Y-m-d'));
				$print_date = strtoupper(date_format($_print_date,"M/d/Y"));
		?>
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;">VAT REG. TIN : <?=$branch_header['branch_tin'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>DATE GENERATED</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon"><?=$purchase_order_date;?></td>
		</tr>
		
		
		Sales Order #
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$branch_header['branch_owner'];?> - <?=$branch_header['branch_owner_title'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>SALES ORDER No.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_sales_order_number'] }}</td>
		</tr>

		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		
		
		</table>
		<br>
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="15%"><b>SUPPLIER</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_name'] }}</td>
			
		</tr>
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>TIN</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_tin'] }}</td>	
			
		</tr>		
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>ADDRESS</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_address'] }}</td>	
			
			
		</tr>
		
		
		</table>
		<BR>
		<table class="" width="50%" cellspacing="0" cellpadding="1" >
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:20px !important;  ">ORDER DETAILS</td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">		
		
			<td colspan="2" width="20%" align="center">#</td>
			<td colspan="2" width="20%" align="center">PRODUCT</td>
			<td colspan="2" width="20%" align="center">TOTAL ORDER</td>	
			<td colspan="2" width="20%" align="center">TOTAL WITHDRAWAL</td>	
			<td colspan="2" width="20%" align="center">TOTAL PENDING</td>
						
		</tr>			
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:5px !important; "></td>
		</tr>	
			<?php 
			$no_total = 1;
			
			?>
			
			@foreach ($purchase_order_delivery_total_component as $purchase_order_delivery_total_component_cols)
		
			<?php
			$total_order_quantity 		= @$purchase_order_delivery_total_component_cols->total_order_quantity;
			$total_delivered_quantity 	= @$purchase_order_delivery_total_component_cols->total_delivered_quantity;
		
			$delivery_balance = $total_order_quantity - $total_delivered_quantity;
			
			$component_height = 15;
			
			?>
			
			<tr class="data_tr" style="font-size:12px;">
				<td colspan="2" align="left"  style="height:<?=$component_height;?>px !important;border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?php echo "$no_total"; ?></td>
				<td colspan="2" align="left"  style="height:<?=$component_height;?>px !important;border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?php echo @$purchase_order_delivery_total_component_cols->product_name; ?></td>
				<td colspan="2" align="right" nowrap style="height:<?=$component_height;?>px !important;border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=number_format($total_order_quantity,2,".",",");?> {{@$purchase_order_delivery_total_component_cols->product_unit_measurement}}</td>
				<td colspan="2" align="right" nowrap style="height:<?=$component_height;?>px !important;border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=number_format($total_delivered_quantity,2,".",",");?> {{@$purchase_order_delivery_total_component_cols->product_unit_measurement}}</td>
				<td colspan="2" align="right" nowrap style="height:<?=$component_height;?>px !important;border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($delivery_balance,2,".",",");?> {{@$purchase_order_delivery_total_component_cols->product_unit_measurement}}</td>
			</tr>
			
			<?php 
			$no_total++; 
			?>
			
			@endforeach

		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:5px !important; "></td>
		</tr>
		

	  	</table>		
		
		<!--WITHDRAWAL Item-->
		<br>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		<tr style="font-size:12px;border:1 solid gray;">
			<td colspan="12" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:20px !important; ">WITHDRAWAL ITEMS</td>
		</tr>
		<tr style="font-size:12px;border:1px solid gray;">			

			<td colspan="1" width="" align="center" style="border:1px solid gray;">ITEM #</td>	
			<td colspan="1" width="" align="center" style="border:1px solid gray;">DELIVERY DATE</td>		
			<td colspan="2" width="10%" align="center" style="border:1px solid gray;">PRODUCT</td>
			<td colspan="1" width="10%" align="center" style="border:1px solid gray;">QUANTITY</td>	
			<td colspan="1" width="" align="center" style="border:1px solid gray;">INVOICE PRICE</td>	
			<td colspan="1" width="" align="center" style="border:1px solid gray;">GROSS AMOUNT</td>	
			<td colspan="2" width="10%" align="center" style="border:1px solid gray;">REFERENCE</td>
			<td colspan="2" width="" align="center" style="border:1px solid gray;">HAULER DETAILS</td>
			<td colspan="1" width="" align="center" style="border:1px solid gray;">REMARKS</td>		
						
					
		</tr>									
			
			<?php 
			$no_delivered_item = 1;
			$total_liters = 0;
			?>
			@foreach ($purchase_order_delivery_component as $purchase_order_delivery_component_cols)
			<?php

			   $component_height = 15;
			   
			   $ordered_price 						= @$purchase_order_delivery_component_cols->ordered_price;
			   $purchase_order_delivery_quantity 	= @$purchase_order_delivery_component_cols->purchase_order_delivery_quantity;

			   $withdrawal_amount = number_format($purchase_order_delivery_quantity * $ordered_price,4,".",",");

			?>
			<tr class="data_tr" style="font-size:12px;">
				<td colspan="1" align="center"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo "$no_delivered_item"; ?></td>
				<td colspan="1" align="center"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$purchase_order_delivery_component_cols->purchase_order_delivery_date; ?></td>
				<td colspan="2" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$purchase_order_delivery_component_cols->product_name; ?></td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$purchase_order_delivery_component_cols->purchase_order_delivery_quantity; ?> {{@$purchase_order_delivery_component_cols->product_unit_measurement}}</td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$ordered_price; ?></td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$withdrawal_amount; ?></td>
				<td colspan="2" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$purchase_order_delivery_component_cols->purchase_order_delivery_withdrawal_reference; ?></td>
				<td colspan="2" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?=@$purchase_order_delivery_component_cols->purchase_order_delivery_hauler_details;?></td>
				<td colspan="1" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?=@$purchase_order_delivery_component_cols->purchase_order_delivery_remarks;?></td>			
			</tr>
			<?php 
			$no_delivered_item++; 
			?>
			
			@endforeach

		
		

	  	</table>
	
	<div class="page-break"></div>
	
	<table class="" width="100%" cellspacing="0" cellpadding="1">

		<tr>
			<td nowrap style="horizontal-align:top;text-align:left;" align="center" colspan="1" rowspan="4" width="10%">
			<img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:112px;">
			</td>
			<td colspan="5" width="30%" style="horizontal-align:center;text-align:left;"><b style="font-size:18px;"><?=$branch_header['branch_name'];?></b></td>
			<td colspan="4" nowrap align="center" width="60%" style="font-size:12px; background-color: skyblue; text-align:center; font-weight:bold; color:#000; border-top-left-radius:30px;border-bottom-left-radius:30px; width:50px"><b>{{ $title }} - Payment & Withdrawal in Pesos</b></td>
		</tr>
		
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$branch_header['branch_address'];?></div>
			</td>		
			<td colspan="2" align="left" width="20%" style="font-size:12px; font-weight:bold;;"><b>CONTROL NO.</b></td>
			<td colspan="3" align="left" width="30%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_control_number'] }}</td>
		</tr>		
		<?php
				$_print_date=date_create(date('Y-m-d'));
				$print_date = strtoupper(date_format($_print_date,"M/d/Y"));
		?>
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;">VAT REG. TIN : <?=$branch_header['branch_tin'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>DATE GENERATED</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon"><?=$purchase_order_date;?></td>
		</tr>
		
		
		Sales Order #
		<tr>
			<td colspan="4"  width="40%" style="horizontal-align:center;text-align:left;">
			<div style="font-size:12px;"><?=$branch_header['branch_owner'];?> - <?=$branch_header['branch_owner_title'];?></div>
			</td>
			<td colspan="2" align="left" width="25%" style="font-size:12px; font-weight:bold;"><b>SALES ORDER No.</b></td>
			<td colspan="3" align="left" width="25%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['purchase_order_sales_order_number'] }}</td>
		</tr>

		<tr>
			<td colspan="10"  width="50%" style="horizontal-align:center;text-align:left;"></td>
		</tr>
		
		
		</table>
		<br>
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="15%"><b>SUPPLIER</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_name'] }}</td>
			
		</tr>
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>TIN</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_tin'] }}</td>	
			
		</tr>		
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>ADDRESS</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $purchase_order_data[0]['supplier_address'] }}</td>	
			
			
		</tr>
		
		
		</table>
		<BR>
		
		<!--WITHDRAWAL Item-->
		<br>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		<tr style="font-size:12px;border:1 solid gray;">
			<td colspan="7" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:20px !important; ">Payment & Withdrawal in Pesos</td>
		</tr>
		<tr style="font-size:12px;border:1px solid gray;">			

			<td colspan="1" width="" align="center" style="border:1px solid gray;">ITEM #</td>	
			<td colspan="1" width="" align="left" style="border:1px solid gray;">TRANSACTION DATE</td>		
			<td colspan="1" width="" align="left" style="border:1px solid gray;">DESCRIPTION</td>
			<td colspan="1" width="" align="left" style="border:1px solid gray;">REFERENCE</td>	
			<td colspan="1" width="" align="right" style="border:1px solid gray;">DEBIT</td>	
			<td colspan="1" width="" align="right" style="border:1px solid gray;">CREDIT</td>	
			<td colspan="1" width="" align="right" style="border:1px solid gray;">VARIANCE(Over/Short Payment)</td>
									
		</tr>									
			
			<?php 
			$no_transaction_item = 1;
			
			$total_debit_amount = 0;
			$total_credit_amount = 0;
			
			?>
			@foreach ($purchase_order_transaction_item as $purchase_order_transaction_item_cols)
			<?php
			
			   $component_height = 15;
			   
			   $source_tb 			= @$purchase_order_transaction_item_cols->source_tb;
			   $transaction_date 	= @$purchase_order_transaction_item_cols->transaction_date;
			   $item_description 	= @$purchase_order_transaction_item_cols->item_description;
			   $amount 				= @$purchase_order_transaction_item_cols->amount;

				if($source_tb=='PO'||$source_tb=='Payment'){
					$debit = $amount;
					$credit = 0;
				}else{
					$debit = 0;
					$credit = $amount;
				}
				
			   
			   $debit_amount = $debit;
			   $credit_amount = $credit;
			   
			   $total_debit_amount += $debit_amount;
			   $total_credit_amount += $credit_amount;
			   
			   $variance = $total_debit_amount - $total_credit_amount;

			?>
			<tr class="data_tr" style="font-size:12px;">
				<td colspan="1" align="center"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo "$no_transaction_item"; ?></td>
				<td colspan="1" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$transaction_date; ?></td>
				<td colspan="1" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$source_tb; ?></td>
				<td colspan="1" align="left"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo @$item_description; ?></td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo number_format($debit_amount,4,".",","); ?></td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo number_format($credit_amount,4,".",","); ?></td>
				<td colspan="1" align="right"  style="height:<?=$component_height;?>px !important;border:1px solid gray;"><?php echo number_format($variance,4,".",","); ?></td>
			</tr>
			<?php 
			$no_transaction_item++; 
			?>
			
			@endforeach

	  	</table>
		
</body>
</html>