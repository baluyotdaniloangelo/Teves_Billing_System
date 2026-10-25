const { test } = require('node:test');
const assert = require('node:assert/strict');
const { summarize, filterRows, groupSales, sortRows, buildCsv } = require('../../public/js/fuel-dashboard.js');
const rows = [
    { _index: 0, report_date: '2026-10-23', branch_id: 1, branch_code: 'A', branch_name: 'Station Alpha', total_sales: '0.10', total_cash_sales: '0.10', non_cash_payment: '0.01', short_over: '-0.03' },
    { _index: 1, report_date: '2026-10-22', branch_id: 1, branch_code: 'A', branch_name: 'Station Alpha', total_sales: '0.20', total_cash_sales: '0.20', non_cash_payment: '0.02', short_over: '0.02' },
    { _index: 2, report_date: '2026-10-23', branch_id: 2, branch_code: 'B', branch_name: 'Station Beta', total_sales: '50.00', total_cash_sales: '48.00', non_cash_payment: '2.00', short_over: '0.00' }
];

test('decimal database strings total exactly and keep shortages signed', () => {
    assert.deepEqual(summarize(rows), { count: 3, stations: 2, over: .02, short: -.03, total_sales: 50.3, total_cash_sales: 48.3, non_cash_payment: 2.03, short_over: -.01 });
    assert.equal(summarize([]).total_sales, 0);
});
test('charts aggregate every record and dates run oldest to newest', () => {
    assert.equal(groupSales(rows, 'station')[0].value, .3);
    assert.deepEqual(groupSales(rows, 'day').map(row => [row.key, row.value]), [['2026-10-22', .2], ['2026-10-23', 50.1]]);
    assert.deepEqual(groupSales([], 'day'), []);
});
test('search includes branch names and combines with shortages only', () => {
    assert.equal(filterRows(rows, '  ALPHA ', false).length, 2);
    assert.deepEqual(filterRows(rows, 'Alpha', true), [rows[0]]);
    assert.equal(filterRows(rows, 'missing', false).length, 0);
});
test('numeric sorting works on database strings without mutating source', () => {
    assert.deepEqual(sortRows(rows, 'total_sales', -1).map(row => row._index), [2, 1, 0]);
    assert.deepEqual(rows.map(row => row._index), [0, 1, 2]);
});
test('CSV quotes station text, preserves negative amounts and blocks formulas', () => {
    const csv = buildCsv([{ ...rows[0], branch_code: '=1+1', branch_name: 'A, "quoted" station' }]);
    assert.ok(csv.startsWith('\uFEFF'));
    assert.ok(csv.includes('"\'=1+1"'));
    assert.ok(csv.includes('"A, ""quoted"" station"'));
    assert.ok(csv.endsWith('"-0.03"'));
    assert.equal(csv.split('\r\n').length, 2);
});
