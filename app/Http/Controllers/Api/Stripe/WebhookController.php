<?php

namespace App\Http\Controllers\Api\Stripe;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Laravel\Cashier\Http\Controllers\WebhookController as CashierController;

/**
 * @group Stripe Webhook Endpoints
 * @unauthenticated
 */
class WebhookController extends CashierController
{
    /**
     * Handle stripe events.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function handle(Request $request)
    {
        $event = $request->input('type');

        $payload = $request->all();

        $method = $this->eventToMethod($event);

        if (method_exists($this, $method)) {
            $this->{$method}($payload);
        }
    }

    /**
     * Handle event when payment intent is created
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentCreated(array $payload)
    {
    }

    /**
     * Handle event when payment intent is processing
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentProcessing(array $payload)
    {
    }

    /**
     * Handle event when payment intent requires action
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentRequiresAction(array $payload)
    {
    }

    /**
     * Handle event when payment intent is canceled
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentCanceled(array $payload)
    {
    }

    /**
     * Handle event when payment intent payment failed
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentPaymentFailed(array $payload)
    {
    }

    /**
     * Handle event when payment intent is succeeded
     *
     * @param  array  $payload
     * @return void
     */
    public function whenPaymentIntentSucceeded(array $payload)
    {
    }

    /**
     * Transform an stripe event to stripe method
     *
     * @param  string  $event
     * @return string
     */
    protected function eventToMethod($event)
    {
        return 'when' . Str::of(str_replace('.', '_', $event))->studly();
    }
}
