<?php

use App\Http\Controllers\BackendController;
use App\Http\Controllers\QueriesController;
use Illuminate\Support\Facades\Route;

route::get("/test", function () {
    return "El backend funciona correctamente";
});

Route::get("/backend", action: [BackendController::class, "getAll"]);

Route::get("/backend/{id?}", [BackendController::class, "get"]);

Route::post("/backend", [BackendController::class, "create"]);

Route::put("/backend/{id}", [BackendController::class, "update"]);

Route::delete("/backend/{id}", [BackendController::class, "delete"]);

Route::get("/query", action: [QueriesController::class, "get"]);

Route::get("/query/{id}", action: [QueriesController::class, "getById"]);

Route::get("/query/method/names", action: [QueriesController::class, "getNames"]);

Route::get("/query/method/search/{name}/{price}", action: [QueriesController::class, "searchName"]);

Route::get("/query/method/searchString/{value}", action: [QueriesController::class, "searchString"]);
