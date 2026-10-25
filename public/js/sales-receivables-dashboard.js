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
        const dataNode = document.getElementById('sales-receivables-data');
        const chartNode = document.getElementById('sales-receivables-chart');
        if (!dataNode || !chartNode || !root.ApexCharts) return;

        let rows = [];
        try { rows = JSON.parse(dataNode.textContent || '[]'); } catch (error) { return; }

        const labels = rows.map(row => new Date(row.date + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const money = value => '₱' + Number(value || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
        const options = {
            series: [
                { name: 'Sales Orders', data: rows.map(row => Number(row.orders || 0)) },
                { name: 'Direct Billing', data: rows.map(row => Number(row.billing || 0)) },
                { name: 'Collections', data: rows.map(row => Number(row.collections || 0)) }
            ],
            chart: { type: 'line', height: 285, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Nunito, sans-serif', animations: { enabled: false }, parentHeightOffset: 0 },
            colors: ['#e66b3e', '#3281bd', '#4e9b72'],
            stroke: { curve: 'smooth', width: 3 },
            markers: { size: 3, strokeWidth: 0, hover: { size: 5 } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#edf1e8', strokeDashArray: 4, padding: { left: 6, right: 12 } },
            xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { rotate: -35, hideOverlappingLabels: true, style: { fontSize: '9px' } } },
            yaxis: { min: 0, labels: { formatter: money, style: { fontSize: '9px' } } },
            tooltip: { y: { formatter: value => '₱' + Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) } },
            noData: { text: 'No activity for this period', style: { fontSize: '11px', color: '#82927a' } },
            legend: { show: false }
        };

        chart = new root.ApexCharts(chartNode, options);
        chart.render().catch(() => {
            chartNode.textContent = 'Chart unavailable. Summary figures remain available above.';
        });
    }

    root.SalesReceivablesDashboard = { init: initialize, destroy: destroy };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
}(window));
