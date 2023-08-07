<?php
$company_header=$CashiersReportData[0]['teves_branch'];

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
			<td colspan="10" style="border:0px solid #000; text-align:center; font-weight:bold; font-size:16px !important; padding:5px; color:red;"> {{ $title }} </td>
			
		</tr>
		<tr style="font-size:12px;">
			<td colspan="10">&nbsp;</td>
		</tr>
		<tr style="font-size:12px;">
			<td colspan="2" align="left">Cashier On duty:</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000;">{{ $CashiersReportData[0]['cashiers_name'] }}</td>			
			<td colspan="2" nowrap align="left">Date :</td>	
				<?php
				$_report_date=date_create($CashiersReportData[0]['report_date']);
				$report_date = strtoupper(date_format($_report_date,"M/d/Y"));
				?>
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;"><?=$report_date;?></td>
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="2" align="left">Forecourt Attendant :</td>
			<td colspan="4" align="left" style="border-bottom:1px solid #000; ">{{ $CashiersReportData[0]['forecourt_attendant'] }}</td>	
			<td colspan="2" nowrap align="left">Shift :</td>	
			<td colspan="2" nowrap align="left" style="border-bottom:1px solid #000;">{{ $CashiersReportData[0]['shift'] }}</td>			
		</tr>
	
		<tr style="font-size:12px;">
			<td colspan="10" style="height:5.66px !important;"></td>
		</tr>
		
		
		<tr style="font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="2" rowspan="2">PRODUCT </td>
		  <td style="font-size:11px; border:1px solid #000;" colspan="4">TOTALIZER READING</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">CALIBRATION</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">SALES IN LITERS</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">PUMP PRICE</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">PESO  SALES</td>
		</tr>
  
		<tr style="font-size:11px;" > 
		  <td style="font-size:11px; border:1px solid #000;" colspan="2">BEGINNING</td>
		  <td style="font-size:11px; border:1px solid #000;" colspan="2">CLOSING</td>
		</tr>
  
		</table>
		
</body>
</html>