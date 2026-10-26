<script>
$(function () {
    const quantity = value => Number(value).toLocaleString('en-PH', {maximumFractionDigits: 2});

    function cells(row) {
        const filled = row.actual_filled_quantity ?? row.actual_quantity;
        const customerBo = row.actual_bo_returned_customer_quantity;
        const deliveryBo = row.actual_bo_during_delivery_quantity;
        return [filled, customerBo, deliveryBo].map(value =>
            '<td class="text-end fw-semibold" title="Beginning Inventory plus Add minus Less">' + (value == null ? '—' : quantity(Number(value))) + '</td>'
        ).join('');
    }

    window.LpgActualInventoryUI = {cells};
});
</script>
