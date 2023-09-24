<?php
$company_header=$receivable_data[0]['company_header'];

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
		
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:12px;">
			<td colspan="4" style="border:1px solid #000; background-color: #c6e0b4; text-align:center; font-weight:bold; font-size:16px !important; padding:5px;"> {{ $title }} </td>
			<td colspan="2"></td>
			<td colspan="2" nowrap align="left" style="color:red">CONTROL NO :</td>
			<td colspan="2"><div align="left" style="color:red">{{ $receivable_data[0]['control_number'] }}</td>
		</tr>
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:12px;">
		
			<td colspan="6" align="left"><b style="padding-right:5px;">ACCOUNT NAME : </b>{{ $receivable_data[0]['client_name'] }}</td>			
			<td colspan="4" nowrap align="left"><b>BILLING DATE : </b>
				<?php
				$_billing_date=date_create($receivable_data[0]['billing_date']);
				$billing_date = strtoupper(date_format($_billing_date,"M/d/Y"));
				?>
			<?=$billing_date;?></td>
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="6" align="left"><b style="padding-right:5px;">TIN: </b>{{ $receivable_data[0]['client_tin'] }}</td>			
			<td colspan="4 nowrap align="left"><b>PAYMENT REFERENCE. : </b>{{ $receivable_data[0]['or_number'] }}</td>
		</tr>
		
		<tr style="font-size:12px;">	
			<td colspan="6 align="left"><b style="padding-right:5px;">ADDRESS : </b>{{ $receivable_data[0]['client_address'] }}</td>
			<td colspan="4" align="left"><b>PAYMENT TERM : </b>{{ $receivable_data[0]['payment_term'] }}</td>
		</tr>
		<tr style="font-size:12px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="6" align="center" style="border:1px solid #000;  background-color: #c6e0b4; font-weight:bold; height:25px !important;">DESCRIPTION</td>		
			<td colspan="4" nowrap align="center" style="border:1px solid #000;    background-color: #c6e0b4; font-weight:bold;">AMOUNT</td>		
		</tr>									
		
		<tr style="font-size:12px;">
			
			<td colspan="6" align="left" style="border-left:1px solid #000;height: 140px; padding:10px;">{{ $receivable_data[0]['receivable_description'] }}</td>
			<td colspan="4" align="center" style="border-right:1px solid #000;height: 140px;border-bottom:solid 1px;"><?=number_format($receivable_data[0]['receivable_amount'],2);?></td>			
		
		</tr>
		
		<tr style="font-size:12px;">
			
			<td colspan="3" align="right" style="border-left: 1px solid #000;"></td>
			<td colspan="3" align="center" style="background-color: #c6e0b4; font-weight:bold; height:25px !important;">TOTAL DUE </td>
			<td colspan="4" align="center" style="background-color: #c6e0b4; border-right: 1px solid #000; border-bottom:double;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span> 
			<?=number_format($receivable_data[0]['receivable_amount'],2);?>
		</tr>		

		<tr>
			<td colspan="10" style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000;">&nbsp;</td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;font-style: italic;">
			<td colspan="2" align="center" style="border:1px solid #000; height:25px !important;">In Words</td>
			<td colspan="8" style="border:1px solid #000; text-align:center;">&nbsp;{{ $amount_in_words }}</td>
		</tr>

		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		<tr style="font-size:12px;border:0px solid #000;font-style: italic;">
			<td colspan="10" align="left"style="border:1px solid #000; height:25px !important;">
			By signing below, you acknowledge to have a pending payable as stated above. And you receive the</br>
			<b>ORIGINAL ATTACHMENTS SUBJECT FOR RE-CHECKING</b>
			</td>
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>	
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" align="center" style="border:1px solid #000; font-weight:bold; background-color: #c6e0b4; height:25px !important;">MODE OF PAYMENT</td>			
		</tr>		
		 
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="5" align="center" style="border-bottom:1px solid #000; border-left: 1px solid #000; font-weight:bold; height:25px !important;">Check / Bank Deposit</td>		
			<td colspan="5" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold;">Cash</td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000; height:25px !important;">Check Name :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;">Denomination</td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;">Quantity</td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;">Amount</td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000; height:25px !important;">Check Amount :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>	
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000; height:25px !important;">Check Date :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>

		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000; height:25px !important;">Check No. :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" align="left" style="border-left:1px solid #000; height:25px !important;">Bank :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="2" nowrap align="left" style="border-left:1px solid #000; height:25px !important;">Bank Address/Branch :</td>	
			<td colspan="3" align="center" style="border-bottom:1px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="1" align="left" style="border-left:1px solid #000; height:25px !important;">&nbsp;</td>	
			<td colspan="4" align="center" style="border-bottom:0px solid #000;"></td>				
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:1px solid #000; border-right:1px solid #000;"></td>
			<td colspan="1" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>
			<td colspan="2" nowrap align="center" style="border-bottom:1px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>		
		</tr>
		
		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="5" align="left" style="border-left:1px solid #000;border-top:1px solid #000; height:25px !important;">For withheld client :</td>	
			<td colspan="3" nowrap align="center" style="border-bottom:0px solid #000; border-left:1px solid #000; border-right:1px solid #000; font-weight:bold;">TOTAL CASH</td>			
			<td colspan="2" nowrap align="center" style="border-bottom:0px solid #000; border-left:0px solid #000; border-right:1px solid #000;"></td>			
		</tr>

		<tr style="font-size:12px;border:0 solid #000;">
			<td colspan="10" align="left" style="border:1px solid #000; font-weight:bold; height:25px !important;">TOTAL PAYMENT :</td>		
		</tr>
		
		<tr>
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
	  
		<tr style="font-size:12px;">
			<td colspan="3" align="left" style="border-top:1px solid #000;border-right:1px solid #000;  border-left:1px solid #000; font-style: italic; height:25px !important;">&nbsp;Billing Prepared by:</td>	
			<td colspan="3" nowrap align="left" style="border-top:1px solid #000;border-right:1px solid #000; font-style: italic;">&nbsp;Billing Released by:</td>			
			<td colspan="4" nowrap align="left" style="border-top:1px solid #000; border-right:1px solid #000; font-style: italic;">&nbsp;Billing Received by:</td>			
		</tr>
			
		<tr>
			<td colspan="3" style="border-left:1px solid #000; border-right:1px solid #000;">&nbsp;</td>	
			<td colspan="3" style="border-right:1px solid #000;">&nbsp;</td>			
			<td colspan="4" style="border-right:1px solid #000;">&nbsp;</td>	
		</tr>
		
		<tr>
			<td colspan="3" style="border-left:1px solid #000; border-right:1px solid #000;">&nbsp;</td>	
			<td colspan="3" style="border-right:1px solid #000;">&nbsp;</td>			
			<td colspan="4" style="border-right:1px solid #000;">&nbsp;</td>	
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$user_data->user_real_name}}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>	
			<td colspan="3" align="center" style="border-left:0px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>				
			<td colspan="4" align="center" style="border-left:0px solid #000; border-right:1px solid #000; text-decoration:underline;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>	
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;">{{$user_data->user_job_title}}</td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;">Office Staff</td>			
			<td colspan="4" align="center" style="border-right:1px solid #000;">Signature over Printed Name</td>	
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;"></td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;"></td>			
			<td colspan="4" align="left" style="border-right:1px solid #000;">&nbsp;Date    :</td>	
		</tr>
		
		<tr style="font-size:12px;">
			<td colspan="3" align="center" style="border-left:1px solid #000; border-right:1px solid #000;border-bottom:1px solid #000;"></td>	
			<td colspan="3" align="center" style="border-right:1px solid #000;border-bottom:1px solid #000;"></td>			
			<td colspan="4" align="left" style="border-right:1px solid #000;border-bottom:1px solid #000;">&nbsp;Time    :</td>	
		</tr>
		
		</table>
		
</body>
</html>