<?php

namespace App\Traits;

use App\Exceptions\DefaultException;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;

trait HandleSms
{
    /**
     * Send sms.
     */
    public function sendSms(string $phone, string $message): void
    {
        $basic = new Basic(env('VONAGE_KEY'), env('VONAGE_SECRET'));
        $client = new Client($basic);

        $response = $client->sms()->send(
            new SMS('52'.$phone, env('APP_NAME'), $message)
        );

        $message = $response->current();

        if (0 != $message->getStatus()) {
            throw new DefaultException(__('SMS could not be sent'));
        }
    }
}
