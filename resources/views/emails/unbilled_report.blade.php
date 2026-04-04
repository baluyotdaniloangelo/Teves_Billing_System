<h2>Unbilled Client Report</h2>

<table border="1" cellpadding="5" cellspacing="0">
    <thead>
        <tr>
            <th>Client</th>
            <th>Count</th>
            <th>Total Amount</th>
            <th>First Date</th>
            <th>Last Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $row->client_name }}</td>
                <td>{{ $row->unbilled_count }}</td>
                <td>{{ number_format($row->total_unbilled_amount, 2) }}</td>
                <td>{{ $row->first_transaction_date }}</td>
                <td>{{ $row->last_transaction_date }}</td>
            </tr>
        @endforeach
    </tbody>
</table>