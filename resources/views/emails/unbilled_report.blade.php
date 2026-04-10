<h2 style="font-family: Arial, sans-serif; color: #333;">
    Unbilled Client Report
</h2>

<table width="100%" style="border-collapse: collapse; font-family: Arial, sans-serif; font-size: 13px;">
    
    <thead>
        <tr style="background-color: #2c3e50; color: #ffffff;">
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Client</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Count</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: right;">Total Amount</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">First Date</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: center;">Last Date</th>
        </tr>
    </thead>

    <tbody>
        @foreach($data as $index => $row)
            <tr style="background-color: {{ $index % 2 == 0 ? '#f9f9f9' : '#ffffff' }};">
                
                <td style="padding: 8px; border: 1px solid #ddd;">
                    {{ $row->client_name }}
                </td>

                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    {{ $row->unbilled_count }}
                </td>

                <td style="padding: 8px; border: 1px solid #ddd; text-align: right; color: #27ae60; font-weight: bold;">
                    {{ number_format($row->total_unbilled_amount, 2) }}
                </td>

                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    {{ $row->first_transaction_date }}
                </td>

                <td style="padding: 8px; border: 1px solid #ddd; text-align: center;">
                    {{ $row->last_transaction_date }}
                </td>

            </tr>
        @endforeach
		
		@php
			$grandTotal = collect($data)->sum('total_unbilled_amount');
		@endphp

		<tr style="background-color: #34495e; color: #fff; font-weight: bold;">
			<td colspan="2" style="padding: 10px; border: 1px solid #ddd;">TOTAL</td>
			<td style="padding: 10px; border: 1px solid #ddd; text-align: right;">
				{{ number_format($grandTotal, 2) }}
			</td>
			<td colspan="2" style="border: 1px solid #ddd;"></td>
		</tr>
		
    </tbody>

</table>

<br>

<p style="font-family: Arial, sans-serif; font-size: 12px; color: #888;">
    This is an automated report. Please do not reply.
</p>