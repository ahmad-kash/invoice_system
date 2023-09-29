<?php

namespace Tests\Feature\Dashboard;

use App\Enums\InvoiceState;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\Section;
use App\Services\Invoice\InvoiceAttachmentService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\DashboardTestCase;

class InvoiceTest extends DashboardTestCase
{

    public function getPermissions(): array
    {
        return
            [
                'create invoice',
                'show invoice',
                'delete invoice',
                'edit invoice',
                'force delete invoice',
                'restore invoice',
            ];
    }

    /** @test */
    public function user_can_see_all_invoices(): void
    {
        $invoices = Invoice::factory(3)->create();
        $this->withoutExceptionHandling();
        $this->get(route('invoices.index'))
            ->assertOk()
            ->assertViewHas('invoices', function ($paginator) use ($invoices) {
                return $this->eloquentCollectionsAreEqual(collect($paginator->items()), $invoices);
            })
            ->assertSeeInOrder($invoices->map(fn ($invoice) => $invoice->number)->toArray());
    }

    /** @test */
    public function user_can_filter_invoices_by_number(): void
    {
        $invoices = Invoice::factory(5)->create();
        $targetedInvoice = Invoice::factory()->create();

        $this->get(route('invoices.index', ['number' => $invoices[0]->number]))
            ->assertOk()
            ->assertSee($invoices[0]->number)
            ->assertDontSee(
                $invoices->filter(fn ($invoice) => $invoice->number != $invoices[0]->number)
                    ->map(fn ($invoice) => $invoice->number)->toArray()
            );
    }

    /** @test */
    public function user_can_filter_invoices_by_section_name(): void
    {
        $invoices = Invoice::factory(5)->create();
        $newSection = Section::factory()->create();
        $targetedInvoice = Invoice::factory()->create(['section_id' => $newSection->id]);


        $this->get(route('invoices.index', ['section' => $targetedInvoice->sectionName]))
            ->assertOk()
            ->assertSee($targetedInvoice->number)
            ->assertDontSee(
                $invoices->filter(fn ($invoice) => $invoice->number != $targetedInvoice->number)
                    ->map(fn ($invoice) => $invoice->number)->toArray()
            );
    }

    /** @test */
    public function user_can_filter_invoices_by_product_name(): void
    {
        $invoices = Invoice::factory(5)->create();
        $newProduct = Product::factory()->create();
        $targetedInvoice = Invoice::factory()->create(['product_id' => $newProduct->id]);


        $this->get(route('invoices.index', ['product' => $targetedInvoice->productName]))
            ->assertOk()
            ->assertSee($targetedInvoice->number)
            ->assertDontSee(
                $invoices->filter(fn ($invoice) => $invoice->number != $targetedInvoice->number)
                    ->map(fn ($invoice) => $invoice->number)->toArray()
            );
    }

    /** @test */
    public function user_can_filter_invoices_by_state(): void
    {
        $invoices = Invoice::factory(5)->create();
        $targetedInvoice = Invoice::factory()->create(['state' => InvoiceState::paid->value]);


        $this->get(route('invoices.index', ['state' => 'paid']))
            ->assertOk()
            ->assertSee($targetedInvoice->number)
            ->assertDontSee(
                $invoices->filter(fn ($invoice) => $invoice->number != $targetedInvoice->number)
                    ->map(fn ($invoice) => $invoice->number)->toArray()
            );
    }

    /** @test */
    public function user_can_filter_invoices_by_date(): void
    {
        $invoicePaymentIsNow = Invoice::factory()->create(['payment_date' => Carbon::now()->format('Y-m-d')]);
        $invoicePaymentIsFrom10Days = Invoice::factory()->create(['payment_date' => Carbon::now()->subDays(10)->format('Y-m-d')]);
        $invoicePaymentIsFrom20Days = Invoice::factory()->create(['payment_date' => Carbon::now()->subDays(20)->format('Y-m-d')]);
        $invoicePaymentIsFrom30Days = Invoice::factory()->create(['payment_date' => Carbon::now()->subDays(30)->format('Y-m-d')]);

        $this->get(route('invoices.index', ['from' => Carbon::now()->subDays(21)->format('Y-m-d')]))
            ->assertOk()
            ->assertSee([$invoicePaymentIsNow->number, $invoicePaymentIsFrom10Days->number, $invoicePaymentIsFrom20Days->number])
            ->assertDontSee($invoicePaymentIsFrom30Days->number);


        $this->get(route('invoices.index', ['to' => Carbon::now()->subDays(21)->format('Y-m-d')]))
            ->assertOk()
            ->assertSee($invoicePaymentIsFrom30Days->number)
            ->assertDontSee([$invoicePaymentIsNow->number, $invoicePaymentIsFrom10Days->number, $invoicePaymentIsFrom20Days->number]);
    }


