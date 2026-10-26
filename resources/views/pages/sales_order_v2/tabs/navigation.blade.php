<ul class="nav nav-tabs nav-tabs-bordered" id="borderedTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="quotation-tab" data-bs-toggle="tab" data-bs-target="#bordered-quotation" type="button" role="tab" aria-controls="bordered-quotation" aria-selected="true" title="Quotation records and client decisions">Quotation <span class="badge">{{ number_format($salesOrderCounts['quotations']) }}</span></button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-salesorder-tab" data-bs-toggle="tab" data-bs-target="#bordered-salesorder-pending" type="button" role="tab" aria-controls="bordered-salesorder-pending" aria-selected="false" title="Active Sales Orders">Sales Order <span class="badge">{{ number_format($salesOrderCounts['orders']) }}</span></button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="billed-tab" data-bs-toggle="tab" data-bs-target="#bordered-billed" type="button" role="tab" aria-controls="bordered-billed" aria-selected="false" title="Completed deliveries">Delivered <span class="badge">{{ number_format($salesOrderCounts['delivered']) }}</span></button>
    </li>
</ul>
