<?php
$company_header=$purchase_order_data[0]['company_header'];

if($company_header=='Teves'){
	?>
	@include('layouts.report_portrait_teves_pdf');
	<?php
}else{
	?>
	@include('layouts.report_portrait_gt_pdf');
	<?php
}

?>
		<tr style="font-size:10px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:14px;">
			<td colspan="4" style="border:1px solid #000; background-color: #c6e0b4; text-align:center; font-weight:bold; padding:5px; height:30px !important;"> {{ $title }} </td>
			<td colspan="2"></td>
			<td colspan="2" nowrap align="left" style="color:red">CONTROL NO :</td>
			<td colspan="2"><div align="center" style="color:red">{{ $purchase_order_data[0]['purchase_order_control_number'] }}</td>
		</tr>
		<tr style="font-size:10px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:10px;">
			<td colspan="2" align="left">SUPPLIER'S NAME :</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000;">{{ $purchase_order_data[0]['supplier_name'] }}</td>			
			<td colspan="3" nowrap align="left">DATE :</td>	
				<?php
				$_purchase_order_date=date_create($purchase_order_data[0]['purchase_order_date']);
				$purchase_order_date = strtoupper(date_format($_purchase_order_date,"M/d/Y"));
				?>
			<td colspan="1" nowrap align="left" style="border-bottom:1px solid #000;"><?=$purchase_order_date;?></td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left">TIN No.:</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000;">{{ $purchase_order_data[0]['supplier_tin'] }}</td>			
			<td colspan="3" nowrap align="left">SALES ORDER NO.:</td>		
			<td colspan="1" nowrap align="left" style="border-bottom:1px solid #000;">{{ $purchase_order_data[0]['purchase_order_sales_order_number'] }}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left" rowspan='2'>ADDRESS :</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000; "  rowspan='2'>{{ $purchase_order_data[0]['supplier_address'] }}</td>			
			<td colspan="3" nowrap align="left">COLLECTION RECEIPT NO. :</td>		
			<td colspan="1" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $purchase_order_data[0]['purchase_order_collection_receipt_no'] }}</td>
		</tr>
	
		<tr style="font-size:10px;">	
			<td colspan="3" nowrap align="left">OFFICIAL RECEIPT NO.:</td>		
			<td colspan="1" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $purchase_order_data[0]['purchase_order_official_receipt_no'] }}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left"></td>
			<td colspan="4" align="left"></td>			
			<td colspan="3" nowrap align="left">DELIVERY RECEIPT NO.:</td>		
			<td colspan="1" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $purchase_order_data[0]['purchase_order_delivery_receipt_no'] }}</td>
		</tr>
	
		<tr style="font-size:10px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="10" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">PAYMENT DETAILS</td>			
		</tr>		
		 
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold; height:25px !important;">BANK</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">DATE OF PAYMENT</td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">REFERENCE NO.</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">AMOUNT</td>			
		</tr> 
		

		<?php 
			$no_p = 1;
			
			?>
			@foreach ($purchase_payment_component as $purchase_payment_component_cols)
					
			<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;height:25px !important;">{{$purchase_payment_component_cols->purchase_order_bank}}</td>
				<?php
				$_purchase_order_date_of_payment=date_create($purchase_payment_component_cols['purchase_order_date_of_payment']);
				$purchase_order_date_of_payment = strtoupper(date_format($_purchase_order_date_of_payment,"M/d/Y"));
				?>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;"><?=$purchase_order_date_of_payment;?></td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_payment_component_cols->purchase_order_reference_no}}</td>
			<td colspan="2" align="right" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">	
			<?=number_format($purchase_payment_component_cols['purchase_order_payment_amount'],2);?>
			</td>			
			</tr>
			<?php 
			$no_p++; 
			?>
			
			@endforeach

		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="5" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">DELIVERY METHOD</td>
			<td colspan="5" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4;">LOADING TERMINAL</td>
		</tr>		
		 
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="5" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; height:25px !important;">{{ $purchase_order_data[0]['purchase_order_delivery_method'] }}</td>
			<td colspan="5" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{ $purchase_order_data[0]['purchase_loading_terminal'] }}</td>
			</td>			
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">		

			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #ffe699; font-weight:bold; height:25px !important;">DESCRIPTION</td>		
			<td colspan="2" nowrap align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;    background-color: #ffe699; font-weight:bold;">QUANTITY</td>
			<td colspan="1" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #ffe699; font-weight:bold;">UNIT</td>		
			<td colspan="3" nowrap align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; background-color: #ffe699; font-weight:bold;">UNIT PRICE</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #ffe699; font-weight:bold;">AMOUNT</td>							
		</tr>									
			
			<?php 
			$no = 1;
			$total_liters = 0;
			?>
			@foreach ($purchase_order_component as $purchase_order_component_cols)
			<?php
			
			if($purchase_order_component_cols->product_unit_measurement=='L' || $purchase_order_component_cols->product_unit_measurement=='l'){
				$total_liters += $purchase_order_component_cols->order_quantity;
			}else{
				$total_liters += 0;
			}
			
			$component_height = 125 / count($purchase_order_component);
			
			?>
			<tr class="data_tr" style="font-size:10px;">
				<td colspan="2" align="center" nowrap style=" height:<?=$component_height;?>px !important; border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;">{{$purchase_order_component_cols->product_name}}</td>
				<td colspan="2" align="center" nowrap style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;"><?=number_format($purchase_order_component_cols->order_quantity,2,".",",");?></td>
				<td colspan="1" align="center" nowrap style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;">{{$purchase_order_component_cols->product_unit_measurement}}</td>
				<td colspan="3" align="right" nowrap style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;"><?=number_format($purchase_order_component_cols->product_price,2,".",",");?></td>
				<td colspan="2" align="right" nowrap style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:0px solid #000;"><?=number_format($purchase_order_component_cols->order_total_amount,2,".",",");?></td>
			</tr>
			<?php 
			$no++; 
			?>
			
			@endforeach

		<tr style="font-size:10px;">
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:25px !important;">Gross Amount </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($purchase_order_data[0]['purchase_order_gross_amount'],2);?>
		</tr>
		
		<tr style="font-size:10px;">
			
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:25px !important;">Net Amount </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($purchase_order_data[0]['purchase_order_net_amount'],2);?>
		</tr>
		
		<tr style="font-size:10px;">
			
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:25px !important;">Less 1% </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($purchase_order_data[0]['purchase_order_net_amount']*$purchase_order_data[0]['purchase_order_less_percentage']/100,2);?>
		</tr>		

		<tr style="font-size:10px;">			
			<td colspan="3" align="left" style="border-left: 1px solid #000; border-top: 1px solid #000;">Total Volume</td>
			<td colspan="2" align="right" style="border-left: 0px solid #000; border-top: 1px solid #000; font-weight:bold;"><?=number_format($total_liters,2,".",",");?></td>
			<td colspan="1" align="center" style="border-left: 0px solid #000; border-top: 1px solid #000;">Liters</td>
			<td colspan="2" align="right" style="background-color: #a9d08e; border-top: 1px solid #000; font-weight:bold;">Total Due </td>
			<td colspan="2" align="right" style="background-color: #a9d08e; border-top: 1px solid #000; border-right: 1px solid #000; border-bottom:double;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<?=number_format($purchase_order_data[0]['purchase_order_total_payable'],2);?>
		</tr>	
		
		<tr>
			<td colspan="10" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="2" align="center" style="font-style: italic; border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000; height:25px !important;">In Words</td>
			<td colspan="8" style="border:1px solid #000; text-align:left;">&nbsp;<?php echo strtoupper($amount_in_words); ?></td>
		</tr>

		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
	  	
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="10" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">HAULER DETAILS</td>			
		</tr>		
		 
		
		<tr style="font-size:10px;border:0 solid #000;">
		
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Hauler / Operator:</td>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['hauler_operator']}}</td>
			
			<td colspan="1" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Destination</td>
			<td colspan="4" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['lorry_driver']}}</td>
			
		</tr>	

		<tr style="font-size:10px;border:0 solid #000;">
		
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Lorry Driver:</td>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['plate_number']}}</td>
			
			<td colspan="1" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Address</td>
			<td colspan="4" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['contact_number']}}</td>
			
		</tr>

		<tr style="font-size:10px;border:0 solid #000;">
		
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Plate No.:</td>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['plate_number']}}</td>
			
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Date of Departure:<br>
			<div style="font-size:7px;font-style: italic;">from loading terminal</div></td>
				<?php
				if($purchase_order_data[0]['purchase_date_of_departure']==''){
					$purchase_date_of_departure = '';
				}else{
					$_purchase_date_of_departure=date_create($purchase_order_data[0]['purchase_date_of_departure']);
					$purchase_date_of_departure = strtoupper(date_format($_purchase_date_of_departure,"M/d/Y"));
				}
				?>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;"><?=$purchase_date_of_departure;?></td>
			
		</tr>

		<tr style="font-size:10px;border:0 solid #000;">
		
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Contact No.:</td>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$purchase_order_data[0]['contact_number']}}</td>
			
			<td colspan="2" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:1px solid #000;">Date of Arrival:<br>
			<div style="font-size:7px;font-style: italic;">from loading terminal</div></td>
				<?php
				
				if($purchase_order_data[0]['purchase_date_of_arrival']==''){
					$purchase_date_of_arrival = '';
				}else{
					$_purchase_date_of_arrival=date_create($purchase_order_data[0]['purchase_date_of_arrival']);
					$purchase_date_of_arrival = strtoupper(date_format($_purchase_date_of_arrival,"M/d/Y"));
				}
				?>
			<td colspan="3" align="left" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;"><?=$purchase_date_of_arrival;?></td>	
		</tr>		
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="6" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:0px solid #000; font-style: italic; height:25px !important;">Instructions;</td>
			<td colspan="4" align="center" style="border:1px solid #000; font-weight:bold;">NOTE</td>	
		</tr>
	    
		<tr style="font-size:10px;border:0 solid #000;" rowspan="3">
			<td colspan="6" align="center" style="border-top:0px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000; height:90px !important; text-align: justify;">{{$purchase_order_data[0]['purchase_order_instructions']}}</td>
			<td colspan="4" align="left" style="border-top:0px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; height:90px !important;">{{$purchase_order_data[0]['purchase_order_note']}}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
			
		<tr style="font-size:10px;">
			<td colspan="10" align="left" style="border-top:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000; border-bottom:0px solid #000; font-style: italic; height:25px !important;">&nbsp;Purchase order printed by:</td>	
				
		</tr>
		<tr style="font-size:10px;">
			<td colspan="10" align="center" style="border-bottom:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000;">&nbsp;{{$user_data->user_real_name}}<br></td>	
				
		</tr>
		</table>
		
</body>
</html>