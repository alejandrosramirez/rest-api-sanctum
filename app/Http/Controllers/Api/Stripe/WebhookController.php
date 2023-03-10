<?php

namespace App\Http\Controllers\Api\Stripe;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

/**
 * @group Stripe Webhook Endpoints
 *
 * @unauthenticated
 */
class WebhookController extends CashierController
{
    /**
     * Handle stripe events.
     * <aside class='notice'>
     * For more info, check the <a href='https://stripe.com/docs/webhooks' target='_blank'>stripe official docs</a> about stripe webhooks.
     * </aside>.
     *
     * @bodyParam payload object required The stripe payload from webhook.
     * @bodyParam payload.id string required The stripe id event from webhook payload. Example: evt_2Zj5zzFU3a9abcZ1aYYYaaZ1
     * @bodyParam payload.object string required The stripe event type from webhook payload. Example: event
     * @bodyParam payload.type string required The stripe event from webhook payload. Example: payment_intent.succeeded
     */
    public function handle(Request $request): void
    {
        $event = $request->input('type');

        $payload = $request->all();

        $method = $this->eventToMethod($event);

        if (method_exists($this, $method)) {
            $this->{$method}($payload);
        }
    }

    /**
     * Handle event when payment intent is created.
     */
    public function whenPaymentIntentCreated(array $payload): void
    {
    }

    /**
     * Handle event when payment intent is processing.
     */
    public function whenPaymentIntentProcessing(array $payload): void
    {
    }

    /**
     * Handle event when payment intent requires action.
     */
    public function whenPaymentIntentRequiresAction(array $payload): void
    {
    }

    /**
     * Handle event when payment intent is canceled.
     */
    public function whenPaymentIntentCanceled(array $payload): void
    {
    }

    /**
     * Handle event when payment intent payment failed.
     */
    public function whenPaymentIntentPaymentFailed(array $payload): void
    {
    }

    /**
     * Handle event when payment intent is succeeded.
     */
    public function whenPaymentIntentSucceeded(array $payload): void
    {
    }

    /**
     * Transform an stripe event to stripe method.
     *
     * @param  string  $event
     * @return string
     */
    protected function eventToMethod($event)
    {
        return 'when'.Str::of(str_replace('.', '_', $event))->studly();
    }
}
