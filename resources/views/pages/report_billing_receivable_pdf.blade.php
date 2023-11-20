<?php

if($company_header=='Teves'){
	?>
	@include('layouts.receivable_covered_report_teves_pdf');
	<?php
}else{
	?>
	@include('layouts.receivable_covered_report_gt_pdf')
	<?php
}

?>
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="15%"><b>ACCOUNT NAME</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_name'] }}</td>
			
		</tr>
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>TIN</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_tin'] }}</td>	
			
		</tr>		
		
		<tr style="font-size:12px;">
		
			<td colspan="1" align="left" width="15%"><b>ADDRESS</b></td>
			<td colspan="9" align="left" width="85%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $client_data['client_address'] }}</td>	
			
			
		</tr>
		
		
		</table>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" >
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
			<tr>
				<th class="data_th" style="border:1px solid #000;">#</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Date</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Time</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Driver's Name</th>
				<th class="data_th" nowrap style="border:1px solid #000;">S.O. No.</th>
				<th class="data_th" nowrap style="border:1px solid #000;" width="15%">Description</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Product</th>
				<th class="data_th" nowrap style="border:1px solid #000;">Quantity</th>
				<th class="data_th" nowrap style="border:1px solid #000;" width="15%">Price</th>
				<th class="data_th" nowrap style="border:1px solid #000;" width="15%">Amount</th>
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
												<td align="left">Total Volume:</td>
												<td align="left"><?=number_format($total_liters,2);?> L</td>
												<td align="left"><b>Total Sales:</b></td>
												<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>  <?=number_format($total_payable,2);?></td>
											</tr>
											<!--'withholding_tax_percentage','net_value_percentage','vat_value_percentage'-->
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1">Discount per liter:</td>
												<td align="left" ><?=($less_per_liter);?> L</td>
												<td align="left" colspan="1">VATable Sales</td>
												<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format(($total_payable/$net_value_percentage),2);?></td>
												
											</tr>
											
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"></td>
												<td align="left" ></td>
												<td align="left" colspan="1">VAT Amount</td>
												<td align="right"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <span style="text-slign:right;"><?=number_format((($total_payable/$net_value_percentage)*$vat_value_percentage),2);?></span></td>
												
											</tr>
									
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"></td>
												<td align="left" ></td>
												<td align="left" colspan="1">Less: Discount</td>
												<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_liters*$less_per_liter,2);?></td>
												
											</tr>
											
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"></td>
												<td align="left" ></td>
												<td align="left" colspan="1">Less: With Holding Tax</td>
												<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format( ( ($total_payable/$net_value_percentage)*$withholding_tax_percentage) ,2 );?></td>
												
											</tr>
									
											<tr class="data_tr">
												<td align="left" colspan="6"></td>
												<td align="left" colspan="1"></td>
												<td align="left" ></td>
												<td align="left" colspan="1"><b>TOTAL AMOUNT DUE:</b></td>
												<td align="right" ><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> <?=number_format($total_payable-($less_per_liter*$total_liters)-(($total_payable/$net_value_percentage)*$withholding_tax_percentage),2);?></span></td>
											</tr>
			
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
			<tr style="font-size:12px;"><td colspan="10">&nbsp;</td></tr>
			<tr style="font-size:12px;font-style: italic;"><td colspan="10">This billing statement is not valid for claims of taxes. Please refer to Sales Invoice issued.</td></tr>
		</tbody>
		</table>
		
</body>
</html>