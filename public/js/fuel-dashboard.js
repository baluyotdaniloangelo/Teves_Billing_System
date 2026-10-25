(function (root) {
    'use strict';

    const moneyFields = ['total_sales', 'total_cash_sales', 'non_cash_payment', 'short_over'];
    const number = value => Number.isFinite(Number(value)) ? Number(value) : 0;
    const cents = value => Math.round(number(value) * 100);
    const money = value => (value < 0 ? '−' : '') + '₱' + Math.abs(value).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const signed = value => (value < 0 ? '−' : value > 0 ? '+' : '') + money(Math.abs(value));
    const dateKey = value => String(value || '').slice(0, 10);
    const stationKey = row => String(row.branch_id == null ? row.branch_code : row.branch_id);
    const dateLabel = value => new Date(dateKey(value) + 'T00:00:00').toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

    function summarize(rows) {
        const totals = { count: rows.length, stations: new Set(rows.map(stationKey)).size, over: 0, short: 0 };
        moneyFields.forEach(key => { totals[key] = rows.reduce((sum, row) => sum + cents(row[key]), 0) / 100; });
        totals.over = rows.reduce((sum, row) => sum + Math.max(0, cents(row.short_over)), 0) / 100;
        totals.short = rows.reduce((sum, row) => sum + Math.min(0, cents(row.short_over)), 0) / 100;
        return totals;
    }

    function filterRows(rows, query, shortagesOnly) {
        const search = String(query || '').trim().toLowerCase();
        return rows.filter(row => (!search || (String(row.branch_code || '') + ' ' + String(row.branch_name || '')).toLowerCase().includes(search)) && (!shortagesOnly || cents(row.short_over) < 0));
    }

    function groupSales(rows, mode) {
        const groups = new Map();
        rows.forEach(row => {
            const key = mode === 'day' ? dateKey(row.report_date) : stationKey(row);
            const item = groups.get(key) || { key, label: mode === 'day' ? dateLabel(row.report_date) : String(row.branch_code || row.branch_id), cents: 0 };
            item.cents += cents(row.total_sales);
            groups.set(key, item);
        });
        return Array.from(groups.values()).sort((a, b) => (mode === 'day' ? a.key.localeCompare(b.key) : a.label.localeCompare(b.label))).map(item => ({ key: item.key, label: item.label, value: item.cents / 100 }));
    }

    function sortRows(rows, key, direction) {
        return rows.slice().sort((a, b) => {
            const comparison = moneyFields.includes(key) ? cents(a[key]) - cents(b[key]) : String(a[key] || '').localeCompare(String(b[key] || ''));
            return comparison * direction || a._index - b._index;
        });
    }

    function buildCsv(rows) {
        // Prevent station names from being interpreted as spreadsheet formulas.
        const safeText = value => /^[\s]*[=+@\-\t\r\n]/.test(String(value || '')) ? "'" + value : String(value || '');
        const quote = value => '"' + String(value).replace(/"/g, '""') + '"';
        const output = [['Report date', 'Station', 'Branch name', 'Total sales (PHP)', 'Cash on hand (PHP)', 'Non-cash (PHP)', 'Short / over (PHP)']];
        rows.forEach(row => output.push([dateKey(row.report_date), safeText(row.branch_code), safeText(row.branch_name), ...moneyFields.map(key => number(row[key]).toFixed(2))]));
        return '\uFEFF' + output.map(row => row.map(quote).join(',')).join('\r\n');
    }

    if (typeof module !== 'undefined' && module.exports) module.exports = { summarize, filterRows, groupSales, sortRows, buildCsv };
    if (typeof document === 'undefined') return;

    function initialize() {
        const $ = id => document.getElementById(id);
        const source = $('fuel-dashboard-data');
        if (!source) return;
        const records = JSON.parse(source.textContent).map((row, index) => Object.assign({}, row, { _index: index }));
        const rowNodes = new Map(Array.from(document.querySelectorAll('[data-fuel-index]')).map(node => [Number(node.dataset.fuelIndex), node]));
        let filtered = [], page = 0, pageSize = 10, sortKey = 'report_date', direction = -1, chartMode = 'station', chart = null, chartTask = Promise.resolve(), toastTimer;

        function text(id, value) { $(id).textContent = value; }
        function notify(message) {
            text('fuel-status', message);
            $('fuel-status').classList.add('is-visible');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(() => $('fuel-status').classList.remove('is-visible'), 3500);
        }

        function renderTable() {
            filtered = sortRows(filtered, sortKey, direction);
            const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
            page = Math.max(0, Math.min(page, totalPages - 1));
            const matches = new Set(filtered.map(row => row._index));
            rowNodes.forEach((node, index) => { node.hidden = true; node.dataset.matches = String(matches.has(index)); });
            filtered.forEach((row, index) => {
                const node = rowNodes.get(row._index);
                if (!node) return;
                node.hidden = index < page * pageSize || index >= (page + 1) * pageSize;
                $('fuel-rows').appendChild(node);
            });
            $('fuel-empty').hidden = filtered.length > 0;
            $('fuel-totals').hidden = filtered.length === 0;
            text('fuel-showing', filtered.length ? `Showing ${page * pageSize + 1}–${Math.min((page + 1) * pageSize, filtered.length)} of ${filtered.length} records` : '0 matching records');
            text('fuel-page-label', `${page + 1} / ${totalPages}`);
            $('fuel-prev').disabled = page === 0;
            $('fuel-next').disabled = page >= totalPages - 1;
            document.querySelectorAll('[data-fuel-sort]').forEach(button => {
                const active = button.dataset.fuelSort === sortKey;
                button.closest('th').setAttribute('aria-sort', active ? direction === 1 ? 'ascending' : 'descending' : 'none');
                button.querySelector('span').textContent = active ? direction === 1 ? '↑' : '↓' : '↕';
            });
        }

        function chartOptions(groups) {
            const compact = value => Math.abs(value) >= 1000000 ? '₱' + (value / 1000000).toFixed(1) + 'm' : Math.abs(value) >= 1000 ? '₱' + (value / 1000).toLocaleString('en-PH', { maximumFractionDigits: 1 }) + 'k' : '₱' + number(value).toLocaleString('en-PH');
            return {
                series: [{ name: 'Total sales', data: groups.map(item => item.value) }],
                chart: { type: 'bar', height: 237, fontFamily: 'Nunito, sans-serif', toolbar: { show: false }, animations: { enabled: false }, parentHeightOffset: 0, foreColor: '#8c9b82' },
                colors: ['#e77b4e'],
                plotOptions: { bar: { borderRadius: 4, columnWidth: groups.length < 4 ? '28%' : '42%', dataLabels: { position: 'top' } } },
                dataLabels: { enabled: groups.length <= 10, formatter: compact, offsetY: -17, style: { fontSize: '9px', fontWeight: 700, colors: ['#617653'] } },
                grid: { borderColor: '#edf1e6', strokeDashArray: 4, padding: { left: 7, right: 10, top: 18, bottom: 0 } },
                xaxis: { categories: groups.map(item => item.label), axisBorder: { show: false }, axisTicks: { show: false }, tooltip: { enabled: false }, labels: { rotate: -35, rotateAlways: groups.length > 8, hideOverlappingLabels: true, trim: true, style: { fontSize: '9px' } } },
                yaxis: { labels: { formatter: compact, style: { fontSize: '9px' } } },
                noData: { text: 'No sales data for this selection', style: { fontSize: '12px', color: '#8b9d7e' } },
                tooltip: { custom: function ({ dataPointIndex }) {
                    const item = groups[dataPointIndex];
                    const box = document.createElement('div'), label = document.createElement('strong'), amount = document.createElement('span');
                    box.className = 'fuel-chart-tooltip'; label.textContent = item ? item.label : ''; amount.textContent = item ? money(item.value) : '';
                    box.append(label, amount);
                    return box.outerHTML;
                } }
            };
        }

        function renderChart() {
            const groups = groupSales(filtered, chartMode);
            const node = $('reportsChart');
            node.setAttribute('aria-label', 'Total sales by ' + (chartMode === 'day' ? 'day' : 'station') + ': ' + groups.map(item => item.label + ', ' + money(item.value)).join('; '));
            if (!root.ApexCharts) return;
            const options = chartOptions(groups);
            chartTask = chartTask.then(() => {
                if (!chart) { node.textContent = ''; chart = new root.ApexCharts(node, options); return chart.render(); }
                return chart.updateOptions(options, false, false);
            }).catch(() => { node.textContent = 'Chart unavailable. Sales figures remain available in the daily summary.'; });
        }

        function render() {
            filtered = filterRows(records, $('fuel-search').value, $('fuel-short-only').checked);
            const total = summarize(filtered);
            [['sales', 'total_sales'], ['cash', 'total_cash_sales'], ['noncash', 'non_cash_payment']].forEach(([id, field]) => { text('fuel-' + id, money(total[field])); text('fuel-table-' + id, money(total[field])); });
            ['fuel-variance', 'fuel-check-total', 'fuel-table-variance'].forEach(id => {
                text(id, signed(total.short_over));
                $(id).classList.toggle('fuel-negative', total.short_over < 0);
                $(id).classList.toggle('fuel-positive', total.short_over >= 0);
            });
            text('fuel-station-count', `Across ${total.stations} ${total.stations === 1 ? 'station' : 'stations'}`);
            text('fuel-noncash-share', (total.total_sales ? total.non_cash_payment / total.total_sales * 100 : 0).toFixed(2) + '% of recorded sales');
            const position = total.short_over < 0 ? 'Net short' : total.short_over > 0 ? 'Net over' : 'Balanced';
            text('fuel-variance-note', position + ' across selected reports');
            text('fuel-check-status', position + ' recorded cash position');
            text('fuel-over-total', signed(total.over)); text('fuel-short-total', signed(total.short));
            text('fuel-record-count', `${total.count} daily records${$('fuel-short-only').checked ? ' · shortages only' : ''}`);
            text('fuel-table-count', total.count); text('fuel-total-label', `TOTAL · ${total.count} REPORTS`);
            renderTable(); renderChart();
        }

        $('fuel-search').addEventListener('input', () => { page = 0; render(); });
        $('fuel-short-only').addEventListener('change', () => { page = 0; render(); });
        $('fuel-review').addEventListener('click', () => {
            $('fuel-short-only').checked = true; page = 0; render();
            $('fuel-records').scrollIntoView({ behavior: root.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth', block: 'start' });
            $('fuel-short-only').focus({ preventScroll: true });
        });
        document.querySelectorAll('[data-fuel-chart]').forEach(button => button.addEventListener('click', () => {
            chartMode = button.dataset.fuelChart;
            document.querySelectorAll('[data-fuel-chart]').forEach(item => item.setAttribute('aria-pressed', String(item === button)));
            renderChart();
        }));
        document.querySelectorAll('[data-fuel-sort]').forEach(button => button.addEventListener('click', () => {
            if (sortKey === button.dataset.fuelSort) direction *= -1;
            else { sortKey = button.dataset.fuelSort; direction = sortKey === 'branch_code' ? 1 : -1; }
            page = 0; renderTable();
        }));
        $('fuel-page-size').addEventListener('change', () => { pageSize = Number($('fuel-page-size').value); page = 0; renderTable(); });
        $('fuel-prev').addEventListener('click', () => { page--; renderTable(); });
        $('fuel-next').addEventListener('click', () => { page++; renderTable(); });
        $('fuel-start-date').addEventListener('change', () => { $('fuel-end-date').min = $('fuel-start-date').value; });
        $('fuel-print').addEventListener('click', () => root.print());
        $('fuel-export').addEventListener('click', () => {
            if (!filtered.length) { notify('No records to export. Adjust your filters.'); return; }
            const url = URL.createObjectURL(new Blob([buildCsv(filtered)], { type: 'text/csv;charset=utf-8;' }));
            const link = document.createElement('a');
            link.href = url; link.download = `FUEL-cashier-report-${$('fuel-start-date').value}-to-${$('fuel-end-date').value}.csv`;
            document.body.appendChild(link); link.click(); link.remove();
            setTimeout(() => URL.revokeObjectURL(url), 1000);
            notify(`${filtered.length} daily records exported to CSV`);
        });
        root.FuelDashboard = {
            init: initialize,
            destroy: function () {
                if (chart) {
                    chart.destroy();
                    chart = null;
                }
            }
        };
        render();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initialize);
    else initialize();
}(typeof window !== 'undefined' ? window : {}));
