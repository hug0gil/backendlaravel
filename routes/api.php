<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackendController;
use App\Http\Controllers\InfoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QueriesController;
use App\Http\Controllers\SaleController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\UpperCaseName;
use Illuminate\Support\Facades\Route;

route::get("/test", function () {
    //dd(config('jwt.secret'));
    return "El backend funciona correctamente";
});

Route::get("/backend", action: [BackendController::class, "getAll"])->middleware("checkvalue:4545,pato");

Route::get("/backend/{id?}", [BackendController::class, "get"]);

Route::post("/backend", [BackendController::class, "create"]);

Route::put("/backend/{id}", [BackendController::class, "update"]);

Route::delete("/backend/{id}", [BackendController::class, "delete"]);

Route::get("/query", action: [QueriesController::class, "get"]);

Route::get("/query/{id}", action: [QueriesController::class, "getById"]);

Route::get("/query/method/names", action: [QueriesController::class, "getNames"]);

Route::get("/query/method/search/{name}/{price}", action: [QueriesController::class, "searchName"]);

Route::get(uri: "/query/method/searchString/{value}", action: [QueriesController::class, "searchString"]);

Route::post(uri: "/query/method/advancedSearch", action: [QueriesController::class, "advancedSearch"]);

Route::get(uri: "/query/method/join", action: [QueriesController::class, "join"]);

Route::get(uri: "/query/method/groupBy", action: [QueriesController::class, "groupBy"]);

Route::apiResource("/product", ProductController::class)
    ->middleware(["jwt.auth", LogRequests::class]);
//Aplica un middleware para JWT y otro para los loggear información

// jwt.auth middleware sin el token no tiene permiso

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name("login");

//Creamos una agrupación y todos los endpoints que ponemos dentro pasan por el middleware

Route::middleware("jwt.auth")->group(function () {
    Route::get("/who", [AuthController::class, "who"]);
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::post("/refresh", [AuthController::class, "refresh"]);
});

Route::get("/info/message", [InfoController::class, "message"]);
Route::get("/info/tax/{id}", [InfoController::class, "iva"]);
Route::get("/info/encrypt/{data}", [InfoController::class, "encrypt"]);
Route::get("/info/decrypt/{data}", [InfoController::class, "decrypt"]);
Route::get("/info/encryptEmail/{data}", [InfoController::class, "encryptEmail"]);
Route::get("/info/encryptEmail2/{data}", [InfoController::class, "encryptEmail2"]);

Route::get("/info/singleton", [InfoController::class, "singleton"]);

Route::get("/api", [ApiController::class, "get"]);

// Modulo Ventas

Route::get("/sale", [SaleController::class, "get"]);
Route::post("/sale", [SaleController::class, "create"]);
