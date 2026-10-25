<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class SalesCollectionsReportMail extends Mailable
{
    public $report;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function build()
    {
        return $this->subject('Daily Sales & Collections Summary — 30-Day Trend')
            ->view('emails.sales_collections_report');
    }
}
