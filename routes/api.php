<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QueriesController;
use App\Http\Middleware\CheckValueInHeader;
use App\Http\Middleware\LogRequests;
use App\Http\Middleware\UpperCaseName;
use Illuminate\Support\Facades\Route;

route::get("/test", function () {
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
    ->middleware([LogRequests::class]);
