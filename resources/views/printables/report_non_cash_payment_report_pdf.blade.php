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
			<td colspan="3" align="left" style="border-bottom:1px solid #000;"><?=$po_start_date;?> - <?=$po_end_date;?></td>			
			<td colspan="2" align="left"></td>
			<td colspan="2" align="right">DATE GENERATED:</td>
			<td colspan="2" align="left" style="border-bottom:1px solid #000;"><?php echo strtoupper(date('M/d/Y')); ?></td>
		</tr>
		
		
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		</table>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
			<tr>
				<th class="data_th" style="border:1px solid #000;">#</th>
				<th class="data_th" style="border:1px solid #000;">Date</th>
				<th class="data_th" style="border:1px solid #000;">Branch</th>
				<th class="data_th" style="border:1px solid #000;">Shift</th>
				<th class="data_th" style="border:1px solid #000;">Station In Charge</th>
				<th class="data_th" style="border:1px solid #000;">Payment Type</th>
				<th class="data_th" style="border:1px solid #000;">Amount</th>								
			</tr>
											
		<tbody>
			<?php 
			
				$no = 1;
				$total_total_amount = 0;
						
			?>
			
			@foreach ($data as $result_cols)
			
			<?php
		
					$_report_date			= date_create($result_cols['report_date']);
					$report_date 			= strtoupper(date_format($_report_date,"M/d/Y"));
	 
					$shift 					= $result_cols['shift'];
					$branch_initial 		= $result_cols['branch_initial']; 
					$cashiers_name 			= $result_cols['cashiers_name'];
					$forecourt_attendant 	= $result_cols['forecourt_attendant'];
					$encoder_name 			= $result_cols['encoder_name'];
					$payment_type 			= $result_cols['payment_type'];
					$total_amount 			= $result_cols['total_amount'];
					$total_total_amount 	+= $result_cols['total_amount'];
							
			?>
			<tr class="data_tr" >
				<td align="center" nowrap style="border:1px solid #000;"><?=$no;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$report_date;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$branch_initial;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$shift;?></td>
				<td align="left" nowrap style="border:1px solid #000;"><?=$cashiers_name;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$payment_type;?></td>
				<td align="right" nowrap style="border:1px solid #000;"><?=number_format($total_amount,2);?></td>	
			</tr>
			<?php 
			$no++; 
			?>
			
			@endforeach

			<tr class="data_tr" style="font-size:12px;">
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="left" ></td>
				<td align="right">TOTAL:</td>
				<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span id="total_non_cash" style="font-weight: normal;"><?=number_format($total_total_amount,2);?></span></td>
			</tr>
			
			<tr style="font-size:12px;"><td colspan="7">&nbsp;</td></tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" >Prepared by:</td>
				<td align="center" colspan="2" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="4"></td>
			</tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left"></td>
				<td align="center" colspan="2">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="4"></td>
			</tr>
			<tr style="font-size:12px;"><td colspan="7">&nbsp;</td></tr>
			
		</tbody>
		</table>
		
</body>
</html>