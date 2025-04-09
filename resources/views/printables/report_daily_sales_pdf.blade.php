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
			color:#000;
			background-color:#c6e0b4;
		}
		.data_tr {
			padding: 5px;
			font-size: 10px;
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
				<th class="data_th" style="border:1px solid #000;">#</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Date</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">1st Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">2nd Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">3rd Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">4th Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">5th Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">6th Shift</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Fuel Sales</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Other Sales</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">MSC - Total Sales</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">MSC - Discounts</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">MSC - Others</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Theoretical</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Cash</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Non-Cash</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Total Cash Sales</th>
				<th class="data_th" nowrap style="border:1px solid #000;" align="right">Short/Over</th>								
			</tr>
											
		<tbody>
			<?php 
			$no = 1;
			
			$total_fuel_sales = 0;
			$total_discount = 0;
			$total_cashout_other = 0;
			$total_other_sales = 0;
			$total_theoretical_sales = 0;	
			$total_cash_tansaction = 0;
			$total_non_cash_payment = 0;
			$total_cash_sales = 0;
			$total_sales = 0;
			$total_short_over = 0;
						
			?>
			@foreach ($result as $result_cols)
			<?php
			$_date_of_sales=date_create($result_cols['date']);
			$date_of_sales = strtoupper(date_format($_date_of_sales,"M/d/Y"));
	 
							$first_shift_total_sales 			= $result_cols['first_shift_total_sales'];
							$second_shift_total_sales 			= $result_cols['second_shift_total_sales'];
							$third_shift_total_sales 			= $result_cols['third_shift_total_sales'];
							$fourth_shift_total_sales 			= $result_cols['fourth_shift_total_sales'];
							$fifth_shift_total_sales 			= $result_cols['fifth_shift_total_sales'];
							$sixth_shift_total_sales 			= $result_cols['sixth_shift_total_sales'];
							
							$total_fuel_sales 					+= $result_cols['daily_fuel_sales'];
							$total_discount 					+= $result_cols['daily_discount'];
							$total_cashout_other 				+= $result_cols['daily_cashout_other'];
							$total_other_sales 					+= $result_cols['daily_other_sales'];
							$total_theoretical_sales 			+= $result_cols['daily_theoretical_sales'];
							$total_cash_tansaction 				+= $result_cols['daily_cash_transaction'];
							$total_non_cash_payment 			+= $result_cols['daily_non_cash_payment'];
							$total_cash_sales 					+= $result_cols['daily_total_cash_sales'];
							
							
							$total_daily_sales 					 = $first_shift_total_sales + $second_shift_total_sales + $third_shift_total_sales + $fourth_shift_total_sales + $fifth_shift_total_sales + $sixth_shift_total_sales;
							$total_sales 						+= $first_shift_total_sales + $second_shift_total_sales + $third_shift_total_sales + $fourth_shift_total_sales + $fifth_shift_total_sales + $sixth_shift_total_sales;					
							$total_short_over 					+= $result_cols['daily_short_over'];
							
			?>
			<tr class="data_tr" >
				<td align="center" nowrap style="border:1px solid #000;"><?=$no;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$date_of_sales;?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['first_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['second_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['third_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['fourth_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['fifth_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['sixth_shift_total_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_fuel_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_other_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['shift_total_sales_sum'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_discount'],2);?></td>			
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_cashout_other'],2);?></td>		
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_theoretical_sales'],2);?></td>	
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_cash_transaction'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_non_cash_payment'],2);?></td>	
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_total_cash_sales'],2);?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($result_cols['daily_short_over'],2);?></td>	
			</tr>
			<?php 
			$no++; 
			?>
			
			@endforeach

			<tr class="data_tr">
				<td align="left"></td>
				<td align="left">TOTAL</td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_fuel_sales" style="font-weight: normal;"><?=number_format(($total_fuel_sales),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_other_sales" style="font-weight: normal;"><?=number_format(($total_other_sales),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_sales" style="font-weight: normal;"><?=number_format(($total_sales),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_discount" style="font-weight: normal;"><?=number_format(($total_discount),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cashout_other" style="font-weight: normal;"><?=number_format(($total_cashout_other),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_theoretical_sales" style="font-weight: normal;"><?=number_format(($total_theoretical_sales),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cash_tansaction" style="font-weight: normal;"><?=number_format(($total_cash_tansaction),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_non_cash_payment" style="font-weight: normal;"><?=number_format(($total_non_cash_payment),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_cash_sales" style="font-weight: normal;"><?=number_format(($total_cash_sales),2);?></span></td>
				<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_short_over" style="font-weight: normal;"><?=number_format(($total_short_over),2);?></span></td>
			</tr>
			
			<tr style="font-size:12px;"><td colspan="9">&nbsp;</td></tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">Prepared by:</td>
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="4"></td>
			</tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2"></td>
				<td align="center" colspan="3">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="4"></td>
			</tr>
			<tr style="font-size:12px;"><td colspan="9
			">&nbsp;</td></tr>
			
		</tbody>
		</table>
		
</body>
</html>