    /** @test */
    public function user_can_see_no_invoice_found_message_if_there_is_not_any_invoices_in_database(): void
    {
        $this->assertDatabaseEmpty(Invoice::class);

        $this->get(route('invoices.index'))
            ->assertOk()
            ->assertSee('لا يوجد فواتير');
    }

    /** @test */
    public function user_can_see_edit_invoice_page(): void
    {
        $invoice = Invoice::factory()->create();
        $this->get(route('invoices.edit', ['invoice' => $invoice->id]))
            ->assertOk()
            ->assertSee('تعديل الفاتورة')
            ->assertViewHas('sections')
            ->assertViewHas('invoice', $invoice);
    }

    /** @test */
    public function user_can_see_show_invoice_page(): void
    {
        $invoice = Invoice::factory()->create();
        $this->get(route('invoices.show', ['invoice' => $invoice->id]))
            ->assertOk()
            ->assertSee('الفاتورة')
            ->assertViewHas('invoice', $invoice);
    }

    /** @test */
    public function user_can_see_create_invoice_page(): void
    {
        $this->get(route('invoices.create'))
            ->assertOk()
            ->assertViewHas('sections')
            ->assertSee('أضافة فاتورة جديدة');
    }

    /** @test */
    public function user_can_see_no_sections_found_message(): void
    {
        $this->get(route('invoices.create'))
            ->assertOk()
            ->assertSee('رجاء قم باضافة قسم');
    }

    /** @test */
    public function user_can_see_sections_in_a_list(): void
    {
        $sections = Section::factory(2)->create();

        // in create page
        $this->get(route('invoices.create'))
            ->assertOk()
            ->assertViewHas('sections', function ($sectionsInView) use ($sections) {
                return $this->eloquentCollectionsAreEqual($sectionsInView, $sections);
            })
            ->assertSeeInOrder($sections->map(fn ($section) => $section->name)->toArray());

        // in edit page
        $invoice = Invoice::factory()->create();
        $this->get(route('invoices.edit', ['invoice' => $invoice->id]))
            ->assertOk()
            ->assertViewHas('sections', function ($sectionsInView) use ($sections) {
                return $this->eloquentCollectionsAreEqual($sectionsInView, $sections);
            })
            ->assertSeeInOrder($sections->map(fn ($section) => $section->name)->toArray());
    }

    /** @test */
    public function user_can_create_invoice(): void
    {

        $invoice = Invoice::factory()->make(['collection_amount' => 1000, 'commission_amount' => 700]);
        $this->post(route('invoices.store'), $invoice->toArray())
            ->assertRedirectToRoute('invoices.index');

        $invoice->refresh();
        $this->assertDatabaseHas('invoices', ['number' => $invoice->number]);
    }


