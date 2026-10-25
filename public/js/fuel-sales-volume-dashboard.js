(function (root) {
    'use strict';

    let chart = null;

    function destroy() {
        if (chart) {
            chart.destroy();
            chart = null;
        }
    }

    function initialize() {
        const dataNode = document.getElementById('fuel-sales-volume-data');
        const chartNode = document.getElementById('fuel-sales-volume-chart');
        if (!dataNode || !chartNode || !root.ApexCharts) return;

        let rows = [];
        try { rows = JSON.parse(dataNode.textContent || '[]'); } catch (error) { return; }
        chartNode.textContent = '';

        const labels = rows.map(row => new Date(row.date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const quantity = value => Number(value || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 }) + ' L';
        chart = new root.ApexCharts(chartNode, {
            series: [
                { name: 'Sales Order deliveries', data: rows.map(row => Number(row.sales_order || 0)) },
                { name: 'Retail', data: rows.map(row => Number(row.retail || 0)) }
            ],
            chart: { type: 'line', height: 270, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Nunito, sans-serif', animations: { enabled: false }, parentHeightOffset: 0 },
            colors: ['#e5794d', '#4f8aad'],
            stroke: { curve: 'smooth', width: 3 },
            markers: { size: 2, strokeWidth: 0, hover: { size: 5 } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#edf1e8', strokeDashArray: 4, padding: { left: 6, right: 12 } },
            xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { rotate: -35, hideOverlappingLabels: true, style: { fontSize: '9px' } } },
            yaxis: { min: 0, labels: { formatter: quantity, style: { fontSize: '9px' } } },
            tooltip: { y: { formatter: value => Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' L' } },
            noData: { text: 'No fuel volume for this period', style: { fontSize: '11px', color: '#82927a' } },
            legend: { show: false }
        });
        chart.render().catch(() => { chartNode.textContent = 'Chart unavailable. Volume totals remain available above.'; });
    }

    root.FuelSalesVolumeDashboard = { init: initialize, destroy: destroy };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
}(window));
