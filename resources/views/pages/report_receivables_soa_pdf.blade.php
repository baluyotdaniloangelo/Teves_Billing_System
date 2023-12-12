<?php
$company_header=$receivable_data[0]['company_header'];

if($company_header=='Teves'){
	?>
	@include('layouts.soa_report_portrait_teves_pdf')
	<?php
}else{
	?>
	@include('layouts.soa_report_portrait_gt_pdf')
	<?php
}
?>		<br>
		<table class="" width="100%" cellspacing="0" cellpadding="1" >	
		<?php
				$_billing_date=date_create($receivable_data[0]['billing_date']);
				$billing_date = strtoupper(date_format($_billing_date,"M/d/Y"));
		?>
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="10%"><b>ACCOUNT NAME</b></td>	
			<td colspan="9" align="left" width="90%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $receivable_data[0]['client_name'] }}</td>		
		</tr>
		<?php
				$_print_date=date_create(date('Y-m-d'));
				$print_date = strtoupper(date_format($_print_date,"M/d/Y"));
		?>
		<tr style="font-size:12px;">
			<td colspan="1" align="left" width="10%"><b>TIN</b></td>	
			<td colspan="9" align="left" width="90%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $receivable_data[0]['client_tin'] }}</td>
		</tr>
		
		<tr style="font-size:12px;">		
			<td colspan="1" align="left" width="20%"><b>ADDRESS</b></td>	
			<td colspan="9" align="left" width="80%" style="font-size:12px; border-bottom:solid 1px gray;" class="td_colon">{{ $receivable_data[0]['client_address'] }}</td>			
		</tr>

		<tr style="font-size:12px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		</table>
		
		<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
				
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:10px !important; "></td>
		</tr>		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-right:1px solid gray; background-color: #c6e0b4; font-weight:bold; height:25px !important;">Billing Date</td>	
			<td colspan="4" nowrap align="center" style="border-right:1px solid gray; background-color: #c6e0b4; font-weight:bold;">Description</td>
			<td colspan="3" nowrap align="center" style="border:0px solid #000; background-color: #c6e0b4; font-weight:bold;">Amount</td>
		</tr>
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:10px !important; "></td>
		</tr>		
		<?php
			$_po_start_date=date_create($receivable_data[0]['billing_period_start']);
			$po_start_date = strtoupper(date_format($_po_start_date,"M/d/Y"));
			
			$_po_end_date=date_create($receivable_data[0]['billing_period_end']);
			$po_end_date = strtoupper(date_format($_po_end_date,"M/d/Y"));
			?>
		<tr style="font-size:12px;">
			
			<td colspan="3" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; height: 20px; padding:10px;"><?=$billing_date;?></td>
			<?php
			if($receivable_data[0]['sales_order_idx']==0){
				?>
				<td colspan="4" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=$po_start_date;?> - <?=$po_end_date;?></td>
				<?php
			}else{
				?>
				<td colspan="4" align="left" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;">{{ $receivable_data[0]['receivable_description'] }}</td>
				<?php
			}
			?>
			<td colspan="3" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($receivable_data[0]['receivable_amount'],2);?></td>			
		
		</tr>
		
		<tr style="font-size:12px;">
			
			<td colspan="3" align="right" style="border-left: 0px solid #000;"></td>
			<td colspan="3" align="center" style="background-color: #c6e0b4; font-weight:bold; height:25px !important;">TOTAL AMOUNT PAYABLE </td>
			<td colspan="4" align="right" style="background-color: #c6e0b4; border-right: 0px solid #000; border-bottom:double;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<?=number_format($receivable_data[0]['receivable_amount'],2);?>
		
		</tr>		
		
		<tr>
			<td colspan="10" style="border-left:0px solid #000;border-right:0px solid #000;border-bottom:0px solid #000;">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="10" style="font-size:12px; border:0px solid #000;  background-color: #c6e0b4; font-weight:bold; height:25px !important; text-align: center;">PAYMENT</td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="1" align="center" style="border-right:1px solid gray;  background-color: #c6e0b4; font-weight:bold; height:25px !important;">#</td>		
			<td colspan="2" align="center" style="border-right:1px solid gray;  background-color: #c6e0b4; font-weight:bold; height:25px !important;">Date of Payment</td>		
			<td colspan="2" align="center" style="border-right:1px solid gray;  background-color: #c6e0b4; font-weight:bold; height:25px !important;">Reference No.</td>		
			<td colspan="3" nowrap align="center" style="border-right:1px solid gray; background-color: #c6e0b4; font-weight:bold;">Mode of Payment</td>
			<td colspan="2" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold;">Amount</td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" nowrap align="center" style="border:0px solid gray; background-color: #c6e0b4; font-weight:bold; height:10px !important; "></td>
		</tr>
		<?php 
			$no = 1;
			$total_payment = 0;
			?>
		@foreach ($receivable_payment_data as $receivable_payment_data_cols)
			<?php
				$_paymnent_date=date_create("$receivable_payment_data_cols->receivable_date_of_payment");
				$paymnent_date = strtoupper(date_format($_paymnent_date,"M/d/Y"));
			?>
		<tr style="font-size:12px;">
			
			<td colspan="1" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=$no;?></td>
			<td colspan="2" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;"><?=$paymnent_date;?></td>
			<td colspan="2" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;">{{ $receivable_payment_data_cols['receivable_reference'] }}</td>
			<td colspan="3" align="center" style="border-left:0px solid #000; border-bottom:solid 1px gray; padding:10px;">{{ $receivable_payment_data_cols['receivable_mode_of_payment'] }}</td>
			<td colspan="2" align="right" style="border-left:0px solid #000; border-right:0px solid #000; border-bottom:solid 1px gray;"><?=number_format($receivable_payment_data_cols['receivable_payment_amount'],2);?></td>			
		
		</tr>
			<?php
				$no++; 
				$total_payment += $receivable_payment_data_cols->receivable_payment_amount;
			?>
			
		@endforeach
		
		<tr style="font-size:12px;">
			
			<td colspan="3" align="right" style="border-left: 0px solid #000; border-bottom: 0px solid #000;"></td>
			<td colspan="3" align="center" style="background-color: #c6e0b4; font-weight:bold; height:25px !important; border-bottom: 0px solid #000;">REMAINING BALANCE </td>
			<td colspan="4" align="right" style="background-color: #c6e0b4; border-right: 0px solid #000;border-bottom: 1px solid #000;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<?=number_format($receivable_data[0]['receivable_amount']-$total_payment,2);?>
		
		</tr>		
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:12px;border:0px solid #000;font-style: italic;">
			<td colspan="10" align="left"style="border:0px solid #000; height:25px !important;">
			By Affixing your signature below, you hereby acknowledge to have a current pending payable as stated and do acknowledge that you received the original purchase order copy for re-checking purposes.</br>
			</td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">Prepared by:</td>
				<td align="center" colspan="3" style=""></td>
				<td align="left" colspan="5"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">{{$user_data->user_real_name}}</td>
				<td align="left" colspan="5"></td>
				<td align="left" colspan="2"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="3" style=" ">{{$user_data->user_job_title}}</td>
				<td align="left" colspan="5"></td>
				<td align="left" colspan="2"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr class="data_tr" style="font-size:12px;">
				<td align="left" colspan="2">Received by:</td>
				<td align="center" colspan="3" style=""></td>
				<td align="left" colspan="5"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>		
		
		<tr class="data_tr" style="font-size:12px;">
				
				<td align="center" colspan="3" style="border-bottom:1px solid #000;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
				<td align="left" colspan="2"></td>
				<td align="left" colspan="5"></td>
		</tr>
		
		</table>
		
</body>
</html>