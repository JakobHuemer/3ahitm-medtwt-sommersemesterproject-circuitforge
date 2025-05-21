<?php

use App\Enums\Rating;
use App\Enums\VersionType;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\VersionController;
use App\Models\Post;
use App\Models\Version;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::post("/register", [AuthController::class, 'register'])->name("register")
    ->middleware([HandlePrecognitiveRequests::class]);
Route::post("/login", [AuthController::class, "authenticate"])->name("login")
    ->middleware([HandlePrecognitiveRequests::class]);

Route::post("/logout", [AuthController::class, 'logout'])->name("logout");

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

// delete socials
Route::delete("/socials/{id}", [OAuthController::class, "removeSocialConnection"])
    ->middleware("auth:sanctum");


Route::get("/versions", VersionController::class);


// Post Resource
Route::apiResource("/posts", PostController::class)
//    ->middleware("auth:sanctum")
;

Route::get("/assets/{assetId}", [PostController::class, 'getAsset']);

Route::get("/posts/{post}/ratings", function (Post $post): int {
    return $post->getRating();
});

Route::get("/posts/{post}/ratings/{rating}", function (Post $post, Rating $rating) {
    $post->rate($rating);
});

Route::delete("/posts/{post}/ratings", function (Post $post) {
    $post->unrate();
});
