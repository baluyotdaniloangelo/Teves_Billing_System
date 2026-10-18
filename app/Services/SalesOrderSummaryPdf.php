<?php

namespace App\Services;

class SalesOrderSummaryPdf
{
    public function branchPages(array $report, $user)
    {
        $branches = (new SalesOrderSummaryReport())->branches($user)->keyBy('branch_id');
        $orders = $report['data']->groupBy('company_header');
        $view = $report['client'] ? 'printables.report_sales_order_summary_per_client_pdf' : 'printables.report_sales_order_summary_pdf';
        $documents = [];
        $styles = '';
        foreach ($report['branch_summary'] as $summary) {
            $header = $branches->get($summary['branch_id']);
            abort_unless($header, 422, 'A selected branch is no longer available. Generate the report again.');
            $html = view($view, [
                'title' => 'Sales Order - Summary',
                'sales_order_data' => $orders->get($summary['branch_id'], collect()),
                'user_data' => $user, 'receivable_header' => $header,
                'client_data' => $report['client'],
                'start_date' => $report['filters']['start_date'], 'end_date' => $report['filters']['end_date'],
            ])->render();
            // Compose the rendered original printables into one valid HTML document.
            if (!$styles) {
                preg_match_all('/<style\b[^>]*>.*?<\/style>/is', $html, $styleBlocks);
                $styles = implode("\n", $styleBlocks[0]);
            }
            if (!preg_match('/<body\b[^>]*>(.*?)<\/body>/is', $html, $body)) {
                throw new \RuntimeException('Unable to compose the branch printable.');
            }
            $documents[] = $body[1];
        }
        return ['branch_documents' => $documents, 'printable_styles' => $styles];
    }
}
