<?php

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->withoutMiddleware(\Tymon\JWTAuth\Http\Middleware\Authenticate::class);
});

test('Pagination test', function () {
    $user = User::factory()->create();

    $token = JWTAuth::fromUser($user);

    Product::factory()->count(5)->create();

    $response = $this->withHeader("Authorization", "Bearer $token")->getJson('/api/product?per_page=5&page=0');

    $response->assertStatus(Response::HTTP_OK)
        ->assertJsonCount(5)
        ->assertJsonStructure([
            '*' => ['id', 'name', 'price', 'category_id']
        ]);
    // Al poner el asterisco dice, todos los elementos de la response 
    // tienen que tener esta estructura

    $data = $response->json();
    expect(count($data))->toBe(5);
});

test('Creating product correctly', function () {
    $category = Category::factory()->create();

    $productData = [
        'name' => 'Product A',
        'price' => 999,
        'description' => 'lore ipsum',
        'category_id' => $category->id
    ];

    $response = $this->postJson(route('product.store'), $productData);
    // Hace post al endpoint basado en el nombre de la ruta product.store y pasa los datos

    $response->assertStatus(Response::HTTP_OK)->assertJsonStructure(
        ['id', 'name', 'price', 'category_id'] // Comprueba la response y la estructura
    );

    $this->assertDatabaseHas('product', $productData); // comprueba que exista en la bdd
});


test('Invalid data sent to create a product', function () {
    $invalidProductData = [
        'name' => '',
        'price' => 'texto',
        'description' => str_repeat('a', 3000),
        'category_id' => 3434
    ];

    $response = $this->postJson(route('product.store'), $invalidProductData);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['name', 'price', 'description', 'category_id']);
});

test('Update product successfully', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id
    ]);

    $newCategory = Category::factory()->create();

    $data = [
        'name' => 'Updated',
        'price' => 199.99,
        'description' => 'aaaaa',
        'category_id' => $newCategory->id
    ];

    $response = $this->putJson(route('product.update', $product), $data);

    $response->assertStatus(Response::HTTP_OK)
        ->assertJson([
            'message' => 'Product updated successfuly!',
            'product' => [
                'id' => $product->id,
                'name' => 'Updated',
                'price' => 199.99,
                'description' => 'aaaaa',
                'category_id' => $newCategory->id
            ]
        ]);

    $this->assertDatabaseHas('product', $data);
});


/* Si no quiero validar todo y quiero validar campos concretos usamos assertJsonFragment (no se pueden anidados)
    ->assertJsonFragment([
            'id' => $product->id,
            'name' => 'Updated',
            'price' => 199.99,
            'description' => 'aaaaa',
            'category_id' => $newCategory->id
        ]);
    */


test('Failed if not send category_id', function () {
    $category = Category::factory()->create();
    $product = Product::factory()->create([
        'category_id' => $category->id
    ]);

    //dd($category);

    $data = [
        'name' => 'Updated',
        'price' => 199.99,
        'description' => 'aaaaa',
    ];

    $response = $this->putJson(route('product.update', $product), $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJsonValidationErrors(['category_id']);
});

test('Remove product successfully', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson(route('product.destroy', $product));

    $response->assertStatus(Response::HTTP_OK)->assertJson(["message" => "Product eliminated!"]);

    //$this->assertDatabaseMissing("product", ["id" => $product->id]);
    $this->assertSoftDeleted("product", ["id" => $product->id]);

    // como tenemos las softdeletes activas el producto aparece en la bdd
});

test('Failed to remove because the product was not found', function () {
    //$product = Product::factory()->create();

    $response = $this->deleteJson(route('product.destroy', ['product' => 3]));
    $response->assertStatus(Response::HTTP_NOT_FOUND);
});
