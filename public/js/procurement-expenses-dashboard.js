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
        const dataNode = document.getElementById('procurement-expenses-data');
        const chartNode = document.getElementById('procurement-expenses-chart');
        const dashboard = document.querySelector('main.pe-dashboard');
        if (!dataNode || !chartNode || !dashboard || !root.ApexCharts) return;

        let rows = [];
        try { rows = JSON.parse(dataNode.textContent || '[]'); } catch (error) { return; }

        const source = dashboard.dataset.expenseSource || 'All';
        const labels = rows.map(row => new Date(row.date + 'T12:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
        const money = value => '₱' + Number(value || 0).toLocaleString('en-PH', { maximumFractionDigits: 0 });
        const series = [];
        if (source !== 'LPG') series.push({ name: 'Fuel cash out', data: rows.map(row => Number(row.fuel || 0)) });
        if (source !== 'Fuel') {
            series.push({ name: 'LPG OPEX', data: rows.map(row => Number(row.lpg_opex || 0)) });
            series.push({ name: 'LPG Non-OPEX', data: rows.map(row => Number(row.lpg_non_opex || 0)) });
        }

        // Remove the no-script fallback before ApexCharts appends its own chart node.
        chartNode.textContent = '';
        chart = new root.ApexCharts(chartNode, {
            series: series,
            chart: { type: 'area', height: 285, toolbar: { show: false }, zoom: { enabled: false }, fontFamily: 'Nunito, sans-serif', animations: { enabled: false }, parentHeightOffset: 0 },
            colors: source === 'Fuel' ? ['#dc7045'] : (source === 'LPG' ? ['#3987bd', '#7d68b1'] : ['#dc7045', '#3987bd', '#7d68b1']),
            stroke: { curve: 'smooth', width: 2.5 },
            fill: { type: 'gradient', gradient: { shadeIntensity: 0.2, opacityFrom: 0.22, opacityTo: 0.02, stops: [0, 90, 100] } },
            markers: { size: 0, hover: { size: 4 } },
            dataLabels: { enabled: false },
            grid: { borderColor: '#edf1ed', strokeDashArray: 4, padding: { left: 7, right: 12 } },
            xaxis: { categories: labels, axisBorder: { show: false }, axisTicks: { show: false }, labels: { rotate: -35, hideOverlappingLabels: true, style: { fontSize: '9px' } } },
            yaxis: { min: 0, labels: { formatter: money, style: { fontSize: '9px' } } },
            tooltip: { y: { formatter: value => '₱' + Number(value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) } },
            noData: { text: 'No expenses for this period', style: { fontSize: '11px', color: '#82908a' } },
            legend: { show: false }
        });
        chart.render().catch(() => {
            chartNode.textContent = 'Chart unavailable. Expense totals remain available in the source breakdown.';
        });
    }

    root.ProcurementExpensesDashboard = { init: initialize, destroy: destroy };
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
}(window));
