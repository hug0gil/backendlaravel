<?php

namespace App\ExternalService\Listeners;

use App\ExternalService\Events\DataGet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogDataGet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(DataGet $event): void
    {
        Log::info("Data getted from the external service: ", $event->data);
    }
}
