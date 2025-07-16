<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Symfony\Component\HttpFoundation\Response;

class QueriesController extends Controller
{
    public function get()
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function getById($id)
    {
        $products = Product::find($id);

        if (!$products) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }
        return response()->json($products);
    }

    public function getNames()
    {
        $products = Product::select("name")->orderBy("name", "asc")->get();

        if (!$products) {
            return response()->json(['message' => 'Product not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($products);
    }

    public function searchName(string $name, float $price)
    {
        $products = Product::where("name", $name)->where("price", ">", $price)->get();
        //$products = Product::where("name", $name)->where("price", ">", $price)->select("name")->get();

        return response()->json($products);
    }

    public function searchString(string $value)
    {
        $products = Product::where("name", "LIKE", "%{$value}%")->get();
        return response()->json($products);
    }
}
