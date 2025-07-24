<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Hi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hi {name : Name of the person}
     {--lastName= : Last name of the person} {--uppercase : Put the text in uppercase}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show a greeting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $lastName = $this->option('lastName');
        $uppercase = $this->option('uppercase');

        $message = "Hi {$name} {$lastName}!";

        //dd($uppercase);

        if ($uppercase) {
            $this->info(strtoupper($message));
        } else
            $this->info($message);




        //$this->info('Hi Laravel!');
    }
}
