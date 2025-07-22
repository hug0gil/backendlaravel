<?php

namespace App\Business\Services;

use Illuminate\Support\Facades\Crypt;

class EncryptService
{
    private $key;

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function encrypt(string $data)
    {
        return base64_encode($this->key . ":" . Crypt::encryptString($data));
    }

    public function decrypt(string $data)
    {
        $decodeData = base64_decode($data);

        [$key, $encrypted] = explode(":", $decodeData);

        if ($key !== $this->key) {
            throw new \Exception("Incorrect key");
        }

        return Crypt::decryptString($encrypted);
    }
}
