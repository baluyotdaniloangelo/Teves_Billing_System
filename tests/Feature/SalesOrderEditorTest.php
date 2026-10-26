<?php

namespace Tests\Feature;

use App\Http\Controllers\SalesOrderController;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SalesOrderEditorTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Exercise rendering without reading or writing the application's database.
        config(['database.default' => 'editor_test', 'database.connections.editor_test' => [
            'driver' => 'sqlite', 'database' => ':memory:', 'prefix' => '', 'foreign_key_constraints' => true,
        ], 'session.driver' => 'array']);
        foreach ([
            'user_tb' => ['user_id', 'user_branch_access_type'],
            'teves_client_table' => ['client_id', 'client_name', 'client_address', 'client_tin', 'deleted_at'],
            'teves_branch_table' => ['branch_id', 'branch_code', 'branch_name'],
            'teves_product_category' => ['category_id', 'category_name', 'deleted_at'],
            'teves_product_table' => ['product_id', 'category_idx', 'deleted_at'],
            'teves_receivable_payment' => ['receivable_mode_of_payment', 'deleted_at'],
            'teves_receivable_table' => ['receivable_id', 'sales_order_idx', 'control_number', 'client_idx', 'deleted_at'],
        ] as $table => $fields) {
            Schema::create($table, function (Blueprint $schema) use ($fields) {
                foreach ($fields as $field) $schema->string($field)->nullable();
            });
        }
        $order = [
            'sales_order_id' => 762, 'category_idx' => 1, 'company_header' => 1,
            'sales_order_date' => '2026-10-26', 'sales_order_client_idx' => 1,
            'sales_order_control_number' => 'TEST-SO-762', 'sales_order_dr_number' => '',
            'sales_order_or_number' => '', 'sales_order_po_number' => '', 'sales_order_charge_invoice' => '',
            'sales_order_collection_receipt' => '', 'sales_order_payment_term' => 'Net 30',
            'sales_order_delivered_to' => 'Test Client', 'sales_order_delivered_to_address' => 'Test address',
            'sales_order_delivery_method' => '', 'sales_order_total_due' => 8969,
            'sales_order_hauler' => '', 'sales_order_required_date' => '2026-10-27',
            'sales_order_instructions' => '', 'sales_order_note' => '', 'sales_order_mode_of_payment' => '',
            'sales_order_date_of_payment' => null, 'sales_order_reference_no' => '',
            'sales_order_payment_amount' => 0, 'sales_order_net_percentage' => 1.12,
            'sales_order_withholding_tax' => 1, 'sales_order_payment_type' => 'Receivable',
            'sales_order_invoice' => 1, 'sales_order_quotation' => 0,
            'sales_order_quotation_hide_volume' => 0, 'deleted_at' => null,
        ];
        Schema::create('teves_sales_order_table', function (Blueprint $schema) use ($order) {
            foreach (array_keys($order) as $field) $schema->string($field)->nullable();
        });
        DB::table('user_tb')->insert(['user_id' => 1, 'user_branch_access_type' => 'ALL']);
        DB::table('teves_client_table')->insert(['client_id'=>1, 'client_name'=>'Test Client', 'client_address'=>'Test address', 'client_tin'=>'123', 'deleted_at'=>null]);
        DB::table('teves_branch_table')->insert(['branch_id'=>1, 'branch_code'=>'TEST', 'branch_name'=>'Test branch']);
        DB::table('teves_sales_order_table')->insert($order);
        DB::table('teves_sales_order_table')->insert(array_merge($order, ['sales_order_id'=>763, 'sales_order_control_number'=>'TEST-SO-763']));
        DB::table('teves_receivable_table')->insert([
            ['receivable_id'=>20, 'sales_order_idx'=>762, 'control_number'=>'TEST-AR-20', 'client_idx'=>1, 'deleted_at'=>null],
            ['receivable_id'=>21, 'sales_order_idx'=>763, 'control_number'=>'TEST-AR-21', 'client_idx'=>1, 'deleted_at'=>null],
        ]);
        session()->put(['loginID'=>1, 'UserType'=>'SUAdmin']);
    }

    private function editorView($id = 762, $tab = 'product', $modal = true)
    {
        $request = Request::create('/sales_order_form', 'GET', [
            'sales_order_id'=>$id, 'tab'=>$tab, 'modal'=>$modal ? 1 : 0,
        ]);
        $this->app->instance('request', $request);
        return app(SalesOrderController::class)->sales_order_form($request);
    }

    private function document($html)
    {
        $document = new \DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $document->loadHTML('<?xml encoding="utf-8" ?>'.$html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        return new \DOMXPath($document);
    }

    public function test_modal_response_is_a_script_free_fragment_for_each_order()
    {
        foreach ([762, 763] as $id) {
            $view = $this->editorView($id);
            $this->assertSame('pages.sales_order_v2.editor.content', $view->name());
            $html = $view->render();
            $this->assertSame(0, preg_match('/<(?:!doctype|html|head|body|script|iframe)\b/i', $html));
            $this->assertStringNotContainsString('AccountUserform', $html);
            $this->assertStringNotContainsString('lpgAuditUnread', $html);
            $this->assertStringContainsString('data-sales-order-id="'.$id.'"', $html);
            $dom = $this->document($html);
            $this->assertSame(1, $dom->query('//*[@data-sales-order-id]')->length);
            $this->assertSame(0, $dom->query('//*[@id="main"]')->length);
        }
    }

    public function test_editor_and_create_form_have_no_duplicate_ids()
    {
        $view = $this->editorView();
        $html = $view->render().view('pages.sales_order_v2.modals.create_sales_order', $view->getData())->render();
        $dom = $this->document($html);
        $seen = [];
        foreach ($dom->query('//*[@id]') as $element) {
            $id = $element->getAttribute('id');
            $this->assertArrayNotHasKey($id, $seen, 'Duplicate ID: '.$id);
            $seen[$id] = true;
        }
        $this->assertArrayHasKey('so-editor-company_header', $seen);
        $this->assertArrayHasKey('company_header', $seen);
    }

    public function test_every_tab_targets_one_panel_and_only_requested_tab_is_active()
    {
        foreach (['sales_order_information','product','delivery','payment','receivable'] as $selected) {
            $dom = $this->document($this->editorView(762, $selected)->render());
            $tabs = $dom->query('//*[@id="so-editor-myTab"]//*[@data-bs-toggle="tab"]');
            $this->assertSame(5, $tabs->length);
            $active = 0;
            foreach ($tabs as $tab) {
                $target = ltrim($tab->getAttribute('data-bs-target'), '#');
                $panels = $dom->query('//*[@id="'.$target.'"]');
                $this->assertSame(1, $panels->length, 'Ambiguous target: '.$target);
                $isActive = preg_match('/\bactive\b/', $tab->getAttribute('class')) === 1;
                $this->assertSame($isActive ? 'true' : 'false', $tab->getAttribute('aria-selected'));
                if ($isActive) $active++;
                $this->assertSame($isActive, preg_match('/\bactive\b/', $panels->item(0)->getAttribute('class')) === 1);
            }
            $this->assertSame(1, $active);
        }
    }

    public function test_full_page_and_invalid_tab_requests_use_the_expected_views()
    {
        $this->assertSame('pages.sales_order_form', $this->editorView(762, 'product', false)->name());
        $this->assertSame('product', $this->editorView(762, 'not-a-tab')->getData()['tab']);
    }

    public function test_entry_forms_keep_submit_controls_and_validation_inside_each_form()
    {
        $dom = $this->document($this->editorView()->render());
        $forms = $dom->query('//form[@data-so-editor-form]');
        $this->assertSame(4, $forms->length);
        foreach ($forms as $form) {
            $this->assertNotSame('', $form->getAttribute('action'));
            $this->assertSame('post', $form->getAttribute('method'));
            $this->assertSame(1, $dom->query('.//button[@type="submit"]', $form)->length);
            $this->assertSame(1, $dom->query('.//*[@data-submit-status]', $form)->length);
            $this->assertSame(1, $dom->query('.//*[@data-form-alert]', $form)->length);
            foreach ($dom->query('.//*[@data-errors]', $form) as $input) {
                $errorId = $input->getAttribute('aria-describedby');
                $this->assertSame(1, $dom->query('.//*[@id="'.$errorId.'"]', $form)->length);
            }
        }
        $this->assertSame(1, $dom->query('//form[@data-so-editor-form="payment"]//input[@type="file"]')->length);
        $this->assertSame('multipart/form-data', $dom->query('//form[@data-so-editor-form="payment"]')->item(0)->getAttribute('enctype'));
    }
}
