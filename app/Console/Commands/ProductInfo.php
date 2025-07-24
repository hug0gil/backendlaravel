<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

use function PHPUnit\Framework\isNumeric;

class ProductInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product-info {id : Product ID to look up}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $id = $this->argument('id');

        if (!is_numeric($id) || !$id || $id <= 0) {
            $this->error("Error: ID must be a positive number");
            return Command::FAILURE;
        }

        $product = Product::find($id);

        if (!$product) {
            $this->error("The product doesn't exist");
            return Command::FAILURE;
        }

        $this->info("Name: {$product->name}");
        $this->info("Description: {$product->description}");
        $this->info("Price: {$product->price}");
    }
}
