<?php

namespace App\Business\Services;

class SingletonService
{

    public $value;

    public function __construct()
    {
        $this->value = rand(1, 1000);
    }
}
