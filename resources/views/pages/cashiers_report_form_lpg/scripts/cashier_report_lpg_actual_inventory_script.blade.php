<script>
$(function () {
    const quantity = value => Number(value).toLocaleString('en-PH', {maximumFractionDigits: 2});

    function cells(row) {
        const actual = row.actual_quantity == null ? null : Number(row.actual_quantity);
        const variance = row.variance_quantity == null ? null : Number(row.variance_quantity);
        const color = variance < 0 ? 'text-danger' : (variance > 0 ? 'text-success' : '');
        return '<td class="text-end fw-semibold" title="Beginning Inventory plus Add minus Less">' + (actual === null ? '—' : quantity(actual)) + '</td>'
            + '<td class="text-end fw-semibold ' + color + '" title="Actual Ending Inventory minus Theoretical Ending Inventory">' + (variance === null ? '—' : quantity(variance)) + '</td>';
    }

    window.LpgActualInventoryUI = {cells};
});
</script>
