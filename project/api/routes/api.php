<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use JacobFitzp\LaravelTiptapValidation\Facades\TiptapValidation;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/test", function (Request $request) {
    echo "jamoin";
    return json_encode($request->cookies->all());
});


// make a post route that takes a content: string in the body:
Route::post("/test", function (Request $request) {
    $content = $request->input("content");

    $rules = [
        'content' => [
            'required',
            TiptapValidation::content()
                ->whitelist()
                ->nodes("doc", "paragraph", "text", "heading", "blockquote", "codeBlock")
                ->marks("bold", "italic", "underline", "strike", "code"),
            TiptapValidation::containsText()
                ->between(0, 40),
        ]
    ];

    $data = [
        "content" => $content,
    ];

    $validator = Validator::make($data, $rules);

    if ($validator->fails()) {
        return "FAULS";
    } else {
        return "RIGHT";
    }


});

Route::post("/sanitize", function (Request $request, HtmlSanitizer $sanitizer) {

    $content = $request->input("content");


    function sanitize(array $data, HtmlSanitizerInterface $sanitizer) {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = $sanitizer->sanitize($value);
            } elseif (is_array($value)) {
                $data[$key] = sanitize($value, $sanitizer);
            }
        }

        return $data;
    }

    $sanitized = sanitize($content, $sanitizer);

    return $sanitized;
});


Route::middleware("auth:sanctum")->group(function () {
    Route::get("/sanctum", function (Request $request) {
        echo "SANCTUM GET API";
        return "SANCTUM GET API";
    });

    Route::post("/sanctum", function (Request $request) {
        echo "SANCTUM POST API";
        return "SANCTUM POST API";
    });
});

Route::middleware("auth")->group(function () {
    Route::get("auth", function (Request $request) {
        echo "AUTH GET API";
        return "AUTH GET API";
    });

    Route::post("auth", function (Request $request) {
        echo "AUTH POST API";
        return "AUTH POST API";
    });
});
