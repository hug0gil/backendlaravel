<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $products = Product::where("description", "LIKE", "%{$value}%")
            ->orWhere("name", "like", "%{$value}%")->get();


        return response()->json($products);
    }

    public function advancedSearch(Request $request)
    {
        $products = Product::where(function ($query) use ($request) {
            if ($request->input("name")) {
                $query->where("name", "like", "%{$request->input("name")}%");
            }
        })->where(function ($query) use ($request) {
            if ($request->input("desciption")) {
                $query->where("desciption", "like", "%{$request->input("desciption")}%");
            }
        })->where(function ($query) use ($request) {
            if ($request->input("price")) {
                $query->where("price", ">", $request->input("price"));
            }
        })->get();

        return response()->json($products);
    }

    public function join()
    {
        $products = Product::join("category", "product.category_id", "=", "category.id")
            ->select("product.*", "category.name as category")->get();

        return response()->json($products);
    }

    public function groupBy()
    {
        $result = Product::join("category", "product.category_id", "=", "category.id")
            ->select("category.id", "category.name", DB::raw("COUNT(product.id) as total"))
            ->groupBy("category.id", "category.name")->get();
        return response()->json($result);
    }
    // Hay que agrupar por los campos que seleccionamos
}
