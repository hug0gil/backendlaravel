<?php

use App\Business\Entities\ConceptEntity;
use App\Business\Entities\SaleEntity;

test('Correct creation', function () {
    $concept1 = new ConceptEntity(2, 50, 1);
    $concept2 = new ConceptEntity(1, 30, 2);

    $sale = new SaleEntity(1, "ejemplo@gmail.com", '2025-01-12 15:01:01', [$concept1, $concept2]);

    expect($sale->id)->toBe(1)
        ->and($sale->email)->toBe("ejemplo@gmail.com")
        ->and($sale->sale_date)->toBe('2025-01-12 15:01:01')
        ->and($sale->concepts)->toBeArray()
        ->and($sale->concepts)->toHaveCount(2);
});

test('Calculate total', function () {
    $concept1 = new ConceptEntity(2, 50, 1);
    $concept2 = new ConceptEntity(1, 30, 2);

    $sale = new SaleEntity(1, "ejemplo@gmail.com", '2025-01-12 15:01:01', [$concept1, $concept2]);

    expect($sale->total)->toBe(130.0);
});
