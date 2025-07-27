<?php

use App\Business\Services\CreateSaleService;
use App\Http\Requests\SaleRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

beforeEach(function () {
    $this->service = new CreateSaleService();
});

test('Sale creation successfully', function () {
    $product1 = Product::factory()->create(['price' => 100]);
    $product2 = Product::factory()->create(['price' => 50]);

    $request = new SaleRequest([
        "sale_date" => "2025-07-27 14:30:00",
        "email" => "hugogilb2005@gmail.com",
        "concepts" => [
            ["quantity" => 2, "product_id" => $product1->id],
            ["quantity" => 3, "product_id" => $product2->id]
        ]
    ]);

    $saleEntity = $this->service->create($request);

    $this->assertDatabaseHas("concept", [
        "sale_id" => $saleEntity->id,
        "product_id" => $product1->id,
        "quantity" => 2,
        "price" => 100.0
    ]);

    $this->assertDatabaseHas("concept", [
        "sale_id" => $saleEntity->id,
        "product_id" => $product2->id,
        "quantity" => 3,
        "price" => 50.0
    ]);
});

test('Fail the request validation', function () {
    $data = [
        'email' => '',
        'sale_date' => '',
        'concepts' => []
    ];

    $validator = Validator::make($data, (new SaleRequest())->rules());

    expect($validator->fails())->toBeTrue();
});
