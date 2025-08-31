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
			padding: 1px;
			font-size: 12px;
			color:#000;
			background-color:#c6e0b4;
		}
		.data_tr {
			padding: 1px;
			font-size: 13px;
		}
	</style>
	
</head>
<body>
    
	<table class="" width="100%" cellspacing="0" cellpadding="1" >
			<?php
				$logo = $receivable_header['branch_logo'];
			?>
			<tr>
			<td rowspan="4" align="right" colspan="5">
			<img src="{{public_path('client_logo/')}}<?=$logo;?>" style="width:160px;">
			</td>
			<td style="font-size:20px; font-weight:bold;" align="center" colspan="2"><?=$receivable_header['branch_name'];?></td>
		</tr>
		
		<tr>
			<td  style="font-size:12px;" align="center" colspan="2"><?=$receivable_header['branch_address'];?></td>
		</tr>
		
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="2">VAT REG. TIN : <?=$receivable_header['branch_tin'];?></td>
		</tr>
		<tr>
			<td style="font-size:12px;" nowrap align="center" colspan="2"><?=$receivable_header['branch_owner'];?> - <?=$receivable_header['branch_owner_title'];?></td>
		</tr>

		<tr>
			<td colspan="10"><div align="center"><h5>{{ $title }}</h5></div></td>
		</tr>
		<?php
			
			$_po_start_date=date_create("$start_date");
			$po_start_date = strtoupper(date_format($_po_start_date,"M/d/Y"));
			
			$_po_end_date=date_create("$end_date");
			$po_end_date = strtoupper(date_format($_po_end_date,"M/d/Y"));
			?>
		<tr style="font-size:12px;">
			<td colspan="1" align="left">PERIOD:</td>
			<td colspan="2" align="left" style="border-bottom:1px solid #000;"><?=$po_start_date;?> - <?=$po_end_date;?></td>			
			<td colspan="4" align="left"></td>
			<td colspan="1" align="left">DATE PRINTED:</td>
			<td colspan="2" align="left" style="border-bottom:1px solid #000;"><?php echo strtoupper(date('M/d/Y')); ?></td>
			
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		</table>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" >

			<tr>
				<th class="" style="border:1px solid #000;">#</th>
				<th class="data_th" nowrap style="border:1px solid #000;">DATE</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="left">DAY</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="center">TANK</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="left">PRODUCT</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">OPENING STOCK</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">STOCK DELIVERIES</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">SALES</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">BOOK STOCK</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">CLOSING STOCK</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">VARIANCE</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">CUMULATIVE VARIANCE</th>
				<th class="data_th"  style="border:1px solid #000;" align="right">CUMULATIVE SALES</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">PERCENTAGE</th>							
			</tr>
											
		<tbody>
			<?php 
			$no = 1;
			
			$cumulative_variance = 0;
			$cumulative_sales	 = 0;
						
			?>
			@foreach ($data as $result_cols)
			<?php
			
							$report_date 			= $result_cols->report_date;
							$report_day 			= $result_cols->report_day;
							
							$product_name 			= $result_cols->product_name;
							$tank_name 				= $result_cols->tank_name;
							
							$beginning_inventory 	= $result_cols->beginning_inventory;
							$sales_in_liters 		= $result_cols->sales_in_liters;
							$ugt_pumping 			= $result_cols->ugt_pumping;
							
							$delivery 					= $result_cols->delivery;
							$ending_inventory 			= $result_cols->ending_inventory;
							$book_stock 				= $result_cols->book_stock;
							$variance 					= $result_cols->variance;
							
							$cumulative_variance += $variance;
							$cumulative_sales	 += $sales_in_liters;
							
			?>
			<tr class="data_tr" >
				<td align="center" nowrap style="border:1px solid #000;"><?=$no;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$report_date;?></td>
				<td align="left" nowrap style="border:1px solid #000;"><?=$report_day;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$tank_name;?></td>
				<td align="left" nowrap style="border:1px solid #000;"><?=$product_name;?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($beginning_inventory,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($delivery,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($sales_in_liters,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($book_stock,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($ending_inventory,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($variance,2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($cumulative_variance,2);?></td>			
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($cumulative_sales,2);?></td>		
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format(0,2);?></td>		
			</tr>
			<?php 
			$no++; 
			?>
			
			@endforeach

		</tbody>
		</table>
		
</body>
</html>