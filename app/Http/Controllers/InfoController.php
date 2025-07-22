<?php

namespace App\Http\Controllers;

use App\Business\Interfaces\MessageServiceInterface;
use App\Business\Services\EncryptService;
use App\Business\Services\ProductService;
use App\Business\Services\SingletonService;
use App\Business\Services\UserService;
use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class InfoController extends Controller
{

    public function __construct(
        protected ProductService $productService,
        protected EncryptService $encryptService,
        protected UserService $userService,
        protected MessageServiceInterface $hiService,
        protected SingletonService $singletonService
    ) {}

    public function message()
    {
        return response()->json($this->hiService->hi());
    }

    public function iva(int $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(["message" => "Product not found"], Response::HTTP_NOT_FOUND);
        }

        $priceWithIVA = $this->productService->calculateIVA($product->price);

        return response()->json(["price" => $product->price, "priceWithIVA" => $priceWithIVA], Response::HTTP_OK);
    }

    public function encrypt($data)
    {
        return response()->json($this->encryptService->encrypt($data));
    }

    public function decrypt($data)
    {
        return response()->json($this->encryptService->decrypt($data));
    }

    public function encryptEmail($id)
    {
        return response()->json($this->userService->encryptEmail($id));
    }

    public function singleton(SingletonService $singletonService2)
    {
        return response()->json($this->singletonService->value . " " . $singletonService2->value);
    }

    public function encryptEmail2($id)
    {
        $userService = app()->make(UserService::class);
        return response()->json($userService->encryptEmail($id));
    }
}
