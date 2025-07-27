<?php

use App\Business\Entities\ConceptEntity;

test('Correct creation', function () {
    $concept = new ConceptEntity(3, 20, 2);

    expect($concept->quantity)->toBe(3)
        ->and($concept->price)->toBe(20.0)
        ->and($concept->product_id)->toBe(2)
        ->and($concept->total)->toBe($concept->quantity * $concept->price);
});

test('Calculate total', function () {
    $concept = new ConceptEntity(3, 20, 2);

    expect($concept->calculateTotal())->toBe(60.0);
});
