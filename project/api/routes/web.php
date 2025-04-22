<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;


Route::post("/register", [AuthController::class, 'register'])->name("register");
Route::post("/login", [AuthController::class, "authenticate"])->name("login");
Route::post("/logout", [AuthController::class, 'logout'])->name("logout");

Route::post("/dry-register", [AuthController::class, 'dry_register'])->name("dry-register");

Route::get("/check/username");

Route::get("/me", function (Request $request) {
    return $request->user();
})->middleware("auth:sanctum");


Route::get("/test", function (Request $request) {
    return "ALL CAN ACCESS";
});


// login/register with socials
Route::get("/auth/{providerType}/redirect", [OAuthController::class, "oauthRedirectHandler",]);
Route::get("/auth/{providerType}/callback", [OAuthController::class, "oauthCallbackHandler",]);

// add socials
Route::get("/auth-add/{providerType}/redirect", [OAuthController::class, "addOauthRedirectHandler"])
    ->middleware("auth:sanctum");
Route::get("/auth-add/{providerType}/callback", [OAuthController::class, "addOAuthCallbackHandler"])
    ->middleware("auth:sanctum");

// get socials
Route::get("/socials", [OAuthController::class, "getOAuthProviders"])
    ->middleware("auth:sanctum");
