<?php

use App\Business\Services\EncryptService;

test('encrypts text and output is different, and decrypt and is the same', function () {
    $key = "clave";

    $encryptor = new EncryptService($key);

    $originalString = "estosevaaencriptar";

    $encryptedString = $encryptor->encrypt($originalString);

    expect($encryptedString)->not()->toBe($originalString);

    $decryptedString = $encryptor->decrypt($encryptedString);

    expect($decryptedString)->toBe($originalString);
});


test("Exception when the key is different for decryption", function () {
    $key1 = "clave";
    $key2 = "clavemala";

    $encryptor1 = new EncryptService($key1);
    $encryptor2 = new EncryptService($key2);

    $encryptedString = $encryptor1->encrypt("Prueba");

    expect(fn() => $encryptor2->decrypt($encryptedString))->toThrow(Exception::class);
});
