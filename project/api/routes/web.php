<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;


Route::post("/register", [AuthController::class, 'register'])->name("register");
Route::post("/login", [AuthController::class, "authenticate"])->name("login");


Route::get("/test", function (Request $request) {
    echo "JUST SOME TEST";

    return "JUST SOME TEST";
})->name("test")->middleware("auth:sanctum");

Route::post("/test", function (Request $request) {
    echo "JUST SOME POST TEST";

    return "JUST SOME POST TEST";
})->name("test")->middleware("auth:sanctum");




// sanctum
Route::get("/sanctum", function (Request $request) {
    echo "SANCTUM GET API";

    return "SANCTUM GET API";
})->middleware("auth:sanctum");

Route::post("/sanctum", function (Request $request) {
    echo "SANCTUM POST API";

    return "SANCTUM POST API";
})->middleware("auth:sanctum");

Route::get("/auth", function (Request $request) {
    echo "AUTH GET API";

    return "AUTH GET API";
})->middleware("auth");

Route::post("/auth", function (Request $request) {
    echo "AUTH POST API";

    return "AUTH POST API";
})->middleware("auth");
