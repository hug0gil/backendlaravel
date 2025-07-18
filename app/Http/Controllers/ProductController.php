<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query("per_page", 10); // Nº de productos por página
        $page = $request->query("page", 0); // Nº de la página
        $offset = $page * $perPage;
        // Para saber desde que posición mostrar multiplico el Nº de páginas * Nº de productos por página

        $products = Product::skip($offset)->take($perPage)->get();

        return response()->json($products);
    }

    //Control de request en controlador
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                "name" => ["Required", "string", "max:255"],
                "description" => ["Required", "string", "max:2000"],
                "price" => ["Required", "numeric"],
                "category_id" => ["Required", "exists:category,id"]
            ], [
                "name.required" => "The product name is required.",
                "name.string" => "The product name must be a string.",
                "name.max" => "The product name may not be greater than 255 characters.",

                "description.required" => "The product description is required.",
                "description.string" => "The product description must be a string.",
                "description.max" => "The product description may not be greater than 2000 characters.",

                "price.required" => "The price is required.",
                "price.numeric" => "The price must be a number.",

                "category_id.required" => "The category ID is required.",
                "category_id.exists" => "The selected category ID doesn't exist"
            ]);

            $product = Product::create($validatedData);

            return response()->json($product);
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    // Control de request con request personalizado
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            //dd($product->id); Dump and die

            $product->update(attributes: $request->validated());

            return response()->json(["message" => "Product updated successfuly!", "product" => $product], Response::HTTP_OK);
        } catch (ValidationException $e) {
            return response()->json(["error" => $e->errors()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                "message" => "Product eliminated!",
                "product" => $product
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json([
                "message" => "Error eliminating product",
                "error" => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
