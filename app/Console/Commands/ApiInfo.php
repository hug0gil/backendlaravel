<?php

namespace App\Console\Commands;

use App\ExternalService\ApiService;
use Illuminate\Console\Command;

class ApiInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ApiInfo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Look up an external API';

    public function __construct(protected ApiService $apiService)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $jsonString = json_encode($this->apiService->getData());
        $this->info($jsonString);
    }
}
