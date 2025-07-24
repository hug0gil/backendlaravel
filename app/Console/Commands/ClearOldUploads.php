<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearOldUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'maintenance:clear-old-uploads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old archives, for maintenance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = public_path('tempfiles');

        if (!File::exists($folderPath)) {
            $this->error('Folder not found: ' . $folderPath);
            return Command::FAILURE;
        }

        $files = File::allFiles($folderPath);

        foreach ($files as $file) {
            File::delete($file);
            $this->info('Eliminated: ' . $file->getFilename());
        }
    }
}
