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
		</table>
		<table cellspacing="0" width="100%">
		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="3" rowspan="2">PRODUCT </td>
		  <td style="font-size:11px; border:1px solid #000;" colspan="4">TOTALIZER READING</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">CALIBRATION</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">SALES IN LITERS</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">PUMP PRICE</td>
		  <td style="font-size:11px; border:1px solid #000;" rowspan="2">PESO  SALES</td>
		</tr>
  
		<tr style="text-align: center; font-size:11px;" > 
		  <td style="font-size:11px; border:1px solid #000;" colspan="2">BEGINNING</td>
		  <td style="font-size:11px; border:1px solid #000;" colspan="2">CLOSING</td>
		</tr>
		
		<!--Load Part 1-->
			<?php 
			$p1_no_premium = 1;
			$order_quantity_P1_premium_95 = 0;
			$product_price_P1_premium_95 = 0;
			$order_total_amount_P1_premium_95 = 0;
			?>
			@foreach ($data_P1_premium_95 as $data_P1_premium_95_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_premium_95_cols->product_name}}</td>
				<td nowrap style="border:1px solid #000;"><?=$p1_no_premium;?></td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_premium_95_cols->beginning_reading}}</td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_premium_95_cols->closing_reading}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_premium_95_cols->calibration}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_premium_95_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_premium_95_cols->product_price}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_premium_95_cols->order_total_amount}}</td>
			</tr>
			<?php 
			
			$order_quantity_P1_premium_95 += $data_P1_premium_95_cols->order_quantity;
			$product_price_P1_premium_95 += $data_P1_premium_95_cols->product_price;
			$order_total_amount_P1_premium_95 += $data_P1_premium_95_cols->order_total_amount;
			
			$p1_no_premium++; 
			?>
			@endforeach
		
		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000; text-align:right; " colspan="8">TOTAL </td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_quantity_P1_premium_95;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$product_price_P1_premium_95;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_total_amount_P1_premium_95;?></td>
		</tr>
		
		<tr>
		  <td colspan="11">&nbsp;</td>
		</tr>
		
			<?php 
			$p1_no_super_regular = 1;
			$order_quantity_P1_super_regular = 0;
			$product_price_P1_super_regular = 0;
			$order_total_amount_P1_super_regular = 0;
			?>
			@foreach ($data_P1_super_regular as $data_P1_super_regular_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_super_regular_cols->product_name}}</td>
				<td nowrap style="border:1px solid #000;"><?=$p1_no_super_regular;?></td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_super_regular_cols->beginning_reading}}</td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_super_regular_cols->closing_reading}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_super_regular_cols->calibration}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_super_regular_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_super_regular_cols->product_price}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_super_regular_cols->order_total_amount}}</td>
			</tr>
			<?php 
			
			$order_quantity_P1_super_regular += $data_P1_super_regular_cols->order_quantity;
			$product_price_P1_super_regular += $data_P1_super_regular_cols->product_price;
			$order_total_amount_P1_super_regular += $data_P1_super_regular_cols->order_total_amount;
			
			$p1_no_super_regular++; 
			?>
			@endforeach
		
		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000; text-align:right; " colspan="8">TOTAL </td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_quantity_P1_super_regular;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$product_price_P1_super_regular;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_total_amount_P1_super_regular;?></td>
		</tr>
		
		<tr>
		  <td colspan="11">&nbsp;</td>
		</tr>
		
			<?php 
			$p1_no_diesel = 1;
			$order_quantity_P1_diesel = 0;
			$product_price_P1_diesel = 0;
			$order_total_amount_P1_diesel = 0;
			?>
			@foreach ($data_P1_diesel as $data_P1_diesel_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_diesel_cols->product_name}}</td>
				<td nowrap style="border:1px solid #000;"><?=$p1_no_diesel;?></td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_diesel_cols->beginning_reading}}</td>
				<td nowrap style="border:1px solid #000;" colspan="2">{{$data_P1_diesel_cols->closing_reading}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_diesel_cols->calibration}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_diesel_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_diesel_cols->product_price}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P1_diesel_cols->order_total_amount}}</td>
			</tr>
			<?php 
			
			$order_quantity_P1_diesel += $data_P1_diesel_cols->order_quantity;
			$product_price_P1_diesel += $data_P1_diesel_cols->product_price;
			$order_total_amount_P1_diesel += $data_P1_diesel_cols->order_total_amount;
			
			$p1_no_diesel++; 
			?>
			@endforeach
		
		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000; text-align:right; " colspan="8">TOTAL </td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_quantity_P1_diesel;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$product_price_P1_diesel;?></td>
		  <td style="font-size:11px; border:1px solid #000;"><?=$order_total_amount_P1_diesel;?></td>
		</tr>
		
		</table>
		<br>
		<table cellspacing="0" width="100%">

        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="5">OTHER SALES (Lubricants/Car Care Products/Others)</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
          <td style="font-size:11px; border:1px solid #000;">#</td>
          <td style="font-size:11px; border:1px solid #000;">Product Description</td>
		  <td style="font-size:11px; border:1px solid #000;">Quantity</td>
		  <td style="font-size:11px; border:1px solid #000;">Unit Price</td>
          <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
            <?php
            $other_sales_no = 1;
            $other_sales_total = 0;
            ?>
            @foreach ($data_P2 as $data_P2_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$other_sales_no;?></td>
                <td nowrap style="border:1px solid #000;">{{$data_P2_cols->product_name}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P2_cols->product_price}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P2_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_P2_cols->order_total_amount}}</td>
			</tr>
            <?php
            $other_sales_no++;
            $other_sales_total += $data_P2_cols->order_total_amount;
            ?>
			@endforeach

		</table>
		<br>
		
		<table cellspacing="0" width="100%">
		
		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="4">MISCELLANEOUS ITEMS</td>
		</tr>
		
        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="4">SALES ORDER - CREDIT SALES</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;">#</td>
		  <td style="font-size:11px; border:1px solid #000;">Product</td>
		  <td style="font-size:11px; border:1px solid #000;">Liters</td>
          <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
			<?php
			$sc = 1;
			$total_sales_credit =0;
			?>
            @foreach ($data_SALES_CREDIT as $data_SALES_CREDIT_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$sc;?></td>
				<td nowrap style="border:1px solid #000;">{{$data_SALES_CREDIT_cols->product_name}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_SALES_CREDIT_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_SALES_CREDIT_cols->order_total_amount}}</td>
			</tr>
			<?php 
			$sc++; 
			$total_sales_credit += $data_SALES_CREDIT_cols->order_total_amount;
			?>
			
			@endforeach

		</table>
		<br>
		<table cellspacing="0" width="100%">
		
        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="5">DISCOUNTS ( WHOLE SALE - FUEL)</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;">#</td>
		  <td style="font-size:11px; border:1px solid #000;">Reference No.</td>
		  <td style="font-size:11px; border:1px solid #000;">Liters</td>
          <td style="font-size:11px; border:1px solid #000;">Unit Price</td>
		  <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
			
			<?php
			$dis = 1;
			$total_discount = 0;
			?>

            @foreach ($data_DISCOUNTS as $data_DISCOUNTS_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$dis;?></td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->reference_no}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->unit_price}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->order_total_amount}}</td>
			</tr>
			<?php
			$dis++; 
			$total_discount += $data_DISCOUNTS_cols->order_total_amount;
			?>
			@endforeach

		</table>
		<br>
		<table cellspacing="0" width="100%">
		
        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="4"> OTHERS Lubricants discounts / Money Cash Out / Misload</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;">#</td>
		  <td style="font-size:11px; border:1px solid #000;">Reference No.</td>
		  <td style="font-size:11px; border:1px solid #000;">Liters / Pieces</td>
		  <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
			
			<?php
			$other_msc = 1;
			$total_others_msc = 0;
			?>

            @foreach ($data_OTHERS as $data_OTHERS_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$other_msc;?></td>
				<td nowrap style="border:1px solid #000;">{{$data_OTHERS_cols->reference_no}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_OTHERS_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->unit_price}}</td>
			</tr>
			<?php
			$other_msc++; 
			$total_others_msc += $data_DISCOUNTS_cols->unit_price;
			?>
			@endforeach

		</table>

		</table>
		<br>



		<table cellspacing="0" width="100%">
		
        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="4"> OTHERS Lubricants discounts / Money Cash Out / Misload</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;">#</td>
		  <td style="font-size:11px; border:1px solid #000;">Reference No.</td>
		  <td style="font-size:11px; border:1px solid #000;">Liters / Pieces</td>
		  <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
			
			<?php
			$dis = 1;
			?>

            @foreach ($data_OTHERS as $data_OTHERS_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$dis;?></td>
				<td nowrap style="border:1px solid #000;">{{$data_OTHERS_cols->reference_no}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_OTHERS_cols->order_quantity}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_DISCOUNTS_cols->unit_price}}</td>
			</tr>
			<?php $dis++; ?>
			@endforeach

		</table>
        
		<br>
		<table cellspacing="0" width="100%">

        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		  <td style="font-size:11px; border:1px solid #000;" colspan="3">THEORETICAL SALES</td>
		</tr>

		<tr style="text-align:center; font-size:11px; border:1px solid #000; " >
          <td style="font-size:11px; border:1px solid #000;">#</td>
          <td style="font-size:11px; border:1px solid #000;">Description</td>
          <td style="font-size:11px; border:1px solid #000;">Amount</td>
		</tr>
            <?php
            $theoretical_sales_no = 1;
            $theoretical_sales_total = 0;
            ?>
            @foreach ($data_theoretical_sales as $data_theoretical_sales_cols)
			<tr class="data_tr" style="text-align: center; font-size:11px;">
				<td nowrap style="border:1px solid #000;"><?=$theoretical_sales_no;?></td>
				<td nowrap style="border:1px solid #000;">{{$data_theoretical_sales_cols->description_p4}}</td>
				<td nowrap style="border:1px solid #000;">{{$data_theoretical_sales_cols->amount_p4}}</td>
			</tr>
            <?php
            $theoretical_sales_no++;
            $theoretical_sales_total += $data_theoretical_sales_cols->amount_p4;
            ?>
			@endforeach

		</table>
		<br>
        <style>
            .final {
              width: 100%;
            }

            .summary {
              float: left;
              width: 50%;
            }

            .cash_on_hand {
              float: left;
              width: 50%;
            }
        </style>
        <?php
            $total_fuel_sales = $order_total_amount_P1_premium_95 + $order_total_amount_P1_super_regular + $order_total_amount_P1_diesel;
        ?>
        <div class = "final">
          <div class = "summary">
                		<table cellspacing="0" width="100%">		
                        <tr style="text-align:center; font-size:11px; border:1px solid #000; " >
		                  <td style="font-size:11px; border:1px solid #000;" colspan="2">SUMMARY</td>
		                </tr>

		                <tr>
		                   <td style="font-size:11px; border:1px solid #000;">FUEL SALES</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($total_fuel_sales,2,".",",");?></td>
		                </tr>

     		            <tr>
		                   <td style="font-size:11px; border:1px solid #000;">OTHER SALES</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($other_sales_total,2,".",",");?></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:left;">MISCELLANEOUS ITEMS</td>
                           <td style="font-size:11px; border:1px solid #000;"></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:right;">SALES ORDER - CREDIT SALES</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($total_sales_credit,2,".",",");?></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:right;">DISCOUNTS ( WHOLE SALE - FUEL)</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($total_discount,2,".",",");?></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:right;">OTHERS</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($total_others_msc,2,".",",");?></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:left;">THEORETICAL SALES</td>
                           <td style="font-size:11px; border:1px solid #000;"><?=number_format($theoretical_sales_total,2,".",",");?></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:left;">CASH ON HAND</td>
                           <td style="font-size:11px; border:1px solid #000;"></td>
		                </tr>

                        <tr>
		                   <td style="font-size:11px; border:1px solid #000; text-align:left;">CASH - SHORT/OVER</td>
                           <td style="font-size:11px; border:1px solid #000;"></td>
		                </tr>

		                </table>
          </div>
          <div class = "cash_on_hand">
             Column 2 content
          </div>
        </div>

</body>
</html>
