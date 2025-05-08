<?php

use App\Enums\VersionType;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OAuthController;
use App\Models\Version;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;


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


Route::get("/versions/{versionQuery}/{versionTypes?}",
    function (string $versionQuery,
              Request $request,
              ?string $versionTypes = null) {
        $versionTypesArray = $versionTypes
            ? explode(",", $versionTypes)
            : VersionType::values();

        $versions = Version::where("version", "LIKE", "%${versionQuery}%")
            ->whereIn("type", $versionTypesArray)
            ->take(20)
            ->get();

        return $versions;
    });
