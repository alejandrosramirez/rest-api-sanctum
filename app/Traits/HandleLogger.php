<?php

namespace App\Traits;

use Jenssegers\Agent\Agent;

trait HandleLogger
{
    /**
     * Log a message.
     *
     * @param  string  $type
     * @param  string  $logName
     * @param  string  $description
     * @param  ?mixed  $extraData
     * @return void
     */
    public function logger(string $type, string $logName, string $description, mixed $extraData = null): void
    {
        $agent = new Agent();

        $data = [
            'ip' => request()->ip(),
            'device' => $agent->device(),
            'device_browser' => $agent->browser(),
            'device_browser_version' => $agent->version($agent->browser()),
            'device_ip' => request()->ip(),
            'device_platform' => $agent->platform(),
            'device_platform_version' => $agent->version($agent->platform()),
            'type' => $type,
            'description' => $description,
        ];

        if ($extraData) {
            $data = array_merge($data, ['info' => $extraData]);
        }

        activity(strtoupper($logName).'_LOG')
            ->withProperties($data)
            ->log($description);
    }
}
