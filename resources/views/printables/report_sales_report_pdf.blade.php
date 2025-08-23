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
		
		<?php
		// Assuming $data is your array of stdClass objects
		$headers = [];

		if (!empty($data) && is_array($data)) {
			$firstItem = $data[0];
			$headers = array_keys(get_object_vars($firstItem));
		}
		
		
		// Calculate sums
		foreach ($data as $row) {
			foreach ($headers as $header) {
				if ($header !== 'Report Date' && is_numeric($row->$header)) {
					if (!isset($sums[$header])) {
						$sums[$header] = 0;
					}
					$sums[$header] += $row->$header;
				}
			}
		}
		?>
			<table class="" width="100%" cellspacing="0" cellpadding="1" border="1" >

			<thead>
				<tr>
					<th>No.</th>
					<?php foreach ($headers as $header): ?>
					<th>
						<?php
						// Keep 'Report Date' as is, transform others
						echo $header === 'Report Date' 
							? 'Report Date' 
							: htmlspecialchars(str_replace('_', '-', $header));
						?>
					</th>
					<?php endforeach; ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($data as $index => $row): ?>
					<tr>
						<td><?php echo $index + 1; ?></td>
						<?php foreach ($headers as $header): ?>
							<td style="<?php echo $header === 'Report Date' ? 'text-align: center;' : 'text-align: right;'; ?>">
							<?php
							if (isset($row->$header)) {
								$value = $row->$header;
								echo is_numeric($value)
									? number_format($value, 2)
									: htmlspecialchars($value);
							} else {
								echo '';
							}
							?>
							</td>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
				
			<!-- Summary Row -->
			<tr style="font-weight: bold; background-color: #f0f0f0;">
				<td style="text-align: right;" colspan='2'>Total</td>
				<?php foreach ($headers as $header): ?>
					<?php if ($header === 'Report Date'): ?>
						
					<?php else: ?>
						<td style="text-align: right;"><?php echo number_format($sums[$header] ?? 0, 2); ?></td>
					<?php endif; ?>
				<?php endforeach; ?>
			</tr>
				
				
				
			</tbody>
		</table>

</body>
</html>