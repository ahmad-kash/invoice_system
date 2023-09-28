<?php

namespace Tests\Feature\Notification;

use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\DashboardTestCase;

class NotificationTest extends DashboardTestCase
{
    use RefreshDatabase;

    public function getPermissions(): array
    {
        return [
            'create invoice', 'edit invoice', 'delete invoice', 'restore invoice', 'force delete invoice',
        ];
    }

    /** @test */
    public function mark_notification_as_read_when_show_notification_url(): void
    {
        //make a create invoice notification
        $invoice = Invoice::factory()->create();
        $this->post(route('invoices.store'), $invoice->toArray());

        //get Notification data
        $notification = $this->user->unreadNotifications->first();

        //show notification url
        $this->get(route(
            'notifications.show',
            [
                'notification' => $notification->id,
                'url' => route('invoices.show', ['invoice' => $invoice->id]),
            ]
        ))
            ->assertRedirect(route('invoices.show', ['invoice' => $invoice->id]));

        //notification must have read_at as not date
        $this->assertNotNull($notification->refresh()->read_at);
    }

    /** @test */
    public function mark_all_user_unread_notifications_as_read(): void
    {
        //make a create invoice notification
        $invoice = Invoice::factory()->create();
        $this->post(route('invoices.store'), $invoice->toArray());
        // make update invoice notification
        $this->put(route('invoices.update', ['invoice' => $invoice->id]), ['number' => '123']);

        $this->post(route('notifications.showAll'))
            ->assertRedirect();


        foreach ($this->user->unreadNotifications as $notification) {
            $this->assertNotNull($notification->read_at);
        }
    }
}
