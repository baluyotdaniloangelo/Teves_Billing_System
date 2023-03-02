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
		}
		.data_tr {
			padding: 5px;
		}
	</style>
	
</head>
<body>
    
	<table class="" width="100%" cellspacing="0" cellpadding="1" style="table-layout:fixed;">
	
		<tr>
			<td rowspan="4" align="center" colspan="4">
			<img src="{{public_path('client_logo/logo-2.png')}}" style="width:120px;">
			</td>
			<td nowrap style="font-size:20px; font-weight:bold;" align="center" colspan="3">G-T PETROLEUM PRODUCTS RETAILING</td>
		</tr>
		
		<tr>
			<td nowrap style="font-size:10px;" align="center" colspan="3">San Juan, Madrid Surigao del Sur</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;" nowrap align="center" colspan="3">VAT REG. TIN : 740-213-285-000</td>
		</tr>
		
		<tr>
			<td style="font-size:10px;" nowrap align="center" colspan="3">GLEZA F. TEVES - Proprietress</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:14px;">
			<td colspan="4" style="border:1px solid #000; background-color: #c6e0b4; text-align:center; font-weight:bold; !important; padding:5px; height:30px !important;"> {{ $title }} </td>
			<td colspan="2"></td>
			<td colspan="2" nowrap align="left" style="color:red">CONTROL NO :</td>
			<td colspan="2"><div align="center" style="color:red">{{ $sales_order_data[0]['sales_order_control_number'] }}</td>
		</tr>
		<tr style="font-size:10px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:10px;">
			<td colspan="2" align="left">SOLD TO :</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000;">{{ $sales_order_data[0]['client_name'] }}</td>			
			<td colspan="3" nowrap align="left">DATE PRINTED:</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;">{{ $sales_order_data[0]['sales_order_date'] }}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left">TIN No.:</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000;">{{ $sales_order_data[0]['client_tin'] }}</td>			
			<td colspan="3" nowrap align="left">D.R. NO. :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;">{{ $sales_order_data[0]['sales_order_dr_number'] }}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left">ADDRESS :</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000; ">{{ $sales_order_data[0]['client_address'] }}</td>			
			<td colspan="3" nowrap align="left">O.R No. :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $sales_order_data[0]['sales_order_or_number'] }}</td>
		</tr>
	
		<tr style="font-size:10px;">
			<td colspan="2" align="left">DELIVERED TO :</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000; ">{{ $sales_order_data[0]['sales_order_delivered_to'] }}</td>			
			<td colspan="3" nowrap align="left">PAYMENT TERM :</td>		
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000; ">{{ $sales_order_data[0]['sales_order_payment_term'] }}</td>
		</tr>
		
		<tr style="font-size:10px;">
			<td colspan="2" align="left">ADDRESS :</td>
			<td colspan="3" align="left" style="border-bottom:1px solid #000; ">{{ $sales_order_data[0]['sales_order_delivered_to_address'] }}</td>			
			<td colspan="3" nowrap align="left"></td>		
			<td colspan="2" nowrap align="left" style=""></td>
		</tr>
	
		<tr style="font-size:10px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">		

			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #c6e0b4; font-weight:bold; height:25px !important;">DESCRIPTION</td>		
			<td colspan="2" nowrap align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;    background-color: #c6e0b4; font-weight:bold;">QUANTITY</td>
			<td colspan="1" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #c6e0b4; font-weight:bold;">UNIT</td>		
			<td colspan="3" nowrap align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; background-color: #c6e0b4; font-weight:bold;">UNIT PRICE</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;  background-color: #c6e0b4; font-weight:bold;">AMOUNT</td>		
					
		</tr>									
			
			<?php 
			$no = 1;
			$total_liters = 0;
			?>
			@foreach ($sales_order_component as $sales_order_component_cols)
			<?php
			
			if($sales_order_component_cols->product_unit_measurement=='L' || $sales_order_component_cols->product_unit_measurement=='l'){
				$total_liters += $sales_order_component_cols->order_quantity;
			}else{
				$total_liters += 0;
			}
			
			$component_height = 125 / count($sales_order_component);
			
			?>
			<tr class="data_tr" style="font-size:10px;">
				<td colspan="2" align="center" nowrap style=" height:<?=$component_height;?>px !important; border-top:0px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;">{{$sales_order_component_cols->product_name}}</td>
				<td colspan="2" align="center" nowrap style="border-top:0px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;"><?=number_format($sales_order_component_cols->order_quantity,2,".",",");?></td>
				<td colspan="1" align="center" nowrap style="border-top:0px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;">{{$sales_order_component_cols->product_unit_measurement}}</td>
				<td colspan="3" align="right" nowrap style="border-top:0px solid #000; border-left:0px solid #000; border-right:0px solid #000; border-bottom:0px solid #000;"><?=number_format($sales_order_component_cols->product_price,2,".",",");?></td>
				<td colspan="2" align="right" nowrap style="border-top:0px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:0px solid #000;"><?=number_format($sales_order_component_cols->order_total_amount,2,".",",");?></td>
			</tr>
			<?php 
			$no++; 
			?>
			
			@endforeach

		<tr style="font-size:10px;">
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:20px !important;">Gross Amount </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($sales_order_data[0]['sales_order_gross_amount'],2);?>
		</tr>
		
		<tr style="font-size:10px;">
			
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:20px !important;">Net Amount </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($sales_order_data[0]['sales_order_net_amount'],2);?>
		</tr>
		
		<tr style="font-size:10px;">
			
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:20px !important;">Less 1% </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom: 0px solid #000;">
			<?=number_format($sales_order_data[0]['sales_order_net_amount']*$sales_order_data[0]['sales_order_less_percentage']/100,2);?>
		</tr>		

		<tr style="font-size:10px;">			
			<td colspan="8" align="right" style="border-left: 1px solid #000; font-weight:bold; height:20px !important;">Total Due </td>
			<td colspan="2" align="right" style="background-color: #fff; border-right: 1px solid #000; border-bottom:double;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<?=number_format($sales_order_data[0]['sales_order_total_due'],2);?>
		</tr>		
		
		<tr>
			<td colspan="10" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;"></td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000; height:20px !important;font-style: italic;">In Words</td>
			<td colspan="8" style="border:1px solid #000; text-align:center;">&nbsp;<?php echo strtoupper($amount_in_words); ?></td>
		</tr>

		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>

		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">DELIVERY METHOD</td>
			<td colspan="4" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4;">HAULER | DRIVER</td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4;">REQUIRED DATE</td>			
		</tr>		
		 
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; height:25px !important;">{{$sales_order_data[0]->sales_order_delivery_method}}</td>
			<td colspan="4" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$sales_order_data[0]->sales_order_hauler}}</td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$sales_order_data[0]->sales_order_required_date}}</td>	
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
	  				
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="6" align="left" style="border-top:1px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:0px solid #000; font-style: italic; height:25px !important;">Instructions;</td>
			<td colspan="4" align="center" style="border:1px solid #000; font-weight:bold;">NOTE</td>	
		</tr>
	    
		<tr style="font-size:10px;border:0 solid #000;" rowspan="6">
			<td colspan="6" align="center" style="border-top:0px solid #000; border-left:1px solid #000; border-right:0px solid #000; border-bottom:1px solid #000; height:100px !important; text-align: justify;">{{$sales_order_data[0]->sales_order_instructions}}</td>
			<td colspan="4" align="left" style="border-top:0px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; height:90px !important; height:100px !important;">{{$sales_order_data[0]->sales_order_note}}</td>
		</tr>
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="10" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">PAYMENT DETAILS</td>			
		</tr>		
		 
		<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold; height:25px !important;">MODE OF PAYMENT</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">DATE OF PAYMENT</td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">REFERENCE NO.</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000; font-weight:bold;">AMOUNT</td>			
		</tr> 
		
		<?php 
			$no_p = 1;
			
			?>
			@foreach ($sales_payment_component as $sales_payment_component_cols)
					
			<tr style="font-size:10px;border:0 solid #000;">
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;height:25px !important;">{{$sales_payment_component_cols->sales_order_mode_of_payment}}</td>
			<td colspan="2" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$sales_payment_component_cols->sales_order_date_of_payment}}</td>
			<td colspan="3" align="center" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">{{$sales_payment_component_cols->sales_order_reference_no}}</td>
			<td colspan="2" align="right" style="border-top:1px solid #000; border-left:0px solid #000; border-right:1px solid #000; border-bottom:1px solid #000;">	
			<?=number_format($sales_payment_component_cols['sales_order_payment_amount'],2);?>
			</td>			
			</tr>
			<?php 
			$no_p++; 
			?>
			
			@endforeach
				
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
			
		<tr style="font-size:10px;">
			<td colspan="10" align="left" style="border-top:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000; border-bottom:0px solid #000; font-style: italic; height:25px !important;">&nbsp;Sales order printed by:</td>	
				
		</tr>
		<tr style="font-size:10px;">
			<td colspan="10" align="center" style="border-bottom:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000;">&nbsp;{{$user_data->user_real_name}}<br></td>	
				
		</tr>
		<tr style="font-size:10px;font-style: italic;">
			<td colspan="10" style="">This Sales Order is not valid for claims of taxes. Please refer to sales invoice / official receipts issued.</td>
		</tr>
		</table>
		
		
</body>
</html>