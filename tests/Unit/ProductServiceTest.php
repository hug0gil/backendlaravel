<?php

use App\Business\Entities\Taxes;
use App\Business\Services\ProductService;

test('Calculate IVA taxes', function () {
    $price = 100;

    $service = new ProductService();
    $result = $service->calculateIVA($price);

    expect($result)->toBe($price * (1 + Taxes::IVA));
});
