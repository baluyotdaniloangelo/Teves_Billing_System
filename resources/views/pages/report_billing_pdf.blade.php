<?php
//$company_header=$purchase_order_data[0]['company_header'];

if($company_header=='Teves'){
	?>
	@include('layouts.report_billing_header_teves_pdf');
	<?php
}else{
	?>
	@include('layouts.report_billing_header_gt_pdf');
	<?php
}

?>

		<tr>
			<td colspan="10"><div align="center"><h5>{{ $title }}</h5></div></td>
		</tr>
		<tr style="font-size:12px;">
			<td colspan="1" align="left">Client:</td>
			<td colspan="5" align="left" style="border-bottom:1px solid #000;">{{ $client_data['client_name'] }}</td>			
			<td colspan="1" nowrap align="right">P.O Period:</td>
			<?php
			$_po_start_date=date_create("$start_date");
			$po_start_date = strtoupper(date_format($_po_start_date,"M/d/Y"));
			
			$_po_end_date=date_create("$end_date");
			$po_end_date = strtoupper(date_format($_po_end_date,"M/d/Y"));
			?>
			<td colspan="3" nowrap align="left" style="border-bottom:1px solid #000;">&nbsp;<?=$po_start_date;?> - <?=$po_end_date;?></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="1" align="left">Address:</td>
			<td colspan="5" align="left" style="border-bottom:1px solid #000;">{{ $client_data['client_address'] }}</td>			
			<td colspan="1" align="right">Billing Date:</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000;">&nbsp;<?php echo strtoupper(date('M/d/Y')); ?></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		
		
			<tr>
				<th class="data_th" style="border:1px solid #000;">#</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Date</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Time</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Driver's Name</th>
				<th class="data_th" nowrap style="border:1px solid #000;">P.O No.</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Plate Number</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Product</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Quantity</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Price</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Amount</th>
			</tr>
			
											
		<tbody>
			<?php 
			$no = 1;
			$total_payable = 0;
			$total_liters = 0;
			?>
			@foreach ($billing_data as $billing_data_cols)
			<?php
			$_order_date=date_create("$billing_data_cols->order_date");
			$order_date = strtoupper(date_format($_order_date,"M/d/Y"));
			?>
			<tr class="data_tr" >
				<td align="center" nowrap style="border:1px solid #000;"><?=$no;?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=$order_date;?></td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_time}}</td>
				<td nowrap style="border:1px solid #000;">{{$billing_data_cols->drivers_name}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->order_po_number}}</td>
				<td align="center" nowrap style="border:1px solid #000;">{{$billing_data_cols->plate_no}}</td>
				<td nowrap style="border:1px solid #000;">{{$billing_data_cols->product_name}}</td>
				<td align="center" nowrap style="border:1px solid #000;"><?=number_format($billing_data_cols->order_quantity,2)?> {{$billing_data_cols->product_unit_measurement}}</td>
				<td align="center" nowrap style="border:1px solid #000;"><?=number_format($billing_data_cols->product_price,2);?></td>
				<td align="center" nowrap style="border:1px solid #000;"><?=number_format($billing_data_cols->order_total_amount,2);?></td>
			</tr>
			<?php 
			$no++; 
			
			if($billing_data_cols->product_unit_measurement=='L'){
				$total_liters += $billing_data_cols->order_quantity;
			}else{
				$total_liters += 0;
			}
			
			$total_payable += $billing_data_cols->order_total_amount;
			?>
			
			@endforeach
			
											
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left"><b>Total Volume:</b></td>
												<td align="left"><?=number_format($total_liters,2);?> L</td>
												<td align="left"><b>Total Due:</b></td>
												<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  <?=number_format($total_payable,2);?></td>
											</tr>
											
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"><b>Less per liter:</b></td>
												<td align="left" ><?=($less_per_liter);?> L</td>
												<td align="left" colspan="1"></td>
												<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format(($less_per_liter*$total_liters),2);?></td>
												
											</tr>
											
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"></td>
												<td align="left" ></td>
												<td align="left" colspan="1"><b>Total Payable:</b></td>
												<td align="left" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_payable-($less_per_liter*$total_liters),2);?></span></td>
											</tr>
			
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">Prepared by:</td>
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="5"></td>
			</tr>
			
			<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2"></td>
				<td align="center" colspan="3">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="5"></td>
			</tr>
			
		</tbody>
		</table>
		
</body>
</html>