    /** @test */
    public function user_can_edit_invoice(): void
    {
        $invoice = Invoice::factory()->create(['collection_amount' => 1000, 'commission_amount' => 700]);

        $this->withoutExceptionHandling();
        $this->put(route('invoices.update', ['invoice' => $invoice->id]), ['number' => 'test'])
            ->assertRedirectToRoute('invoices.index');

        $invoice->refresh();
        $this->assertDatabaseHas('invoices', ['number' => 'test']);
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForInvoice
     */
    public function a_user_can_not_create_invoice_with_invalid_data(string $fieldName, ...$args): void
    {
        $args = static::getFakeDataWithKey($args);
        Product::factory()->create(); //create product and section for invoice

        $this->post(route('invoices.store'), $args)
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    /**
     *  @test
     *  @dataProvider provideInvalidDataForInvoice
     */
    public function a_user_can_not_edit_invoice_with_invalid_data(string $fieldName, ...$args): void
    {
        $args = static::getFakeDataWithKey($args);
        $invoice = Invoice::factory()->create();
        $this->put(route('invoices.update', ['invoice' => $invoice->id]), $args)
            ->assertSessionHasErrors($fieldName)
            ->assertRedirect();
    }

    public static function provideInvalidDataForInvoice()
    {

        return [
            'number is null' => static::getFakeDataForValidation('number', null),
            'number is not string' => static::getFakeDataForValidation('number', 1),
            'number is not alpha_num' => static::getFakeDataForValidation('number', '@#4asdf'),

            'due_date is null' => static::getFakeDataForValidation('due_date', null),
            'due_date is not a date' => static::getFakeDataForValidation('due_date', 'test'),

            'payment_date is null' => static::getFakeDataForValidation('payment_date', null),
            'payment_date is not a date' => static::getFakeDataForValidation('payment_date', 'test'),

            'product_id is null' => static::getFakeDataForValidation('product_id', null),
            'product_id does not exists' => static::getFakeDataForValidation('product_id', 5),

            'section_id is null' => static::getFakeDataForValidation('section_id', null),
            'section_id does not exists' => static::getFakeDataForValidation('section_id', 5),

            'collection_amount is null' => static::getFakeDataForValidation('collection_amount', null),
            'collection_amount is not inSignedInt' => static::getFakeDataForValidation('collection_amount', -5),
            'collection_amount is not bigger than commission_amount' => static::getFakeDataForValidation('collection_amount', function ($data) {
                return $data['commission_amount'] - 1000;
            }),

            'commission_amount is null' => static::getFakeDataForValidation('commission_amount', null),
            'commission_amount is not inSignedInt' => static::getFakeDataForValidation('commission_amount', -5),

            'discount is null' => static::getFakeDataForValidation('discount', null),
            'discount is not inSignedInt' => static::getFakeDataForValidation('discount', -5),

            'VAT_rate is null' => static::getFakeDataForValidation('VAT_rate', null),
            'VAT_rate is not string' => static::getFakeDataForValidation('VAT_rate', 1),
            'VAT_rate does not began with %' => static::getFakeDataForValidation('VAT_rate', '1'),

            'note is not string' => static::getFakeDataForValidation('note', 1),
        ];
    }
    private static function getFakeDataForValidation($key, $value)
    {
        $faker = self::getFaker();

        $fakeData = ['number' => 't4ts', 'due_date' => $faker->date(), 'create_date' => $faker->date(), 'payment_date' => $faker->date(), 'product_id' => 1, 'section_id' => 1, 'collection_amount' => $faker->randomNumber(3), 'commission_amount' => $faker->randomNumber(2), 'discount' => $faker->randomNumber(1), 'VAT_rate' => '%' . (string)$faker->randomNumber(2), 'note' => $faker->sentence()];

        if (is_callable($value))
            $value = $value($fakeData);

        $fakeData[$key] = $value;

        return [$key] + $fakeData;
    }
    private static function getFakeDataWithKey($args)
    {
        return [
            'number' => $args[0], 'due_date' => $args[1], 'create_date' => $args[2], 'payment_date' => $args[3], 'product_id' => $args[4], 'section_id' => $args[5], 'collection_amount' => $args[6], 'commission_amount' => $args[7], 'discount' => $args[8], 'VAT_rate' => $args[9], 'note' => $args[10]
        ];
    }

    /** @test */
    public function user_can_soft_delete_invoice(): void
    {
        $invoice = Invoice::factory()->create();

        $this->delete(route('invoices.destroy', ['invoice' => $invoice->id]))
            ->assertRedirectToRoute('invoices.index');

        $this->assertDatabaseHas('invoices', ['number' => $invoice->number, 'deleted_at' => now()]);
    }

    /** @test */
    public function user_can_restore_soft_deleted_invoice(): void
    {
        $invoice = Invoice::factory()->create();
        $this->withoutExceptionHandling();

        $this->delete(route('invoices.destroy', ['invoice' => $invoice->id]));
        $this->put(route('invoices.restore', ['invoice' => $invoice->id]))
            ->assertRedirectToRoute('invoices.index');
        $this->assertDatabaseHas('invoices', ['number' => $invoice->number, 'deleted_at' => null]);
    }

    /** @test */
    public function user_can_force_delete_invoice(): void
    {
        Storage::fake();
        $invoice = Invoice::factory()->create();
        $file = UploadedFile::fake()->image('file.jpg');
        app()->make(InvoiceAttachmentService::class)->store($invoice, $file);
        $dirPath = $invoice->directory;

        $this->delete(route('invoices.forceDestroy', ['invoice' => $invoice->id]))
            ->assertRedirectToRoute('invoices.index');

        $this->assertDatabaseEmpty('invoices');
        $this->assertDatabaseEmpty('invoice_attachments');
        $this->assertDirectoryDoesNotExist($dirPath);
    }

    /** @test */
    public function user_can_force_delete_invoice_that_does_not_has_any_attachment(): void
    {
        $invoice = Invoice::factory()->create();

        $this->delete(route('invoices.forceDestroy', ['invoice' => $invoice->id]))
            ->assertRedirectToRoute('invoices.index');

        $this->assertDatabaseEmpty('invoices');
    }
}
